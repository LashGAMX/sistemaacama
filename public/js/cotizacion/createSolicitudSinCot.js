$(document).ready(function () {
    //todo Incializadores
    $('#datos-tab').click();

    $('#btnGuardarCot').click(function () {
        setCotizacion(); 
      });
  
    $('#parametro-tab').click(function () {
      createTabParametros();
    });
    $('#cotizacion-tab').click(function () {
      getDatosCotizacion()
    });
 
    $('#addRow').click(function () {
        getPuntoMuestro();
     }); 
     addColPunto()
  });
  // todo Variables globales
  var parametros = new Array()
  var allParametros = new Array()
  var counterPunto = 0;
  var idCot = 0;
  var data = new Array()
  var myTransfer 
  var des = true
  var desSw = false
  //todo funciones
  function addColPunto()
{
  var t = $('#puntoMuestro').DataTable({
    "ordering": false,
    "pageLength": 100,
    "language": {
        "lengthMenu": "# _MENU_ por pagina",
        "zeroRecords": "No hay datos encontrados",
        "info": "Pagina _PAGE_ de _PAGES_",
        "infoEmpty": "No hay datos encontrados",
    },
    "scrollY": 200,
    "scrollCollapse": true
});
}
  function getPuntoMuestro() 
  {
      let punto = new Array();
      let puntoId = new Array();
      let tab = '';
      let siralab = document.getElementById('siralab');
      $.ajax({
          url: base_url + '/admin/cotizacion/solicitud/getPuntoMuestro', //archivo que recibe la peticion
          type: 'POST', //método de envio
          data: {
            siralab:siralab.checked,
            idSuc:$("#clienteSucursal").val(),
            _token: $('input[name="_token"]').val(),
          },
          dataType: 'json', 
          async: false,
          success: function (response) {
            console.log(response);
            $.each(response.model,function(key,item){
              if (siralab.checked == true) {
                punto.push(item.Punto);
                puntoId.push(item.Id_punto);
            } else {
                punto.push(item.Punto_muestreo);
                puntoId.push(item.Id_punto);   
            }
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
            $('select[id="puntoMuestreo"] option:selected').text(),
          ] ).draw( false );
}
  function setPrecioCotizacion()
  {
    let tab = document.getElementById("puntoMuestro")
    let tab2 = document.getElementById("tableParametros")
    let puntos = new Array()
    let chParam = new Array()
    let parametro = new Array()
    for (let i = 1; i < tab.rows.length; i++) {
        puntos.push(tab.rows[i].children[0].textContent)
    }
    for (let i = 1; i < tab2.rows.length; i++) {
      param.push(tab2.rows[i].children[2].textContent)
      chParam.push(tab2.rows[i].children[0].children[0].checked)
    }
    $.ajax({
      url: base_url + '/admin/cotizacion/solicitud/setSolicitudSinCot', //archivo que recibe la peticion
      type: 'POST', //método de envio
      data: {
        id: $('#idCot').val(),
        // obsInt: $('#observacionInterna').val(),
        // obsCot: $('#observacionCotizacion').val(),
        metodoPago: $('#metodoPago').val(),
        precioAnalisis: $('#precioAnalisis').val(),
        precioCat: $('#precioCat').val(),
        descuento: $('#descuento').val(),
        precioAnalisisCon: $('#precioAnalisisCon').val(),
        precioMuestra: $('#precioMuestra').val(),
        iva: $('#iva').val(),
        subTotal: $('#subTotal').val(),
        precioTotal: $('#precioTotal').val(), 

        inter:$("#intermediario").val(),
        clientes:$("#cliente").val(),
        sucursal:$("#clienteSucursal").val(),
        direccionReporte:$("#clienteDir").val(),
        siralab:$("#siralab").prop('checked'),
        contacto:$("#contacto").val(),
        atencion:$("#atencion").val(),
        observacion:$("#observacion").val(),
        tipoServicio:$("#tipoServicio").val(),    
        tipoDescarga:$("#tipoDescarga").val(),
        norma:$("#norma").val(),
        subnorma:$("#subnorma").val(),
        fechaMuestreo:$("#fecha").val(),
        frecuencia:$("#frecuencia").val(),
        numTomas:$("#tomas").val(),
        tipoMuestra:$("#tipoMuestra").val(),
        promedio:$("#promedio").val(),
        tipoReporte:$("#tipoReporte").val(),
        puntos:puntos,
        parametros:parametro,
        chParam:chParam,
          _token: $('input[name="_token"]').val(),
      },
      dataType: 'json', 
      async: false,
      success: function (response) {
        alert("Orden creada correctamente")
        window.location = base_url + "/admin/cotizacion/solicitud"
        
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
    
    $("#textCliente").val($("#nombreCont").val() +" "+$("#apellidoCont").val())
    $("#textTelefono").val($("#celCont").val())
    $("#textEmail").val($("#emailCont").val())
  
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
    let puntos = new Array()
    let param = new  Array()
    let chParam = new Array()
    let tab = document.getElementById("puntoMuestro")
    let tab2 = document.getElementById("tableParametros")
    for (let i = 1; i < tab.rows.length; i++) {
      puntos.push(tab.rows[i].children[0].textContent)
    }
    for (let i = 1; i < tab2.rows.length; i++) {
      param.push(tab2.rows[i].children[2].textContent)
    chParam.push(tab2.rows[i].children[0].children[0].checked)
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
        nomCli: $('#clienteSucursal option:selected').text(),
        dirCli: $('#clienteDir option:selected').text(),
        atencion: $('#nombreCont').val(),
        telCli: $('#celCont').val(),
        correoCli: $('#emailCont').val(),
        clienteDir: $('#clienteDir').val(),
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
        chParam:chParam,
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
    allParametros = new Array()
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
    let sec2 = document.getElementById("contacto")
    let tab2  = '';
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
          tab2 += '<option value="' + item.Id_contacto + '">' + item.Nombres + '</option>';
      });
      sec2.innerHTML = tab2
      }
    });
  }
  function setDireccionCliente() {
    $("#dirCli").val($("#clienteDir option:selected").text())
  }
  function getDataContacto() {
    $.ajax({
        url: base_url + '/admin/cotizacion/solicitud/getDataContacto', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            id: $("#contacto").val(),
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
          id: $("#clientes").val(),
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
function editContacto()
{
  let element;
  $.ajax({
      url: base_url + '/admin/cotizacion/solicitud/getDataContacto', //archivo que recibe la peticion
      type: 'POST', //método de envio
      data: {
          id: $("#contacto").val(),
          _token: $('input[name="_token"]').val(),
      }, 
      dataType: 'json',
      async: false,
      success: function (response) { 
          console.log(response);
          element = [
              inputText('Nombre', 'nombreContacto', 'nombreContacto', 'Nombre',response.model.Nombres),
              inputText('Apellido paterno', 'paternoContacto', 'paternoContacto', 'Paterno',response.model.A_paterno),
              inputText('Apellido materno', 'maternoContacto', 'maternoContacto', 'Materno',response.model.A_materno),
              inputText('Celular', 'celularContacto', 'celularContacto', 'Celular',response.model.Celular),
              inputText('Telefono', 'telefonoContacto', 'telefonoContacto', 'Telefono',response.model.Telefono),
              inputText('Correo', 'correoContacto', 'correoContacto', 'Correo',response.model.Email),
          ];
          itemModal[1] = element;
          newModal('divModal', 'editContacto', 'Editar contacto cliente', 'lg', 3, 2, 1, inputBtn('', '', 'Guardar', 'save', 'success', 'storeContacto('+response.model.Id_contacto+','+response.model.Id_cliente+')'));
      }
  });

}
function storeContacto(idContacto,idCliente)
{
    let tab = '';
    $.ajax({
        url: base_url + '/admin/cotizacion/solicitud/storeContacto', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            idCliente: idCliente,
            idContacto:idContacto,
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
function btnReccalcular()
{
  console.log("Esta recalculando")
  let analisis = parseFloat($("#precioAnalisis").val())
  let extra = parseFloat($("#precioCat").val())
  let muestreo = parseFloat($("#precioMuestra").val())
  let iva = parseFloat($("#iva").val())
  let subTotal = parseFloat($("#subTotal").val())
  let precioTotal = parseFloat($("#precioTotal").val())
  let temp = 0

  if ($("#precioMuestra").val() == '') {
    subTotal = analisis + extra
    temp = (subTotal * 16) / 100
    precioTotal = temp + subTotal
    console.log("sin campo")
  } else {
    subTotal = analisis + extra + muestreo
    temp = (subTotal * 16) / 100
    precioTotal = temp + subTotal
    console.log("con campo")
  }
  $("#subTotal").val(subTotal)
  $("#precioTotal").val(precioTotal)
}