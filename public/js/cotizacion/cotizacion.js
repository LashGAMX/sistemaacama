/**
 * Programador: Jose David Barrita Lopez
 * Revisión: Luis Alberto Santiago Hernandez
 * Fecha: 29 de Marzo del 2021
 * Modulo: Cotización
 */
$(function () {
    console.log("V1.303030!");
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

    if (id == 1) {

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
        obtenerDatos();
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
                    formulario(1);
                }
            }

        });
    });
});
/**
 * Obtener Datos
 */
function obtenerDatos() {
    console.log('a');
    var clienteManual = $('clienteManual').val();
    console.log(clienteManual);
    var dirrecion = $('direcion').val();
    var atencionA = $('atencionA').val();
    console.log(atencionA);
    var telefono = $('telefono').val();
    var correo = $('correo').val();
    $('obtenerNombreClienet').val(clienteManual);
    $('obtenerAtencion').val(atencionA);
}

function cargarParametros() {
    console.log('David');

    var select = document.getElementById('parametrosPorClasifiacion');
    console.log(select.value);

}

let datos = [];

function addTodoItem() {
    var todoItem = $("#new-todo-item").val();
    datos.push(todoItem);
    console.log(datos);
    $("#todo-list").append("<li><input type='checkbox'" +
        " name='todo-item-done'" +
        " class='todo-item-done'" +
        " value='" + todoItem + "' /> " +
        todoItem +
        " <button class='btn btn-danger btn-sm mt-1'>" +
        "X</button></li>");
    $("#new-todo-item").val("");
}

function deleteTodoItem(e, item) {
    console.log('Bienvenidos');
    console.log(item);
    console.log(e);
    e.preventDefault();
    $(item).parent().fadeOut('slow', function () {
        $(item).parent().remove();
    });
}

$(function () {
    $("#add-todo-item").on('click', function (e) {
        e.preventDefault();
        addTodoItem()
    });
    $("#todo-list").on('click', '.todo-item-delete', function (e) {
        var item = this;
        console.log(item);
        deleteTodoItem(e, item)
    })
});

/**
 * Función para Saber si es Un Cliente Intermediario.
 **/

$('#intermediario').change(function () {
    var intermediario = $(this).val();
    if (intermediario) {
        // Se oculta los datos de cliente manual
        $("#clienteManual").css("display", "none");
        $("#direccion").css("display", "none");
        $("#nombreCliente").css("display", "none");
        $("#nombreDireccion").css("display", "none")
    }
});

/**
 * Función para Saber si el cliente ya esta registrado.
 **/
$('#clienteObtenidoSelect').change(function () {
    var clienteObtenidoSelect = $(this).val();
    if (clienteObtenidoSelect) {
        // Se oculta los datos de cliente manual
        $("#clienteManual").css("display", "none");
        $("#direccion").css("display", "none");
        $("#nombreCliente").css("display", "none");
        $("#nombreDireccion").css("display", "none")
    }
});
/**
 * Parametros por Clasificación
 */
$("#parametrosPorClasifiacion").change(function () {
    var id_subnorma = $("#parametrosPorClasifiacion").val();
    let select = document.getElementById('selectParametros');
    $.ajax({
        data: {
            id_subnorma: id_subnorma
        },
        url: 'cotizacion/obtenerParametros',
        type: 'POST',
        success: function (response) {
            //$("#obtenerParametros").html(response);
            var result =  '';
            result += '<select multiple="multiple" size="10" name="duallistbox_demo2" class="demo2" id="obtenerParametros">';
            result += response;
            result += '</select>'
            console.log('.........');
            console.log(response);
            console.log('.........');
            console.log(result);
            select.innerHTML = result;
        },
        error: function () {
            console.log(id_subnorma);
            console.log("error");
        }
    });
})
