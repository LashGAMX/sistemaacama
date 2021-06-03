var base_url = 'https://dev.sistemaacama.com.mx';
var table;
 $(document).ready(function (){
     table = $("#listaAsignar").DataTable ({
            "ordering": false,
            "language": {
                "lengthMenu": "# _MENU_ por pagina",
                "zeroRecords": "No hay datos encontrados",
                "info": "Pagina _PAGE_ de _PAGES_",
                "infoEmpty": "No hay datos encontrados",   
            }
        });
            $("#btnEdit").prop('disabled', true);
            $('#listaAsignar tbody').on( 'click', 'tr', function () { 
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
                // console.log("no selecionado");
                // selectedRow = false;
                // $("#btnEdit").prop('disabled', true);
            }
            else {
                table.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
                // console.log("Seleccionado");
                // selectedRow = true;
                // $("#btnEdit").prop('disabled', false);
            }
    });
        let idSolicitud = 0; 
        $('#listaAsignar tr').on('click', function(){
            let dato = $(this).find('td:first').html();
            idSolicitud = dato;
        });
        $('#btnImprimir').click( function () {
            alert("Imprimir"+" Id: "+idSolicitud);
            // window.location = base_url+"/admin/cotizacion/update/"+idSolicitud;
        } );
        $('#btnGenerar').click( function () {
            alert("Generar");
            // window.location = base_url+"/admin/cotizacion/exportPdfOrden/"+idSolicitud;
        });
        $('#btnPlanMuestreo').click( function () {
            alert("Plan de muestreo")
            // window.location = base_url+"/admin/cotizacion/exportPdfOrden/"+idSolicitud;
        });
 });