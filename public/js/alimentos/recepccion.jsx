//evento para registrar  al dar clic al boton guardar
$("#btnRep").click(function () {
    const usuarioSeleccionado = $("#usuario").val();

    const [idUsuario, nombreUsuario] = usuarioSeleccionado.split("|");

    const data = {
        Folio: $("#folio").val(),
        Fecha: $("#fecha").val(),
        Fecha2: $("#fecha2").val(),
        resRecep: $("#resRecep").val(),
        resRecep2: $("#resRecep2").val(),

        Fecha3: $("#fecha3").val(),
        analistadesecho: $("#analistadesecho").val(),
        Lugardedesecho: $("#Lugardedesecho").val(),
        idrep: $("#idrep").val(),
        Nombre: nombreUsuario,
        idUsuario: idUsuario,
    };

    IngresarRecepcion(data);
});

//InICIALIZACION DE LAS TABLAS
$(document).ready(function () {
    DatosRecepcion();
});
//METODO PARA CARGAR LAS TRES TABLAS
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
                            <td>${item.Folio}</td>
                            <td>${item.Recibio}</td>
                            <td>${item.Fecha}</td>
                            <td>${item.Hora_recepcion}</td>
                            <td>${item.Resguardo}</td>
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
                            <td>${item2.Nombre}</td>
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
                    }
                );

                $(document).on("click", "#Resguardo tbody tr", function () {
                    $("#Resguardo tbody tr").removeClass("selected");
                    $(this).addClass("selected");
                    const idRep = $(this).data("idrep");
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

//Actualizacion de los datos acorde al que se selecciona
function updateregistro(idRep) {
    // console.log("Id_rep seleccionado:", idRep);

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

            if (response.Nombre === "Sin Analista") {
                $("#usuario").val(""); // Esto es correcto si no se quiere seleccionar nada
            } else {
                const usuarioValue = `${response.Id_user}|${response.Nombre}`;
                // console.log("Valor de usuarioValue:", usuarioValue); // Agrega un log para verificar

                // Verificar si el valor existe en las opciones del select
                const optionExists =
                    $("#usuario option[value='" + usuarioValue + "']").length >
                    0;
                // console.log("¿La opción existe?", optionExists); // Agrega un log para verificar

                if (optionExists) {
                    $("#usuario").val(usuarioValue); // Seleccionar el valor correspondiente
                } else {
                    $("#usuario").val(""); // Si el valor no existe, seleccionar "Sin seleccionar"
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
