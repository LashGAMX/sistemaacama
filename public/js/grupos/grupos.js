console.log('grupos');

var base_url = 'https://dev.sistemaacama.com.mx';

/**
 * Función para Abrir el Modal
 */
$("#crearGrupo").click(function () {
    accionGuardar();
    $("#myModal").modal();
    $('#TituloGuardar').val('');
    $('#comentarioGuardar').val('');
});
/**
 *  Función para crear un grupo
 */
function crearGrupo() {
    console.log(`Se Creara el Grupo`);
    let titulo = $('#TituloGuardar').val();
    let comentario = $('#comentarioGuardar').val();
    let _token = $('#_token').val();
    let status = 1;
    $.ajax({
        url: "grupos/guardar",
        type: "POST",
        data: {
            titulo: titulo,
            comentario: comentario,
            _token: _token
        },
        success: function (response) {
            console.log(response);
            $('#myModal').modal('hide')
            swal({
                icon: "success",
                title: "Grupo Creado Correctamente!",
            });
        }
    });
}

/**
 * Funcion para modificar la información de un grupo.
 * @param {*} id
 */
function modifcarGrupo(id) {
    console.log(`${id}`);
    let id_edit = id;
    $('#id_edit_form').val(id_edit);
    $.ajax({
        url: "grupos/obtenerInformacionGrupo",
        type: "POST",
        data: {
            id_edit: id_edit
        },
        success: function (response) {
            console.log(response);
            let titulo = $('#TituloGuardar').val(response.Titulo);
            let comentario = $('#comentarioGuardar').val(response.Comentario);
            accionActualizar();
            $("#myModal").modal();
        }
    });
}
/**
 * Funcion para actualizar
 */
function actualizarGrupo() {
    console.log(`actualizar`);
    let id_edit = $('#id_edit_form').val();
    let titulo = $('#TituloGuardar').val();
    let comentario = $('#comentarioGuardar').val();
    let _token = $('#_token').val();
    $.ajax({
        url: "grupos/actualizarInformacionGrupo",
        type: "POST",
        data: {
            titulo: titulo,
            comentario: comentario,
            id_edit: id_edit,
            _token: _token
        },
        success: function (response) {
            console.log(response);
            $('#myModal').modal('hide')
            swal({
                icon: "success",
                title: "Grupo Actualizado Correctamente!",
            });
        }
    });
}

/**
 * Funcion para configurar a los usuarios
 * @param {*} id
 */
function configuracionUsuarios(id) {
    let id_grupo = id;
    $('#id_grupo').val(id);
    $('#lista_usuarios').val();
    $.ajax({
        url: "grupos/obtenerTablaGruposUsuarios",
        type: "POST",
        data: {
            id_grupo: id_grupo,
        },
        success: function (response) {
            console.log(response);
            $("#tabla-grupos").html(response);
        }
    });
    $("#usuarios").modal();
}
/**
 * Funcion para agregar un usuario al grupo
 */
function agregarUsuario() {
    let usuario = $('#lista_usuarios').val();
    let grupo = $('#id_grupo').val();
    console.log(`${usuario}`);
    $.ajax({
        url: "grupos/agregarUsuario",
        type: "POST",
        data: {
            usuario: usuario,
            grupo: grupo
        },
        success: function (response) {
            console.log(response);
            $('#usuarios').modal('hide')
            switch (response) {
                case 100:
                    swal({
                        icon: "success",
                        title: "Se Añadio el Usuario al Grupo",
                    });
                    break;
                case 200:
                    swal({
                        icon: "success",
                        title: "El Usuario ya Estab Registrado en Otro Grupo y Ahora en Este",
                    });
                    break;
                case 300:
                    swal({
                        icon: "success",
                        title: "El Usuario ya Esta Registrado en Este Grupo",
                    });
                    break;
                default:
                    break;
            }


        }
    });
}


/**
 * Eliminar Usuario Grupo
 */
function eliminarUsuarioGrupo(id) {
    let id_usuario = id;
    let id_grupo = $('#id_grupo').val();
    $.ajax({
        url: "grupos/eliminarUsuarioGrupo",
        type: "POST",
        data: {
            id_grupo: id_grupo,
            id_usuario: id_usuario
        },
        success: function (response) {
            console.log(response);
            $('#usuarios').modal('hide')
            swal({
                icon: "success",
                title: "Grupo Eliminado Correctamente!",
            });
        }
    });
}

//****************************************************************************************/
//***************************************************************************************/
// FUNCIONES PARA VISTA  //
//***************************************************************************************/
/**
 * Ocultar Boton Guardar
 */
function accionActualizar() {
    $('#guardarGrupo').css("display", "none");
    $('#actualizarGrupo').css("display", "block");
}
/**
 * Ocultar Boton Actualizar
 */
function accionGuardar() {
    $('#guardarGrupo').css("display", "block");
    $('#actualizarGrupo').css("display", "none");
}
