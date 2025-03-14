var table;
var idCot

$(document).ready(function () {
    $('#btnBuscar').click( function (){
        console.log('click btnBuscar')
        window.location = base_url+ "/admin/cotizacion/buscarFecha/"+ $("#inicio").val()+ "/" + $("#fin").val();
    });

      
    $('#btnPrint').click( function () {
        window.open(base_url+"/admin/cotizacion/exportPdfOrden/"+idCot);
        //window.location = base_url+"/admin/cotizacion/exportPdfOrden/"+idCot;
    } );
    $('#btnPrint2').click( function () {
        window.open(base_url+"/admin/cotizacion/exportPdfCotizacion/"+idCot);
    } );
    $('#btnShow').click( function () {
        window.location = base_url+"/admin/cotizacion/show/"+idCot;
    } );

    $('#btnDuplicar').click(function(){                
        window.location = base_url + "/admin/cotizacion/duplicarCot/"+idCot;
    });

    $("#btnEdit").prop('disabled', true);


    _initTable()
});

function _initTable()
{


    table = $('#tablaCotizacion').DataTable({
        "ordering": false,
        paging: false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",   
        },
        
    });
    $('#tablaCotizacion tr').on('click', function(){
        let dato = $(this).find('td:first').html();
        idCot = dato;
      });
      $('#tablaCotizacion tbody').on( 'click', 'tr', function () { 
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
            $('#btnEdit').click( function (){
                $.ajax({
                    url: base_url + '/admin/cotizacion/comprobarEdicion', //archivo que recibe la peticion
                    type: 'POST', //método de envio
                    data: {
                      id: idCot,
                        _token: $('input[name="_token"]').val(),
                    },
                    dataType: 'json',
                    async: false,
                    success: function (response) {
                        console.log(response)
                        if(response.sw == true)
                        {
                            alert("No puedes editar esta cotizacion porque ya se encuentra activa en orden de servicio");
                        }else{
                            window.location = base_url+"/admin/cotizacion/update/"+idCot;
                        }
                    }
                  });
            });
        
        }
    } );

    
 
}