var area = "fq";

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
    $("#btnGuardarBitacora").click(function () {
        
        setPlantillaDetalleVol()
    });
});

$('#btnDatosLote').click(function () {
    switch ($("#tipoFormula").val()) {
        case '33': // CLORO RESIDUAL LIBRE
        case '64':
            $("#secctionCloro").show();
            $("#secctionDqo").hide();
            $("#secctionNitrogeno").hide();
            $("#secctionDureza").hide();
            break;
        case '6': // DQO
            $("#secctionDqo").show();
            $("#secctionCloro").hide();
            $("#secctionNitrogeno").hide();
            $("#secctionDureza").hide();
            break;
        case '11': //Nitrogeno Total
            $("#secctionNitrogeno").show();
            $("#secctionCloro").hide();
            $("#secctionDqo").hide();
            $("#secctionDureza").hide();
            break;
        case '9': //Nitrogeno Amoniacal
        case '108':
            $("#secctionNitrogeno").show();
            $("#secctionCloro").hide();
            $("#secctionDqo").hide();
            $("#secctionDureza").hide();
            break;
        case '10': //Nitrogeno Organico
            $("#secctionNitrogeno").show();
            $("#secctionCloro").hide();
            $("#secctionDqo").hide();
            $("#secctionDureza").hide();
            break;
        case '103': //Nitrogeno Organico
            $("#secctionDureza").show();
            $("#secctionCloro").hide();
            $("#secctionDqo").hide();
            $("#secctionNitrogeno").hide();
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
        case '103':
            $("#blancoResDur").val($("#blancoDureza").val())
            titulado1 = parseFloat($("#edtaDur1").val())
            titulado2 = parseFloat($("#edtaDur2").val())
            titulado3 = parseFloat($("#edtaDur3").val())
            solucion = parseFloat($("#tituladoDur").val())
            prom = (titulado1 + titulado2 + titulado3) / 3
            res = solucion / (prom - parseFloat($("#blancoResDur").val()))
            $("#normalidadResDur").val(res.toFixed(3))
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
            case '103': // Dureza total
            $.ajax({
                type: 'POST',
                url: base_url + "/admin/laboratorio/" + area + "/guardarValidacionVol",
                data: {
                    caso: 4,
                    idParametro: $("#tipoFormula").val(),
                    blanco: $("#blancoResDur").val(),
                    idLote: $("#idLoteHeader").val(),
                    solucion: $("#tituladoDur").val(),
                    titulado1: $("#edtaDur1").val(),
                    titulado2: $("#edtaDur1").val(),
                    titulado3: $("#edtaDur1").val(),
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
            soluble: $('#solubleDqo').val(),
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
function setPlantillaDetalleVol(){
    $.ajax({ 
        type: "POST",
        url: base_url + "/admin/laboratorio/"+area+"/setPlantillaDetalleVol",
        data: {
            id: $('#idLoteHeader').val(),
            texto: $("#summernote").summernote('code'),
            titulo: $("#tituloBit").val(),
            rev:$("#revBit").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);                        
            alert("Plantilla modificada")
        }
    });
}
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
function selectElement(id, valueToSelect) {    
    let element = document.getElementById(id);
    element.value = valueToSelect;
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
            //------------DQO------------
            if (response.dataDqo !== null) {
                $("#ebullicion_loteId").val(response.dataDqo.Id_lote);
                $("#ebullicion_inicio").val(response.dataDqo.Inicio);
                $("#ebullicion_fin").val(response.dataDqo.Fin);
                $("#ebullicion_invlab").val(response.dataDqo.Invlab);
                selectElement('tecnicaDqo', response.detalleDqo.Tecnica);    
            } else {
                $("#ebullicion_loteId").val('');
                $("#ebullicion_inicio").val('');
                $("#ebullicion_fin").val('');
                $("#ebullicion_invlab").val('');
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

            $("#tituloBit").val(response.plantilla[0].Titulo)
            $("#revBit").val(response.plantilla[0].Rev)
            summer.innerHTML = '<div id="summernote">'+response.plantilla[0].Texto+'</div>';
            $('#summernote').summernote({
                placeholder: '', 
                tabsize: 2,
                height: 300,         
            }); 
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
$('#guardarTodo').click(function () {
    //Guardado de datos
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/lote/guardarDatos",
        data: {
            idLote: $('#idLoteHeader').val(),

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
