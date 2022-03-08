var idSol = 0;
$(document).ready(function () {

    let table = $('#tableServicios').DataTable({        
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        }
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

        console.log("Valor de tipoReporte: " + $("#tipoReporte").val());

        if($("#tipoReporte").val() == 1){
            window.open(base_url+"/admin/informes/exportPdfConComparacion/"+idSol);
        }else if($("#tipoReporte").val() == 2){
            window.open(base_url+"/admin/informes/exportPdfSinComparacion/"+idSol);
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
          tab += '<select onclick="getSolParametro()" class="form-control" id="puntoMuestreo">';
          $.each(response.model, function (key, item) {
            tab += '  <option value="'+item.Id_muestreo+'">'+item.Punto_muestreo+'</option>';
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
          tab += '          <th>Concentracion</th>';
          tab += '          <th>Diagnostico</th>';
          tab += '          <th>Liberado</th>';
          tab += '          <th>#</th>';
          tab += '          <th># Muestra</th>';
          tab += '          <th>Opc</th> ';
          tab += '        </tr>';
          tab += '    </thead>';
          tab += '    <tbody>';
          $.each(response.model, function (key, item) {
              tab += '<tr>';
              tab += '<td></td>';
              tab += '<td>'+item.Parametro+'</td>';
              tab += '<td></td>';
              tab += '<td></td>';
              tab += '<td></td>';
              tab += '<td></td>';
              tab += '<td></td>';
              tab += '<td></td>';
              tab += '<td></td>';
              tab += '<td></td>';
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