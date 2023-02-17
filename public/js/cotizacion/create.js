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
var myTransfer 
var des = true
var desSw = false
var tabParam = false
//todo funciones
function setPrecioCotizacion()
{
  $.ajax({
    url: base_url + '/admin/cotizacion/setPrecioCotizacion', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      id: $('#idCot').val(),
      obsInt: $('#observacionInterna').val(),
      obsCot: $('#observacionCotizacion').val(),
      metodoPago: $('#metodoPago').val(),
      precioAnalisis: $('#precioAnalisis').val(),
      precioCat: $('#precioCat').val(),
      descuento: $('#descuento').val(),
      precioAnalisisCon: $('#precioAnalisisCon').val(),
      precioMuestra: $('#precioMuestra').val(),
      iva: $('#iva').val(),
      subTotal: $('#subTotal').val(),
      precioTotal: $('#precioTotal').val(),
        _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
      alert("Cotizacion creada correctamente")
      
    }
});
}
function aplicarDescuento()
{
  let subTotal = 0
  let iva = 0
  let descuento = 0;
  let temp = 0;
  if (desSw == false) {
    descuento = (parseFloat($("#descuento").val()) *  parseFloat($("#precioAnalisis").val())) / 100
    temp = parseFloat($("#precioAnalisis").val()) - descuento
    $("#precioAnalisisCon").val(temp)

    subTotal = (parseFloat($("#precioAnalisisCon").val()) + parseFloat($("#precioCat").val()) + parseFloat($("#precioMuestra").val()))
    iva = (subTotal * 16) / 100;
    sumatotal = subTotal + iva;

    $('#subTotal').val(subTotal)
    $('#precioTotal').val(sumatotal.toFixed(2));
    desSw = true
  } else {
    alert("No puedes volver a aplicar un descuento")
  }

}
function btnDescuento()
{
  if (des == true) {
    $(".activarDescuento").attr("hidden",false) 
    des = false;
  } else {
    $(".activarDescuento").attr("hidden",true) 
    des = true;
  }
}
function getLocalidad()
{
    let sub = document.getElementById('localidad');
    let tab = '';
    $.ajax({
        url: base_url + '/admin/cotizacion/getLocalidad', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          idLocalidad: $('#estado').val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            $.each(response.model, function (key, item) {              
              if($("#idCot") != "") 
              {
                if (response.cotizacionMuestreo.Localidad == item.Id_localidad) {
                  tab += '<option value="'+item.Id_localidad+'" selected>'+item.Nombre+'</option>';
                } else {
                  tab += '<option value="'+item.Id_localidad+'">'+item.Nombre+'</option>'; 
                }
              }else{ 
                tab += '<option value="'+item.Id_localidad+'">'+item.Nombre+'</option>';
              }
            });
            sub.innerHTML = tab;
        }
    });
}
function updateParametroCot()
{
  $.ajax({
    url: base_url + '/admin/cotizacion/updateParametroCot', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      id:$("#idCot").val(),
      subnorma:$("#subnorma").val(),
      param: myTransfer.getSelectedItems(),
      _token: $('input[name="_token"]').val(),
    }, 
    dataType: 'json',
    async: false,
    success: function (response) {
      console.log(response)
      parametros = response.parametro
      tabParam = true
      createTabParametros()
    }
  });
}
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
        tabParam = true
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
        parametros = response.parametros
        createTabParametros()
        getLocalidad()
    }
});
}
function getParametrosSelected()
{
  let temp1 = new Array()
  let temp2 = new Array()
  let sw = false;
  let json = new Array()
  let sec = document.getElementById("divTrans")
  let tab = '';
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
        tab = '<div id="transfer1" class="transfer-demo"></div>'
        sec.innerHTML = tab
        $.each(response.parametros, function (key, item) {
            $.each(response.model, function (key, item2) {
              if(item.Id_parametro == item2.Id_subnorma){
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
    
        myTransfer = $("#transfer1").transfer(settings1);
    }
});
}
function cantGasolinaTeorico() 
{
  let km = document.getElementById('km')
 if(km.value != '' && $('#kmExtra').val() != '')
 {
   $.ajax({
    url: base_url + '/admin/cotizacion/cantidadGasolina', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      kmExtra: $('#kmExtra').val(),
      km: $('#km').val(),
        _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
        console.log(response)
        $("#gasolinaTeorico").val(response.total);
    }
  });
 }
}
function setPrecioMuestreo()
{
 
  let suma = 0;
  let sumatotal = 0;
  let iva = 0;
  let temp = 0;

  $.ajax({
    url: base_url + '/admin/cotizacion/setPrecioMuestreo', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      id:$("#idCot").val(),
      tomasMuestreo: $('#tomasMuestreo').val(),
      diasHospedaje: $('#diasHospedaje').val(),
      hospedaje: $('#hospedaje').val(),
      diasMuestreo: $('#diasMuestreo').val(),
      numeroMuestreo: $('#numeroMuestreo').val(),
      caseta: $('#caseta').val(),
      estado: $('#estado').val(),
      localidad: $('#localidad').val(),
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
        temp = response.total
        $("#totalMuestreo").val(temp.toFixed())
        $("#precioMuestra").val(temp.toFixed())
        
        suma = (parseFloat($("#precioAnalisis").val()) + parseFloat($("#precioCat").val()) + parseFloat($("#precioMuestra").val()))
        iva = (suma * 16) / 100;
        sumatotal = suma + iva;

        $('#subTotal').val(suma)
        $('#precioTotal').val(sumatotal.toFixed(2));

        // suma = parseInt(precioAnalisis.toFixed()) + parseInt(totalMuestreo.toFixed()) + parseInt(precioCatalogo.toFixed());
        // iva = (suma * 16) / 100;
        // $('#subTotal').val(suma);
        // sumatotal = suma + iva;
        // $('#precioTotal').val(sumatotal.toFixed());
    }
});
}
function getDatosCotizacion()
{
  let suma = 0;
  let sumatotal = 0;
  let iva = 0;


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
  $('#descuento').val("")
  $('#precioAnalisisCon').val("")
  desSw = false
  let tab = document.getElementById("puntoMuestro")
  $.ajax({
    url: base_url + '/admin/cotizacion/getDatosCotizacion', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      id:$("#idCot").val(),
      intermediario:$("#intermediario").val(),
      subnorma: $('#subnorma').val(),
      frecuencia: $("#frecuencia").val(),
        _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
        console.log(response)
      $("#precioAnalisis").val(parseFloat(response.precio) * (tab.rows.length - 1)) 
      $("#precioCat").val(parseFloat(response.precioCat) * (tab.rows.length - 1))
      $("#extra").val("("+response.extra+")")
      $("#contSer").val("("+(tab.rows.length - 1)+")")
    
      suma = (parseFloat($("#precioAnalisis").val()) + parseFloat($("#precioCat").val()) + parseFloat($("#precioMuestra").val()))
      iva = (suma * 16) / 100;
      sumatotal = suma + iva;
      $('#subTotal').val(suma)
    $('#precioTotal').val(sumatotal.toFixed(2));
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
    if ($("#idCot").val() != "") {
      if (item.Extra == 1) {
        tab += '<tr class="bg-danger">'
      } else {
        tab += '<tr>'
      }  
    } else {
    tab += '<tr>' 
    }
    tab += '<td>'+cont+'</td>';
    if (tabParam == true) {
      tab += '<td>'+item.Id_subnorma+'</td>';
    } else {
      tab += '<td>'+item.Id_parametro+'</td>'; 
    }
    tab += '<td>'+item.Parametro+'('+item.Tipo_formula+')</td>';
    tab += '</tr>'
    cont++
  });  
  table.innerHTML = tab 
}
function setCotizacion() {
  tabParam = false
  let puntos = new Array()
  let param = new  Array()
  let tab = document.getElementById("puntoMuestro")
  let tab2 = document.getElementById("tableParametros")
  for (let i = 1; i < tab.rows.length; i++) {
    puntos.push(tab.rows[i].children[1].children[0].value)
  }
  for (let i = 1; i < tab2.rows.length; i++) {
    param.push(tab2.rows[i].children[1].textContent)
  }
  console.log(param)
  $.ajax({
    url: base_url + '/admin/cotizacion/setCotizacion', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      id:$("#idCot").val(),
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
      alert("Cotizacion guardada")
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
      '<input type="text" class="form-control" placeholder="Punto de muestreo">',
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
      createTabParametros()
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