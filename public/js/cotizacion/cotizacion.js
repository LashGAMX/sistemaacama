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

function formularioUno() {
    console.log('Formaulario Dos');
    $("#formularioUno").css("display", "");
    $("#formularioDos").css("display", "none");
    $("#formularioTres").css("display", "none");
}

function formularioDos() {
    console.log('Formaulario Dos');
    $("#formularioUno").css("display", "none");
    $("#formularioTres").css("display", "none");
    $("#formularioDos").css("display", "");
}

function formularioTres() {
    console.log('Formulario Tres');
    $("#formularioUno").css("display", "none");
    $("#formularioDos").css("display", "none");
    $("#formularioTres").css("display", "");
}
