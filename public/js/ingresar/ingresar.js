var base_url = 'https://dev.sistemaacama.com.mx';
var table;
var folioAsignar;
let idSolicitud = 0; 
let folio;

let tmp;

function validacionFecha(horaE, horaR, btnI){
    let horaEntrada = moment(document.getElementById(horaE).value, "YYYY-MM-DD HH:mm:ss");
    let horaRecepcion = moment(document.getElementById(horaR).value, "YYYY-MM-DD HH:mm:ss");    

    let btnIngresar = document.getElementById(btnI);
    //let now = moment();

    if(((moment(horaEntrada).isSame(horaRecepcion))) || moment(horaEntrada).isBefore(horaRecepcion)){
        btnIngresar.setAttribute("disabled", true);
        alert("La hora y/o fecha de entrada no puede ser inferior o igual a la hora de recepción");
    }else{        
        btnIngresar.disabled = false;
    }

    if(isNaN(horaRecepcion)){        
        btnIngresar.disabled = true;
    }

    if(isNaN(horaEntrada)){        
        btnIngresar.disabled = true;
    }    
}

function setIngresar(){          
    let btnIngresar = document.getElementById("btnIngresar");
    let horaRecepcion = document.getElementById("hora_recepcion1");
    let horaEntrada = document.getElementById("hora_entrada");    

    if(this.tmp === undefined){
        $.ajax({
            type: "POST",
            url: base_url + '/admin/ingresar/ingresar',
            data: {
                folio: $("#folio").val(),
                descarga: $("#descarga").val(),
                cliente: $("#cliente").val(),
                empresa: $("#empresa").val(),
                ingreso: "Establecido",
                horaEntrada: moment($("#hora_entrada").val()).format("YYYY-MM-DD HH:mm:ss"),
                idSolicitud: $("#numSol").val()
            },
            dataType: "json",
            async: false,
            success: function (response) {            
                console.log(response);
            }
        });
    }else{
        $.ajax({
            type: "POST",
            url: base_url + '/admin/ingresar/ingresar',
            data: {
                folio: $("#folio").val(),
                descarga: $("#descarga").val(),
                cliente: $("#cliente").val(),
                empresa: $("#empresa").val(),
                ingreso: "Establecido",
                horaEntrada: tmp
            },
            dataType: "json",
            async: false,
            success: function (response) {            
                //console.log(response);
            }
        });
    }

    horaRecepcion.disabled = true;
    horaEntrada.disabled = true;
    btnIngresar.disabled = true;
}

window.addEventListener("load", function(){        
    let texto;
    let folio = document.getElementById("folio");
    let descarga = document.getElementById("descarga");
    let cliente = document.getElementById("cliente");
    let observacion = document.getElementById("observaciones");
    let empresa = document.getElementById("empresa");
    let horaRecepcion = document.getElementById("hora_recepcion");
    let horaRecepcion1 = document.getElementById("hora_recepcion1");
    let horaEntrada = document.getElementById("hora_entrada");    
    let mensaje = document.getElementById("mensajeBusqueda");
    let fechaFin = this.document.getElementById("f_fin");
    let fechaConformacion = document.getElementById("f_con");
    let procedencia = document.getElementById("procedencia");
    let now;
    let btnIngresar = document.getElementById("btnIngresar");
    let numeroSolicitud = document.getElementById("numSol");
    

    if(texto === undefined){
        horaRecepcion1.disabled = true;
        horaEntrada.disabled = true;
        btnIngresar.disabled = true;
    }

    document.getElementById("texto").addEventListener("keyup", function(){
        texto = document.getElementById("texto").value;        
        horaRecepcion.value = "";        

        if($("#texto").val().length >= 8){
            $.ajax({
                url: base_url + '/admin/ingresar/buscador',
                type: 'GET',
                data: {
                    texto:texto,
                    _token: $('input[name="_token"]').val()
                },
                dataType: 'json',
                async: false,
                success: function (response) {
                    if(texto.length != 0){
                        if(response.solicitud !== null){                            
                            horaEntrada.disabled = false;
                            horaRecepcion1.disabled = false;
                            folio.value = response.solicitud.Folio_servicio;
                            descarga.value = response.solicitud.Descarga;
                            cliente.value = response.solicitud.Empresa;
                            observacion.value = response.solicitud.Observacion;
                            empresa.value = response.solicitud.Nombres;
                            numeroSolicitud.value = response.solicitud.Id_solicitud;

                            //Establece la fecha de fin del muestreo ya sea de siralab o general******************                            
                            if(response.solicitudes.Siralab == 0){  //SI ES GENERAL
                                console.log("Generales");
                            }else{  //SI ES DE SIRALAB
                                $.ajax({
                                    type: 'GET',
                                    url: base_url + '/admin/ingresar/siralabFecha',
                                    data: {                                        
                                        sucursal: response.solicitudes.Id_sucursal
                                    },
                                    
                                    dataType: "json",
                                    async: false,
                                    success: function (response) {                                        
                                        fechaFin.value = moment(response.siralab.F_termino).format("DD/MM/YYYY hh:mm:ss a");                                                                                
                                    }
                                });
                            }
                            //Fin del establecimiento de la fecha de fin del muestreo*****************************

                            if(response.model.Hora_entrada !== null){
                                //console.log("La hora de entrada no está vacía");
                                tmp = response.model.Hora_entrada;
                            }

                            if($("#hora_recepcion").val().length != 0){
                                horaRecepcion.value = moment(response.model.created_at).format("DD/MM/YYYY hh:mm:ss a");
                            }else{
                                horaRecepcion.value = moment(response.model.created_at).format("DD/MM/YYYY hh:mm:ss a");
                            }                            
                            
                            //Recupera la fecha de conformación*****************************************************                            
                            $.ajax({
                                type: 'GET',
                                url: base_url + '/admin/ingresar/fechaConformacion',
                                data: {                                    
                                    idSolicitud: response.solicitud.Id_solicitud
                                },
                                
                                dataType: "json",
                                async: false,
                                success: function (response) {
                                    //Calcula el tamaño del arreglo JSON obtenido
                                    let length = Object.keys(response.fechaC).length;
                                    length -= 1;

                                    //Obtiene el valor de la última fecha de muestreo ingresada y se le suman 30min a la hora
                                    let ultimaFecha = moment(response.fechaC[length].Fecha, "YYYY-MM-DDTHH:mm:ss");
                                    ultimaFecha.add(30, 'm');                                    
                                    fechaConformacion.value = ultimaFecha.format("DD/MM/YYYY hh:mm:ss a");
                                }
                            });
                            //Fin de recuperación de la fecha de conformación***************************************
                            
                            //Recupera el estado de la república desde dónde vienen las muestras con previa cotización
                            $.ajax({
                                type: 'GET',
                                url: base_url + '/admin/ingresar/procedencia',
                                data: {                                    
                                    idCotizacion: response.solicitud.Id_cotizacion
                                },
                                
                                dataType: "json",
                                async: false,
                                success: function (response) {
                                    procedencia.value = response.estado.Nombre;
                                }
                            });
                            //Fin de la recuperación del estado de la república con previa cotización*****************

                            now = moment();
                            //-----------------------------------------------------------------------------------

                            if($("#hora_entrada").val().length != 0){                            
                                btnIngresar.disabled = true;
                            }else{
                                btnIngresar.disabled = true;
                            }                            

                            if((moment(horaEntrada, "YYYY-MM-DDTHH:mm:ss").isBefore(now)) || (moment(horaEntrada, "YYYY-MM-DDTHH:mm:ss").isSame(now))){
                                alert("La hora y/o fecha de entrada no puede ser inferior o igual a la hora de recepción");
                                
                                console.log("Valor de horaEntrada: " + $("#hora_entrada").val());
                                
                                btnIngresar.disabled = true;
                            }                            
                                                        
                            mensaje.innerHTML = "";
                        
                        }else{                    
                            folio.value = "";
                            descarga.value = "";
                            cliente.value = "";
                            observacion.value = "";
                            empresa.value = "";
                            horaRecepcion.value = "";
                            horaRecepcion1.value = "";
                            horaEntrada.value = "";
                            fechaFin.value = "";
                            fechaConformacion.value = "";
                            procedencia.value = "";
                            mensaje.innerHTML = "No se encontraron registros";
                            horaEntrada.disabled = true;
                            horaRecepcion1.disabled = true;
                            btnIngresar.disabled = true;                      
                        }
                    }
                },
            });
        }else{
            folio.value = "";
            descarga.value = "";
            cliente.value = "";
            observacion.value = "";
            empresa.value = "";
            horaRecepcion.value = "";
            horaRecepcion1.value = "";
            horaEntrada.value = "";
            fechaFin.value = "";
            fechaConformacion.value = "";
            procedencia.value = "";
            mensaje.innerHTML = "";
            horaEntrada.disabled = true;
            horaRecepcion1.disabled = true;
            btnIngresar.disabled = true;
        }
    }); 
});