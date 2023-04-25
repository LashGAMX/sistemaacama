var idSol;    
$(document).ready(function () {

    table = $('#tableCadena').DataTable({        
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        }
    });    
    $('#tableCadena tbody').on( 'dblclick', 'tr', function () {
        let dato = $(this).find('td:first').html();
        idSol = dato;
      window.location = base_url+"/admin/supervicion/cadena/detalleCadena/"+dato
    });
    
    $('#tableCadena tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            let dato = $(this).find('td:first').html();
            idSol = dato;
        }
    } );

    $('#btnCadena').click(function(){
        window.location = base_url + "/admin/informes/exportPdfCustodiaInterna/"+idSol;
    });
 
});