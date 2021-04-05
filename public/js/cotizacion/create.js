var base_url = 'https://dev.sistemaacama.com.mx';
$(document).ready(function () {
    $('#datos-tab').click();

    $('#parametro-tab').click(function () {
        getDatos1();
        tablaParametros();
    });

    $('#clientes').click(function () {
        dataCliente();
    });
    $('#norma').click(function () {
        dataNorma();
    });
});
var norma;
function getDatos1()
{
    let subnorma = document.getElementById('subnorma')
    $('#normaPa').val(subnorma.textContent);
}
function dataCliente() {

    $.ajax({
        url: base_url + '/admin/cotizacion/getCliente', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            cliente: $('#clientes').val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response)
            $('#nombreCliente').val(response.Empresa);
        }
    });
}
var model;
function dataNorma()
{
    let sub = document.getElementById('subnorma');
    let tab = '';
    $.ajax({
        url: base_url + '/admin/cotizacion/getSubNorma', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            norma: $('#norma').val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response)
            model = response;
            $.each(response.model, function (key, item) {
                tab += '<option value="'+item.Id_subnorma+'">'+item.Clave+'</option>';
            });
            sub.innerHTML = tab;
        }
    });
}

function tablaParametros()
{
  let table = document.getElementById('tabParametros');
  let idSub = document.getElementById('subnorma');
  let tab = '';
  $.ajax({
      url: base_url + '/admin/analisisQ/detalle_normas/getParametro', //archivo que recibe la peticion
      type: 'POST', //método de envio
      data: {
        idSub:idSub.value,
        _token: $('input[name="_token"]').val(),
      },
      dataType: 'json', 
      async: false, 
      success: function (response) {
        console.log(response.model)
        tab += '<div class="row justify-content-end">' + inputBtn('', '', 'Agreagar', 'voyager-list-add', 'success','agregarParametros('+idSub.value+')' , 'botton') + '</div><br>';
        tab += '<table id="tablaParametro" class="table table-sm  table-striped table-bordered">';
        tab += '    <thead class="thead-dark">';
        tab += '        <tr>';
        tab += '            <th style="width: 5%;">Id</th>';
        tab += '            <th style="width: 30%;">Parametro</th>';
        tab += '            <th>Matriz</th>';
        tab += '        </tr>'; 
        tab += '    </thead>';
        tab += '    <tbody>';
        $.each(response.model, function (key, item) {
            tab += '<tr>';
          tab += '<td>'+item.Id_parametro+'</td>';
          tab += '<td>'+item.Parametro+'</td>';
          tab += '<td>'+item.Matriz+'</td>';
          tab += '</tr>';
        });
        tab += '    </tbody>';
        tab += '</table>';
        table.innerHTML = tab;

      }
  });
}