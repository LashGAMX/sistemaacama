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

    $('#btnCreateSinCot').click( function () {
        // alert(idCot);
        window.location = base_url+"/admin/cotizacion/solicitud/createSinCot";
    });

    $('#btnEdit').click( function () {
        window.location = base_url+"/admin/cotizacion/solicitud/update/"+idCot;
    } );
    $('#btnImprimir').click( function () {
        // alert("Imprimir PDF");
        window.location = base_url+"/admin/cotizacion/solicitud/exportPdfOrden/"+idCot;
    } );
    $('#btnGenFolio').click( function () {
         //alert("Imprimir GenerarFolio");
         let element = [];
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
                for (let i  = 0; i  < response.Num_tomas ; i ++) {
                    element[i] = response.Folio_servicio + "-" + (i + 1);
                }
                // element = [
                //     inputText('Nombre', 'nombreContacto', 'nombreContacto', 'Nombre',response.model.Nombres),
                //     inputText('Apellido paterno', 'paternoContacto', 'paternoContacto', 'Paterno',response.model.A_paterno),
                //     inputText('Apellido materno', 'maternoContacto', 'maternoContacto', 'Materno',response.model.A_materno),
                //     inputText('Celular', 'celularContacto', 'celularContacto', 'Celular',response.model.Celular),
                //     inputText('Telefono', 'telefonoContacto', 'telefonoContacto', 'Telefono',response.model.Telefono),
                //     inputText('Correo', 'correoContacto', 'correoContacto', 'Correo',response.model.Email),
                // ];
                itemModal[0] = element;
                newModal('divModal', 'editContacto', 'Generar folios', '', 1, 6, 0, inputBtn('', '', 'Guardar', 'save', 'success', ''));
            }
        });
    
    
         //newModal('divModal', 'setCodigos', 'Codigo generado', '', 3, 2, 0, inputBtn('', '', 'Guardar', 'save', 'success', 'createContacto()'));
        //window.location = base_url+"/admin/cotizacion/solicitud/exportPdfOrden/"+idCot;
    });
});