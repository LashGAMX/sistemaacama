
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
        console.log("Valor de tmp: " + tmp);
        console.log("Dentro de if tmp");

        $.ajax({
            type: "POST",
            url: base_url + '/admin/ingresar/ingresar',
            data: {
                folio: $("#folio").val(),
                descarga: $("#descarga").val(),
                cliente: $("#cliente").val(),
                empresa: $("#empresa").val(),
                horaEntrada: moment($("#hora_entrada").val()).format("YYYY-MM-DD HH:mm:ss")            
            },
            dataType: "json",
            async: false,
            success: function (response) {            
                console.log(response);
                //horaRecepcion.disabled = true;
                //horaEntrada.disabled = true;
                btnIngresar.disabled = true;
            }
        });
    }else{
        console.log("Valor de tmp: " + tmp);
        console.log("Dentro de else tmp");

        $.ajax({
            type: "POST",
            url: base_url + '/admin/ingresar/ingresar',
            data: {
                folio: $("#folio").val(),
                descarga: $("#descarga").val(),
                cliente: $("#cliente").val(),
                empresa: $("#empresa").val(),
                horaEntrada: tmp
            },
            dataType: "json",
            async: false,
            success: function (response) {            
                console.log(response);
                //horaRecepcion.disabled = true;
                //horaEntrada.disabled = true;
                btnIngresar.disabled = true;
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
    let now;
    let btnIngresar = document.getElementById("btnIngresar");
    

    if(texto === undefined){
        horaRecepcion1.disabled = true;
        horaEntrada.disabled = true;
        btnIngresar.disabled = true;
    }

    document.getElementById("texto").addEventListener("keyup", function(){
        texto = document.getElementById("texto").value;        
        horaRecepcion.value = "";
        
        console.log("Tamaño de input texto: " + $("#texto").val().length);

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
                    //console.log("Dentro de función Ajax");                
                    console.log(response);                

                    if(texto.length != 0){
                        if(response.solicitud !== null){                        
                            //-----------------------------Establecimiento de campos-----------------------------
                            horaEntrada.disabled = false;
                            horaRecepcion1.disabled = false;
                            folio.value = response.solicitud.Folio_servicio;
                            descarga.value = response.solicitud.Descarga;
                            cliente.value = response.solicitud.Empresa;
                            observacion.value = response.solicitud.Observacion;
                            empresa.value = response.solicitud.Nombres;

                            if(response.model.Hora_entrada === null){
                                console.log("La hora de entrada está vacía");
                            }else{
                                console.log("La hora de entrada no está vacía");                            
                                tmp = response.model.Hora_entrada;
                                console.log("Valor de tmp en establecimiento de campos: " + tmp);              
                            }

                            if($("#hora_recepcion").val().length != 0){
                                horaRecepcion.value = moment(response.model.created_at).format("DD/MM/YYYY hh:mm:ss a");
                            }else{
                                horaRecepcion.value = moment(response.model.created_at).format("DD/MM/YYYY hh:mm:ss a");
                            }

                            /*if($("#hora_entrada").val().length != 0){
                                horaEntrada.value = moment(response.model.Hora_entrada, "YYYY-MM-DD HH:mm:ss").format("YYYY-MM-DDTHH:mm:ss");                            
                                horaEntrada.disabled = true;
                            }else{
                                horaEntrada.disabled = false;                            
                                horaEntrada.value = moment(response.model.Hora_entrada, "YYYY-MM-DD HH:mm:ss").format("YYYY-MM-DDTHH:mm:ss");
                            }*/
                            
                            now = moment();
                            //-----------------------------------------------------------------------------------

                            if($("#hora_entrada").val().length != 0){                            
                                btnIngresar.disabled = true;                            
                                //horaEntrada.disabled = true;
                                //horaRecepcion1.disabled = true;
                            }else{
                                //horaEntrada.disabled = false;
                                //horaRecepcion1.disabled = false;
                                btnIngresar.disabled = true;
                            }

                            if((moment(horaEntrada).isBefore(now)) || (moment(horaEntrada).isSame(now))){
                                alert("La hora y/o fecha de entrada no puede ser inferior o igual a la hora de recepción");
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
                            mensaje.innerHTML = "No se encontraron registros";
                            horaEntrada.disabled = true;
                            horaRecepcion1.disabled = true;
                            btnIngresar.disabled = true;                      
                        }
                    }else{
                            folio.value = "";
                            descarga.value = "";
                            cliente.value = "";
                            observacion.value = "";
                            empresa.value = "";
                            horaRecepcion.value = "";
                            horaRecepcion1.value = "";
                            horaEntrada.value = "";                        
                            mensaje.innerHTML = "";
                            horaEntrada.disabled = true;
                            horaRecepcion1.disabled = true;
                            btnIngresar.disabled = true;
                    }                
                },
            });          
        }
    }); 
});