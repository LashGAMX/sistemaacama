var base_url = 'https://dev.sistemaacama.com.mx';
var swParametros = 0;
var sw = $("#sw").val();
$(document).ready(function () {
    $('#datos-tab').click();

    if(sw == 1)
    {
      update();
      swParametros == 1;
    }

    $('#parametro-tab').click(function () {
        getDatos1();
        if(swParametros == 0)
        {
          tablaParametros();
        }
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
var modelCot;
function update()
{
  $.ajax({
    url: base_url + '/admin/cotizacion/getCotizacionId', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      idCotizacion: $('#idCotizacion').val(), 
        _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
        modelCot = response.model;
        // console.log(modelCot);
    }
});
dataNorma();
}
var norma;
function getDatos1()
{
    let subnorma = document.getElementById('subnorma')
    $.ajax({
      url: base_url + '/admin/cotizacion/getSubNormaId', //archivo que recibe la peticion
      type: 'POST', //método de envio
      data: {
        idSub: $('#subnorma').val(),
          _token: $('input[name="_token"]').val(),
      },
      dataType: 'json',
      async: false,
      success: function (response) {
          console.log(response)
          $('#normaPa').val(response.model.Clave);
      }
  });
}
var counterPunto = 0;
function addColPunto()
{
  var t = $('#puntoMuestro').DataTable();
  counterPunto = 0;
  if(sw == 1)
  {
    counterPunto = parseInt($("#contPunto").val());
  }
  $('#addRow').on( 'click', function () {
      t.row.add( [
        counterPunto + 1,
        inputText('Punto de muestreo','punto'+counterPunto,'punto','',''),
      ] ).draw( false );

      counterPunto++;
  } );
  $('#puntoMuestro tbody').on( 'click', 'tr', function () {
    if ( $(this).hasClass('selected') ) {
        $(this).removeClass('selected');
    }
    else {
        t.$('tr.selected').removeClass('selected');
        $(this).addClass('selected');
    }
} );

$('#delRow').click( function () {
    t.row('.selected').remove().draw( false );
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
            idParametros:normaParametro,
            idServicio:$('#tipoServicio').val(),
            idDescarga:$('#tipoDescarga').val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response)
            $('#textInter').val(response.intermediarios.Nombres +' '+response.intermediarios.A_paterno);
            $('#textEstado').val("Cotización");
            $('#textServicio').val(response.servicio.Servicio);
            $('#textDescarga').val(response.descarga.Descarga);

            $('#textCliente').val($('#nombreCliente').val());
            $('#textAtencion').val($('#atencion').val());
            $('#textDireccion').val($('#direccion').val());
            $('#textTelefono').val($('#telefono').val());
            $('#textEmail').val($('#correo').val());

            $('#textNorma').val(response.subnorma.Clave);
            $('#textMuestreo').val($('#tipoMuestra').val());
            $('#textTomas').val($('#tomas').val());
            $('#tomasMuestreo').val($('#tomas').val());
            $('#fechaMuestreo').val($('#fecha').val());

            if($("#tipoServicio").val() == 1 || $("#tipoServicio").val() == 2)
            {
              $("#divMuestreo").css("display", "block");
              $("#divMuestreo2").css("display", "block");
            }else{
              $("#divMuestreo").css("display", "none");
              $("#divMuestreo2").css("display", "none");
            }

            getDataParametros();
            getDataMuestreo();

            $("#parametrosCotizacion").val(normaParametro);
            $("#puntosCotizacion").val(puntosMuestro);

            $('#precio').val(response.precioTotal);

        }
    });
}
var puntosMuestro = new Array();
function getDataMuestreo()
{
  let puntos = document.getElementById('puntoMuestro');
  let table = document.getElementById('puntoMuestreo3');
  let tab = '';
  puntosMuestro = new Array();
  
  tab += '<table id="tablaPuntoMuestreo3" class="table table-sm  table-striped table-bordered">';
  tab += '    <thead class="thead-dark">';
  tab += '        <tr>';
  tab += '            <th style="width: 5%;">#</th>';
  tab += '            <th style="width: 30%;">Descripción</th>';
  tab += '        </tr>'; 
  tab += '    </thead>';
  tab += '    <tbody>';
  for (let i = 1; i < puntos.rows.length; i++) {
    puntosMuestro.push($("#"+puntos.rows[i].cells[1].children[1].id).val());
      tab += '<tr>';
      tab += '<td>'+i+'</td>';
      tab += '<td>'+$("#"+puntos.rows[i].cells[1].children[1].id).val()+'</td>';
      tab += '</tr>';
  }
  tab += '    </tbody>';
  tab += '</table>';
  table.innerHTML = tab;
}
function getDataParametros()
{
  let table = document.getElementById('parametros3');

  let tab = '';
  let param = document.getElementById('tablaParametro');
  normaParametro = new Array();

  tab += '<table id="tablaParametros" class="table table-sm  table-striped table-bordered">';
  tab += '    <thead class="thead-dark">';
  tab += '        <tr>';
  tab += '            <th style="width: 5%;">Id</th>';
  tab += '            <th style="width: 30%;">Parametro</th>';
  tab += '            <th>Matriz</th>';
  tab += '        </tr>'; 
  tab += '    </thead>';
  tab += '    <tbody>';
  for (let i = 1; i < param.rows.length; i++) {
      normaParametro.push(param.rows[i].cells[0].textContent);
      tab += '<tr>';
      tab += '<td>'+param.rows[i].cells[0].textContent+'</td>';
      tab += '<td>'+param.rows[i].cells[1].textContent+'</sup></td>';
      tab += '<td>'+param.rows[i].cells[2].textContent+'</td>';
      tab += '</tr>';
  }
  tab += '    </tbody>';
  tab += '</table>';
  table.innerHTML = tab;

  $('#listaParametros').modal('hide');
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
              if(sw == 1)
              {
                if(modelCot.Id_subnorma == item.Id_paquete)
                {
                  tab += '<option value="'+item.Id_paquete+'" selected>'+item.Clave+'</option>';
                }else{
                  tab += '<option value="'+item.Id_paquete+'">'+item.Clave+'</option>';
                }
              }else{
                  tab += '<option value="'+item.Id_paquete+'">'+item.Clave+'</option>';
              }
            
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
              if(sw == 1)
              {
                if(modelCot.Id_norma == item.Id_norma)
                {
                  tab += '<option value="'+item.Id_norma+'" selected>'+item.Clave_norma+'</option>';
                }else{
                  tab += '<option value="'+item.Id_norma+'">'+item.Clave_norma+'</option>';
                } 
              }else{
                tab += '<option value="'+item.Id_norma+'">'+item.Clave_norma+'</option>';
              }
            });
            sub.innerHTML = tab;
        }
    });
    if(sw == 1)
    {
      dataSubnorma();
    }
}

var normaParametro = new Array();
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
        normaParametro = new Array();
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
          normaParametro.push(item.Id_parametro);
            tab += '<tr>';
          tab += '<td>'+item.Id_parametro+'</td>';
          tab += '<td>'+item.Parametro+'<sup> ('+item.Simbologia+')</sup></td>';
          tab += '<td>'+item.Matriz+'</td>';
          tab += '</tr>';
        });
        tab += '    </tbody>';
        tab += '</table>';
        table.innerHTML = tab;

      }
  });
}
function tablaParametrosCot()
{
  let table = document.getElementById('tabParametros');
  let idSub = document.getElementById('subnorma');

  let tab = '';
  $.ajax({
      url: base_url + '/admin/analisisQ/detalle_normas/getParametroCot', //archivo que recibe la peticion
      type: 'POST', //método de envio
      data: {
        idCot:$('#idCotizacion').val(),
        _token: $('input[name="_token"]').val(),
      },
      dataType: 'json', 
      async: false, 
      success: function (response) {
        console.log(response.model)
        normaParametro = new Array();
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
          normaParametro.push(item.Id_parametro);
            tab += '<tr>';
          tab += '<td>'+item.Id_parametro+'</td>';
          tab += '<td>'+item.Parametro+'<sup> ('+item.Simbologia+')</sup></td>';
          tab += '<td>'+item.Matriz+'</td>';
          tab += '</tr>';
        });
        tab += '    </tbody>';
        tab += '</table>';
        table.innerHTML = tab;

      }
  });
}
function updateNormaParametro()
{
  let table = document.getElementById('tabParametros');
  let idSub = document.getElementById('subnorma');

  let tab = '';
  let param = document.getElementById('parametros');
  normaParametro = new Array();

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
  for (let i = 0; i < param.length; i++) {
    if(param[i].selected == true)
    {
      normaParametro.push(param[i].value);
      tab += '<tr>';
      tab += '<td>'+parametroId[i]+'</td>';
      tab += '<td>'+parametro[i]+'<sup> ('+simbologia[i]+')</sup></td>';
      tab += '<td>'+matriz[i]+'</td>';
      tab += '</tr>';
    }
  }
  tab += '    </tbody>';
  tab += '</table>';
  table.innerHTML = tab;

  $('#listaParametros').modal('hide');

}
var parametro = new Array();
var parametroId = new Array();
var matriz = new Array();
var simbologia = new Array();
function agregarParametros(idSub)
{
  swParametros = 1;
  let idNorma = document.getElementById('norma').value;
  parametro = new Array();
  parametroId = new Array();
  matriz = new Array();
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
      console.log(response);
      $.each(response.sqlParametro,function(key,item){
        parametro.push("Pa: "+item.Parametro + '/ Mat:' + item.Matriz);
        parametroId.push(item.Id_parametro);
        matriz.push(item.Matriz);
        simbologia.push(item.Simbologia);
      });
      // $.each(normaParametro,function(key,item){
      //   normaId.push(item.Id_parametro);
      // });
    }
  });
  let element = [
    inputMultiple('parametros','','Lista de parametros',parametro,parametroId,'Parametros',normaParametro,'duallistbox'),
  ];
  itemModal[0] = element;
  newModal('divModal','listaParametros','Asignar parametros','lg',1,1,0,inputBtn('','','Guardar','save','success','updateNormaParametro()'));
  $('.duallistbox').bootstrapDualListbox({
    nonSelectedListLabel: 'No seleccionado',
    selectedListLabel: 'Seleccionado',
    preserveSelectionOnMove: 'Mover',
    moveOnSelect: true,
    infoText:'Mostrar todo {0}',
    filterPlaceHolder:'Filtro'
  });
}