var table;
// var selectedRow = false;
var idsol = 0;
var dato;
$(document).ready(function () {
    table = $("#tablaServicio").DataTable({
        ordering: false,
        paging: false,
        language: {
            lengthMenu: "# _MENU_ por pagina",
            zeroRecords: "No hay datos encontrados",
            info: "Pagina _PAGE_ de _PAGES_",
            infoEmpty: "No hay datos encontrados",
        },
    });
    $("#tablaServicio tbody").on("click", "tr", function () {
        if ($(this).hasClass("selected")) {
            $(this).removeClass("selected");
        } else {
            table.$("tr.selected").removeClass("selected");
            $(this).addClass("selected");
        }
    });

    $("#tablaServicio tr").on("click", function () {
        dato = $(this).find("td:first").html();
        idSol = dato;
    });

    $("#btnPagar").click(function () {
        setPagar();
    });
    $("#btnCredito").click(function () {
        setCredito();
    });
    $("#btnRetenido").click(function () {
        setRetenido();
    });
    $("#getDescargar").click(function () {
        getDescargar();
    });
});

function getDescargar() {
    window.open(base_url + "/admin/cobranza/getDescargar/" + idSol);
}
function setPagar() {
    $.ajax({
        url: base_url + "/admin/cobranza/setPago", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            id: idSol,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            if (parseInt(response.sw) == 0) {
                $("#tablaServicio tbody tr.selected").css(
                    "background-color",
                    "#ffffff"
                );
            } else {
                $("#tablaServicio tbody tr.selected").css(
                    "background-color",
                    "#dff0d8"
                );
            }
            alert(response.msg);
        },
    });
}

function setCredito() {
    $.ajax({
        url: base_url + "/admin/cobranza/setCredito", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            id: idSol,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            if (parseInt(response.sw) == 0) {
                $("#tablaServicio tbody tr.selected").css(
                    "background-color",
                    "#ffffff"
                );
            } else {
                $("#tablaServicio tbody tr.selected").css(
                    "background-color",
                    "#fbf18a"
                );
            }
            alert(response.msg);
        },
    });
}

function setRetenido() {
    $.ajax({
        url: base_url + "/admin/cobranza/setRetenido", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            id: idSol,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            if (parseInt(response.sw) == 0) {
                $("#tablaServicio tbody tr.selected").css(
                    "background-color",
                    "#ffffff"
                );
            } else {
                $("#tablaServicio tbody tr.selected").css(
                    "background-color",
                    "#fdaaa3"
                );
            }
            alert(response.msg);
        },
    });
}
