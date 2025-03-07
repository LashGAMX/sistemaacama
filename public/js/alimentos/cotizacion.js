$(document).ready(function () {
    $("#clientes").select2();
    $("#sucursal").select2();
    $("#contacto").select2();
    $("#norma").select2();
    $("#subnorma").select2();
    cargarClientes();
    getservicios();
    getNormas();
    getcotizacionAli();

    $("#clientes").on("change", function () {
        const idCliente = $(this).val();
        cargarsucursal(idCliente);
    });

    $("#sucursal").on("change", function () {
        const idSucursal = $(this).val();
        limpiarInputs();
        $("#contacto").empty();
        $("#contacto").append(
            '<option value="">Seleccione una Opción</option>'
        );

        if (idSucursal !== "0") {
            getDireccionReporte(idSucursal);
            getDataContacto(idSucursal);
            setContacto(idSucursal);
        }
    });

    $("#contacto").on("change", function () {
        const idContacto = $(this).val();

        editContacto(idContacto);
        if (idContacto && idContacto !== "0") {
            obtenerDatosContacto(idContacto);
        } else {
            limpiarInputs();
        }
    });

    $("#btnAddCol").on("click", function () {
        CrearPuntoMuestra();
    });
    $("#BtnEditar").on("click", function () {
        $("#contactoModalLabel").text("Editar Contacto");
        $("#contactoModal").modal("show");
        $("#GuardarContacto").hide();
        $("#EditarContacto").show();
    });

    $("#BtnCrear").on("click", function () {
        $("#contactoModalLabel").text("Crear Nuevo Contacto");
        $("#contactoModal").modal("show");
        $("#GuardarContacto").show();
        $("#EditarContacto").hide();
    });

    $("#btnCreate").on("click", function () {
        window.open(base_url + "/admin/alimentos/create-cotizacion");
    });
});

function getDataContacto(idSucursal) {
    // console.log("Llamando a getDataContacto con idSucursal:", idSucursal);
    $.ajax({
        url: base_url + "/admin/alimentos/getDataContacto",
        type: "POST",
        dataType: "json",
        data: {
            id: idSucursal,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            // console.log("Respuesta del servidor en getDataContacto:", response);

            if (response.length === 0) {
            } else {
                $.each(response, function (index, contacto) {
                    $("#contacto").append(
                        `<option value="${contacto.Id_contacto}">${contacto.Id_contacto} (${contacto.Nombre})</option>`
                    );
                });
            }
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud:", error);
        },
    });
}

function obtenerDatosContacto(idContacto) {
    // console.log("Llamando a obtenerDatosContacto con idContacto:", idContacto);

    $.ajax({
        url: base_url + "/admin/alimentos/getDatos",
        type: "POST",
        dataType: "json",
        data: {
            contacto_id: idContacto,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (data) {
            // console.log("Respuesta del servidor en obtenerDatosContacto:", data);

            if (data.length > 0) {
                const contacto = data[0];
                $("#idCont").val(contacto.Id_contacto);
                $("#nombreCont").val(contacto.Nombre);
                $("#deptCont").val(contacto.Departamento);
                $("#puestoCont").val(contacto.Puesto);
                $("#emailCont").val(contacto.Email);
                $("#telCont").val(contacto.Telefono);
                $("#celCont").val(contacto.Celular);
            } else {
                limpiarInputs();
            }
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud:", error);
        },
    });
}

function limpiarInputs() {
    $("#idCont").val("");
    $("#nombreCont").val("");
    $("#deptCont").val("");
    $("#puestoCont").val("");
    $("#emailCont").val("");
    $("#telCont").val("");
    $("#celCont").val("");
}

function cargarsucursal(id) {
    $.ajax({
        url: base_url + "/admin/alimentos/setSucursal",
        type: "POST",
        dataType: "json",
        data: {
            id: id,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            $("#sucursal").empty();
            $("#sucursal").append('<option value="0">Sin seleccionar</option>');
            $.each(response, function (index, sucursal) {
                $("#sucursal").append(
                    `<option value="${sucursal.Id_sucursal}">${sucursal.Id_sucursal} (${sucursal.Empresa})</option>`
                );
            });
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud:", error);
        },
    });
}
function cargarClientes() {
    $.ajax({
        url: base_url + "/admin/alimentos/getClienteGen",
        type: "GET",
        dataType: "json",
        success: function (data) {
            $("#clientes").empty();
            $("#clientes").append('<option value="0">Sin seleccionar</option>');
            $.each(data, function (index, cliente) {
                $("#clientes").append(
                    '<option value="' +
                        cliente.Id_cliente +
                        '">' +
                        cliente.Id_cliente +
                        " (" +
                        cliente.Empresa +
                        ")</option>"
                );
            });
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud:", error);
        },
    });
}
function getservicios() {
    $.ajax({
        url: base_url + "/admin/alimentos/getservicios",
        type: "GET",
        dataType: "json",
        success: function (data) {
            $("#servicio").empty();
            $("#servicio").append('<option value="0">Sin Seleccionar</option>');
            $.each(data, function (index, servicio) {
                $("#servicio").append(
                    '<option value="' +
                        servicio.Id_tipo +
                        '">' +
                        " (" +
                        servicio.Id_tipo +
                        ") " +
                        servicio.Servicio +
                        "</option>"
                );
            });
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud:", error);
        },
    });
}
function getNormas() {
    $.ajax({
        url: base_url + "/admin/alimentos/getNormas",
        type: "GET",
        dataType: "json",
        success: function (data) {
            $("#norma").empty();
            $("#norma").append('<option value="0">Sin seleccionar</option>');

            $.each(data, function (index, norma) {
                $("#norma").append(
                    '<option value="' +
                        norma.Id_norma +
                        '">' +
                        " (" +
                        norma.Id_norma +
                        ") " +
                        norma.Norma +
                        "</option>"
                );
            });
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud:", error);
        },
    });
}

function getSubNormas(id) {
    // console.log("IdNorma: " + id);
    $.ajax({
        url: base_url + "/admin/alimentos/getSubNorma",
        type: "POST",
        dataType: "JSON",
        data: {
            id: id,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            // console.log("Respuesta del servidor:", response); // Verifica la respuesta

            if (response.length === 0) {
                $("#subnorma").empty();
                $("#subnorma").append(
                    '<option value="0">Sin seleccionar</option>'
                );
            } else {
                $("#subnorma").empty();
                $("#subnorma").append(
                    '<option value="0">Sin seleccionar</option>'
                );
                $.each(response, function (index, subnorma) {
                    $("#subnorma").append(
                        `<option value="${subnorma.Id_subnorma}">(${subnorma.Id_subnorma}) ${subnorma.Clave}</option>`
                    );
                });
            }
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud:", error);
        },
    });
}
function getParametrosNorma(id) {
    console.log("SubNorma: " + id);
}

function getDireccionReporte(idSucursal) {
    if (idSucursal === "0") {
        $("#direccionReporte").empty();
        $("#direccionReporte").append(
            '<option value="0">Sin seleccionar</option>'
        );
        return;
    }

    $.ajax({
        url: base_url + "/admin/alimentos/getDirecciones",
        type: "POST",
        dataType: "json",
        data: {
            id: idSucursal,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            $("#direccionReporte").empty();
            $("#direccionReporte").append(
                '<option value="0">Sin seleccionar</option>'
            );
            $.each(response, function (index, dir) {
                $("#direccionReporte").append(
                    '<option value="' +
                        dir.Direccion +
                        '">' +
                        dir.Direccion +
                        "</option>"
                );
            });
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud:", error);
        },
    });
}

function getcotizacionAli() {
    $("#tablaCotizacion").DataTable({
        processing: false,
        serverSide: false,
        ajax: {
            url: base_url + "/admin/alimentos/getcotizacionAli",
            type: "GET",
            dataSrc: "data",
        },
        columns: [
            { data: "Id_cotizacion" },
            { data: "Folio_servicio" },
            { data: "Folio" },
            { data: "Fecha_cotizacion" },
            { data: "Nombre" },
            { data: "Id_norma" },
            { data: "Tipo_descarga" },
            { data: "Estado_cotizacion" },
            { data: "Creado_por" },
            { data: "created_at" },
            { data: "Actualizado_por" },
            { data: "updated_at" },
        ],
        ordering: false,
        language: {
            lengthMenu: "Mostrar _MENU_ registros por página",
            zeroRecords: "No se encontraron resultados",
            info: "Mostrando página _PAGE_ de _PAGES_",
            infoEmpty: "No hay registros disponibles",
        },
        order: [[0, "desc"]],
    });
}
function setContacto(id) {
    const idSucursal = id;
    $("#GuardarContacto")
        .off("click")
        .on("click", function () {
            const data = {
                idSucursal: idSucursal,
                nombre: $("#nombre").val(),
                departamento: $("#dept").val(),
                puesto: $("#puesto").val(),
                email: $("#email").val(),
                telefono: $("#tel").val(),
                celular: $("#cel").val(),
            };

            // console.log("Datos a enviar:", data);
            $.ajax({
                url: base_url + "/admin/alimentos/setContacto",
                type: "POST",
                data: data,
                success: function (response) {
                    console.log("Respuesta del servidor:", response);
                    alert("Contacto Creado");

                    $("#contactoModal").modal("hide");
                    $("#nombre").val("");
                    $("#dept").val("");
                    $("#puesto").val("");
                    $("#email").val("");
                    $("#tel").val("");
                    $("#cel").val("");
                    // getDataContacto(idSucursal);
                },
                error: function (xhr, status, error) {
                    console.error("Error en la solicitud:", error);
                    alert(
                        "Error al crear el contacto. Por favor, inténtalo de nuevo."
                    );
                },
            });
        });
}
function editContacto(id) {
    // console.log("CONTACTO A EDITAR: "+id);
    const idContacto = id;
    $.ajax({
        url: base_url + "/admin/alimentos/getDatos",
        type: "POST",
        dataType: "json",
        data: {
            contacto_id: idContacto,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (data) {
            // console.log("Respuesta del servidor en obtenerDatosContacto:", data);

            if (data.length > 0) {
                const contacto = data[0];

                $("#nombre").val(contacto.Nombre);
                $("#dept").val(contacto.Departamento);
                $("#puesto").val(contacto.Puesto);
                $("#email").val(contacto.Email);
                $("#tel").val(contacto.Telefono);
                $("#cel").val(contacto.Celular);
            } else {
                limpiarInputs();
            }
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud:", error);
        },
    });

    $("#EditarContacto")
        .off("click")
        .on("click", function () {
            const data = {
                idContacto: idContacto,
                nombre: $("#nombre").val(),
                departamento: $("#dept").val(),
                puesto: $("#puesto").val(),
                email: $("#email").val(),
                telefono: $("#tel").val(),
                celular: $("#cel").val(),
            };

            $.ajax({
                url: base_url + "/admin/alimentos/editContacto",
                type: "POST",
                data: data,
                success: function (response) {
                    if (response.success) {
                        alert("Contacto actualizado con éxito");
                        obtenerDatosContacto(idContacto);
                    } else {
                        alert(response.message);
                    }

                    $("#contactoModal").modal("hide");
                    $("#nombre").val("");
                    $("#dept").val("");
                    $("#puesto").val("");
                    $("#email").val("");
                    $("#tel").val("");
                    $("#cel").val("");
                },
                error: function (xhr, status, error) {
                    console.error("Error en la solicitud:", error);
                    alert(
                        "Error al actualizar el contacto. Por favor, inténtalo de nuevo."
                    );
                },
            });
        });
}

// function getClientesIntermediarios() {
//     let sub = document.getElementById("intermediario");
//     let tab = "";
//     let selectedClienteId = $("#intermediario").val(); // Obtener el cliente seleccionado previamente

//     $.ajax({
//         url: base_url + "/admin/alimentos/setcliente",
//         type: "POST",
//         data: {
//             id: $("#intermediario").val(),
//             _token: $('meta[name="csrf-token"]').attr('content'), // Obtener el token de la metaetiqueta
//         },
//         dataType: "json",
//         success: function (response) {
//             tab += '<option value="0">Sin seleccionar</option>';
//             $.each(response.model, function (key, item) {
//                 if (selectedClienteId == item.Id_cliente) { // Comparar con el cliente seleccionado
//                     tab +=
//                         '<option value="' + item.Id_cliente + '" selected>' +
//                         item.Empresa + "</option>";
//                 } else {
//                     tab +=
//                         '<option value="' + item.Id_cliente + '">' +
//                         item.Empresa + "</option>";
//                 }
//             });
//             sub.innerHTML = tab;
//         },
//     });
// }

let cont = 1;

function CrearPuntoMuestra() {
    alert("¿Estas seguro que deseas crear un punto de meuestreo?");

    const selectedValue = document.getElementById("servicio").value;

    let analysisText;
    if (selectedValue === "1") {
        analysisText = "Análisis y muestreo";
    } else if (selectedValue === "2") {
        analysisText = "Muestreo";
    } else if (selectedValue === "3") {
        analysisText = "Análisis";
    } else {
        analysisText = "";
    }

    const newRow = document.createElement("tr");

    newRow.innerHTML = `
        <td>${cont}</td>
        <td>${cont}</td>
        <td><input type="text" value="Muestra" /></td>
        <td><input type="text" value="${analysisText}" /></td>
        <td><button class="btn-remove">Eliminar</button></td>
    `;

    document.getElementById("bodyTabMuestras").appendChild(newRow);
    cont++;
    newRow.querySelector(".btn-remove").addEventListener("click", function () {
        newRow.remove();
    });
}
