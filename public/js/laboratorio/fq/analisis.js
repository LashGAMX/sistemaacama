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
});