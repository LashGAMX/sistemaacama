$(document).ready(function () {
    table = $('.tableObservacion').DataTable({        
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
    var table = $('#primeraTabla').DataTable();    

    $('#primeraTabla tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
        $('#segundaTabla tbody tr').toggleClass('selected');                
    });

    $('#segundaTabla tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
        $('#primeraTabla tbody tr').toggleClass('selected');
    });
    
    $('#button').click( function () { 
        alert( table.rows('.selected').data().length +' row(s) selected');
    });
});