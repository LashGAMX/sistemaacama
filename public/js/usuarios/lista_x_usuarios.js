
$(document).ready(function () {

    table = $('#tableUser').DataTable({        
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        }
    });    
    $('#tableUser tbody').on( 'dblclick', 'tr', function () {
        let dato = $(this).find('td:first').html();
        window.location = base_url+"/admin/usuarios/parametroUser/"+dato
    });
    
});