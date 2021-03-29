// const { some } = require("lodash");

console.log('V.03');
// Abrir Modal
function abrirModal(opt) {
    let option = opt;
    console.log(typeof(option));
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
    console.log(typeof(id));
    if(id == 1){
        console.log('Formaulario Dos');
    $("#formularioUno").css("display", "");
    $("#formularioDos").css("display", "none");
    $("#formularioTres").css("display", "none");

    $("home-tab").css("display", "none");
    $("nuevo").css("display", "none");
    $("pro").css("display", "none");
    $("contact-tab").css("display", "none");
    }

    if(id == 2){
    console.log('Formaulario Dos');
    $("#formularioUno").css("display", "none");
    $("#formularioTres").css("display", "none");
    $("#formularioDos").css("display", "");

    $("home-tab").css("display", "none");
    $("nuevo").css("display", "none");
    $("pro").css("display", "none");
    $("contact-tab").css("display", "none");
    }
    if (id == 3) {
        console.log('Formulario Tres');
        $("#formularioUno").css("display", "none");
        $("#formularioDos").css("display", "none");
        $("#formularioTres").css("display", "");

    $("home-tab").css("display", "none");
    $("nuevo").css("display", "none");
    $("pro").css("display", "none");
    $("contact-tab").css("display", "none");

    }

    }
