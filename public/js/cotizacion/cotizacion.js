console.log('V.02');

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
/**
 *
 */
function formularioUno() {

}
/**
 *
 */
function formularioDos() {

}
/**
 *
 */
function formularioTres() {

}
function formulario(id) {
    console.log(id);
    console.log(typeof(id));
    if(id == 1){
        console.log('Formaulario Dos');
    $("#formularioUno").css("display", "");
    $("#formularioDos").css("display", "none");
    $("#formularioTres").css("display", "none");
    }

    if(id == 2){
    console.log('Formaulario Dos');
    $("#formularioUno").css("display", "none");
    $("#formularioTres").css("display", "none");
    $("#formularioDos").css("display", "");
    }

    if (id == 3) {
        console.log('Formulario Tres');
        $("#formularioUno").css("display", "none");
        $("#formularioDos").css("display", "none");
        $("#formularioTres").css("display", "");
    }
}
