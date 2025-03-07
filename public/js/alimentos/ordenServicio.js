var idSol = 0;

$(document).ready(function () {
    $("#btnImprimir").click(function () {
        if (idSol === null || idSol === 0) {
            alert("No ha seleccionado una solicitud");
        } else {
            window.open(base_url + "/admin/alimentos/exportPdfOrden/" + idSol);
        }
    });

    getOrden();
    $("#btnCreate").on("click", function () {
        window.open(base_url + "/admin/alimentos/orden-servicio/create-orden");
    });
    $("#btnCreateIngreso").on("click", function () {
        window.open(base_url + "/admin/alimentos/orden-servicio/create-orden-ingreso");
    });
    $("#btnEdit").on("click", function () {
        if (idSol != 0) {
            window.open(
                base_url + "/admin/alimentos/orden-servicio/edit-orden/" + idSol
            );
        } else {
            alert(
                "Hay que seleccionar una orden de servicio para poder editar"
            );
        }
    });
    $("#btnDuplicar").click(function () {
        let selectedRow = $("#tablaSolicitud tbody tr.selected");
        if (selectedRow.length > 0) {
            let idCotizacion = selectedRow.data("id-cotizacion");
            if (idCotizacion) {
                window.location =
                    base_url +
                    "/admin/alimentos/duplicarSolicitud/" +
                    idCotizacion;
            } else {
                alert("El Id_cotizacion no es válido.");
            }
        } else {
            alert("Primero debes seleccionar una solicitud.");
        }
    });
});

let idCotizacion = null;
function getOrden() {
    $.ajax({
        type: "GET",
        url: base_url + "/admin/alimentos/getOrden",
        dataType: "json",
        success: function (response) {
            if (response.status === "success") {
                let solicitudes = response.data;
                let tableBody = "";

                solicitudes.forEach(function (solicitud) {
                    tableBody += `
                        <tr data-id-cotizacion="${
                            solicitud.Id_cotizacion
                                ? solicitud.Id_cotizacion
                                : ""
                        }">
                            <td>${
                                solicitud.Id_solicitud
                                    ? solicitud.Id_solicitud
                                    : ""
                            }</td>
                            <td>${solicitud.Folio ? solicitud.Folio : ""}</td>
                            <td>${
                                solicitud.Cliente ? solicitud.Cliente : ""
                            }</td>
                            <td>${
                                solicitud.Fecha_muestreo
                                    ? solicitud.Fecha_muestreo
                                    : ""
                            }</td>
                            <td>${
                                solicitud.Observacion
                                    ? solicitud.Observacion
                                    : ""
                            }</td>
                        </tr>
                    `;
                });
                $("#tablaSolicitud tbody").html(tableBody);
                let table = $("#tablaSolicitud").DataTable({
                    ordering: false,
                    paging: false,
                    language: {
                        lengthMenu: "# _MENU_ por pagina",
                        zeroRecords: "No hay datos encontrados",
                        info: "Pagina _PAGE_ de _PAGES_",
                        infoEmpty: "No hay datos encontrados",
                    },
                });
                table.on("click", "tbody tr", (e) => {
                    let classList = e.currentTarget.classList;
                    if (classList.contains("selected")) {
                        classList.remove("selected");
                        idSol = null;
                    } else {
                        table
                            .rows(".selected")
                            .nodes()
                            .each((row) => row.classList.remove("selected"));
                        classList.add("selected");
                        idSol = $(e.currentTarget).find("td:first").html();
                    }
                    if (idSol !== null) {
                        console.log("ID seleccionado:", idSol);
                    } else {
                        console.log("No se ha seleccionado ningún ID.");
                    }
                });
            } else {
                alert("Error al obtener los datos de solicitudes.");
            }
        },
    });
}
