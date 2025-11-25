$(document).ready(function () {
  var tableParametros;
  var solModel;
  $("#btnFolio").click(function () {
    setGenFolio();
  });
  $("#btnGuardar").click(function () {
    setSolicitud();
  });
  $("#btnAddCol").click(function () {
    setMuestraSol();
  });
  $("#btnGuardarSol").click(function () {
    setPreSolicitud();
  });
  tabParametros();
  getDataSolicitud();

  $(".select2Multiple").select2({});
});
function setPreSolicitud() {
  const formData = {
    idSol: $("#idSol").val(),
    cliente: $("#clientes").val(),
    sucursal: $("#sucursal").val(),
    folio: $("#Folio").val(), // Correcto
    contacto: $("#contacto").val(),
    direccion: $("#direccionReporte").val(),
    atencion: $("#atencion").val(),
    observacion: $("#observacion").val(),
    servicio: $("#servicio").val(),
    norma: $("#norma").val(),
    // subnorma: $("#subnorma").val(),
    fechaMuestreo: $("#fechaMuestreo").val(),
    numTomas: $("#numTomas").val(),
    id: $("#user").val(), // ID DEL USUARIO
    _token: $('input[name="_token"]').val(),
  };

  // console.log(formData); // <<--- Así ves si id realmente trae un número

  $.ajax({
    type: "POST",
    url: base_url + "/admin/alimentos/setSolicitud",
    dataType: "json",
    data: formData,
    success: function (response) {
      alert(response.msg);
      $("#idSol").val(response.model.Id_solicitud);
    },
  });
}
function getDataSolicitud() {
  if ($("#idSol").val() != "") {
    $.ajax({
      type: "POST",
      url: base_url + "/admin/alimentos/getDataSolicitud",
      dataType: "json",
      data: {
        id: $("#idSol").val(),
        _token: $('input[name="_token"]').val(),
      },
      success: function (response) {
        solModel = response.model;

        getSucursalCliente(solModel.Id_cliente);
        getDireccionReporte(solModel.Id_sucursal);
        getDataContacto(solModel.Id_contacto);
        getSubNormas(solModel.Id_norma);
        getMuestraSol();
      },
    });
  }
}
function tabParametros() {
  tableParametros = $("#tableParametros").DataTable({
    ordering: false,
    paging: false,
    language: {
      lengthMenu: "# _MENU_ por pagina",
      zeroRecords: "No hay datos encontrados",
      info: "Pagina _PAGE_ de _PAGES_",
      infoEmpty: "No hay datos encontrados",
    },
  });
}
function setMuestraSol() {
  if ($("#idSol").val() != "") {
    $.ajax({
      type: "POST",
      url: base_url + "/admin/alimentos/setMuestraSol",
      dataType: "json",
      data: {
        idSol: $("#idSol").val(),
        numTomas: $("#numTomas").val(),
        _token: $('input[name="_token"]').val(),
      },
      success: function (response) {
        alert(response.msg);
        getMuestraSol();
      },
    });
  } else {
    alert("No puedes crear muestras sin antes haber guardado");
  }
}
function getMuestraSol() {
  if ($("#idSol").val() != "") {
    $.ajax({
      type: "POST",
      url: base_url + "/admin/alimentos/getMuestraSol",
      dataType: "json",
      data: {
        idSol: $("#idSol").val(),
        _token: $('input[name="_token"]').val(),
      },
      success: function (response) {
        //  console.log("respuesta ser",response);
        let tabMuestras = "";
        let cont = 1;

        response.model.forEach((item) => {
          // Generar las opciones para el select de parámetros
          //   console.log("proceso", response.proceso);
          let opciones = response.parametros
            .map((param) => {
              const isSelected = response.solParam[cont - 1]?.some(
                (solP) =>
                  solP.Id_parametro === param.Id_parametro &&
                  solP.Id_matrizar === param.Id
              )
                ? "selected"
                : "";

              return `<option 
              value="${param.Id_parametro}" 
              data-idmatrizar="${param.Id}" 
              ${isSelected}>
              (${param.Id_parametro}) ${param.Parametro} 
              <strong>Matriz:(${param.Matriz})</strong> 
              <strong>Limite:(${param.Limite})</strong> 
              </option>`;}).join("");
          // Generar las opciones para el select de normas
          let opciones2 = response.normas
            .map((norma) => {
              const isSelected =
                item.Id_norma == norma.Id_norma ? "selected" : "";
              return `<option value="${norma.Id_norma}" ${isSelected}>
                                        (${norma.Id_norma}) ${norma.Clave_norma}
                                    </option>`;
            })
            .join("");
          const disabledAttr = response.proceso ? "disabled" : "";

          // Generar la fila para cada muestra
          tabMuestras += `<tr>
                            <td style="width: 1%; white-space: nowrap;">${cont}</td>
                            <td style="width: 1%; white-space: nowrap;">${item.Id_muestra}</td>
                            <td style="width: 10%; white-space: nowrap;" ><textarea  id="muestra${item.Id_muestra}">${item.Muestra ?? ""}</textarea></td>
                            <td><select class="select2Multiple form-control" id="parametros${item.Id_muestra}" multiple="multiple" ${disabledAttr}> ${opciones}</select></td>
                            <td><select class="select2 form-control" id="normas${item.Id_muestra}">${opciones2}</select></td>
                            <td><button id="btnSaveMuestra${item.Id_muestra}" onclick="setSaveMuestra(${item.Id_muestra})" class="btn btn-success"><i class="fas fa-save"></i></button> 
                                &nbsp; 
                                <button id="btnDelMuestra${item.Id_muestra}" onclick="DeleteMuestra(${item.Id_muestra})" class="btn btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button> 
                            </td>
                          </tr>`;
                        cont++;
        });
        $("#bodyTabMuestras").html(tabMuestras);
      },
      complete: function () {
        $(".select2Multiple").select2({
          placeholder: "Selecciona una o más opciones",
          allowClear: true,
        });
      },
    });
  } else {
    alert("No puedes crear muestras sin antes haber guardado");
  }
}
function DeleteMuestra(id) {
  // console.log("ID enviado:", id);
  $.ajax({
    type: "POST",
    url: base_url + "/admin/alimentos/DeleteMuestra",
    dataType: "json",
    data: {
      id: id,
      _token: $('input[name="_token"]').val(),
    },
    success: function (response) {
      alert(response.msg);
      getMuestraSol();
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud:", error);
    },
  });
}
function setSaveMuestra(id) {
  // Captura la opción seleccionada
  const parametrosSeleccionados = [];
  const selectedOption = $("#parametros" + id + " option:selected");

  // Si hay opciones seleccionadas, extraemos los datos
  selectedOption.each(function () {
    parametrosSeleccionados.push({
      Id_parametro: $(this).val(),
      Id_matrizar: $(this).data("idmatrizar"),
    });
  });

  // Verifica si hay al menos un parámetro
  if (parametrosSeleccionados.length === 0) {
    alert("Debes seleccionar al menos un parámetro");
    return;
  }

  // Envía los datos por AJAX
  $.ajax({
    type: "POST",
    url: base_url + "/admin/alimentos/setSaveMuestra",
    dataType: "json",
    data: {
      idSol: $("#idSol").val(),
      id: id,
      muestra: $("#muestra" + id).val(),
      parametros: parametrosSeleccionados, // array de objetos { Id_parametro, Id_matrizar }
      norma: $("#normas" + id).val(),
      _token: $('input[name="_token"]').val(),
    },
    success: function (response) {
      alert(response.msg);
      getMuestraSol();
    },
    error: function (xhr) {
      console.error("Error:", xhr.responseText);
      alert("Error al guardar. Revisa la consola.");
    },
  });
}
function setSolicitud() {
  const recibeValue = $("#recibe").val();

  const formData = {
    idSol: $("#idSol").val(),
    cliente: $("#clientes").val(),
    sucursal: $("#sucursal").val(),
    folio: $("#Folio").val(), // Correcto
    contacto: $("#contacto").val(),
    direccion: $("#direccionReporte").val(),
    atencion: $("#atencion").val(),
    observacion: $("#observacion").val(),
    servicio: $("#servicio").val(),
    norma: $("#norma").val(),
    subnorma: $("#subnorma").val(),
    fechaMuestreo: $("#fechaMuestreo").val(),
    numTomas: $("#numTomas").val(),
    idUsuario: $("#user").val(),
    condiciones: $("#condiciones").val(),
    ambientales: $("#ambientales").val(),
    fechapro: $("#fechapro").val(),
    id: $("#user").val(),
    _token: $('input[name="_token"]').val(),
  };
  $.ajax({
    type: "POST",
    url: base_url + "/admin/alimentos/setSolicitud",
    dataType: "json",
    data: formData,
    success: function (response) {
      alert(response.msg);
      window.location.href = base_url + "/admin/alimentos/orden-servicio";

      $("#idSol").val(response.model.Id_solicitud);
    },
  });
}
function getSucursalCliente(id) {
  $.ajax({
    type: "POST",
    url: base_url + "/admin/alimentos/getSucursal",
    dataType: "json",
    data: {
      id: id,
      _token: $('input[name="_token"]').val(),
    },
    success: function (response) {
      // console.log(response);
      let sucursal = "";
      sucursal += `<option value="0">Sin seleccionar</option>`;
      response.model.forEach((item) => {
        if (solModel.Id_sucursal == item.Id_sucursal) {
          sucursal += `<option value="${item.Id_sucursal}" selected>(${item.Id_sucursal}) ${item.Empresa}</option>`;
        } else {
          sucursal += `<option value="${item.Id_sucursal}">(${item.Id_sucursal}) ${item.Empresa}</option>`;
        }
      });
      $("#sucursal").html(`
                ${sucursal} 
            `);
    },
  });
}
function getDireccionReporte(id) {
  $.ajax({
    type: "POST",
    url: base_url + "/admin/alimentos/getDireccionReporte",
    dataType: "json",
    data: {
      idSol: $("#idSol").val(),
      id: id,
      _token: $('input[name="_token"]').val(),
    },
    success: function (response) {
      // console.log(response);
      let direccion = "";
      direccion += `<option value="0">Sin seleccionar</option>`;
      response.model.forEach((item) => {
        if (solModel.Id_direccion == item.Id_direccion) {
          direccion += `<option value="${item.Id_direccion}" selected>(${item.Id_direccion}) ${item.Direccion}</option>`;
        } else {
          direccion += `<option value="${item.Id_direccion}">(${item.Id_direccion}) ${item.Direccion}</option>`;
        }
      });
      $("#direccionReporte").html(`
                ${direccion} 
            `);
    },
  });
  getContactoSucursal(id);
}
function getContactoSucursal(id) {
  $.ajax({
    type: "POST",
    url: base_url + "/admin/alimentos/getContactoSucursal",
    dataType: "json",
    data: {
      idSol: $("#idSol").val(),
      id: id,
      _token: $('input[name="_token"]').val(),
    },
    success: function (response) {
      // console.log(response);

      let data = '<option value="0">Sin seleccionar</option>';
      response.model.forEach((item) => {
        if (solModel.Id_contacto == item.Id_contacto) {
          data += `<option value="${item.Id_contacto}" selected>(${item.Id_contacto}) ${item.Nombre}</option>`;
        } else {
          data += `<option value="${item.Id_contacto}">(${item.Id_contacto}) ${item.Nombre}</option>`;
        }
      });

      $("#contacto").html(data);
    },
  });
}
function getSubNormas(id) {
  $.ajax({
    type: "POST",
    url: base_url + "/admin/alimentos/getSubNormas",
    dataType: "json",
    data: {
      idSol: $("#idSol").val(),
      id: id,
      _token: $('input[name="_token"]').val(),
    },
    success: function (response) {
      // console.log(response);
      let data = "";
      data += `<option value="0">Sin seleccionar</option>`;
      response.model.forEach((item) => {
        if (solModel.Id_subnorma == item.Id_subnorma) {
          data += `<option value="${item.Id_subnorma}" selected>(${item.Id_subnorma}) ${item.Clave}</option>`;
        } else {
          data += `<option value="${item.Id_subnorma}">(${item.Id_subnorma}) ${item.Clave}</option>`;
        }
      });

      $("#subnorma").html(`
                ${data} 
            `);
    },
  });
}
function getDataContacto(id) {
  // console.log("ID getDataContacto:", id);
  $.ajax({
    type: "POST",
    url: base_url + "/admin/alimentos/getDataContacto",
    dataType: "json",
    data: {
      id: id,
      _token: $('input[name="_token"]').val(),
    },
    success: function (response) {
      // console.log("Respuesta del servidosr:", response);

      // Verificamos que la respuesta tenga al menos un objeto (asumiendo que es un array)
      if (response.length > 0) {
        $("#idCont").val(response[0].Id_contacto);
        $("#nombreCont").val(response[0].Nombre);
        $("#deptCont").val(response[0].Departamento);
        $("#puestoCont").val(response[0].Puesto);
        $("#emailCont").val(response[0].Email);
        $("#telCont").val(response[0].Telefono);
        $("#celCont").val(response[0].Celular);
      } else {
        //console.log("No se encontraron datos del contacto.");
      }
    },
    error: function (xhr, status, error) {
      console.error("Error al obtener los datos:", error);
      alert("Ocurrió un error al consultar los datos del contacto.");
    },
  });
}
function setGenFolio() {
  const fecha = $("#fechaMuestreo").val();
  const idCot = $("#idCot").val();

  if (!fecha) {
    alert("Necesitas la fecha de muestreo para generar el folio");
    return;
  }

  const data = {
    id: idCot,
    fecha: fecha,
    _token: $('input[name="_token"]').val(),
  };

  console.log("Datos enviados:", data);

  // Deshabilitar mientras se procesa
  $("#fechaMuestreo").attr("disabled", true);

  $.ajax({
    url: base_url + "/admin/alimentos/setGenFolioSol",
    type: "GET",
    data: data,
    dataType: "json",
    success: function (response) {
      console.log("Respuesta del servidor:", response);

      // Si viene folio, lo cargamos en el input
      if (response.folio) {
        $("#Folio").val(response.folio);
      }

      // Mostrar mensaje siempre
      if (response.msg) {
        alert(response.msg);
      }

      // Habilitar nuevamente
      $("#fechaMuestreo").attr("disabled", false);
    },
    error: function (xhr, status, error) {
      console.error("Error AJAX:", error);
      alert("Hubo un error al generar el folio");
      $("#fechaMuestreo").attr("disabled", false);
    },
  });
}
