$(document).ready(function () {
    table = $('#tableObservacion').DataTable({        
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        }
    });
});

//Multiple selección
$(document).ready(function() {
    var table = $('#tableObservacion').DataTable(); 
    $('#tableObservacion tbody').on( 'click', 'tr', function () { 
        console.log("Seleccionaste la fila");
        
        $(this).toggleClass('selected'); } 
    ); 
    
    $('#button').click( function () { 
        alert( table.rows('.selected').data().length +' row(s) selected' ); } 
    ); 
});