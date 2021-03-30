/**
 * Programador: Jose David Barrita Lopez
 * Fecha: 29 de Marzo del 2021
 * Modulo: Cotización
 */
$(function () {
    console.log("V..02075!");//You ar
    var guardarFormulario = document.getElementById("guardarFormulario");
    // var btn_primero = document.getElementById('btn_primero');
    // var bnt_dos_atras = document.getElementById('bnt_dos_atras');
    var bnt_tres = document.getElementById('bnt_tres');
    var bnt_dos = document.getElementById('bnt_dos');

    guardarFormulario.style.display = "none";
    // btn_primero.style.display = "none";
    bnt_tres.style.display = "none";
    // bnt_dos_atras.style.display = "none";
    bnt_dos.style.display = "none";
    var nombretabs = document.getElementById("home-tab");
    nombretabs.innerHTML = "Formulario de Cotización";
    formulario(1);
});
// Funcion para cargar componente
function obtenerParametros() {
    var demo2 = $('.demo2').bootstrapDualListbox({
        nonSelectedListLabel: 'No seleccionado',
        selectedListLabel: 'Seleccionado',
        preserveSelectionOnMove: 'Mover',
        moveOnSelect: true,
        nonSelectedFilter: 'Filtro'
    });
}

// Abrir Modal
function abrirModal(opt) {
    let option = opt;
    console.log(typeof (option));
    console.log('nuevo------------');
    console.log(option)
    $('#modalCotizacionPrincipal').modal('show');
    switch (option) {
        case 1:
            $("#tituloModal").html("Crear Cotización");
            break;
        case 2:
            $("#tituloModal").html("Modificar Cotización");
        default:
            break;
    }

}

function formulario(id) {
    console.log(id);
    console.log(typeof (id));
    if (id == 1) {
        console.log('Formaulario Dos');
        $("#formularioUno").css("display", "");
        $("#formularioDos").css("display", "none");
        $("#formularioTres").css("display", "none");

        $("home-tab").css("display", "none");
        $("nuevo").css("display", "none");
        $("pro").css("display", "none");
        $("contact-tab").css("display", "none");

        // Ver
        bnt_dos.style.display = "block"

        // No ver
        guardarFormulario.style.display = "none";
        bnt_tres.style.display = "none";
    }

    if (id == 2) {
        obtenerParametros();
        console.log('Formaulario Dos');
        $("#formularioUno").css("display", "none");
        $("#formularioTres").css("display", "none");
        $("#formularioDos").css("display", "");

        $("home-tab").css("display", "none");
        $("nuevo").css("display", "none");
        $("pro").css("display", "none");
        $("contact-tab").css("display", "none");

        //Ver
        bnt_tres.style.display = "block";
        // No ver
        guardarFormulario.style.display = "none";
        bnt_dos.style.display = "none"
    }
    if (id == 3) {

        $("#formularioUno").css("display", "none");
        $("#formularioDos").css("display", "none");
        $("#formularioTres").css("display", "");

        $("home-tab").css("display", "none");
        $("nuevo").css("display", "none");
        $("pro").css("display", "none");
        $("contact-tab").css("display", "none");

        //Ver
        guardarFormulario.style.display = "block";
        bnt_dos.style.display = "none";
        bnt_tres.style.display = "none";
    }
}

$(document).ready(function () {
    $('#formularioCotizacion').submit(function (e) {
        console.log('submot');
        e.preventDefault();
        var clienteManual = $('#clienteManual').val();
        var tipoServicio = $('#tipoServicio').val();
        var atencionA = $('#atencionA').val();
        var fechaCotizacion = $('#fechaCotizacion').val();
        var _token = $("#csrf").val();

        $.ajax({
            url: "cotizacion/save",
            type: "POST",
            data: {
                clienteManual: clienteManual,
                tipoServicio: tipoServicio,
                atencionA: atencionA,
                fechaCotizacion: fechaCotizacion,
                _token: _token

            },
            success: function (response) {
                if (response) {
                    console.log('nuevo-pro-2020');
                    formulario(id);
                }
            }

        });
    });
});
