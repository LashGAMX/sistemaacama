var base_url = 'https://dev.sistemaacama.com.mx';
var table;
// var selectedRow = false;
$(document).ready(function () {
    var idCot = 0; 
    table = $('#tablaSolicitud').DataTable({
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",   
        }
    });
    $("#btnEdit").prop('disabled', true);
    $('#tablaSolicitud tbody').on( 'click', 'tr', function () { 
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
            // console.log("no selecionado");
            // selectedRow = false;
            $("#btnEdit").prop('disabled', true);
            idCot = 0;
        }
        else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            // console.log("Seleccionado");
            // selectedRow = true;
            $("#btnEdit").prop('disabled', false);
        }
    } );
 
    $('#tablaSolicitud tr').on('click', function(){
        let dato = $(this).find('td:first').html();
        idCot = dato;
      });
    $('#btnCreate').click( function () {
        // alert(idCot);
        window.location = base_url+"/admin/cotizacion/solicitud/create/"+idCot;
    } );
    $('#btnEdit').click( function () {
        window.location = base_url+"/admin/cotizacion/solicitud/update/"+idCot;
    } );
    $('#btnImprimir').click( function () {
        // alert("Imprimir PDF");
        window.location = base_url+"/admin/cotizacion/solicitud/exportPdfOrden/"+idCot;
    } );
});