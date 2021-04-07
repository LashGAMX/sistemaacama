var base_url = 'https://dev.sistemaacama.com.mx';
$(document).ready(function () {
    $('#datos-tab').click();

    $('#parametro-tab').click(function () {
        getDatos1();
        tablaParametros();
    });

    $('#cotizacion-tab').click(function () {
        getDatos2();
    });

    $('#clientes').click(function () {
        dataCliente();
    });
    $('#tipoDescarga').click(function () {
      dataNorma();
    });
    $('#norma').click(function () {
      dataSubnorma();
    });
    $('#frecuencia').click(function () {
      dataTomas();
    });

    addColPunto();
});
var norma;
function getDatos1()
{
    let subnorma = document.getElementById('subnorma')
    $('#normaPa').val(subnorma.textContent);
}
function addColPunto()
{
  var t = $('#puntoMuestro').DataTable();
  var counterPunto = 1;

  $('#addRow').on( 'click', function () {
      t.row.add( [
        counterPunto,
        inputText('Punto de muestreo','punto'+counterPunto,'punto','',''),
      ] ).draw( false );

      counterPunto++;
  } );

}
function getDatos2()
{
    $.ajax({
        url: base_url + '/admin/cotizacion/getDatos2', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            intermediario: $('#intermediario').val(),
            idSub:$('#subnorma').val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response)
            $('#textInter').val(response.intermediarios.Nombres +' '+response.intermediarios.A_paterno);
            $('#textEstado').val("Cotización");
            $('#textServicio').val($('#tipoServicio').val());
            $('#textDescarga').val($('#tipoDescarga').val());

            $('#textCliente').val($('#nombreCliente').val());
            $('#textAtencion').val($('#atencion').val());
            $('#textDireccion').val($('#direccion').val());
            $('#textTelefono').val($('#telefono').val());
            $('#textEmail').val($('#correo').val());

            $('#textNorma').val(response.subnorma.Clave);
            $('#textMuestreo').val($('#tipoMuestra').val());
            // $('#TextTomas').val($('#telefono').val());
            $('#fechaCopy').val($('#fecha').val());

            $('#precio').val(response.precio.Precio);

        }
    });
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
function dataTomas() {

  $.ajax({
      url: base_url + '/admin/cotizacion/getTomas', //archivo que recibe la peticion
      type: 'POST', //método de envio
      data: {
          idFrecuencia: $('#frecuencia').val(),
          _token: $('input[name="_token"]').val(),
      },
      dataType: 'json',
      async: false,
      success: function (response) {
          console.log(response)
          $('#tomas').val(response.Tomas);
      }
  });
}
var model;
function dataSubnorma()
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
                tab += '<option value="'+item.Id_paquete+'">'+item.Clave+'</option>';
            });
            sub.innerHTML = tab;
        }
    });
}
function dataNorma()
{
    let sub = document.getElementById('norma');
    let tab = '';
    $.ajax({
        url: base_url + '/admin/cotizacion/getNorma', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          idDescarga: $('#tipoDescarga').val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response)
            model = response;
            $.each(response.model, function (key, item) {
                tab += '<option value="'+item.Id_norma+'">'+item.Clave_norma+'</option>';
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

function agregarParametros(idSub)
{
  let idNorma = document.getElementById('norma').value;
  let parametro = new Array();
  let parametroId = new Array();
  let normaId = new Array(); //Alacena parametros agregados de la sub-norma
  $.ajax({
    url: base_url + '/admin/analisisQ/detalle_normas/getParametroNorma', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      idSub:idSub,
      idNorma:idNorma,
      _token: $('input[name="_token"]').val(),
    },
    dataType: 'json', 
    async: false,
    success: function (response) {
      $.each(response.sqlParametro,function(key,item){
        parametro.push("Pa: "+item.Parametro + '/ Mat:' + item.Matriz);
        parametroId.push(item.Id_parametro);
      });
      $.each(response.sqlNorma,function(key,item){
        normaId.push(item.Id_parametro);
      });
    }
  });
  let element = [
    inputMultiple('parametros','','Lista de parametros',parametro,parametroId,'Parametros',normaId,'duallistbox'),
  ];
  itemModal[0] = element;
  newModal('divModal','listaParametros','Asignar parametros','lg',1,1,0,inputBtn('','','Guardar','save','success','createNormaParametro('+idSub+')'));
  $('.duallistbox').bootstrapDualListbox({
    nonSelectedListLabel: 'No seleccionado',
    selectedListLabel: 'Seleccionado',
    preserveSelectionOnMove: 'Mover',
    moveOnSelect: true,
    infoText:'Mostrar todo {0}',
    filterPlaceHolder:'Filtro'
  });
}