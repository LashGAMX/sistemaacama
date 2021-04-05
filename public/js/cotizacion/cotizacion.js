/**
 * Programador: Jose David Barrita Lopez
 * Revisión: Luis Alberto Santiago Hernandez
 * Fecha: 29 de Marzo del 2021
 * Modulo: Cotización
 */
var clienteManualCopia = '',
    direccionCopia = '',
    atencionACopia = '',
    telefonoCopia = '';
var correoCopia = '',
    estadoCotizacionCopia = '',
    tipoServicioCopia = '',
    tipoDescargaCopia = '';
var normaFormularioUnoCopia = '',
    frecuenciaCopia = '',
    tipoMuestraCopia = '',
    promedioCopia = '';
var reporteCopia, condiccionesVentaCopia = '',fechaCotizacionCopia='';
//Codigo Jquery
/**
 * Logica de Botones & Como Mostrar los Formularios
 */
$(function () {
    console.log("V1...18!");
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
            console.log('Modo Modificar');
            var idModal = $(this).data("id");
            console.log(idModal);
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

        clienteManualCopia = $('#clienteManual').val();
        $('#obtenerNombreClienteCopy').val(clienteManualCopia);
        direccionCopia = $('#direccion').val();
        $('#direccionCopy').val(direccionCopia);
        atencionACopia = $('#atencionA').val();
        $('#obtenerAtencionCopy').val(atencionACopia);
        telefonoCopia = $('#telefono').val();
        $('#telefonoCopy').val(telefonoCopia);
        correoCopia = $('#correo').val();
        $('#correoCopy').val(correoCopia);
        estadoCotizacionCopia = $('#estadoCotizacion').val();
        $('#estadoCotizacionCopy').val(estadoCotizacionCopia);
        tipoServicioCopia = $('#tipoServicio').val();
        $('#servicioNombreCopy').val(tipoServicioCopia);
        tipoDescargaCopia = $('#tipoDescarga').val();
        $('#tipoDescargaCopy').val(tipoDescargaCopia);
        normaFormularioUnoCopia = $('#normaFormularioUno').val();
        $('#obtenerNormaCopy').val(normaFormularioUnoCopia);
        fechaCotizacionCopia = $('#fechaCotizacion').val();
        $('#fechaCopy').val(fechaCotizacionCopia);
        frecuenciaCopia = $('#frecuencia').val();
        $('#frecuenciaCopy').val(frecuenciaCopia);
        tipoMuestraCopia = $('#tipoMuestra').val();
        promedioCopia = $('#promedio').val();
        $('#tomasCopy').val(promedioCopia);
        reporteCopia = $('#reporte').val();
        condiccionesVentaCopia = $('#condiccionesVenta').val();
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
/**
 * Guardar Cotización
 */
$(document).ready(function () {
    $('#formularioCotizacion').submit(function (e) {
        console.log('submot');
        e.preventDefault();
        var clienteManual = $('#clienteManual').val();
        var tipoServicio = $('#tipoServicio').val();
        var atencionA = $('#atencionA').val();
        var fechaCotizacion = $('#fechaCotizacion').val();
        var telefono = $('#telefono').val();
        var correo = $('#correo').val();
        var estadoCotizacion = $('#estadoCotizacion').val();
        var tipoDescarga = $('#tipoDescarga').val();
        var clasifacionNorma = $('#clasifacionNorma').val();
        var normaFormularioUno = $('#normaFormularioUno').val();
        var frecuencia = $('#frecuencia').val();
        var tipoMuestra = $('#tipoMuestra').val();
        var promedio = $('#promedio').val();
        var puntosMuestreo = $('#puntosMuestreo').val();
        var reporte = $('#reporte').val();
        var codiccionesVenta = $('#codiccionesVenta').val();

        var tomasMuestreo = $('#tomasMuestreo').val();
        var viaticos = $('#viaticos').val();
        var paqueteria = $('#paqueteria').val();
        var gastosExtras = $('#gastosExtras').val();
        var numeroServicio = $('#numeroServicio').val();
        var kmExtra = $('#kmExtra').val();
        var precioKm = $('#precioKm').val();
        var precioKmExtra = $('#precioKmExtra').val();
        var observacionInterna = $('#observacionInterna').val();
        var observacionCotizacion = $('#observacionCotizacion').val();
        var tarjeta = $('#tarjeta').val();
        var tiempoEntrega = $('#tiempoEntrega').val();
        var valoresParametros = new Array(); //
        valoresParametros = $("#obtenerParametros").val();
        console.log(valoresParametros);
        var _token = $("#csrf").val();

        $.ajax({
            url: "cotizacion/save",
            type: "POST",
            data: {
                clienteManual: clienteManual,
                tipoServicio: tipoServicio,
                atencionA: atencionA,
                fechaCotizacion: fechaCotizacion,
                telefono: telefono,
                correo: correo,
                estadoCotizacion: estadoCotizacion,
                tipoDescarga: tipoDescarga,
                clasifacionNorma: clasifacionNorma,
                normaFormularioUno: normaFormularioUno,
                frecuencia: frecuencia,
                tipoMuestra: tipoMuestra,
                promedio: promedio,
                puntosMuestreo: puntosMuestreo,
                reporte: reporte,
                codiccionesVenta: codiccionesVenta,
                tomasMuestreo: tomasMuestreo,
                viaticos: viaticos,
                paqueteria: paqueteria,
                gastosExtras: gastosExtras,
                numeroServicio: numeroServicio,
                kmExtra: kmExtra,
                precioKm: precioKm,
                precioKmExtra: precioKmExtra,
                observacionInterna: observacionInterna,
                observacionCotizacion: observacionCotizacion,
                tarjeta: tarjeta,
                tiempoEntrega: tiempoEntrega,
                valoresParametros: valoresParametros,
                _token: _token
            },
            success: function (response) {
                if (response) {
                    console.log('nuevo-pro-2020');
                    // formulario(1);
                    window.location.assign("https://dev.sistemaacama.com.mx/admin/cotizacion");
                }
            }

        });
    });
});

/**
 * Obtener Parametros
 */
function cargarParametros() {
    console.log('David');
    var select = document.getElementById('parametrosPorClasifiacion');
    console.log(select.value);
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
                var result = '';
                result += '<select multiple="multiple" size="10" name="duallistbox_demo2" class="demo2" id="obtenerParametros">';
                result += response;
                result += '</select>'
                select.innerHTML = result;
                obtenerParametros();
            },
            error: function () {
                console.log(id_subnorma);
                console.log("error");
            }
        });
    })
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
            var result = '';
            result += '<select multiple="multiple" size="10" name="duallistbox_demo2" class="demo2" id="obtenerParametros">';
            result += response;
            result += '</select>'
            select.innerHTML = result;
            obtenerParametros();
        },
        error: function () {
            console.log(id_subnorma);
            console.log("error");
        }
    });
});
/**
 * Funcion para Formulario Select
 */
$("#normaFormularioUno").change(function () {
    var id_norma = $("#normaFormularioUno").val();
    let selectTwo = document.getElementById('clasificacionNorma');
    $.ajax({
        data: {
            id_norma: id_norma
        },
        url: 'cotizacion/obtenerClasificacion',
        type: 'POST',
        success: function (res) {
            var resulto = '';
            resulto += '<select name=""  class="form-control" id="clasifacionNorma">';
            resulto += res;
            resulto += '</select>';
            selectTwo.innerHTML = resulto;
            console.log(resulto);
        },
        error: function () {
            console.log(id_norma);
            console.log("error");
        }
    });
});
/**
 * Funcion para Formulario Select Norma Formulario Dos
 */
$("#normaSelectFormularioDos").change(function () {
    var id_norma = $("#normaSelectFormularioDos").val();
    console.log(id_norma);
    let selectThree = document.getElementById('clasificacionNormaFormularioDos');
    $.ajax({
        data: {
            id_norma: id_norma
        },
        url: 'cotizacion/obtenerClasificacion',
        type: 'POST',
        success: function (resq) {
            var resultoo = '';
            resultoo += '<select name=""  class="form-control" id="parametrosPorClasifiacion" onclick="cargarParametros()">';
            resultoo += resq;
            resultoo += '</select>';
            selectThree.innerHTML = resultoo;
            console.log(resultoo);
        },
        error: function () {
            console.log(id_norma);
            console.log("error");
        }
    });
});

/**
 * Función para Editar
 * @param {*} id
 */
function edit_columna(id) {
    console.log(`Bienvenido a Editar${id}`);


}

/**
 * Función para ver el Historico
 * @param {*} id
 */
function ver_historico(idCotizacion) {
    console.log(`Bienvenido a Historico${idCotizacion}`);
    let tablaHistorico = document.getElementById('historicoById');
    $.ajax({
        data: {
            idCotizacion: idCotizacion
        },
        url: 'cotizacion/obtenerHistorico',
        type: 'POST',
        success: function (resq) {
            var resulquery = '';
            resulquery += '<table id="tablaParametro" class="table table-sm  table-striped table-bordered">';
            resulquery += '    <thead class="thead-dark">';
            resulquery += '        <tr>';
            resulquery += '            <th style="width: 5%;">Id</th>';
            resulquery += '            <th>Cliente</th>';
            resulquery += '            <th>Servicio Folio</th>';
            resulquery += '            <th>Cotización Folio</th>';
            resulquery += '            <th>Empresa</th>';
            resulquery += '            <th>Servicio</th>';
            resulquery += '            <th>Fecha Cotización</th>';
            resulquery += '            <th>Fecha Control:</th>';
            resulquery += '            <th>Hora Control:</th>';
            resulquery += '            <th>Autor</th>';
            resulquery += '        </tr>';
            resulquery += '    </thead>';
            resulquery += '    <tbody>';
            resulquery += resq;
            resulquery += '</tbody>';
            resulquery += '</table>';
            tablaHistorico.innerHTML = resulquery;
            console.log(resulquery);
        },
        error: function () {
            console.log("error");
        }
    });
}

/**
 * Función para duplicar(DUPLICAR)
 * @param {*} id
 */
function edit_duplicacion(id) {
    console.log(`Bienvenido a Duplicar${id}`);
    swal({
            title: "¿Está segura o seguro de duplicar, al generarlo se creara un nuevo folio con la misma información?",
            text: "Las cotizaciones son importantes has un buen uso de la duplicación.!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    data: {
                        id: id
                    },
                    url: 'cotizacion/duplicarCotizacion',
                    type: 'POST',
                    success: function (respuesta) {
                        var json_data = JSON.parse(respuesta);
                        $.ajax({
                            url: "cotizacion/save",
                            type: "POST",
                            data: {
                                clienteManual: json_data.Cliente,
                                tipoServicio: json_data.Servicio,
                                atencionA: null,
                                fechaCotizacion: null,
                                telefono: json_data.Telefono,
                                correo: json_data.Correo,
                                estadoCotizacion: null,
                                tipoDescarga: json_data.Tipo_descarga,
                                clasifacionNorma: null,
                                normaFormularioUno: null,
                                frecuencia: json_data.frecuencia,
                                tipoMuestra: json_data.Tipo_muestra,
                                promedio: json_data.Promedio,
                                puntosMuestreo: null,
                                reporte: json_data.Reporte,
                                codiccionesVenta: codiccionesVenta,
                                tomasMuestreo: null,
                                viaticos: json_data.Viaticos,
                                paqueteria: null,
                                gastosExtras: null,
                                numeroServicio: null,
                                kmExtra: null,
                                precioKm: null,
                                precioKmExtra: json_data.precioKmExtra,
                                observacionInterna: json_data.observacionInterna,
                                observacionCotizacion: json_data.observacionCotizacion,
                                tarjeta: json_data.tarjeta,
                                tiempoEntrega: json_data.tiempoEntrega
                            },
                            success: function (response) {
                                if (response) {
                                    console.log('nuevo-pro-2020');

                                }
                            }
                        });
                        swal("Cotizacion! Generada Correctamente!", {
                            icon: "success",
                        });
                    },
                    error: function () {
                        console.log("error");
                        swal("Un Error!");
                    }
                });

            } else {
                swal("El proceso no fue realizado!");
            }
        });
}
