var area = "analisis";

$(document).ready(function () {
    $('#tabLote').DataTable({
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        }
    });
    $('#tabCaptura').DataTable({
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        }
    });

    $('.select2').select2();
    $('#btnPendientes').click(function () {
        getPendientes()
    });
    $('#btnPendientes').click(function () {
        getPendientes()
    });
    $('#btnBuscarLote').click(function () {
        getLote()
    });
    $('#btnCrearLote').click(function () {
        setLote()
    });
    $('#btnAsignarMuestra').click(function () {
        setMuestraLote()
    });
    $('#btnEjecutar').click(function () {
        setDetalleMuestra()
    });
    $('.btnEjecutar').click(function () {
        setDetalleMuestra()
    });
    $('#btnSetControl').click(function () {
        setControlCalidad()
    });
    $('#btnLiberarTodo').click(function () {
        setLiberarTodo()
    });
    $('#btnLiberar').click(function () {
        setLiberar();
    });
    $('#btnBitacora').click(function () {
        setBitacora();
    });
    $('#btnSetDetalleGrasas').click(function () {
        setDetalleGrasas();
    });
    $('#btnEjecutarVal').click(function () {
        setFormulaValoracion();
    });
    $('#btnGuardarVal').click(function () {
        setValoracion();
    });
    $('#btnSetNormalidadAlc').click(function () {
        setNormalidadAlc();
    });
    $('#btnSetNormalidadAci').click(function () {
        setNormalidadAci();
    });
    $('#btnFechaDeFGA').click(function () {
        setFechaDefGA();
    });
    $('#btnFechaDeFGA2').click(function () {
        setFechaDefGA2();
    });
    $('#btnGuardarTipoDqo').click(function () {
        $.ajax({
            type: 'POST',
            url: base_url + "/admin/laboratorio/analisis/setTipoDqo",
            data: {
                idLote: idLote,
                tipo: $("#tipoDqo").val(),
                tecnica: $('#tecnicaDqo').val(),
                soluble: $('#solubleDqo').val(),
                _token: $('input[name="_token"]').val(),
            },
            dataType: "json",
            async: false,
            success: function (response) {
                console.log(response);
                // swal("Registro!", "Lote creado correctamente!", "success");
                alert("Dato modificado")
                // $('#modalCrearLote').modal('hide')
            }
        });
    });
    $('#btnColiformes').click(function () {

        //Guardado de datos
        $.ajax({
            type: "POST",
            url: base_url + "/admin/laboratorio/micro/lote/setDetalleLote",
            data: {
                idLote: idLote,
                idParametro: $("#parametro").val(),
                sembrado: $("#sembrado_sembrado").val(),
                fechaResiembra: $("#sembrado_fechaResiembra").val(),
                numTubo: $("#sembrado_tuboN").val(),
                bitacora: $("#sembrado_bitacora").val(),
                preparacion: $("#pruebaPresuntiva_preparacion").val(),
                lectura: $("#pruebaPresuntiva_lectura").val(),
                medio: $("pruebaConfirmativa_medio").val(),
                preparacionCon: $("#pruebaConfirmativa_preparacion").val(),
                lecturaCon: $("#pruebaConfirmativa_lectura").val(),
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
    $('#btnGuardarDqo').click(function () {

        $.ajax({
            type: "POST",
            url: base_url + "/admin/laboratorio/micro/lote/guardarDqo",
            data: {
                idLote: idLote,
                cantDilucion: $("#cantDilucion").val(),
                de: $("#de").val(),
                a: $("#a").val(),
                pag: $("#pag").val(),
                n: $("#n").val(),
                estandaresbit: $("#estandaresbit").val(),
                dilucion: $("#dilucion").val(),
                _token: $('input[name="_token"]').val()
            },
            dataType: "json",
            async: false,
            success: function (response) {
                console.log(response);
                alert("Datos guardados")
                // swal("Registro!", "Datos guardados correctamente!", "success");
            }
        });
    });
    $('#metodoCortoCol').click(function () {
        metodoCortoCol();
        $('#indicador').val(1);
        console.log("metodo corto");
    });
    $('#metodoCortoEnt').click(function () {
        metodoCortoEnterococos();
        
    });
    $('#btnCleanColiforme').click(function () {
        $("#dil1Col").val("");
        $("#dil2Col").val("");
        $("#dil3Col").val("");
        $("#nmp1Col").val("");
        $("#todos1Col").val("");
        $("#negativos1Col").val("");
        $("#positivos1Col").val("");
        $("#con1Col").val(0);
        $("#con2Col").val(0);
        $("#con3Col").val(0);
        $("#con4Col").val(0);
        $("#con5Col").val(0);
        $("#con6Col").val(0);
        $("#con7Col").val(0);
        $("#con8Col").val(0);
        $("#con9Col").val(0);
        $("#pre1Col").val(0);
        $("#pre2Col").val(0);
        $("#pre3Col").val(0);
        $("#pre4Col").val(0);
        $("#pre5Col").val(0);
        $("#pre6Col").val(0);
        $("#pre7Col").val(0);
        $("#pre8Col").val(0);
        $("#pre9Col").val(0);

        $("#resultadoCol").val("");
    });


});

//todo Variables globales
var tableLote
var idLote
var idMuestra
var idMuestra = 0
var idArea = 0
var blanco = 0
var idTecnica = 0
var numColonia = 0
//todo funciones
function getStdMenu() {
    console.log("getStdMenu")
    $("#tabGa-tab").hide()
    $("#tabVol-tab").hide()
    $("#dqo-tab").hide()
    $("#secctionDureza").hide()
    $("#secctionCloro").hide()
    $("#secctionNitrogeno").hide()
    $("#coliformes-tab").hide()
    $("#dbo-tab").hide()
    $("#tabAlcalinidad-tab").hide()
    $("#tabAcidez-tab").hide()
    $(".durSec1").hide()
    $(".durSec2").hide()
    $(".durSec3").hide()

    switch (parseInt(idArea)) {
        case 16: // Espectofotometria
        case 5:

            break;
        case 13://G&A 
            $("#tabGa-tab").show()
            break;
        case 14://Vol
        case 8:
            $("#tabVol-tab").show()
            $("#dqo-tab").show()
            switch (parseInt($("#parametro").val())) {
                case 33: // CLORO RESIDUAL LIBRE
                case 119:
                case 218:
                    document.getElementById('divTitulo1Cloros').innerHTML = 'mL Titulado 1 de Tiosulfato'
                    document.getElementById('divTituloTrazableCloros').innerHTML = 'mL de K2Cr2O7 Trazable'
                    $("#secctionCloro").show(); 
                    break;
                case 64:
                    document.getElementById('divTitulo1Cloros').innerHTML = 'Nitrato de plata'
                    document.getElementById('divTituloTrazableCloros').innerHTML = 'mL de Cloruro de sodio'
                    $("#secctionCloro").show(); 
                    break;
                case 6: // DQO
                case 161:
                    $("#secctionDqo").show();
                    break;
                case 11: //Nitrogeno Total
                case 9: //Nitrogeno Amoniacal
                case 108:
                case 10: //Nitrogeno Organico
                    $("#secctionNitrogeno").show();
                    break;
                case 77: // Dureza
                case 252:
                case 251:
                    $(".durSec1").show()
                    $(".durSec2").hide()
                    $(".durSec3").hide()
                    $("#secctionDureza").show();
                    break;
                case 103: //Dureza
                    $(".durSec1").show()
                    $(".durSec2").show()
                    $(".durSec3").show()
                    $("#secctionDureza").show();
                    break;
                case 28: // Alcalinidad
                case 29:
                case 30:
                    console.log("Entro alcalinidad")
                    $("#tabVol-tab").hide()
                    $("#dqo-tab").hide()
                    $("#tabAlcalinidad-tab").show()
                    break;
                case 27: 
                console.log("Entro Acidez")
                    $("#tabVol-tab").hide()
                    $("#dqo-tab").hide()
                    $("#tabAcidez-tab").show()
                    break;
                default:
                    break;
            }
            break;
        case 6:
        case 12:
        case 3:
            switch (parseInt($("#parametro").val())) {
                case 12:
                case 137:
                case 35:
                    $("#coliformes-tab").show()
                    break;
                case 253:
                    $("#coliformes-tab").show()
                    break;
                case 5:
                case 71:
                case 70:
                    $("#dbo-tab").show()
                    break;
                default:
                    break;
            }
            break;
        default:
            break;
    }
}
function setNormalidadAlc()
{
    if ($("#fecIniAlc").val() != "" && $("#fecIniAlc").val() != "") {
        $.ajax({
            type: 'POST',
            url: base_url + "/admin/laboratorio/analisis/setNormalidadAlc",
            data: {
                idLote: idLote,
                idNormalidad: $("idNormalidadAlc").val(),
                resultado: $("#resValAlc").val(),
                fechaIni: $("#fecIniAlc").val(),
                fechaFin: $("#fecFinAlc").val(),
                granoCarbon1: $("#granoCarbon1").val(),
                granoCarbon2: $("#granoCarbon2").val(),
                granoCarbon3: $("#granoCarbon3").val(),
                tituladodeH1: $("#tituladodeH1").val(),
                tituladodeH2: $("#tituladodeH2").val(),
                tituladodeH3: $("#tituladodeH3").val(),
                equivalenteAlc: $("#equivalenteAlc").val(),
                factConversionAlc: $("#factConversionAlc").val(),
                _token: $('input[name="_token"]').val(),
            },
            dataType: "json",
            async: false,
            success: function (response) {
                console.log(response);
                alert("Datos creados correctamente")
                $("#resValAlc").val(response.model[0].Resultado)
                $("#idNormalidadAlc").val(response.model[0].Id_valoracion)
            }
        }); 
    } else {
        alert("No puedes ejecutar sin el rango de fechas")   
    }

}
function setNormalidadAci(){
    if ($("#fecIniAci").val() != "" && $("#fecFinAci").val() != "") {
        $.ajax({
            type: 'POST',
            url: base_url + "/admin/laboratorio/analisis/setNormalidadAlc",
            data: {
                idLote: idLote,
                idNormalidad: $("idNormalidadAci").val(),
                resultado: $("#resValAci").val(),
                fechaIni: $("#fecIniAci").val(),
                fechaFin: $("#fecFinAci").val(),
                granoCarbon1: $("#granoBifAci1").val(),
                granoCarbon2: $("#granoBifAci2").val(),
                granoCarbon3: $("#granoBifAci3").val(),
                tituladodeH1: $("#tituladoAci1").val(),
                tituladodeH2: $("#tituladoAci2").val(),
                tituladodeH3: $("#tituladoAci3").val(),
                equivalenteAlc: $("#equivalenteAci").val(),
                factConversionAlc: $("#factConversionAci").val(),
                _token: $('input[name="_token"]').val(),
            },
            dataType: "json",
            async: false,
            success: function (response) {
                console.log(response);
                alert("Datos creados correctamente")
                $("#resValAci").val(response.model[0].Resultado)
                $("#idNormalidadAci").val(response.model[0].Id_valoracion)
            }
        }); 
    } else {
        alert("No puedes ejecutar sin el rango de fechas")   
    }
}
function getDetalleEcoli(idMuestra,colonia,indice){
    numColonia = colonia;
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/micro/getDetalleEcoli",
        data: {
            colonia:colonia,
            idDetalle: idMuestra,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response)
            $("#indol1Ecoli").val(response.convinaciones.Indol)
            $("#rm1Ecoli").val(response.convinaciones.Rm)
            $("#vp1Ecoli").val(response.convinaciones.Vp)
            $("#citrato1Ecoli").val(response.convinaciones.Citrato)
            $("#bgn1Ecoli").val(response.convinaciones.BGN)
            $("#indol2Ecoli").val(response.convinaciones.Indol2)
            $("#rm2Ecoli").val(response.convinaciones.Rm2)
            $("#vp2Ecoli").val(response.convinaciones.Vp2)
            $("#citrato2Ecoli").val(response.convinaciones.Citrato2)
            $("#bgn2Ecoli").val(response.convinaciones.BGN2)
            $("#observacionEcoli").val(response.model.observacion)
            $("#indiceEcoli").val(indice)
        }
    });
}
function metodoCortoCol() {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/analisis/metodoCortoColiformes",
        data: {
            idDetalle: idMuestra,
            indicador: $('#indicadorCol').val(),
            resultadoCol: $("#resultadoColCol").val(),
            idParametro: $('#parametro').val(),

            D1: $("#dil1Col").val(),
            D2: $('#dil2Col').val(),
            D3: $('#dil3Col').val(),
            NMP: $('#nmp1Col').val(),
            G3: $('#todos1Col').val(),
            G2: $('#negativos1Col').val(),
            G1: $('#positivos1Col').val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            // inicio metodo corto

            alert(response.msg)

            $("#con3Col").val(0)
            $("#con2Col").val(0)
            $("#con1Col").val(0)
            $("#con4Col").val(0)
            $("#con5Col").val(0)
            $("#con6Col").val(0)
            $("#con7Col").val(0)
            $("#con8Col").val(0)
            $("#con9Col").val(0)

            $("#pre10Col").val(0)
            $("#pre11Col").val(0)
            $("#pre12Col").val(0)
            $("#pre13Col").val(0)
            $("#pre14Col").val(0)
            $("#pre15Col").val(0)
            $("#pre16Col").val(0)
            $("#pre17Col").val(0)
            $("#pre19Col").val(0)

            $("#pre1Col").val(0)
            $("#pre2Col").val(0)
            $("#pre3Col").val(0)
            $("#pre4Col").val(0)
            $("#pre5Col").val(0)
            $("#pre6Col").val(0)
            $("#pre7Col").val(0)
            $("#pre8Col").val(0)
            $("#pre9Col").val(0)

            $("#con10Col").val(0)
            $("#con11Col").val(0)
            $("#con12Col").val(0)
            $("#con13Col").val(0)
            $("#con14Col").val(0)
            $("#con15Col").val(0)
            $("#con16Col").val(0)
            $("#con17Col").val(0)
            $("#con18Col").val(0)
            getCapturaLote()
            getDetalleMuestra(idMuestra)
        }
    });
}
function metodoCortoEnterococos()
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/analisis/metodoCortoColiformes",
        data: {
            idDetalle: idMuestra,
            resultadoCol: $("#resultadoEnt").val(),
            idParametro: $('#parametro').val(),
            D1: $('#endil1Ent').val(),
            D2: $('#endil2Ent').val(),
            D3: $('#endil3Ent').val(),
            NMP: $('#ennmp1Ent').val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            // inicio metodo corto

            alert(response.msg)
            $("#enPre1Ent").val()
            $("#enPre2Ent").val()
            $("#enPre3Ent").val()
            $("#enPre4Ent").val()
            $("#enPre5Ent").val()
            $("#enPre6Ent").val()
            $("#enPre7Ent").val()
            $("#enPre8Ent").val()
            $("#enPre9Ent").val()
            $("#enPre12Ent").val()
            $("#enPre22Ent").val()
            $("#enPre32Ent").val()
            $("#enPre42Ent").val()
            $("#enPre52Ent").val()
            $("#enPre62Ent").val()
            $("#enPre72Ent").val()
            $("#enPre82Ent").val()
            $("#enPre92Ent").val()
            $("#enCon1Ent").val()
            $("#enCon2Ent").val()
            $("#enCon3Ent").val()
            $("#enCon4Ent").val()
            $("#enCon5Ent").val()
            $("#enCon6Ent").val()
            $("#enCon7Ent").val()
            $("#enCon8Ent").val()
            $("#enCon9Ent").val()
            $("#enCon12Ent").val()
            $("#enCon22Ent").val()
            $("#enCon32Ent").val()
            $("#enCon42Ent").val()
            $("#enCon52Ent").val()
            $("#enCon62Ent").val()
            $("#enCon72Ent").val()
            $("#enCon82Ent").val()
            $("#enCon92Ent").val()
            getCapturaLote()
            getDetalleMuestra(idMuestra)
        }
    });
}
function setFormulaValoracion() {
    let prom = 0;
    let res = 0;
    let titulado1 = 0;
    let titulado2 = 0;
    let titulado3 = 0;
    let gramos = 0;
    let factorN = 0;
    let pm = 0;
    switch (parseFloat($("#parametro").val())) {
        case 33: // CLORO RESIDUAL LIBRE
        case 64:
        case 218:
        case 119:
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
        case 28:
        case 29: // Alcalinidad
            $("#blancoResAlc").val($("#blancoValAlc").val())
            titulado1 = $("#gmCarbonato1Alc").val();
            titulado2 = $("#gmCarbonato2Alc").val();
            titulado3 = $("#gmCarbonato3Alc").val();
            vol1 = $("#titulado1Alc").val();
            vol2 = $("#titulado2Alc").val();
            vol3 = $("#titulado3Alc").val();
            equivalentes = $("#gmEquivalentesAlc").val();
            fac = $("#factorAlc").val();
            promB = (parseFloat(vol1) + parseFloat(vol2) + parseFloat(vol3)) / 3;
            prom = (parseFloat(titulado1) + parseFloat(titulado2) + parseFloat(titulado3)) / 3;
            form1 = (parseFloat(promB) * parseFloat(equivalentes))
            res = (parseFloat(prom) / form1) * parseFloat(fac);
            $("#molaridadResAlc").val(res.toFixed(3));
            break;
        case 6: // DQO
        case 161: // DQO
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
        case 11: // Nitrogeno Total
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
        case 9:
        case 108: // Nitrogeno amonicacal
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
        case 10: // Nitrogeno Organico
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
        case 77:
        case 251:
            $("#blancoResDur").val($("#blancoDureza").val())
            titulado1 = parseFloat($("#edtaDur1Sec1").val())
            titulado2 = parseFloat($("#edtaDur2Sec1").val())
            titulado3 = parseFloat($("#edtaDur3Sec1").val())
            solucion = parseFloat($("#tituladoDurSec1").val())
            prom = (titulado1 + titulado2 + titulado3) / 3
            res = solucion / (prom.toFixed(4) - parseFloat($("#blancoResDur").val()))
            $("#normalidadResDur").val(res.toFixed(2))
            $("#resDurezaSec1").val(res.toFixed(2))
            break;
        case 103:
            let res1 = 0.0
            let solucion1 = 0.0
            let prom1 = 0.0

            let res2 = 0.0
            let solucion2 = 0.0
            let prom2 = 0.0

            let res3 = 0.0
            let solucion3 = 0.0
            let prom3 = 0.0
            let promBlanco = 0
            promBlanco = (parseFloat($("#blancoDureza").val()) + parseFloat($("#blancoDureza2").val()) + parseFloat($("#blancoDureza3").val())) / 3
            $("#blancoResDur").val(promBlanco)
            solucion1 = parseFloat($("#tituladoDurSec1").val())
            prom1 = ((parseFloat($("#edtaDur1Sec1").val()) + parseFloat($("#edtaDur2Sec1").val()) + parseFloat($("#edtaDur3Sec1").val()) ) / 3)
            res1 = solucion1 / (prom1.toFixed(4) - parseFloat($("#blancoResDur").val()))

            solucion2 = parseFloat($("#tituladoDurSec2").val())
            prom2 = ((parseFloat($("#edtaDur1Sec2").val()) + parseFloat($("#edtaDur2Sec2").val()) + parseFloat($("#edtaDur3Sec2").val()) ) / 3)
            res2 = solucion2 / (prom2.toFixed(4) - parseFloat($("#blancoResDur").val()))

            solucion3 = parseFloat($("#tituladoDurSec3").val())
            prom3 = ((parseFloat($("#edtaDur1Sec3").val()) + parseFloat($("#edtaDur2Sec3").val()) + parseFloat($("#edtaDur3Sec3").val()) ) / 3)
            res3 = solucion3 / (prom3.toFixed(4) - parseFloat($("#blancoResDur").val()))

            res = (res1 + res2 + res3) / 3

            $("#resDurezaSec1").val(res1.toFixed(2))
            $("#resDurezaSec2").val(res2.toFixed(2))
            $("#resDurezaSec3").val(res3.toFixed(2))

            $("#normalidadResDur").val(res.toFixed(2))
            break;
        default:
            break;
    }
}
function setValoracion() {
    switch (parseInt($("#parametro").val())) {
        case 295: // CLORO RESIDUAL LIBRE
        case 64:
        case 218:
        case 119:
        case 33:
            $.ajax({
                type: 'POST',
                url: base_url + "/admin/laboratorio/fq/guardarValidacionVol",
                data: {
                    caso: 1,
                    idParametro: $("#tipoFormula").val(),
                    blanco: $("#blancoResClo").val(),
                    idLote: idLote,
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
                    alert("Datos guardados")
                }
            });
            break;
        case 6: // DQO
        case 161:
            $.ajax({
                type: 'POST',
                url: base_url + "/admin/laboratorio/fq/guardarValidacionVol",
                data: {
                    caso: 2,
                    idLote: idLote,
                    idParametro: $("#tipoFormula").val(),
                    blanco: $("#blancoResD").val(),
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
        case 11: // NITROGENO TOTAL
            $.ajax({
                type: 'POST',
                url: base_url + "/admin/laboratorio/fq/guardarValidacionVol",
                data: {
                    caso: 3,
                    idLote: idLote,
                    idParametro: $("#tipoFormula").val(),
                    blanco: $("#blancoResN").val(),
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
        case 10: // NITROGENO TOTAL
            $.ajax({
                type: 'POST',
                url: base_url + "/admin/laboratorio/fq/guardarValidacionVol",
                data: {
                    caso: 3,
                    idLote: idLote,
                    idParametro: $("#tipoFormula").val(),
                    blanco: $("#blancoResN").val(),
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
        case 9: // NITROGENO TOTAL
            $.ajax({
                type: 'POST',
                url: base_url + "/admin/laboratorio/fq/guardarValidacionVol",
                data: {
                    caso: 3,
                    idParametro: $("#tipoFormula").val(),
                    blanco: $("#blancoResN").val(),
                    idLote: idLote,
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
        case 108: // NITROGENO amoniacal
        case 28:
            $.ajax({
                type: 'POST',
                url: base_url + "/admin/laboratorio/fq/guardarValidacionVol",
                data: {
                    caso: 3,
                    idParametro: $("#tipoFormula").val(),
                    blanco: $("#blancoResN").val(),
                    idLote: idLote,
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
        case 103: // Dureza total
        case 77:
        case 251:
        case 252:
            $.ajax({
                type: 'POST',
                url: base_url + "/admin/laboratorio/fq/guardarValidacionVol",
                data: {
                    caso: 4,
                    idParametro: $("#parametro").val(),
                    blanco: $("#blancoResDur").val(),
                    blanco1: $("#blancoDureza").val(),
                    blanco2: $("#blancoDureza2").val(),
                    blanco3: $("#blancoDureza3").val(),
                    idLote: idLote,
                    solucionSec1: $("#tituladoDurSec1").val(),
                    titulado1Sec1: $("#edtaDur1Sec1").val(),
                    titulado2Sec1: $("#edtaDur2Sec1").val(),
                    titulado3Sec1: $("#edtaDur3Sec1").val(),
                    resultadoSec1: $("#resDurezaSec1").val(),
                    
                    solucionSec2: $("#tituladoDurSec2").val(),
                    titulado1Sec2: $("#edtaDur1Sec2").val(),
                    titulado2Sec2: $("#edtaDur2Sec2").val(),
                    titulado3Sec2: $("#edtaDur3Sec2").val(),
                    resultadoSec2: $("#resDurezaSec2").val(),

                    solucionSec3: $("#tituladoDurSec3").val(),
                    titulado1Sec3: $("#edtaDur1Sec3").val(),
                    titulado2Sec3: $("#edtaDur2Sec3").val(),
                    titulado3Sec3: $("#edtaDur3Sec3").val(),
                    resultadoSec3: $("#resDurezaSec3").val(),

                    resultado: $("#normalidadResDur").val(),
                    _token: $('input[name="_token"]').val(),
                },
                dataType: "json",
                async: false,
                success: function (response) {
                    console.log(response);
                    alert("Datos guardados")
                }
            });
            break;
        case 28: // ALCALINIDAD
        case 29:
            $.ajax({
                type: 'POST',
                url: base_url + "/admin/laboratorio/fq/guardarValidacionVol",
                data: {
                    caso: 5,
                    idLote: idLote,
                    idParametro: $("#tipoFormula").val(),
                    titulado1: $("#gmCarbonato1Alc").val(),
                    titulado2: $("#gmCarbonato2Alc").val(),
                    titulado3: $("#gmCarbonato3Alc").val(),
                    vol1: $("#titulado1Alc").val(),
                    vol2: $("#titulado1A2c").val(),
                    vol3: $("#titulado1A3c").val(),
                    equivalentes: $("#gmEquivalentesAlc").val(),
                    fac: $("#factorAlc").val(),
                    molaridad: $("#molaridadResAlc").val(),
                    blanco: $("#blancoResAlc").val(),
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
}
function habilitarTabla(id1, id2) {
    $("#" + id1).show();
    $("#" + id2).hide();
}
function setDetalleGrasas() {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/setDetalleGrasas",
        data: {
            id: idLote,
            temp1: $('#tempGA1').val(),
            entrada1: $('#entradaGA1').val(),
            salida1: $('#salidaGA1').val(),
            temp2: $('#tempGA2').val(),
            entrada2: $('#entradaGA2').val(),
            salida2: $('#salidaGA2').val(),
            temp3: $('#tempGA3').val(),
            entrada3: $('#entradaGA3').val(),
            salida3: $('#salidaGA3').val(),
            dosentrada1: $('#2entradaGA1').val(),
            dosalida1: $('#2salidaGA1').val(),
            dospesado1: $('#2pesadoGA1').val(),
            dosentrada2: $('#2entradaGA2').val(),
            dosalida2: $('#2salidaGA2').val(),
            dospesado2: $('#2pesadoGA2').val(),
            dosentrada3: $('#2entradaGA3').val(),
            dosalida3: $('#2salidaGA3').val(),
            dospesado3: $('#2pesadoGA3').val(),
            trestemperatura: $('#3temperaturaGA').val(),
            tresentrada: $('#3entradaGA').val(),
            tressalida: $('#3salidaGA').val(),
            cuatroentrada: $('#4entradaGA').val(),
            cuatrosalida: $('#4salidaGA').val(),
            cincoentrada: $('#5entradaGA').val(),
            cincosalida: $('#5salidaGA').val(),

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

function setBitacora() {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/setBitacora",
        data: {
            id: idLote,
            texto: $("#summernote").summernote('code'),
            titulo: $("#tituloBit").val(),
            rev: $("#revBit").val(),
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
var setObservacion = (function (id = "",id2 = "na",id3 = "na") {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/setObservacion",
        data: {
            idLote: idLote,
            idMuestra: idMuestra,
            observacion: $("#" + id).val(),
            observacion2: $("#" + id2).val(),
            observacion3: $("#" + id3).val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            getCapturaLote();
        }
    });
    
});
function setLiberar() {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/setLiberar",
        data: {
            idMuestra: idMuestra,
            idLote: idLote,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            if (response.sw == true) {
                getLote();
                getCapturaLote();
                alert("Muestra liberada")
                return false;
            } else {
                alert("La muestra no se pudo liberar");
            }
           
        }
    });
}
function setLiberarTodo() {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/setLiberarTodo",
        data: {
            idLote: idLote,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            if (response.sw == true) {
                getLote();
                getCapturaLote();
                alert("Muestras liberadas")
            } else {
                alert("La muestra no se pudo liberar");
            }
        }
    });
}
function setControlCalidad() {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/setControlCalidad",
        data: {
            idMuestra: idMuestra,
            idLote: idLote,
            idControl: $("#controlCalidad").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            alert("Control generado")
            getLote();
            getCapturaLote();
        }
    });
}
function exportBitacora(id) {
    window.open(base_url + "/admin/laboratorio/" + area + "/bitacora/impresion/" + id);
}
function getDetalleLote(id, parametro) {
    getStdMenu()
    $("#modalDetalleLote").modal("show")
    
    let summer = document.getElementById("divSummer")
    let tableHist = document.getElementById("tableValAlcalinidadHist")
    let tab = ''
    let tableHistAci = document.getElementById("tableValAlcalinidadHist")
    let tabAci = ''
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getDetalleLote",
        data: {
            id: id,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            switch (parseInt(idArea)) {
                case 16: // Espectofotometria
                case 5:

                    break;
                case 14:
                    switch (parseInt($("#parametro").val())) {
                        case 33:
                        case 64:
                            $("#blancoResClo").val(response.model.Blanco)
                            $("#blancoCloro").val(response.model.Blanco)
                            $("#normalidadResCloro").val(response.model.Resultado)
                            $("#tituladoClo1").val(response.model.Ml_titulado1)
                            $("#tituladoClo2").val(response.model.Ml_titulado2)
                            $("#tituladoClo3").val(response.model.Ml_titulado3)
                            $("#trazableClo").val(response.model.Ml_trazable)
                            $("#normalidadClo").val(response.model.Normalidad)
                            break;
                        case 28:
                        case 29:
                            if (response.model != null) {
                                $("#idNormalidadAlc").val(response.model.Id_valoracion)
                                $("#resValAlc").val(response.model.Resultado)
                                $("#fecIniAlc").val(response.model.Fecha_inicio)
                                $("#fecFinAlc").val(response.model.Fecha_fin)
                                $("#granoCarbon1").val(response.model.Granos_carbon1)
                                $("#granoCarbon2").val(response.model.Granos_carbon2)
                                $("#granoCarbon3").val(response.model.Granos_carbon3)
                                $("#tituladodeH1").val(response.model.Titulado1)
                                $("#tituladodeH2").val(response.model.Titulado2)
                                $("#tituladodeH3").val(response.model.Titulado3)
                                $("#equivalenteAlc").val(response.model.Granos_equivalente)
                                $("#factConversionAlc").val(response.model.Factor_conversion)
        
                                
                                tab += '<h6>Historial</h6><table id="tabCaptura" class="table table-sm">';
                                tab += '    <thead>';
                                tab += '        <tr>';
                                tab += '          <th>ID</th>';
                                tab += '          <th>Fecha Ini</th>';
                                tab += '          <th>Fecha Fin</th>';
                                tab += '          <th>Resultado</th>';
                                tab += '        </tr>';
                                tab += '    </thead>';
                                tab += '    <tbody>';
                                $.each(response.aux, function (key, item) {
                                    tab += '<tr>';
                                    tab += '<td>'+item.Id_valoracion+'</td>';
                                    tab += '<td>'+item.Fecha_inicio+'</td>';
                                    tab += '<td>'+item.Fecha_fin+'</td>';
                                    tab += '<td>'+item.Resultado+'</td>';
                                    tab += '</tr>';
                                })
                                tab += '    </tbody>';
                                tab += '    </table>';
                                tableHist.innerHTML = tab
                            } 
                            break;
                        case 27:
                            if (response.model != null) {
                                $("#idNormalidadAci").val(response.model.Id_valoracion)
                                $("#resValAci").val(response.model.Resultado)
                                $("#fecIniAci").val(response.model.Fecha_inicio)
                                $("#fecFinAci").val(response.model.Fecha_fin)
                                $("#granoBifAci1").val(response.model.Granos_carbon1)
                                $("#granoBifAci2").val(response.model.Granos_carbon2)
                                $("#granoBifAci3").val(response.model.Granos_carbon3)
                                $("#tituladoAci1").val(response.model.Titulado1)
                                $("#tituladoAci2").val(response.model.Titulado2)
                                $("#tituladoAci3").val(response.model.Titulado3)
                                $("#equivalenteAci").val(response.model.Granos_equivalente)
                                $("#factConversionAci").val(response.model.Factor_conversion)
        
                                
                                tabAci += '<h6>Historial</h6><table id="tabCaptura" class="table table-sm">';
                                tabAci += '    <thead>';
                                tabAci += '        <tr>';
                                tabAci += '          <th>ID</th>';
                                tabAci += '          <th>Fecha Ini</th>';
                                tabAci += '          <th>Fecha Fin</th>';
                                tabAci += '          <th>Resultado</th>';
                                tabAci += '        </tr>';
                                tabAci += '    </thead>';
                                tabAci += '    <tbody>';
                                $.each(response.aux, function (key, item) {
                                    tabAci += '<tr>';
                                    tabAci += '<td>'+item.Id_valoracion+'</td>';
                                    tabAci += '<td>'+item.Fecha_inicio+'</td>';
                                    tabAci += '<td>'+item.Fecha_fin+'</td>';
                                    tabAci += '<td>'+item.Resultado+'</td>';
                                    tabAci += '</tr>';
                                })
                                tabAci += '    </tbody>';
                                tabAci += '    </table>';
                                tableHistAci.innerHTML = tabAci
                            } 
                            break;
                        default:
                            break;
                    }
                 
                    break;
                case 8:
                    switch (parseInt($("#parametro").val())) {
                        case 77:
                        case 103:
                        case 251:
                        case 252:

                            if (response.model != null) {
                            $("#tituladoDurSec1").val(response.model.SolucionSec1)
                            $("#edtaDur1Sec1").val(response.model.Disolucion1Sec1)
                            $("#edtaDur2Sec1").val(response.model.Disolucion2Sec1)
                            $("#edtaDur3Sec1").val(response.model.Disolucion3Sec1)
                            $("#resDurezaSec1").val(response.model.ResultadoSec1)

                            $("#tituladoDurSec2").val(response.model.SolucionSec2)
                            $("#edtaDur1Sec2").val(response.model.Disolucion1Sec2)
                            $("#edtaDur2Sec2").val(response.model.Disolucion2Sec2)
                            $("#edtaDur3Sec2").val(response.model.Disolucion3Sec2)
                            $("#resDurezaSec2").val(response.model.ResultadoSec2)

                            $("#tituladoDurSec3").val(response.model.SolucionSec3)
                            $("#edtaDur1Sec3").val(response.model.Disolucion1Sec3)
                            $("#edtaDur2Sec3").val(response.model.Disolucion2Sec3)
                            $("#edtaDur3Sec3").val(response.model.Disolucion3Sec3)
                            $("#resDurezaSec3").val(response.model.ResultadoSec3)

                            $("#blancoDureza").val(response.model.Blanco1)
                            $("#blancoDureza2").val(response.model.Blanco2)
                            $("#blancoDureza3").val(response.model.Blanco3)
                            $("#blancoResDur").val(response.model.Blanco)
                            $("#normalidadResDur").val(response.model.Resultado)
                            } 

                            break; 
                        default:
                            break;
                    }
                    break;
                case 13://G&A
                    $('#tempGA1').val(response.model.Calentamiento_temp1);
                    $('#entradaGA1').val(response.model.Calentamiento_entrada1);
                    $('#salidaGA1').val(response.model.Calentamiento_salida1);
                    $('#tempGA2').val(response.model.Calentamiento_temp2);
                    $('#entradaGA2').val(response.model.Calentamiento_entrada2);
                    $('#salidaGA2').val(response.model.Calentamiento_salida2);
                    $('#tempGA3').val(response.model.Calentamiento_temp3);
                    $('#entradaGA3').val(response.model.Calentamiento_entrada3);
                    $('#salidaGA3').val(response.model.Calentamiento_salida3);

                    $('#2entradaGA1').val(response.model.Enfriado_entrada1);
                    $('#2salidaGA1').val(response.model.Enfriado_salida1);
                    $('#2pesadoGA1').val(response.model.Enfriado_pesado1);
                    $('#2entradaGA2').val(response.model.Enfriado_entrada2);
                    $('#2salidaGA2').val(response.model.Enfriado_salida2);
                    $('#2pesadoGA2').val(response.model.Enfriado_pesado2);
                    $('#2entradaGA3').val(response.model.Enfriado_entrada3);
                    $('#2salidaGA3').val(response.model.Enfriado_salida3);
                    $('#2pesadoGA3').val(response.model.Enfriado_pesado3);

                    $('#3temperaturaGA').val(response.model.Secado_temp);
                    $('#3entradaGA').val(response.model.Secado_entrada);
                    $('#3salidaGA').val(response.model.Secado_salida);

                    $('#4entradaGA').val(response.model.Reflujo_entrada);
                    $('#4salidaGA').val(response.model.Reflujo_salida);

                    $('#5entradaGA').val(response.model.Enfriado_matraces_entrada);
                    $('#5salidaGA').val(response.model.Enfriado_matraces_salida);
                    break;
                case 6:
                case 12:
                    switch (parseInt($("#parametro").val())) {
                        case 6:
                        case 70:
                            $('#cantDilucion').val(response.model.Cant_dilucion);
                            $('#de').val(response.model.De);
                            $('#a').val(response.model.A);
                            $('#pag').val(response.model.Pag);
                            $('#n').val(response.model.N);
                            $('#dilucion').val(response.model.Dilucion);
                            $('#estandaresbit').val(response.model.Estandares_bit);
                            break;
                        case 5:
                        case 71:
                            $('#cantDilucion').val(response.model.Cant_dilucion);
                            $('#de').val(response.model.De);
                            $('#a').val(response.model.A);
                            $('#pag').val(response.model.Pag);
                            $('#n').val(response.model.N);
                            $('#dilucion').val(response.model.Dilucion);
                            $('#estandaresbit').val(response.model.Estandares_bit);
                            break;
                        default:
                            break;
                    }

                
                default:
                    break;
            }
            
            $("#tituloLote").val(id + ' - ' + parametro + '| Fecha lote:'+response.lote.Fecha)
            console.log("creado plantilla")
            $("#tituloBit").val(response.plantilla[0].Titulo)
            $("#revBit").val(response.plantilla[0].Rev)
            summer.innerHTML = '<div id="summernote">' + response.plantilla[0].Texto + '</div>';

            $('#summernote').summernote({
                placeholder: '',
                tabsize: 2,
                height: 300,
             
            });
        }
    });
}
function setDetalleMuestra() {
    let  resGa = 0
    switch (parseInt(idArea)) {
        case 16: // Espectofotometria
        case 5:
            switch (parseInt($('#parametro').val())) {
                case 152: // COT
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/laboratorio/" + area + "/setDetalleMuestra",
                        data: {
                            idLote: idLote,
                            idMuestra: idMuestra,
                            ABS: $('#abs1COT').val(),
                            CA: $('#blanco1COT').val(),
                            CB: $('#b1COT').val(),
                            CM: $('#m1COT').val(),
                            CR: $('#r1COT').val(),
                            D: $('#fDilucion1COT').val(),
                            E: $('#volMuestra1COT').val(),
                            X: $('#abs11COT').val(),
                            Y: $('#abs21COT').val(),
                            Z: $('#abs31COT').val(),

                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            if (response.idControl == 5) {
                                $("#resultadoCOT").val(response.model.Resultado);
                            } else {
                                $("#abs1COT").val(parseFloat(response.model.Promedio).toFixed(3));
                                $("#abs2COT").val(parseFloat(response.model.Promedio).toFixed(3));
                                $("#resultadoCOT").val(response.model.Resultado.toFixed(3));
                                $("#fDilucion1COT").val(response.model.Vol_dilucion.toFixed(3));
                                $("#fDilucion2COT").val(response.model.Vol_dilucion.toFixed(3));

                            }
                        }
                    });
                    break;
                case 113:
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/laboratorio/" + area + "/setDetalleMuestra",
                        data: {
                            idLote: idLote,
                            idMuestra: idMuestra,
                            ABS: $('#abs1SulfatosF').val(),
                            CA: $('#blanco1F').val(),
                            CB: $('#b1SulfatosF').val(),
                            CM: $('#m1SulfatosF').val(),
                            CR: $('#r1SulfatosF').val(),
                            D: $('#fDilucion1SulfatosF').val(),
                            E: $('#volMuestra1SulfatosF').val(),
                            X: $('#abs11SulfatosF').val(),
                            Y: $('#abs21SulfatosF').val(),
                            Z: $('#abs31SulfatosF').val(),
                            ABS4: $('#abs41SulfatosF').val(),
                            ABS5: $('#abs51SulfatosF').val(),
                            ABS6: $('#abs61SulfatosF').val(),
                            ABS7: $('#abs71SulfatosF').val(),
                            ABS8: $('#abs81SulfatosF').val(),
                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            $("#abs1SulfatosF").val(response.model.Promedio.toFixed(3));
                            $("#abs2SulfatosF").val(response.model.Promedio.toFixed(3));
                            $("#resultadoSulfatos").val(response.model.Resultado.toFixed(3));
                            $("#fDilucion1SulfatosF").val(response.model.Vol_dilucion.toFixed(3));
                            $("#fDilucion2SulfatosF").val(response.model.Vol_dilucion.toFixed(3));

                        }
                    });
                    break;
                default:
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/laboratorio/" + area + "/setDetalleMuestra",
                        data: {
                            idLote: idLote,
                            idMuestra: idMuestra,
                            ABS: $('#absPromEspectro1').val(),
                            CA: $('#blancoEspectro1').val(),
                            CB: $('#bEspectro1').val(),
                            CM: $('#mEspectro1').val(),
                            CR: $('#rEspectro1').val(),
                            phIni: $('#phIniEspectro1').val(),
                            phFin: $('#phFinEspectro1').val(),
                            nitratos: $('#nitratosEspectro1').val(),
                            nitritos: $('#nitritosEspectro1').val(),
                            sulfuros: $('#sulfurosEspectro1').val(),
                            D: $('#fDilucionEspectro1').val(),
                            E: $('#volMuestraEspectro1').val(),
                            X: $('#abs1Espectro1').val(),
                            Y: $('#abs2Espectro1').val(),
                            Z: $('#abs3Espectro1').val(),
                            X2: $('#abs1Espectro2').val(),
                            Y2: $('#abs2Espectro2').val(),
                            Z2: $('#abs3Espectro2').val(),
                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            $("#absPromEspectro1").val(parseFloat(response.model.Promedio.toFixed(3)));
                            $("#absPromEspectro2").val(parseFloat(response.model.Promedio.toFixed(3)));
                            $("#resultadoEspectro").val(parseFloat(response.model.Resultado.toFixed(3)));
                            $("#fDilucionEspectro1").val(parseFloat(response.model.Vol_dilucion).toFixed(3));
                            $("#fDilucionEspectro2").val(parseFloat(response.model.Vol_dilucion).toFixed(3));

                        }
                    });
                    break;
            }
            break;
        case 13://G&A
            $.ajax({
                type: "POST",
                url: base_url + "/admin/laboratorio/" + area + "/setDetalleMuestra",
                data: {
                    idLote: idLote,
                    idMuestra: idMuestra,
                    R: $("#resultadoGA").val(),
                    H: $("#hGA1").val(),
                    J: $("#jGA1").val(),
                    K: $("#kGA1").val(),
                    C: $("#cGA1").val(),
                    L: $("#lGA1").val(),
                    I: $("#iGA1").val(),
                    G: $("#gGA1").val(),
                    E: $("#eGA1").val(),
                    P: $("#pGA").val(),
                    _token: $('input[name="_token"]').val()
                },
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    if (response.std == true) {
                        let fixh1 = parseFloat(response.model.M_final).toFixed(4);
                        let fixj1 = parseFloat(response.model.M_inicial1).toFixed(4);
                        let fixk1 = parseFloat(response.model.M_inicial2).toFixed(4);
                        let fixc1 = parseFloat(response.model.M_inicial3).toFixed(4);

                        $("#hGA1").val(fixh1);
                        $("#jGA1").val(fixj1);
                        $("#kGA1").val(fixk1);
                        $("#cGA1").val(fixc1);
                        $('#pGA').val(response.model.Matraz);
                        $('#resultadoGA').val(response.model.Resultado);
                        resGa = response.model.Resultado

                        alert("Datos guardados y calculados")
                    } else {
                        alert("No hay matraz disponible")
                    }
                }
            });
            break;
        case 15:// Solidos
            switch (parseInt($('#parametro').val())) {
                case 3: // Directos
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/laboratorio/" + area + "/setDetalleMuestra",
                        data: {
                            idLote: idLote,
                            idMuestra: idMuestra,
                            resultado: $("#resultadoModalSolidosDir").val(),
                            inmhoff: $("#inmhoffSolidosDir").val(),
                            temperaturaLlegada: $("#temperaturaLlegadaSolidosDir").val(),
                            temperaturaAnalizada: $("#temperaturaAnalizadaSolidosDir").val(),
                            obs: $("#observacionSolidosDir").val(),
                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            $("#resultadoSolidosDir").val(response.model.Resultado)
                        }
                    });
                    break;
                case 47: // Por diferencia
                case 88:
                case 44:
                case 45:
                case 43:
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/laboratorio/" + area + "/setDetalleMuestra",
                        data: {
                            idLote: idLote,
                            idMuestra: idMuestra,
                            resultado: $("#preResDifSolidosDif").val(),
                            val1: $("#val11SolidosDif").val(),
                            val2: $("#val21SolidosDif").val(),
                            obs: $("#observacionSolidosDif").val(),
                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            $("#resultadoSolidosDif").val(response.res);
                        }
                    });
                    break;
                default: // Default solidos (4)
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/laboratorio/" + area + "/setDetalleMuestra",
                        data: {
                            idLote: idLote,
                            idMuestra: idMuestra,
                            R: $("#resultadoSolidos").val(),
                            crisol: $("#crisolSolidos").val(),
                            masa1: $("#m11Solidos").val(),
                            masa2: $("#m21Solidos").val(),
                            pesoConMuestra1: $("#pcm11Solidos").val(),
                            pesoConMuestra2: $("#pcm21Solidos").val(),
                            pesoC1: $("#pc1Solidos").val(),
                            pesoC2: $("#pc21Solidos").val(),
                            volumen: $("#v1Solidos").val(),
                            factor: $("#f1Solidos").val(),
                            obs: $("#observacionSolidos").val(),
                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            getDetalleMuestra(idMuestra)
                        }
                    });
                    break;
            }
            break;
        case 14:// Volumetria
            switch (parseInt($('#parametro').val())) {
                case 218: // Cloro
                case 33:
                case 64:
                case 119:
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/laboratorio/" + area + "/setDetalleMuestra",
                        data: {
                            idLote: idLote,
                            idMuestra: idMuestra,
                            A: $("#cloroA1Vol").val(),
                            E: $("#cloroE1Vol").val(),
                            H: $("#cloroH1Vol").val(),
                            G: $("#cloroG1Vol").val(),
                            B: $("#cloroB1Vol").val(),
                            C: $("#cloroC1Vol").val(),
                            D: $("#cloroD1Vol").val(),
                            Resultado: $("#resultadoCloroVol").val(),
                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            $("#resultadoCloroVol").val(response.model.Resultado)
                        }
                    });

                    break;
                case 6: // Dqo
                case 161:
                    if (idTecnica == 2) {
                        $.ajax({
                            type: "POST",
                            url: base_url + "/admin/laboratorio/" + area + "/setDetalleMuestra",
                            data: {
                                idLote: idLote,
                                idMuestra: idMuestra,
                                sw: idTecnica,
                                B: $("#tituladoDqo1DqoVol").val(),
                                C: $("#MolaridadDqo1DqoVol").val(),
                                CA: $("#blancoDqo1DqoVol").val(),
                                D: $("#factorDqo1DqoVol").val(),
                                E: $("#volDqo1DqoVol").val(),
                                radio: $("#estadoRadioDqoVol").val(),
                                resultado: $("#resultadoDqoVol").val(),
                                _token: $('input[name="_token"]').val()
                            },
                            dataType: "json",
                            success: function (response) {
                                console.log(response);
                                alert("Datos guardados")
                                $("#resultadoDqoVol").val(number_format(response.model.Resultado.toFixed(2),2,'.',''));
                            }
                        });
                    } else {
                        $.ajax({
                            type: "POST",
                            url: base_url + "/admin/laboratorio/" + area + "/setDetalleMuestra",
                            data: {
                                idLote: idLote,
                                idMuestra: idMuestra,
                                sw: idTecnica,
                                parametro: $('#formulaTipo').val(),
                                resultado: $('#resultado').val(),
                                observacion: $('#obs').val(),
                                ABS: $('#abs1').val(),
                                CA: $('#blanco1').val(),
                                CB: $('#b1').val(),
                                CM: $('#m1').val(),
                                CR: $('#r1').val(),
                                D: $('#fDilucion1').val(),
                                E: $('#volMuestra1').val(),
                                X: $('#abs11').val(),
                                Y: $('#abs21').val(),
                                Z: $('#abs31').val(),
                                radio: $("#estadoRadio").val(),
                                _token: $('input[name="_token"]').val()
                            },
                            dataType: "json",
                            success: function (response) {
                                console.log(response);
                                alert("¡GUARDADO!");
                                $("#resultadoNitrogenoVol").val(response.model.Resultado.toFixed(2));
                            }
                        });
                    }
                    break;
                case 9: // Nitrogeno
                case 287:
                case 10:
                case 11:
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/laboratorio/" + area + "/setDetalleMuestra",
                        data: {
                            idLote: idLote,
                            idMuestra: idMuestra,
                            A: $("#tituladosNitro1Vol").val(),
                            B: $("#blancoNitro1Vol").val(),
                            C: $("#molaridadNitro1Vol").val(),
                            D: $("#factorNitro1Vol").val(),
                            E: $("#conversion1Vol").val(),
                            G: $("#volNitro1Vol").val(),
                            resultado: $("#resultadoNitrogenoVol").val(),
                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            $("#resultadoNitrogenoVol").val(response.model.Resultado.toFixed(2));
                        }
                    });
                    break;
                case 108:// Nitrogeno Amon
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/laboratorio/" + area + "/setDetalleMuestra",
                        data: {
                            idLote: idLote,
                            idMuestra: idMuestra,
                            A: $("#factor1ENitrogenoEVol").val(),
                            B: $("#concentracion1ENitrogenoEVol").val(),
                            C: $("#volAñadidoStd1ENitrogenoEVol").val(),
                            D: $("#VolAñadidoMuestra1ENitrogenoEVol").val(),
                            V: $("#volumenMuestraE1NitrogenoEVol").val(),
                            O: $("#observacionNitroENitrogenoEVol").val(),
                            resultado: $("#resultadoNitrogenoEVol").val(),
                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            $("#resultadoNitrogenoEVol").val(response.model.Resultado.toFixed(2));
                        }
                    });
                    break;
                case 28:
                case 29:
                case 27:
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/laboratorio/" + area + "/setDetalleMuestra",
                        data: {
                            idLote: idLote,
                            idMuestra: idMuestra,
                            A: $("#tituladoAlc1Vol").val(),
                            E: $("#phMuestraAlc1Vol").val(),
                            D: $("#volMuestraAlc1Vol").val(),
                            B: $("#normalidadAlc1Vol").val(),
                            C: $("#conversionAlc1Vol").val(),
                            Resultado: $("#resultadoAlcalinidad").val(),
                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            $("#resultadoAlcalinidad").val(response.model.Resultado)
                        }
                    });
                    break;
                case 30:
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/laboratorio/" + area + "/setDetalleMuestra",
                        data: {
                            idLote: idLote,
                            idMuestra: idMuestra,
                            A: $("#resFeno").val(), 
                            B: $("#resAnaranjado").val(),
                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            $("#resultadoAlcalinidadTo").val(response.model.Resultado)
                        }
                    });
                    break;
                default: // Default Directos
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/laboratorio/" + area + "/setDetalleMuestra",
                        data: {
                            idLote: idLote,
                            idMuestra: idMuestra,
                            aparente: $("#resDirectoDef").val(),
                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            $("#resultadoDirectoDef").val(response.model.Resultado)
                        }

                    });
                    break;
            }
            break;
        case 7://Campo
        case 19://directo
            switch (parseInt($('#parametro').val())) {
                case 14: // Ph
                case 67:
                case 110:
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/laboratorio/" + area + "/setDetalleMuestra",
                        data: {
                            idLote: idLote,
                            idMuestra: idMuestra,
                            l1: $("#lecturaUno1Directo").val(),
                            l2: $("#lecturaDos1Directo").val(),
                            l3: $("#lecturaTres1Directo").val(),
                            temp: $("#temperatura1Directo").val(),
                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            $("#resultadoDirecto").val(response.model.Resultado)

                        }

                    });
                    break;
                case 218: // Cloruros
                case 119:
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/laboratorio/" + area + "/setDetalleMuestra",
                        data: {
                            idLote: idLote,
                            idMuestra: idMuestra,
                            dilucion: $("#dilucionCloro1DirectoClo").val(),
                            volumen: $("#volumenCloro1DirectoClo").val(),
                            l1: $("#lecturaUnoCloro1DirectoClo").val(),
                            l2: $("#lecturaDosCloro1DirectoClo").val(),
                            l3: $("#lecturaTresCloro1DirectoClo").val(),
                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            alert("datos guardados")
                            $("#dilucionCloro1DirectoClo").val(response.model.Factor_dilucion)
                            $("#promedioCloro1DirectoClo").val(response.model.Resultado)
                            $("#resultadoDirectoClo").val(response.model.Resultado)
                        }

                    });
                    break;
                case 97: // temperatura
                case 33:
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/laboratorio/" + area + "/setDetalleMuestra",
                        data: {
                            idLote: idLote,
                            idMuestra: idMuestra,
                            l1: $("#lecturaUno1TDirectoTemp").val(),
                            l2: $("#lecturaDos1TDirectoTemp").val(),
                            l3: $("#lecturaTres1TDirectoTemp").val(),
                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            $("#resultadoDirectoTemp").val(response.model.Resultado)

                        }

                    });
                    break;
                case 66://color
                case 65:
                case 120:
                case 372:
                case 365:
                case 370:
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/laboratorio/" + area + "/setDetalleMuestra",
                        data: {
                            idLote: idLote,
                            idMuestra: idMuestra,
                            aparente: $("#aparente1DirectoColor").val(),
                            verdadero: $("#verdadero1DirectoColor").val(),
                            dilusion: $("#dilusion1DirectoColor").val(),
                            volumen: $("#volumen1DirectoColor").val(),
                            factor: $("#factor1DirectoColor").val(),
                            ph: $("#ph1DirectoColor").val(),
                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            $("#resultadoDirectoColor").val(response.model.Resultado)
                            // $("#dilusion1DirectoColor").val(response.mode)
                            $("#factor1DirectoColor").val(response.Factor_dilucion)

                        }

                    });
                    break;
                case 89:
                case 98:
                case 115:
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/laboratorio/" + area + "/setDetalleMuestra",
                        data: {
                            idLote: idLote,
                            idMuestra: idMuestra,
                            factor: $("#dilusionTurb1DirectoTur").val(),
                            volumen: $("#valumenTurb1DirectoTur").val(),
                            l1: $("#lecturaUnoTurb1DirectoTur").val(),
                            l2: $("#lecturaDosTurb1DirectoTur").val(),
                            l3: $("#lecturaTresTurb1DirectoTur").val(),
                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            $("#promedioTurb1DirectoTur").val(response.model.Promedio)
                            $("#resultadoDirectoTur").val(number_format(parseFloat(response.model.Resultado),3,'.',''))
                            $("#dilusionTurb1DirectoTur").val(response.model.Factor_dilucion)
                        }

                    });
                    break;
           
                case 102:
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/laboratorio/" + area + "/setDetalleMuestra",
                        data: {
                            idLote: idLote,
                            idMuestra: idMuestra,
                            ph: $("#phpColor").val(),
                            volColor: $("#volColor").val(),
                            fd1: $("#fdColor1").val(),
                            longitud1: $("#longitud1").val(),
                            abs11: $("#abs21Color").val(),
                            abs12: $("#abs22Color").val(),
                            abs13: $("#abs23Color").val(),
                            fd2: $("#fdColor2").val(),
                            longitud2: $("#longitud2").val(),
                            abs21: $("#abs21Color").val(),
                            abs22: $("#abs22Color").val(),
                            abs23: $("#abs23Color").val(),
                            fd3: $("#fdColor3").val(),
                            longitud3: $("#longitud3").val(),
                            abs31: $("#abs31Color").val(),
                            abs32: $("#abs32Color").val(),
                            abs33: $("#abs33Color").val(),
                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            $("#resultadoColor1").val(response.model.Resultado1)
                            $("#resultadoColor2").val(response.model.Resultado2)
                            $("#resultadoColor3").val(response.model.Resultado3)

                            $("#absProm1").val(response.model.Abs_promedio1)
                            $("#absProm2").val(response.model.Abs_promedio2)
                            $("#absProm3").val(response.model.Abs_promedio3)
                            $("#phpColor").val(response.model.Ph_muestra)
                            

                        }

                    });
                    break;
                default: // Default Directos
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/laboratorio/" + area + "/setDetalleMuestra",
                        data: {
                            idLote: idLote,
                            idMuestra: idMuestra,
                            resultado: $("#resDirectoDef").val(),
                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            $("#resultadoDirectoDef").val(response.model.Resultado)
                        }

                    });
                    break;
            
                }
                    break;

        case 8: //Potable
            sw = 1
            switch (parseInt($('#parametro').val())) {
                case 77: //Dureza
                case 103:
                case 251:
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/laboratorio/" + area + "/setDetalleMuestra",
                        data: {
                            idLote: idLote,
                            idMuestra: idMuestra,         
                                               
                            edta1: $("#edta1Dureza").val(),
                            ph1: $("#ph1Dureza").val(),
                            vol1: $("#vol1Dureza").val(),
                            real1: $("#real1Dureza").val(),
                            conversion1: $("#conversion1Dureza").val(),

                            edta2: $("#edta2Dureza").val(),
                            ph2: $("#ph2Dureza").val(),
                            vol2: $("#vol2Dureza").val(),
                            real2: $("#real2Dureza").val(),
                            conversion2: $("#conversion2Dureza").val(),
                            
                            edta3: $("#edta3Dureza").val(),
                            ph3: $("#ph3Dureza").val(),
                            vol3: $("#vol3Dureza").val(),
                            real3: $("#real3Dureza").val(),
                            conversion3: $("#conversion3Dureza").val(),

                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            
                            $("#resInd1Dureza").val(response.model.ResultadoVal1)
                            $("#resInd2Dureza").val(response.model.ResultadoVal2)
                            $("#resInd3Dureza").val(response.model.ResultadoVal3)

                            $("#resultadoDureza").val(response.model.Resultado)
                        }
                    });
                    break;
                case 252:
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/laboratorio/" + area + "/setDetalleMuestra",
                        data: {
                            idLote: idLote,
                            idMuestra: idMuestra,                            
                            id: $("#parametro").val(),
                            fecha: $("#fechaLote").val(),
                            durezaT: $("#durezaTDurezaDif").val(),
                            durezaC: $("#durezaCDurezaDif").val(),
                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            $("#resultadoDurezaDif").val(response.model.Resultado)
                        }
                    });

                    break;
                default:
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/laboratorio/potable/operacion",
                        data: {
                            sw: sw,
                            idDetalle: idMuestra,
                            id: $("#parametro").val(),
                            fecha: $("#fechaLote").val(),
                            dilucion: $("#dilucion1Potable").val(),
                            lectura1: $("#lectura11Potable").val(),
                            lectura2: $("#lectura21Potable").val(),
                            lectura3: $("#lectura31Potable").val(),
                            vol: $("#volM1Potable").val(),
                            promedio: $("#prom1Potable").val(),
                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            $("#resultadoPotable").val(response.resultado.toFixed(2))
                        }
                    });
                    break;
            }
            break;
        case 6: // Mb
        case 12:
        case 3:
        case 52:
            switch (parseInt($('#parametro').val())) {
                case 12:
                case 35:
                case 51: // Coliformes totales
                case 137:
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/laboratorio/micro/operacion",
                        data: {
                            idDetalle: idMuestra,
                            resultadoCol: $("#resultadoCol").val(),
                            idParametro: $('#parametro').val(),
                            D1: $('#dil1Col').val(),
                            D2: $('#dil2Col').val(),
                            D3: $('#dil3Col').val(),
                            NMP: $('#nmp1Col').val(),
                            G3: $('#todos1Col').val(),
                            G2: $('#negativos1Col').val(),
                            G1: $('#positivos1Col').val(),
                            con3: $("#con3Col").val(),
                            con2: $("#con2Col").val(),
                            con1: $("#con1Col").val(),
                            con4: $("#con4Col").val(),
                            con5: $("#con5Col").val(),
                            con6: $("#con6Col").val(),
                            con7: $("#con7Col").val(),
                            con8: $("#con8Col").val(),
                            con9: $("#con9Col").val(),

                            con10: $("#con10Col").val(),
                            con11: $("#con11Col").val(),
                            con12: $("#con12Col").val(),
                            con13: $("#con13Col").val(),
                            con14: $("#con14Col").val(),
                            con15: $("#con15Col").val(),
                            con16: $("#con16Col").val(),
                            con17: $("#con17Col").val(),
                            con18: $("#con18Col").val(),

                            pre1: $("#pre1Col").val(),
                            pre2: $("#pre2Col").val(),
                            pre3: $("#pre3Col").val(),
                            pre4: $("#pre4Col").val(),
                            pre5: $("#pre5Col").val(),
                            pre6: $("#pre6Col").val(),
                            pre7: $("#pre7Col").val(),
                            pre8: $("#pre8Col").val(),
                            pre9: $("#pre9Col").val(),

                            pre11: $("#pre10Col").val(),
                            pre22: $("#pre11Col").val(),
                            pre33: $("#pre12Col").val(),
                            pre44: $("#pre13Col").val(),
                            pre55: $("#pre14Col").val(),
                            pre66: $("#pre15Col").val(),
                            pre77: $("#pre16Col").val(),
                            pre88: $("#pre17Col").val(),
                            pre99: $("#pre18Col").val(),

                            // resultado: $('#resultadoCol').val(),
                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            // inicio metodo corto
                            alert("Registro calculado y guardado")
                            if (response.metodoCorto == 1) {
                                console.log("metodo corto hecho!");
                            } else {

                                if (response.res == 0) {
                                    $('#resultadoCol').val("< 3");
                                } else {
                                    $('#resultadoCol').val(response.res);
                                }
                                $('#nmp1Col').val(response.res)
                                $('#indicadorCol').val("");
                            }
                        }
                    });
                    break;
                case 135: //Coliformes alimentos
                case 133:
                case 132:
                case 134:

                    
                    let presuntiva1 = $("#pres124ColAli").val();
                    let presuntiva2 = $("#pres148ColAli").val();
                    let confir1 = $("#confir124ColAli").val();
                    let confir2 = $("#confir148ColAli").val();
                    if (presuntiva2 < presuntiva1)
                    {
                        alert("La presuntiva de 24hrs no puede ser mayor a la Presuntiva de 48hrs")
                    }else if (confir2 < confir1)
                    {
                        alert("La confirmativas de 24hrs no puede ser mayor a la Confirmativa de 48hrs")
                    }
                    else{

                        $.ajax({
                            type: "POST",
                            url: base_url + "/admin/laboratorio/micro/operacionColAlimentos",
                            data: {
                                idDetalle: idMuestra,
                                presuntiva1:$("#pres124ColAli").val(),
                                presuntiva2:$("#pres148ColAli").val(),
                                confirmativa1:$("#confir124ColAli").val(),
                                confirmativa2:$("#confir148ColAli").val(),
                                _token: $('input[name="_token"]').val()
                            },
                            dataType: "json",
                            success: function (response) {
                                console.log(response)
                                switch(response.parametro){
                                    case "135":
                                    case "134":
                                        if (response.resultado > 8.0){
                                            $("#resultadoColAli").val(">"+response.resultado)
                                        } else {
                                            $("#resultadoColAli").val(response.resultado)
                                        }
                                        if(response.resultado == 0){
                                            $("#resultadoColAli").val("No Detectable")
                                        } else {
                                            $("#resultadoColAli").val(response.model.Resultado)
                                        }
                                    break;
                                    case 132:
                                    case 133:
                                        if (response.resultado > 8.0){
                                            $("#resultadoColAli").val(">"+response.resultado)
                                        } else {
                                            $("#resultadoColAli").val(response.resultado)
                                        }
                                        if(response.resultado == 0){
                                            $("#resultadoColAli").val("No Detectable")
                                        } else {
                                            $("#resultadoColAli").val(response.model.Resultado)
                                        }
                                        break;
                                    default:
                                        if (response.resultado < response.limite){
                                            $("#resultadoColAli").val("<"+response.limite)
                                        } else {
                                            $("#resultadoColAli").val(response.resultado)
                                        }

                                    break;
                                }

                            }
                        });

                    }
                    break;
                case 253: //todo  ENTEROCOCO FECAL
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/laboratorio/micro/operacion",
                        data: {
                            idDetalle: idMuestra,
                            resultadoCol: $("#resultadoEnt").val(),
                            idParametro: $('#parametro').val(),
                            D1: $('#endil1Ent').val(),
                            D2: $('#endil2Ent').val(),
                            D3: $('#endil3Ent').val(),
                            NMP: $('#ennmp1Ent').val(),
                            G3: $('#entodos1Ent').val(),
                            G2: $('#ennegativos1Ent').val(),
                            G1: $('#enpositivos1Ent').val(),

                            Presuntiva11: $("#enPre1Ent").val(),
                            Presuntiva12: $("#enPre2Ent").val(),
                            Presuntiva13: $("#enPre3Ent").val(),
                            Presuntiva14: $("#enPre4Ent").val(),
                            Presuntiva15: $("#enPre5Ent").val(),
                            Presuntiva16: $("#enPre6Ent").val(),
                            Presuntiva17: $("#enPre7Ent").val(),
                            Presuntiva18: $("#enPre8Ent").val(),
                            Presuntiva19: $("#enPre9Ent").val(),

                            Presuntiva21: $("#enPre12Ent").val(),
                            Presuntiva22: $("#enPre22Ent").val(),
                            Presuntiva23: $("#enPre32Ent").val(),
                            Presuntiva24: $("#enPre42Ent").val(),
                            Presuntiva25: $("#enPre52Ent").val(),
                            Presuntiva26: $("#enPre62Ent").val(),
                            Presuntiva27: $("#enPre72Ent").val(),
                            Presuntiva28: $("#enPre82Ent").val(),
                            Presuntiva29: $("#enPre92Ent").val(), 

                            Confirmativa11: $("#enCon1Ent").val(),
                            Confirmativa12: $("#enCon2Ent").val(),
                            Confirmativa13: $("#enCon3Ent").val(),
                            Confirmativa14: $("#enCon4Ent").val(),
                            Confirmativa15: $("#enCon5Ent").val(),
                            Confirmativa16: $("#enCon6Ent").val(),
                            Confirmativa17: $("#enCon7Ent").val(),
                            Confirmativa18: $("#enCon8Ent").val(),
                            Confirmativa19: $("#enCon9Ent").val(),

                            Confirmativa21: $("#enCon12Ent").val(),
                            Confirmativa22: $("#enCon22Ent").val(),
                            Confirmativa23: $("#enCon32Ent").val(),
                            Confirmativa24: $("#enCon42Ent").val(),
                            Confirmativa25: $("#enCon52Ent").val(),
                            Confirmativa26: $("#enCon62Ent").val(),
                            Confirmativa27: $("#enCon72Ent").val(),
                            Confirmativa28: $("#enCon82Ent").val(),
                            Confirmativa29: $("#enCon92Ent").val(),

                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            // inicio metodo corto
                            if (response.metodoCorto == 1) {
                                console.log("metodo corto hecho!");
                            } else {

                                if (response.res == 0) {
                                    $('#resultadoEnt').val("< 3");
                                } else {
                                    $('#resultadoEnt').val(response.res);
                                }
                                // $('#ennmp1Ent').val(response.res)
                                $('#indicadorEnt').val("");
                            }
                        }
                    });
                    break;
                case 5: //todo DEMANDA BIOQUIMICA DE OXIGENO (DBO5)
                case 71:
                    let sug = 0;
                    if (document.getElementById("sugeridoDbo").checked == true) {
                        sug = 1;
                    }
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/laboratorio/" + area + "/setDetalleMuestra",
                        data: {
                            tipo: 1,
                            idLote: idLote,
                            idParametro: $("#parametro").val(),
                            idMuestra: idMuestra,
                            Observacion: $('#observacion').val(),
                            H: $('#botellaF1Dbo').val(),
                            G: $('#od1Dbo').val(),
                            B: $('#oxiFinal1Dbo').val(),
                            A: $('#oxiInicial1Dbo').val(),
                            J: $('#phF1Dbo').val(),
                            I: $('#phIni1Dbo').val(),
                            D: $('#volDbo1Dbo').val(),
                            E: $('#dil1Dbo').val(),
                            C: $('#win1Dbo').val(),
                            OI: $('#oxigenoIncialB1').val(),
                            OF: $('#oxigenofinalB1').val(),
                            V: $('#volMuestraB1Dbo').val(),
                            OIB: $('#oxigenoIncialB1').val(),
                            OFB: $('#oxigenofinalB1').val(),
                            VB: $('#volMuestraB1').val(),
                            S: sug,
                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            if (response.tipo == 1) {
                                
                            } else {
                                
                            }
                            switch (response.tipo) {
                                case 1:
                                    $('#resultadoDbo').val(response.resultado);
                                    break;
                                case 2:
                                    $('#resultadoDboBlanco').val(response.resultado);
                                    break;
                                default:
                                    $('#resDboB').val(response.resultado);
                                    break;
                            }
                        }
                    });
                    break;
                case 70:
                    let sug2 = 0;
                    if (document.getElementById("sugeridoDboIno").checked == true) {
                        sug2 = 1;
                    }
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/laboratorio/micro/operacion",
                        data: {
                            idParametro: $("#parametro").val(),
                            idDetalle: idMuestra,
                            Observacion: $('#observacionDboIno').val(),
                            A: $('#oxiInicialIno1Dbo').val(),
                            B: $('#oxiFinalIno1Dbo').val(),
                            C: $('#volInoMuestra1Dbo').val(),
                            D: $('#oxigenoDisueltoIniIno1Dbo').val(),
                            E: $('#oxigenoDisueltoFinIno1Dbo').val(),
                            G: $('#volTotalFrascoIno1Dbo').val(),
                            H: $('#volIno1Dbo').val(),
                            I: $('#volMuestraSiemIno1Dbo').val(),
                            J: $('#porcentajeIno1Dbo').val(),
                            K: $('#volWinkerIno1Dbo').val(),
                            L: $('#noBotellaIno1Dbo').val(),
                            M: $('#noBotellaFin1Dbo').val(),
                            N: $('#phInicialIno1Dbo').val(),
                            O: $('#phFinIno1Dbo').val(),
                            P: $('#preIno1Dbo').val(),
                            S: sug2,
                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response)
                            $('#resultadoDboIno').val(response.res);
                        }
                    });
                    break;
                case 16: //todo Huevos de Helminto
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/laboratorio/micro/operacion",
                        data: {
                            idParametro: $("#parametro").val(),
                            idDetalle: idMuestra,
                            lum1: $("#lum1HH").val(),
                            na1: $("#na1HH").val(),
                            sp1: $("#sp1HH").val(),
                            tri1: $("#tri1HH").val(),
                            uni1: $("#uni1HH").val(),
                            volH1: $("#volH1HH").val(),
                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            $("#resultadoHH").val(response.res);
                        }
                    });
                    break;
                case 78:
                    var obs = $("#observacionEcoli option:selected").text();
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/laboratorio/micro/operacionEcoli",
                        data: {
                            idLote: idLote,
                            colonia: numColonia,
                            idDetalle: idMuestra,
                            indol1: $("#indol1Ecoli").val(),
                            rm1: $("#rm1Ecoli").val(),
                            vp1: $("#vp1Ecoli").val(),
                            citrato1: $("#citrato1Ecoli").val(),
                            bgn1: $("#bgn1Ecoli").val(),
                            observacion: obs,

                            indice: $("#indiceEcoli").val(),

                            indol2: $("#indol2Ecoli").val(),
                            rm2: $("#rm2Ecoli").val(),
                            vp2: $("#vp2Ecoli").val(),
                            citrato2: $("#citrato2Ecoli").val(),
                            bgn2: $("#bgn2Ecoli").val(),
                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response)
                            if (response.Resultado == 1) {
                                $("#resultadoEcoli").val("Positivo para E. coli")
                            } else {
                                $("#resultadoEcoli").val("Negativo para E. coli")
                            }
                            getCapturaLote()
                        }
                    });

                    break;
                default:
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/laboratorio/" + area + "/setDetalleMuestra",
                        data: {
                            idLote: idLote,
                            idMuestra: idMuestra,
                            resultado: $("#resDirectoDef").val(),
                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            $("#resultadoDirectoDef").val(response.model.Resultado)
                        }

                    });
                    break;
            }
            break;

        default:
            $.ajax({
                type: "POST",
                url: base_url + "/admin/laboratorio/" + area + "/setDetalleMuestra",
                data: {
                    idLote: idLote,
                    idMuestra: idMuestra,
                    resultado: $("#resDirectoDef").val(),
                    _token: $('input[name="_token"]').val()
                },
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    $("#resultadoDirectoDef").val(response.model.Resultado)
                }

            });
            break;
    }
    
    if (parseInt(idArea) != 13) {
        getCapturaLote()
    } else { 
        $("#resultadoCap".idMuestraG).val(resGa)
    }

}
let idMuestraG = 0
function getDetalleMuestra(id) {
    idMuestraG = id
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/" + area + "/getDetalleMuestra",
        data: {
            id: $("#idMuestra" + id).val(),
            idLote: idLote,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response)

            switch (parseInt(response.lote[0].Id_area)) {
                case 16: // Espectrofotometria
                case 5: // Fisicoquimicos
                    switch (parseInt(response.lote[0].Id_tecnica)) {
                        case 69:
                            $("#conPh").show();
                            $("#conPh2").show();

                            $("#conN1").hide();
                            $("#conN2").hide();
                            $("#conN3").hide();
                            break;
                        case 70:
                            $("#conPh").show();
                            $("#conPh2").show();

                            $("#conN1").hide();
                            $("#conN2").hide();
                            $("#conN3").hide();
                            break;
                        case 19:
                        case 118:
                        case 99:
                            $("#conN1").show();
                            $("#conN2").show();
                            $("#conN3").show();

                            $("#conPh").hide();
                            $("#conPh2").hide();
                            break;
                        default:
                            $("#conPh").hide();
                            $("#conPh2").hide();

                            $("#conN1").hide();
                            $("#conN2").hide();
                            $("#conN3").hide();
                            break;
                    }
                    switch (parseInt(response.lote[0].Id_tecnica)) {
                        case 152: // COT
                            $("#observacion").val(response.model.Observacion);
                            $("#abs1COT").val(response.model.Promedio);
                            $("#abs2COT").val(response.model.Promedio);
                            $("#idMuestra").val(id);
                            if (response.blanco != null) {
                                $("#blanco1COT").val(response.blanco.Resultado);
                                $("#blanco2COT").val(response.blanco.Resultado);
                            }
                            $("#b1COT").val(response.curva.B);
                            $("#m1COT").val(response.curva.M);
                            $("#r1COT").val(response.curva.R);
                            $("#b2COT").val(response.curva.B);
                            $("#m2COT").val(response.curva.M);
                            $("#rCOT2").val(response.curva.R);
                            $("#volMuestra1COT").val(response.model.Vol_muestra);
                            $("#volMuestra2COT").val(response.model.Vol_muestra);
                            $("#abs11COT").val(number_format(parseFloat(response.model.Abs1),3,'.',''));
                            $("#abs21COT").val(number_format(parseFloat(response.model.Abs2),3,'.',''));
                            $("#abs31COT").val(number_format(parseFloat(response.model.Abs3),3,'.',''));
                            $("#resultadoCOT").val(response.model.Resultado);
                            break;
                        case 113:
                            $("#observacionSulfatos").val(response.model.Observacion);
                            $("#abs1SulfatosF").val(response.model.Promedio);
                            $("#abs2SulfatosF").val(response.model.Promedio);
                            $("#blancoSulfatos1F").val(response.model.Blanco);
                            $("#blancoSulfatos2F").val(response.model.Blanco);
                            $("#b1SulfatosF").val(response.curva.B);
                            $("#m1SulfatosF").val(response.curva.M);
                            $("#r1SulfatosF").val(response.curva.R);
                            $("#b2SulfatosF").val(response.curva.B);
                            $("#m2SulfatosF").val(response.curva.M);
                            $("#r2SulfatosF").val(response.curva.R);

                            $("#fDilucion1SulfatosF").val(response.model.Vol_dilucion);
                            $("#fDilucion2SulfatosF").val(response.model.Vol_dilucion);
                            $("#volMuestra1SulfatosF").val(response.model.Vol_muestra);
                            $("#volMuestra2SulfatosF").val(response.model.Vol_muestra);
    
                            $("#abs11SulfatosF").val(number_format(parseFloat(response.model.Abs1),3,'.',''));
                            $("#abs12SulfatosF").val(number_format(parseFloat(response.model.Abs1),3,'.',''));
                            $("#abs21SulfatosF").val(number_format(parseFloat(response.model.Abs2),3,'.',''));
                            $("#abs22SulfatosF").val(number_format(parseFloat(response.model.Abs2),3,'.',''));
                            $("#abs31SulfatosF").val(number_format(parseFloat(response.model.Abs3),3,'.',''));
                            $("#abs32SulfatosF").val(number_format(parseFloat(response.model.Abs3),3,'.',''));
                            

                            $("#abs41SulfatosF").val(response.model.Abs4);
                            $("#abs42SulfatosF").val(response.model.Abs4);
                            $("#abs51SulfatosF").val(response.model.Abs5);
                            $("#abs52SulfatosF").val(response.model.Abs5);
                            $("#abs61SulfatosF").val(response.model.Abs6);
                            $("#abs62SulfatosF").val(response.model.Abs6);
                            $("#abs71SulfatosF").val(response.model.Abs7);
                            $("#abs72SulfatosF").val(response.model.Abs7);
                            $("#abs81SulfatosF").val(response.model.Abs8);
                            $("#abs82SulfatosF").val(response.model.Abs8);
                            $("#resultadoSulfatos").val(response.model.Resultado);
                            break;
                        case 95:
                            $("#observacion").val(response.model.Observacion);
                            $("#absPromEspectro1").val(parseFloat(response.model.Promedio).toFixed(3));
                            $("#absPromEspectro2").val(parseFloat(response.model.Promedio).toFixed(3));
                            $("#blancoEspectro1").val(response.model.Blanco);
                            $("#blancoEspectro2").val(response.model.Blanco);
                            $("#bEspectro1").val(response.curva.B);
                            $("#mEspectro1").val(response.curva.M);
                            $("#rEspectro1").val(response.curva.R);
                            $("#bEspectro2").val(response.curva.B);
                            $("#mEspectro2").val(response.curva.M);
                            $("#rEspectro2").val(response.curva.R);
                            $("#phIniEspectro1").val(response.model.Ph_ini);
                            $("#phFinEspectro1").val(response.model.Ph_fin);
                            if (response.model.Nitratos != null) {
                                $("#nitratosEspectro1").val(response.model.Nitratos);
                            } else {
                                $("#nitratosEspectro1").val(0);
                            }
                            if (response.model.Nitritos != null) {
                                $("#nitritosEspectro1").val(response.model.Nitritos);
                            } else {
                                $("#nitritosEspectro1").val(0);
                            }
                            if (response.model.Sulfuros != null) {
                                $("#sulfurosEspectro1").val(response.model.Sulfuros);
                            } else {
                                $("#sulfurosEspectro1").val(0);
                            }
                            $("#fDilucion1").val(response.model.Vol_dilucion);
                            $("#fDilucion2").val(response.model.Vol_dilucion);
                            $("#volMuestraEspectro1").val(response.model.Vol_muestra);
                            $("#volMuestraEspectro2").val(response.model.Vol_muestra);
                            $("#abs1Espectro1").val(number_format(parseFloat(response.model.Abs1),3,'.',''));
                            $("#abs2Espectro1").val(number_format(parseFloat(response.model.Abs2),3,'.',''));
                            $("#abs3Espectro1").val(number_format(parseFloat(response.model.Abs3),3,'.',''));

                            $("#abs1Espectro2").val(response.model.Abs4);
                            $("#abs2Espectro2").val(response.model.Abs5);
                            $("#abs3Espectro2").val(response.model.Abs6);
                            $("#resultadoEspectro").val(parseFloat(response.model.Resultado).toFixed(3));
                            break;
                        case 79:
                            $("#observacion").val(response.model.Observacion);
                            $("#absPromEspectro1").val(parseFloat(response.model.Promedio).toFixed(3));
                            $("#absPromEspectro2").val(parseFloat(response.model.Promedio).toFixed(3));
                            $("#blancoEspectro1").val(response.model.Blanco);
                            $("#blancoEspectro2").val(response.model.Blanco);
                            $("#bEspectro1").val(response.curva.B);
                            $("#mEspectro1").val(response.curva.M);
                            $("#rEspectro1").val(response.curva.R);
                            $("#bEspectro2").val(response.curva.B);
                            $("#mEspectro2").val(response.curva.M);
                            $("#rEspectro2").val(response.curva.R);
                            $("#phIniEspectro1").val(response.model.Ph_ini);
                            $("#phFinEspectro1").val(response.model.Ph_fin);
                            if (response.model.Nitratos != null) {
                                $("#nitratosEspectro1").val(response.model.Nitratos);
                            } else {
                                $("#nitratosEspectro1").val(0);
                            }
                            if (response.model.Nitritos != null) {
                                $("#nitritosEspectro1").val(response.model.Nitritos);
                            } else {
                                $("#nitritosEspectro1").val(0);
                            }
                            if (response.model.Sulfuros != null) {
                                $("#sulfurosEspectro1").val(response.model.Sulfuros);
                            } else {
                                $("#sulfurosEspectro1").val(0);
                            }
                            $("#fDilucion1").val(response.model.Vol_dilucion);
                            $("#fDilucion2").val(response.model.Vol_dilucion);
                            $("#volMuestraEspectro1").val(response.model.Vol_muestra);
                            $("#volMuestraEspectro2").val(response.model.Vol_muestra);

                            $("#abs1Espectro1").val(number_format(parseFloat(response.model.Abs1),3,'.',''));
                            $("#abs2Espectro1").val(number_format(parseFloat(response.model.Abs2),3,'.',''));
                            $("#abs3Espectro1").val(number_format(parseFloat(response.model.Abs3),3,'.',''));
                            $("#abs1Espectro2").val(number_format(parseFloat(response.model.Abs1),3,'.',''));
                            $("#abs2Espectro2").val(number_format(parseFloat(response.model.Abs2),3,'.',''));
                            $("#abs3Espectro2").val(number_format(parseFloat(response.model.Abs3),3,'.',''));
                            
                            $("#resultadoEspectro").val(parseFloat(response.model.Resultado).toFixed(3));
                            break;
                        default:
                            $("#observacion").val(response.model.Observacion);
                            $("#absPromEspectro1").val(parseFloat(response.model.Promedio).toFixed(3));
                            $("#absPromEspectro2").val(parseFloat(response.model.Promedio).toFixed(3));
                            $("#blancoEspectro1").val(response.model.Blanco);
                            $("#blancoEspectro2").val(response.model.Blanco);
                            $("#bEspectro1").val(response.curva.B);
                            $("#mEspectro1").val(response.curva.M);
                            $("#rEspectro1").val(response.curva.R);
                            $("#bEspectro2").val(response.curva.B);
                            $("#mEspectro2").val(response.curva.M);
                            $("#rEspectro2").val(response.curva.R);
                            $("#phIniEspectro1").val(response.model.Ph_ini);
                            $("#phFinEspectro1").val(response.model.Ph_fin);
                            if (response.model.Nitratos != null) {
                                $("#nitratosEspectro1").val(response.model.Nitratos);
                            } else {
                                $("#nitratosEspectro1").val(0);
                            }
                            if (response.model.Nitritos != null) {
                                $("#nitritosEspectro1").val(response.model.Nitritos);
                            } else {
                                $("#nitritosEspectro1").val(0);
                            }
                            if (response.model.Sulfuros != null) {
                                $("#sulfurosEspectro1").val(response.model.Sulfuros);
                            } else {
                                $("#sulfurosEspectro1").val(0);
                            }
                            $("#fDilucionEspectro1").val(response.model.Vol_dilucion);
                            $("#fDilucionEspectro2").val(response.model.Vol_dilucion);
                            $("#volMuestraEspectro1").val(response.model.Vol_muestra);
                            $("#volMuestraEspectro2").val(response.model.Vol_muestra);
                            $("#abs1Espectro1").val(number_format(parseFloat(response.model.Abs1),3,'.',''));
                            $("#abs2Espectro1").val(number_format(parseFloat(response.model.Abs2),3,'.',''));
                            $("#abs3Espectro1").val(number_format(parseFloat(response.model.Abs3),3,'.',''));
                            $("#abs1Espectro2").val(number_format(parseFloat(response.model.Abs1),3,'.',''));
                            $("#abs2Espectro2").val(number_format(parseFloat(response.model.Abs2),3,'.',''));
                            $("#abs3Espectro2").val(number_format(parseFloat(response.model.Abs3),3,'.',''));

                            $("#resultadoEspectro").val(parseFloat(response.model.Resultado).toFixed(3));
                            break;
                    }
                    break;
                case 13://G&A
                    $("#pGA").val(response.model.Matraz);
                    $("#resultadoGA").val(response.model.Resultado);
                    $("#hGA1").val(response.model.M_final);
                    $("#jGA1").val(response.model.M_inicial1);
                    $("#kGA1").val(response.model.M_inicial2);
                    $("#cGA1").val(response.model.M_inicial3);
                    $("#lGA1").val(response.model.Ph);
                    $("#iGA1").val(response.model.Vol_muestra);
                    if (response.model.Id_control != 5) {
                        $("#gGA1").val(response.blanco.Resultado);
                        $("#gGA2").val(response.blanco.Resultado);
                    } else {
                        $("#gGA1").val(response.model.Blanco);
                        $("#gGA2").val(response.model.Blanco);
                    }
                    $("#eGA1").val(response.model.F_conversion);
                    $("#observacionGA").val(response.model.Observacion);


                    $("#hGA2").val(response.model.M_final);
                    $("#jGA2").val(response.model.M_inicial1);
                    $("#kGA2").val(response.model.M_inicial2);
                    $("#cGA2").val(response.model.M_inicial3);
                    $("#lGA2").val(response.model.Ph);
                    $("#iGA2").val(response.model.Vol_muestra);
                    $("#eGA2").val(response.model.F_conversion);
                    break;
                case 15://Solidos
                    $("#muestraCapturaSolidos").val(response.model.Codigo)
                    if (response.model.Id_parametro == 4) {
                        document.getElementById('titulomasa1Solidos').innerHTML = 'Masa 2'
                        document.getElementById('titulomasa2Solidos').innerHTML = 'Masa 6'

                        document.getElementById('pscmS1').innerHTML = 'Masa constante c/muestra 1'
                        document.getElementById('pscmS2').innerHTML = 'Masa constante c/muestra 2'
                        document.getElementById('pcS1').innerHTML = 'Masa constante 1'
                        document.getElementById('pcS2').innerHTML = 'Masa constante 2'
                        document.getElementById('tituloCrisol').innerHTML = 'Crisol'
                    } else if (response.model.Id_parametro == 90) {
                        document.getElementById('titulomasa1Solidos').innerHTML = 'Masa 1'
                        document.getElementById('titulomasa2Solidos').innerHTML = 'Masa 3'

                        document.getElementById('pscmS1').innerHTML = 'Peso constante c/muestra 1'
                        document.getElementById('pscmS2').innerHTML = 'Peso constante c/muestra 2'
                        document.getElementById('pcS1').innerHTML = 'Peso constante 1'
                        document.getElementById('pcS2').innerHTML = 'Peso constante 2'
                        document.getElementById('tituloCrisol').innerHTML = 'Capsula'
                    } else if (response.model.Id_parametro == 46) {
                        document.getElementById('titulomasa1Solidos').innerHTML = 'Masa 7'
                        document.getElementById('titulomasa2Solidos').innerHTML = 'Masa 6'
                        document.getElementById('pscmS1').innerHTML = 'Masa constante  c/muestra 1'
                        document.getElementById('pscmS2').innerHTML = 'Masa constante  c/muestra 2'
                        document.getElementById('pcS1').innerHTML = 'Masa constante Calcinado 1'
                        document.getElementById('pcS2').innerHTML = 'Masa constante Calcinado 2'
                        document.getElementById('tituloCrisol').innerHTML = 'Crisol'
                    } else if (response.model.Id_parametro == 48) {
                        document.getElementById('titulomasa1Solidos').innerHTML = 'Masa 4'
                        document.getElementById('titulomasa2Solidos').innerHTML = 'Masa 3'

                        document.getElementById('pscmS1').innerHTML = 'Peso constante c/muestra 1'
                        document.getElementById('pscmS2').innerHTML = 'Peso constante c/muestra 2'
                        document.getElementById('pcS1').innerHTML = 'Peso constante Calcinado 1'
                        document.getElementById('pcS2').innerHTML = 'Peso constante Calcinado 2'
                        document.getElementById('tituloCrisol').innerHTML = 'Capsula'
                    } else if (response.model.Id_parametro == 112) {
                        document.getElementById('titulomasa1Solidos').innerHTML = 'Peso B'
                        document.getElementById('titulomasa2Solidos').innerHTML = 'Peso A'

                        document.getElementById('pscmS1').innerHTML = 'Peso constante c/muestra 1'
                        document.getElementById('pscmS2').innerHTML = 'Peso constante c/muestra 2'
                        document.getElementById('pcS1').innerHTML = 'Peso constante 1'
                        document.getElementById('pcS2').innerHTML = 'Peso constante 2'
                        document.getElementById('tituloCrisol').innerHTML = 'Crisol'
                    } 
                    else {
                        document.getElementById('titulomasa1Solidos').innerHTML = 'Masa B'
                        document.getElementById('titulomasa2Solidos').innerHTML = 'Masa A'
                    }
                    switch (parseInt(response.model.Id_parametro)) {
                        case 3: // Directos
                            $("#resultadoModalSolidosDir").val(response.model.Resultado)
                            $("#inmhoffSolidosDir").val(response.model.Inmhoff)
                            $("#temperaturaLlegadaSolidosDir").val(response.model.Temp_muestraLlegada)
                            $("#temperaturaAnalizadaSolidosDir").val(response.model.Temp_muestraAnalizada)
                            $("#resultadoSolidosDir").val(response.model.Resultado)
                            $("#folioSolidosDir").val(response.model.Codigo)
                            break;
                        case 47: // Por diferencia
                        case 44:
                        case 45:
                        case 43:
                            $("#nomParametro1SolidosDif").val(response.nom1);
                            $("#val11SolidosDif").val(response.dif1.Resultado);
                            $("#nomParametro2SolidosDif").val(response.nom2);
                            $("#val21SolidosDif").val(response.dif2.Resultado);
                            let res = (response.dif1.Resultado) - (response.dif2.Resultado);
                            $("#preResDifSolidosDif").val(res);
                            $("#resultadoSolidosDif").val(response.model.Resultado);
                            $("#observacionSolidosDif").val(response.model.Observacion);
                            break; 
                        case 88:
                            $("#nomParametro1SolidosDif").val(response.nom1);
                            $("#val11SolidosDif").val(response.dif1.Resultado);
                            $("#nomParametro2SolidosDif").val(response.nom2);
                            $("#val21SolidosDif").val(parseFloat(response.dif2.Resultado).toFixed(2));
                            let res2 = (response.dif1.Resultado) - (parseFloat(response.dif2.Resultado).toFixed(2));
                            $("#preResDifSolidosDif").val(res2.toFixed(2));
                            $("#resultadoSolidosDif").val(response.model.Resultado);
                            $("#observacionSolidosDif").val(response.model.Observacion);
                            $("#conductividadDifSolidosDif").val(response.conductividad);
                            break;
                        case 46:
                            $("#conducSolidosDef").val(response.conductividad);
                            $("#m11Solidos").val(number_format(parseFloat(response.model.Masa1),4));
                            $("#m12Solidos").val(number_format(parseFloat(response.model.Masa1),4));
                            $("#m21Solidos").val(number_format(parseFloat(response.dif1.Masa2),4));
                            $("#m22Solidos").val(number_format(parseFloat(response.dif1.Masa2),4));
                            $("#pcm11Solidos").val(number_format(parseFloat(response.dif1.Peso_constante1),4));
                            $("#pcm12Solidos").val(number_format(parseFloat(response.dif1.Peso_constante1),4));
                            $("#pcm21Solidos").val(number_format(parseFloat(response.dif1.Peso_constante2),4));
                            $("#pcm22Solidos").val(number_format(parseFloat(response.dif1.Peso_constante2),4));
                            $("#pc1Solidos").val(number_format(parseFloat(response.model.Peso_muestra1),4));
                            $("#pc2Solidos").val(number_format(parseFloat(response.model.Peso_muestra1),4));
                            $("#pc21Solidos").val(number_format(parseFloat(response.model.Peso_muestra2),4));
                            $("#pc22Solidos").val(number_format(parseFloat(response.model.Peso_muestra2),4));
                            $("#v1Solidos").val(response.dif1.Vol_muestra);
                            $("#v2Solidos").val(response.dif1.Vol_muestra);
                            $("#f1Solidos").val(response.model.Factor_conversion);
                            $("#f1Solidos").val(response.model.Factor_conversion);

                            $("#crisolSolidos").val(response.dif1.Crisol);
                            $("#resultadoSolidos").val(response.model.Resultado);
                            $("#observacionSolidos").val(response.model.Observacion);
                            break;
                        case 48:
                            $("#conducSolidosDef").val(response.conductividad);
                            $("#m11Solidos").val(number_format(parseFloat(response.model.Masa1),4));
                            $("#m12Solidos").val(number_format(parseFloat(response.model.Masa1),4));
                            $("#m21Solidos").val(number_format(parseFloat(response.dif1.Masa2),4));
                            $("#m22Solidos").val(number_format(parseFloat(response.dif1.Masa2),4));
                            $("#pcm11Solidos").val(number_format(parseFloat(response.dif1.Peso_constante1),4));
                            $("#pcm12Solidos").val(number_format(parseFloat(response.dif1.Peso_constante1),4));
                            $("#pcm21Solidos").val(number_format(parseFloat(response.dif1.Peso_constante2),4));
                            $("#pcm22Solidos").val(number_format(parseFloat(response.dif1.Peso_constante2),4));
                            $("#pc1Solidos").val(number_format(parseFloat(response.model.Peso_muestra1),4));
                            $("#pc2Solidos").val(number_format(parseFloat(response.model.Peso_muestra1),4));
                            $("#pc21Solidos").val(number_format(parseFloat(response.model.Peso_muestra2),4));
                            $("#pc22Solidos").val(number_format(parseFloat(response.model.Peso_muestra2),4));
                            $("#v1Solidos").val(response.dif1.Vol_muestra);
                            $("#v2Solidos").val(response.dif1.Vol_muestra);
                            $("#f1Solidos").val(response.model.Factor_conversion);
                            $("#f1Solidos").val(response.model.Factor_conversion);

                            $("#crisolSolidos").val(response.dif1.Crisol);
                            $("#resultadoSolidos").val(response.model.Resultado);
                            $("#observacionSolidos").val(response.model.Observacion);
                            break;
                        default: // Default
                            $("#conducSolidosDef").val(response.conductividad);
                            $("#m11Solidos").val(number_format(parseFloat(response.model.Masa1),4));
                            $("#m12Solidos").val(number_format(parseFloat(response.model.Masa1),4));
                            $("#m21Solidos").val(number_format(parseFloat(response.model.Masa2),4));
                            $("#m22Solidos").val(number_format(parseFloat(response.model.Masa2),4));
                            $("#pcm11Solidos").val(number_format(parseFloat(response.model.Peso_constante1),4));
                            $("#pcm12Solidos").val(number_format(parseFloat(response.model.Peso_constante1),4));
                            $("#pcm21Solidos").val(number_format(parseFloat(response.model.Peso_constante2),4));
                            $("#pcm22Solidos").val(number_format(parseFloat(response.model.Peso_constante2),4));
                            $("#pc1Solidos").val(number_format(parseFloat(response.model.Peso_muestra1),4));
                            $("#pc2Solidos").val(number_format(parseFloat(response.model.Peso_muestra1),4));
                            $("#pc21Solidos").val(number_format(parseFloat(response.model.Peso_muestra2),4));
                            $("#pc22Solidos").val(number_format(parseFloat(response.model.Peso_muestra2),4));
                            $("#v1Solidos").val(response.model.Vol_muestra);
                            $("#v2Solidos").val(response.model.Vol_muestra);
                            $("#f1Solidos").val(response.model.Factor_conversion);
                            $("#f1Solidos").val(response.model.Factor_conversion);

                            $("#crisolSolidos").val(response.model.Crisol);
                            $("#resultadoSolidos").val(response.model.Resultado);
                            $("#observacionSolidos").val(response.model.Observacion);
                            break;
                    }
                case 14: // Volumetria
                    switch (parseInt(response.model.Id_parametro)) {
                        case 33:
                        case 64:
                        case 119:
                        case 218:
                            if (response.model.Resultado != null) {
                                $("#cloroA1Vol").val(response.model.Vol_muestra);
                                $("#cloroE1Vol").val(response.model.Ml_muestra);
                                $("#cloroH1Vol").val(response.model.Ph_final);
                                $("#cloroG1Vol").val(response.model.Ph_inicial);
                                $("#cloroB1Vol").val(response.valoracion.Blanco);
                                $("#cloroC1Vol").val(response.valoracion.Resultado);
                                $("#cloroD1Vol").val(response.model.Factor_conversion);
                                $("#resultadoCloroVol").val(response.model.Resultado);
                                $("#observacionCloroVol").val(response.model.Observacion);
                            } else {
                                $("#cloroB1Vol").val(response.valoracion.Blanco);
                                $("#cloroC1Vol").val(response.valoracion.Resultado);
                            }
                            break;
                        case 6: // Dqo
                        case 161:
                            if (response.valoracion == "") { // Tubo sellado
                                $("#b1").val(response.curva.B);
                                $("#m1").val(response.curva.M);
                                $("#r1").val(response.curva.R);
                                $("#b2").val(response.curva.B);
                                $("#m2").val(response.curva.M);
                                $("#r2").val(response.curva.R);
                                $("#observacion").val(response.model.Observacion);
                                $("#abs1").val(response.model.Promedio);
                                $("#abs2").val(response.model.Promedio);
                                $("#idMuestra").val(idDetalle);
                                $("#blanco1").val(response.model.Blanco);
                                $("#blanco2").val(response.model.Blanco);
                                $("#fDilucion1").val(response.model.Vol_dilucion);
                                $("#fDilucion2").val(response.model.Vol_dilucion);
                                $("#volMuestra1").val(response.model.Vol_muestra);
                                $("#volMuestra2").val(response.model.Vol_muestra);
                                $("#abs11").val(response.model.Abs1);
                                $("#abs21").val(response.model.Abs2);
                                $("#abs31").val(response.model.Abs3);
                                $("#abs12").val(response.model.Abs1);
                                $("#abs22").val(response.model.Abs2);
                                $("#abs32").val(response.model.Abs3);
                                $("#resultadoDqoVol").val(number_format(response.model.Resultado.toFixed(2),2,'.',''));

                                console.log("Tubo sellado");
                            } else {
                                $("#tituladoDqo1DqoVol").val('');
                                $("#MolaridadDqo1DqoVol").val('');
                                $("#blancoDqo1DqoVol").val('');
                                $("#factorDqo1DqoVol").val('');
                                $("#volDqo1DqoVol").val('');
                                $("#resultadoDqoVol").val('');
                                $("#observacionDqoVol").val('');
                                if (response.model.Resultado != null) {
                                    $("#tituladoDqo1DqoVol").val(response.model.Titulo_muestra);
                                    $("#MolaridadDqo1DqoVol").val(response.valoracion.Resultado);
                                    $("#blancoDqo1DqoVol").val(response.valoracion.Blanco);
                                    $("#factorDqo1DqoVol").val(response.model.Equivalencia);
                                    $("#volDqo1DqoVol").val(response.model.Vol_muestra);
                                    $("#resultadoDqoVol").val(number_format(response.model.Resultado.toFixed(2),2,'.',''));
                                    $("#observacionDqoVol").val(response.model.Observacion);


                                } else {
                                    $("#MolaridadDqo1DqoVol").val(response.valoracion.Resultado);
                                    $("#blancoDqo1DqoVol").val(response.valoracion.Blanco);
                                }
                                console.log("Tubo Reflujo");
                            }
                            break;
                        case 9: // Nitrogeno
                        case 287:
                        case 10:
                        case 11:
                            if (response.model.Resultado != null) {
                                $("#tituladosNitro1Vol").val(response.model.Titulado_muestra);
                                $("#blancoNitro1Vol").val(response.model.Titulado_blanco);
                                $("#molaridadNitro1Vol").val(response.model.Molaridad);
                                // $("#factorNitro1Vol").val(response.model.Factor_equivalencia);
                                //$("#conversion1Vol").val(response.model.Factor_conversion);
                                $("#volNitro1Vol").val(response.model.Vol_muestra);
                                $("#observacionNitrogenoVol").val(response.model.Observacion);
                                $("#resultadoNitrogenoVol").val(response.model.Resultado);
                            } else {
                                $("#blancoNitro1Vol").val(response.valoracion.Blanco);
                                $("#molaridadNitro1Vol").val(response.valoracion.Resultado);
                            }

                            break;
                        case 108:// Nitrogeno Amon
                            if (response.model.Resultado != null) {
                                $("#factor1ENitrogenoEVol").val(response.model.Titulado_muestra);
                                $("#concentracion1ENitrogenoEVol").val(response.valoracion.Blanco);
                                $("#volAñadidoStd1ENitrogenoEVol").val(response.valoracion.Resultado);
                                // $("#factor1ENitrogenoEVol").val(response.model.Factor_equivalencia);
                                //$("#concentracion1ENitrogenoEVol").val(response.model.Factor_conversion);
                                $("#VolAñadidoMuestra1ENitrogenoEVol").val(response.model.Vol_muestra);
                                $("#volumenMuestraE1NitrogenoEVol").val(response.model.Vol_muestra);
                                $("#observacionNitrogenoEVol").val(response.model.Observacion);
                                $("#resultadoNitrogenoEVol").val(response.model.Resultado);
                            } else {
                                // $("#blancoNitro1Vol").val(response.valoracion.Blanco);
                                $("#molaridadNitro1Vol").val(response.valoracion.Resultado);
                            }
                            break;
                        case 28:
                        case 29:      
                            document.getElementById('tituloModalAlcalinidad').innerHTML = 'Captura de resultados Alcalinida'
                            document.getElementById('tituloNormalidadModal').innerHTML = 'Normalidad aido sulfúrico'

                                $("#tituladoAlc1Vol").val(response.model.Titulados);
                                $("#phMuestraAlc1Vol").val(response.model.Ph_muestra);
                                $("#volMuestraAlc1Vol").val(response.model.Vol_muestra);
                                $("#normalidadAlc1Vol").val(response.valoracion.Resultado);
                                $("#conversionAlc1Vol").val(response.model.Factor_conversion);
                                $("#observacionAlcalinidadVol").val(response.model.Observacion);
                                $("#resultadoAlcalinidad").val(response.model.Resultado);
                            break;
                        case 27:
                            document.getElementById('tituloModalAlcalinidad').innerHTML = 'Captura de resultados Acidez'
                            document.getElementById('tituloNormalidadModal').innerHTML = 'Normalidad NaOH '

                            $("#tituladoAlc1Vol").val(response.model.Titulados);
                            $("#phMuestraAlc1Vol").val(response.model.Ph_muestra);
                            $("#volMuestraAlc1Vol").val(response.model.Vol_muestra);
                            $("#normalidadAlc1Vol").val(response.valoracion.Resultado);
                            $("#conversionAlc1Vol").val(response.model.Factor_conversion);
                            $("#observacionAlcalinidadVol").val(response.model.Observacion);
                            $("#resultadoAlcalinidad").val(response.model.Resultado);
                            break;
                        case 30:
                            $("#observacionAlcalinidadToVol").val(response.model.Observacion);
                            $("#resultadoAlcalinidadTo").val(response.model.Resultado);
                            $("#resFeno").val(response.dif1.Resultado);
                            $("#resAnaranjado").val(response.dif2.Resultado);
                        break;
                        default: // Default Directos
                            // tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button '+status+' type="button" class="btn btn-'+color+'" onclick="getDetalleMuestra(' + item.Id_detalle + ',1);" data-toggle="modal" data-target="#modalCapturaSolidos">Capturar</button>';
                            break;
                    }
                    break;
                case 7:// Campo
                case 19: //Directos
                    switch (parseInt(response.model.Id_parametro)) {
                        case 14: // Ph
                        case 67:
                        case 110:
                            $("#observacionDirecto").val(response.model.Observacion)
                            $("#lecturaUno1Directo").val(response.model.Lectura1)
                            $("#lecturaDos1Directo").val(response.model.Lectura2)
                            $("#lecturaTres1Directo").val(response.model.Lectura3)
                            $("#temperatura1Directo").val(response.model.Temperatura)
                            $("#resultadoDirecto").val(response.model.Resultado)
                            $("#phCampoCompuesto").val(response.phCampo)
                            break;
                        case 218: // Cloruros
                        case 119:
                            $("#dilucionCloro1DirectoClo").val(response.model.Factor_dilucion)
                            $("#observacionDirectoClo").val(response.model.Observacion)
                            $("#volumenCloro1DirectoClo").val(response.model.Vol_muestra);
                            $("#lecturaUnoCloro1DirectoClo").val(response.model.Lectura1);
                            $("#lecturaDosCloro1DirectoClo").val(response.model.Lectura2);
                            $("#lecturaTresCloro1DirectoClo").val(response.model.Lectura3);
                            $("#promedioCloro1DirectoClo").val(response.model.Promedio);
                            $("#resultadoDirectoClo").val(response.model.Resultado);
                            break;
                        case 97: // temperatura
                        case 33:
                            $("#observacionDirectoTemp").val(response.model.Observacion)
                            $("#lecturaUno1TDirectoTemp").val(response.model.Lectura1)
                            $("#lecturaDos1TDirectoTemp").val(response.model.Lectura2)
                            $("#lecturaTres1TDirectoTemp").val(response.model.Lectura3)
                            $("#resultadoDirectoTemp").val(response.model.Resultado)
                            break;
                        case 66:
                        case 65:
                        case 120:
                        case 365:
                        case 372:
                        case 370:
                            $("#observacionDirectoColor").val(response.model.Observacion)
                            $("#aparente1DirectoColor").val(response.model.Color_a)
                            $("#verdadero1DirectoColor").val(response.model.Color_v)
                            $("#dilusion1DirectoColor").val(response.model.Factor_dilucion)
                            $("#volumen1DirectoColor").val(response.model.Vol_muestra)
                            $("#ph1DirectoColor").val(response.model.Ph)
                            $("#factor1DirectoColor").val(response.model.Factor_correcion)
                            $("#resultadoDirectoColor").val(response.model.Resultado)
                            break;
                        case 58://turbiedad
                        case 89:
                        case 98:
                        case 115:
                            $("#observacionDirectoTur").val(response.model.Observacion)
                            $("#dilusionTurb1DirectoTur").val(response.model.Factor_dilucion);
                            $("#valumenTurb1DirectoTur").val(response.model.Vol_muestra);
                            $("#lecturaUnoTurb1DirectoTur").val(response.model.Lectura1);
                            $("#lecturaDosTurb1DirectoTur").val(response.model.Lectura2);
                            $("#lecturaTresTurb1DirectoTur").val(response.model.Lectura3);
                            $("#promedioTurb1DirectoTur").val(response.model.Promedio);
                            $("#resultadoDirectoTur").val(response.model.Resultado);
                            break;
                        case 130:
                        case 261:
                            if (response.model.Resultado != null) {
                                $("#resultadoDirectoDef").val(response.model.Resultado)    
                                $("#resDirectoDef").val(response.model.Lectura1)    
                            } else {
                                $("#resultadoDirectoDef").val("")     
                                $("#resDirectoDef").val(response.model2.Resultado)    
                            }
                            
                            break;
                        // case 365:
                        // case 372:
                        // case 370:
                        //     $("#colorResDir").val(response.model.Resultado)
                        //     $("#resultadoColorDir").val(response.model.Resultado)
                        //     $("#volColDir").val(response.model.Vol_muestra)
                        //     $("#phColorDir").val(response.model.Ph)
                        //     break;
                        case 102:
                            $("#volColor").val(response.model.Vol_muestra)
                            $("#fdColor1").val(response.model.Fd1)
                            $("#longitud1").val(response.model.Longitud1)
                            $("#abs1Color").val(response.model.Abs1_436)
                            $("#abs2Color").val(response.model.Abs2_436)
                            $("#abs3Color").val(response.model.Abs3_436)
                            $("#fdColor2").val(response.model.Fd2)
                            $("#longitud2").val(response.model.Longitud2)
                            $("#abs21Color").val(response.model.Abs1_525)
                            $("#abs22Color").val(response.model.Abs2_525)
                            $("#abs23Color").val(response.model.Abs3_525)
                            $("#fdColor3").val(response.model.Fd3)
                            $("#longitud3").val(response.model.Longitud3)
                            $("#abs31Color").val(response.model.Abs1_620)
                            $("#abs32Color").val(response.model.Abs2_620)
                            $("#abs33Color").val(response.model.Abs3_620)
                            $("#resultadoColor1").val(response.model.Resultado1)
                            $("#resultadoColor2").val(response.model.Resultado2)
                            $("#resultadoColor3").val(response.model.Resultado3)

                            $("#absProm1").val(response.model.Abs_promedio1)
                            $("#absProm2").val(response.model.Abs_promedio2)
                            $("#absProm3").val(response.model.Abs_promedio3)
                            $("#phpColor").val(response.model.Ph_muestra)

                            $("#observacionColorDir1").val(response.model.Observacion1)
                            $("#observacionColorDir2").val(response.model.Observacion2)
                            $("#observacionColorDir3").val(response.model.Observacion3)
                            
                            break;
                        default: // Default Directos
                            $("#observacionDirectoDef").val(response.model.Observacion);
                            $("#resultadoDirectoDef").val(response.model.Resultado);
                            $("#resDirectoDef").val(response.model.Resultado);
                            break;
                    }
                    break;
                case 8: //Potable
                console.log("Entro a obtener datos Potable")
                    switch (parseInt(response.lote[0].Id_tecnica)) {
                        case 77: //Dureza
                        case 251:

                        $(".durSec2").hide()

                        $("#edta1Dureza").val(response.model2.EdtaVal1);
                        $("#ph1Dureza").val(response.model2.Ph_muestraVal1);
                        $("#vol1Dureza").val(response.model2.Vol_muestraVal1);
                        $("#real1Dureza").val(response.valoracion.Resultado);
                        $("#conversion1Dureza").val(response.model2.Factor_conversionVal1);
                        $("#resInd1Dureza").val(response.model2.ResultadoVal1);

                        

                        $("#resultadoDureza").val(response.model2.Resultado);
                        break;
                        case 103:

                        $("#edta1Dureza").val();
                        $("#ph1Dureza").val();
                        $("#vol1Dureza").val();
                        $("#real1Dureza").val();
                        $("#conversion1Dureza").val();
                        $("#resInd1Dureza").val();
                        $("#edta2Dureza").val();
                        $("#ph2Dureza").val();
                        $("#vol2Dureza").val();
                        $("#real2Dureza").val()  
                        $("#conversion2Dureza").val();
                        $("#resInd2Dureza").val();
                        $("#edta3Dureza").val();
                        $("#ph3Dureza").val();
                        $("#vol3Dureza").val();
                        $("#real3Dureza").val();
                        $("#conversion3Dureza").val();
                        $("#resInd3Dureza").val();
                        $("#resultadoDureza").val();

                            $(".durSec2").show()
                            console.log("Entro a obtener datos dureza")
                            $("#edta1Dureza").val(response.model2.EdtaVal1);
                            $("#ph1Dureza").val(response.model2.Ph_muestraVal1);
                            $("#vol1Dureza").val(response.model2.Vol_muestraVal1);
                            $("#real1Dureza").val(response.valoracion.Resultado);
                            $("#conversion1Dureza").val(response.model2.Factor_conversionVal1);
                            $("#resInd1Dureza").val(response.model2.ResultadoVal1);

                            $("#edta2Dureza").val(response.model2.EdtaVal2);
                            $("#ph2Dureza").val(response.model2.Ph_muestraVal2);
                            $("#vol2Dureza").val(response.model2.Vol_muestraVal2);
                            $("#real2Dureza").val(response.valoracion.Resultado); 
                            $("#conversion2Dureza").val(response.model2.Factor_conversionVal2);
                            $("#resInd2Dureza").val(response.model2.ResultadoVal2);
 
                            $("#edta3Dureza").val(response.model2.EdtaVal3);
                            $("#ph3Dureza").val(response.model2.Ph_muestraVal3);
                            $("#vol3Dureza").val(response.model2.Vol_muestraVal3);
                            $("#real3Dureza").val(response.valoracion.Resultado);
                            $("#conversion3Dureza").val(response.model2.Factor_conversionVal3);
                            $("#resInd3Dureza").val(response.model2.ResultadoVal3);

                            $("#resultadoDureza").val(response.model2.Resultado);
                            break;
                        case 252:
                            $("#resultadoDurezaDif").val(response.model2.Resultado);
                            $("#durezaTDurezaDif").val(response.d2.Resultado); 
                            $("#durezaCDurezaDif").val(response.d1.Resultado);
                            break;
                        default:

                            break;
                    }
                    break;
                case 6: // Mb
                case 12:
                case 3:
                    switch (parseInt(response.model.Id_parametro)) {
                        case 12:
                        case 35:
                        case 51: // Coliformes totales
                        case 137: 
                        console.log("Ocultando seccion")
                        
                        $("#con10Col").attr('hidden',true)
                        $("#con11Col").attr('hidden',true)
                        $("#con12Col").attr('hidden',true)
                        $("#con13Col").attr('hidden',true)
                        $("#con14Col").attr('hidden',true)
                        $("#con15Col").attr('hidden',true)
                        $("#con16Col").attr('hidden',true)
                        $("#con17Col").attr('hidden',true)
                        $("#con18Col").attr('hidden',true)
                        document.getElementById('tituloColiformes').innerText = 'Prueba confirmativa 24hrs'
                        if (parseInt(response.model.Id_parametro) == 137) {
                            document.getElementById('tituloColiformes').innerHTML = 'Prueba confirmativa 24hrs / 48hrs'
                        }

                        switch (parseInt(response.model.Id_parametro) ) {
                            case 35:
                                $("#con10Col").attr('hidden',false)
                                $("#con11Col").attr('hidden',false)
                                $("#con12Col").attr('hidden',false)
                                $("#con13Col").attr('hidden',false)
                                $("#con14Col").attr('hidden',false)
                                $("#con15Col").attr('hidden',false)
                                $("#con16Col").attr('hidden',false)
                                $("#con17Col").attr('hidden',false)
                                $("#con18Col").attr('hidden',false)  
     
                                $("#con10Col").val(response.model.Confirmativa1);
                                $("#con11Col").val(response.model.Confirmativa2);
                                $("#con12Col").val(response.model.Confirmativa3);
                                $("#con13Col").val(response.model.Confirmativa4);
                                $("#con14Col").val(response.model.Confirmativa5);
                                $("#con15Col").val(response.model.Confirmativa6);
                                $("#con16Col").val(response.model.Confirmativa7);
                                $("#con17Col").val(response.model.Confirmativa8);
                                $("#con18Col").val(response.model.Confirmativa9);
    
                                $("#con1Col").val(response.model.Confirmativa10);
                                $("#con2Col").val(response.model.Confirmativa11);
                                $("#con3Col").val(response.model.Confirmativa12);
                                $("#con4Col").val(response.model.Confirmativa13);
                                $("#con5Col").val(response.model.Confirmativa14);
                                $("#con6Col").val(response.model.Confirmativa15);
                                $("#con7Col").val(response.model.Confirmativa16);
                                $("#con8Col").val(response.model.Confirmativa17);
                                $("#con9Col").val(response.model.Confirmativa18);
                                break;
                            case 137:
                                $("#con10Col").attr('hidden',false)
                                $("#con11Col").attr('hidden',false)
                                $("#con12Col").attr('hidden',false)
                                $("#con13Col").attr('hidden',false)
                                $("#con14Col").attr('hidden',false)
                                $("#con15Col").attr('hidden',false)
                                $("#con16Col").attr('hidden',false)
                                $("#con17Col").attr('hidden',false)
                                $("#con18Col").attr('hidden',false)  

                                $("#con10Col").val(response.model.Confirmativa10);
                                $("#con11Col").val(response.model.Confirmativa11);
                                $("#con12Col").val(response.model.Confirmativa12);
                                $("#con13Col").val(response.model.Confirmativa13);
                                $("#con14Col").val(response.model.Confirmativa14);
                                $("#con15Col").val(response.model.Confirmativa15);
                                $("#con16Col").val(response.model.Confirmativa16);
                                $("#con17Col").val(response.model.Confirmativa17);
                                $("#con18Col").val(response.model.Confirmativa18);
    
                                $("#con1Col").val(response.model.Confirmativa1);
                                $("#con2Col").val(response.model.Confirmativa2);
                                $("#con3Col").val(response.model.Confirmativa3);
                                $("#con4Col").val(response.model.Confirmativa4);
                                $("#con5Col").val(response.model.Confirmativa5);
                                $("#con6Col").val(response.model.Confirmativa6);
                                $("#con7Col").val(response.model.Confirmativa7);
                                $("#con8Col").val(response.model.Confirmativa8);
                                $("#con9Col").val(response.model.Confirmativa9);
                                break;
                            default:
                                $("#con10Col").val(response.model.Confirmativa10);
                                $("#con11Col").val(response.model.Confirmativa11);
                                $("#con12Col").val(response.model.Confirmativa12);
                                $("#con13Col").val(response.model.Confirmativa13);
                                $("#con14Col").val(response.model.Confirmativa14);
                                $("#con15Col").val(response.model.Confirmativa15);
                                $("#con16Col").val(response.model.Confirmativa16);
                                $("#con17Col").val(response.model.Confirmativa17);
                                $("#con18Col").val(response.model.Confirmativa18);
    
                                $("#con1Col").val(response.model.Confirmativa1);
                                $("#con2Col").val(response.model.Confirmativa2);
                                $("#con3Col").val(response.model.Confirmativa3);
                                $("#con4Col").val(response.model.Confirmativa4);
                                $("#con5Col").val(response.model.Confirmativa5);
                                $("#con6Col").val(response.model.Confirmativa6);
                                $("#con7Col").val(response.model.Confirmativa7);
                                $("#con8Col").val(response.model.Confirmativa8);
                                $("#con9Col").val(response.model.Confirmativa9);
                                break;
                        }

                            $("#dil1Col").val(response.model.Dilucion1);
                            $("#dil2Col").val(response.model.Dilucion2);
                            $("#dil3Col").val(response.model.Dilucion3);
                            $("#nmp1Col").val(response.model.Indice);
                            $("#todos1Col").val(response.model.Muestra_tubos);
                            $("#negativos1Col").val(response.model.Tubos_negativos);
                            $("#positivos1Col").val(response.model.Tubos_positivos);

                     
                            $("#pre1Col").val(response.model.Presuntiva1);
                            $("#pre2Col").val(response.model.Presuntiva2); 
                            $("#pre3Col").val(response.model.Presuntiva3);
                            $("#pre4Col").val(response.model.Presuntiva4);
                            $("#pre5Col").val(response.model.Presuntiva5);
                            $("#pre6Col").val(response.model.Presuntiva6);
                            $("#pre7Col").val(response.model.Presuntiva7);
                            $("#pre8Col").val(response.model.Presuntiva8);
                            $("#pre9Col").val(response.model.Presuntiva9);

                            $("#pre10Col").val(response.model.Presuntiva10);
                            $("#pre11Col").val(response.model.Presuntiva11); 
                            $("#pre12Col").val(response.model.Presuntiva12);
                            $("#pre13Col").val(response.model.Presuntiva13);
                            $("#pre14Col").val(response.model.Presuntiva14);
                            $("#pre15Col").val(response.model.Presuntiva15);
                            $("#pre16Col").val(response.model.Presuntiva16);
                            $("#pre17Col").val(response.model.Presuntiva17);
                            $("#pre18Col").val(response.model.Presuntiva18);

                            $("#resultadoCol").val(response.model.Resultado);
                            $("#observacionCol").val(response.model.Observacion); 

                            switch (parseFloat(response.model.Id_control)) {
                                case 1:
                                case 11:
                                    $("#dil2Col").attr('hidden',false)
                                    $("#dil3Col").attr('hidden',false)
                                    $("#pre4Col").attr('hidden',false)
                                    $("#pre7Col").attr('hidden',false)
                                    $("#pre5Col").attr('hidden',false)
                                    $("#pre8Col").attr('hidden',false)
                                    $("#pre6Col").attr('hidden',false)
                                    $("#pre9Col").attr('hidden',false)
                                    $("#pre13Col").attr('hidden',false)
                                    $("#pre16Col").attr('hidden',false)
                                    $("#pre14Col").attr('hidden',false)
                                    $("#pre17Col").attr('hidden',false)
                                    $("#pre15Col").attr('hidden',false)
                                    $("#pre18Col").attr('hidden',false)

                                    $("#con4Col").attr('hidden',false)
                                    $("#con7Col").attr('hidden',false)
                                    $("#con5Col").attr('hidden',false)
                                    $("#con8Col").attr('hidden',false)
                                    $("#con6Col").attr('hidden',false)
                                    $("#con9Col").attr('hidden',false)
                                    break;
                                default:
                                    $("#dil2Col").attr('hidden',true)
                                    $("#dil3Col").attr('hidden',true)
                                    $("#pre4Col").attr('hidden',true)
                                    $("#pre7Col").attr('hidden',true)
                                    $("#pre5Col").attr('hidden',true)
                                    $("#pre8Col").attr('hidden',true)
                                    $("#pre6Col").attr('hidden',true)
                                    $("#pre9Col").attr('hidden',true)
                                    $("#pre13Col").attr('hidden',true)
                                    $("#pre16Col").attr('hidden',true)
                                    $("#pre14Col").attr('hidden',true)
                                    $("#pre17Col").attr('hidden',true)
                                    $("#pre15Col").attr('hidden',true)
                                    $("#pre18Col").attr('hidden',true)

                                    $("#con4Col").attr('hidden',true)
                                    $("#con7Col").attr('hidden',true)
                                    $("#con5Col").attr('hidden',true)
                                    $("#con8Col").attr('hidden',true)
                                    $("#con6Col").attr('hidden',true)
                                    $("#con9Col").attr('hidden',true)

                                    $("#con13Col").attr('hidden',true)
                                    $("#con14Col").attr('hidden',true)
                                    $("#con15Col").attr('hidden',true)
                                    $("#con16Col").attr('hidden',true)
                                    $("#con17Col").attr('hidden',true)
                                    $("#con18Col").attr('hidden',true)
                                    break;
                            }
                            break;
                        case 135: //Coliformes alimentos
                        case 133:
                        case 132:
                        case 134:

                                $("#pres124ColAli").val(response.model.Presuntiva1)
                                $("#pres148ColAli").val(response.model.Presuntiva2)
                                $("#confir124ColAli").val(response.model.Confirmativa1)
                                $("#confir148ColAli").val(response.model.Confirmativa2)
                                 $("#resultadoColAli").val(response.model.Resultado)
                            break;
                        case 253: //todo  ENTEROCOCO FECAL
                            $("#endil1Ent").val(response.model.Dilucion1);
                            $("#endil2Ent").val(response.model.Dilucion2);
                            $("#endil3Ent").val(response.model.Dilucion3);
                            $("#ennmp1Ent").val(response.model.Indice);
                            $("#entodos1Ent").val(response.model.Muestra_tubos);
                            $("#ennegativos1Ent").val(response.model.Tubos_negativos);
                            $("#enpositivo1Ent").val(response.model.Tubos_positivos);

                            

                            $("#enCon1Ent").val(response.model.Confirmativa11);
                            $("#enCon2Ent").val(response.model.Confirmativa12);
                            $("#enCon3Ent").val(response.model.Confirmativa13);
                            $("#enCon4Ent").val(response.model.Confirmativa14);
                            $("#enCon5Ent").val(response.model.Confirmativa15);
                            $("#enCon6Ent").val(response.model.Confirmativa16);
                            $("#enCon7Ent").val(response.model.Confirmativa17);
                            $("#enCon8Ent").val(response.model.Confirmativa18);
                            $("#enCon9Ent").val(response.model.Confirmativa19);

                            $("#enCon12Ent").val(response.model.Confirmativa21);
                            $("#enCon22Ent").val(response.model.Confirmativa22);
                            $("#enCon32Ent").val(response.model.Confirmativa23);
                            $("#enCon42Ent").val(response.model.Confirmativa24);
                            $("#enCon52Ent").val(response.model.Confirmativa25);
                            $("#enCon62Ent").val(response.model.Confirmativa26);
                            $("#enCon72Ent").val(response.model.Confirmativa27);
                            $("#enCon82Ent").val(response.model.Confirmativa28);
                            $("#enCon92Ent").val(response.model.Confirmativa29);

                            $("#enPre1Ent").val(response.model.Presuntiva11);
                            $("#enPre2Ent").val(response.model.Presuntiva12);
                            $("#enPre3Ent").val(response.model.Presuntiva13);
                            $("#enPre4Ent").val(response.model.Presuntiva14);
                            $("#enPre5Ent").val(response.model.Presuntiva15);
                            $("#enPre6Ent").val(response.model.Presuntiva16);
                            $("#enPre7Ent").val(response.model.Presuntiva17);
                            $("#enPre8Ent").val(response.model.Presuntiva18);
                            $("#enPre9Ent").val(response.model.Presuntiva19);

                            $("#enPre12Ent").val(response.model.Presuntiva21);
                            $("#enPre22Ent").val(response.model.Presuntiva22);
                            $("#enPre32Ent").val(response.model.Presuntiva23);
                            $("#enPre42Ent").val(response.model.Presuntiva24);
                            $("#enPre52Ent").val(response.model.Presuntiva25);
                            $("#enPre62Ent").val(response.model.Presuntiva26);
                            $("#enPre72Ent").val(response.model.Presuntiva27);
                            $("#enPre82Ent").val(response.model.Presuntiva28);
                            $("#enPre92Ent").val(response.model.Presuntiva29);
                            

                            $("#resultadoEnt").val(response.model.Resultado);
                            $("#observacionEnt").val(response.model.Observacion);

                            switch (parseFloat(response.model.Id_control)) {
                                case 1:
                                case 11:
                                    $("#enCon4Ent").attr('hidden',false);
                                    $("#enCon5Ent").attr('hidden',false);
                                    $("#enCon6Ent").attr('hidden',false);
                                    $("#enCon7Ent").attr('hidden',false);
                                    $("#enCon8Ent").attr('hidden',false);
                                    $("#enCon9Ent").attr('hidden',false);
        
                                    $("#enCon42Ent").attr('hidden',false);
                                    $("#enCon52Ent").attr('hidden',false);
                                    $("#enCon62Ent").attr('hidden',false);
                                    $("#enCon72Ent").attr('hidden',false);
                                    $("#enCon82Ent").attr('hidden',false);
                                    $("#enCon92Ent").attr('hidden',false);
        
                                
                                    $("#enPre4Ent").attr('hidden',false);
                                    $("#enPre5Ent").attr('hidden',false);
                                    $("#enPre6Ent").attr('hidden',false);
                                    $("#enPre7Ent").attr('hidden',false);
                                    $("#enPre8Ent").attr('hidden',false);
                                    $("#enPre9Ent").attr('hidden',false);
        
                                    
                                    $("#enPre42Ent").attr('hidden',false);
                                    $("#enPre52Ent").attr('hidden',false);
                                    $("#enPre62Ent").attr('hidden',false);
                                    $("#enPre72Ent").attr('hidden',false);
                                    $("#enPre82Ent").attr('hidden',false);
                                    $("#enPre92Ent").attr('hidden',false);
                                    
                                    break;
                                default:
                                    
                                    $("#enCon4Ent").attr('hidden',true);
                                    $("#enCon5Ent").attr('hidden',true);
                                    $("#enCon6Ent").attr('hidden',true);
                                    $("#enCon7Ent").attr('hidden',true);
                                    $("#enCon8Ent").attr('hidden',true);
                                    $("#enCon9Ent").attr('hidden',true);
        
                                    $("#enCon42Ent").attr('hidden',true);
                                    $("#enCon52Ent").attr('hidden',true);
                                    $("#enCon62Ent").attr('hidden',true);
                                    $("#enCon72Ent").attr('hidden',true);
                                    $("#enCon82Ent").attr('hidden',true);
                                    $("#enCon92Ent").attr('hidden',true);
        
                                
                                    $("#enPre4Ent").attr('hidden',true);
                                    $("#enPre5Ent").attr('hidden',true);
                                    $("#enPre6Ent").attr('hidden',true);
                                    $("#enPre7Ent").attr('hidden',true);
                                    $("#enPre8Ent").attr('hidden',true);
                                    $("#enPre9Ent").attr('hidden',true);
        
                                    
                                    $("#enPre42Ent").attr('hidden',true);
                                    $("#enPre52Ent").attr('hidden',true);
                                    $("#enPre62Ent").attr('hidden',true);
                                    $("#enPre72Ent").attr('hidden',true);
                                    $("#enPre82Ent").attr('hidden',true);
                                    $("#enPre92Ent").attr('hidden',true);

                                    break;
                            }
                            break;
                        case 5: //todo DEMANDA BIOQUIMICA DE OXIGENO (DBO5)
                        case 71:
                            $('#botellaF1Dbo').val(response.model.Botella_final);
                            $('#od1Dbo').val(response.model.Botella_od);
                            $('#oxiFinal1Dbo').val(response.model.Odf);
                            $('#oxiInicial1Dbo').val(response.model.Odi);
                            $('#phF1Dbo').val(response.model.Ph_final);
                            $('#phIni1Dbo').val(response.model.Ph_inicial);
                            $('#volDbo1Dbo').val(response.model.Vol_muestra);
                            $('#dil1Dbo').val(response.model.Dilucion);
                            $('#win1Dbo').val(response.model.Vol_botella);

                            $("#observacionDbo").val(response.model.Observacion);
                            $("#resultadoDbo").val(response.model.Resultado);
                            if (response.model.Sugerido == 1) {
                                document.getElementById("sugeridoDbo").checked = true;
                            } else {
                                document.getElementById("sugeridoDbo").checked = false;
                            }
                            if (response.model2 == "NULL") {
                                $('#resultadoDbo').val("N/A");
                            } else {
                                $('#resultadoDbo').val(response.model2.Resultado);
                            }
                            break;
                        case 70:
                            $('#oxiInicialIno1Dbo').val(response.model.Oxigeno_inicial)
                            $('#oxiFinalIno1Dbo').val(response.model.Oxigeno_final)
                            $('#volInoMuestra1Dbo').val(response.model.Vol_muestra )
                            $('#oxigenoDisueltoIniIno1Dbo').val(response.model.Oxigeno_disueltoini)
                            $('#oxigenoDisueltoFinIno1Dbo').val(response.model.Oxigeno_disueltofin)
                            $('#volTotalFrascoIno1Dbo').val(response.model.Vol_total_frasco)
                            $('#volIno1Dbo').val(response.model.Vol_inoculo)
                            $('#volMuestraSiemIno1Dbo').val(response.model.Vol_muestra_siembra)
                            $('#porcentajeIno1Dbo').val(response.model.Porcentaje_dilucion )
                            $('#volWinkerIno1Dbo').val(response.model.Vol_winker)
                            $('#noBotellaIno1Dbo').val(response.model.Botella_od)
                            $('#noBotellaFin1Dbo').val(response.model.Botella_fin)
                            $('#phInicialIno1Dbo').val(response.model.Ph_inicial)
                            $('#phFinIno1Dbo').val(response.model.Ph_final)
                            $('#preIno1Dbo').val(response.model.Pre_dilucion)

                            if (response.model.Sugerido == 1) {
                                document.getElementById("sugeridoDboIno").checked = true;
                            } else {
                                document.getElementById("sugeridoDboIno").checked = false;
                            }
                            $('#resultadoDboIno').val(response.model.Resultado);
                            break;
                        case 16: //todo Huevos de Helminto
                            $("#lum1HH").val(response.model.A_alumbricoides);
                            $("#na1HH").val(response.model.H_nana);
                            $("#sp1HH").val(response.model.Taenia_sp);
                            $("#tri1HH").val(response.model.T_trichiura);
                            $("#uni1HH").val(response.model.Uncinarias);
                            $("#volH1HH").val(response.model.Vol_muestra);
                            $("#resultadoHH").val(response.model.Resultado);
                            break;
                        case 78:

                            $("#indol1Ecoli").val()
                            $("#rm1Ecoli").val()
                            $("#vp1Ecoli").val()
                            $("#citrato1Ecoli").val()
                            $("#bgn1Ecoli").val()
                            $("#indol2Ecoli").val()
                            $("#rm2Ecoli").val()
                            $("#vp2Ecoli").val()
                            $("#citrato2Ecoli").val()
                            $("#bgn2Ecoli").val()
                            $("#observacionEcoli").val()
                            $("#indiceEcoli").val()
                            $("#resultadoEcoli").val()

                            $("#indol1Ecoli").val(response.convinaciones.Indol)
                            $("#rm1Ecoli").val(response.convinaciones.Rm)
                            $("#vp1Ecoli").val(response.convinaciones.Vp)
                            $("#citrato1Ecoli").val(response.convinaciones.Citrato)
                            $("#bgn1Ecoli").val(response.convinaciones.BGN)
                            $("#indol2Ecoli").val(response.convinaciones.Indol2)
                            $("#rm2Ecoli").val(response.convinaciones.Rm2)
                            $("#vp2Ecoli").val(response.convinaciones.Vp2)
                            $("#citrato2Ecoli").val(response.convinaciones.Citrato2)
                            $("#bgn2Ecoli").val(response.convinaciones.BGN2)
                            $("#observacionEcoli").val(response.model.observacion)
                            $("#indiceEcoli").val(indice)

                            if (response.model.convinaciones == 1) {
                                $("#resultadoEcoli").val("Positivo para E. coli")
                            } else {
                                $("#resultadoEcoli").val("Negativo para E. coli")
                            }
                            break;
                        default:
                            $("#observacionDirectoDef").val(response.model.Observacion);
                            $("#resultadoDirectoDef").val(response.model.Resultado);
                            $("#resDirectoDef").val(response.model.Resultado);
                            break;
                    }
                    break;

                default:
                    $("#observacionDirectoDef").val(response.model.Observacion);
                    $("#resultadoDirectoDef").val(response.model.Resultado);
                    $("#resDirectoDef").val(response.model.Resultado);
                    break;
            }
        }
    });
}
function setFechaDefGA()
{
    $("#entradaGA1").val($("#fechaDefGA").val())
    $("#salidaGA1").val($("#fechaDefGA").val())
    $("#entradaGA2").val($("#fechaDefGA").val())
    $("#salidaGA2").val($("#fechaDefGA").val())
    $("#entradaGA3").val($("#fechaDefGA").val())
    $("#salidaGA3").val($("#fechaDefGA").val())
    $("#2entradaGA1").val($("#fechaDefGA").val())
    $("#2salidaGA1").val($("#fechaDefGA").val())
    $("#2pesadoGA1").val($("#fechaDefGA").val())
    $("#2entradaGA2").val($("#fechaDefGA").val())
    $("#2salidaGA2").val($("#fechaDefGA").val())
    $("#2pesadoGA2").val($("#fechaDefGA").val())
    $("#2entradaGA3").val($("#fechaDefGA").val())
    $("#2salidaGA3").val($("#fechaDefGA").val())
    $("#2pesadoGA3").val($("#fechaDefGA").val())
}
function setFechaDefGA2()
{
    $("#3entradaGA").val($("#fechaDefGA2").val())
    $("#3salidaGA").val($("#fechaDefGA2").val())
    $("#4entradaGA").val($("#fechaDefGA2").val())
    $("#4salidaGA").val($("#fechaDefGA2").val())
    $("#5entradaGA").val($("#fechaDefGA2").val())
    $("#5salidaGA").val($("#fechaDefGA2").val())
    
}
function getCapturaLote() {
        var icono;
    blanco = 0
    contador = 0
    let indice = new Array()
    let cont = 0
    let contitem = 0

    let tabla = document.getElementById('divCaptura');
    let tab = '';
    let clase = ''
    let dec = 2
    let estiloH = ''
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/" + area + "/getCapturaLote",
        data: {
            idLote: idLote,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            tab += '<table id="tabCaptura" class="table table-sm">';
            tab += '    <thead>';
            tab += '        <tr>';
            tab += '          <th style="width:100px">Opc</th>';
            tab += '          <th style="width:100px">Folio</th>';
            tab += '          <th style="width:100px">Norma</th>';
            tab += '          <th style="width:100px">Resultado</th>';
            tab += '          <th>Observación</th>';
            tab += '          <th style="width:100px"></th>';
            tab += '          <th hidden></th>';
            tab += '          <th></th>';
            tab += '          <th></th>';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            $.each(response.model, function (key, item) {
                
                if (item.Liberado != 1) {
                    status = "";
                    color = "success";
                    clase = "btn btn-success";
                } else {
                    status = "disabled";
                    clase = "btn btn-warning";
                    color = "warning"
                }
                tab += '<tr>';
                if (item.Id_control == 5) {
                    blanco = item.Resultado
                }

                estiloH = parseInt(item.Historial) == 1 ? 'background-color:#e5e5ff;' : ''
                console.log(estiloH) 
                switch (parseInt(response.lote[0].Id_area)) {

                    case 16: // Espectrofotometria
                    case 5: // Fisicoquimicos
                    dec = 3
                        switch (parseInt(item.Id_parametro)) {
                            case 152:
                                status = "";
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalCapturaCOT">Capturar</button>';
                                break;
                            case 113:
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalCapturaSulfatos">Capturar</button>';
                                break;
                            case 79:
                                status = "";
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalCapturaEspectro">Capturar</button>';
                                break;
                            default:
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalCapturaEspectro">Capturar</button>';
                                break;
                        }
                        break;
                    case 13: // G&A
                        tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalCapturaGA">Capturar</button>';
                        break;
                    case 15: // Solidos
                    status = ""
                        switch (parseInt(item.Id_parametro)) {
                            case 3: // Directos
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalCapturaSolidosDir">Capturar</button>';
                                break;
                            case 47: // Por diferencia
                            case 88:
                            case 44:
                            case 45:
                            case 43:
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalCapturaSolidosDif">Capturar</button>';
                                break;
                            default: // Default
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalCapturaSolidos">Capturar</button>';
                                break;
                        }
                        break;
                    case 14: // Volumetria
                        switch (parseInt(item.Id_parametro)) {
                            case 33:
                            case 64:
                            case 119:
                            case 218:
                                dec = 2
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalCloroVol">Capturar</button>';
                                break;
                            case 6: // Dqo
                            case 161:
                                idTecnica = response.aux.Tecnica
                                if (response.aux.Tecnica == 2) {
                                    tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalDqoVol">Capturar</button>';
                                } else {
                                    tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalCapturaEspectro">Capturar</button>';
                                }
                                break;
                            case 9: // Nitrogeno
                            case 287:
                            case 10:
                            case 11:
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalCapturaNitrogenoVol">Capturar</button>';
                                break;
                            case 28: // Alcalinidad
                            case 29:
                            case 27:
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalCapturaAlcalinidadVol">Capturar</button>';
                                break;
                            case 30:
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalCapturaAlcalinidadToVol">Capturar</button>';
                                break;
                            case 108:// Nitrogeno Amon
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalCapturaNitrogenoEVol">Capturar</button>';
                                break;
                            default: // Default Directos
                                // tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button '+status+' type="button" class="btn btn-'+color+'" onclick="getDetalleMuestra(' + item.Id_detalle + ',1);" data-toggle="modal" data-target="#modalCapturaSolidos">Capturar</button>';
                                break;
                        }
                        break;
                    case 7: // Campo
                    case 19: // Directos
                        switch (parseInt(item.Id_parametro)) {
                            case 14: // Ph
                            case 67:
                            case 110:
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalDirecto">Capturar</button>';
                                break;
                            case 218: // Cloruros
                            case 119:
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalDirectoClo">Capturar</button>';
                                break;
                            case 97: // temperatura
                            case 33:
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalDirectoTemp">Capturar</button>';
                                break;
                            case 66://102
                            case 65:
                            case 120:
                            case 372:
                            case 365:
                            case 370:
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalDirectoColor">Capturar</button>';
                                break;
                            case 89:
                            case 98:
                            case 115:
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalDirectoTur">Capturar</button>';
                                break;
                            case 238:
                            case 360:
                            case 241:
                            case 53:
                            case 266:
                                dec = 3
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalDirectoDef">Capturar</button>';
                                break;
                            case 131:
                                dec = 4
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalDirectoDef">Capturar</button>';
                                break;
                            case 102:
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalCapturaColorVerdadero">Capturar</button>';
                                break;
                            // case 370:
                            // case 372:
                            // case 365:
                            //     tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalDirectoColorPh">Capturar</button>';
                            //     break;
                            default: // Default Directos
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalDirectoDef">Capturar</button>';
                                break;
                        }
                        break;
                    case 8://Potable
                        switch (parseInt(item.Id_parametro)) {
                            case 77://Dureza
                            case 251:
                            case 103:
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalDureza">Capturar</button>';
                                break;
                            case 252:
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalDurezaDif">Capturar</button>';
                                break;
                            default:
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalPotable">Capturar</button>';
                                break;
                        }
                        break;
                    case 6://Mb
                    case 12://
                    case 51:
                    case 3: // Alimentos
                        switch (parseInt(item.Id_parametro)) {
                            case 12://Coliformes
                            case 35://Ecoli
                            case 51:
                            case 137: 
                            status = "";
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalCapturaCol">Capturar</button>';
                                break;
                            case 253://Enterococos
                            status = "";
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalCapturaEnt">Capturar</button>';
                                break;
                            case 5://Dbo
                            case 71:
                                if (item.Id_control == 5) {
                                    tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalCapturaDboBlanco">Capturar</button>';
                                } else {
                                    tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalCapturaDbo">Capturar</button>';
                                }
                                break;
                            case 70:
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalCapturaDboIno">Capturar</button>';
                                break;
                            case 16://HH
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalCapturaHH">Capturar</button>';
                                break;
                            case 135: //Coliformes alimentos
                            case 133:
                            case 132:
                            case 134:
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalColiformesAlimentos">Capturar</button>';
                                break;
                            case 78:
                                let temp78 = ""
                                let data = [item.Colonia1, item.Colonia2, item.Colonia3, item.Colonia4, item.Colonia5];
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '">';
                                tab += '<div class="row">'
                                tab += '<div class="col-md-12">'
                                // tab += '<button type="button" id="'+i+'" '+status+' class="'+clase+'" onclick="getDetalleColiAlimentos(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalEcoli">Capturar</button>';
                                tab += '<label>'
                                tab += "&nbsp Colonias &nbsp";
                                tab += '</label>';
                                tab += '</div>';
                                tab += '</div">';
                                

                                for (let i = 0; i < response.indice[contador]; i++) {
                                    temp78 = ""
                                    tab += '<div class="row">'
                                    tab += '<div class="col-md-12">'
                                    tab += '<button type="button" id="col' + i + '" ' + status + ' class="' + clase + '" onclick="getDetalleEcoli(' + item.Id_detalle + ',' + (i + 1) + ',' + indice[contador] + ');" data-toggle="modal" data-target="#modalEcoli">Capturar</button>';
                                    tab += '<label>'
                                    tab += "&nbsp Colonia &nbsp" + (i + 1) + '&nbsp';
                                    tab += '</label>';
                                    if (data[i] == 1) {
                                        temp78 = "Positivo para E. coli"
                                    } else {
                                        temp78 = "Negativo para E. coli"
                                    }
                                    tab += '<input type="text" id="resultColonia" value="' + temp78 + '">'
                                    tab += '</div>';
                                    tab += '</div">';
                                }
                                contador++;
                                break;
                            default:
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalDirectoDef">Capturar</button>';
                                break;
                        }
                        break;
                    default:
                        switch (parseInt(item.Id_parametro)) {
                            default:
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleMuestra(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalDirectoDef">Capturar</button>';
                                break;
                        }
                        break;
                }
                if (item.Id_control != 1) {
                    tab += '<br> <small class="text-danger">' + item.Control + '</small></td>';
                } else {
                    tab += '<br> <small class="text-info">' + item.Control + '</small></td>';
                }
                tab += '<td style="'+estiloH+'"><input disabled style="width: 150px;" value="' + item.Codigo + '"></td>';
                tab += '<td style="'+estiloH+'"><input disabled style="width: 200px;" value="' + item.Clave_norma + '"></td>';
              
                if (item.Resultado != null) {
                    let formated = number_format(parseFloat(item.Resultado), dec)
                    tab += '<td style="'+estiloH+'"><input id="resultadoCap'+item.Id_detalle+'" disabled style="width: 100px;" value="' + formated + '"></td>';
                } else {
                    tab += '<td style="'+estiloH+'"><input id="resultadoCap'+item.Id_detalle+'" disabled style="width: 80px;" value=""></td>';
                }
                if (item.Observacion != null) {
                    tab += '<td style="'+estiloH+'">' + item.Observacion + '</td>';
                } else {
                    tab += '<td style="'+estiloH+'"></td>';
                }
                tab += '<td style="'+estiloH+'"><button class="btn-warning" onclick="getHistorial('+item.Id_codigo+')" data-toggle="modal" data-target="#modalHistorial"><i class="fas fa-info"></i></button></td>';
                tab += '<td hidden>'+item.Codigo+'</td>';
                         
                if (item.Sugerido_sup == 1) {
                    icono = '<i class="fa fa-check " style="color:green; font-size: 20px;" aria-hidden="true"></i>';
                } else {
                    icono = '';
                }
                
                tab += '<td style="'+estiloH+'">' + icono + '</td>';
                if (response.img[contitem] == "") {
                    tab += '<td style="' + estiloH + '"></td>';
                }else{
                    tab += '<td style="' + estiloH + '"><img src="data:image/png;base64,' + response.img[contitem] + '" style="width:100%;height:auto;" onclick="modalImagenMuestra(\'' + response.img[contitem] + '\')"></td>';
                }
                

                tab += '</tr>';
                contitem++

            });
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;

            var t2 = $('#tabCaptura').DataTable({
                "ordering": false,
                paging: false,
                "language": {
                    "lengthMenu": "# _MENU_ por pagina",
                    "zeroRecords": "No hay datos encontrados",
                    "info": "Pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay datos encontrados",
                }
            });


            $('#tabCaptura tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                    $("#clorurosParametro").html('');
                    $("#conductividadParametro").html('');
                    $("#phParametro").html('');
                    $("#aceptacion").html('');
                }
                else {
                    t2.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                    let folioElegido = $(this).find('td:first')[0].parentElement.children[1].firstElementChild.getAttribute("value");
                    console.log(folioElegido)
                    getDetalleElegido(folioElegido);
                }
            });
            $('#tabCaptura tr').on('click', function () {
                let dato = $(this).find('td:first');
                idMuestra = dato[0].firstElementChild.value;
            });
        }
    });
}
function modalImagenMuestra(id){
    $('#modalImgFoto').modal('show')
    console.log(id)
    let img = document.getElementById('divImagen')
    img.innerHTML = '<img src="data:image/png;base64,' + id+ '" style="width:100%;height:auto;">'
}
function number_format (number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}
function setMuestraLote() {
    let muestra = document.getElementsByName("stdCkAsignar")
    let codigos = new Array();
    for (let i = 0; i < muestra.length; i++) {
        if (muestra[i].checked) {
            codigos.push(muestra[i].value);
        }
    }
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/" + area + "/setMuestraLote",
        data: {
            codigos: codigos,
            idLote: idLote,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            alert("Muestras asignadas")
            getMuestraSinAsignar()
            getLote()
        }
    });
}
function getMuestraSinAsignar() {
    let tabla = document.getElementById('devAsignarLote');
    let tab = '';
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/" + area + "/getMuestraSinAsignar",
        data: {
            idLote: idLote,
            idParametro: $("#parametro").val(),
            fecha: $("#fechaAsignar").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response)
            let estiloH  = ""
            let mensaje = ''

            $("#tipoFormulaAsignar").val(response.lote.Tipo_formula)
            $("#parametroAsignar").val(response.lote.Parametro)
            $("#fechaAnalisisAsignar").val(response.lote.Fecha)
            $("#asignadoLote").val(response.lote.Asignado)
            $("#liberadoLote").val(response.lote.Liberado)
            $("#porAsingarLote").val(response.model.length)

            tab += '<table id="tabAsignar" class="table table-sm">'
            tab += '    <thead>'
            tab += '        <tr>'
            tab += '          <th>Op</th>'
            tab += '          <th># Muestra</th> '
            tab += '          <th>Norma</th> '
            tab += '          <th>Punto muestreo</th> '
            tab += '          <th>Fecha recepción</th> '
           // tab += '          <th>Fecha Hoy</th> '
            //tab += '          <th>Diferencia</th> '
            tab += '          <th>Dias Analisis</th> '
           // tab += '          <th>Info</th> '
            tab += '        </tr>'
            tab += '    </thead>'
            tab += '    <tbody>'
            for (let i = 0; i < response.model.length; i++) {
                
                hoy= new Date(response.fechahoy[i]);
                fechaRep = new Date(response.fecha[i]);
                diasA= response.diasanalisis[i];
                diasD= Math.ceil(( hoy - fechaRep) / (1000 * 60 * 60 * 24)); 


                estiloH = response.historial[i] == 1 ? 'background-color:#e5e5ff;' : '' 

                if (parseInt(diasA) != 0  && diasA != null) 
                {
                    color='';
                    mensaje="Sin Configurar";
                    
                    if (diasD < diasA) 
                    {
                        color = 'bg-success';
                        mensaje = 'Faltan Días('+((diasD-diasA)*(-1))+')';      

                    }else if(diasD === 1)
                    {
                        color = 'bg-warning';
                        mensaje = 'En Tiempo('+diasD+')';
                    }else if(diasA < diasD)
                    {
                        color = 'bg-danger';
                        mensaje = 'Fuera de Tiempo('+diasD+')';
                    }else{
                        color ='';
                        
                    }
                } else {
                    color= '';
                }
                console.log(color)

                tab += '<tr>'
                tab += '<td><input type="checkbox" value="' + response.idCodigo[i] + '" name="stdCkAsignar" onclick="contarCheckbox()"></td>'
                tab += '<td style="' + estiloH + '">' + response.folio[i] + '</td>'
                tab += '<td style="' + estiloH + '">' + response.norma[i] + '</td>'
                tab += '<td style="' + estiloH + '">' + response.punto[i] + '</td>'
                tab += '<td style="' + estiloH + '">' + response.fecha[i] + '</td>'
                //tab += '<td style="' + estiloH + '">' + response.fechahoy[i] + '</td>'
                //tab += '<td style="' + estiloH + '">' + diasD + '</td>'
                tab += '<td class="' + color + '">' + mensaje + '</td>'
                //tab += '<td><button id="btnInfo" class="btn-info" ><i class="fas fa-info"></i></button></td>'
                tab += '</tr>'
                
          
            }

            tab += '    </tbody>'
            tab += '</table>'
            tabla.innerHTML = tab;

            //Inicializacion de tabla
            tableLote = $('#tabAsignar').DataTable({
                "ordering": false,
                paging: false,
                "language": {
                    "lengthMenu": "# _MENU_ por pagina",
                    "zeroRecords": "No hay datos encontrados",
                    "info": "Pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay datos encontrados",
                },
                dom: '<"toolbar">frtip',
            });
            $(document).on('click', '#btn-Info', function() {
               
                $('#modalAsignar').modal('hide');
                $('#modalHistorial').modal('show');
            });
        

           $('div.toolbar').html('<button onclick="setMuestraLote()" id="btnAsignarMuestra" class="btn-success"><i class="fas fa-paper-plane"></i></button> <button onclick="selecionarCkeck()" class="btn-info"><i class="fas fa-check-double"></i></button>');
        }
    });
}


function contarCheckbox(){
    let cantidadSeleccionados = $("input[name=stdCkAsignar]:checked").length;
    $('#muestrasSeleccionadas').val(cantidadSeleccionados)
}
function selecionarCkeck()
{
    allSelectCheck("stdCkAsignar")
    contarCheckbox()
}
function setLote() {

    if ($("#parametro").val() != "0" && $("#fechaLote").val() != "") {
        if (confirm("¿Estas seguro de crear el lote?")) {
            $.ajax({
                type: 'POST',
                url: base_url + "/admin/laboratorio/" + area + "/getLote",
                data: {
                    id: $("#parametro").val(),
                    fecha: $("#fechaLote").val(),
                    _token: $('input[name="_token"]').val(),
                },
                dataType: "json",
                async: false,
                success: function (response) {
                    if (response.model.length > 0) {
                        if (confirm("Ya tienes un lote en esta fecha, ¿Quieres crear otro lote?")) {
                            $.ajax({
                                type: 'POST',
                                url: base_url + "/admin/laboratorio/" + area + "/setLote",
                                data: {
                                    id: $("#parametro").val(),
                                    fecha: $("#fechaLote").val(),
                                    _token: $('input[name="_token"]').val(),
                                },
                                dataType: "json",
                                async: false,
                                success: function (response) {
                                    console.log(response)
                                    getLote()
                                }
                            });
                        } else {
                            getLote()
                        }
                    } else {
                        $.ajax({
                            type: 'POST',
                            url: base_url + "/admin/laboratorio/" + area + "/setLote",
                            data: {
                                id: $("#parametro").val(),
                                fecha: $("#fechaLote").val(),
                                _token: $('input[name="_token"]').val(),
                            },
                            dataType: "json",
                            async: false,
                            success: function (response) {
                                console.log(response)
                                getLote()
                            }
                        });
                    }
                }
            });
        }
    } else {
        alert("Para buscar un lote tienes que seleccionar un parametro y una fecha")
    }
}
function getLote() {
    if ($("#parametro").val() != "0") {
        let tabla = document.getElementById('divLote');
        let tab = '';
        let  cont = 0
        $.ajax({
            type: 'POST',
            url: base_url + "/admin/laboratorio/" + area + "/getLote",
            data: {
                id: $("#parametro").val(),
                fecha: $("#fechaLote").val(),
                folio: $("#folio").val(),
                _token: $('input[name="_token"]').val(),
            },
            dataType: "json",
            async: false,
            success: function (response) {
                console.log(response);
                if (response.model.length < 1) {
                    alert("No hay lote encontrado")
                }
                tab += '<table id="tabLote" class="table table-sm">'
                tab += '    <thead>'
                tab += '        <tr>'
                tab += '          <th>Id</th>'
                tab += '          <th>Fecha</th> '
                tab += '          <th>Parametro</th> '
                tab += '          <th>Asignados</th> '
                tab += '          <th>Liberados</th> '
                tab += '          <th>Opc</th> '
                tab += '        </tr>'
                tab += '    </thead>'
                tab += '    <tbody>'
                $.each(response.model, function (key, item) {
                    tab += '<tr>'
                    tab += '<td>' + item.Id_lote + '</td>'
                    tab += '<td>' + item.Fecha + '</td>'
                    switch (parseInt($("#parametro").val())) {
                        case 6:
                            tab += '<td>' + item.Parametro + ' ('+response.aux[cont]+')</td>'
                            break;
                        default:
                            tab += '<td>' + item.Parametro + '</td>'
                            break;
                    }
                    tab += '<td>' + item.Asignado + '</td>'
                    tab += '<td>' + item.Liberado + '</td>'
                    tab += '<td>'
                    tab += '     <button class="btn-info" onclick="exportBitacora(' + item.Id_lote + ')"><i class="voyager-download"></i></button><br>'
                    tab += '     <button onclick="getDetalleLote(' + item.Id_lote + ',\'' + item.Parametro + '\')" class="btn-info" id="btnEditarBitacora"><i class="voyager-edit"></i></button>'
                    tab += '</td>'
                    tab += '</tr>'
                    cont++
                })
                tab += '    </tbody>'
                tab += '</table>'
                tabla.innerHTML = tab
                idArea = response.model[0].Id_area
                //Inicializacion de tabla
                tableLote = $('#tabLote').DataTable({
                    "ordering": false,
                    "language": {
                        "lengthMenu": "# _MENU_ por pagina",
                        "zeroRecords": "No hay datos encontrados",
                        "info": "Pagina _PAGE_ de _PAGES_",
                        "infoEmpty": "No hay datos encontrados",
                    }
                });
                //Funcion de seleccionar
                $('#tabLote tbody').on('click', 'tr', function () {
                    if ($(this).hasClass('selected')) {
                        $(this).removeClass('selected');
                    } else {
                        tableLote.$('tr.selected').removeClass('selected');
                        $(this).addClass('selected');
                        idLote = $(this).children(':first').html();
                        getCapturaLote()
                        $("#clorurosParametro").html('');
                        $("#conductividadParametro").html('');
                        $("#phParametro").html('');
                    }
                });
                $('#tabLote tbody').on('dblclick', 'tr', function () {
                    $("#modalAsignar").modal("show")
                    idLote = $(this).children(':first').html();
                    getMuestraSinAsignar()
                });
                if (response.model.length > 0) {

                } else {
                    alert("No hay lotes en esta fecha")
                    idLote = 0
                    getCapturaLote()
                }
            }
        });
    } else {
        alert("Para buscar un lote tienes que seleccionar un parametro y una fecha")
    }
}
function getPendientes() {
    let tabla = document.getElementById('divPendientes');
    let tab = '';
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/" + area + "/getPendientes",
        data: {
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            let mensaje = ''
            console.log(response);
            model = response.model
            tab += '<table class="table table-sm" style="font-size:10px" id="tablePendientes">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>Folio</th>';
            tab += '          <th>Parametro</th>';
            tab += '          <th>Fecha recepción</th>';
            tab += '          <th>Empresa</th>'
            tab += '          <th>Dias Analisis</th>'
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            for (let i = 0; i < model.length; i++) {
               
                fechaRecepcion = new Date(model[i][2]); 
                
                Hoy = new Date(model[i][5]);
                    
                diasAnalisis = model[i][6];
                diasDiferencia = Math.ceil(( Hoy - fechaRecepcion) / (1000 * 60 * 60 * 24)); 

                estilohistorial= model[i][4] == 1 ? 'background-color:#e5e5ff;' : '';

                if (parseInt(diasAnalisis) !== 0 && diasAnalisis !== null) 
                {
         
                    if (diasDiferencia < diasAnalisis) {
                       
                        color = 'bg-success';
                        mensaje = 'Faltan Días('+((diasDiferencia-diasAnalisis)*(-1))+')';

                    }else if(diasDiferencia === 1)
                    {
                        color = 'bg-warning';
                        mensaje = 'En Tiempo('+diasDiferencia+')';

                    }else if(diasAnalisis < diasDiferencia)
                    {
                        color = 'bg-danger';
                        mensaje = 'Fuera de Tiempo('+diasDiferencia+')';
                    }
                }
                console.log(color)

                tab += '<tr>';
                tab += '<td style="' + estilohistorial + '">' + model[i][0] + '</td>';//Folio
                tab += '<td style="' + estilohistorial + '">' + model[i][1] + '</td>';//parametro
                tab += '<td style="' + estilohistorial + '">' + model[i][2] + '</td>';//fecha recepcion
                tab += '<td style="' + estilohistorial + '">' + model[i][3] + '</td>';// empresa
                tab += '<td class="' + color + '">' + mensaje + '</td>'
                tab += '</tr>';
            }
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;

            table = $('#tablePendientes').DataTable({
                "ordering": false,
                "language": {
                    "lengthMenu": "# _MENU_ por pagina",
                    "zeroRecords": "No hay datos encontrados",
                    "info": "Pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay datos encontrados",
                }
            });
        }
    });
}

function getHistorial(id)
{
    console.log("Get Historial");
    let tabla1 = document.getElementById('divTablaHist');
    let tabla2 = document.getElementById('divTablaCodigos');
    let tab1 = ''
    let tab2 = ''

  
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getHistorial",
        data: {
            idLote: idLote,
            idCodigo:id,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            tab1 += `
                <table id="tablaLoteModal" class="table table-sm">
                    <thead class="thead-dark">
                        <tr>
                            <th>Id lote</th>
                            <th>Fecha lote</th>
                            <th>Codigo</th>
                            <th>Parametro</th>
                            <th>Resultado</th>
                    
                        </tr>
                    </thead>
                    <tbody>
                            ${
                               $.map(response.idsLotes, function (item,index){
                                let    estilo =   parseInt(response.historialHist[index]) == 1 ? 'background-color:#e5e5ff;' : ''
                                    return `
                                 
                                    
                                        <tr>
                                            <td>${item}</td>
                                            <td style ="${estilo}">${response.fechaLote[index]}</td>
                                            <td style ="${estilo}">${response.Codigohist[index]}</td>
                                            <td style ="${estilo}"">${response.parametrohist[index]}</td>
                                            <td style ="${estilo}">${response.resultadoHist[index]}</td>
                                            
                                        </tr>
                                    `
                               }).join('') 
                            }
                    </tbody>
                 </table>
            `

            tab2 = `
                    <table id="tablaCodigosHistorial" class="table table-sm">
                        <thead>
                            <tr>
                                <th>Id_codigo</th>
                                <th>Codigo</th>
                                <th>Parametro</th>
                                <th>Resultado Ejec.</th>
                                <th>Resultado Lib.</th>
                                <th>Analizo</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            ${
                                $.map(response.model, function (item){
                                    let estilo2 = item.Liberado == 1 ? 'bg-success' : '';
                                    return `
                                        <tr>
                                        
                                            <td class="${estilo2}">${item.Id_codigo}</td>
                                            <td class="${estilo2}">${item.Codigo}</td>
                                            <td class="${estilo2}">${item.Parametro} (${item.Tipo_formula})</td>
                                            <td class="${estilo2}">${item.Resultado != null ? item.Resultado : ''}</td>
                                            <td class="${estilo2}">${item.Resultado2 != null ? item.Resultado2 : ''}</td>
                                            <td class="${estilo2}">${item.Analizo != 1 ? item.name : ''}</td>
                                            
                                        </tr>
                                    `;
                                }).join('') 
                            }
                        </tbody>

                    </table>
                 `

            tabla1.innerHTML = tab1
            tabla2.innerHTML= tab2

            
            var t2 = $('#tablaCodigosHistorial').DataTable({
                "ordering": false,
                paging: false,
                scrollY: '300px',
                "language": {
                    "lengthMenu": "# _MENU_ por pagina",
                    "zeroRecords": "No hay datos encontrados",
                    "info": "Pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay datos encontrados",
                }
            });
        }
    });
}

function getUltimoLote()
{
    let tabla = document.getElementById('divUltimoLote');
    let tab = '';
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getUltimoLote",
        data: {
            id: $("#parametro").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            tab += 'Fec. Ult. Lote: '+response.model.Fecha
            tabla.innerHTML = tab
        }
    });
}

function getDetalleElegido(folio){
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/analisis/getDetalleElegido",
        data: {
            folio: folio,
            idMuestra: idMuestra,
            idLote: idLote,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response)
            const elegidoCloruro = response.cloruros;
            const elegidoConductividad = response.conductividad;
            const elegidoPh = response.ph;
            $("#aceptacion").html(response.aceptacion);
            if(elegidoCloruro[0] != null && elegidoCloruro[0].Resultado2 != null){
                $("#clorurosParametro").html(elegidoCloruro[0]);
            }
            else{
                $("#clorurosParametro").html('N.A');
            }
            if(elegidoConductividad[0] != null && elegidoConductividad[0].Resultado2 != null){
                $("#conductividadParametro").html(elegidoConductividad[0].Resultado2);
            }
            else{
                $("#conductividadParametro").html('N.A');
            }
            if(elegidoPh[0] != 'undefined' && elegidoPh[0].Resultado2 != null){
                $("#phParametro").html(elegidoPh[0].Resultado2);
            }
            else{
                $("#phParametro").html('N.A');
            }

            
        }
    })
}