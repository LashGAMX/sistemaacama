$(document).ready(function () {
    var tableParametros;

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
                console.log(response);
                let tabMuestras = "";
                let cont = 1;

                response.model.forEach((item) => {
                    // Generar las opciones para el select de parámetros
                    let opciones = response.parametros
                        .map((param) => {
                            const isSelected = response.solParam[
                                cont - 1
                            ]?.some(
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
                             </option>`;
                        })
                        .join("");

                    // Generar las opciones para el select de normas
                    let opciones2 = response.normas
                        .map((norma) => {
                            const isSelected =
                                item.Id_norma == norma.Id_norma
                                    ? "selected"
                                    : "";
                            return `<option value="${norma.Id_norma}" ${isSelected}>
                                        (${norma.Id_norma}) ${norma.Clave_norma}
                                    </option>`;
                        })
                        .join("");

                    // Generar la fila para cada muestra
                    tabMuestras += `
                        <tr>
                            <td style="width: 1%; white-space: nowrap;">${cont}</td>
                       <td style="width: 1%; white-space: nowrap;">${
                           item.Id_muestra
                       }</td>

                            <td style="width: 10%; white-space: nowrap;" >
                             <textarea  id="muestra${item.Id_muestra}">${
                        item.Muestra ?? ""
                    }</textarea>

                            </td>
                            <td>
                                <select class="select2Multiple form-control" id="parametros${
                                    item.Id_muestra
                                }" multiple="multiple">
                                    ${opciones}
                                </select>
                            </td>
                            <td>
                                <select class="select2 form-control" id="normas${
                                    item.Id_muestra
                                }">
                                    ${opciones2}
                                </select>
                            </td>
                            <td>
                                <button id="btnSaveMuestra${
                                    item.Id_muestra
                                }" onclick="setSaveMuestra(${
                        item.Id_muestra
                    })" class="btn btn-success">
                                    <i class="fas fa-save"></i>
                                </button> 
                                &nbsp; 
                                  <button id="btnDelMuestra${
                                      item.Id_muestra
                                  }" onclick="DeleteMuestra(${
                        item.Id_muestra
                    })" class="btn btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button> 
                            </td>
                        </tr>`;
                    cont++;
                });

                // Insertar las filas generadas en la tabla
                $("#bodyTabMuestras").html(tabMuestras);
            },
            complete: function () {
                // Inicializar select2 en los nuevos elementos generados
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
    console.log("ID enviado:", id);
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
            window.location = base_url + "/admin/alimentos/orden-servicio";
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
            console.log(response);
            let sucursal = `<option value="0">Sin seleccionar</option>`;
            let firstSucursalId = null; // Variable para almacenar el primer ID

            response.model.forEach((item, index) => {
                sucursal += `<option value="${item.Id_sucursal}" ${
                    index === 0 ? "selected" : ""
                }>(${item.Id_sucursal}) ${item.Empresa}</option>`;
                if (index === 0) {
                    firstSucursalId = item.Id_sucursal; // Guardar el primer ID
                }
            });

            $("#sucursal").html(sucursal);

            // Solo llamar si hay al menos una sucursal
            if (firstSucursalId) {
                getDireccionReporte(firstSucursalId);
            }
        },
    });
}

function getDireccionReporte(id) {
    console.log("ID", id);
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
            console.log(response);
            let direccion = "";
            direccion += `<option value="0">Sin seleccionar</option>`;
            response.model.forEach((item) => {
                direccion += `<option value="${item.Id_direccion}" selected>(${item.Id_direccion}) ${item.Direccion}</option>`;
            });

            $("#direccionReporte").html(`
                ${direccion} 
            `);
        },
    });
    getContactoSucursal(id);
}
function getContactoSucursal(id) {
    console.log("ID CONTACTO", id);
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
            console.log(response);

            let data = '<option value="0">Sin seleccionar</option>';
            response.model.forEach((item) => {
                data += `<option value="${item.Id_contacto}" selected>(${item.Id_contacto}) ${item.Nombre}</option>`;
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
            console.log(response);
            let data = "";
            data += `<option value="0">Sin seleccionar</option>`;
            response.model.forEach((item) => {
                data += `<option value="${item.Id_subnorma}" selected>(${item.Id_subnorma}) ${item.Clave}</option>`;
            });

            $("#subnorma").html(`
                ${data} 
            `);
        },
    });
}
function getDataContacto(id) {
    console.log("ID getDataContacto:", id);
    $.ajax({
        type: "POST",
        url: base_url + "/admin/alimentos/getDataContacto",
        dataType: "json",
        data: {
            id: id,
            _token: $('input[name="_token"]').val(),
        },
        success: function (response) {
            console.log("Respuesta del servidor:", response);

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
                // console.log("No se encontraron datos del contacto.");
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
            //console.log("Respuesta del servidor:", response);

            // Si viene folio, lo cargamos en el input
            if (response.folio) {
                $("#Folio").val(response.folio);
            }

            // Cargar el Id_solicitud en el input
            if (response.id) {
                $("#idSol").val(response.id);
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
        }
    });
}
