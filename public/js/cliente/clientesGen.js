$(document).ready(function () {
  getclientes();

  $("#intermediarioSelectCrear").select2();
  $("#intermediarioSelectEditar").select2();

  $("#crearClienteGen").click(function () {
    setClientesGen();
  });

  $(document).ready(function () {
    $("#editarClienteGen").click(function () {
      upClientesGen();
      setTimeout(() => {
        getClientes();
        alert("Cliente Editado");
      }, 100);
    });
  });
  $(document).on("click", ".boton-editar", function () {
    idSeleccionado = $(this)[0].parentNode.parentNode.children[0].innerHTML;
    let nombreSeleccionado =
      $(this)[0].parentNode.parentNode.children[1].innerHTML;
    let intermediarioSeleccionado =
      $(this)[0].parentNode.parentNode.children[2].innerHTML;
      let user =
      $(this)[0].parentNode.parentNode.children[8].innerHTML;
       let pass =
      $(this)[0].parentNode.parentNode.children[9].innerHTML;
      
    let activo = $(this)[0].getAttribute("activo");
    if (activo == "si") {
      $("#activoCheckEditar").attr("checked", true);
    } else {
      $("#activoCheckEditar").attr("checked", false);
    }
    $("#nombreClienteEditar").val(nombreSeleccionado);
    $("#intermediarioSelectEditar").val(intermediarioSeleccionado).change();
    $("#Users").val(user).change();
      $("#Pass").val(pass).change();
    
  });

  $(document).on("click", ".boton-ver", function () {
    idSeleccionado = $(this)[0].parentNode.parentNode.children[0].innerHTML;
    window.location.href =
      base_url + "/admin/clientes/clientesGenDetalle/" + idSeleccionado;
  });
});

var idSeleccionado;

// function getclientes() {
//     let color = "";
//     let tabla = document.getElementById("clientesGen");

//     if (!tabla) {
//         console.error("No ESTA EL ELEMENTO CARNAL.");
//         return;
//     }
//     let tab = "";
//     $.ajax({
//         type: "POST",
//         url: base_url + "/admin/clientes/getClientesGen",
//         data: {},
//         dataType: "json",
//         async: false,
//         success: function (response) {
//             const listaClientesGen = response.clienteGen;
//             tab += '<table id="tablaClientesGen" class="table table-sm">';
//             tab += '    <thead class="thead-dark">';
//             tab += "        <tr>";
//             tab += "          <th>Id</th>";
//             tab += "          <th>Cliente</th>";
//             tab += "          <th>Id inter</th>";
//             tab += "          <th>Intermediario</th> ";
//             tab += "          <th>Creación</th> ";
//             tab += "          <th>Creó</th> ";
//             tab += "          <th>Modificación</th> ";
//             tab += "          <th>Modificó</th> ";
//             tab += "          <th>Acciones</th>";
//             tab += "      </tr> ";
//             tab += "    </thead> ";
//             tab += "    <tbody> ";

//             listaClientesGen.forEach((element) => {
//                 if (element.deleted_at == null) {
//                     color = "";
//                 } else {
//                     color = "danger";
//                 }
//                 tab += "<tr>";
//                 tab +=
//                     '<td class="bg-' +
//                     color +
//                     '">' +
//                     element.Id_cliente +
//                     "</td>";
//                 tab +=
//                     '<td class="bg-' + color + '">' + element.Empresa + "</td>";
//                 tab +=
//                     '<td class="bg-' +
//                     color +
//                     '">' +
//                     element.Id_intermediario +
//                     "</td>";
//                 tab +=
//                     '<td class="bg-' + color + '">' + element.Nombres + "</td>";
//                 tab +=
//                     '<td class="bg-' +
//                     color +
//                     '">' +
//                     element.created_at +
//                     "</td>";
//                 tab +=
//                     '<td class="bg-' +
//                     color +
//                     '">' +
//                     element.Id_user_c +
//                     "</td>";
//                 tab +=
//                     '<td class="bg-' +
//                     color +
//                     '">' +
//                     element.updated_at +
//                     "</td>";
//                 tab +=
//                     '<td class="bg-' +
//                     color +
//                     '">' +
//                     element.Id_user_m +
//                     "</td>";
//                 tab += "<td>";
//                 tab +=
//                     '<button type="button" class="btn btn-warning boton-editar" activo="si" data-toggle="modal" data-target="#modalEditar"><i class="voyager-edit"></i><span hidden-sm hidden-xs>editar</span></button>';
//                 tab +=
//                     '<button type="button" class="btn btn-primary boton-ver"><i class="voyager-external"></i><span hidden-sm hidden-xs>Ver</span></button>';
//                 tab += "</td>";
//                 tab += "</tr>";
//             });
//             tab += "    </tbody> ";
//             tab += "</table>";

//             tabla.innerHTML = tab;

//             $("#tablaClientesGen").DataTable({
//                 language: {
//                     lengthMenu: "_MENU_ por página",
//                     zeroRecords: "No hay datos encontrados",
//                     info: "Página _PAGE_ de _PAGES_",
//                     infoEmpty: "No hay datos encontrados",
//                 },
//                 scrollY: "500px",
//                 scrollCollapse: true,
//                 paging: true,
//                 ordering: false,
//                 pageLength: 100, // <-- Esto establece 100 registros por página por defecto
//             });
//             // Filtro personalizado: solo busca en las columnas 1 (Cliente) y 3 (Intermediario)
//             $.fn.dataTable.ext.search.push(function (
//                 settings,
//                 data,
//                 dataIndex
//             ) {
//                 let searchTerm = table.search().toLowerCase();

//                 // Columnas 1 y 3 (según tu HTML: Empresa y Nombres)
//                 let col1 = data[1].toLowerCase(); // Cliente
//                 let col3 = data[3].toLowerCase(); // Intermediario

//                 // Verifica si el término está en alguna de las dos columnas
//                 return col1.includes(searchTerm) || col3.includes(searchTerm);
//             });

//             // Fuerza a DataTables a re-ejecutar el filtro cuando se escriba en la barra de búsqueda
//             $("#tablaClientesGen_filter input")
//                 .off()
//                 .on("keyup", function () {
//                     table.search(this.value).draw();
//                 });
//         },
//         error: function (jqXHR, textStatus, errorThrown) {
//             console.error("Error al cargar datos:", textStatus, errorThrown);
//         },
//     });
// }
function getclientes() {
  let color = "";
  let tabla = document.getElementById("clientesGen");

  // if (!tabla) {
  //   console.error("No ESTA EL ELEMENTO CARNAL.");
  //   return;
  // }

  let tab = "";
  $.ajax({
    type: "POST",
    url: base_url + "/admin/clientes/getClientesGen",
    data: {},
    dataType: "json",
    async: false,
    success: function (response) {
      const listaClientesGen = response.clienteGen;
      tab += '<table id="tablaClientesGen" class="table w-100">';
      tab += '  <thead class="thead-dark">';
      tab += "    <tr>";
      tab += "      <th>Id</th>";
      tab +=
        '<th><input type="text" placeholder="Buscar Cliente" class="form-control form-control-sm"/></th>';
      tab += "      <th>Id inter</th>";
      tab +=
        '<th><input type="text" placeholder="Buscar Intermediario" class="form-control form-control-sm"/></th>';
      tab += "      <th>Creación</th>";
      tab += "      <th>Creó</th>";
      tab += "      <th>Modificación</th>";
      tab += "      <th>Modificó</th>";
      tab += "      <th>User</th>";
      tab += "      <th>Pass</th>";
      tab += "      <th>Acciones</th>";
      tab += "    </tr>";
      tab += "  </thead>";
      tab += "  <tbody>";

      listaClientesGen.forEach((element) => {
        color = element.deleted_at == null ? "" : "danger";

        tab += "<tr>";
        tab += `<td class="bg-${color}">${element.Id_cliente}</td>`;
        tab += `<td class="bg-${color}">${element.Empresa}</td>`;
        tab += `<td class="bg-${color}">${element.Id_intermediario}</td>`;
        tab += `<td class="bg-${color}">${element.Nombres} ${element.A_paterno}</td>`;
        tab += `<td class="bg-${color}">${element.created_at}</td>`;
        tab += `<td class="bg-${color}">${element.name_user_c}</td>`;
        tab += `<td class="bg-${color}">${element.updated_at}</td>`;
        tab += `<td class="bg-${color}">${element.name_user_m ?? 'Sin Modificaciónes'}</td>`;
        tab += `<td class="bg-${color}">${element.User ?? 'N/A'}</td>`;
        tab += `<td class="bg-${color}">${element.Password ?? 'N/A'}</td>`;
        tab += `<td>
                            <button type="button" class="btn btn-warning boton-editar" activo="si" data-toggle="modal" data-target="#modalEditar">
                                <i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span>
                            </button>
                            <button type="button" class="btn btn-primary boton-ver">
                                <i class="voyager-external"></i> <span hidden-sm hidden-xs>Ver</span>
                            </button>
                        </td>`;
        tab += "</tr>";
      });

      tab += "  </tbody>";
      tab += "</table>";

      tabla.innerHTML = tab;

      // Inicializar DataTable con filtros por columna
      let table = $("#tablaClientesGen").DataTable({
        language: {
          lengthMenu: "_MENU_ por página",
          zeroRecords: "No hay datos encontrados",
          info: "Página _PAGE_ de _PAGES_",
          infoEmpty: "No hay datos encontrados",
        },
        scrollY: "500px",
        scrollCollapse: true,
        paging: true,
        ordering: false,
        pageLength: 100,
        initComplete: function () {
          // Filtrar solo columnas 1 (Cliente) y 3 (Intermediario)
          this.api()
            .columns([1, 3])
            .every(function () {
              let column = this;
              $("input", column.header()).on("keyup change clear", function () {
                if (column.search() !== this.value) {
                  column.search(this.value).draw();
                }
              });
            });
        },
      });
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error al cargar datos:", textStatus, errorThrown);
    },
  });
}

function setClientesGen() {
  let checked = $("#activoCheck").is(":checked");
  $.ajax({
    type: "POST",
    data: {
      nombres: $("#nombreClienteCrear").val(),
      idIntermediario: $("#intermediarioSelectCrear").val(),
      activoCheck: checked,
      _token: $("meta[name='csrf-token']").attr("content"),
    },
    dataType: "json",
    url: base_url + "/admin/clientes/setClientesGen",
    success: function (response) {
      
      alert("Cliente Creado exitosamente");
      getclientes();
    },
    error: function (xhr, status, error) {
      console.error(xhr.responseText);
      alert("Error al crear el cliente. Por favor, intenta de nuevo.");
    },
  });
}

function upClientesGen() {
  let checked = false;
  if ($("#activoCheckEditar").is(":checked")) {
    checked = true;
  } else {
    checked = false;
  }
  $.ajax({
    type: "POST",
    data: {
      idCliente: idSeleccionado,
      nombres: $("#nombreClienteEditar").val(),
      idIntermediario: $("#intermediarioSelectEditar").val(),
      user: $("#Users").val(),
      pass: $("#Pass").val(),
      activoCheckEditar: checked,
      _token: $("meta[name='csrf-token']").attr("content"),
    },
    dataType: "json",
    url: base_url + "/admin/clientes/upClientesGen",
    success: function (response) {
      console.log(response);
      if (response.success) {
        alert("No se pudo editar el cliente");
      } else {
        alert("Cliente Editado Correctamente");
        getclientes();
      }
    },
    error: function (xhr, status, error) {
      console.error(xhr.responseText);
      alert("Error en la solicitud AJAX");
    },
  });
}
