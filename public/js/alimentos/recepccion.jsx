//evento para registrar  al dar clic al boton guardar
$("#btnRep").click(function () {
    const usuarioSeleccionado = $("#usuario").val();
    const usuarioSeleccionado2 = $("#usuario2").val();

    let nombreUsuario = null;
    let nombreUsuario2 = null;

    // Solo hacer split si hay valor
    if (usuarioSeleccionado) {
        [, nombreUsuario] = usuarioSeleccionado.split("|"); // ignora el ID
    }

    if (usuarioSeleccionado2) {
        [, nombreUsuario2] = usuarioSeleccionado2.split("|");
    }

    const data = {
        Folio: $("#folio").val(),
        Fecha: $("#fecha").val(),
        Fechainicio: $("#fechainicio").val(),
        Fecha2: $("#fecha2").val(),
        resRecep: $("#resRecep").val(),
        resRecep2: $("#resRecep2").val(),
        Fecha3: $("#fecha3").val(),
        analistadesecho: $("#analistadesecho").val(),
        Lugardedesecho: $("#Lugardedesecho").val(),
        idrep: $("#idrep").val(),
        fechareal: $("#fechaoriginal").val(),
        Nombre: nombreUsuario,
        Nombre2: nombreUsuario2,
        _token: $('input[name="_token"]').val(),
    };

    IngresarRecepcion(data);
});
//evento para consultar los datos  al dar clic al boton consultar
$("#btnCon").click(function () {
    const idRep = $("#idrep").val();

    if (idRep) {
        ConsultaReg(idRep);
    } else {
        alert("Selecciona un registro primero.");
    }
});

//funcion para guardar los datos del formulario de las tres pestañas
function IngresarRecepcion(data) {
    $.ajax({
        url: base_url + "/admin/alimentos/IngresarRecepcion",
        type: "POST",
        dataType: "json",
        data: data,
        success: function (response) {
            alert(response.message);
            DatosRecepcion();
        },
        error: function (xhr, status, error) {
            alert("No se pudo registrar la recepción.");
            console.error("Error al ingresar la recepción:", error);
        },
    });
}

//InICIALIZACION DE LAS TABLAS
$(document).ready(function () {
    DatosRecepcion();

    $("#btnBitacora").on("click", function () {
        window.open(base_url + "/admin/alimentos/BitacoraRecep", "_blank");
    });
      $("#btnHistorial").on("click", function () {
        window.open(base_url + "/admin/alimentos/HistorialRecepAli", "_blank");
    });
});

//METODO PARA CARGAR LAS TRES TABLAS (Recepción En Area de alimentos, Resguardo de Alimentos, Desecho Muestras)
function DatosRecepcion() {
    $.ajax({
        url: base_url + "/admin/alimentos/getRecepcionAli",
        type: "GET",
        dataType: "json",
        success: function (response) {
            // Limpiar las tablas antes de llenarlas
            const tableBodyRecepcion = $("#datosrecepcion tbody");
            const tableBodyResguardo = $("#Resguardo tbody");
            const tableBodyDesecho = $("#Desecho tbody");
            tableBodyRecepcion.empty();
            tableBodyResguardo.empty();
            tableBodyDesecho.empty();

            if (response.length > 0) {
                // Llenar la tabla "datosrecepcion"
                response.forEach(function (item) {
                    const rowRecepcion = `
                        <tr data-idrep="${item.Id_rep}">
                            <td class="text-center align-middle"> 
                             <div class="form-check d-flex justify-content-center">
                               <input type="checkbox" class="form-check-input"
                                 ${item.Entrega == 1 ? 'checked' : ''}  
                                 onchange="EntregaMuestra(${item.Id_rep}, this.checked)">
                             </div>
                            </td>
                            <td>${item.Folio}</td>
                            <td>${item.Muestra}</td>
                            <td>${item.Recibio}</td>
                            <td>${item.Fecha}</td>
                            <td>${item.Hora_recepcion}</td>
                            <td>${item.Resguardo}</td>
                            <td>${item.AnalistaRecep}</td>
                            <td>
                            <button type="button" class="btn btn-sm btn-primary" onclick="parametro(${item.Id_muestra})">
                              <i class="fa fa-eye"></i>
                            </button>
                            </td>
                            <td>
                             <button class="btn btn-sm btn-success"   onclick="LiberarReg(${item.Id_rep})" ><i class="fas fa-check"></i>  </button>
                            </td>
                          


                        </tr>
                    `;
                    tableBodyRecepcion.append(rowRecepcion);
                });

                // Inicializar la tabla "datosrecepcion" solo si no está inicializada
                if (!$.fn.dataTable.isDataTable("#datosrecepcion")) {
                    $("#datosrecepcion").DataTable({
                        paging: true,
                        searching: true,
                        info: true,
                        responsive: true,
                        ordering: false,
                        language: {
                            processing: "Procesando...",
                            search: "Buscar:",
                            lengthMenu: "Mostrar _MENU_ registros",
                            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                            infoEmpty: "Mostrando 0 a 0 de 0 registros",
                            infoFiltered:
                                "(filtrado de _MAX_ registros totales)",
                            loadingRecords: "Cargando...",
                            zeroRecords: "No se encontraron resultados",
                            emptyTable: "No hay datos disponibles en la tabla",
                            paginate: {
                                first: "Primero",
                                previous: "Anterior",
                                next: "Siguiente",
                                last: "Último",
                            },
                        },
                    });
                } else {
                    // Si ya está inicializada, recargar la tabla
                    $("#datosrecepcion")
                        .DataTable()
                        .clear()
                        .rows.add(tableBodyRecepcion.children())
                        .draw();
                }

                // Llenar la tabla "Resguardo"
                response.forEach(function (item2) {
                    const rowResguardo = `
                        <tr data-idrep="${item2.Id_rep}">
                            <td>${item2.Folio}</td>
                            <td>${item2.AnalistaRes}</td>
                            <td>${item2.Fecha_inicio}</td>
                            <td>${item2.Fecha_resguardo}</td>
                            <td>${item2.Resguardo2}</td>
                        </tr>
                    `;
                    tableBodyResguardo.append(rowResguardo);
                });

                // Inicializar la tabla "Resguardo" solo si no está inicializada
                if (!$.fn.dataTable.isDataTable("#Resguardo")) {
                    $("#Resguardo").DataTable({
                        paging: true,
                        searching: true,
                        info: true,
                        responsive: true,
                        ordering: false,
                        language: {
                            processing: "Procesando...",
                            search: "Buscar:",
                            lengthMenu: "Mostrar _MENU_ registros",
                            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                            infoEmpty: "Mostrando 0 a 0 de 0 registros",
                            infoFiltered:
                                "(filtrado de _MAX_ registros totales)",
                            loadingRecords: "Cargando...",
                            zeroRecords: "No se encontraron resultados",
                            emptyTable: "No hay datos disponibles en la tabla",
                            paginate: {
                                first: "Primero",
                                previous: "Anterior",
                                next: "Siguiente",
                                last: "Último",
                            },
                        },
                    });
                } else {
                    // Si ya está inicializada, recargar la tabla
                    $("#Resguardo")
                        .DataTable()
                        .clear()
                        .rows.add(tableBodyResguardo.children())
                        .draw();
                }

                // Llenar la tabla "Desecho"
                response.forEach(function (item3) {
                    const rowDesecho = `
                        <tr data-idrep="${item3.Id_rep}">
                            <td>${item3.Folio}</td>
                            <td>${item3.Fecha_desecho}</td>
                            <td>${item3.Analista_desecho}</td>
                            <td>${item3.Lugar_desecho}</td>
                        </tr>
                    `;
                    tableBodyDesecho.append(rowDesecho);
                });

                // Inicializar la tabla "Desecho" solo si no está inicializada
                if (!$.fn.dataTable.isDataTable("#Desecho")) {
                    $("#Desecho").DataTable({
                        paging: true,
                        searching: true,
                        info: true,
                        responsive: true,
                        ordering: false,
                        language: {
                            processing: "Procesando...",
                            search: "Buscar:",
                            lengthMenu: "Mostrar _MENU_ registros",
                            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                            infoEmpty: "Mostrando 0 a 0 de 0 registros",
                            infoFiltered:
                                "(filtrado de _MAX_ registros totales)",
                            loadingRecords: "Cargando...",
                            zeroRecords: "No se encontraron resultados",
                            emptyTable: "No hay datos disponibles en la tabla",
                            paginate: {
                                first: "Primero",
                                previous: "Anterior",
                                next: "Siguiente",
                                last: "Último",
                            },
                        },
                    });
                } else {
                    // Si ya está inicializada, recargar la tabla
                    $("#Desecho")
                        .DataTable()
                        .clear()
                        .rows.add(tableBodyDesecho.children())
                        .draw();
                }

                // Delegación de eventos para las filas de las tablas
                $(document).on(
                    "click",
                    "#datosrecepcion tbody tr",
                    function () {
                        $("#datosrecepcion tbody tr").removeClass("selected");
                        $(this).addClass("selected");
                        const idRep = $(this).data("idrep");

                        updateregistro(idRep);
                        $("#tablaRecepcion").on(
                            "click",
                            ".view-btn",
                            function () {
                                const idRep = $(this).data("idrep");
                                parametro(idRep);
                            }
                        );
                    }
                );

                $(document).on("click", "#Resguardo tbody tr", function () {
                    $("#Resguardo tbody tr").removeClass("selected");
                    $(this).addClass("selected");
                    const idRep = $(this).data("idrep");
                   // console.log("prueba", idRep);
                    updateregistro(idRep);
                });
                $(document).on("click", "#Desecho tbody tr", function () {
                    $("#Desecho tbody tr").removeClass("selected");
                    $(this).addClass("selected");
                    const idRep = $(this).data("idrep");
                    updateregistro(idRep);
                });
            } else {
                // Si no hay datos, mostrar mensaje en las tablas
                tableBodyRecepcion.append(
                    '<tr><td colspan="5" class="text-center">No hay datos disponibles</td></tr>'
                );
                tableBodyResguardo.append(
                    '<tr><td colspan="4" class="text-center">No hay datos disponibles</td></tr>'
                );
                tableBodyDesecho.append(
                    '<tr><td colspan="4" class="text-center">No hay datos disponibles</td></tr>'
                );
            }
        },
        error: function (xhr, status, error) {
            console.error("Error:", xhr.responseText);
            alert(
                "Hubo un error al consultar los datos. Por favor, recargue la página."
            );
        },
    });
}

////función Para imprimir la bitacora
function ImprimirBitacora(idRep) {
    if (!idRep) {
        alert("NO HAS SELECCIONADO EL REGISTRO");
        return;
    }

    const url = base_url + "/admin/alimentos/BitacoraPdf/" + idRep;

    // Abre el PDF en una nueva pestaña
    window.open(url, "_blank");
}

//Actualizacion de los datos acorde al que se selecciona
function updateregistro(idRep) {
    //console.log("Id_rep seleccionado:", idRep);

    $.ajax({
        url: base_url + "/admin/alimentos/getRecepcion",
        type: "POST",
        dataType: "json",
        data: { idRep: idRep },
        success: function (response) {
            // Mostrar los datos obtenidos en consola
            // console.log("Datos de la recepción:", response);

            // Asignar valores a los inputs
            $("#idrep").val(response.Id_rep || "");
            $("#folio").val(response.Folio || "");
        },
        error: function (xhr, status, error) {
            console.error("Error:", xhr.responseText);
        },
    });
}
////función Para cargar los datos en el formulario para editarlo
function ConsultaReg(idRep) {
    $.ajax({
        url: base_url + "/admin/alimentos/getRecepcion",
        type: "POST",
        dataType: "json",
        data: { idRep: idRep },
        success: function (response) {
            // Mostrar los datos obtenidos en consola
            // console.log("Datos de la recepción:", response);

            // Asignar valores a los inputs
            $("#idrep").val(response.Id_rep || "");
            $("#folio").val(response.Folio || "");
            $("#fecha").val(response.Fecha || "");
            $("#fechaRealRecep").val(response.Fecha_R_Recep || "");
            $("#fechaoriginal").val(response.Fecha_R_Alimento || "");
            // Manejo para AnalistaRes
            if (
                response.AnalistaRes === "Sin Analista" ||
                response.AnalistaRes == null
            ) {
                $("#usuario").val(""); // Deselecciona si no hay analista
            } else {
                let foundUsuario = false;

                $("#usuario option").each(function () {
                    const optionText = $(this).text().trim().toLowerCase();
                    const responseNombre =
                        response.AnalistaRes.trim().toLowerCase();

                    if (optionText === responseNombre) {
                        $("#usuario").val($(this).val());
                        foundUsuario = true;
                        return false;
                    }
                });

                if (!foundUsuario) {
                    $("#usuario").val("");
                }
            }

            // Manejo para AnalistaRecep
            if (
                response.AnalistaRecep === "Sin Analista" ||
                response.AnalistaRecep == null
            ) {
                $("#usuario2").val("");
            } else {
                let foundUsuario2 = false;

                $("#usuario2 option").each(function () {
                    const optionText2 = $(this).text().trim().toLowerCase();
                    const responseNombre2 =
                        response.AnalistaRecep.trim().toLowerCase();

                    if (optionText2 === responseNombre2) {
                        $("#usuario2").val($(this).val());
                        foundUsuario2 = true;
                        return false; // Rompe el .each
                    }
                });

                if (!foundUsuario2) {
                    $("#usuario2").val(""); // Deselecciona si no se encontró coincidencia
                }
            }

            if (response.Resguardo2 === null || response.Resguardo2 === "") {
                $("#resRecep2").val(""); // Seleccionar "Sin seleccionar"
            } else {
                // Resto del código si el Resguardo tiene valor
                const resguardoValue2 = response.Resguardo2;
                const optionExists =
                    $("#resRecep2 option[value='" + resguardoValue2 + "']")
                        .length > 0;

                if (optionExists) {
                    $("#resRecep2").val(resguardoValue2); // Seleccionar el valor correspondiente de Resguardo
                } else {
                    $("#resRecep2").val(""); // Si el valor no existe en las opciones, seleccionar "Sin seleccionar"
                }
            }
            $("#fecha2").val(response.Fecha_resguardo || ""); // Fecha
            $("#fechainicio").val(response.Fecha_inicio || "");
            if (response.Resguardo === null || response.Resguardo === "") {
                $("#resRecep").val(""); // Seleccionar "Sin seleccionar"
            } else {
                // Resto del código si el Resguardo tiene valor
                const resguardoValue = response.Resguardo;
                const optionExists =
                    $("#resRecep option[value='" + resguardoValue + "']")
                        .length > 0;

                if (optionExists) {
                    $("#resRecep").val(resguardoValue); // Seleccionar el valor correspondiente de Resguardo
                } else {
                    $("#resRecep").val(""); // Si el valor no existe en las opciones, seleccionar "Sin seleccionar"
                }
            }

            $("#fecha3").val(response.Fecha_desecho || ""); // Fecha

            if (
                response.Analista_deseho === null ||
                response.Analista_desecho === ""
            ) {
                $("#analistadesecho").val(""); // Seleccionar "Sin seleccionar"
            } else {
                // Resto del código si el Resguardo tiene valor
                const analistadesechoValue = response.Analista_desecho;
                const optionExists =
                    $(
                        "#analistadesecho option[value='" +
                            analistadesechoValue +
                            "']"
                    ).length > 0;

                if (optionExists) {
                    $("#analistadesecho").val(analistadesechoValue); // Seleccionar el valor correspondiente de Resguardo
                } else {
                    $("#analistadesecho").val(""); // Si el valor no existe en las opciones, seleccionar "Sin seleccionar"
                }
            }
            if (
                response.Lugar_desecho === null ||
                response.Lugar_desecho === ""
            ) {
                $("#Lugardedesecho").val("");
            } else {
                const LugardesechoValue = response.Lugar_desecho;
                const optionExists =
                    $(
                        "#Lugardedesecho option[value='" +
                            LugardesechoValue +
                            "']"
                    ).length > 0;
                if (optionExists) {
                    $("#Lugardedesecho").val(LugardesechoValue);
                } else {
                    $("#Lugardedesecho").val("");
                }
            }
        },
        error: function (xhr, status, error) {
            console.error("Error:", xhr.responseText);
        },
    });
}
//función Para consultar los parametros por muestra
function parametro(idRep) {
    //console.log("ID de muestra:", idRep);

    $.ajax({
        url: base_url + "/admin/alimentos/parametros",
        method: "POST",
        dataType: "json",
        data: {
            idRep: idRep,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            // console.log("Datos recibidos:", response);

            $("#parametrosModal").remove();

            let filas = "";
            response.forEach((item) => {
                filas += `
                    <tr>
                        <td>${item.Id_parametro ?? ""}</td>
                        <td>${item.parametro ?? ""}</td>
                        <td>${item.limite ?? ""}</td>
                        <td>${item.unidad ?? ""}</td>
                        <td>${item.matriz ?? ""}</td>
                    </tr>`;
            });

            const modalHtml = `
            <div class="modal fade" id="parametrosModal" tabindex="-1" role="dialog" aria-labelledby="parametrosModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Parámetros de la muestra</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <table class="table table-bordered table-sm">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Parámetro</th>
                          <th>Límite</th>
                          <th>Unidad</th>
                          <th>Matriz</th>
                        </tr>
                      </thead>
                      <tbody>
                        ${filas}
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>`;
            $("body").append(modalHtml);
            $("#parametrosModal").modal("show");
        },
        error: function (xhr) {
            console.error(
                "Error al obtener los parámetros:",
                xhr.status,
                xhr.responseText
            );
            alert("Error al cargar los parámetros. Revisa la consola.");
        },
    });
}

//función Para liberar registros de Bitacora
function LiberarReg(idRep) {
    const confirmar = window.confirm(
        "¿Desea Liberar el Registro de bitácora? (NOTA:UNA VEZ LIBERADO ES IMPOSIBLE EDITAR DATOS POR PARTE DEL USUARIO)"
    );

    if (confirmar) {
        $.ajax({
            url: base_url + "/admin/alimentos/LiberarReg",
            type: "POST",
            dataType: "json",
            data: { idrep: idRep },
            success: function (response) {
                alert(response.message);
                // DatosRecepcion();
                  
            },
        });
    } else {
        alert("Proceso Cancelado.");
    }
}
function EntregaMuestra(idRep, isChecked) {
    const confirmar = window.confirm(
        "¿Llegó la muestra al Área de Alimentos? (NOTA: Verificar cada muestra por favor)"
    );

    if (confirmar) {
        // Convertimos true/false a 1/0
        const estado = isChecked ? 1 : 0;

        $.ajax({
            url: base_url + "/admin/alimentos/EntregaMuestra",
            type: "POST",
            dataType: "json",
            data: { 
                idrep: idRep,
                entrega: estado 
            },
            success: function (response) {
                alert(response.message);
                
            },
            error: function () {
                alert("Ocurrió un error al actualizar la entrega.");
            }
        });
    } else {
        // Si cancela la confirmación, desmarcamos el check visualmente
        const checkbox = event.target;
        checkbox.checked = !isChecked; 
        alert("Acción cancelada.");
    }
}


