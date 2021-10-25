var base_url = 'https://dev.sistemaacama.com.mx';
var table;
var folioAsignar;
let idSolicitud = 0; 
let folio;

let tmp;

// function validacionFecha(horaE, horaR, btnI){
//     let horaEntrada = moment(document.getElementById(horaE).value, "YYYY-MM-DD HH:mm:ss");
//     let horaRecepcion = moment(document.getElementById(horaR).value, "YYYY-MM-DD HH:mm:ss");    

//     let btnIngresar = document.getElementById(btnI);
//     //let now = moment();

//     if(((moment(horaEntrada).isSame(horaRecepcion))) || moment(horaEntrada).isBefore(horaRecepcion)){
//         btnIngresar.setAttribute("disabled", true);
//         alert("La hora y/o fecha de entrada no puede ser inferior o igual a la hora de recepción");
//     }else{        
//         btnIngresar.disabled = false;
//     }

//     if(isNaN(horaRecepcion)){        
//         btnIngresar.disabled = true;
//     }

//     if(isNaN(horaEntrada)){        
//         btnIngresar.disabled = true;
//     }    
// }
//function 
// var data = new Array();
function buscarFolio(){
    $.ajax({
        type: "POST",
        url: base_url + '/admin/ingresar/buscarFolio',
        data: {
            folioSol: $("#folioSol").val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            //data = response;                         
            $("#idSol").val(response.model.Id_solicitud);
            $("#folio").val(response.model.Folio_servicio);
            $("#descarga").val(response.model.Descarga);
            $("#cliente").val(response.model.Nombres);
            $("#empresa").val(response.model.Empresa);
        }
    });
}
function setIngresar(){
    $.ajax({
        type: "POST",
        url: base_url + '/admin/ingresar/setIngresar',
        data: {
            idSol: $("#idSol").val(),
            folio: $("#folio").val(),
            descarga: $("#descarga").val(),
            cliente: $("#cliente").val(),
            empresa: $("#empresa").val(),
            ingreso: "Establecido",
            horaEntrada: $("#hora_recepcion1"),
            
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
        }
    });
}

