$(document).ready(function () {
  
  //todo Incializadores
  $('#datos-tab').click();
  addColPunto()

  // $('#btnGuardarCot').click(function () {
  //     setCotizacion(); 
  //   });
    $('#btnGuardarCotizacion').click(function () {
      switch (std) {
        case 1:
          setCotizacion()
          break;
        case 2:
          break
        case 3:
          setPrecioCotizacion()
          break
        default:
          break;
      }
    });


    $('#datos-tab').click(function () {
      std = 1
    });
  $('#parametro-tab').click(function () {
    createTabParametros();
    std = 2
  });
  $('#cotizacion-tab').click(function () {
    getDatosCotizacion()
    std = 3
  });
  $('#btnFolio').click(function () {
    setGenFolio()
  });
  
  if ($("#idCot").val() != "") {
      getDataUpdate()
      $('.select2').select2();
  } 
  let summer = document.getElementById("divSummer");
  summer.innerHTML = '<div id="observacionCotizacion">'+$("#obsCot").val()+'</div>'
  $('#observacionCotizacion').summernote({
    placeholder: '', 
    tabsize: 2,
    height: 200,

  });
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
var std = 1;
//todo funciones
function setGenFolio()
{
  $.ajax({
    url: base_url + '/admin/cotizacion/setGenFolio', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      id: $('#idCot').val(),
      fecha: $('#fechaCot').val(),
        _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
      console.log(response)
       alert(response.msg)
       $("#folio").val(response.folio)
    }
});
}
function setPrecioCotizacion()
{
  $.ajax({
    url: base_url + '/admin/cotizacion/setPrecioCotizacion', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      id: $('#idCot').val(),
      obsInt: $('#observacionInterna').val(),
      obsCot: $("#observacionCotizacion").summernote('code'),
      metodoPago: $('#metodoPago').val(),
      precioAnalisis: $('#precioAnalisis').val(),
      precioCat: $('#precioCat').val(),
      descuento: $('#descuento').val(),
      precioAnalisisCon: $('#precioAnalisisCon').val(),
      precioMuestra: $('#precioMuestra').val(),
      gastosExtras: $("#gastosExtras").val(),
      paqueteria:$("#paqueteria").val(),
      numeroServicio:$("#numeroServicio").val(),
      iva: $('#iva').val(),
      subTotal: $('#subTotal').val(),
      precioTotal: $('#precioTotal').val(),
        _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
      alert("Cotizacion creada correctamente, Ya puedes regresar a la seccino de cotización")
      
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
        btnReccalcular()
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
              "parametro" : "("+item.Id_parametro+")"+item.Parametro,
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
      // paqueteria: $('#paqueteria').val(),
      // gastosExtras: $('#gastosExtras').val(),
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

  if($("#tipoServicio").val() != 3){
    $("#divMuestreo").show()
    $("#divMuestreo4").show()
  }else{
    $("#divMuestreo").hide()
    $("#divMuestreo3").hide()
    $("#divMuestreo4").hide()
  }

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
function  createTabParametros()
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
    temp = ''
    if(item.Reporte == 1){
      temp = "checked"
    }
    tab += '<td><input type="checkbox" '+temp+'></td>';
    tab += '<td>'+cont+'</td>';
    tab += '<td>'+item.Id_parametro+'</td>'; 
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
  let chParam = new Array()
  let tab = document.getElementById("puntoMuestro")
  let tab2 = document.getElementById("tableParametros")
  let std1 = 0
  let std2 =  0

  try {
    for (let i = 1; i < tab.rows.length; i++) {
      puntos.push(tab.rows[i].children[1].children[0].value)
        std1 = 1
    }
  } catch (error) {
    std1 = 0
  }
  console.log(std)
  
  for (let i = 1; i < tab2.rows.length; i++) {
    if (tab2.rows.length > 0) {
      param.push(tab2.rows[i].children[2].textContent)
      chParam.push(tab2.rows[i].children[0].children[0].checked)
      std2 = 1 
    }
    
  }
  console.log(param)
  $.ajax({
    url: base_url + '/admin/cotizacion/setCotizacion', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      std1: std1,
      std2:std2,
      id:$("#idCot").val(),
      intermediario: $('#intermediario').val(),
      cliente: $('#cliente').val(),
      clienteSucursal: $('#clienteSucursal').val(),
      clienteDir: $('#clienteDir').val(),
      idDir: $("#clienteDir").val(),
      idGen: $("#clienteGen").val(),
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
      puntosSize:puntos.length,
      parametros:param,
      paramSize:param.length,
      chParam:chParam,
      _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
      alert("Cotizacion guardada")
      console.log(response)
      $("#idCot").val(response.model.Id_cotizacion)
      $("#numeroServicio").val(response.model.Num_servicios)
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
          tab += '<option value="' + item.Id_sucursal + '" selected>('+item.Id_sucursal+') ' + item.Empresa + '</option>';
        } else {
          tab += '<option value="' + item.Id_sucursal + '">('+item.Id_sucursal+') ' + item.Empresa + '</option>'; 
        }
      });
      sub.innerHTML = tab;
    }
  });
}
function getDataCliente() {
  let sub = document.getElementById('clienteDir');
  let tab = '';
  let sub2 = document.getElementById('clienteGen');
  let tab2 = '';
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

      tab2 += '<option value="0">Sin seleccionar</option>';
      $.each(response.contacto, function (key, item) {
        tab2 += '<option value="' + item.Id_contacto + '">' + item.Nombre + '</option>';
      }); 
      sub2.innerHTML = tab2;
      $("#nomCli").val(response.model.Empresa)
    }
  });
}
function setDireccionCliente() {
  $("#dirCli").val($("#clienteDir option:selected").text())
}
function setDatoGeneral()
{
  $.ajax({
    url: base_url + '/admin/cotizacion/setDatoGeneral', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      id: $("#clienteGen").val(),
      _token: $('input[name="_token"]').val(), 
    },
    dataType: 'json',
    async: false,
    success: function (response) {
      console.log(response)
      $("#telCli").val(response.model.Telefono)
      $("#correoCli").val(response.model.Email)
      $("#atencion").val(response.model.Nombre)
    }
  });
}
function btnReccalcular()
{
  console.log("Esta recalculando")
  let analisis = parseFloat($("#precioAnalisis").val())
  let extra = parseFloat($("#precioCat").val())
  let muestreo = parseFloat($("#precioMuestra").val())
  let gastosExtras = parseFloat($("#gastosExtras").val())
  let paqueteria = parseFloat($("#paqueteria").val())
  let iva = parseFloat($("#iva").val())
  let subTotal = parseFloat($("#subTotal").val())
  let precioTotal = parseFloat($("#precioTotal").val())
  let temp = 0



  if ($("#precioMuestra").val() == '') {
    muestreo = 0
  } 
  if ($("#gastosExtras").val() == '') {
    gastosExtras = 0
  } 
  if ($("#paqueteria").val() == '') {
    paqueteria = 0
  } 
  subTotal = analisis + extra + muestreo + gastosExtras + paqueteria
  temp = (subTotal * 16) / 100
  precioTotal = temp + subTotal
  $("#subTotal").val(subTotal)
  $("#precioTotal").val(precioTotal)
}