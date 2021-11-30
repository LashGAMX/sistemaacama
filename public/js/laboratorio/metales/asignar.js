$(document).ready(function () {
    table = $('#tableAsignar').DataTable({        
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        }
    });
});

//Múltiple selección
$(document).ready(function() {
    var table = $('#tableAsignar').DataTable(); 
    $('#tableAsignar tbody').on( 'click', 'tr', function () {                 
        $(this).toggleClass('selected'); } 
    ); 
    
    $('#button').click( function () { 
        alert( table.rows('.selected').data().length +' row(s) selected' ); } 
    );
});