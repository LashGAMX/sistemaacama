var base_url = 'https://dev.sistemaacama.com.mx';
var table;
// var selectedRow = false;
$(document).ready(function () {
    table = $('#listaServicio').DataTable({
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",   
        }
    });
    $("#btnEdit").prop('disabled', true);
    $('#listaServicio tbody').on( 'click', 'tr', function () { 
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
            // console.log("no selecionado");
            // selectedRow = false;
            $("#btnEdit").prop('disabled', true);
        }
        else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            // console.log("Seleccionado");
            // selectedRow = true;
            $("#btnEdit").prop('disabled', false);
        }
    } );
 
    let idCot = 0; 
    $('#listaServicio tr').on('click', function(){
        let dato = $(this).find('td:first').html();
        idCot = dato;
      });
    $('#btnCapturar').click( function () {
        alert("Capturar");
        window.location = base_url+"/admin/campo/captura";
    } );
    $('#btnHojaCampo').click( function () {
        alert("Hoja de campo");
        // window.location = base_url+"/admin/cotizacion/exportPdfOrden/"+idCot;
    });
    $('#btnBitacora').click( function () {
        alert("Bitacora")
        // window.location = base_url+"/admin/cotizacion/exportPdfOrden/"+idCot;
    });
});