// Declaracion de la variable global
let folio = null;
let cliente = null;
let empresa = null;
let horaRecepcion = null;
let horaEntrada = null;
var idSol = 0;
const safe = (value) => {
    return value === null || value === undefined || value === "null"
        ? ""
        : value;
};
function setIncumplimiento() {
    $.ajax({
        url: base_url + "/admin/alimentos/setIncumplimiento",
        type: "POST",
        dataType: "json",
        data: {
            id: idSol,
            nMuestra: $("#nMuestra").val(),
            motivoInc: $("#motivoInc").val(),
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            console.log(response)
            alert(response.msg)
            let $showIncum = $("#showIncumplimiento").empty();
            let filaIn = ""
            let cont = 1;
            response.model.forEach(function (item) {
                if (item.Cumple == 0) {
                    filaIn += `
                                <p>Muestra ${cont} | Incumplimiento: ${item.Motivo}</p>
                            `;
                }
                cont++;
            });


            $("#showIncumplimiento").append(filaIn);

        },


    });
}

function buscarFolio() {
    const folio = $("#folioSol").val();

    $.ajax({
        url: base_url + "/admin/alimentos/buscarFolio",
        type: "POST",
        dataType: "json",
        data: {
            folio: folio,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {

            // Si el folio no exise
            // console.log(response);
            idSol = response.folio.Id_solicitud
            let filaIn = ""
            if (!response.folio) {
                alert("No Existe ese Folio. Verificalo en Solicitudes");
                $("#cliente").val("");
                $("#empresa").val("");
                $("#folio").val("");
                $("#idSol").val("");
                $("#descarga").val("");
                $("#divPuntos tbody").empty();
                $("#divCodigos tbody").empty();
                $("#hora_recepcion1").empty();
                $("#hora_entrada").empty();

                if ($.fn.DataTable.isDataTable("#codigos")) {
                    $("#codigos").DataTable().clear().destroy();
                }
                if ($.fn.DataTable.isDataTable("#puntos")) {
                    $("#puntos").DataTable().clear().destroy();
                }
                return;
            }

            // Si el folio existe, procesa los datos
            $("#folio").val(response.folio.Folio || "N/A");
            $("#cliente").val(response.folio.Cliente || "N/A");
            $("#empresa").val(response.folio.Sucursal || "N/A");
            $("#idSol").val(response.folio.Id_solicitud || "");
            const idsol = response.folio.Id_solicitud || ""; // Asigna a idsol directamente desde la respuesta
            // console.log("Valor asignado a idSol:", $("#idSol").val());

            $("#hora_recepcion1").val(
                response.proceso?.Hora_recepcion
                    ? formatDateTime(response.proceso.Hora_recepcion)
                    : ""
            );
            $("#hora_entrada").val(
                response.proceso?.Hora_entrada
                    ? formatDateTime(response.proceso.Hora_entrada)
                    : ""
            );

            $("#recibe").val(
                response.proceso ? response.proceso.Id_recibio || "0" : "0"
            );

            $("#fechaMuestreo").val(
                response.proceso?.Fecha_muestreo
                    ? formatDateTime(response.proceso.Fecha_muestreo)
                    : ""
            );

            $("#ingreso")
                .text(
                    response.proceso
                        ? response.proceso.Ingreso === 1
                            ? "Muestra Ingresada"
                            : response.proceso.Ingreso === null
                                ? "Muestra no ingresada"
                                : "Muestra no ingresada"
                        : "Muestra no ingresada"
                )
                .css(
                    "color",
                    response.proceso && response.proceso.Ingreso === 1
                        ? "green"
                        : "red"
                );

            // Limpia las tablas y llena con nuevos datos
            const $codigosBody = $("#codigos tbody").empty();
            const $puntosBody = $("#puntos tbody").empty();
            const $showIncum = $("#showIncumplimiento").empty();

            response.codigos.forEach((item) => {
                                const rowStyle = item.Cancelado == 1 ? 'background-color: #f8d7da;' : ''; 

                $codigosBody.append(
                    `<tr style="${rowStyle}"><td>${item.Codigo}</td><td>(${item.Id_parametro}) ${item.Parametro}</td></tr>`
                );
            });
            response.muestra.forEach(function (item) {
                const rowStyle = item.Cancelado == 1 ? 'background-color: #f8d7da;' : ''; 

                const fila = `
                    <tr data-id="${item.Id_muestra}" style="${rowStyle}">
                        <td style="width: 5px;">${item.Id_muestra}</td>
                            
                        <td>
                         <textarea class="form-control" rows="5" cols="100"  name="muestra" data-id="${safe(
                    item.Id_muestra
                )}">${item.Muestra ?? ""}</textarea>
                        </td>
                        <td>
                         
                            <label>Tem.Muestra</label>
                            <input type="text" name="tem_muestra"  value="${safe(
                    item.Tem_muestra
                )}" data-id="${item.Id_muestra
                    }" style="width: 100%;" />
                                    <label>Tem.recep</label><br>
                                    <input type="text" name="tem_recepcion"  value="${safe(
                        item.Tem_recepcion
                    )}" data-id="${item.Id_muestra
                    }" style="width: 100%;" />
                         
                        </td>
                        <td>
                        <label>Unidad</label>
                         <input type="text"  name="unidad" value="${safe(
                        item.Unidad
                    )}" data-id="${item.Id_muestra}" style="width: 100%;" />
                         <label>Cant.</label>
                         <input type="text" name="cantidad" value="${safe(
                        item.Cantidad
                    )}" data-id="${item.Id_muestra}" style="width: 100%;" />
                        </td>

                        <td>
                        <textarea  name="observacion" data-id="${item.Id_muestra
                    }">${safe(item.Observacion)}</textarea>
                        </td>
                        <td>
                         <input type="datetime-local" class="form-control" name="fecha_muestreo" value="${formatDateTime(
                        item.Fecha_muestreo
                    )}" data-id="${item.Id_muestra}" />
                        </td>
                        
                        <td style="width: 10px;"> 
                        <button class="btn btn-info" data-id="${item.Id_muestra}" data-calculo="${item.Calculo}" onclick="CalculoTri(this.dataset.id, this.dataset.calculo)">
                          <i class="fas fa-bookmark"></i>
                        </button>                         
                        <button class="btn btn-success save-btn" data-id="${item.Id_muestra}"><i class="fas fa-save"></i> </button>
                        </td>
                    </tr>
                        `;
                let cont = 1
                if (item.Cumple == 0) {
                    filaIn = `
                            <p>Muestra ${cont} | Incumplimiento: ${item.Motivo}</p>
                        `;
                }
                cont++;

                $("#divPuntos tbody").append(fila);
                $("#showIncumplimiento").append(filaIn);
            });

            // Evento para capturar el click en el botón de guardar
            $(document).on("click", ".save-btn", function () {
                const idMuestra = $(this).data("id");
                const fila = $(this).closest("tr");
                const idSol = document.getElementById("idSol").value;

                const muestra = fila.find('[name="muestra"]').val();
                const temMuestra = fila.find('[name="tem_muestra"]').val();
                const temRecepcion = fila.find('[name="tem_recepcion"]').val();
                const observacion = fila.find('[name="observacion"]').val();
                const unidad = fila.find('[name="unidad"]').val();
                const cantidad = fila.find('[name="cantidad"]').val();
                const calculo = fila.find('[name="calculo"]').val();
                const motivo = fila.find('[name="motivo"]').val();
                const cumple = fila.find('[name="cumple"]').prop("checked")
                    ? 1
                    : 0;
                const fechamuestreo = fila
                    .find('[name="fecha_muestreo"]')
                    .val();
                const data = {
                    idSol: idSol,
                    Id_muestra: idMuestra,
                    muestra: muestra,
                    fechamuestreo: fechamuestreo,
                    tem_muestra: temMuestra,
                    tem_recepcion: temRecepcion,
                    observacion: observacion,
                    Num_unidad: unidad,
                    cantidad: cantidad,
                };

                UpdateMuestra(data);
            });

            // Reinicia DataTables
            // if ($.fn.DataTable.isDataTable("#codigos")) {
            //     $("#codigos").DataTable().destroy();
            // }
            // if ($.fn.DataTable.isDataTable("#puntos")) {
            //     $("#puntos").DataTable().destroy();
            // }

            // $("#codigos").DataTable({ ordering: false, paginate: false });
            // $("#puntos").DataTable({ ordering: false });

            // // Agregar eventos
            // $("#puntos tbody").on("click", "tr", function () {
            //     $(this).toggleClass("selected");
            // });

            // $("#codigos tbody").on("click", "tr", function () {
            //     $(this).toggleClass("selected");
            // });
        },
    });
}
function CalculoTri(id, calculo) {
  // Aseguramos que 'calculo' no sea null ni undefined
  calculo = calculo || "";

  let modal = document.getElementById('calculoModal');

  if (!modal) {
    modal = document.createElement('div');
    modal.id = 'calculoModal';
    modal.className = 'modal fade';
    modal.tabIndex = -1; // Recomendado por Bootstrap
    modal.innerHTML = `
      <div class="modal-dialog">
        <div class="modal-content">
        
          <div class="modal-header">
            <h5 class="modal-title">Cálculo Trimestral</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
<textarea id="textareaCalculo"
          class="form-control mb-3"
          rows="4"
          placeholder="Escribe tu cálculo Trimestral">${calculo || ""}</textarea>
            <button type="button" 
                    class="btn btn-primary" 
                    onclick="guardarCalculo(${id})">Guardar</button> 
          </div>

        </div>
      </div>
    `;
    document.body.appendChild(modal);
  } else {
    // Si el modal ya existe, actualizamos el contenido del textarea
    document.getElementById('textareaCalculo').value = calculo;
    // Actualizamos el botón por si cambia el id
    document.querySelector('#calculoModal .btn-primary')
            .setAttribute('onclick', `guardarCalculo(${id})`);
  }

  // Mostramos el modal con jQuery/Bootstrap
  $('#calculoModal').modal('show');
}

function guardarCalculo(id) {
    const texto = document.getElementById('textareaCalculo').value.trim();
    if (!texto) {
        alert('Escribe un cálculo antes de guardar.');
        return;
    }

    $.ajax({
        type: 'POST',
        url: base_url + '/admin/alimentos/guardarCalculo',
        dataType: 'json',
        data: {
            id_muestra: id,
            texto: texto,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (resp) {
            console.log(resp);
            $('#calculoModal').modal('hide');
            alert(resp.message);
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            alert('Hubo un problema al guardar el cálculo.');
        }
    });
}

function formatDateTime(value) {
    if (!value || value === "N/A") {
        return "";
    }
    // Reemplazar espacios por 'T' si es necesario
    value = value.replace(" ", "T");
    const date = new Date(value);
    if (isNaN(date.getTime())) {
        return "";
    }
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const day = String(date.getDate()).padStart(2, "0");
    const hours = String(date.getHours()).padStart(2, "0");
    const minutes = String(date.getMinutes()).padStart(2, "0");
    const seconds = String(date.getSeconds()).padStart(2, "0");
    return `${year}-${month}-${day}T${hours}:${minutes}:${seconds}`;
}

function UpdateMuestra(data) {
    console.log(data);
    // Realizar la solicitud AJAX para actualizar los datos
    $.ajax({
        type: "POST",
        url: base_url + "/admin/alimentos/UpdateMuestra",
        dataType: "json",
        data: data, // Pasar los datos tal cual como objeto
        success: function (response) {
            // alert("Muestra  Actualizada Correctamente");
        },
        error: function (error) {
            console.error("Error al actualizar la muestra:", error);
            alert("Ocurrió un error al actualizar la muestra.");
        },
    });
}

$(document).ready(function () {
    $("#btnSetCodigos").click(function () {
        if (confirm("Deseas generar codigos?")) {
            setGenFolio();
        }
    });
    $("#btnGetBitacora").click(function () {
        window.open(base_url + "/admin/alimentos/GetBitacora/");
    });

    $("#btnIngresar").click(function () {
        let folio = $("#folio").val();
        let cliente = $("#cliente").val();
        let empresa = $("#empresa").val();
        let horaRecepcion = $("#hora_recepcion1").val();
        let horaEntrada = $("#hora_entrada").val();
        // let fechaMuestreo = $("#fechaMuestreo").val();
        let recibe = $("#recibe").val();
        idsol = $("#idSol").val();
        let idRecibe = $("#recibe").val();
        let nombreRecibe = $("#recibe option:selected").text();

        console.log("Valores del formulario:", {
            folio,
            cliente,
            empresa,
            horaRecepcion,
            horaEntrada,
            idRecibe,
            nombreRecibe,
            idsol,
        });

        if (
            !folio ||
            !cliente ||
            !empresa ||
            !horaRecepcion ||
            !horaEntrada ||
            !idRecibe ||
            !nombreRecibe ||
            !idsol
        ) {
            alert("Faltan datos. Por favor, llena todos los campos.");
            return;
        }

        const data = {
            idsol,
            folio,
            cliente,
            empresa,
            hora_recepcion: horaRecepcion,
            hora_entrada: horaEntrada,
            // fechaMuestreo: fechaMuestreo,
            idRecibe,
            nombreRecibe,
        };

        console.log("Datos enviados:", data);
        ingresar(data);
    });
});

function ingresar(data) {
    $.ajax({
        url: base_url + "/admin/alimentos/ingresar",
        type: "POST",
        dataType: "json",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: data,
        // beforeSend: function () {
        //     console.log("Datos a enviar:", this.data);
        // },

        success: function (response) {
            // Si el servidor devuelve un mensaje en la respuesta exitosa
            alert(response.message);
        },
        error: function (xhr, status, error) {
            if (xhr.status === 403) {
                alert(xhr.responseJSON.message);
            } else {
                console.error("Error al enviar los datos:", error);
                alert("Ocurrió un error al procesar la solicitud.");
            }
        },
    });
}

function setGenFolio() {
    // Obtener el valor del input #idSol
    let idsol = $("#idSol").val();

    // Verificar que el valor no esté vacío antes de enviarlo
    if (!idsol) {
        alert("El campo ID SOL está vacío.");
        return;
    }

    $.ajax({
        url: base_url + "/admin/alimentos/CodigoAlimentos",
        type: "POST",
        dataType: "json",
        data: {
            idsol: idsol, // Enviar el valor obtenido
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.message) {
                alert(response.message);
                console.log("Folio:", response.Folio);
                console.log("idSol:", response.Id_solicitud);
                // console.log("Número de Parámetros:", response.ParametrosCount);
            }
        },
        error: function (xhr, status, error) {
            if (xhr.status === 400) {
                alert("Hubo un error al crear los códigos.");
            } else {
                console.error("Error:", error);
                alert("Hubo un error al crear los códigos.");
            }
        },
    });
}
