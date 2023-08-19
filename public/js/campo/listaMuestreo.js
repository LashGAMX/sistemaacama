
var table;
// var selectedRow = false;
$(document).ready(function () {
    table = $('#listaServicio').DataTable({
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
    $('#btnBuscar').click(function(){
        buscar();
    });
    $('#btnCapturar').click( function () {
        //alert("Capturar");
        window.open(base_url+"/admin/campo/captura/"+idCot);
    } );

    $('#btnHojaCampo').click( function () {
        window.open(base_url+"/admin/campo/hojaCampo/"+idCot);
        //window.location = base_url+"/admin/campo/hojaCampo/"+idCot;
    });
    $('#btnBitacora').click( function () {
        window.open(base_url+"/admin/campo/bitacoraCampo/"+idCot);
        //window.location = base_url+"/admin/campo/bitacoraCampo/"+idCot;
    });
});

function buscar(){

    let tablaDoc = document.getElementById('divTabla')
    let table = ""
   
    $.ajax({
        type: 'POST', //método de envio
        url: base_url + '/admin/campo/captura/buscar', //archivo que recibe la peticion
        data: {
            month:$("#month").val(),
            daystart:$("#daystart").val(),
            dayfinish:$("#dayfinish").val(),
            _token: $('input[name="_token"]').val(),
          },
        dataType: 'json',  
        success: function (response) {      
            console.log(response);

            table += ' <table class="table table-sm" id="listaServicio">';
            table += '    <thead class="thead-dark">';
            table += '        <tr>';
            table += '            <th>Id Solicitud</th>';
            table += '            <th>Solicitud</th>';
            table += '            <th>Cliente</th>';
            table += '            <th>Fecha muestro</th>';
            table += '            <th>Norma</th>';
            table += '            <th>Muestreador</th>';
            table += '            <th>Fecha Creacion</th>';
            table += '            <th>Fecha Modificación</th>';
            table += '        </tr>';
            table += '    </thead>';
            table += '    <tbody>';
            $.each(response.model, function (key, item) {
              table += '<tr>';
              table += '    <td>'+item.Id_solicitud+'</td>';
              table += '    <td>'+item.Folio_servicio+'</td>';
              table += '    <td>'+item.Empresa+'</td>';
              table += '    <td>'+item.Fecha_muestreo+'</td>';
              table += '    <td>'+item.Clave_norma+'</td>';
              table += '    <td>'+item.name+'</td>';
              table += '    <td>'+item.created_at+'</td>';
              table += '    <td>'+item.updated_at+'</td>';
              table += '</tr>';
              //cont++
            });
            table += '    </tbody>';
            table += '</table>';
            tablaDoc.innerHTML = table;   
                 
            table = $('#listaServicio').DataTable({
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

         }
        
    });  

 }