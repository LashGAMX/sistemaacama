$(document).ready(function () {
    var tableParametros;

    $("#btnFolio").click(function () {
        setGenFolio();
    });
    $("#btnGuardar").click(function () {
        setSolicitud2();
    });
    $("#btncrearsol").click(function () {
        setSolicitud();
    });
    $("#btnAddCol").click(function () {
        setMuestraSol();
    });
    tabParametros();
    $(".select2Multiple").select2({});
});
function setMuestraSol() {
  const idSol   = $("#idSol").val().trim();   
  const folio   = $("#Folio").val().trim();   
  const numTomas = $("#numTomas").val().trim();

  if (!idSol && !folio) {
    alert("Primero debe crear el Folio y después guardarlo");
    return; 
  }

  
  $.ajax({
    type: "POST",
    url: `${base_url}/admin/alimentos/setMuestraSol`,
    dataType: "json",
    data: {
      idSol,
      numTomas,
      folio,
      _token: $('input[name="_token"]').val(),
    },
    success(response) {
      alert(response.msg);

      
      if (response.idSol) {
        $("#idSol").val(response.idSol);
      }
      getMuestraSol();
    },
    error(xhr) {
      console.error(xhr.responseText);
      alert("Ocurrió un problema al guardar la muestra.");
    },
  });
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
  if ($.trim($("#idSol").val()) !== "") {
    console.log("Tiene un valor");
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
                                    <label for="tempMuestra${
                                        item.Id_muestra
                                    }">Muestra</label>
                                    <input 
                                        type="text" 
                                        id="tempMuestra${item.Id_muestra}" 
                                        name="tempMuestra${item.Id_muestra}" 
                                        value="${item.Tem_muestra ?? ""}" 
                                        
                                    >
                                
                                    <label for="temprecep${
                                        item.Id_muestra
                                    }" style="margin-top: 5px;">Recepción</label>
                                    <input 
                                        type="text" 
                                        id="temprecep${item.Id_muestra}" 
                                        name="temprecep${item.Id_muestra}" 
                                        value="${item.Tem_recepcion ?? ""}" 
                                       
                                    >
                                </td>
                                
                                
                                <td>
                                    <input 
                                        type="text" 
                                        id="Obs${item.Id_muestra}" 
                                        name="Obs${item.Id_muestra}" 
                                        value="${item.Observacion ?? ""}" 
                                        
                                    >
                                </td>
                                
                                <td>
                                <label for="unidad${item.Id_muestra}">U.</label>
                                    <input
                                        type="text" 
                                        id="unidad${item.Id_muestra}" 
                                        name="unidad${item.Id_muestra}" 
                                        value="${item.Unidad ?? ""}" 
                                       
                                    >
                                <label for="cant${
                                    item.Id_muestra
                                }">Cant.</label>
                                    <input
                                        type="text" 
                                        id="cant${item.Id_muestra}" 
                                        name="cant${item.Id_muestra}" 
                                        value="${item.Cantidad ?? ""}" 
                                      
                                    >
                                </td>
                                <td>
                                  <textarea 
                                    id="motivo${item.Id_muestra}" 
                                    name="motivo${item.Id_muestra}" 
                                >${item.Motivo ?? ""}</textarea>
                                
                                </td>
                                <td>
                                    <input 
                                        type="checkbox" 
                                        id="cumple${item.Id_muestra}" 
                                        name="cumple${item.Id_muestra}" 
                                        ${item.Cumple == "1" ? "checked" : ""}
                                    >
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
        alert("No puedes crear el Folio y guardarlo (boton verde y azul)");
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

    $.ajax({
        type: "POST",
        url: base_url + "/admin/alimentos/setSaveMuestra",
        dataType: "json",
        data: {
            idSol: $("#idSol").val(),
            id: id,
            muestra: $("#muestra" + id).val(),
            tempMuetra: $("#tempMuestra" + id).val(),
            temprecep: $("#temprecep" + id).val(),

            Obs: $("#Obs" + id).val(),
            unidad: $("#unidad" + id).val(),
            cant: $("#cant" + id).val(),
            motivo: $("#motivo" + id).val(),
            cumple: $("#cumple" + id).is(":checked") ? 1 : 0,

             parametros: parametrosSeleccionados,
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
    const recibeValue = $("#recibe").val();
    const [iduser, nombre] = recibeValue
        .split(" | ")
        .map((item) => item.trim());

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
        idUsuario: $("#user").val(),
        condiciones: $("#condiciones").val(),
        ambientales: $("#ambientales").val(),
        fechapro: $("#fechapro").val(),
        fechamuestreo: $("#FechaMuestreo").val(),
        recibeId: iduser,
        id: $("#user").val(),
        recibeNombre: nombre,
        _token: $('input[name="_token"]').val(),
    };

    $.ajax({
        type: "POST",
        url: base_url + "/admin/alimentos/setSolicitud",
        dataType: "json",
        data: formData,
        beforeSend: function () {
            console.log("Datos que se enviarán:");
            console.log(formData);
        },

        success: function (response) {
            alert(response.msg);
            $("#idSol").val(response.model.Id_solicitud);
            window.location = base_url + "/admin/alimentos/orden-servicio";
        },
    });
}
function setSolicitud2() {
    const recibeValue = $("#recibe").val();
    const [iduser, nombre] = recibeValue
        .split(" | ")
        .map((item) => item.trim());

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
        idUsuario: $("#user").val(),
        condiciones: $("#condiciones").val(),
        ambientales: $("#ambientales").val(),
        fechapro: $("#fechapro").val(),
        fechamuestreo: $("#FechaMuestreo").val(),
        recibeId: iduser,
        id: $("#user").val(),
        recibeNombre: nombre,
        _token: $('input[name="_token"]').val(),
    };

    $.ajax({
        type: "POST",
        url: base_url + "/admin/alimentos/setSolicitud2",
        dataType: "json",
        data: formData,
        // beforeSend: function () {
        //      console.log("Datos que se enviarán:");
        //      console.log(formData);
        //  },
        success: function (response) {
            alert(response.msg);
            $("#idSol").val(response.model.Id_solicitud);
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
    let fechaCompleta = $("#fechaMuestreo").val();
    let soloFecha = fechaCompleta ? fechaCompleta.split("T")[0] : "";

    if (soloFecha !== "") {
        const data = {
            id: $("#idCot").val(),
            fecha: soloFecha,
            _token: $('input[name="_token"]').val(),
        };

        console.log("Datos:", data);

        $.ajax({
            url: base_url + "/admin/alimentos/setGenFolioSol",
            type: "POST",
            data: data,
            dataType: "json",
            success: function (response) {
                if (response.msg === "Ya tiene un folio") {
                    alert(response.msg);
                } else if (response.msg === "Cotización no encontrada") {
                    if (response.folio) {
                        $("#Folio").val(response.folio);
                        alert("Folio Generado Correctamente");
                    } else {
                        alert("No se pudo generar el folio.");
                    }
                }

                $("#fechaMuestreo").attr("disabled", false);
            },
        });
    } else {
        alert("Necesitas la fecha de muestreo para generar el folio");
    }
}
