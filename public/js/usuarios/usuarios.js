console.log('usuarios');

/**
 * Cambiar Contraseña
 */
function cambiarPassword(id) {
    $('#id_usuario').val(id);
    $('#cambioPassword').modal();
}

/**
 *
 */
function guardarPassword() {
    let id_cliente = $('#id_usuario').val();
    let password = $('#passwordUsuarios').val();
    let _token = $('#_token').val();
    $.ajax({
        url: "../usuarios/cambiarPassword",
        type: "POST",
        data: {
            id_cliente: id_cliente,
            password: password
        },
        success: function (response) {
            console.log(response);
            // $('#usuarios').modal('hide')
            // swal({
            //     icon: "success",
            //     title: "Grupo Eliminado Correctamente!",
            // });
        }
    });
}

/**
 * Modificar Información del Usuario
 */
function modifcarInformacionUsuario() {

}
