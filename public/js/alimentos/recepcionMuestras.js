// Declaracion de la variable global
let folio = null;
let cliente = null;
let empresa = null;
let horaRecepcion = null;
let horaEntrada = null;

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
        // beforeSend: function()
        // {
        //  console.log('Datos a enviar:', this.data);
        //  },
        success: function (response) {
            // Si el folio no existe
            if (!response.folio) {
                alert("No Existe ese Folio. Verificalo en Solicitudes");
                $("#cliente").val("");
                $("#empresa").val("");
                $("#folio").val("");
                $("#descarga").val("");
                $("#divPuntos tbody").empty();
                $("#divCodigos tbody").empty();
                $("#hora_recepcion1").empty();
                $("#hora_entrada").empty();

                // Destruir DataTables si existen
                if ($.fn.DataTable.isDataTable("#codigos")) {
                    $("#codigos").DataTable().destroy();
                }
                if ($.fn.DataTable.isDataTable("#puntos")) {
                    $("#puntos").DataTable().destroy();
                }
                return;
            }

            // Si el folio existe, procesa los datos
            $("#folio").val(response.folio.Folio || "N/A");
            $("#cliente").val(response.folio.Cliente || "N/A");
            $("#empresa").val(response.folio.Sucursal || "N/A");
            $("#idSol").val(response.folio.Id_solicitud || "");
            const idsol = response.folio.Id_solicitud || ""; // Asigna a idsol directamente desde la respuesta
            console.log("Valor asignado a idSol:", $("#idSol").val());

            $("#hora_recepcion1").val(
                formatDateTime(response.proceso.Hora_recepcion)
            );
            $("#hora_entrada").val(
                formatDateTime(response.proceso.Hora_entrada)
            );
            $("#recibe").val(response.proceso.Id_recibio || "0");
         
            $("#ingreso")
                .text(
                    response.proceso.Ingreso === 1
                        ? "Muestra Ingresada"
                        : response.proceso.Ingreso === null
                        ? "Muestra no ingresada"
                        : "N/A"
                )
                .css("color", response.proceso.Ingreso === 1 ? "green" : "red");

             
                

            // Limpia las tablas y llena con nuevos datos
            $("#divCodigos tbody").empty();
            $("#divPuntos tbody").empty();

            response.codigos.forEach(function (item) {
                const fila = `<tr><td>${item.Codigo}</td><td>${item.Parametro}</td></tr>`;
                $("#divCodigos tbody").append(fila);
            });
            response.muestra.forEach(function (item) {
                const fila = `
                    <tr data-id="${item.Id_muestra}">
                        <td style="width: 5px;">${item.Id_muestra}</td>
                        <td style="width: 10px;">
                            <input type="text" class="form-control" value="${item.Muestra}" data-id="${item.Id_muestra}" />
                        </td>
                        <td style="width: 10px;">
                            <input type="text" class="form-control" value="${item.Tem_muestra}" data-id="${item.Id_muestra}" />
                        </td>
                        <td style="width: 10px;">
                            <input type="text" class="form-control" value="${item.Tem_recepcion}" data-id="${item.Id_muestra}" />
                        </td>
                          <td style="width: 10px;">
                            <input type="text" class="form-control" value="${item.Observacion}" data-id="${item.Id_muestra}" />
                        </td>
                        <td style="width: 10px;">
                            <button class="btn btn-success save-btn" data-id="${item.Id_muestra}">
                                <i class="fas fa-save"></i>
                            </button>
                        </td>
                    </tr>`;
                $("#divPuntos tbody").append(fila);
            });
            // Evento para capturar el click en el botón de guardar
            $(document).on("click", ".save-btn", function () {
                // Obtienes el ID del botón
                const idMuestra = $(this).data("id");

                const fila = $(this).closest("tr");
                const muestra = fila.find('input[type="text"]').eq(0).val();
                const temMuestra = fila.find('input[type="text"]').eq(1).val();
                const temRecepcion = fila
                    .find('input[type="text"]')
                    .eq(2)
                    .val();
                const observacion = fila.find('input[type="text"]').eq(3).val();

                const data = {
                    Id_muestra: idMuestra,
                    muestra: muestra,
                    tem_muestra: temMuestra,
                    tem_recepcion: temRecepcion,
                    observacion: observacion,
                };

                UpdateMuestra(data);
            });

            // Reinicia DataTables
            if ($.fn.DataTable.isDataTable("#codigos")) {
                $("#codigos").DataTable().destroy();
            }
            if ($.fn.DataTable.isDataTable("#puntos")) {
                $("#puntos").DataTable().destroy();
            }

            $("#codigos").DataTable({ ordering: false, paginate: false });
            $("#puntos").DataTable({ ordering: false });

            // Agregar eventos
            $("#puntos tbody").on("click", "tr", function () {
                $(this).toggleClass("selected");
            });

            $("#codigos tbody").on("click", "tr", function () {
                $(this).toggleClass("selected");
            });
        },
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
    //   console.log("dato",data);
    // Realizar la solicitud AJAX para actualizar los datos
    $.ajax({
        type: "POST",
        url: base_url + "/admin/alimentos/UpdateMuestra",
        dataType: "json",
        data: data, // Pasar los datos tal cual como objeto
        success: function (response) {
            alert("Muestra  Actualizada Correctamente");
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
        let folio = $("#folioSol").val();
        let cliente = $("#cliente").val();
        let empresa = $("#empresa").val();
        let horaRecepcion = $("#hora_recepcion1").val();
        let horaEntrada = $("#hora_entrada").val();
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
            !nombreRecibe||
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
            idRecibe,
            nombreRecibe,
        };

        console.log("Datos enviados:", data);
        ingresar(data);
    });
});

function ingresar(data) {
    $.ajax({
        url: base_url + "/admin/alimentos/ingresar", // Asegúrate de que `base_url` esté definido
        type: "POST",
        dataType: "json",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), // Incluye el token CSRF
        },
        data: data,
        beforeSend: function() 
  {
   console.log('Datos a enviar:', this.data);
   },

        success: function (response) {
            // Si el servidor devuelve un mensaje en la respuesta exitosa
            alert(response.message);
        },
        error: function (xhr, status, error) {
            // Verificar si el código de estado es 403 (Prohibido)
            if (xhr.status === 403) {
                // Si es el caso, mostramos el mensaje de que no se puede actualizar
                alert(xhr.responseJSON.message);
            } else {
                // Si ocurre otro tipo de error, mostramos un mensaje genérico
                console.error("Error al enviar los datos:", error);
                alert("Ocurrió un error al procesar la solicitud.");
            }
        },
    });
}

function setGenFolio() {
    // console.log("Generando:", idsol);

    $.ajax({
        url: base_url + "/admin/alimentos/CodigoAlimentos",
        type: "POST",
        dataType: "json",
        data: {
            idsol: idsol,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.message) {
                alert(response.message);
                console.log("Folio:", response.Folio);
                console.log("idSol", response.Id_solicitud);
                // console.log("Número de Parámetros:", response.ParametrosCount);
            }
        },
        error: function (xhr, status, error) {
            if (xhr.status === 400) {
                //En  Caso de códigos existentes
                alert("Hubo un error al crear los códigos.");
            } else {
                console.error("Error:", error);
                alert("Hubo un error al crear los códigos.");
            }
        },
    });
}
