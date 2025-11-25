var idSol = 0;

$(document).ready(function () {
    $("#btnImprimir").click(function () {
        if (idSol === null || idSol === 0) {
            alert("No ha seleccionado una solicitud");
        } else {
            window.open(base_url + "/admin/alimentos/exportPdfOrden/" + idSol);
        }
    });
    $("#ELIMINADAS").on("change", function () {
        getOrden();
    });

    getOrden();
    $("#btnCreate").on("click", function () {
        window.open(base_url + "/admin/alimentos/orden-servicio/create-orden");
    });
    $("#btnCreateIngreso").on("click", function () {
        window.open(
            base_url + "/admin/alimentos/orden-servicio/create-orden-ingreso"
        );
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

       $("#btnEdit2").on("click", function () {
        if (idSol != 0) {
            window.open(
                base_url + "/admin/alimentos/orden-servicio/edit-ordenCI/" + idSol
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
    const mostrarEliminadas = $("#ELIMINADAS").is(":checked") ? 1 : 0;

    $.ajax({
        type: "GET",
        url: base_url + "/admin/alimentos/getOrden",
        dataType: "json",
        data: {
            eliminadas: mostrarEliminadas,
        },
        success: function (response) {
            // console.log(response);
            if (response.status === "success") {
                let solicitudes = response.data;
                let tableBody = "";
                if ($.fn.DataTable.isDataTable("#tablaSolicitud")) {
                    $("#tablaSolicitud").DataTable().clear().destroy();
                }
                solicitudes.forEach(function (solicitud) {
                 let rowClass = solicitud.Cancelado == 1 ? 'style="background-color: #f8d7da;"' : '';

                    tableBody += `
                        <tr ${rowClass}  data-id-cotizacion="${
                            solicitud.Id_cotizacion || ""
                        }">
                            <td>${solicitud.Id_solicitud || ""}</td>
                            <td>${solicitud.Folio || ""}</td>
                            <td>${solicitud.Norma || ""}</td>
                            <td>${solicitud.Sucursal || ""}</td>
                            <td>${solicitud.Fecha_muestreo || ""}</td>
                            <td>${
                                solicitud.usuario && solicitud.usuario.name
                                    ? solicitud.usuario.name
                                    : "N/A"
                            }</td>
                            <td>${solicitud.Observacion || ""}</td>
                        </tr>
                    `;
                });

                $("#tablaSolicitud tbody").html(tableBody);

                let table = $("#tablaSolicitud").DataTable({
                    ordering: false,
                    paging: false,
                    destroy: true,
                    language: {
                        lengthMenu: "# _MENU_ por pagina",
                        zeroRecords: "No hay datos encontrados",
                        info: "Pagina _PAGE_ de _PAGES_",
                        infoEmpty: "No hay datos encontrados",
                    },
                    initComplete: function () {
                        this.api()
                            .columns()
                            .every(function () {
                                var that = this;
                                $("input", this.header()).on(
                                    "keyup change clear",
                                    function () {
                                        if (that.search() !== this.value) {
                                            that.search(this.value).draw();
                                        }
                                    }
                                );
                            });
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
                });
            } else {
                alert("Error al obtener los datos de solicitudes.");
            }
        },
    });
}
