var base_url = 'https://dev.sistemaacama.com.mx';
$(document).ready(function () {
    $('#datos-tab').click();
    $('#tipoDescarga').click(function () {
        dataNorma();
    });
    $('#norma').click(function () {
        dataSubnorma();
    });
    $('#parametro-tab').click(function () {
        getDatos1();
        tablaParametros();
        // if (swParametros == 0) {
        //     tablaParametros();
        // }
    });
    $('#addRow').click(function () {
       getPuntoMuestro();
    });
    addColPunto();
});
// Get datos intermedario
function getDatoIntermediario() {
    $.ajax({
        url: base_url + '/admin/cotizacion/solicitud/getDatoIntermediario', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            idCliente: $('#intermediario').val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        // async: false,
        success: function (response) {
            console.log(response);
            $("#nombreInter").val(response.intermediario.Nombres + "" + response.intermediario.A_paterno);
            $("#celularInter").val(response.intermediario.Celular1);
            $("#telefonoInter").val(response.intermediario.Tel_oficina);
        }
    });
}
//Get Sucursal cliente
function getSucursal() {
    let sucursal = document.getElementById('sucursal');
    let contacto = document.getElementById('contacto');
    let tab = '';
    let tab2 = '';
    $.ajax({
        url: base_url + '/admin/cotizacion/solicitud/getSucursal', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            cliente: $('#clientes').val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response)
            $.each(response.sucursal, function (key, item) {
                tab += '<option value="' + item.Id_sucursal + '">' + item.Empresa + '</option>';
            });
            tab2 += '<option value="0">Sin seleccionar</option>';
            $.each(response.contacto, function (key, item) {
                tab2 += '<option value="' + item.Id_contacto + '">' + item.Nombres + '</option>';
            });
            sucursal.innerHTML = tab;
            contacto.innerHTML = tab2;
        }
    });
}
function getDireccionReporte() {
    let direccion = document.getElementById('direccionReporte');
    let tab = '';
    $.ajax({
        url: base_url + '/admin/cotizacion/solicitud/getDireccionReporte', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            idSucursal: $('#sucursal').val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response);
            tab += '<option value="0">Sin seleccionar</option>';
            $.each(response.direccion, function (key, item) {
                tab += '<option value="' + item.Id_direccion + '">' + item.Direccion + '</option>';
            });
            direccion.innerHTML = tab;
        }
    });
}

function setContacto() {
    let element = [
        inputText('Nombre', 'nombreContacto', 'nombreContacto', 'Nombre'),
        inputText('Apellido paterno', 'paternoContacto', 'paternoContacto', 'Paterno'),
        inputText('Apellido materno', 'maternoContacto', 'maternoContacto', 'Materno'),
        inputText('Celular', 'celularContacto', 'celularContacto', 'Celular'),
        inputText('Telefono', 'telefonoContacto', 'telefonoContacto', 'Telefono'),
        inputText('Correo', 'correoContacto', 'correoContacto', 'Correo'),
    ];
    itemModal[0] = element;
    newModal('divModal', 'setContacto', 'Crear contacto cliente', 'lg', 3, 2, 0, inputBtn('', '', 'Guardar', 'save', 'success', 'createContacto()'));
}
function createContacto() {
    let contacto = document.getElementById('contacto');
    let tab = '';
    $.ajax({
        url: base_url + '/admin/cotizacion/solicitud/setContacto', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            idCliente: $("#clientes").val(),
            nombre: $("#nombreContacto").val(),
            paterno: $("#paternoContacto").val(),
            materno: $("#maternoContacto").val(),
            celular: $("#celularContacto").val(),
            telefono: $("#telefonoContacto").val(),
            correo: $("#correoContacto").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response);
            tab += '<option value="0">Sin seleccionar</option>';
            $.each(response.model, function (key, item) {
                tab += '<option value="' + item.Id_contacto + '">' + item.Nombres + '</option>';
            });
            contacto.innerHTML = tab;
            swal("Registro!", "Registro guardado correctamente!", "success");
            $('#setContacto').modal('hide')
        }
    });
}
function getDataContacto() {
    $.ajax({
        url: base_url + '/admin/cotizacion/solicitud/getDataContacto', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            idContacto: $("#contacto").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response);
            $("#idCont").val(response.model.Id_contacto);
            $("#nombreCont").val(response.model.Nombres);
            $("#apellidoCont").val(response.model.A_paterno);
            $("#emailCont").val(response.model.Email);
            $("#celCont").val(response.model.Celular);
            $("#telCont").val(response.model.Telefono);
        }
    });
}
function dataNorma() {
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
                tab += '<option value="' + item.Id_norma + '">' + item.Clave_norma + '</option>';
            });
            sub.innerHTML = tab;
        }
    });

}
function dataSubnorma() {
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
                tab += '<option value="' + item.Id_paquete + '">' + item.Clave + '</option>';

            });
            sub.innerHTML = tab;
        }
    });
}
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

  swUpdateParam = 1;
  $('#listaParametros').modal('hide');

}

function getPuntoMuestro()
{
    let punto = new Array();
    let puntoId = new Array();
    $.ajax({
        url: base_url + '/admin/cotizacion/solicitud/getPuntoMuestro', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          idSuc:$("#sucursal").val(),
          _token: $('input[name="_token"]').val(),
        },
        dataType: 'json', 
        async: false,
        success: function (response) {
          console.log(response);
          $.each(response.model,function(key,item){
            punto.push(item.Punto_muestreo);
            puntoId.push(item.Id_punto);
          });
        }
      });
    let element = [
        inputSelect('puntoMuestreo','','Punto de muestreo',punto,puntoId),
    ];
    itemModal[1] = element;
    newModal('divModal', 'setPuntoMuestro', 'Agregar punto muestreo', 'lg', 1, 1, 1, inputBtn('btnAddPunto','btnAddPunto', 'Guardar', 'save', 'success', 'btnAddPunto()'));    
}
function btnAddPunto()
{
    var t = $('#puntoMuestro').DataTable();
    //   counterPunto = 0;

          t.row.add( [
            $("#puntoMuestreo").val(),
            $("#puntoMuestreo").text(),
          ] ).draw( false );

}
function addColPunto()
{
  var t = $('#puntoMuestro').DataTable();
//   counterPunto = 0;

//   $('#btnAddPunto').on( 'click', function () {
//       t.row.add( [
//         // counterPunto + 1,
//         $("#puntoMuestreo").val(),
//       ] ).draw( false );

//     //   counterPunto++;
//   } );
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