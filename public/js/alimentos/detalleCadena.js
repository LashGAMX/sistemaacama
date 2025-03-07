var idCodigo;
var dataModel;
var name;
var idPunto;
var detPa;
$(document).on("change", ".sugeridoCheckbox", function () {
    var Id_codigo = $(this).data("id");
    var sugerido_sup = this.checked ? 1 : 0;

    $.ajax({
        type: "POST",
        url: base_url + "/admin/supervicion/cadena/sugerido",
        data: {
            Id_codigo: Id_codigo,
            sugerido_sup: sugerido_sup,
        },
        success: function (response) {
            if (sugerido_sup === 1) {
                alert(
                    "Has marcado este resultado para que tu analista lo libere"
                );
            } else if (sugerido_sup === 0) {
                alert("Has desmarcado el resultado");
            }
        },
        error: function (xhr, status, error) {
            alert("Hubo un error al procesar la solicitud.");
        },
    });
});

$(document).ready(function () {
    let tablePunto = $("#tablePuntos").DataTable({
        ordering: false,
        language: {
            lengthMenu: "# _MENU_ por pagina",
            zeroRecords: "No hay datos encontrados",
            info: "Pagina _PAGE_ de _PAGES_",
            infoEmpty: "No hay datos encontrados",
        },
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
    });
    $("#btnCadena").click(function () {
        window.open(base_url + "/admin/informes/cadena/pdf/" + idPunto);
    });
    $("#btnCadenaVidrio").click(function () {
        window.open(base_url + "/admin/informes/cadenavidrio/pdf/" + idPunto);
    });
    $("#btnSetEmision").click(function () {
        setEmision();
    });
    $("#tablePuntos tbody").on("click", "tr", function () {
        if ($(this).hasClass("selected")) {
            $(this).removeClass("selected");
        } else {
            tablePunto.$("tr.selected").removeClass("selected");
            $(this).addClass("selected");
            let dato = $(this).find("td:first").html();
            idPunto = dato;
            getParametros();
            getFotos();
        }
    });

    $("#tableParametros").DataTable({
        ordering: false,
        language: {
            lengthMenu: "# _MENU_ por pagina",
            zeroRecords: "No hay datos encontrados",
            info: "Pagina _PAGE_ de _PAGES_",
            infoEmpty: "No hay datos encontrados",
        },
    });
    $("#tableResultado").DataTable({
        ordering: false,
        language: {
            lengthMenu: "# _MENU_ por pagina",
            zeroRecords: "No hay datos encontrados",
            info: "Pagina _PAGE_ de _PAGES_",
            infoEmpty: "No hay datos encontrados",
        },
    });
    $("#ckLiberado").click(function () {
        setLiberar();
    });
    $("#ckSupervisado").click(function () {
        setSupervicion();
    });
    $("#ckHistorial").click(function () {
        var historialValor = $(this).is(":checked") ? 1 : 0; //verifica si esta activado o desactivado

        setHistorial(historialValor);
    });
    $("#btnLiberar").click(function () {
        liberarResultado();
    });
});
function getFotos() {
    let divfotos = document.getElementById("fotos");
    $.ajax({
        type: "POST",
        url: base_url + "/admin/supervicion/cadena/getFotos",
        data: {
            id: idPunto,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: true,
        success: function (response) {
            let fotosHTML = "";

            response.model.forEach((item) => {
                fotosHTML += `<img src="data:image/jpeg;base64,${item.Foto}"  onclick="modalImagenMuestra('${item.Foto}')" style="width:50%" alt="Foto">`;
            });

            $("#fotos").html(`
                <div class="row">
                    <div class="col-md-12">
                        ${fotosHTML}
                    </div>
                </div>
            `);
        },
    });
}
function modalImagenMuestra(id) {
    $("#modalImgFoto").modal("show");
    console.log(id);
    let img = document.getElementById("divImagen");
    img.innerHTML =
        '<img src="data:image/png;base64,' +
        id +
        '" style="width:100%;height:auto;">';
}
function setEmision() {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/supervicion/cadena/setEmision",
        data: {
            idSol: $("#idSol").val(),
            fecha: $("#fechaEmision").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            alert(response.msg);
        },
    });
}
function setSupervicion() {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/supervicion/cadena/setSupervicion",
        data: {
            idSol: $("#idSol").val(),
            std: $("#ckSupervisado").prop("checked"),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            alert(response.msg);
        },
    });
}
function setLiberar() {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/supervicion/cadena/setLiberar",
        data: {
            idSol: $("#idSol").val(),
            std: $("#ckLiberado").prop("checked"),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            alert(response.msg);
        },
    });
}
function setHistorial(historialValor) {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/supervicion/cadena/setHistorial",
        data: {
            idSol: $("#idSol").val(),
            historial: historialValor,

            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            if (response.sw == true) {
                swal("Registro!", "Proceso por historial", "success");

                $("#tableParametros tbody tr").each(function () {
                    $(this).find("td:eq(5)").text(historialValor);
                });
            } else {
                swal("Registro!", "Proceso por historial cancelado", "success");
            }
        },
    });
}

function getParametros() {
    console.log("Este es el Id_SOL: " + idPunto);
    $.ajax({
        type: "POST",
        url: base_url + "/admin/alimentos/getParametroCadena",
        data: {
            idPunto: idPunto,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",

        success: function (response) {
            console.log(response);
            let tab = '<table id="tableParametros" class="table">';
            tab += '<thead class="thead-dark">';
            tab += "<tr>";
            tab += "<th>Id</th>";
            tab += "<th>Parametro</th>";
            tab += "<th>Tipo formula</th>";
            tab += '<th style="width:50px">Ejec.</th>';
            tab += '<th style="width:50px">Res.</th>';
            tab += "<th>his</th>";
            // tab += "<th>Limite</th>";
            // tab += "<th>%</th>";
            tab += "</tr>";
            tab += "</thead>";
            tab += "<tbody>";

            let countDanger = 0; // Contador para parámetros fuera de rango
            let cont = 0;
            $.each(response.model, function (key, item) {
                let color = "";
                let AP = "";

                if (item.Resultado2 != null) {
                    color = "success";
                } else {
                    color = "warning";
                }

                switch (parseInt(item.Id_parametro)) {
                    case 14: //Ph
                    case 100:
                    case 26: // Gasto
                    case 13: // GA
                    case 12: //Coliformes
                    case 137:
                    case 51:
                    case 134:
                    case 132:
                    case 67: //conductividad
                    case 2: //Materia Floatante
                    case 97: //Températura
                    case 100:
                    case 5:
                    case 71:
                    case 35:
                    case 253:
                    case 361:
                        if (item.Liberado != 1) {
                            color = "danger";
                        } else {
                            color = "success";
                        }
                        break;
                    default:
                        break;
                }

                if (item.Cadena == 0) {
                    AP = "primary";
                } else {
                    AP = "";
                }

                let LOL = "";

                if (
                    item.Limite == "N/A" ||
                    item.Limite == null ||
                    item.Resultado2 == null
                ) {
                    LOL = "success";
                } else if (item.Limite.includes("-")) {
                    var limites = item.Limite.split("-");
                    var limiteMin = parseFloat(limites[0]);
                    var limiteMax = parseFloat(limites[1]);

                    var resultado2 = parseFloat(item.Resultado2);
                    if (item.resultado2 < item.Limite) {
                        LOL = "success";
                    }
                    if (
                        !isNaN(resultado2) &&
                        resultado2 >= limiteMin &&
                        resultado2 <= limiteMax
                    ) {
                        LOL = "success";
                    } else {
                        LOL = "danger";
                        countDanger++;
                    }
                } else if (
                    parseFloat(item.Resultado2) == parseFloat(item.Limite)
                ) {
                    LOL = "success";
                } else if (
                    parseFloat(item.Resultado2) < parseFloat(item.Limite)
                ) {
                    LOL = "success";
                } else if (
                    parseFloat(item.Resultado2) > parseFloat(item.Limite)
                ) {
                    LOL = "danger";
                    countDanger++;
                } else {
                    LOL = "success";
                }

                tab += "<tr>";
                tab += "<td>" + item.Id_codigo + "</td>";
                tab +=
                    '<td class="bg-' +
                    color +
                    '">(' +
                    item.Id_parametro +
                    ") " +
                    item.Parametro +
                    "</td>";
                tab +=
                    '<td class="bg-' + AP + '">' + item.Tipo_formula + "</td>";
                tab += '<td class="bg-' + AP + '">' + item.Resultado + "</td>";
                tab += '<td class="bg-' + AP + '">' + item.Resultado2 + "</td>";
                tab +=
                    '<td><button class="btn-warning" onclick="getHistorial(' +
                    item.Id_codigo +
                    ')" data-toggle="modal" data-target="#modalHistorial"><i class="fas fa-info"></i></button></td>';
                // tab += '<td class="bg-' + LOL + '">' + item.Limite + "</td>";
                // tab += '<td class="bg-'+response.porcentajeCom[cont]+'">' + response.porcentaje[cont] + '</td>';
                tab += "</tr>";
                cont++;
            });

            tab += "</tbody>";
            tab += "</table>";

            $("#divTableParametros").html(tab);

            // const mensaje = $("#mensaje");

            // if (countDanger == 0) {
            //     mensaje.text("No Hay Parametros Fuera de Rango");
            //     mensaje.css("background-color", "green");
            // } else if (countDanger > 0) {
            //     mensaje.text("Parametros Fuera de Rango:  ");
            //     mensaje.css("background-color", "red");

            //     const span = $("<span>")
            //         .addClass("badge rounded-pill")
            //         .css("background-color", "rgb(3, 196, 245)");
            //     span.text(" " + countDanger);

            //     mensaje.append(span);
            // }

            let tableParametro = $("#tableParametros").DataTable({
                ordering: false,
                language: {
                    lengthMenu: "# _MENU_ por pagina",
                    zeroRecords: "No hay datos encontrados",
                    info: "Pagina _PAGE_ de _PAGES_",
                    infoEmpty: "No hay datos encontrados",
                },
                scrollY: "300px",
                scrollCollapse: true,
                paging: false,
            });
            $("#tableParametros tbody").on("click", "tr", function () {
                if ($(this).hasClass("selected")) {
                    $(this).removeClass("selected");
                } else {
                    tableParametro.$("tr.selected").removeClass("selected");
                    $(this).addClass("selected");
                    getDetalleAnalisis(idCodigo);
                    console.log("ID codigo: " + idCodigo);
                }
            });
            $("#tableParametros tr").on("click", function () {
                let dato = $(this).find("td:first").html();
                idCodigo = dato;
            });
        },

        error: function (xhr, status, error) {
            console.error("Error en la solicitud AJAX:", status, error);
        },
    });
}

function regresarMuestra() {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/alimentos/regresarMuestra",
        data: {
            idSol: $("#idSol").val(),
            idCodigo: idCod,

            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            alert("Muestra regresada");

            var table = $("#tableParametros").DataTable();
            table.rows().every(function () {
                var rowData = this.data();
                var rowId = rowData[0];

                if (rowId == idCodigo) {
                    this.cell(this.index(), 4).data(response.resLiberado);

                    $(this.node())
                        .find("td:eq(1)")
                        .removeClass("bg-success")
                        .addClass("bg-warning");
                    return false;
                }
            });
        },
    });
}
function reasignarMuestra() {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/alimentos/reasignarMuestra",
        data: {
            idSol: $("#idSol").val(),
            idCodigo: idCod,
            _token: $('input[name="_token"]').val(),
        },
        // beforeSend: function()
        // {
        //  console.log('Datos a enviar:', this.data);
        //  },

        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            alert(response.msg);
            getDetalleAnalisis(idCodigo);
        },
    });
}

function desactivarMuestra() {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/alimentos/desactivarMuestra",
        data: {
            idSol: $("#idSol").val(),
            idCodigo: idCod,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            var table = $("#tableParametros").DataTable();
            table.rows().every(function () {
                var rowData = this.data();
                var rowId = rowData[0];

                if (rowId == idCodigo) {
                    alert("Muestra desactivada");
                    $(this.node()).find("td:eq(2)").addClass("bg-primary");
                    $(this.node()).find("td:eq(3)").addClass("bg-primary");
                    $(this.node()).find("td:eq(4)").addClass("bg-primary");
                }
            });
        },
    });
}

var resLiberado = 0;
var idCod = idCodigo;
var name = "";
function getDetalleAnalisis(idCodigo) {
    let tabla = document.getElementById("divTabDescripcion");
    let tab = "";
    let aux = 0;
    let cont = 0;
    let lib = "";
    tabla.innerHTML = tab;
    resLiberado = 0;
    $("#resDes").val(0.0);
    $.ajax({
        type: "POST",
        url: base_url + "/admin/alimentos/getDetalleAnalisis",
        data: {
            idSol: $("#idSol").val(),
            idCodigo: idCodigo,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            dataModel = response.model;

            idCod = idCodigo;
            console.log(parseInt(response.paraModel.Id_parametro));
            switch (parseInt(response.paraModel.Id_parametro)) {
                default:
                    tab += '<table id="tableResultado" class="table table-sm">';
                    tab += '    <thead class="thead-dark">';
                    tab += "        <tr>";
                    tab += "          <th>Descripcion</th>";
                    tab += "          <th>Valor</th>";
                    tab += "        </tr>";
                    tab += "    </thead>";
                    tab += "    <tbody>";
                    $.each(response.model, function (key, item) {
                        if (parseInt(item.Liberado) != 1) {
                            lib = "bg-danger";
                        } else {
                            lib = "bg-success";
                        }
                        tab += "<tr>";
                        tab +=
                            '<td class="' +
                            lib +
                            '">' +
                            item.Parametro +
                            "</td>";
                        tab += "<td>" + item.Resultado + "</td>";
                        tab += "</tr>";
                        resLiberado = item.Resultado;
                    });
                    tab += "    </tbody>";
                    tab += "</table>";
                    tabla.innerHTML = tab;
                    break;
            }
            $("#resDes").val(resLiberado);
            let tableResultado = $("#tableResultado").DataTable({
                ordering: false,
                language: {
                    lengthMenu: "# _MENU_ por pagina",
                    zeroRecords: "No hay datos encontrados",
                    info: "Pagina _PAGE_ de _PAGES_",
                    infoEmpty: "No hay datos encontrados",
                },
            });
            $("#tableResultado tbody").on("click", "tr", function () {
                if ($(this).hasClass("selected")) {
                    $(this).removeClass("selected");
                } else {
                    tableResultado.$("tr.selected").removeClass("selected");
                    $(this).addClass("selected");
                    let dato = $(this).find("td:eq(1)").html();
                    detPa = dato;
                    resLiberado = detPa;
                }
            });
        },
    });
}

function liberarResultado() {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/supervicion/cadena/liberarMuestra",
        data: {
            idSol: $("#idSol").val(),
            idPunto: idPunto,
            idCod: idCod,
            resLiberado: resLiberado,
            _token: $('input[name="_token"]').val(),
        },

        dataType: "json",
        success: function (response) {
            var table = $("#tableParametros").DataTable();
            table.rows().every(function () {
                var rowData = this.data();
                var rowId = rowData[0];

                if (rowId == idCodigo) {
                    this.cell(this.index(), 4).data(response.resLiberado);

                    $(this.node()).find("td:eq(4)").text(resLiberado);
                    $(this.node())
                        .find("td:eq(1)")
                        .removeClass("bg-warning")
                        .removeClass("bg-danger")
                        .addClass("bg-success");
                    return false;
                }
            });
        },
    });
}

function liberarSolicitud() {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/supervicion/cadena/liberarSolicitud",
        data: {
            idSol: $("#idSol").val(),
            liberado: $("#ckLiberado").prop("checked"),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            if (response.sw == true) {
                swal("Registro!", "Solicitud liberada", "success");
            } else {
                swal("Registro!", "Liberacion modificada", "success");
            }
        },
    });
}

function getHistorial(id) {
    console.log("Get Historial");
    let tabla1 = document.getElementById("divTablaHist");
    let tab1 = "";

    $.ajax({
        type: "POST",
        url: base_url + "/admin/supervicion/cadena/getHistorial",
        data: {
            idCodigo: id,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            tab1 += `
                <table id="tablaLoteModal" class="table table-sm">
                    <thead class="thead-dark">
                        <tr>
                            <th>Id lote</th>
                            <th>Fecha lote</th>
                            <th>Codigo</th>
                            <th>Parametro</th>
                            <th>Resultado</th>
                    
                        </tr>
                    </thead>
                    <tbody>
                            ${$.map(response.idsLotes, function (item, index) {
                                let estilo =
                                    parseInt(response.historialHist[index]) == 1
                                        ? "background-color:#e5e5ff;"
                                        : "";
                                return `
                                 
                                    
                                        <tr>
                                            <td>${item}</td>
                                            <td style ="${estilo}">${response.fechaLote[index]}</td>
                                            <td style ="${estilo}">${response.Codigohist[index]}</td>
                                            <td style ="${estilo}"">${response.parametrohist[index]}</td>
                                            <td style ="${estilo}">${response.resultadoHist[index]}</td>
                                            
                                        </tr>
                                    `;
                            }).join("")}
                    </tbody>
                 </table>
            `;

            tabla1.innerHTML = tab1;

            var t2 = $("#tablaCodigosHistorial").DataTable({
                ordering: false,
                paging: false,
                scrollY: "300px",
                language: {
                    lengthMenu: "# _MENU_ por pagina",
                    zeroRecords: "No hay datos encontrados",
                    info: "Pagina _PAGE_ de _PAGES_",
                    infoEmpty: "No hay datos encontrados",
                },
            });
        },
    });
}
