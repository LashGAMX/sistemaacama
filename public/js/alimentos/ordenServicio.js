let idSol = 0;
let table;

$(document).ready(function () {
  bindEvents();
  loadSolicitudes();
});

/* Inicializa DataTable una sola vez */
function initDataTable(data = []) {
  table = $("#tablaSolicitud").DataTable({
    data: data, // pasamos los datos iniciales
    ordering: false,
    paging: false,
    language: {
      lengthMenu: "# _MENU_ por página",
      zeroRecords: "No hay datos encontrados",
      info: "Página _PAGE_ de _PAGES_",
      infoEmpty: "No hay datos encontrados",
    },
    createdRow: function (row, dataRow, dataIndex) {
      // dataRow es el array de la fila, usamos response.data[dataIndex] para Cancelado
      if (responseData[dataIndex].Cancelado == 1) {
        $(row).css("background-color", "#f8d7da"); // 🔴 toda la fila
      }
    },
    initComplete: function () {
      // Filtros por columnas
      this.api()
        .columns()
        .every(function () {
          let that = this;
          $("input", this.header()).on("keyup change clear", function () {
            if (that.search() !== this.value) {
              that.search(this.value).draw();
            }
          });
        });
    },
  });

  // Selección de filas
  $("#tablaSolicitud tbody").on("click", "tr", function () {
    if ($(this).hasClass("selected")) {
      $(this).removeClass("selected");
      idSol = 0;
    } else {
      table.$("tr.selected").removeClass("selected");
      $(this).addClass("selected");
      idSol = $(this).find("td:first").text();
    }
  });
}

/* Carga solicitudes y refresca la tabla*/
let responseData = []; // variable global para createdRow

function loadSolicitudes() {
  const mostrarEliminadas = $("#ELIMINADAS").is(":checked") ? 1 : 0;

  $.ajax({
    type: "GET",
    url: base_url + "/admin/alimentos/getOrden",
    dataType: "json",
    data: { eliminadas: mostrarEliminadas },
    success: function (response) {
      if (response.status === "success") {
        responseData = response.data;

        const rows = response.data.map((solicitud) => [
          solicitud.Id_solicitud || "",
          solicitud.Folio || "",
          solicitud.Norma || "",
          solicitud.Sucursal || "",
          solicitud.Fecha_muestreo || "",
          solicitud.usuario?.name || "N/A",
          solicitud.usuario2?.name || "N/A",
        ]);

        if (!$.fn.DataTable.isDataTable("#tablaSolicitud")) {
          initDataTable(rows);
        } else {
          table.clear().rows.add(rows).draw();
        }
      } else {
        alert("Error al obtener los datos de solicitudes.");
      }
    },
  });
}

/* Eventos principales*/
function bindEvents() {
  // Checkbox eliminadas
  $("#ELIMINADAS").on("change", function () {
    loadSolicitudes();
  });

  // Botón imprimir
  $("#btnImprimir").click(function () {
    if (!idSol) {
      alert("No ha seleccionado una solicitud");
    } else {
      window.open(base_url + "/admin/alimentos/exportPdfOrden/" + idSol);
    }
  });

  // Crear orden
  $("#btnCreate").click(function () {
    window.open(base_url + "/admin/alimentos/orden-servicio/create-orden");
  });

  // Crear ingreso
  $("#btnCreateIngreso").click(function () {
    if (
      confirm(
        "Este método no es editable ni cuenta con guardado temporal. Una vez creado se dará entrada al laboratorio."
      )
    ) {
      window.open(
        base_url + "/admin/alimentos/orden-servicio/create-orden-ingreso"
      );
    }
  });

  // Editar orden
  $("#btnEdit").click(function () {
    if (idSol) {
      window.open(
        base_url + "/admin/alimentos/orden-servicio/edit-orden/" + idSol
      );
    } else {
      alert("Hay que seleccionar una orden de servicio para editar");
    }
  });

  // Cancelar orden
  $("#btnCancelar").click(function () {
    if (!idSol) {
      alert("Hay que seleccionar una orden de servicio para cancelar");
      return;
    }

    if (confirm("Estás por cancelar una solicitud. ¿Deseas continuar?")) {
      $("#inputIdSol").val(idSol);
      $("#modalCancelar").modal("show");
    }
  });

  // Confirmar cancelación
  $(document).on("click", "#confirmarCancelacion", function () {
    const motivo = $("#motivo").val().trim();
    if (!motivo) {
      alert("Por favor, escribe el motivo de cancelación.");
      return;
    }

    const id = $("#inputIdSol").val();

    $.ajax({
      type: "POST",
      url: base_url + "/admin/alimentos/CancelarOrden",
      dataType: "json",
      data: {
        id,
        motivo,
        _token: $('meta[name="csrf-token"]').attr("content"),
      },
      success: function (resp) {
        if (resp.success) {
          alert("Solicitud cancelada");
          window.location.href = base_url + "/admin/alimentos/orden-servicio";
        } else {
          alert("No se pudo cancelar la solicitud");
        }
      },
      error: function () {
        alert("Ocurrió un error al cancelar la solicitud");
      },
    });

    $("#modalCancelar").modal("hide");
  });

  // Editar CI
  $("#btnEdit2").click(function () {
    if (idSol) {
      window.open(
        base_url + "/admin/alimentos/orden-servicio/edit-ordenCI/" + idSol
      );
    } else {
      alert("Hay que seleccionar una orden de servicio para editar");
    }
  });

  // Duplicar orden
  $("#btnDuplicar").click(function () {
    if (!idSol) {
      alert("Hay que seleccionar una orden de servicio para duplicar");
      return;
    }

    $.ajax({
      type: "POST",
      url: base_url + "/admin/alimentos/DuplicarSolAlimentos",
      dataType: "json",
      data: {
        id: idSol,
        _token: $('meta[name="csrf-token"]').attr("content"),
      },
      success: function (response) {
        if (response.success) {
          alert("Solicitud duplicada. Nuevo ID: " + response.new_id);
          window.location.href = base_url + "/admin/alimentos/orden-servicio";
        } else {
          alert("Error al duplicar la solicitud");
        }
      },
      error: function () {
        alert("Ocurrió un error al duplicar la solicitud");
      },
    });
  });

  // Atajos de teclado
  $(document).on("keydown", function (e) {
    // Ctrl + E
    if (e.ctrlKey && e.key.toLowerCase() === "e") {
      e.preventDefault();
      if (idSol) {
        window.open(
          base_url + "/admin/alimentos/orden-servicio/edit-orden/" + idSol
        );
      } else {
        alert("Hay que seleccionar una orden de servicio para editar");
      }
    }

    // Ctrl + P
    if (e.ctrlKey && e.key.toLowerCase() === "p") {
      e.preventDefault();
      if (idSol) {
        window.open(base_url + "/admin/alimentos/exportPdfOrden/" + idSol);
      } else {
        alert("No ha seleccionado una solicitud");
      }
    }
  });
}
