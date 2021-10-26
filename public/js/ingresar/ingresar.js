var base_url = 'https://dev.sistemaacama.com.mx';

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
    console.log("Click en btnIngresar");
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
            horaEntrada: $("#hora_recepcion1").val(), 
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
        }
    });
}

