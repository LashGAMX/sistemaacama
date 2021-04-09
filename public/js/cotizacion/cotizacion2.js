var table;
$(document).ready(function () {
    table = $('#tablaCotizacion').DataTable({
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",   
        }
    });
    
    $('#tablaCotizacion tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );
 
    let idFolio = 0;
    $('#tablaCotizacion tr').on('click', function(){
        let dato = $(this).find('td:first').html();
        idFolio = dato;
      });
    $('#btnEdit').click( function () {
        
    } );
});