var idSol = 0;
var idPunto = 0;
$(document).ready(function () {
 let selectedId = null;
  let typingTimer;
  const typingDelay = 300; // milisegundos (evita llamadas rápidas)

  InformeData();

  // Filtro por columnas (con debounce)
  $(document).on("keyup change", "#informeTable thead input", function (e) {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(() => {
      let filters = {};
      $("#informeTable thead input").each(function (i) {
        let columnName = [
          "Id_solicitud",
          "Folio",
          "Empresa",
          "Norma",
          "Servicio",
        ][i];
        filters[columnName] = $(this).val();
      });

      // guarda la selección actual
      let selectedRow = $("#informeTable tbody tr.selected");
      if (selectedRow.length) {
        selectedId = selectedRow.data("id");
      }

      InformeData(filters, selectedId);
    }, typingDelay);
  });

  function InformeData(filters = {}, selectedId = null) {
    $.ajax({
      url: base_url + "/admin/alimentos/InformeData",
      type: "GET",
      data: filters,
      dataType: "json",
      success: function (response) {
        let tbody = $("#informeTable tbody");
        tbody.empty();

        if (response.length === 0) {
          tbody.append(
            '<tr><td colspan="5" class="text-center">No hay Registros en la Tabla.<br>(Revise con Recepción de Muestras)</td></tr>'
          );
          return;
        }

        response.forEach(function (item) {
          let selectedClass =
            selectedId && selectedId === item.Id_solicitud ? "selected" : "";

          let row = `
            <tr class="${selectedClass}" data-id="${item.Id_solicitud}">
              <td>${item.Id_solicitud}</td>
              <td>${item.Folio}</td>
              <td>${item.Empresa}</td>
              <td>${item.Norma}</td>
              <td>${item.Servicio}</td>
            </tr>`;
          tbody.append(row);
        });

        // Click para seleccionar fila
        $("#informeTable tbody tr")
          .off("click")
          .on("click", function (e) {
            e.stopPropagation(); // <-- evita que dispare otros eventos
            $("#informeTable tbody tr").removeClass("selected");
            $(this).addClass("selected");
            let id = $(this).data("id");
            selectedId = id;
            getPuntoMuestro(id);
          });
      },
      error: function (xhr, status, error) {
        console.error("Error en la petición:", error);
      },
    });
  }

  // Click en botón Imprimir
  $("#btnImprimir").on("click", function () {
    imprimirRegistroSeleccionado();
  });

  // Detectar Ctrl + P
  $(document).on("keydown", function (e) {
    if (e.ctrlKey && e.key.toLowerCase() === "p") {
      e.preventDefault(); // evita el diálogo de impresión del navegador
      imprimirRegistroSeleccionado();
    }
  });
});
// Función para imprimir el registro seleccionado
function imprimirRegistroSeleccionado() {
  let filaSeleccionada = $("#informeTable tbody tr.selected");
  if (filaSeleccionada.length === 0) {
    alert("Selecciona un registro primero.");
    return;
  }
  let punto = $("#puntoMuestreo").val();
  if (punto) {
    window.open(base_url + "/admin/alimentos/exportPdfInforme/" + punto);
  } else {
    alert("No se ha seleccionado un punto de muestreo.");
  }
}
// Función que carga la tabla
function InformeData(filters = {}) {
  $.ajax({
    url: base_url + "/admin/alimentos/InformeData",
    type: "GET",
    data: filters,
    dataType: "json",
    success: function (response) {
      //console.log(response);
      let tbody = $("#informeTable tbody");
      tbody.empty();

      if (response.length === 0) {
        tbody.append(
          '<tr><td colspan="3" class="text-center">No hay Registros en la Tabla.<br>(Revise con Recepción de Muestras)</td></tr>'
        );
      } else {
        response.forEach(function (item) {
          let row = `<tr data-id="${item.Id_solicitud}">
                                   <td>${item.Id_solicitud}</td>
                                   <td>${item.Folio}</td>
                                   <td>${item.Empresa}</td>
                                   <td>${item.Norma}</td>
                                   <td>${item.Servicio}</td>


                               </tr>`;
          tbody.append(row);
        });

        // Click para seleccionar fila
        $("#informeTable tbody tr")
          .off("click")
          .on("click", function () {
            $("#informeTable tbody tr").removeClass("selected");
            $(this).addClass("selected");
            let id = $(this).data("id");
            getPuntoMuestro(id);
          });
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la petición:", error);
    },
  });
}
function getPuntoMuestro(id) {
  let tabla = document.getElementById("selPuntos");
  let tab = "";

  $.ajax({
    url: base_url + "/admin/alimentos/getPuntoMuestro",
    type: "POST", //método de envio
    data: {
      id: id,
      _token: $('input[name="_token"]').val(),
    },
    dataType: "json",
    async: false,
    success: function (response) {
      //   console.log(response);
      tab = "";
      tab += '<select class="form-control" id="puntoMuestreo">';
      $.each(response.model, function (key, item) {
        tab +=
          '  <option value="' +
          item.Id_muestra +
          '">' +
          item.Muestra +
          "</option>";
      });
      tab += "</select>";
      tabla.innerHTML = tab;
      $("#puntoMuestreo").trigger("change");
    },
  });
}
