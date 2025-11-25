var idCodigo;
var dataModel;
var name;
var idPunto;
var detPa;

var resLiberado = 0;
var idCod = idCodigo;
var name = "";

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
            let dato2 = $(this).find("td:eq(2)").html();
            idmuestra=dato2;
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
        alert("No disponibles para alimento por el momento");
        setLiberar();
        
    });
    $("#ckSupervisado").click(function () {
        alert("No disponibles para alimento por el momento");
        setSupervicion();
    });
    $("#ckHistorial").click(function () {      
        alert("No disponibles para alimento por el momento");
        var historialValor = $(this).is(":checked") ? 1 : 0; 

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
        url: base_url + "/admin/alimentos/setEmision",
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
// function setSupervicion() {
//     $.ajax({
//         type: "POST",
//         url: base_url + "/admin/supervicion/cadena/setSupervicion",
//         data: {
//             idSol: $("#idSol").val(),
//             std: $("#ckSupervisado").prop("checked"),
//             _token: $('input[name="_token"]').val(),
//         },
//         dataType: "json",
//         async: false,
//         success: function (response) {
//             console.log(response);
//             alert(response.msg);
//         },
//     });
// }
// function setLiberar() {
//     $.ajax({
//         type: "POST",
//         url: base_url + "/admin/supervicion/cadena/setLiberar",
//         data: {
//             idSol: $("#idSol").val(),
//             std: $("#ckLiberado").prop("checked"),
//             _token: $('input[name="_token"]').val(),
//         },
//         dataType: "json",
//         async: false,
//         success: function (response) {
//             alert(response.msg);
//         },
//     });
// }
// function setHistorial(historialValor) {
//     $.ajax({
//         type: "POST",
//         url: base_url + "/admin/supervicion/cadena/setHistorial",
//         data: {
//             idSol: $("#idSol").val(),
//             historial: historialValor,

//             _token: $('input[name="_token"]').val(),
//         },
//         dataType: "json",
//         async: false,
//         success: function (response) {
//             if (response.sw == true) {
//                 swal("Registro!", "Proceso por historial", "success");

//                 $("#tableParametros tbody tr").each(function () {
//                     $(this).find("td:eq(5)").text(historialValor);
//                 });
//             } else {
//                 swal("Registro!", "Proceso por historial cancelado", "success");
//             }
//         },
//     });
// }

function getParametros() {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/alimentos/getParametroCadena",
        data: {
            idmuestra: idmuestra,
            idPunto: idPunto,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        success: function (response) {
            let tab = `
                <table id="tableParametros" class="table">
                <thead class="thead-dark">
                    <tr>
                        <th>Id</th>
                        <th>Parametro</th>
                        <th>Tipo formula</th>
                        <th style="width:50px">Ejec.</th>
                        <th style="width:50px">Res.</th>
                        <th>his</th>
                    </tr>
                </thead>
                <tbody>
            `;

            let countDanger = 0;

            // Función para determinar color de fila
            function getColorResultado2(item) {
                if (item.Resultado2 !== null) return "success";
                return "warning";
            }

            // Función para determinar color AP
            function getColorAP(item) {
                return item.Cadena == 0 ? "primary" : "";
            }

            // Función para determinar color Limite (LOL)
            function getColorLimite(item) {
                if (!item.Limite || item.Limite === "N/A" || item.Resultado2 == null) return "success";

                const resultado2 = parseFloat(item.Resultado2);
                if (item.Limite.includes("-")) {
                    const [minStr, maxStr] = item.Limite.split("-");
                    const min = parseFloat(minStr);
                    const max = parseFloat(maxStr);

                    if (!isNaN(resultado2) && resultado2 >= min && resultado2 <= max) return "success";
                    countDanger++;
                    return "danger";
                }

                const limiteNum = parseFloat(item.Limite);
                if (isNaN(resultado2) || isNaN(limiteNum)) return "success";

                if (resultado2 <= limiteNum) return "success";

                countDanger++;
                return "danger";
            }

            $.each(response.model, function (key, item) {
                // Colores
                let color = getColorResultado2(item);
                let AP = getColorAP(item);
                let LOL = getColorLimite(item);

                // Condiciones especiales para algunos Id_parametro
                const paramsDanger = [14,100,26,13,12,137,51,134,132,67,2,97,5,71,35,253,361];
                if (paramsDanger.includes(parseInt(item.Id_parametro))) {
                    color = (item.Liberado != 1) ? "danger" : "success";
                }

                tab += `<tr data-id_codigo="${item.Id_codigo}" data-id_parametro="${item.Id_parametro}">
                    <td>${item.Id_codigo}</td>
                    <td class="bg-${color}">(${item.Id_parametro}) ${item.Parametro}</td>
                    <td class="bg-${AP}">${item.Tipo_formula}</td>
                    <td class="bg-${AP}">${item.Resultado}</td>
                    <td class="bg-${AP}">${item.Resultado2}</td>
                    <td>
                        <button class="btn-warning" onclick="getHistorial(${item.Id_codigo})" data-toggle="modal" data-target="#modalHistorial">
                            <i class="fas fa-info"></i>
                        </button>
                    </td>
                </tr>`;
            });

            tab += "</tbody></table>";

            $("#divTableParametros").html(tab);

            // Inicializar DataTable
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

            // Evento click único en tbody tr
            $("#tableParametros tbody").on("click", "tr", function () {
                if ($(this).hasClass("selected")) {
                    $(this).removeClass("selected");
                } else {
                    tableParametro.$("tr.selected").removeClass("selected");
                    $(this).addClass("selected");

                    const idCodigo = $(this).data("id_codigo");
                    const idParametro = $(this).data("id_parametro");

                    getDetalleAnalisis(idCodigo, idParametro);
                    //console.log("ID codigo:", idCodigo, "ID parametro:", idParametro);
                }
            });

            // Aquí podrías mostrar mensaje si quieres
            /*
            const mensaje = $("#mensaje");
            if (countDanger == 0) {
                mensaje.text("No Hay Parametros Fuera de Rango").css("background-color", "green");
            } else {
                mensaje.text("Parametros Fuera de Rango: ").css("background-color", "red");
                const span = $("<span>").addClass("badge rounded-pill").css("background-color", "rgb(3, 196, 245)").text(" " + countDanger);
                mensaje.append(span);
            }
            */
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
           // console.log(response);
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
function reasignarMuestra2() {
   
    $.ajax({
        type: "POST",
        url: base_url + "/admin/alimentos/reasignarMuestra2",
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


function getDetalleAnalisis(idCodigo,Id_parametro) {
  
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
            Id_parametro: Id_parametro,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            dataModel = response.model;

            idCod = idCodigo;
            //console.log(parseInt(response.paraModel.Id_parametro));
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
