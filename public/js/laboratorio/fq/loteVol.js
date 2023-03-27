var area = "fq";

//var quill;

//Opciones del editor de texto Quill
/*var options = {
    placeholder: 'Introduce procedimiento/validación',
    theme: 'snow'
};*/

$(document).ready(function () {

    $('#btnPendiente').click(function () {
        getPendientes()
    });
    $('#tipoFormula').select2();

$("#btnLimpiarVal").click(function () {
    $("#titulado1D").val("")
    $("#titulado2D").val("")
    $("#titulado3D").val("")
    $("#blancoResD").val("")
    $("#molaridadResD").val("")
console.log('Limpiar');
});

});
$('#btnDatosLote').click(function () {
    switch ($("#tipoFormula").val()) {
        case '33': // CLORO RESIDUAL LIBRE
        case '64':
            $("#secctionCloro").show();
            $("#secctionDqo").hide();
            $("#secctionNitrogeno").hide();
            break;
        case '6': // DQO
            $("#secctionDqo").show();
            $("#secctionCloro").hide();
            $("#secctionNitrogeno").hide();
            break;
        case '11': //Nitrogeno Total
            $("#secctionNitrogeno").show();
            $("#secctionCloro").hide();
            $("#secctionDqo").hide();
            break;
        case '9': //Nitrogeno Amoniacal
        case '108':
            $("#secctionNitrogeno").show();
            $("#secctionCloro").hide();
            $("#secctionDqo").hide();
            break;
        case '10': //Nitrogeno Organico
            $("#secctionNitrogeno").show();
            $("#secctionCloro").hide();
            $("#secctionDqo").hide();
            break;
        default:
            break;
    }
});
$('#btnEjecutarVal').click(function () {
    let prom = 0;
    let res = 0;
    let titulado1 = 0;
    let titulado2 = 0;
    let titulado3 = 0;
    let gramos = 0;
    let factorN = 0;
    let pm = 0;
    switch ($("#tipoFormula").val()) {
        case '33': // CLORO RESIDUAL LIBRE
        case '64':
            $("#blancoResClo").val($("#blancoCloro").val())
            titulado1 = $("#tituladoClo1").val();
            titulado2 = $("#tituladoClo2").val();
            titulado3 = $("#tituladoClo3").val();
            let trazable = $("#trazableClo").val();
            let normalidad = $("#normalidadClo").val();

            prom = (parseFloat(titulado1) + parseFloat(titulado2) + parseFloat(titulado3)) / 3;
            res = (parseFloat(trazable) * parseFloat(normalidad)) / prom;
            $("#normalidadResCloro").val(res.toFixed(4));
            break;
        case '6': // DQO
            $("#blancoResD").val($("#blancoValD").val())
            titulado1 = $("#titulado1D").val();
            titulado2 = $("#titulado2D").val();
            titulado3 = $("#titulado3D").val();
            let volk2 = $("#volk2D").val();
            let concentracion = $("#concentracionD").val();
            let factor = $("#factorD").val();

            prom = (parseFloat(titulado1) + parseFloat(titulado2) + parseFloat(titulado3)) / 3;
            res = (parseFloat(volk2) * parseFloat(concentracion) * parseFloat(factor)) / prom;
            $("#molaridadResD").val(res.toFixed(3));
            // console.log(res)
            break;
        case '11': // Nitrogeno Total
            $("#blancoResN").val($("#blancoValN").val())
            titulado1 = $("#titulado1N").val();
            titulado2 = $("#titulado2N").val();
            titulado3 = $("#titulado3N").val();
            gramos = $("#gramosN").val();
            factorN = $("#factorN").val();
            pm = $("#PmN").val();

            prom = (parseFloat(titulado1) + parseFloat(titulado2) + parseFloat(titulado3)) / 3;
            res = (parseFloat(gramos) / (parseFloat(pm) * prom)) * factorN;
            $("#molaridadResN").val(res.toFixed(3));
            // console.log(res)
            break;
        case '9':
        case '108': // Nitrogeno amonicacal 
            $("#blancoResN").val($("#blancoValN").val())
            titulado1 = $("#titulado1N").val();
            titulado2 = $("#titulado2N").val();
            titulado3 = $("#titulado3N").val();
            gramos = $("#gramosN").val();
            factorN = $("#factorN").val();
            pm = $("#PmN").val();

            prom = (parseFloat(titulado1) + parseFloat(titulado2) + parseFloat(titulado3)) / 3;
            res = (parseFloat(gramos) / (parseFloat(pm) * prom)) * factorN;
            $("#molaridadResN").val(res.toFixed(3));
             console.log(res)
            break;
        case '10': // Nitrogeno Organico
            $("#blancoResN").val($("#blancoValN").val())
            titulado1 = $("#titulado1N").val();
            titulado2 = $("#titulado2N").val();
            titulado3 = $("#titulado3N").val();
            gramos = $("#gramosN").val();
            factorN = $("#factorN").val();
            pm = $("#PmN").val();

            prom = (parseFloat(titulado1) + parseFloat(titulado2) + parseFloat(titulado3)) / 3;
            res = (parseFloat(gramos) / (parseFloat(pm) * prom)) * factorN;
            $("#molaridadResN").val(res.toFixed(3));
            // console.log(res)
            break;
        default:
            break;
    }
});
$('#btnGuardarVal').click(function () {
    switch ($("#tipoFormula").val()) {
        case '295': // CLORO RESIDUAL LIBRE
        case '64':
            $.ajax({
                type: 'POST',
                url: base_url + "/admin/laboratorio/" + area + "/guardarValidacionVol",
                data: {
                    caso: 1,
                    idParametro: $("#tipoFormula").val(),
                    blanco: $("#blancoResClo").val(),
                    idLote: $("#idLoteHeader").val(),
                    titulado1: $("#tituladoClo1").val(),
                    titulado2: $("#tituladoClo2").val(),
                    titulado3: $("#tituladoClo3").val(),
                    trazable: $("#trazableClo").val(),
                    normalidad: $("#normalidadClo").val(),
                    resultado: $("#normalidadResCloro").val(),
                    _token: $('input[name="_token"]').val(),
                },
                dataType: "json",
                async: false,
                success: function (response) {
                    console.log(response);
                }
            });
            break;
        case '6': // DQO
            $.ajax({
                type: 'POST',
                url: base_url + "/admin/laboratorio/" + area + "/guardarValidacionVol",
                data: {
                    caso: 2,
                    idParametro: $("#tipoFormula").val(),
                    blanco: $("#blancoResD").val(),
                    idLote: $("#idLoteHeader").val(),
                    volk2D: $("#volk2D").val(),
                    concentracion: $("#concentracionD").val(),
                    factor: $("#factorD").val(),
                    titulado1: $("#titulado1D").val(),
                    titulado2: $("#titulado2D").val(),
                    titulado3: $("#titulado3D").val(),
                    resultado: $("#molaridadResD").val(),
                    _token: $('input[name="_token"]').val(),
                },
                dataType: "json",
                async: false,
                success: function (response) {
                    console.log(response);
                }
            });
            break;
        case '11': // NITROGENO TOTAL
            $.ajax({
                type: 'POST',
                url: base_url + "/admin/laboratorio/" + area + "/guardarValidacionVol",
                data: {
                    caso: 3,
                    idParametro: $("#tipoFormula").val(),
                    blanco: $("#blancoResN").val(),
                    idLote: $("#idLoteHeader").val(),
                    gramos: $("#gramosN").val(),
                    factor: $("#factorN").val(),
                    titulado1: $("#titulado1N").val(),
                    titulado2: $("#titulado2N").val(),
                    titulado3: $("#titulado3N").val(),
                    pm: $("#PmN").val(),
                    resultado: $("#molaridadResN").val(),
                    _token: $('input[name="_token"]').val(),
                },
                dataType: "json",
                async: false,
                success: function (response) {
                    console.log(response);
                }
            });
            break;
        case '10': // NITROGENO TOTAL
            $.ajax({
                type: 'POST',
                url: base_url + "/admin/laboratorio/" + area + "/guardarValidacionVol",
                data: {
                    caso: 3,
                    idParametro: $("#tipoFormula").val(),
                    blanco: $("#blancoResN").val(),
                    idLote: $("#idLoteHeader").val(),
                    gramos: $("#gramosN").val(),
                    factor: $("#factorN").val(),
                    titulado1: $("#titulado1N").val(),
                    titulado2: $("#titulado2N").val(),
                    titulado3: $("#titulado3N").val(),
                    pm: $("#PmN").val(),
                    resultado: $("#molaridadResN").val(),
                    _token: $('input[name="_token"]').val(),
                },
                dataType: "json",
                async: false,
                success: function (response) {
                    console.log(response);
                }
            });
            break;
        case '9': // NITROGENO TOTAL
            $.ajax({
                type: 'POST',
                url: base_url + "/admin/laboratorio/" + area + "/guardarValidacionVol",
                data: {
                    caso: 3,
                    idParametro: $("#tipoFormula").val(),
                    blanco: $("#blancoResN").val(),
                    idLote: $("#idLoteHeader").val(),
                    gramos: $("#gramosN").val(),
                    factor: $("#factorN").val(),
                    titulado1: $("#titulado1N").val(),
                    titulado2: $("#titulado2N").val(),
                    titulado3: $("#titulado3N").val(),
                    pm: $("#PmN").val(),
                    resultado: $("#molaridadResN").val(),
                    _token: $('input[name="_token"]').val(),
                },
                dataType: "json",
                async: false,
                success: function (response) {
                    console.log(response);
                }
            });
            break;
            case '108': // NITROGENO amoniacal
            $.ajax({
                type: 'POST',
                url: base_url + "/admin/laboratorio/" + area + "/guardarValidacionVol",
                data: {
                    caso: 3,
                    idParametro: $("#tipoFormula").val(),
                    blanco: $("#blancoResN").val(),
                    idLote: $("#idLoteHeader").val(),
                    gramos: $("#gramosN").val(),
                    factor: $("#factorN").val(),
                    titulado1: $("#titulado1N").val(),
                    titulado2: $("#titulado2N").val(),
                    titulado3: $("#titulado3N").val(),
                    pm: $("#PmN").val(),
                    resultado: $("#molaridadResN").val(),
                    _token: $('input[name="_token"]').val(),
                },
                dataType: "json",
                async: false,
                success: function (response) {
                    console.log(response);
                }
            });
            break;
        default:
            break;
    }
});

$('#btnGuardarTipoDqo').click(function () {
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/" + area + "/setTipoDqo",
        data: {
            idLote: $("#idLoteHeader").val(),
            tipo: $("#tipoDqo").val(),
            tecnica: $('#tecnicaDqo').val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            swal("Registro!", "Lote creado correctamente!", "success");
            $('#modalCrearLote').modal('hide')
        }
    });
});

function habilitarTabla(id1, id2) {
    $("#" + id1).show();
    $("#" + id2).hide();
}
function createLote() {
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/" + area + "/createLote",
        data: {
            tipo: $("#tipoFormula").val(),
            fecha: $("#fechaLote").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            swal("Registro!", "Lote creado correctamente!", "success");
            $('#modalCrearLote').modal('hide')
        }
    });
}

function buscarLote() {
    let tabla = document.getElementById('divTable');
    let tab = '';
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/" + area + "/buscarLote",
        data: {
            tipo: $("#tipoFormula").val(),
            fecha: $("#fecha").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            tab += '<table id="tablaLote" class="table table-sm">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>#</th>';
            tab += '          <th>Tipo formula</th>';
            tab += '          <th>Fecha lote</th> ';
            tab += '          <th>Fecha creacion</th> ';
            tab += '          <th>Opc</th> ';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            if (response.sw == true) {
                $.each(response.model, function (key, item) {
                    tab += '<tr>';
                    tab += '<td>' + item.Id_lote + '</td>';
                    tab += '<td>' + item.Parametro + ' (' + item.Tipo_formula + ')</td>';
                    tab += '<td>' + item.Fecha + '</td>';
                    tab += '<td>' + item.created_at + '</td>';
                    tab += '<td><button type="button" id="btnAsignar" onclick="setAsignar(' + item.Id_lote + ')"  class="btn btn-primary">Agregar</button></td>';
                    tab += '</tr>';
                });
            } else {
                tab += '<h5 style="color:red;">No hay datos</h5>';
            }
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;
        }
    });
}

function setAsignar(id) {
    window.location = base_url + "/admin/laboratorio/" + area + "/asgnarMuestraLoteVol/" + id;
}

//Adaptando para FQ
function getDatalote() {
    let tabla = document.getElementById('divTableFormulaGlobal');
    let tab = '';
    let summer = document.getElementById("divSummer");
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/" + area + "/getDataloteVol",
        data: {
            idLote: $("#idLoteHeader").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.group(response);

            //calentamiento de matraces
            if (response.dataGrasas[0] !== null) {
                for (let i = 0; i < 3; i++) {
                    $("#calLote" + (i + 1)).val(response.dataGrasas[0][i].Id_lote);
                    $("#calMasa" + (i + 1)).val(response.dataGrasas[0][i].Masa_constante);
                    $("#calTemp" + (i + 1)).val(response.dataGrasas[0][i].Temperatura);
                    $("#calEntrada" + (i + 1)).val(response.dataGrasas[0][i].Entrada);
                    $("#calSalida" + (i + 1)).val(response.dataGrasas[0][i].Salida);
                }
            } else {
                for (let i = 0; i < 3; i++) {
                    $("#calLote" + (i + 1)).val('');
                    $("#calMasa" + (i + 1)).val('');
                    $("#calTemp" + (i + 1)).val('');
                    $("#calEntrada" + (i + 1)).val('');
                    $("#calSalida" + (i + 1)).val('');
                }
            }

            //enfriado de matraces
            if (response.dataGrasas[1] !== null) {
                for (let i = 0; i < 3; i++) {
                    $("#enfLote" + (i + 1)).val(response.dataGrasas[1][i].Id_lote);
                    $("#enfMasa" + (i + 1)).val(response.dataGrasas[1][i].Masa_constante);
                    $("#enfEntrada" + (i + 1)).val(response.dataGrasas[1][i].Entrada);
                    $("#enfSalida" + (i + 1)).val(response.dataGrasas[1][i].Salida);
                    $("#enfPesado" + (i + 1)).val(response.dataGrasas[1][i].Pesado_matraz);
                }
            } else {
                for (let i = 0; i < 3; i++) {
                    $("#enfLote" + (i + 1)).val('');
                    $("#enfMasa" + (i + 1)).val('');
                    $("#enfEntrada" + (i + 1)).val('');
                    $("#enfSalida" + (i + 1)).val('');
                    $("#enfPesado" + (i + 1)).val('');
                }
            }

            //secado de cartuchos
            if (response.dataGrasas[2] !== null) {
                $("#secadoLote1").val(response.dataGrasas[2].Id_lote);
                $("#secadoTemp1").val(response.dataGrasas[2].Temperatura);
                $("#secadoEntrada1").val(response.dataGrasas[2].Entrada);
                $("#secadoSalida1").val(response.dataGrasas[2].Salida);
            } else {
                $("#secadoLote1").val('');
                $("#secadoTemp1").val('');
                $("#secadoEntrada1").val('');
                $("#secadoSalida1").val('');
            }

            //tiempo de reflujo
            if (response.dataGrasas[3] !== null) {
                $("#tiempoLote1").val(response.dataGrasas[3].Id_lote);
                $("#tiempoEntrada1").val(response.dataGrasas[3].Entrada);
                $("#tiempoSalida1").val(response.dataGrasas[3].Salida);
            } else {
                $("#tiempoLote1").val('');
                $("#tiempoEntrada1").val('');
                $("#tiempoSalida1").val('');
            }

            //enfriado de matraces
            if (response.dataGrasas[4] !== null) {
                $("#enfriadoLote1").val(response.dataGrasas[4].Id_lote);
                $("#enfriadoEntrada1").val(response.dataGrasas[4].Entrada);
                $("#enfriadoSalida1").val(response.dataGrasas[4].Salida);
            } else {
                $("#enfriadoLote1").val('');
                $("#enfriadoEntrada1").val('');
                $("#enfriadoSalida1").val('');
            }

            //------------------------Coliformes

            if ((response.dataColi[0] !== null) && (response.dataColi[1] !== null) && (response.dataColi[2] !== null)) {
                //Formatea la fecha a un formato admitido por el input datetime
                let fecha = response.dataColi[0].Sembrado;
                let fechaIngresada = moment(fecha, 'YYYY-MM-DDTHH:mm');
                let fechaFormateada = moment(fechaIngresada).format('yyyy-MM-DDTHH:mm');

                $("#sembrado_loteId").val(response.dataColi[0].Id_lote);
                $("#sembrado_sembrado").val(fechaFormateada);
                $("#sembrado_fechaResiembra").val(response.dataColi[0].Fecha_resiembra);
                $("#sembrado_tuboN").val(response.dataColi[0].Tubo_n);
                $("#sembrado_bitacora").val(response.dataColi[0].Bitacora);

                //--------------------------
                fecha = response.dataColi[1].Preparacion;
                fechaIngresada = moment(fecha, 'YYYY-MM-DDTHH:mm');
                fechaFormateada = moment(fechaIngresada).format('yyyy-MM-DDTHH:mm');
                $('#pruebaPresuntiva_preparacion').val(fechaFormateada);

                fecha = response.dataColi[1].Lectura;
                fechaIngresada = moment(fecha, 'YYYY-MM-DDTHH:mm');
                fechaFormateada = moment(fechaIngresada).format('yyyy-MM-DDTHH:mm');
                $('#pruebaPresuntiva_lectura').val(fechaFormateada);

                //--------------------------
                $('#pruebaConfirmativa_medio').val(response.dataColi[2].Medio);

                fecha = response.dataColi[2].Preparacion;
                fechaIngresada = moment(fecha, 'YYYY-MM-DDTHH:mm');
                fechaFormateada = moment(fechaIngresada).format('yyyy-MM-DDTHH:mm');
                $('#pruebaConfirmativa_preparacion').val(fechaFormateada);

                fecha = response.dataColi[2].Lectura;
                fechaIngresada = moment(fecha, 'YYYY-MM-DDTHH:mm');
                fechaFormateada = moment(fechaIngresada).format('yyyy-MM-DDTHH:mm');
                $('#pruebaConfirmativa_lectura').val(fechaFormateada);

            } else {
                $("#sembrado_loteId").val('');
                $("#sembrado_sembrado").val('');
                $("#sembrado_fechaResiembra").val('');
                $("#sembrado_tuboN").val('');
                $("#sembrado_bitacora").val('');

                $('#pruebaPresuntiva_preparacion').val('');
                $('#pruebaPresuntiva_lectura').val('');

                $('#pruebaConfirmativa_medio').val('');
                $('#pruebaConfirmativa_preparacion').val('');
                $('#pruebaConfirmativa_lectura').val('');
            }

            //------------DQO------------
            if (response.dataDqo !== null) {
                $("#ebullicion_loteId").val(response.dataDqo.Id_lote);

                /* let fecha = response.dataDqo.Inicio;
                let fechaIngresada = moment(fecha, 'DD-MM-YYYY');
                let fechaFormateada = moment(fechaIngresada).format('yyyy-MM-DD'); */
                $("#ebullicion_inicio").val(response.dataDqo.Inicio);

                /* fecha = response.dataDqo.Fin;
                fechaIngresada = moment(fecha, 'DD-MM-YYYY');
                fechaFormateada = moment(fechaIngresada).format('yyyy-MM-DD'); */
                $("#ebullicion_fin").val(response.dataDqo.Fin);

                $("#ebullicion_invlab").val(response.dataDqo.Invlab);
            } else {
                $("#ebullicion_loteId").val('');
                $("#ebullicion_inicio").val('');
                $("#ebullicion_fin").val('');
                $("#ebullicion_invlab").val('');
            }
            //-----------------------------------------

            console.log("actualizado");

            if (response.reporte !== null) {
                summer.innerHTML = '<div id="summernote">' + response.reporte.Texto + '</div>';
                $('#summernote').summernote({
                    placeholder: '',
                    tabsize: 2,
                    height: 100,

                });
            } else {
                $.ajax({
                    type: "POST",
                    url: base_url + "/admin/laboratorio/" + area + "/getPlantillaPredVol",
                    data: {
                        idLote: $("#idLoteHeader").val(),
                        _token: $('input[name="_token"]').val(),
                    },
                    dataType: "json",
                    async: false,
                    success: function (response) {
                        //console.log(response);                        
                        summer.innerHTML = '<div id="summernote">' + response.Texto + '</div>';
                        $('#summernote').summernote({
                            placeholder: '',
                            tabsize: 2,
                            height: 100,

                        });
                    }
                });
            }

            switch ($("#tipoFormula").val()) {
                case '33': // CLORO RESIDUAL LIBRE
                case '64':
                    $("#blancoResClo").val(response.valoracion.Blanco);
                    $("#blancoCloro").val(response.valoracion.Blanco);
                    $("#tituladoClo1").val(response.valoracion.Ml_titulado1);
                    $("#tituladoClo2").val(response.valoracion.Ml_titulado2);
                    $("#tituladoClo3").val(response.valoracion.Ml_titulado3);
                    $("#trazableClo").val(response.valoracion.Ml_trazable);
                    $("#normalidadClo").val(response.valoracion.Normalidad);
                    $("#normalidadResCloro").val(response.valoracion.Resultado);
                    break;
                case '6': // DQO
                    $("#blancoResD").val(response.valoracion.Blanco);
                    $("#blancoValD").val(response.valoracion.Blanco);
                    $("#volk2D").val(response.valoracion.Vol_k2);
                    $("#concentracionD").val(response.valoracion.Concentracion);
                    $("#factorD").val(response.valoracion.Factor);
                    $("#titulado1D").val(response.valoracion.Vol_titulado1);
                    $("#titulado2D").val(response.valoracion.Vol_titulado2);
                    $("#titulado3D").val(response.valoracion.Vol_titulado3);
                    $("#molaridadResD").val(response.valoracion.Resultado);
                    break;
                case '9' || '10' || '11' || '108': // NITROGENO TOTAL
                    $("#blancoResN").val(response.valoracion.Blanco);
                    $("#blancoValN").val(response.valoracion.Blanco);
                    $("#gramosN").val(response.valoracion.Gramos);
                    $("#factorN").val(response.valoracion.Factor_conversion);
                    $("#titulado1N").val(response.valoracion.Titulo1);
                    $("#titulado2N").val(response.valoracion.Titulo2);
                    $("#titulado3N").val(response.valoracion.Titulo3);
                    $("#PmN").val(response.valoracion.Pm);
                    $("#molaridadResN").val(response.valoracion.Resultado);
                    break;
                default:
                    break;
            }

        }
    });
}


function isSelectedProcedimiento(procedimientoTab) {
    let valorProcedimientoTab = 'https://dev.sistemaacama.com.mx/admin/laboratorio/lote#procedimiento';
    let pestañaProcedimiento = document.getElementById(procedimientoTab);
    let btnActualizar = document.getElementById('btnRefresh');
    let annex = '';
    let evento = "(onclick='busquedaPlantilla('idLoteHeader');')";

    if (pestañaProcedimiento == valorProcedimientoTab) {
        annex += '<button type="button" class="btn btn-primary" evento.value><i class="fas fa-sync-alt"></i></button>';
    } else {
        annex = '';
    }

    btnActualizar.innerHTML = annex;
}

//Método que guarda el texto ingresado en el editor de texto Quill en la BD
function guardarTexto(idLote) {
    let lote = document.getElementById(idLote).value;
    let texto = document.getElementById("summernote");
    let summer = document.getElementById("divSummer");

    console.log("Antes de ajax");

    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/" + area + "/lote/procedimiento",
        data: {
            texto: $("#summernote").summernote('code'),
            lote: lote,
            idArea: 5
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log("REGISTRO EXITOSO");
            //console.log(response);
            summer.innerHTML = '<div id="summernote">' + response.texto.Texto + '</div>';
            $('#summernote').summernote({
                placeholder: '',
                tabsize: 2,
                height: 100,
            });
        }
    });
}

//Función que guarda todos los input de la vista Lote > Modal > [Grasas, Coliformes, DBO, DQO, Metales]
$('#guardarTodo').click(function () {
    //console.log("Valor de IDLote: " + $('#idLoteHeader').val());

    //Calentamiento de matraces
    let calentamiento = new Array();

    for (let i = 0; i < 3; i++) {
        row = new Array();

        row.push($("#calLote" + (i + 1)).val());
        row.push($("#calMasa" + (i + 1)).val());
        row.push($("#calTemp" + (i + 1)).val());
        row.push($("#calEntrada" + (i + 1)).val());
        row.push($("#calSalida" + (i + 1)).val());
        calentamiento.push(row);
    }

    console.log("Array calentamiento: " + calentamiento);


    //Enfriado de matraces
    let enfriado = new Array();

    for (let i = 0; i < 3; i++) {
        row = new Array();
        row.push($("#enfLote" + (i + 1)).val());
        row.push($("#enfMasa" + (i + 1)).val());
        row.push($("#enfEntrada" + (i + 1)).val());
        row.push($("#enfSalida" + (i + 1)).val());
        row.push($("#enfPesado" + (i + 1)).val());
        enfriado.push(row);
    }
    console.log("Array enfriado: " + enfriado);

    console.log("secadoLote: " + $("#secadoLote1").val());
    console.log("secadoTemp: " + $("#secadoTemp1").val());
    console.log("secadoEntrada: " + $("#secadoEntrada1").val());
    console.log("secadoSalida: " + $("#secadoSalida1").val());

    console.log("tiempoLote: " + $("#tiempoLote1").val());
    console.log("tiempoEntrada: " + $("#tiempoEntrada1").val());
    console.log("tiempoSalida: " + $("#tiempoSalida1").val());

    console.log("enfriadoLote: " + $("#enfriadoLote1").val());
    console.log("enfriadoEntrada: " + $("#enfriadoEntrada1").val());
    console.log("enfriadoSalida: " + $("#enfriadoSalida1").val());

    //Guardado de datos
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/lote/guardarDatos",
        data: {
            idLote: $('#idLoteHeader').val(),

            //-----------------Grasas----------------------
            grasas_calentamiento: calentamiento,

            grasas_enfriado: enfriado,

            grasas_secadoLote: $("#secadoLote1").val(),
            grasas_secadoTemp: $("#secadoTemp1").val(),
            grasas_secadoEntrada: $("#secadoEntrada1").val(),
            grasas_secadoSalida: $("#secadoSalida1").val(),

            grasas_tiempoLote: $("#tiempoLote1").val(),
            grasas_tiempoEntrada: $("#tiempoEntrada1").val(),
            grasas_tiempoSalida: $("#tiempoSalida1").val(),

            grasas_enfriadoLote: $("#enfriadoLote1").val(),
            grasas_enfriadoEntrada: $("#enfriadoEntrada1").val(),
            grasas_enfriadoSalida: $("#enfriadoSalida1").val(),



            //-----------------Coliformes------------------
            sembrado_loteId: $('#sembrado_loteId').val(),
            sembrado_sembrado: $('#sembrado_sembrado').val(),
            sembrado_fechaResiembra: $('#sembrado_fechaResiembra').val(),
            sembrado_tuboN: $('#sembrado_tuboN').val(),
            sembrado_bitacora: $('#sembrado_bitacora').val(),

            pruebaPresuntiva_preparacion: $('#pruebaPresuntiva_preparacion').val(),
            pruebaPresuntiva_lectura: $('#pruebaPresuntiva_lectura').val(),

            pruebaConfirmativa_medio: $('#pruebaConfirmativa_medio').val(),
            pruebaConfirmativa_preparacion: $('#pruebaConfirmativa_preparacion').val(),
            pruebaConfirmativa_lectura: $('#pruebaConfirmativa_lectura').val(),



            //--------------------DQO---------------------
            ebullicion_loteId: $("#ebullicion_loteId").val(),
            ebullicion_inicio: $("#ebullicion_inicio").val(),
            ebullicion_fin: $("#ebullicion_fin").val(),
            ebullicion_invlab: $("#ebullicion_invlab").val(),

            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            //swal("Registro!", "Datos guardados correctamente!", "success");            
        }
    });
});

function getPendientes()
{ 
    let tabla = document.getElementById('divPendientes');
    let tab = '';
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/"+area+"/getPendientes",
        data: {
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            model = response.model
            tab += '<table class="table table-sm" style="font-size:10px">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>Folio</th>';
            tab += '          <th>Parametro</th>';
            tab += '          <th>Fecha recepción</th>';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            for (let i = 0; i < model.length; i++) {
                tab += '<tr>';
                tab += '<td>'+model[i][0]+'</td>';
                tab += '<td>'+model[i][1]+'</td>';
                tab += '<td>'+model[i][2]+'</td>';
                tab += '</tr>';   
            }
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;
        }
    });
}

