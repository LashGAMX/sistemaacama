
var table;
// var selectedRow = false;
var idCot = 0; 
$(document).ready(function () {
    table = $('#tablaSolicitud').DataTable({
        "ordering": false,
        paging: false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",   
        }
    });
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

    $("#btnBuscar").click( function(){

        let tablaDoc = document.getElementById('divTabla')
        let table = ""
        $.ajax({
            url: base_url + '/admin/cotizacion/solicitud/buscar', //archivo que recibe la peticion
            type: 'POST', //método de envio
            data: {
                nombre: $('#nombre').val(),
                folio: $('#folio').val(),
                norma: $('#norma').val(),

                _token: $('input[name="_token"]').val(),
            }, 
            dataType: 'json',
            async: false,
            success: function (response) { 
            console.log(response);

            table += ' <table class="table table-sm" id="tablaSolicitud">';
            table += '    <thead class="thead-dark">';
            table += '        <tr>';
            table += '            <th>Id</th>';
            table += '            <th>Estado</th>';
            table += '            <th>Folio servicio</th>';
            table += '            <th>Folio cotización</th>';
            table += '            <th>Fecha Muestreo</th>';
            table += '            <th>Nombre Cliente</th>';
            table += '            <th>Norma</th>';
            table += '            <th>Tipo descarga</th>';
            table += '            <th>Creado por:</th>';
            table += '            <th>Fecha creación</th>';
            table += '            <th>Actualizado por</th>';
            table += '            <th>Fecha actualización</th>';
            table += '        </tr>';
            table += '    </thead>';
            table += '    <tbody>';
            $.each(response.model, function (key, item) {
              table += '<tr>';
              table += '    <td>'+item.Id_cotizacion+'</td>';
              table += '    <td>'+item.Estado+'</td>';
              table += '    <td>'+item.Folio_servicio+'</td>';
              table += '    <td>'+item.Folio+'</td>';
              table += '    <td>'+item.Fecha_muestreo+'</td>';
              table += '    <td>'+item.Nombre+'</td>';
              table += '    <td>'+item.Clave_norma+'</td>';
              table += '    <td>'+item.Descarga+'</td>';
              table += '    <td>'+item.NameC+'</td>';
              table += '    <td>'+item.created_at+'</td>';
              table += '    <td>'+item.NameA+'</td>';
              table += '    <td>'+item.updated_at+'</td>';
              table += '</tr>';
              //cont++
            });
            table += '    </tbody>';
            table += '</table>';
            tablaDoc.innerHTML = table;   
                 
            table = $('#tablaSolicitud').DataTable({
                "ordering": false,
                paging: false,
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
                }
                else {
                    table.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                     //console.log("Seleccionado");
                     //selectedRow = true;
                    $("#btnEdit").prop('disabled', false);
                }
            } );

            }
        });
    });
 
    $('#tablaSolicitud tr').on('click', function(){
        let dato = $(this).find('td:first').html();
        idCot = dato;
      });
    $('#btnCreate').click( function () {
        if (idCot > 0) {
            window.location = base_url+"/admin/cotizacion/solicitud/create/"+idCot;
        }else{
            window.location = base_url+"/admin/cotizacion/solicitud/create"
        }
    } );

    $('#btnCreateSinCot').click( function () {
        // alert(idCot);
        window.location = base_url+"/admin/cotizacion/solicitud/createSinCot";
    });

    $('#btnEdit').click( function () {        
        if (idCot > 0) {
            window.location = base_url+"/admin/cotizacion/solicitud/updateOrden/"+idCot;
        }else{
            alert("Primero selecciona una orden")
        } 
    } );
    $('#btnImprimir').click( function () {
        // alert("Imprimir PDF");
        window.open(base_url+"/admin/cotizacion/solicitud/exportPdfOrden/"+idCot);
        //window.location = base_url+"/admin/cotizacion/solicitud/exportPdfOrden/"+idCot;
    } ); 

    $('#btnDuplicar').click(function(){                
        window.location = base_url + "/admin/cotizacion/solicitud/duplicarSolicitud/"+idCot;
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