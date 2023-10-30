 var idSol = 0;
var idPunto = 0;
$(document).ready(function () {

    let table = $('#tableServicios').DataTable({        
        "ordering": false,
        paging: false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",   
        },
        
    });     

    $('#tableServicios tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else { 
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );
    $('#tableServicios tr').on('click', function(){
        let dato = $(this).find('td:first').html();
        idSol = dato;
      });

    $('#btnImprimir').on('click', function(){
        switch ($("#tipoReporte").val()) {
            case "1":
            case "2":
                window.open(base_url+"/admin/informes/exportPdfInforme/"+idSol+"/"+$("#puntoMuestreo").val()+"/"+$("#tipoReporte").val());       
                break;
            case "3":
                window.open(base_url+"/admin/informes/exportPdfInformeCampo/"+idSol+"/"+$("#puntoMuestreo").val()) 
                break;
            case "4":
                window.open(base_url+"/admin/informes/cadena/pdf/"+$("#puntoMuestreo").val()) 
                break;
            default:
                break;
        }
    });

}); 


function getPuntoMuestro(id) 
{
    let tabla = document.getElementById('selPuntos');
    let tab = '';

    $.ajax({
        url: base_url + '/admin/informes/getPuntoMuestro',
        type: 'POST', //método de envio
        data: {
            id: id,
            _token: $('input[name="_token"]').val(),
          },
        dataType: 'json', 
        async: false, 
        success: function (response) {
          console.log(response);
          tab = '';
          tab += '<select class="form-control" id="puntoMuestreo">'; 
          $.each(response.model, function (key, item) {
            tab += '  <option value="'+item.Id_solicitud+'">'+item.Punto+'</option>';
         }); 
          tab += '</select>';
          tabla.innerHTML = tab;
        }
    });  
}

function getSolParametro()
{
    let tabla = document.getElementById('divServicios');
    let tab = '';

    $.ajax({
        url: base_url + '/admin/informes/getSolParametro',
        type: 'POST', //método de envio
        data: {
            id: idSol,
            idPunto:$("#puntoMuestreo").val(),
            _token: $('input[name="_token"]').val(),
          },
        dataType: 'json', 
        async: false, 
        success: function (response) {
          console.log(response);
          tab += '<table id="tablaParametro" class="table" style="width: 100%; font-size: 10px">';
          tab += '    <thead class="thead-dark">';
          tab += '        <tr>';
          tab += '          <th>Norma</th>';
          tab += '          <th>Parametro</th>';
          tab += '          <th>Unidad</th>';
          tab += '          <th>Resultado</th>';
        //   tab += '          <th>Concentracion</th>';
        //   tab += '          <th>Diagnostico</th>';
          tab += '          <th>Liberado</th>';
          tab += '          <th>#</th>';
          tab += '          <th># Muestra</th>';
          tab += '        </tr>';
          tab += '    </thead>';
          tab += '    <tbody>';
          $.each(response.model, function (key, item) {
              tab += '<tr>';
              tab += '<td>'+item.Norma+'</td>';
              tab += '<td>'+item.Parametro+'</td>';
              tab += '<td>'+item.Unidad+'</td>';
              tab += '<td>'+item.Resultado2+'</td>';
            //   tab += '<td></td>';
            //   tab += '<td></td>';
            if (item.Resultado != "NULL") {
                tab += '<td>Sin Liberar</td>';
            } else {
                tab += '<td>Liberado</td>';
            }
              
            tab += '<td>'+item.Num_muestra+'</td>';
            tab += '<td>'+item.Codigo+'</td>';
              tab += '</tr>';
          }); 
          tab += '    </tbody>';
          tab += '</table>';
          tabla.innerHTML = tab;

          
    let table = $('#tablaParametro').DataTable({        
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        }
    });

        }
    });  
}