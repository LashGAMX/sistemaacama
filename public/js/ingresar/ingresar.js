var base_url = 'https://dev.sistemaacama.com.mx';
var table;
var folioAsignar;
let idSolicitud = 0; 
let folio;

$(document).ready(function (){        
    table = $('#tablaSolicitud').DataTable({
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",   
        }
    });
    
    $('#tablaSolicitud tbody').on( 'click', 'tr', function () {          
        //console.log("Dentro de tablaSolicitud");        
        
        if ( $(this).hasClass('selected') ) {
            //console.log("Dentro de selected1");
            $(this).removeClass('selected');
        }
        else {            
            //console.log("Dentro de selected1 else");
            table.$('tr.selected').removeClass('selected');            
            $(this).addClass('selected');              
        }
        
        //$('#tablaSolicitud tr').on('click', function(){
            //console.log("Dentro de tablaSolicitud2");
            let dato = $(this).find('td:eq(0)').html();            
            let dato2 =$(this).find('td:eq(1)').html();
            folio = dato2;
            idSolicitud = dato;
        //});                
        
        //console.log("Valor de idSolicitud: " + idSolicitud);
        //console.log("Valor de folio: " + folio);        
        generarDatos(idSolicitud, folio);
    } );
 
    let idCot = 0; 
    $('#tablaSolicitud tr').on('click', function(){
        let dato = $(this).find('td:first').html();
        idCot = dato;        
    });
});  

function mostrarDatos()
 {
    tableAsignar = $("#tablaDatos").DataTable ({
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",   
        },
        scrollY:        '30vh',
        scrollCollapse: true,
        paging:         false
    });    
                    
    $('#tablaDatos tbody').on( 'click', 'tr', function () { 
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');            
        }
        else {
            tableAsignar.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');        
        }
        } );  
}

function validacionFecha(horaE, horaR, btnI){
    let horaEntrada = moment(document.getElementById(horaE).value, "YYYY-MM-DD HH:mm:ss");
    let horaRecepcion = moment(document.getElementById(horaR).value, "DD/MM/YYYY HH:mm:ss A");
    let btnIngresar = document.getElementById(btnI);

    if((moment(horaEntrada)).isBefore(horaRecepcion)){
        btnIngresar.setAttribute("disabled", true);
        console.log("dentro de if en validacionFecha");
        alert("La hora de entrada no puede ser inferior a la hora de recepción");
    }else{
        btnIngresar.setAttribute("disabled", false);
        console.log("dentro de else en validacionFecha");
    }
}

function setIngresar(){
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
            swal("Ingreso!", "Ingreso guardado correctamente!", "success");
        }
    });
}

window.addEventListener("load", function(){
    let texto;
    let folio = document.getElementById("folio");
    let descarga = document.getElementById("descarga");
    let cliente = document.getElementById("cliente");
    let empresa = document.getElementById("empresa");
    let horaRecepcion = document.getElementById("hora_recepcion");
    let horaEntrada = document.getElementById("hora_entrada");
    let mensaje = document.getElementById("mensajeBusqueda");
    let btnIngresar = document.getElementById("btnIngresar");
          
    //btnIngresar.setAttribute("disabled", true);

    document.getElementById("texto").addEventListener("keyup", function(){
        texto = document.getElementById("texto").value;
                
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
                        folio.value = response.solicitud.Folio_servicio;
                        descarga.value = response.solicitud.Descarga;
                        cliente.value = response.solicitud.Empresa;
                        empresa.value = response.solicitud.Nombres;
                        horaRecepcion.value = moment(response.model.created_at).format("DD/MM/YYYY HH:mm:ss A");
                        horaEntrada.value = moment(response.model.Hora_entrada, "YYYY-MM-DD HH:mm:ss").format("YYYY-MM-DDTHH:mm:ss");
                        mensaje.innerHTML = "";

                        //btnIngresar.setAttribute("disabled", true);
                        console.log("dentro de if en response.solicitud");
                    }else{                    
                        folio.value = "";
                        descarga.value = "";
                        cliente.value = "";
                        empresa.value = "";
                        horaRecepcion.value = "";
                        horaEntrada.value = "";
                        mensaje.innerHTML = "No se encontraron registros";
                        
                        btnIngresar.setAttribute("disabled", true);
                        console.log("dentro de else en response.solicitud");
                    }
                }else{
                        folio.value = "";
                        descarga.value = "";
                        cliente.value = "";
                        empresa.value = "";
                        horaRecepcion.value = "";
                        horaEntrada.value = "";
                        mensaje.innerHTML = "";
                        btnIngresar.setAttribute("disabled", true);
                        console.log("dentro de else en texto.length");
                }                
            },
        });        
    });
});