$(document).ready(function () {
  //todo Incializadores
  $('#datos-tab').click();
  // _constructor()
  $('#addRow').click(function () {
    getPuntoMuestro();
  });
  addColPunto()
  $('#parametro-tab').click(function () {
    createTabParametros();
    std = 2
  });
  $('#datos-tab').click(function () {
    std = 1
  });
  $('.select2').select2();

  $('#btnGuardarOrden').click(function () {
    switch (std) {
      case 1: // Primera seccion
        setOrdenServicio();
        break;
      case 2: // Segunda seccion|
        setCreateOrden()
      default:
        break;
    }
  });
  $('#btnFolio').click(function () {
    setGenFolio()
  });
  $('#btnCrearSolicitud').click(function () {
    setCreateOrden()
  });
  if ($("#idCot").val() != "") {
    getDataUpdate()
    $('.select2').select2();
  } else {
    _constructor()
  }
  if ($("#folio").val() != "") {
    disabledInput()
  }
}); 
//todo Goblales
var model = new Array()
var parametros = new Array()
var std = 1;
var data = new Array()
var tabParam = true
var parametros = new Array()
var myTransfer
//todo Constructor
function _constructor() {
  getDatoIntermediario()
  getClienteRegistrado()
  getSucursalCliente()
  getDireccionReporte()
  getDataContacto()
  getSubNormas()
  // createTabParametros()
}
//todo Funciones generales
function disabledInput()
{
  switch (parseInt($("#idUser").val())) {
    case 1:
    case 36:
      
      $("#tipoServicio").attr('disabled',true)
      // $("#tipoDescarga").attr('disabled',true)
      // $("#norma").attr('disabled',true)
      // $("#subnorma").attr('disabled',true)
      $("#frecuencia").attr('disabled',true)
      $("#numTomas").attr('disabled',true)
      $("#tipoMuestra").attr('disabled',true)
      $("#promedio").attr('disabled',true)
      $("#tipoReporte").attr('disabled',true)
      $("#tipoReporte2").attr('disabled',true)
      $("#addRow").attr('disabled',true)
      $("#delRow").attr('disabled',true)
      // $("#btnSetParametro").attr('disabled',true)
      // $("#btnCrearSolicitud").attr('disabled',true)
      $("#btnFolio").attr('disabled',true)
      break;
    default:
      $("#intermediario").attr('disabled',true)
      $("#clientes").attr('disabled',true)
      $("#sucursal").attr('disabled',true)
      $("#direccionReporte").attr('disabled',true)
      $("#siralab").attr('disabled',true)
      $("#contacto").attr('disabled',true)
      $("#atencion").attr('disabled',true)
      $("#observacion").attr('disabled',true)
      $("#tipoServicio").attr('disabled',true)
      $("#tipoDescarga").attr('disabled',true)
      $("#norma").attr('disabled',true)
      $("#subnorma").attr('disabled',true)
      $("#frecuencia").attr('disabled',true)
      $("#numTomas").attr('disabled',true)
      $("#tipoMuestra").attr('disabled',true)
      $("#promedio").attr('disabled',true)
      $("#tipoReporte").attr('disabled',true)
      $("#tipoReporte2").attr('disabled',true)
      $("#addRow").attr('disabled',true)
      $("#delRow").attr('disabled',true)
      $("#btnSetParametro").attr('disabled',true)
      // $("#btnCrearSolicitud").attr('disabled',true)
      $("#btnFolio").attr('disabled',true)
      break;
  }
}
function getDataUpdate() {
  data = new Array()
  parametros = new Array()
  $.ajax({
    url: base_url + '/admin/cotizacion/solicitud/getDataUpdate', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      id: $("#idCot").val(),
      _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
      data = response.model
      parametros = response.parametros
      tabParam = true
      $("#intermediario  option[value=" + data.Id_intermediario + "]").attr("selected", true);
      $("#tipoServicio  option[value=" + data.Id_servicio + "]").attr("selected", true);
      $("#tipoDescarga  option[value=" + data.Id_descarga + "]").attr("selected", true);
      $("#frecuencia  option[value=" + data.Id_muestreo + "]").attr("selected", true);
      $("#promedio  option[value=" + data.Id_promedio + "]").attr("selected", true);
      $("#tipoMuestra  option[value=" + data.Id_muestra + "]").attr("selected", true);
      $("#tipoReporte  option[value=" + data.Id_reporte + "]").attr("selected", true);
      $("#tipoReporte2  option[value=" + data.Id_reporte2 + "]").attr("selected", true);
      $("#atencion").val(data.Atencion)
      if (data.Siralab == 1) {
        $("#siralab").prop("checked", true)
      } else {
        $("#siralab").prop("checked", false)
      }

      getDatoIntermediario()
      getClienteRegistrado()
      getSucursalCliente()
      getDireccionReporte()
      getNormas()
      getSubNormas()

      getPuntoMuestreoSol()
    }
  });
}
function setGenFolio() {
  if ($("#fechaMuestreo").val() != "") {
    $.ajax({
      url: base_url + '/admin/cotizacion/solicitud/setGenFolioSol', //archivo que recibe la peticion
      type: 'POST', //método de envio
      data: {
        id: $('#idCot').val(),
        fecha: $('#fechaMuestreo').val(),
        _token: $('input[name="_token"]').val(),
      },
      dataType: 'json',
      async: false,
      success: function (response) {
        console.log(response)
        alert(response.msg)
        $("#folio").val(response.folio)
        $("#fechaMuestreo").attr("disabled",true)
        disabledInput()
      }
    });
  } else {
    alert("Necesitas la fecha de muestreo para generar el folio")
  }

}
function addColPunto() {
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

  $('#puntoMuestro tbody').on('click', 'tr', function () {
    if ($(this).hasClass('selected')) {
      $(this).removeClass('selected');
    }
    else {
      t.$('tr.selected').removeClass('selected');
      $(this).addClass('selected');
    }
  });

  $('#delRow').click(function () {
    t.row('.selected').remove().draw(false);
    setPuntoMuestro()
  });

}
function getDatoIntermediario() {
  $.ajax({
    url: base_url + '/admin/cotizacion/solicitud/getDatoIntermediario', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      id: $('#intermediario').val(),
      _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
      console.log(response);
      $("#nombreInter").val(response.model.Nombres);
      $("#celularInter").val(response.model.Celular1);
      $("#telefonoInter").val(response.model.Tel_oficina)
      getClienteRegistrado()
    }
  });
}

function getClienteRegistrado() {
  let sub = document.getElementById("clientes")
  let tab = '';
  $.ajax({
    url: base_url + '/admin/cotizacion/solicitud/getClienteRegistrado', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      id: $('#intermediario').val(),
      _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
      console.log(response);
      tab += '<option value="0">Sin seleccionar</option>';
      $.each(response.model, function (key, item) {
        if (data.Id_cliente == item.Id_cliente) {
          tab += '<option value="' + item.Id_cliente + '" selected>('+item.Id_cliente+') ' + item.Empresa + '</option>';
        } else {
          tab += '<option value="' + item.Id_cliente + '">('+item.Id_cliente+') ' + item.Empresa + '</option>';
        }
      });
      sub.innerHTML = tab;
    }
  });
}
function getSucursalCliente() {
  let sec = document.getElementById("sucursal")

  let tab = '';
  $.ajax({
    url: base_url + '/admin/cotizacion/solicitud/getSucursalCliente', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      id: $('#clientes').val(),
      _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
      console.log(response);
      tab += '<option value="0">Sin seleccionar</option>';
      $.each(response.model, function (key, item) {
        if (data.Id_sucursal == item.Id_sucursal) {
          tab += '<option value="' + item.Id_sucursal + '" selected>(' + item.Id_sucursal + ') ' + item.Empresa + '</option>'
        } else {
          tab += '<option value="' + item.Id_sucursal + '">(' + item.Id_sucursal + ') ' + item.Empresa + '</option>'
        }
      })
      sec.innerHTML = tab
    }
  });
}
function getDireccionReporte() {
  let sec = document.getElementById("direccionReporte")
  let sec2 = document.getElementById("contacto")
  let tab = '';
  let tab2 = '';

  let siralab = document.getElementById('siralab');
  $.ajax({
    url: base_url + '/admin/cotizacion/solicitud/getDireccionReporte', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      id: $('#sucursal').val(),
      siralab: siralab.checked,
      _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
      console.log(response);
      $.each(response.model, function (key, item) {
        if (data.Id_direccion == item.Id_direccion) {
          tab += '<option value="' + item.Id_direccion + '" selected>' + item.Direccion + '</option>'
        } else {
          tab += '<option value="' + item.Id_direccion + '">' + item.Direccion + '</option>'
        }
      })
      sec.innerHTML = tab

      tab2 += '<option value="0">Sin seleccionar</option>';
      $.each(response.contacto, function (key, item) {
        if (data.Id_contacto == item.Id_contacto) {
          tab2 += '<option value="' + item.Id_contacto + '" selected>' + item.Nombre + '</option>';
        } else {
          tab2 += '<option value="' + item.Id_contacto + '">' + item.Nombre + '</option>';
        }
      });
      sec2.innerHTML = tab2
    }
  });
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
      $("#nombreCont").val(response.model.Nombre);
      $("#deptCont").val(response.model.Departamento);
      $("#puestoCont").val(response.model.Puesto);
      $("#emailCont").val(response.model.Email);
      $("#telCont").val(response.model.Telefono);
      $("#celCont").val(response.model.Celular);
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
function editContacto() {
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
        inputText('Nombre', 'nombreContacto', 'nombreContacto', 'Nombre', response.model.Nombres),
        inputText('Apellido paterno', 'paternoContacto', 'paternoContacto', 'Paterno', response.model.A_paterno),
        inputText('Apellido materno', 'maternoContacto', 'maternoContacto', 'Materno', response.model.A_materno),
        inputText('Celular', 'celularContacto', 'celularContacto', 'Celular', response.model.Celular),
        inputText('Telefono', 'telefonoContacto', 'telefonoContacto', 'Telefono', response.model.Telefono),
        inputText('Correo', 'correoContacto', 'correoContacto', 'Correo', response.model.Email),
      ];
      itemModal[1] = element;
      newModal('divModal', 'editContacto', 'Editar contacto cliente', 'lg', 3, 2, 1, inputBtn('', '', 'Guardar', 'save', 'success', 'storeContacto(' + response.model.Id_contacto + ',' + response.model.Id_cliente + ')'));
    }
  });

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
function storeContacto(idContacto, idCliente) {
  let tab = '';
  $.ajax({
    url: base_url + '/admin/cotizacion/solicitud/storeContacto', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      idCliente: idCliente,
      idContacto: idContacto,
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

function getPuntoMuestro() {
  let punto = new Array();
  let puntoId = new Array();
  let tab = '';
  let siralab = document.getElementById('siralab');
  $.ajax({
    url: base_url + '/admin/cotizacion/solicitud/getPuntoMuestro', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      siralab: siralab.checked,
      idSuc: $("#sucursal").val(),
      folio:$("#folio").val(),
      _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
      console.log(response);
      if(response.sw == true) { //validacion cuadno se crea una solicitud y se intenta agregar más puntos de muestreo.
        alert('No puedes agregar más puntos de muestreo una vez generada la Solicitud');
      } else {
        $.each(response.model, function (key, item) {
          if (siralab.checked == true) {
            punto.push(item.Punto + " " + item.Anexo + " " + item.Agua);
            puntoId.push(item.Id_punto);
          } else {
            punto.push(item.Punto_muestreo);
            puntoId.push(item.Id_punto);
          }
        });
      }
    }
  });
  let element = [
    inputSelect('puntoMuestreo', '', 'Punto de muestreo', punto, puntoId),
  ];
  itemModal[1] = element;
  newModal('divModal', 'setPuntoMuestro', 'Agregar punto muestreo', 'lg', 1, 1, 1, inputBtn('btnAddPunto', 'btnAddPunto', 'Guardar', 'save', 'success', 'btnAddPunto()'));
}
function btnAddPunto() {
  let table = document.getElementById("puntoMuestro")
  let tab = '';
  $.ajax({
    url: base_url + '/admin/cotizacion/solicitud/setPuntoMuestreo', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      siralab: siralab.checked,
      id: $("#idCot").val(),
      idPunto: $("#puntoMuestreo").val(),
      _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
      console.log(response);
      getPuntoMuestreoSol()
    }
  });
}
function getPuntoMuestreoSol() {
  let sub = document.getElementById("puntoMuestro")
  let tab = '';
  let std = "";

  $.ajax({
    url: base_url + '/admin/cotizacion/solicitud/getPuntoMuestreoSol', //archivo recibe la peticion
    type: 'POST', //método de envio
    data: {
      siralab: siralab.checked,
      id: $("#idCot").val(),
      idPunto: $("#puntoMuestreo").val(),
      _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
      console.log(response);
      if (response.sol.Siralab == 1) {
        std = "disabled"
      }
      $.each(response.model, function (key, item) {
        tab += '<tr>'
        tab += '<td>' + item.Id_punto + '</td>'
        tab += '<td><input id="puntoSol' + item.Id_punto + '" style="width:100%" value="' + item.Punto + '" ' + std + '></td>'
        tab += '<td><button class="btn btn-danger btn-sm" onclick="deletePuntoSol(' + item.Id_punto + ')" ><i class="fas fa-trash"></i></button> &nbsp; <button class="btn btn-info btn-sm" onclick="editPuntoMuestreo(' + item.Id_punto + ')" ' + std + '><i class="fas fa-edit"></i></button></td>'
        tab += '</tr>'
      });
      sub.innerHTML = tab;
    }
  });
}
function deletePuntoSol(id) {
  $.ajax({
    url: base_url + '/admin/cotizacion/solicitud/deletePuntoSol', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      id: id,
      _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
      console.log(response);
      getPuntoMuestreoSol()
      alert("Punto Eliminado")
    }
  });
}
function editPuntoMuestreo(id) {
  $.ajax({
    url: base_url + '/admin/cotizacion/solicitud/editPuntoMuestreo', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      id: id,
      idPunto: $("#puntoSol" + id).val(),
      _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
      console.log(response);
      getPuntoMuestreoSol()
      alert("Punto modificado")
    }
  });
}
function createTabParametros() {
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
    if (item.Reporte == 1) {
      temp = "checked"
    }
    tab += '<td><input type="checkbox" ' + temp + '></td>';
    tab += '<td>' + cont + '</td>';
    tab += '<td>' + item.Id_parametro + '</td>';
    tab += '<td>' + item.Parametro + '(' + item.Tipo_formula + ')</td>';
    tab += '</tr>'
    cont++
  });
  table.innerHTML = tab
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
      $("#numTomas").val(response.model.Tomas)
    }
  });
}
function getParametrosSelected() {
  let temp1 = new Array()
  let temp2 = new Array()
  let sw = false;
  let json = new Array()
  let sec = document.getElementById("divTrans")
  let tab = '';
  $.ajax({
    url: base_url + '/admin/cotizacion/solicitud/getParametrosSelected', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      id: $("#idCot").val(),
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
          if (item.Id_parametro == item2.Id_subnorma) {
            sw = true;
          }
        });
        json.push({
          "parametro": "(" + item.Id_parametro + ")" + item.Parametro + " (" + item.Matriz + ")",
          "id": item.Id_parametro,
          "selected": sw,
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

function setOrdenServicio() {
  let puntos = new Array()
  let param = new Array()
  let chParam = new Array()
  let tab = document.getElementById("puntoMuestro")
  let tab2 = document.getElementById("tableParametros")
  let std1 = 0
  let std2 = 0

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
  $.ajax({
    url: base_url + '/admin/cotizacion/solicitud/setOrdenServicio', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      std1: std1,
      std2: std2,
      id: $("#idCot").val(),
      inter: $("#intermediario").val(),
      clientes: $("#clientes").val(),
      sucursal: $("#sucursal").val(),
      direccionReporte: $("#direccionReporte").val(),
      siralab: $("#siralab").prop('checked'),
      contacto: $("#contacto").val(),
      atencion: $("#atencion").val(),
      observacion: $("#observacion").val(),
      tipoServicio: $("#tipoServicio").val(),
      tipoDescarga: $("#tipoDescarga").val(),
      norma: $("#norma").val(),
      subnorma: $("#subnorma").val(),
      fechaMuestreo: $("#fechaMuestreo").val(),
      frecuencia: $("#frecuencia").val(),
      numTomas: $("#numTomas").val(),
      tipoMuestra: $("#tipoMuestra").val(),
      promedio: $("#promedio").val(),
      tipoReporte: $("#tipoReporte").val(),
      tipoReporte2: $("#tipoReporte2").val(),
      puntos: puntos,
      puntosSize: puntos.length,
      parametros: param,
      paramSize: param.length,
      chParam: chParam,
      _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
      console.log(response)
      alert("Datos guardados")
      $("#idCot").val(response.model.Id_cotizacion)
    }
  });
}
function updateParametroSol() {
  parametros = new Array()
  let param = new Array()
  let chParam = new Array()
  let std2 = 0

  $.ajax({
    url: base_url + '/admin/cotizacion/solicitud/updateParametroSol', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      std2: std2,
      id: $("#idCot").val(),
      param: myTransfer.getSelectedItems(),
      _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
      parametros = response.model,
        alert("Parametros actualizados")
      createTabParametros()
    }
  });
}
function setCreateOrden() {

  if ($("#folio").val() != "") {
    if (confirm("Nota: Una vez creado la orden ya no se podra editar, Deseas continuar?")) {
      $.ajax({
        url: base_url + '/admin/cotizacion/solicitud/setCreateOrden', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          id: $("#idCot").val(),
          _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
          alert("Orden creada correctamente")
          window.location = base_url + '/admin/cotizacion/solicitud'
        }
      });
    }
  } else {
    alert("Necesitas generar folio antes de generar una orden")
  }
}