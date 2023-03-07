
var table;
// var selectedRow = false;
var idCot = 0; 
$(document).ready(function () {
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

    // switch ($("#rol").val()) {
    //     case "13":
    //         $("#btnCreate").hide()
    //         $("#btnGenFolio").hide()
    //         return 
    //     default:
    //         return
    // }
    
    $("#btnBuscar").click( function(){
        window.location = base_url+ "/admin/cotizacion/buscarFecha/"+ $("#inicio").val()+ "/" + $("#fin").val();
    });
 
    $('#tablaSolicitud tr').on('click', function(){
        let dato = $(this).find('td:first').html();
        idCot = dato;
      });
    $('#btnCreate').click( function () {
        // alert(idCot);
        window.location = base_url+"/admin/cotizacion/solicitud/create/"+idCot;
    } );

    $('#btnCreateSinCot').click( function () {
        // alert(idCot);
        window.location = base_url+"/admin/cotizacion/solicitud/createSinCot";
    });

    $('#btnEdit').click( function () {        
        window.location = base_url+"/admin/cotizacion/solicitud/update/"+idCot;
    } );
    $('#btnImprimir').click( function () {
        // alert("Imprimir PDF");
        window.open(base_url+"/admin/cotizacion/solicitud/exportPdfOrden/"+idCot);
        //window.location = base_url+"/admin/cotizacion/solicitud/exportPdfOrden/"+idCot;
    } ); 

    $('#btnDuplicar').click(function(){                
        window.location = base_url + "/admin/cotizacion/solicitud/duplicarSol/"+idCot;
    });

    $('#btnGenFolio').click( function () {
         //alert("Imprimir GenerarFolio");
         let element = [];
         let ult = 0;
         $.ajax({
            url: base_url + '/admin/cotizacion/solicitud/setGenFolio', //archivo que recibe la peticion
            type: 'POST', //método de envio
            data: {
                idCot: idCot,
                _token: $('input[name="_token"]').val(),
            }, 
            dataType: 'json',
            async: false,
            success: function (response) { 
                console.log(response);
                if(response.sw == true)
                {
                    swal("success","Esta solicitud ya tiene codigos registrados"); 
                }else{
                    swal("success","Codigos creados satisfactoriamente");
                }
            }
        });

    });
});