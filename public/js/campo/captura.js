var base_url = 'https://dev.sistemaacama.com.mx';

// var selectedRow = false;
$(document).ready(function () {
    $("#datosGenerales-tab").click();
    datosGenerales();
});

function datosGenerales()
{
    table = $("#materialUsado").DataTable ({
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",   
        },
        scrollY:        '15vh',
        scrollCollapse: true,
        paging:         false
    });
    table = $("#factorDeConversion").DataTable ({
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",   
        },
        scrollY:        '15vh',
        scrollCollapse: true,
        paging:         false
    });
}