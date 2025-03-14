var table;
// var selectedRow = false;
var idCot = 0;
$(document).ready(function () {
    $(".select2").select2();
    $(".select2Data").select2({
        ajax: {
            url: base_url + "/admin/cotizacion/solicitud/getClienteSol", //archivo que recibe la peticion
            dataType: "json",
            delay: 250, // Retardo en milisegundos antes de enviar la solicitud
            type: "POST", //método de envio
            data: function (params) {
                console.log(params);
                return {
                    q: params.term, // Término de búsqueda ingresado por el usuario
                    _token: $('input[name="_token"]').val(),
                };
            },
            processResults: function (data) {
                // Formatea los resultados para Select2
                return {
                    results: data.model.map(function (item) {
                        return {
                            id: item.Id_cliente,
                            text: item.Empresa,
                        };
                    }),
                };
            },
            cache: true, // Almacena en caché los resultados para evitar múltiples solicitudes
        },
    });

    table = $("#tablaSolicitud").DataTable({
        ordering: false,
        paging: false,
        language: {
            lengthMenu: "# _MENU_ por pagina",
            zeroRecords: "No hay datos encontrados",
            info: "Pagina _PAGE_ de _PAGES_",
            infoEmpty: "No hay datos encontrados",
        },
    });
    $("#tablaSolicitud tbody").on("click", "tr", function () {
        if ($(this).hasClass("selected")) {
            $(this).removeClass("selected");
            // console.log("no selecionado");
            // selectedRow = false;
            $("#btnEdit").prop("disabled", true);
            idCot = 0;
        } else {
            table.$("tr.selected").removeClass("selected");
            $(this).addClass("selected");
            // console.log("Seleccionado");
            // selectedRow = true;
            $("#btnEdit").prop("disabled", false);
        }
    });
    // Función para formatear las fechas
    function formatDate(dateString) {
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, "0");
        const month = String(date.getMonth() + 1).padStart(2, "0"); // Los meses empiezan en 0
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }

    // Al generar la tabla

    $("#btnBuscar").click(function () {
        let tablaDoc = document.getElementById("divTabla");
        let table = "";
        $.ajax({
            url: base_url + "/admin/cotizacion/solicitud/buscar", //archivo que recibe la peticion
            type: "POST", //método de envio
            data: {
                nombre: $("#cliente").val(),
                folio: $("#folio").val(),
                norma: $("#norma").val(),

                _token: $('input[name="_token"]').val(),
            },
            dataType: "json",
            async: false,
            success: function (response) {
                console.log(response);
                let estado = "";
                table += ' <table class="table table-sm" id="tablaSolicitud">';
                table += '    <thead class="thead-dark">';
                table += "        <tr>";
                table += "            <th>Id</th>";
                table += "            <th>Estado</th>";
                table += "            <th>Folio servicio</th>";
                table += "            <th>Folio cotización</th>";
                table += "            <th>Fecha Muestreo</th>";
                table += "            <th>Nombre Cliente</th>";
                table += "            <th>Norma</th>";
                table += "            <th>Tipo descarga</th>";
                table += "            <th>Creado por:</th>";
                table += "            <th>Fecha creación</th>";
                table += "            <th>Actualizado por</th>";
                table += "            <th>Fecha actualización</th>";
                table += "        </tr>";
                table += "    </thead>";
                table += "    <tbody>";
                $.each(response.model, function (key, item) {
                    let estado =
                        parseInt(item.Cancelado) === 1 ? "bg-danger" : "";
                    table += "<tr>";
                    table +=
                        '    <td class="' +
                        estado +
                        '">' +
                        item.Id_cotizacion +
                        "</td>";
                    table +=
                        '    <td class="' +
                        estado +
                        '">' +
                        (item.cotizacion
                            ? item.cotizacion.Estado_cotizacion == 1
                                ? "Cotización"
                                : item.cotizacion.Estado_cotizacion == 2
                                ? "Solicitud"
                                : "Sin estado"
                            : "") +
                        "</td>";
                    table +=
                        '    <td class="' +
                        estado +
                        '">' +
                        (item.Folio_servicio || "") +
                        "</td>";
                    table +=
                        '    <td class="' +
                        estado +
                        '">' +
                        (item.Folio || "") +
                        "</td>";
                    table +=
                        '    <td class="' +
                        estado +
                        '">' +
                        (item.Fecha_muestreo || "") +
                        "</td>";
                    table +=
                        '    <td class="' +
                        estado +
                        '">' +
                        (item.cliente ? item.cliente.Nombres : "") +
                        "</td>";
                    table +=
                        '    <td class="' +
                        estado +
                        '">' +
                        (item.norma ? item.norma.Clave_norma : "") +
                        "</td>";
                    table +=
                        '    <td class="' +
                        estado +
                        '">' +
                        (item.descarga ? item.descarga.Descarga : "") +
                        "</td>";
                    table +=
                        '    <td class="' +
                        estado +
                        '">' +
                        (item.user_c ? item.user_c.name : "") +
                        "</td>";
                    table +=
                        '<td class="' +
                        estado +
                        '">' +
                        formatDate(item.created_at) +
                        "</td>";
                    table +=
                        '    <td class="' +
                        estado +
                        '">' +
                        (item.user_m ? item.user_m.name : "") +
                        "</td>";
                    table +=
                        '<td class="' +
                        estado +
                        '">' +
                        formatDate(item.updated_at) +
                        "</td>";
                    table += "</tr>";
                });

                table += "    </tbody>";
                table += "</table>";
                tablaDoc.innerHTML = table;

                table = $("#tablaSolicitud").DataTable({
                    ordering: false,
                    paging: false,
                    language: {
                        lengthMenu: "# _MENU_ por pagina",
                        zeroRecords: "No hay datos encontrados",
                        info: "Pagina _PAGE_ de _PAGES_",
                        infoEmpty: "No hay datos encontrados",
                    },
                });
                $("#btnEdit").prop("disabled", true);
                $("#tablaSolicitud tbody").on("click", "tr", function () {
                    if ($(this).hasClass("selected")) {
                        $(this).removeClass("selected");
                        // console.log("no selecionado");
                        // selectedRow = false;
                        $("#btnEdit").prop("disabled", true);
                    } else {
                        table.$("tr.selected").removeClass("selected");
                        $(this).addClass("selected");
                        //console.log("Seleccionado");
                        //selectedRow = true;
                        $("#btnEdit").prop("disabled", false);
                    }
                });
                $("#tablaSolicitud tr").on("click", function () {
                    let dato = $(this).find("td:first").html();
                    idCot = dato;
                });
                $("#tablaSolicitud tbody").on("dblclick", "tr", function () {
                    let dato = $(this).find("td:first").html();
                    idCot = dato;
            
                    if (idCot > 0) {
                        window.location = ""
                    } else {
                        alert("Primero selecciona una orden");
                    }
                });
            },
        });
    });

    $("#tablaSolicitud tr").on("click", function () {
        let dato = $(this).find("td:first").html();
        idCot = dato;
    });
    $("#tablaSolicitud tbody").on("dblclick", "tr", function () {
        let dato = $(this).find("td:first").html();
        idCot = dato;

        if (idCot > 0) {
            window.location =
                base_url + "/admin/cotizacion/solicitud/updateOrden/" + idCot;
        } else {
            alert("Primero selecciona una orden");
        }
    });

    $("#btnCreate").click(function () {
        if (idCot > 0) {
            window.location =
                base_url + "/admin/cotizacion/solicitud/create/" + idCot;
        } else {
            window.location = base_url + "/admin/cotizacion/solicitud/create";
        }
    });

    $("#btnCreateSinCot").click(function () {
        // alert(idCot);
        window.location = base_url + "/admin/cotizacion/solicitud/createSinCot";
    });
    $("#btnCancelar").click(function () {
        // alert(idCot);
        cancelarOrden();
    });

    $("#btnEdit").click(function () {
        if (idCot > 0) {
            window.location =
                base_url + "/admin/cotizacion/solicitud/updateOrden/" + idCot;
        } else {
            alert("Primero selecciona una orden");
        }
    });
    $("#btnImprimir").click(function () {
        // alert("Imprimir PDF");
        window.open(
            base_url + "/admin/cotizacion/solicitud/exportPdfOrden/" + idCot
        );
        //window.location = base_url+"/admin/cotizacion/solicitud/exportPdfOrden/"+idCot;
    });

    $("#btnDuplicar").click(function () {
        window.location =
            base_url + "/admin/cotizacion/solicitud/duplicarSolicitud/" + idCot;
    });

    $("#btnGenFolio").click(function () {
        //alert("Imprimir GenerarFolio");
        let element = [];
        let ult = 0;
        $.ajax({
            url: base_url + "/admin/cotizacion/solicitud/setGenFolio", //archivo que recibe la peticion
            type: "POST", //método de envio
            data: {
                idCot: idCot,
                _token: $('input[name="_token"]').val(),
            },
            dataType: "json",
            async: false,
            success: function (response) {
                console.log(response);
                if (response.sw == true) {
                    swal(
                        "success",
                        "Esta solicitud ya tiene codigos registrados"
                    );
                } else {
                    swal("success", "Codigos creados satisfactoriamente");
                }
            },
        });
    });
});

function cancelarOrden() {
    let confirmObs = prompt(
        "Estas segur@ de cancelar esta orden de servicio?",
        "Por favor escriba el motivo de la cancelacion"
    );
    if (confirmObs == null || confirmObs == "") {
        alert("Es obligatorio escribir el motivo de la cancelacion");
    } else {
        $.ajax({
            url: base_url + "/admin/cotizacion/solicitud/cancelarOrden", //archivo que recibe la peticion
            type: "POST", //método de envio
            data: {
                id: idCot,
                obs: confirmObs,
                _token: $('input[name="_token"]').val(),
            },
            dataType: "json",
            async: false,
            success: function (response) {
                alert(response.msg);
            },
        });
    }
}
