$(document).ready(function () {
    var tableParametros;
    var solModel;
    $("#btnFolio").click(function () {
        setGenFolio();
    });

    // Puedes inicializar otras funciones aquí si es necesario
    $("#btnGuardar").click(function () {
        setSolicitud();
    });
    $("#btnAddCol").click(function () {
        setMuestraSol();
    });
    tabParametros();
    getDataSolicitud();

    $(".select2Multiple").select2({});
});

function getDataSolicitud() {
    if ($("#idSol").val() != "") {
        $.ajax({
            type: "POST",
            url: base_url + "/admin/alimentos/getDataSolicitud",
            dataType: "json",
            data: {
                id: $("#idSol").val(),
                _token: $('input[name="_token"]').val(),
            },
            success: function (response) {
                solModel = response.model;

                getSucursalCliente(solModel.Id_cliente);
                getDireccionReporte(solModel.Id_sucursal);
                getDataContacto(solModel.Id_contacto);
                getSubNormas(solModel.Id_norma);
                getMuestraSol();
            },
        });
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
function setMuestraSol() {
    if ($("#idSol").val() != "") {
        $.ajax({
            type: "POST",
            url: base_url + "/admin/alimentos/setMuestraSol",
            dataType: "json",
            data: {
                idSol: $("#idSol").val(),
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
                                    solP.Id_parametro === param.Id_parametro
                            )
                                ? "selected"
                                : "";
                            return `<option value="${param.Id_parametro}" ${isSelected}>
                                        (${param.Id_parametro}) ${param.Parametro}
                                    </option>`;
                        })
                        .join("");

                    // Generar las opciones para el select de normas
                    let opciones2 = response.normas
                        .map((norma) => {
                            return `<option value="${norma.Id_norma}">
                                        (${norma.Id_norma}) ${norma.Clave_norma}
                                    </option>`;
                        })
                        .join("");

                    // Generar la fila para cada muestra
                    tabMuestras += `
                        <tr>
                            <td style="width: 2px;">${cont}</td>
                            <td style="width: 2px;">${item.Id_muestra}</td>
                            <td>
                                <textarea rows="2" cols="50" id="muestra${
                                    item.Id_muestra
                                }">
                                    ${item.Muestra ?? ""}
                                </textarea>
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
    $.ajax({
        type: "POST",
        url: base_url + "/admin/alimentos/setSaveMuestra",
        dataType: "json",
        data: {
            idSol: $("#idSol").val(),
            id: id,
            muestra: $("#muestra" + id).val(),
            parametros: $("#parametros" + id).val(),
            norma: $("#normas" + id).val(),
            _token: $('input[name="_token"]').val(),
        },
        success: function (response) {
            alert(response.msg);
            getMuestraSol();
        },
    });
}
function setSolicitud() {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/alimentos/setSolicitud",
        dataType: "json",
        data: {
            idSol: $("#idSol").val(),
            cliente: $("#clientes").val(),
            sucursal: $("#sucursal").val(),
            contacto: $("#contacto").val(),
            direccion: $("#direccionReporte").val(),
            atencion: $("#atencion").val(),
            observacion: $("#observacion").val(),
            servicio: $("#servicio").val(),
            norma: $("#norma").val(),
            subnorma: $("#subnorma").val(),
            fechaMuestreo: $("#fechaMuestreo").val(),
            numTomas: $("#numTomas").val(),
            fechaMuestreo: $("#fechaMuestreo").val(),
            _token: $('input[name="_token"]').val(),
        },
        success: function (response) {
            alert(response.msg);
            $("#idSol").val(response.model.Id_solicitud);
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
            let sucursal = "";
            sucursal += `<option value="0">Sin seleccionar</option>`;
            response.model.forEach((item) => {
                if (solModel.Id_sucursal == item.Id_sucursal) {
                    sucursal += `<option value="${item.Id_sucursal}" selected>(${item.Id_sucursal}) ${item.Empresa}</option>`;
                } else {
                    sucursal += `<option value="${item.Id_sucursal}">(${item.Id_sucursal}) ${item.Empresa}</option>`;
                }
            });

            $("#sucursal").html(`
                ${sucursal} 
            `);
        },
    });
}
function getDireccionReporte(id) {
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
                if (solModel.Id_direccion == item.Id_direccion) {
                    direccion += `<option value="${item.Id_direccion}" selected>(${item.Id_direccion}) ${item.Direccion}</option>`;
                } else {
                    direccion += `<option value="${item.Id_direccion}">(${item.Id_direccion}) ${item.Direccion}</option>`;
                }
            });

            $("#direccionReporte").html(`
                ${direccion} 
            `);
        },
    });
    getContactoSucursal(id);
}
function getContactoSucursal(id) {
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
                if (solModel.Id_contacto == item.Id_contacto) {
                    data += `<option value="${item.Id_contacto}" selected>(${item.Id_contacto}) ${item.Nombre}</option>`;
                } else {
                    data += `<option value="${item.Id_contacto}">(${item.Id_contacto}) ${item.Nombre}</option>`;
                }
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
                if (solModel.Id_subnorma == item.Id_subnorma) {
                    data += `<option value="${item.Id_subnorma}" selected>(${item.Id_subnorma}) ${item.Clave}</option>`;
                } else {
                    data += `<option value="${item.Id_subnorma}">(${item.Id_subnorma}) ${item.Clave}</option>`;
                }
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
              
                $('#idCont').val(response[0].Id_contacto);
                $('#nombreCont').val(response[0].Nombre);
                $('#deptCont').val(response[0].Departamento);
                $('#puestoCont').val(response[0].Puesto);
                $('#emailCont').val(response[0].Email);
                $('#telCont').val(response[0].Telefono);
                $('#celCont').val(response[0].Celular);
            } else {
                alert("No se encontraron datos del contacto.");
            }
        },
        error: function (xhr, status, error) {
            console.error("Error al obtener los datos:", error);
            alert("Ocurrió un error al consultar los datos del contacto.");
        },
    });
}



function setGenFolio() {
    if ($("#fechaMuestreo").val() != "") {
        const data = {
<<<<<<< HEAD
            // id: $("#idCot").val(),
=======
            id: $("#idCot").val(),
>>>>>>> 2b914187672a51c20e1918251d5136fec63fe60b
            fecha: $("#fechaMuestreo").val(),
            _token: $('input[name="_token"]').val(),
        };
        console.log("Datos:", data);

        $.ajax({
            url: base_url + "/admin/alimentos/setGenFolioSol",
            type: "POST",
            data: data,
            dataType: "json",
            success: function (response) {
                console.log("Respuesta del servidor:", response);

                // Si el folio ya existe, mostramos un mensaje diferente
                if (response.msg === "Ya tiene un folio") {
                    alert(response.msg); // Muestra la alerta de que ya existe un folio
                    $("#Folio").val(response.folio); // Carga el folio en el input
                } else if (response.folio) {
                    $("#Folio").val(response.folio); // Carga el folio generado en el input
                    alert("Folio Generado Correctamente");
                } else {
                    alert("No se pudo generar el folio.");
                }

                // Habilitar nuevamente el input para la fecha de muestreo
                $("#fechaMuestreo").attr("disabled", false);
            },

            error: function () {
                alert("Hubo un error al generar el folio.");
            },
        });
    } else {
        alert("Necesitas la fecha de muestreo para generar el folio");
    }
}

