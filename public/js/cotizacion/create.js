$(document).ready(function () {
  //todo Incializadores
  $('#datos-tab').click();
  addColPunto()

  $('#btnGuardarCot').click(function () {
      setCotizacion(); 
    });

  $('#parametro-tab').click(function () {
    createTabParametros();
  });
  $('#cotizacion-tab').click(function () {
    getDatosCotizacion()
  });
  
  if ($("#idCot").val() != "") {
      getDataUpdate()
      $('.select2').select2();
  } 
});
// todo Variables globales
var parametros = new Array()
var counterPunto = 0;
var idCot = 0;
var data = new Array()
//todo funciones
function getDataUpdate()
{
  $.ajax({
    url: base_url + '/admin/cotizacion/getDataUpdate', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      id:$("#idCot").val(),
      _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
        data = response.model
        $("#intermediario  option[value="+data.Id_intermedio+"]").attr("selected",true);
        getClientesIntermediarios()
        getSucursal()
        getDataCliente()
        $("#tipoServicio  option[value="+data.Tipo_servicio+"]").attr("selected",true);
        $("#tipoDescarga  option[value="+data.Tipo_descarga+"]").attr("selected",true);
        $("#atencion").val(data.Atencion)
        $("#telCli").val(data.Telefono)
        $("#correoCli").val(data.Correo)
        getNormas()
        getSubNormas()
    }
});
}
function getParametrosSelected()
{
  let temp1 = new Array()
  let temp2 = new Array()
  let sw = false;
  let json = new Array()
  $.ajax({
    url: base_url + '/admin/cotizacion/getParametrosSelected', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      subnorma: $('#subnorma').val(),
      id:$("#idCot").val(),
      _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
        console.log(response)

        $.each(response.parametros, function (key, item) {
            $.each(response.model, function (key, item2) {
              if(item.Id_parametro == item2.Id_parametro){
                sw = true;
              }
            }); 
            json.push({
              "parametro" : item.Parametro,
              "id":item.Id_parametro,
              "selected":sw,
            })
            sw = false   
        });  
 
        var settings1 = {
            "dataArray": json, 
            "itemName": "parametro",
            "valueName": "id",
            "callable": function (items) {
                console.dir(items)
            }
        };
    
        $("#transfer1").transfer(settings1);
    }
});
}
function setPrecioMuestreo()
{
 
  let suma = 0;
  let sumatotal = 0;
  let iva = 0;

  $.ajax({
    url: base_url + '/admin/cotizacion/setPrecioMuestreo', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      tomasMuestreo: $('#tomasMuestreo').val(),
      diasHospedaje: $('#diasHospedaje').val(),
      hospedaje: $('#hospedaje').val(),
      diasMuestreo: $('#diasMuestreo').val(),
      numeroMuestreo: $('#numeroMuestreo').val(),
      caseta: $('#caseta').val(),
      kmExtra: $('#kmExtra').val(),
      km: $('#km').val(),
      cantidadGasolina: $('#cantidadGasolina').val(),
      paqueteria: $('#paqueteria').val(),
      gastosExtras: $('#gastosExtras').val(),
      numeroServicio: $('#numeroServicio').val(),
      numMuestreador: $('#numMuestreador').val(),
      
        _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
        console.log(response)
        $("#totalMuestreo").val(response.totalMuestreo.toFixed())

        $("#totalMuestreo").val(totalMuestreo.toFixed());
        $("#precioMuestra").val(totalMuestreo.toFixed());

        suma = parseInt(precioAnalisis.toFixed()) + parseInt(totalMuestreo.toFixed()) + parseInt(precioCatalogo.toFixed());
        iva = (suma * 16) / 100;
        $('#subTotal').val(suma);
        sumatotal = suma + iva;
        $('#precioTotal').val(sumatotal.toFixed());
    }
});
}
function getDatosCotizacion()
{
  $("#textInter").val($("#intermediario option:selected").text())
  $("#textEstado").val("Cotizacion")
  $("#textServicio").val($("#tipoServicio option:selected").text())
  $("#textDescarga").val($("#tipoDescarga option:selected").text())
  
  $("#textCliente").val($("#nomCli").val())
  $("#textDireccion").val($("#dirCli").val())
  $("#textAtencion").val($("#atencion").val())
  $("#textTelefono").val($("#telCli").val())
  $("#textEmail").val($("#correoCli").val())

  $("#textNorma").val($("#subnorma option:selected").text())
  $("#textMuestreo").val($("#frecuencia option:selected").text())
  $("#textTomas").val($("#tomas").val())
  $("#fechaMuestreo").val($("#fecha").val())
  $.ajax({
    url: base_url + '/admin/cotizacion/getDatosCotizacion', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      subnorma: $('#subnorma').val(),
      frecuencia: $("#frecuencia").val(),
        _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
        console.log(response)
      $("#precioAnalisis").val(response.precio)
    }
});
}
function createTabParametros()
{
  let table = document.getElementById("tabParametros")
  let tab = '';
  let cont = 1;
  $("#normaPa").val($("#norma option:selected").text())
  $.each(parametros, function (key, item) {
    tab += '<tr>'
    tab += '<td>'+cont+'</td>';
    tab += '<td>'+item.Id_parametro+'</td>';
    tab += '<td>'+item.Parametro+'('+item.Tipo_formula+')</td>';
    tab += '<td>'+item.Clave_norma+'</td>';
    tab += '</tr>'
  });  
  table.innerHTML = tab 
}
function setCotizacion() {
  let puntos = new Array()
  let param = new  Array()
  let tab = document.getElementById("puntoMuestro")
  let tab2 = document.getElementById("tableParametros")
  for (let i = 1; i < tab.rows.length; i++) {
    puntos.push(tab.rows[i].children[1].children[1].value)
  }
  for (let i = 1; i < tab2.rows.length; i++) {
    param.push(tab2.rows[i].children[1].textContent)
  }
  
  $.ajax({
    url: base_url + '/admin/cotizacion/setCotizacion', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      intermediario: $('#intermediario').val(),
      cliente: $('#cliente').val(),
      clienteSucursal: $('#clienteSucursal').val(),
      clienteDir: $('#clienteDir').val(),
      nomCli: $('#nomCli').val(),
      dirCli: $('#dirCli').val(),
      atencion: $('#atencion').val(),
      telCli: $('#telCli').val(),
      correoCli: $('#correoCli').val(),
      tipoServicio: $('#tipoServicio').val(),
      tipoDescarga: $('#tipoDescarga').val(),
      subnorma: $('#subnorma').val(),
      norma: $('#norma').val(),
      fecha: $('#fecha').val(),
      frecuencia: $('#frecuencia').val(),
      tomas: $('#tomas').val(),
      tipoMuestra: $('#tipoMuestra').val(),
      promedio: $('#promedio').val(),
      tipoReporte: $('#tipoReporte').val(),
      puntos:puntos,
      parametros:param,
      _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
      console.log(response)
      $("#idCot").val(response.model.Id_cotizacion)
    }
  });
}
function addColPunto() {
  var t = $('#puntoMuestro').DataTable();
  counterPunto = 0;
  $('#addRow').on('click', function () {
    t.row.add([
      counterPunto + 1,
      inputText('Punto de muestreo', 'punto' + counterPunto, 'punto', '', ''),
    ]).draw(false);

    counterPunto++;
    $("#contPunto").val(counterPunto);
  });
  $('#puntoMuestro tbody').on('click', 'tr', function () {
    if ($(this).hasClass('selected')) {
      $(this).removeClass('selected');
      counterPunto--;
    }
    else {
      t.$('tr.selected').removeClass('selected');
      $(this).addClass('selected');
    }
  });

  $('#delRow').click(function () {
    t.row('.selected').remove().draw(false);
  });
}
function getFrecuenciaMuestreo() {
  $.ajax({
    url: base_url + '/admin/cotizacion/getFrecuenciaMuestreo', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      id: $('#frecuencia').val(),
      _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
      $("#tomas").val(response.model.Tomas)
    }
  });
}
function getParametrosNorma() {
  parametros = new Array()
  $.ajax({
    url: base_url + '/admin/cotizacion/getParametrosNorma', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      id: $('#subnorma').val(),
      _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
      parametros = response.model
    }
  });
}
function getSubNormas() {
  let sub = document.getElementById('subnorma');
  let tab = '';
  $.ajax({
    url: base_url + '/admin/cotizacion/getSubNormas', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      id: $('#norma').val(),
      _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
      tab += '<option value="0">Sin seleccionar</option>';
      $.each(response.model, function (key, item) {
        if (data.Id_subnorma == item.Id_subnorma) {
          tab += '<option value="' + item.Id_subnorma + '" selected>' + item.Clave + '</option>';
        } else {
          tab += '<option value="' + item.Id_subnorma + '">' + item.Clave + '</option>'; 
        }
      });
      sub.innerHTML = tab;
    }
  });
}
function getNormas() {
  let sub = document.getElementById('norma');
  let tab = '';
  $.ajax({
    url: base_url + '/admin/cotizacion/getNormas', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      id: $('#tipoDescarga').val(),
      _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
      tab += '<option value="0">Sin seleccionar</option>';
      $.each(response.model, function (key, item) {
        if (data.Id_norma == item.Id_norma) {
          tab += '<option value="' + item.Id_norma + '" selected>' + item.Clave_norma + '</option>';
        } else {
          tab += '<option value="' + item.Id_norma + '">' + item.Clave_norma + '</option>'; 
        }
      });
      sub.innerHTML = tab;
    }
  });
}
function getClientesIntermediarios() {
  let sub = document.getElementById('cliente');
  let tab = '';
  $.ajax({
    url: base_url + '/admin/cotizacion/getClientesIntermediarios', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      id: $('#intermediario').val(),
      _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
      tab += '<option value="0">Sin seleccionar</option>';
      $.each(response.model, function (key, item) {
        if (data.Id_cliente == item.Id_cliente) {
          tab += '<option value="' + item.Id_cliente + '" selected>' + item.Empresa + '</option>';
        } else {
          tab += '<option value="' + item.Id_cliente + '">' + item.Empresa + '</option>'; 
        }
      });
      sub.innerHTML = tab;
    }
  });
}
function getSucursal() {
  let sub = document.getElementById('clienteSucursal');
  let tab = '';
  $.ajax({
    url: base_url + '/admin/cotizacion/getSucursal', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      id: $('#cliente').val(),
      _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
      tab += '<option value="0">Sin seleccionar</option>';
      $.each(response.model, function (key, item) {
        if (data.Id_sucursal == item.Id_sucursal) {
          tab += '<option value="' + item.Id_sucursal + '" selected>' + item.Empresa + '</option>';
        } else {
          tab += '<option value="' + item.Id_sucursal + '">' + item.Empresa + '</option>'; 
        }
      });
      sub.innerHTML = tab;
    }
  });
}
function getDataCliente() {
  let sub = document.getElementById('clienteDir');
  let tab = '';
  $.ajax({
    url: base_url + '/admin/cotizacion/getDataCliente', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      id: $('#clienteSucursal').val(),
      _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
      console.log(response)
      tab += '<option value="0">Sin seleccionar</option>';
      $.each(response.direccion, function (key, item) {
        tab += '<option value="' + item.Id_direccion + '">' + item.Direccion + '</option>';
      });
      sub.innerHTML = tab;
      $("#nomCli").val(response.model.Empresa)
      $("#atencion").val(response.model.Atencion)
      $("#telCli").val(response.model.Telefono)
      $("#correoCli").val(response.model.Correo)
    }
  });
}
function setDireccionCliente() {
  $("#dirCli").val($("#clienteDir option:selected").text())
}