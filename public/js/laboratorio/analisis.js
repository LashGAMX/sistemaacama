var base_url = "https://dev.sistemaacama.com.mx";

$(document).ready(function () {
    table = $('#tableAnalisis').DataTable({        
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        }
    });

    busquedaAnalisis();
});

function busquedaAnalisis(idSol){
    let idSolicitud = document.getElementById(idSol);    
    //console.log("idSolicitud: " + idSolicitud);

    $.ajax({
        type: "GET",
        url: base_url + "/admin/laboratorio/analisis/datos",
        data: "idSolicitud",
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response.model[0]);
        }
    });
};