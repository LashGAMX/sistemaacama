let idSucursal2 = 0;


const currentUserId = document
  .querySelector('meta[name="current-user-id"]')
  .getAttribute("content");

const idClienteElement = document.getElementById("idCliente");
const idCliente = idClienteElement ? idClienteElement.textContent.trim() : "";

const estadosMexicanos = [
  { nombre: "Aguascalientes" },
  { nombre: "Baja California" },
  { nombre: "Baja California Sur" },
  { nombre: "Campeche" },
  { nombre: "Chiapas" },
  { nombre: "Chihuahua" },
  { nombre: "Coahuila" },
  { nombre: "Colima" },
  { nombre: "Ciudad de México" },
  { nombre: "Durango" },
  { nombre: "Guanajuato" },
  { nombre: "Guerrero" },
  { nombre: "Hidalgo" },
  { nombre: "Jalisco" },
  { nombre: "Estado de México" },
  { nombre: "Morelos" },
  { nombre: "Nayarit" },
  { nombre: "Nuevo León" },
  { nombre: "Oaxaca" },
  { nombre: "Puebla" },
  { nombre: "Querétaro" },
  { nombre: "Quintana Roo" },
  { nombre: "San Luis Potosí" },
  { nombre: "Sinaloa" },
  { nombre: "Sonora" },
  { nombre: "Tabasco" },
  { nombre: "Tamaulipas" },
  { nombre: "Tlaxcala" },
  { nombre: "Veracruz" },
  { nombre: "Yucatán" },
  { nombre: "Zacatecas" },
];

//function para cambiar el formato de la fecha sin la hora
function formatDate(dateString) {
  const date = new Date(dateString);
  const day = date.getDate().toString().padStart(2, "0");
  const month = (date.getMonth() + 1).toString().padStart(2, "0");
  const year = date.getFullYear();
  return `${year}-${month}-${day}`;
}
function obtenerIdSucursal() {
  const idSucursalElement = document.getElementById("idSucursal");
  const idSucursalText = idSucursalElement.textContent.trim();
  return idSucursalText.replace("Id_cliente: ", "").trim();
}
function tabalaclientes() {
  let tabla = document.getElementById("TableCliente");  
  if (!tabla) {
    tabla = document.createElement("table");
    tabla.id = "TableCliente";
    tabla.className = "table table-sm";
    document.getElementById("SucurcalCliente").appendChild(tabla);
  }

  $.ajax({
    type: "GET",
    url: base_url + "/admin/clientes/datosClientes/" + idCliente,
    dataType: "json",
    async: false,
    success: function (response) {
      const tablasucursal = response.datos;
      let tipo = "";
      let tab = "";
      let color = "";

      tab += '<thead class="thead-dark">';
      tab += "<tr>";
      tab += "<th>ID</th>";
      tab += "<th>Nombre</th>";
      tab += "<th>Estado</th>";
      tab += "<th>Tipo de Cliente</th>";
      tab += "<th>Acción</th>";
      tab += "</tr>";
      tab += "</thead>";
      tab += "<tbody>";

      tablasucursal.forEach((element) => {
        color = element.deleted_at !== null ? "bg-danger" : "";

        tab += "<tr>";
        tab += '<td class="' + color + '">' + element.Id_sucursal + "</td>";
        tab += '<td class="' + color + '">' + element.Empresa + "</td>";
        tab += '<td class="' + color + '">' + element.Estado + "</td>";

        if (element.Id_siralab === 1) {
          tipo = "Reporte";
        } else if (element.Id_siralab === 2) {
          tipo = "Reporte / Siralab";
        } else {
          tipo = "No seleccionado";
        }
        tab += '<td class="' + color + '">' + tipo + "</td>";

        tab += '<td class="' + color + '">';
        tab +=
          '<button type="button" class="btn btn-warning boton-editar" data-id="' +
          element.Id_sucursal +
          '" data-toggle="modal" data-target="#ModalSucursal"><i class="voyager-edit"></i><span hidden-sm hidden-xs>editar</span></button>';
        tab +=
          '<button type="button" class="btn btn-primary boton-ver" data-id="' +
          element.Id_sucursal +
          '"><i></i><span>Ver</span></button>';
        tab += "</td>";
        tab += "</tr>";
      });

      tab += "</tbody>";

      tabla.innerHTML = tab;

      if ($.fn.DataTable.isDataTable("#TableCliente")) {
        $("#TableCliente").DataTable().clear().destroy();
      }

      $("#TableCliente").DataTable({
        ordering: false,
        language: {
          lengthMenu: "# _MENU_ por página",
          zeroRecords: "No hay datos encontrados",
          info: "Página _PAGE_ de _PAGES_",
          infoEmpty: "No hay datos encontrados",
        },
        scrollY: "500px",
        scrollCollapse: true,
        paging: true,
      });

      $("#TableCliente").on("click", ".boton-editar", function () {
        idSucursal = $(this).data("id");
        ConSuc(idSucursal);
        $("#modalLabel").text("Editar Sucursal");
        $("#guardar").hide();
        $("#cambios").show();
      });
      $("#TableCliente").on("click", ".boton-ver", function () {
        var idSucursal = $(this).data("id");
        idSucursal2 = $(this).data("id");
        carga(idSucursal);
        TablaRFC(idSucursal);
        TablaDirReport(idSucursal);
        TablaPM(idSucursal);
        TablaConcesión(idSucursal);
        TablaDireccionSiralab(idSucursal);
        TablePuntoSiralab(idSucursal);
        TablaDatosGen(idSucursal);
        puntosir(idSucursal);
      });
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error al cargar datos:", textStatus, errorThrown);
    },
  });
}

$(document).ready(function () {

// Cuando se abre el modal
$('#DatosModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); 
    var modal = $(this);

    var idSucursal = button.data('#idsucursal'); // PASARLO AQUÍ
    var idContacto = button.data('id'); // solo si es editar

    if (button.attr('id') === 'btnCrearDatos') {
        // Crear
        modal.find('#GDGen12').show();   
        modal.find('#GDGen').hide();  
        modal.find('.modal-title').text('Crear Nuevos Datos');
        $('#FormDatoGen')[0].reset();

        // Evento de crear
        $("#GDGen12").off("click").on("click", function(e){
            e.preventDefault();
            GuardarEdicion(null, idSucursal2); // idContacto=null indica creación
        });

    } else if (button.hasClass('boton-editardatos')) {
        // Editar
        modal.find('#GDGen12').hide(); 
        modal.find('#GDGen').show();    
        modal.find('.modal-title').text('Editar Datos');

        // Cargar datos del contacto
        $.ajax({
            type: "GET",
            url: base_url + "/admin/clientes/GetDatosGen/" + idContacto,
            dataType: "JSON",
            success: function(response){
                if(!response.dato) return alert("Datos no encontrados.");
                const dato = response.dato;
                $("#nombre").val(dato.nombre);
                $("#departamento").val(dato.departamento);
                $("#puesto").val(dato.puesto);
                $("#email").val(dato.email);
                $("#celular").val(dato.celular);
                $("#telefono").val(dato.telefono);
            }
        });


        // Evento de guardar edición
        $("#GDGen").off("click").on("click", function(e){
            e.preventDefault();
            GuardarEdicion(idContacto, idSucursal2);
            //console.log("Envio",idContacto,idSucursal2);
        });
        $("#GDGen12").off("click").on("click", function(e){
            e.preventDefault();
            GuardarEdicion(null, idSucursal2);
            //console.log("Envio ",idSucursal2);
        });
    }
});



  $("#estado").select2({
    dropdownParent: $("#ModalSucursal"),
    placeholder: "Seleccione un estado",
    allowClear: true,
  });
  cargarEstados();
  tabalaclientes();

  $("#NombreMatrix").change(function () {
    if ($(this).is(":checked")) {
      obtenerNombreMatrix(idCliente);
    } else {
      $("#empresa").val("");
    }
  });
  $("#modalpunto").click(function () {
    const datos = {
      Idpunto: $("#idTituloConcecion").val(),
      Activo: $("#activo").is(":checked"), // true o false
      Siralab: $("#siralab").is(":checked"), // true o false
      Pozos: $("#pozos").is(":checked"), // true o false
      Titulo: $("#titulopuntosir").val(),
      Punto: $("#PuntoSirLaB").val(),
      Anexo: $("#anexo").val(),
      Cuerpo: $("#cuerpo option:selected").val(),
      UsoAgua: $("#usoAgua option:selected").val(),
      Categoria: $("#categoria option:selected").val(),
      Grados: $("#Grados").val(),
      Minutos: $("#Minutos").val(),
      Segundos: $("#Segundos").val(),
      Grados2: $("#Grados2").val(),
      Minutos2: $("#Minutos2").val(),
      Segundos2: $("#Segundos2").val(),
      Hora: $("#hora").val(), // formato: HH:MM
      Fechainico: $("#fechaInicio").val(), // formato: YYYY-MM-DD
      FechaTermino: $("#fechaTermino").val(), // formato: YYYY-MM-DD
      Observaciones: $("#observaciones").val(), // texto libre
      Sucursal: $("#idPunto").val(),
    };

    CreatePuntosir(datos);
    ////console.log("Datos del formulario:", datos);
    // O puedes mostrar los datos en un alert en formato JSON
    //alert(JSON.stringify(datos, null, 2));
  });

  $("#ModalSucursal").on("click", "#cambios", function () {
    ConSuc(idSucursal);
  });

  $("#botonModal").on("click", function () {
    $("#formEditSucursal")[0].reset();
    $("#estado").val(null).trigger("change");
    $("#tipo").val("0");
    $("#deleted_at").prop("checked", false);

    $("#modalLabel").text("Crear Sucursal Cliente");
    $("#guardar").show();
    $("#cambios").hide();
  });
$("#CrearDir").on("click", function () {
    $("#ModalDirSir #ModalDirSirLabel").text(
      "Crear Nueva Dirección de Reporte Siralab"
    );

    let idSucursal = $("#idSucursal").text().replace("Id_cliente: ", "").trim();

    $.ajax({
      url: base_url + "/admin/clientes/conreg",
      type: "POST",
      data: {
        idSucursal: idSucursal,
        _token: $('meta[name="csrf-token"]').attr("content"),
      },
      success: function (response) {
        let select = $("#NoTitulo");
        select.empty(); // Limpiamos opciones anteriores

        // Opción por defecto
        select.append(
          $("<option>", {
            value: "",
            text: "-- Selecciona un título --",
            disabled: true,
            selected: true,
          })
        );

        if (response.length > 0) {
          response.forEach(function (item) {
            select.append(
              $("<option>", {
                value: item.Id_titulo,
                text: item.Titulo,
              })
            );
          });
        } else {
          select.append(
            $("<option>", { value: "", text: "No hay títulos disponibles" })
          );
        }
      },
      error: function (xhr) {
        console.error("Error:", xhr.responseText);
      },
    });

    $("#btnModalCrear").show();
    $("#btnModal").hide();
    CrearDirRepSir();

    

});

  
  $("#cambios").click(function () {
    const datos = {
      idSucursal: idSucursal,
      empresa: $("#empresa").val(),
      estado: $("#estado").val(),
      tipo: $("#tipo").val(),
      deleted_at: $("#deleted_at").is(":checked")
        ? null
        : new Date().toISOString(),
    };

    EditarSuc(datos);
  });
  $("#guardar").click(function () {
    const info = {
      idCliente: idCliente,
      empresa: $("#empresa").val(),
      estado: $("#estado").val(),
      tipo: $("#tipo").val(),
      deleted_at: $("#deleted_at").is(":checked")
        ? null
        : new Date().toISOString(),
      idUser: currentUserId,
    };

    crear(info);
  });
  document
    .getElementById("rfcForm")
    .addEventListener("submit", function (event) {
      event.preventDefault();
      crearRFC();
    });
  document
    .getElementById("direcForm")
    .addEventListener("submit", function (event) {
      event.preventDefault();
      NuevaDireccion();
    });
  document
    .getElementById("PuntoMuestreo")
    .addEventListener("submit", function (event) {
      event.preventDefault();
      NuevoPunto();
    });

  document
    .getElementById("concesion")
    .addEventListener("submit", function (event) {
      event.preventDefault();
      NuevoTConcesion();
    });

  $("#btnCrearMuestreo").on("click", function () {
    const idCliente = $("#idCliente").text().trim();

    $("#modalMuestreo #modalTitle").text("Crear Punto de Muestreo Siralab");
  });
});

function cargarEstados() {
  const estadoSelect = $("#estado");
  estadoSelect.empty();
  $.each(estadosMexicanos, function (index, estado) {
    estadoSelect.append(new Option(estado.nombre, estado.id, false, false));
  });
  estadoSelect.trigger("change");
}
function ConSuc(idSucursal) {
  $.ajax({
    type: "GET",
    url: base_url + "/admin/clientes/Consuc/" + idSucursal,
    dataType: "json",
    success: function (response) {
      if (response.error) {
        console.error(response.error);
        return;
      }

      const datos = response.datos;
      ////console.log(datos);
      $("#empresa").val(datos.Empresa);

      $("#estado").val(datos.Estado).trigger("change");

      const tipoSelect = $("#tipo");
      tipoSelect.val(datos.Id_siralab);

      if (datos.Id_siralab !== 1 && datos.Id_siralab !== 2) {
        tipoSelect.val("0");
      }
      // editarSuc(idSucursal);
      $("#deleted_at").prop("checked", datos.deleted_at == null);
    },
  });
}
function obtenerNombreMatrix(idCliente) {
  $.ajax({
    type: "GET",
    url: base_url + "/admin/clientes/Nombrematrix/" + idCliente,
    dataType: "json",
    success: function (response) {
      // //console.log('Nombres de cliente:', response);
      if (response && response.Nombres) {
        $("#empresa").val(response.Nombres);
      } else if (response.error) {
        console.error("Error:", response.error);
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error(
        "Error al obtener el nombre de la matriz:",
        textStatus,
        errorThrown
      );
    },
  });
}
function EditarSuc(datos) {
  ////console.log('dato',datos)
  $.ajax({
    type: "POST",
    url: base_url + "/admin/clientes/EditarSuc",
    data: JSON.stringify(datos),
    contentType: "application/json; charset=utf-8",
    dataType: "json",
    success: function (response) {
      if (response.success) {
        alert(response.message);

        tabalaclientes();
      } else {
        alert("Error al guardar los datos: " + response.message);
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error al guardar datos:", textStatus, errorThrown);
      alert("Error al guardar datos");
    },
  });
}
function crear(info) {
 ////console.log("info:", info);

  $.ajax({
    type: "POST",
    url: base_url + "/admin/clientes/CrearSuc",
    data: JSON.stringify(info),
    contentType: "application/json; charset=utf-8",
    dataType: "json",
    success: function (response) {
      if (response.success) {
        alert(response.message);

        tabalaclientes();
      } else {
        alert("Error al guardar los datos: " + response.message);
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error al guardar datos:", textStatus, errorThrown);
      alert("Error: Faltaron llenar campos! ");
    },
  });
}
function carga(idSucursal) {
  $.ajax({
    type: "GET",
    url: base_url + "/admin/clientes/Consuc/" + idSucursal,
    dataType: "json",
    success: function (response) {
      if (response.error) {
        console.error(response.error);
        return;
      }

      const datos = response.datos;

      // Actualizar el contenido de los elementos
      $("#idSucursal")
        .text("Id_cliente: " + idSucursal)
        .css("color", "Blue");
      $("#idempresa")
        .text("Sucursal: " + datos.Empresa)
        .css("color", "Blue");
      $("#idestado")
        .text("Estado: " + datos.Estado)
        .css("color", "Blue");
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX: ", error);
    },
  });
}
function TablaRFC(idSucursal) {
  $.ajax({
    type: "GET",
    url: base_url + "/admin/clientes/TablaRFC/" + idSucursal,
    dataType: "JSON",
    async: false,
    success: function (response) {
      ////console.log(response);
      const Tabla = response.datos;
      let tab = "";
      let color = "";

      tab += '<thead class="thead-dark">';
      tab += "<tr>";
      tab += "<th>ID</th>";
      tab += "<th>RFC</th>";
      tab += "<th>Creación</th>";
      tab += "<th>Modificación</th>";
      tab += "<th>Acción</th>";
      tab += "</tr>";
      tab += "</thead>";
      tab += "<tbody>";
      Tabla.forEach((element) => {
        tab += "<tr>";
        if (element.deleted_at == null) {
          color = "";
        } else {
          color = "bg-danger";
        }
        tab += '<td class="' + color + '">' + element.Id_rfc + "</td>";
        tab += '<td class="' + color + '">' + element.RFC + "</td>";
        tab +=
          '<td class="' +
          color +
          '">' +
          formatDate(element.created_at) +
          "</td>";
        tab +=
          '<td class="' +
          color +
          '">' +
          formatDate(element.updated_at) +
          "</td>";
        tab += '<td class="' + color + '">';
        tab +=
          '<button type="button" class="btn btn-info boton-editarRFC" data-id="' +
          element.Id_rfc +
          '" data-toggle="modal" data-target="#Modal"><i class="voyager-edit"></i><span class="hidden-sm hidden-xs">editar</span></button>';
        tab += "</td>";
        tab += "</tr>";
      });
      tab += "</tbody>";

      // Reemplazar el contenido de la tabla y reinicializar DataTables
      $("#TableRFC").html(tab);

      if ($.fn.DataTable.isDataTable("#TableRFC")) {
        $("#TableRFC").DataTable().clear().destroy();
      }
      $("#TableRFC").DataTable({
        ordering: false,
        language: {
          zeroRecords: "No hay datos encontrados",

          infoEmpty: "No hay datos encontrados",
        },
        scrollY: "500px",
        scrollCollapse: true,
        paging: false,
      });
      $("#TableRFC").on("click", ".boton-editarRFC", function () {
        var Rfc = $(this).data("id");

        EditRfc(Rfc);
      });
    },
  });
}
function TablaDirReport(idSucursal) {
  $.ajax({
    type: "GET",
    url: base_url + "/admin/clientes/TablaDirReport/" + idSucursal,
    dataType: "JSON",
    async: false,
    success: function (response) {
      ////console.log(response);
      const Tabla = response.datos;
      let tab = "";
      let color = "";

      tab += '<thead class="thead-dark">';
      tab += "<tr>";
      tab += "<th>ID</th>";
      tab += "<th>Dirección</th>";
      tab += "<th>Creación</th>";
      tab += "<th>Modificación</th>";
      tab += "<th>Acción</th>";
      tab += "</tr>";
      tab += "</thead>";
      tab += "<tbody>";
      Tabla.forEach((element) => {
        tab += "<tr>";
        if (element.deleted_at == null) {
          color = "";
        } else {
          color = "bg-danger";
        }
        tab += '<td class="' + color + '">' + element.Id_direccion + "</td>";
        tab += '<td class="' + color + '">' + element.Direccion + "</td>";
        tab +=
          '<td class="' +
          color +
          '">' +
          formatDate(element.created_at) +
          "</td>";
        tab +=
          '<td class="' +
          color +
          '">' +
          formatDate(element.updated_at) +
          "</td>";
        tab += '<td class="' + color + '">';
        tab +=
          '<button type="button" class="btn btn-info boton-editar" data-id="' +
          element.Id_direccion +
          '" data-toggle="modal" data-target="#Modal"><i class="voyager-edit"></i><span class="hidden-sm hidden-xs">editar</span></button>';
        tab += "</td>";
        tab += "</tr>";
      });
      tab += "</tbody>";

      // Reemplazar el contenido de la tabla y reinicializar DataTables
      $("#TablaDirReport").html(tab);

      if ($.fn.DataTable.isDataTable("#TablaDirReport")) {
        $("#TablaDirReport").DataTable().clear().destroy();
      }
      $("#TablaDirReport").DataTable({
        ordering: false,
        language: {
          zeroRecords: "No hay datos encontrados",

          infoEmpty: "No hay datos encontrados",
        },
        scrollY: "500px",
        scrollCollapse: true,
        paging: false,
      });
      $("#TablaDirReport").on("click", ".boton-editar", function () {
        var direccion = $(this).data("id");

        Editdireccion(direccion);
      });
    },
  });
}
function TablaPM(idSucursal) {
  $.ajax({
    type: "GET",
    url: base_url + "/admin/clientes/TablaPM/" + idSucursal,
    dataType: "JSON",
    success: function (response) {
      if (response.datos) {
        const Tabla = response.datos;
        let tab = "";
        let color = "";

        tab += '<thead class="thead-dark">';
        tab += "<tr>";
        tab += "<th>ID</th>";
        tab += "<th>Punto de Muestreo</th>";
        tab += "<th>Creación</th>";
        tab += "<th>Modificación</th>";
        tab += "<th>Acción</th>";
        tab += "</tr>";
        tab += "</thead>";
        tab += "<tbody>";
        Tabla.forEach((element) => {
          tab += "<tr>";
          color = element.deleted_at == null ? "" : "bg-danger";
          tab += '<td class="' + color + '">' + element.Id_punto + "</td>";
          tab +=
            '<td class="' + color + '">' + element.Punto_muestreo + "</td>";
          tab +=
            '<td class="' +
            color +
            '">' +
            formatDate(element.created_at) +
            "</td>";
          tab +=
            '<td class="' +
            color +
            '">' +
            formatDate(element.updated_at) +
            "</td>";
          tab += '<td class="' + color + '">';
          tab +=
            '<button type="button" class="btn btn-info boton-editar" data-id="' +
            element.Id_punto +
            '" data-toggle="modal" data-target="#Modal"><i class="voyager-edit"></i><span class="hidden-sm hidden-xs">editar</span></button>';
          tab += "</td>";
          tab += "</tr>";
        });
        tab += "</tbody>";

        $("#TablaPM").html(tab);

        if ($.fn.DataTable.isDataTable("#TablaPM")) {
          $("#TablaPM").DataTable().clear().destroy();
        }
        $("#TablaPM").DataTable({
          ordering: false,
          language: {
            zeroRecords: "No hay datos encontrados",
            infoEmpty: "No hay datos encontrados",
          },
          scrollY: "500px",
          scrollCollapse: true,
          paging: false,
        });
        $("#TablaPM").on("click", ".boton-editar", function () {
          var punto = $(this).data("id");

          EditPunto(punto);
        });
      } else {
        alert("Datos no encontrados.");
      }
    },
    error: function (xhr, status, error) {
      console.error("Error al cargar los datos de la tabla:", xhr.responseText);
    },
  });
}
function TablaConcesión(idSucursal) {
  $.ajax({
    type: "GET",
    url: base_url + "/admin/clientes/TablaConcesión/" + idSucursal,
    dataType: "JSON",
    async: false,
    success: function (response) {
      ////console.log(response);
      const Tabla = response.datos;
      let tab = "";
      let color = "";

      tab += '<thead class="thead-dark">';
      tab += "<tr>";
      tab += "<th>ID</th>";
      tab += "<th>Titulo</th>";
      tab += "<th>Creación</th>";
      tab += "<th>Modificación</th>";
      tab += "<th>Acción</th>";
      tab += "</tr>";
      tab += "</thead>";
      tab += "<tbody>";
      Tabla.forEach((element) => {
        tab += "<tr>";
        if (element.deleted_at == null) {
          color = "";
        } else {
          color = "bg-danger";
        }
        tab += '<td class="' + color + '">' + element.Id_titulo + "</td>";
        tab += '<td class="' + color + '">' + element.Titulo + "</td>";
        tab +=
          '<td class="' +
          color +
          '">' +
          formatDate(element.created_at) +
          "</td>";
        tab +=
          '<td class="' +
          color +
          '">' +
          formatDate(element.updated_at) +
          "</td>";
        tab += '<td class="' + color + '">';
        tab +=
          '<button type="button" class="btn btn-info boton-editar" data-id="' +
          element.Id_titulo +
          '" data-toggle="modal" data-target="#Modal"><i class="voyager-edit"></i><span class="hidden-sm hidden-xs">editar</span></button>';
        tab += "</td>";
        tab += "</tr>";
      });
      tab += "</tbody>";

      $("#TablaConcesión").html(tab);

      if ($.fn.DataTable.isDataTable("#TablaConcesión")) {
        $("#TablaConcesión").DataTable().clear().destroy();
      }
      $("#TablaConcesión").DataTable({
        ordering: false,
        language: {
          zeroRecords: "No hay datos encontrados",
          infoEmpty: "No hay datos encontrados",
        },
        scrollY: "500px",
        scrollCollapse: true,
        paging: false,
      });
      $("#TablaConcesión").on("click", ".boton-editar", function () {
        var titulo = $(this).data("id");
        EditTitulo(titulo);
      });
    },
  });
}
function TablaDireccionSiralab(idSucursal) {
  $.ajax({
    type: "GET",
    url: base_url + "/admin/clientes/TablaDireccionSiralab/" + idSucursal,
    dataType: "JSON",
    async: false,
    success: function (response) {
      ////console.log(response);
      const Tabla = response.datos;
      let tab = "";
      let color = "";

      tab += '<thead class="thead-dark">';
      tab += "<tr>";
      tab += "<th>Titulo</th>";
      tab += "<th>Calle</th>";
      tab += "<th>Num Ext</th>";
      tab += "<th>Num Int</th>";
      tab += "<th>Estado</th>";
      tab += "<th>Colonia</th>";
      tab += "<th>C.P.</th>";
      tab += "<th>Ciudad</th>";
      tab += "<th>Localidad</th>";
      tab += "<th>Acción</th>";
      tab += "</tr>";
      tab += "</thead>";
      tab += "<tbody>";
      Tabla.forEach((element) => {
        tab += "<tr>";
        if (element.deleted_at == null) {
          color = "";
        } else {
          color = "bg-danger";
        }
        tab +=
          '<td class="' + color + '">' + element.Titulo_concesion + "</td>";
        tab += '<td class="' + color + '">' + element.Calle + "</td>";
        tab += '<td class="' + color + '">' + element.Num_exterior + "</td>";
        tab += '<td class="' + color + '">' + element.Num_interior + "</td>";
        tab += '<td class="' + color + '">' + element.Estado + "</td>";
        tab += '<td class="' + color + '">' + element.Colonia + "</td>";
        tab += '<td class="' + color + '">' + element.CP + "</td>";
        tab += '<td class="' + color + '">' + element.Ciudad + "</td>";
        tab += '<td class="' + color + '">' + element.Localidad + "</td>";

        tab += '<td class="' + color + '">';
        tab +=
          '<button type="button" class="btn btn-info boton-editar" data-id="' +
          element.Titulo_concesion +
          '" data-toggle="modal" data-target="#Modal"><i class="voyager-edit"></i><span class="hidden-sm hidden-xs">editar</span></button>';
        tab += "</td>";
        tab += "</tr>";
      });
      tab += "</tbody>";

      $("#TablaDireccionSiralab").html(tab);

      if ($.fn.DataTable.isDataTable("#TablaDireccionSiralab")) {
        $("#TablaDireccionSiralab").DataTable().clear().destroy();
      }
      $("#TablaDireccionSiralab").DataTable({
        ordering: false,
        language: {
          zeroRecords: "No hay datos encontrados",
          infoEmpty: "No hay datos encontrados",
        },
        scrollY: "500px",
        scrollCollapse: true,
        paging: false,
      });
      $("#TablaDireccionSiralab").on("click", ".boton-editar", function () {
        var direccionReporteSir = $(this).data("id");
        EditDirReporteSir(direccionReporteSir);
      });
    },
  });
}
function TablePuntoSiralab(idSucursal) {
  $.ajax({
    type: "GET",
    url: base_url + "/admin/clientes/TablePuntoSiralab/" + idSucursal,
    dataType: "JSON",
    async: false,
    success: function (response) {
      ////console.log(response);
      const Tabla = response.datos;
      let tab = "";
      let color = "";

      tab += '<thead class="thead-dark">';
      tab += "<tr>";
      tab += "<th>Titulo</th>";
      tab += "<th>Punto</th>";
      tab += "<th>Anexo</th>";
      tab += "<th>Siralab</th>";
      tab += "<th>Pozos</th>";
      tab += "<th>Cuerpo Receptor</th>";
      tab += "<th>Uso Agua</th>";
      tab += "<th>Latitud</th>";
      tab += "<th>Longitud</th>";

      tab += "<th>Hora</th>";
      tab += "<th>Fecha ini</th>";
      tab += "<th>Fecha TER</th>";

      tab += "<th>Acción</th>";
      tab += "</tr>";
      tab += "</thead>";
      tab += "<tbody>";
      Tabla.forEach((element) => {
        tab += "<tr>";
        if (element.deleted_at == null) {
          color = "";
        } else {
          color = "bg-danger";
        }
        tab +=
          '<td class="' + color + '">' + element.Titulo_consecion + "</td>";
        tab += '<td class="' + color + '">' + element.Punto + "</td>";
        tab += '<td class="' + color + '">' + element.Anexo + "</td>";
        tab += '<td class="' + color + '">' + element.Siralab + "</td>";
        tab += '<td class="' + color + '">' + element.Pozos + "</td>";
        tab += '<td class="' + color + '">' + element.Cuerpo_receptor + "</td>";
        tab += '<td class="' + color + '">' + element.Uso_agua + "</td>";
        tab += '<td class="' + color + '">' + element.Latitud + "</td>";
        tab += '<td class="' + color + '">' + element.Longitud + "</td>";
        tab += '<td class="' + color + '">' + element.Hora + "</td>";
        tab += '<td class="' + color + '">' + element.F_inicio + "</td>";
        tab += '<td class="' + color + '">' + element.F_termino + "</td>";
        tab += '<td class="' + color + '">';
        tab +=
          '<button type="button" class="btn btn-info boton-editar" ' +
          'data-id="' +
          element.Id_punto +
          '" ' +
          'data-toggle="modal" data-target="#modalMuestreo">' +
          '<i class="voyager-edit"></i><span class="hidden-sm hidden-xs">editar</span></button>';
        tab += "</td>";
        tab += "</tr>";
      });
      tab += "</tbody>";

      $("#TablePuntoSiralab").html(tab);
      $(document).on("click", ".boton-editar", function () {
        const Idpunto = $(this).data("id");

        // Colocas el valor en el input del modal
        $("#idTituloConcecion").val(Idpunto);

        $.ajax({
          type: "POST",
          url: base_url + "/admin/clientes/updatePun",
          data: { Idpunto },
          dataType: "json",
          success: function (data) {
            if (data.length > 0) {
              $("#titulopuntosir").val(data[0].Titulo_consecion);
              $("#PuntoSirLaB").val(data[0].Punto);
              $("#anexo").val(data[0].Anexo);
              $("#cuerpo").val(data[0].Cuerpo_receptor);
              $("#usoAgua").val(data[0].Uso_agua);
              $("#categoria").val(data[0].Categoria);
              $("#Grados").val(data[0].GradosLat);
              $("#Minutos").val(data[0].MinutosLat);
              $("#Segundos").val(data[0].SegundosLat);
              $("#Grados2").val(data[0].GradosLong);
              $("#Minutos2").val(data[0].MinutosLong);
              $("#Segundos2").val(data[0].SegundosLong);
              $("#hora").val(data[0].Hora);
              $("#fechaInicio").val(data[0].F_inicio),
                $("#fechaTermino").val(data[0].F_termino),
                $("#observaciones").val(data[0].Observacion);
              $("#activo").prop("checked", data[0].deleted_at === null);
              $("#siralab").prop("checked", data[0].Siralab == 1);
              $("#pozos").prop("checked", data[0].Pozos == 1);
            }
          },
        });

        // Cambias el título del modal
        $("#modalTitle").text("Editar Punto de Muestreo Siralab");
      });
      if ($.fn.DataTable.isDataTable("#TablePuntoSiralab")) {
        $("#TablePuntoSiralab").DataTable().clear().destroy();
      }

      $("#TablePuntoSiralab").DataTable({
        ordering: false,
        language: {
          zeroRecords: "No hay datos encontrados",
          infoEmpty: "No hay datos encontrados",
        },
        scrollY: "500px",
        scrollCollapse: true,
        paging: false,
      });
    },
  });
}
// Función para cargar la tabla de datos
function TablaDatosGen(idSucursal) {
  $.ajax({
    type: "GET",
    url: base_url + "/admin/clientes/TablaDatosGen/" + idSucursal,
    dataType: "JSON",
    success: function (response) {
      const tabla = response.datos;
      let tab = `
        <thead class="thead-dark">
          <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Departamento</th>
            <th>Puesto</th>
            <th>Email</th>
            <th>Celular</th>
            <th>Telefono</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
      `;

      tabla.forEach((element) => {
        tab += `
          <tr>
            <td>${element.Id_contacto}</td>
            <td>${element.Nombre}</td>
            <td>${element.Departamento}</td>
            <td>${element.Puesto}</td>
            <td>${element.Email}</td>
            <td>${element.Celular}</td>
            <td>${element.Telefono}</td>
            <td>
              <button type="button" class="btn btn-info boton-editardatos" 
                      data-id="${element.Id_contacto}" 
                      data-toggle="modal" data-target="#DatosModal">
                <i class="voyager-edit"></i><span class="hidden-sm hidden-xs">editar</span>
              </button>
            </td>
          </tr>
        `;
      });

      tab += "</tbody>";
      $("#Tablageneral").html(tab);

      // Inicializar DataTable
      if ($.fn.DataTable.isDataTable("#Tablageneral")) {
        $("#Tablageneral").DataTable().clear().destroy();
      }

      $("#Tablageneral").DataTable({
        ordering: false,
        language: {
          zeroRecords: "No hay datos encontrados",
          infoEmpty: "No hay datos encontrados",
        },
        scrollY: "500px",
        scrollCollapse: true,
        paging: false,
      });
    },
    error: function (xhr, status, error) {
      console.error("Error en la consulta AJAX: ", status, error);
    },
  });
}

function GuardarEdicion(idContacto, idSucursal2) {
  //console.log("idsuc",idSucursal2);
    const newNom   = $("#nombre").val();
    const newNDep  = $("#departamento").val();
    const newPuest = $("#puesto").val();
    const newEmail = $("#email").val();
    const newCel   = $("#celular").val();
    const newTel   = $("#telefono").val();


    $.ajax({
        type: "POST",
        url: base_url + "/admin/clientes/EditarDatos",
        data: {
            id: idContacto,       
            idSucursal2: idSucursal2,
            nombre: newNom,
            departamento: newNDep,
            puesto: newPuest,
            email: newEmail,
            celular: newCel,
            telefono: newTel,
        },
        dataType: "JSON",
        success: function(response){
            if(response.success){
                alert(response.message);
                TablaDatosGen(idSucursal);
                $("#DatosModal").modal("hide");
            } else {
                alert("Error: " + response.message);
            }
        },
        error: function(xhr){
            alert("Error: " + (xhr.responseJSON?.message || "Error inesperado"));
        }
    });
}



function EditRfc(Rfc) {
  $.ajax({
    type: "GET",
    url: base_url + "/admin/clientes/GetRFCDetails/" + Rfc,
    dataType: "JSON",
    success: function (response) {
      const data = response.data;

      $("#modalText").val(data.RFC);
      $("#modalStatus").prop("checked", data.status);
      $("#saveButton").data("id-rfc", Rfc);
      $("#editModal #modalLabel").text("Editar RFC del cliente");

      // Muestra el modal
      $("#editModal").modal("show");
    },
    error: function () {
      alert("Error al cargar los datos del RFC.");
    },
  });

  $("#saveButton")
    .off("click")
    .on("click", function () {
      const Rfc = $(this).data("id-rfc");
      const newRfc = $("#modalText").val();
      const status = $("#modalStatus").is(":checked");

      $.ajax({
        type: "POST",
        url: base_url + "/admin/clientes/UpdateRFC",
        contentType: "application/json",
        data: JSON.stringify({
          id_rfc: Rfc,
          rfc: newRfc,
          status: status,
        }),
        dataType: "JSON",
        success: function (response) {
          if (response.success) {
            alert("RFC actualizado con éxito.");
            $("#editModal").modal("hide");
            TablaRFC(Rfc);
          } else {
            alert("Error al actualizar el RFC.");
          }
        },
        error: function (xhr, status, error) {
          console.error("Error:", xhr.responseText);
          alert("Error al comunicar con el servidor.");
        },
      });
    });
}
function Editdireccion(direccion) {
  //console.log(direccion);
  $.ajax({
    type: "GET",
    url: base_url + "/admin/clientes/GetDirDetails/" + direccion,
    dataType: "JSON",
    success: function (response) {
      const datos = response.datos;
      $("#modalText").val(datos.Direccion);
      $("#modalStatus").prop("checked", datos.status);
      $("#saveButton").data("Id_direccion", direccion);
      $("#editModal #modalLabel").text("Editar Dirección");

      $("#editModal").modal("show");
    },
    error: function () {
      alert(
        "Error al consultar la dirección, es posible que haya sido eliminada."
      );
    },
  });

  $("#saveButton")
    .off("click")
    .on("click", function () {
      const direccion = $(this).data("Id_direccion");
      const newdir = $("#modalText").val();
      const status = $("#modalStatus").is(":checked");

      // Datos a enviar al servidor
      const data = {
        direccion: direccion,
        newdir: newdir,
        status: status,
      };
      ////console.log("Datos a enviar al servidor:", data);
      $.ajax({
        type: "POST",
        url: base_url + "/admin/clientes/UpdateDIRrep",
        contentType: "application/json",
        data: JSON.stringify(data),
        success: function (response) {
          //  //console.log("Respuesta del servidor:", response);
          if (response.success) {
            alert("Dirección actualizada con éxito.");
            $("#editModal").modal("hide");
            TablaDirReport(direccion);
          } else {
            alert("Error al actualizar la dirección.");
          }
        },
        error: function (xhr, status, error) {
          console.error(
            "Error al comunicar con el servidor:",
            xhr.responseText
          );
        },
      });
    });
}
function EditPunto(punto) {
  $.ajax({
    type: "GET",
    url: base_url + "/admin/clientes/GetPunDetails/" + punto,
    dataType: "JSON",
    success: function (response) {
      if (response.datos) {
        const data = response.datos;
        $("#modalText").val(data.punto);
        $("#modalStatus").prop("checked", data.status);

        $("#saveButton").data("Id_punto", punto);
        $("#modalLabel").text("Editar Punto de Muestreo");
        $("#editModal #modalLabel").text("Editar Punto de Muestreo");

        $("#editModal").modal("show");

        // //console.log(data); // Para depuración
      } else {
        alert("Datos no encontrados.");
      }
    },
    error: function () {
      alert("Error al cargar los datos del Punto de Muestreo.");
    },
  });

  $("#saveButton")
    .off("click")
    .on("click", function () {
      const punto1 = $(this).data("Id_punto");
      const newpunto = $("#modalText").val();
      const status = $("#modalStatus").is(":checked");
      const idSucursal = obtenerIdSucursal();
      // Datos a enviar al servidor
      const data = {
        punto1: punto1,
        newpunto: newpunto,
        status: status,
        idSucursal: idSucursal,
      };

      //console.log("Datos a enviar al servidor:", data);

      $.ajax({
        type: "POST",
        url: base_url + "/admin/clientes/UpdatePunto",
        contentType: "application/json",
        data: JSON.stringify(data),
        success: function (response) {
          if (response.success) {
            alert("Punto actualizado con éxito.");
            $("#editModal").modal("hide");
            // Reemplaza `punto` con el ID o parámetro correcto, si necesario
            TablaPM(idSucursal);
          } else {
            alert("Error al actualizar el punto.");
          }
        },
        error: function (xhr, status, error) {
          console.error(
            "Error al comunicar con el servidor:",
            xhr.responseText
          );
        },
      });
    });
}
function EditTitulo(titulo) {
  $.ajax({
    type: "GET",
    url: base_url + "/admin/clientes/GetTituloDetails/" + titulo,
    dataType: "JSON",
    success: function (response) {
      if (response.datos) {
        const data = response.datos;
        $("#modalText").val(data.titulo);
        $("#modalStatus").prop("checked", data.status);
        $("#saveButton").data("Id_titulo", titulo);
        $("#editModal #modalLabel").text("Editar Titulo de Consesión");

        $("#editModal").modal("show"); // Muestra el modal
      } else {
        alert("Datos no encontrados.");
      }
    },
    error: function () {
      alert("Error al cargar los datos del Título de Consesión.");
    },
  });

  $("#saveButton")
    .off("click")
    .on("click", function () {
      const titulo = $(this).data("Id_titulo");
      const newtitulo = $("#modalText").val();
      const status = $("#modalStatus").is(":checked");
      const idSucursal = obtenerIdSucursal();
      // Datos a enviar al servidor
      const data = {
        titulo: titulo,
        newtitulo: newtitulo,
        status: status,
        idSucursal: idSucursal,
      };

      // //console.log("Datos a enviar al servidor:", data);

      $.ajax({
        type: "POST",
        url: base_url + "/admin/clientes/UpdateTitulo",
        contentType: "application/json",
        data: JSON.stringify(data),
        success: function (response) {
          if (response.success) {
            alert("Titulo de Consesión actualizado con éxito.");
            $("#editModal").modal("hide");
            // Reemplaza `punto` con el ID o parámetro correcto, si necesario
            TablaConcesión(idSucursal);
          } else {
            alert("Error al actualizar el punto.");
          }
        },
        error: function (xhr, status, error) {
          console.error(
            "Error al comunicar con el servidor:",
            xhr.responseText
          );
        },
      });
    });
}
function EditDirReporteSir(direccionReporteSir) {
  $.ajax({
    type: "GET",
    url:
      base_url + "/admin/clientes/GetDirSiralbDetails/" + direccionReporteSir,
    dataType: "JSON",
    success: function (response) {
      if (response.datos) {
        const data = response.datos;

        // Actualizar los campos con los datos recibidos
        const select = $("#NoTitulo");
        select.empty();
        const option = $("<option>").val(data.TituloId).text(data.Tu);
        select.append(option);
        select.val(data.TituloId);
        $("#calle").val(data.Calle);
        $("#numExt").val(data.Noext);
        $("#numInt").val(data.Noint);
        $("#estadodir").val(data.Estado);
        $("#colonia").val(data.Colonia);
        $("#cp").val(data.Cp);
        $("#ciudad").val(data.Ciudad);
        $("#localidad").val(data.Localidad);
        $("#Status").prop("checked", data.Status);
        $("#ModalDirSir #ModalDirSirLabel").text(
          "Editar Dirección de Reporte Siralab"
        );

        // Mostrar el modal para edición
        $("#btnModalCrear").hide();
        $("#btnModal").show();
        $("#ModalDirSir").modal("show");
      } else {
        alert("Datos No Encontrados");
      }
    },
    error: function () {
      alert("Error al cargar los datos de la Dirección Siralab.");
    },
  });

  // Elimina cualquier listener anterior para evitar duplicados
  $("#btnModal")
    .off("click")
    .on("click", function () {
      const idSucursal = obtenerIdSucursal();
      let datos = {};

      // Recolectar los datos del formulario
      datos["Titulo"] = document.getElementById("NoTitulo").value;
      datos["Calle"] = document.getElementById("calle").value;
      datos["NumeroExterior"] = document.getElementById("numExt").value;
      datos["NumeroInterior"] = document.getElementById("numInt").value;
      datos["Estado"] = document.getElementById("estadodir").value;
      datos["Colonia"] = document.getElementById("colonia").value;
      datos["CP"] = document.getElementById("cp").value;
      datos["Ciudad"] = document.getElementById("ciudad").value;
      datos["Localidad"] = document.getElementById("localidad").value;
      datos["Status"] = document.getElementById("Status").checked;
      datos["Id_sucursal"] = idSucursal;

      // Enviar los datos a través de AJAX
      $.ajax({
        url: base_url + "/admin/clientes/UpdateTituloRepSir",
        type: "POST",
        data: datos,
        success: function (response) {
          if (response.success) {
            alert("Datos Actualizados Correctamente");
            TablaDireccionSiralab(idSucursal);
            $("#ModalDirSir").modal("hide");
          } else {
            alert("No se modicaron los datos");
          }
        },
        error: function (xhr) {
          console.error("Error:", xhr.responseText);
        },
      });
    });
}

function CrearDirRepSir() {
  document
    .getElementById("btnModalCrear")
    .addEventListener("click", function () {
      const idSucursal = obtenerIdSucursal();
      let datos = {};

      datos["Titulo"] = document.getElementById("NoTitulo").value;
      datos["Calle"] = document.getElementById("calle").value;
      datos["NumeroExterior"] = document.getElementById("numExt").value;
      datos["NumeroInterior"] = document.getElementById("numInt").value;
      datos["Estado"] = document.getElementById("estadodir").value; //
      datos["Colonia"] = document.getElementById("colonia").value;
      datos["CP"] = document.getElementById("cp").value;
      datos["Ciudad"] = document.getElementById("ciudad").value;
      datos["Localidad"] = document.getElementById("localidad").value;
      datos["Status"] = document.getElementById("Status").checked ? 1 : 0;
      datos["Id_sucursal"] = idSucursal;
      _token: $('input[name="_token"]').val(),
        $.ajax({
          url: base_url + "/admin/clientes/CrearDirRepSir",
          type: "POST",
          data: datos,
          success: function (response) {
            if (response.success) {
              alert("Datos Actualizados Correctamente");
              TablaDireccionSiralab(idSucursal);
              $("#ModalDirSir").modal("hide");
            } else {
              alert("No se modicaron los datos");
            }
          },
          error: function (xhr) {
            console.error("Error:", xhr.responseText);
          },
        });
    });
}

function crearRFC() {
  const idSucursal = obtenerIdSucursal();

  // Obtener los valores del formulario
  const rfc = document.getElementById("RfC").value;
  const status = document.getElementById("idstatus1").checked;

  // Construir el objeto de datos
  const datos = {
    rfc: rfc,
    status: status,
    idSucursal: idSucursal,
  };

  ////console.log("Datos del formulario:", datos);

  $.ajax({
    type: "POST",
    url: base_url + "/admin/clientes/NuevoRFC",
    contentType: "application/json; charset=utf-8",
    data: JSON.stringify(datos),
    dataType: "json",
    success: function (response) {
      if (response.success) {
        alert("RFC Creado con éxito.");
        TablaRFC(idSucursal);
      } else {
        alert("Error al crear el RFC: " + response.message);
      }
    },
  });
}

function NuevaDireccion() {
  const idSucursal = obtenerIdSucursal();

  // Obtener los valores del formulario
  const direccion = document.getElementById("direccion").value;
  const status = document.getElementById("idstatus2").checked;

  // Construir el objeto de datos
  const dato1 = {
    direccion: direccion,
    status: status,
    idSucursal: idSucursal,
  };

  ////console.log("Datos del formulario:", datos);

  $.ajax({
    type: "POST",
    url: base_url + "/admin/clientes/NuevaDireccion",
    contentType: "application/json; charset=utf-8",
    data: JSON.stringify(dato1),
    dataType: "json",
    success: function (response) {
      if (response.success) {
        alert("Direccion Creada con éxito.");
        TablaDirReport(idSucursal);
      } else {
        alert("Error al crear la Direccion ");
      }
    },
  });
}

function NuevoPunto() {
  const idSucursal = obtenerIdSucursal();

  // Obtener los valores del formulario
  const punto = document.getElementById("punto").value;
  const status = document.getElementById("idstatus3").checked;

  // Construir el objeto de datos
  const dato2 = {
    punto: punto,
    status: status,
    idSucursal: idSucursal,
  };

  ////console.log("Datos del formulario:", datos);

  $.ajax({
    type: "POST",
    url: base_url + "/admin/clientes/NuevoPunto",
    contentType: "application/json; charset=utf-8",
    data: JSON.stringify(dato2),
    dataType: "json",
    success: function (response) {
      if (response.success) {
        alert("Punto Creado con éxito.");
        TablaPM(idSucursal);
      } else {
        alert("Error al crear el Punto ");
      }
    },
  });
}

function NuevoTConcesion() {
  const idSucursal = obtenerIdSucursal();

  // Obteniene los valores del formulario
  const titulo = document.getElementById("titulo").value;
  const status = document.getElementById("idstatus4").checked;

  // Construir el objeto de datos
  const dato3 = {
    titulo: titulo,
    status: status,
    idSucursal: idSucursal,
  };
 ////console.log("Datos del formulario:", dato3);  
  $.ajax({
    type: "POST",
    url: base_url + "/admin/clientes/NuevoTConcesion",
    contentType: "application/json; charset=utf-8",
    data: JSON.stringify(dato3),
    dataType: "json",
    success: function (response) {
      if (response.success) {
        alert("Titulo Creado con éxito.");
        TablaConcesión(idSucursal);
      } else {
        alert("Error al crear el Titulo");
      }
    },
  });
}
function puntosir(idSucursal) {
  $("#idPunto").val(idSucursal);
  $.ajax({
    type: "POST",
    url: base_url + "/admin/clientes/Precon",
    data: { idSucursal: idSucursal },
    dataType: "json",
    success: function (data) {
      // //console.log(data);
      const $tituloSel = $("#titulopuntosir");
      $tituloSel.empty().append('<option value="">Sin seleccionar</option>');

      // data.titulos es ahora un objeto { Id_titulo: Titulo }
      Object.entries(data.titulos).forEach(([id, titulo]) => {
        $tituloSel.append($("<option>", { value: id, text: titulo }));
      });

      const $categoriaSel = $("#categoria");
      $categoriaSel.empty().append('<option value="">Sin seleccionar</option>');
      data.categorias.forEach((cat) => {
        $categoriaSel.append(
          $("<option>", { value: cat.Id_categoria, text: cat.Categoria })
        );
      });

      const $cuerpoSel = $("#cuerpo")
        .empty()
        .append('<option value="">Sin seleccionar</option>');

      data.cuerpo.forEach((cue) => {
        $cuerpoSel.append(
          $("<option>", { value: cue.Id_tipo, text: cue.Cuerpo })
        );
      });

      const detallesData = data.detalle;

      $cuerpoSel.on("change", function () {
        const selectedIdTipo = $(this).val();

        const $usoAguaSel = $("#usoAgua")
          .empty()
          .append('<option value="">Sin seleccionar</option>');

        const detallesFiltrados = detallesData.filter(
          (det) => det.Id_tipo == selectedIdTipo
        );

        detallesFiltrados.forEach((det) => {
          $usoAguaSel.append(
            $("<option>", { value: det.Id_detalle, text: det.Detalle })
          );
        });
      });
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud:", error);
    },
  });
}
function CreatePuntosir(datos) {
  $.ajax({
    type: "POST",
    url: base_url + "/admin/clientes/CreatePunSir",
    contentType: "application/json; charset=utf-8",
    data: JSON.stringify(datos),
    dataType: "json",
    success: function (response) {
      if (response.success) {
        alert(response.message);
      } else {
        alert("Error al crear el Punto Siralab");
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
    },
  });
}

//   $.ajax({
//     type: "POST",
//     url: base_url + "/admin/clientes/tituloPunSir/"+id,
//     contentType: "application/json; charset=utf-8",
//     dataType: "json",
//     success: function (response) {
//     //   //console.log("Respuesta del servidor:", response);

//     //   Limpiar el select y agregar la opción inicial
//     //   $("#titulopuntosir").empty().append('<option selected>Sin seleccionar</option>');

//     //   Llenar el select con los títulos recibidos
//     //   response.forEach(function (item) {
//     //     $("#titulopuntosir").append(
//     //       '<option value="' + item.Id_titulo + '">' + item.Titulo + '</option>'
//     //     );
//     //   });
//     },
//     error: function (xhr, status, error) {
//       console.error("Error en la solicitud:", error);
//     }
//   });
//}
