var base_url = 'https://dev.sistemaacama.com.mx';
var table;
 $(document).ready(function (){
    listaSolicitudes();
    solicitudGenerada();
    puntoOrden();
 });
function puntoOrden()
{
    table = $("#muestreadorAsignado").DataTable ({
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",   
        },
        scrollY:        '30vh',
        scrollCollapse: true,
        paging:         false
    });
}
 function listaSolicitudes()
 {
    table = $("#listaAsignar").DataTable ({
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",   
        },
        scrollY:        '30vh',
        scrollCollapse: true,
        paging:         false
    });
        $("#btnEdit").prop('disabled', true);
        $('#listaAsignar tbody').on( 'click', 'tr', function () { 
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
        generar(idSolicitud);
        // window.location = base_url+"/admin/cotizacion/exportPdfOrden/"+idSolicitud;
    });
    $('#btnPlanMuestreo').click( function () {
        alert("Plan de muestreo")
        // window.location = base_url+"/admin/cotizacion/exportPdfOrden/"+idSolicitud;
    });
 }
 function solicitudGenerada()
 {
    table = $("#solicitudGenerada").DataTable ({
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",   
        },
        scrollY:        '30vh',
        scrollCollapse: true,
        paging:         false
    });
    
        $("#btnEdit").prop('disabled', true);
        $('#solicitudGenerada tbody').on( 'click', 'tr', function () { 
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
 }

 function generar(idSolicitud)
{
    $.ajax({
        url: base_url + '/admin/campo/asignar/generar', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            idSolicitud:idSolicitud,
            _token: $('input[name="_token"]').val(),
          },
        dataType: 'json', 
        async: false, 
        success: function (response) {
          console.log(response);
        }
    }); 
}