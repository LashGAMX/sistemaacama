var idSol = 0;
var idPunto = 0;
var firma64 = "";
$(document).ready(function () {
    let folioSeleccionado = "";

    let table = $("#tableServicios").DataTable({
        ordering: false,
        paging: false,
        language: {
            lengthMenu: "# _MENU_ por pagina",
            zeroRecords: "No hay datos encontrados",
            info: "Pagina _PAGE_ de _PAGES_",
            infoEmpty: "No hay datos encontrados",
        },
    });

    $("#tableServicios tbody").on("click", "tr", function () {
        if ($(this).hasClass("selected")) {
            $(this).removeClass("selected");
            // console.log(this.children[1].innerHTML);
            folioSeleccionado = "";
        } else {
            table.$("tr.selected").removeClass("selected");
            $(this).addClass("selected");
            folioSeleccionado = this.children[1].innerHTML;
            // console.log(this.children[1].innerHTML);
        }
    });
    $("#searchButton").on("click", function () {
        const monthValue = $("#monthInput").val();
        //  console.log("Mes seleccionado:", monthValue);
        BuscarMesA(monthValue);
    });
    $("#tableServicios tr").on("click", function () {
        let dato = $(this).find("td:first").html();
        idSol = dato;
    });

    $("#btnImprimir").on("click", function () {
        switch ($("#tipoReporte").val()) {
            case "1":
            case "2":
                window.open(
                    base_url +
                        "/admin/informes/exportPdfInforme/" +
                        idSol +
                        "/" +
                        $("#puntoMuestreo").val() +
                        "/" +
                        $("#tipoReporte").val()
                );
                break;
            case "3":
                window.open(
                    base_url +
                        "/admin/informes/exportPdfInformeCampo/" +
                        idSol +
                        "/" +
                        $("#puntoMuestreo").val()
                );
                break;
            case "4":
                window.open(
                    base_url +
                        "/admin/informes/CustodiaInterna/" +
                        idSol +
                        "/" +
                        $("#puntoMuestreo").val()
                );
                break;
            case "5":
                window.open(
                    base_url +
                        "/admin/informes/exportPdfInformeVidrio/" +
                        idSol +
                        "/" +
                        $("#puntoMuestreo").val()
                );
                break;
            case "6":
                window.open(
                    base_url +
                        "/admin/informes/exportPdfInformeAdd/" +
                        idSol +
                        "/" +
                        $("#puntoMuestreo").val()
                );
                break;
            case "7":
                window.open(
                    base_url +
                        "/admin/informes/exportHojaCampoAdd/" +
                        $("#puntoMuestreo").val()
                );
                break;
            case "8":
                window.open(
                    base_url +
                        "/admin/informes/exportPdfInformeEbenhochSin/" +
                        idSol +
                        "/" +
                        $("#puntoMuestreo").val()
                );
                break;
            case "9":
                window.open(
                    base_url +
                        "/admin/informes/exportPdfInformeEbenhochSolo/" +
                        idSol +
                        "/" +
                        $("#puntoMuestreo").val()
                );
                break;
            case "10":
                window.open(
                    base_url +
                        "/admin/informes/cadenavidrio/pdf/" +
                        $("#puntoMuestreo").val()
                );
                break;
          
            default:
                break;
        }
    });

    $("#btnNota").on("click", function () {
        setNota4(idSol);
    });
    $("#firmaAut").on("click", function () {
        setFirmaAut(idSol);
    });

    $(document).on("change", "#puntoMuestreo", function () {
        setTimeout(() => {
            // console.log(folioSeleccionado);
            // console.log($(this).val());
            setDatosTablaParametro(folioSeleccionado, $(this).val());
        }, 200);
    });

    $("#btnFirma").on("click", function () {
        setfirmaPad();
    });
});
function BuscarMesA(monthValue) {
    $.ajax({
        url: base_url + "/admin/informes/BuscarMesA",
        type: "POST",
        data: {
            mes: monthValue,
            _token: $('input[name="_token"]').val(),
        },
        success: function (response) {
            // Generar las filas dinámicamente
            let rowsHtml = "";
            response.rows.forEach(function (item) {
                rowsHtml += `
                    <tr onclick="getPuntoMuestro(${item.id_solicitud})">
                        <td>${item.id_solicitud}</td>
                        <td>${item.folio}</td>
                        <td>${item.empresa}</td>
                        <td>${item.clave_norma}</td>
                        <td>${item.servicio}</td>
                        <td>${item.obs_proceso}</td>
                    </tr>
                `;
            });

            // Insertar las filas generadas en el tbody
            $("#ContenidoServicios").html(rowsHtml);
        },
        error: function (xhr, status, error) {
            console.error("Error en la petición:", error);
        },
    });
}

function setBase64(cod) {
    console.log("setBase64",cod);
    const base64Image = "data:image/png;base64," + cod; // Tu cadena Base64 aquí

    const canvas = document.getElementById("cnv");
    const ctx = canvas.getContext("2d");

    const img = new Image();
    img.onload = function () {
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
    };
    img.src = base64Image; // Asigna la imagen en Base64 como fuente
}

function clearCanva() {
    const canvas = document.getElementById("cnv");
    const ctx = canvas.getContext("2d");

    // Limpia todo el canvas
    ctx.clearRect(0, 0, canvas.width, canvas.height);
}

function setfirmaPad() {
    $.ajax({
        url: base_url + "/admin/informes/setfirmaPad",
        type: "POST", //método de envio
        data: {
            id: $("#puntoMuestreo").val(),
            firma: firma64,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            alert(response.msg);
        },
    });
}
function setFirmaAut(id) {
    if (confirm("Estas seguro de firmas estos informes?")) {
        $.ajax({
            url: base_url + "/admin/informes/setFirmaAut",
            type: "POST", //método de envio
            data: {
                id: id,
                firma: $("#firmaAut").prop("checked"),
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
}

function setNota4(id) {
    if (id != 0) {
        console.log("Entro al if");
        $.ajax({
            url: base_url + "/admin/informes/setNota4",
            type: "POST", //método de envio
            data: {
                id: id,
                nota: $("#nota").prop("checked"),
                _token: $('input[name="_token"]').val(),
            },
            dataType: "json",
            async: false,
            success: function (response) {
                console.log(response);
                alert("Nota modificada");
            },
        });
    } else {
        alert(
            "Tienes que seleccionar un informe antes de seleccionar la nota 4"
        );
    }
}

function getPuntoMuestro(id) {

    console.log("asq",id);
    let tabla = document.getElementById("selPuntos");
    let tab = "";
    let temp = 0;
    $.ajax({
        url: base_url + "/admin/informes/getPuntoMuestro",
        type: "POST", //método de envio
        data: {
            id: id,
            _token: $('input[name="_token"]').val(),
        },
        
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            tab = "";
            tab += '<select class="form-control" id="puntoMuestreo">';
            $.each(response.model, function (key, item) {
                tab +=
                    '  <option value="' +
                    item.Id_solicitud +
                    '">' +
                    item.Punto +
                    "</option>";
                if (temp == 1) {
                    idPunto = item.Id_solicitud;
                }
                temp++;
            });
            tab += "</select>";
            tabla.innerHTML = tab;
            $("#puntoMuestreo").trigger("change");
        },
    });
}

function getSolParametro() {
    let tabla = document.getElementById("divServicios");
    let tab = "";

    $.ajax({
        url: base_url + "/admin/informes/getSolParametro",
        type: "POST", //método de envio
        data: {
            id: idSol,
            idPunto: $("#puntoMuestreo").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            setBase64(response.proceso.Firma_autorizo);
            console.log(response);
            tab +=
                '<table id="tablaParametro" class="table" style="width: 100%; font-size: 10px">';
            tab += '    <thead class="thead-dark">';
            tab += "        <tr>";
            tab += "          <th>Norma</th>";
            tab += "          <th style='width:10%'>Parametro</th>";
            tab += "          <th>Unidad</th>";
            tab += "          <th>Resultado</th>";
            //   tab += '          <th>Concentracion</th>';
            //   tab += '          <th>Diagnostico</th>';
            tab += "          <th>Incertidumbre</th>";
            tab += "          <th>Liberado</th>";
            tab += "          <th>#</th>";
            tab += "          <th># Muestra</th>";
            tab += "        </tr>";
            tab += "    </thead>";
            tab += "    <tbody>";
            $.each(response.model, function (key, item) {
                tab += "<tr>";
                tab += "<td>" + item.Norma + "</td>";
                tab += "<td style='width:10%'>" + item.Parametro + "</td>";
                tab += "<td>" + item.Unidad + "</td>";
                tab += "<td>" + item.Resultado2 + "</td>";
                tab += "<td><input style='width:100%' id='incer"+item.Id_codigo+"' value='"+item.Incertidumbre+"'></td>";

                //   tab += '<td></td>';
                //   tab += '<td></td>';
                if (item.Resultado != "NULL") {
                    tab += "<td>Sin Liberar</td>";
                } else {
                    tab += "<td>Liberado</td>";
                }

                tab += "<td>" + item.Num_muestra + "</td>";
                tab += "<td>" + item.Codigo + "</td>";
                tab += "</tr>";
            });
            tab += "    </tbody>";
            tab += "</table>";
            tabla.innerHTML = tab;

            let table = $("#tablaParametro").DataTable({
                ordering: false,
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

function setDatosTablaParametro(folio, puntoMuestreo) {
    $.ajax({
        url: base_url + "/admin/informes/getInformacionPuntosMuestreo",
        type: "POST",
        data: {
            folio: folio,
            puntoMuestreo: puntoMuestreo,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        success: function (response) {
            setBase64(response.proceso.Firma_autorizo);
            const listaParametros = response.model;
            let template = ``;
            console.log(listaParametros);

            listaParametros.forEach((element) => {
                let limiteExcedido = verificarExcedido(
                    element.Resultado2,
                    element.Limite_cuantificacion
                );

                template += `
                    <tr>
                        <td></td>
                        <td>${element.Parametro}</td>
                        <td>${element.Unidad}</td>
                   <td>
                        ${
                            element.Parametro == "Turbiedad" && parseFloat(element.Resultado) > 10
                                ? ">10"
                                : (element.Id_parametro == 370 || element.Id_parametro == 372) &&
                                parseFloat(element.Resultado) > 70
                                ? ">70"
                                : element.Resultado2
                        }
                    </td>
                    <td>
                        <input style="width:50%" id="incer${element.Id_codigo}"  value="${element.Incertidumbre ? element.Incertidumbre : ''}">
                    </td>

                        ${
                            limiteExcedido == true
                                ? `<td style="background-color: red; color: white;">${element.Limite_cuantificacion}</td>`
                                : `<td>${element.Limite_cuantificacion}</td>`
                        }
                    <td><button  onclick="setIncertidumbre(${element.Id_codigo ?? ''})"  class="btn-success"><i class="fas fa-check"></i> </button></td>
                    </tr>
                `;
            });

            $("#datosTablaParametro").html(template);
        },
    });
}

function setIncertidumbre(id){
 $.ajax({
        url: base_url + "/admin/informes/setIncertidumbre",
        type: "POST",
        data: {
            id:id,
            incertidumbre: $("#incer"+id).val(),
            _token: $('input[name="_token"]').val(),
        },
        success: function (response) {
            // Generar las filas dinámicamente
            alert("Incertidumbre agregada: "+ $("#incer"+id).val())
        },
        error: function (xhr, status, error) {
            console.error("Error en la petición:", error);
        },
    });
}

function verificarExcedido(resultado, limite) {
    if (limite == "N/A" || !limite || !resultado) {
        return false;
    }
    let resultadoNumerico = parseFloat(resultado);
    if (!limite.includes("-")) {
        if (resultadoNumerico > parseFloat(limite)) {
            console.log(`resultado: ${resultadoNumerico} es mayor a ${limite}`);
            return true;
        } else {
            console.log(`resultado: ${resultadoNumerico} es menor a ${limite}`);
            return false;
        }
    } else {
        let rango = limite.split("-");
        if (
            resultadoNumerico < parseFloat(rango[0]) ||
            resultadoNumerico > parseFloat(rango[1])
        ) {
            console.log(
                `resultado: ${resultadoNumerico} es menor a ${rango[0]} o mayor a ${rango[1]}`
            );
            return true;
        } else {
            console.log(
                `resultado: ${resultadoNumerico} esta dentro del limite ${limite}`
            );
            return false;
        }
    }
}
