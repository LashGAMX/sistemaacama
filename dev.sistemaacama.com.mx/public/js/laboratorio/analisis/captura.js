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
    $('#btnPendientes').click(function(){
        getPendientes()
    }); 
    $('#btnBuscarLote').click(function(){
        getLote()
    }); 
    $('#btnCrearLote').click(function(){
        setLote()
    });
    $('#btnAsignarMuestra').click(function(){
        setMuestraLote()
    }); 
    $('#btnEjecutar').click(function(){
        setDetalleMuestra()
    }); 
    $('.btnEjecutar').click(function(){
        setDetalleMuestra()
    }); 
    $('#btnSetControl').click(function(){
        setControlCalidad()
    }); 
    $('#btnLiberarTodo').click(function(){
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
});

 //todo Variables globales
var tableLote
var idLote
var idMuestra 
var idMuestra = 0
var idArea = 0
var blanco = 0 
 //todo funciones
 function getStdMenu()
 {
    $("#tabGa-tab").hide()
    switch (parseInt(idArea)) {
        case 16: // Espectofotometria
        case 5:

            break;
        case 13://G&A
        $("#tabGa-tab").show()
        break;
        default:
            break;
    }
 }
 function setDetalleGrasas(){
    $.ajax({ 
        type: "POST",
        url: base_url + "/admin/laboratorio/"+area+"/setDetalleGrasas",
        data: {
            id: idLote,
            temp1:$('#tempGA1').val(),
            entrada1:$('#entradaGA1').val(),
            salida1:$('#salidaGA1').val(),
            temp2:$('#tempGA2').val(),
            entrada2:$('#entradaGA2').val(),
            salida2:$('#salidaGA2').val(),
            temp3:$('#tempGA3').val(),
            entrada3:$('#entradaGA3').val(),
            salida3:$('#salidaGA3').val(),
            dosentrada1:$('#2entraGAda1').val(),
            dosalida1:$('#2salidaGA1').val(),
            dospesado1:$('#2pesadoGA1').val(),
            dosentrada2:$('#2entraGAda2').val(),
            dosalida2:$('#2salidaGA2').val(),
            dospesado2:$('#2pesadoGA2').val(),
            dosentrada3:$('#2entraGAda3').val(),
            dosalida3:$('#2salidaGA3').val(),
            dospesado3:$('#2pesadoGA3').val(),
            trestemperatura:$('#3temperaturaGA').val(),
            tresentrada:$('#3entradaGA').val(),
            tressalida:$('#3salidaGA').val(),
            cuatroentrada:$('#4entradaGA').val(),
            cuatrosalida:$('#4salidaGA').val(),
            cincoentrada:$('#5entradaGA').val(),
            cincosalida:$('#5salidaGA').val(),

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
function setBitacora()
 {
    $.ajax({ 
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/setBitacora",
        data: {
            id: idLote,
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
 function setObservacion(id)
{
    
    $.ajax({
        type: "POST", 
        url: base_url + "/admin/laboratorio/" + area + "/setObservacion",
        data: {
            idLote:idLote,
            idMuestra: idMuestra,
            observacion: $("#"+id).val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            getCapturaLote();
        }
    }); 
}  
 function setLiberar()
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/setLiberar",
        data: {
            idMuestra: idMuestra,
            idLote:idLote,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            if(response.sw == true)
            {
                getLote();
                getCapturaLote();
                alert("Muestra liberada")
            }else{
                alert("La muestra no se pudo liberar");
            }
        }
    });
}
 function setLiberarTodo()
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/setLiberarTodo",
        data: {
            idLote:idLote,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            if(response.sw == true)
            {
                getLote();
                getCapturaLote();
                alert("Muestras liberadas")
            }else{
                alert("La muestra no se pudo liberar");
            }
        }
    });
}
 function setControlCalidad()
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/setControlCalidad",
        data: {
            idMuestra: idMuestra, 
            idLote:idLote,
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
 function exportBitacora(id)
 {
    window.open(base_url+"/admin/laboratorio/" + area + "/bitacora/impresion/"+id);       
 }
 function getDetalleLote(id,parametro)
 {
    getStdMenu()
    $("#modalDetalleLote").modal("show")
    $("#tituloLote").val(id+' - '+parametro)
    let summer = document.getElementById("divSummer")
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getDetalleLote",
        data: {
            id:id,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response); 
            switch (parseInt(idArea)) {
                case 16: // Espectofotometria
                case 5:
        
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
 function setDetalleMuestra()
 {
    switch (parseInt(idArea)) {
        case 16: // Espectofotometria
        case 5:
                switch (parseInt($('#parametro').val())) {
                    case 152: // COT
                        $.ajax({
                            type: "POST",
                            url: base_url + "/admin/laboratorio/" + area + "/setDetalleMuestra",
                            data: {
                                idLote:idLote,
                                idMuestra: idMuestra,
                                ABS:$('#abs1COT').val(),
                                CA:$('#blanco1COT').val(),
                                CB:$('#b1COT').val(),
                                CM:$('#m1COT').val(),
                                CR:$('#r1COT').val(),
                                D:$('#fDilucion1COT').val(),
                                E:$('#volMuestra1COT').val(),
                                X:$('#abs11COT').val(),
                                Y:$('#abs21COT').val(),
                                Z:$('#abs31COT').val(),
                            
                                _token: $('input[name="_token"]').val()
                            },
                            dataType: "json",
                            success: function (response) {
                                console.log(response);
                                if (response.idControl == 5){
                                    $("#resultadoCOT").val(response.model.Resultado); 
                                } else {
                                    $("#abs1COT").val(response.model.Promedio.toFixed(3)); 
                                    $("#abs2COT").val(response.model.Promedio.toFixed(3)); 
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
                                idLote:idLote,
                                idMuestra: idMuestra,
                                ABS:$('#abs1SulfatosF').val(),
                                CA:$('#blanco1F').val(),
                                CB:$('#b1SulfatosF').val(),
                                CM:$('#m1SulfatosF').val(),
                                CR:$('#r1SulfatosF').val(),
                                D:$('#fDilucion1SulfatosF').val(),
                                E:$('#volMuestra1SulfatosF').val(),
                                X:$('#abs11SulfatosF').val(),
                                Y:$('#abs21SulfatosF').val(),
                                Z:$('#abs31SulfatosF').val(),
                                ABS4:$('#abs41SulfatosF').val(),
                                ABS5:$('#abs51SulfatosF').val(),
                                ABS6:$('#abs61SulfatosF').val(),
                                ABS7:$('#abs71SulfatosF').val(),
                                ABS8:$('#abs81SulfatosF').val(),
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
                                idLote:idLote,
                                idMuestra: idMuestra,
                                ABS:$('#absPromEspectro1').val(),
                                CA:$('#blancoEspectro1').val(),
                                CB:$('#bEspectro1').val(),
                                CM:$('#mEspectro1').val(),
                                CR:$('#rEspectro1').val(),
                                phIni:$('#phIniEspectro1').val(),
                                phFin:$('#phFinEspectro1').val(),
                                nitratos:$('#nitratosEspectro1').val(),
                                nitritos:$('#nitritosEspectro1').val(),
                                sulfuros:$('#sulfurosEspectro1').val(),
                                D:$('#fDilucionEspectro1').val(),
                                E:$('#volMuestraEspectro1').val(),
                                X:$('#abs1Espectro1').val(), 
                                Y:$('#abs2Espectro1').val(),
                                Z:$('#abs3Espectro1').val(),
                                _token: $('input[name="_token"]').val()
                            },
                            dataType: "json",
                            success: function (response) { 
                                console.log(response);
                                $("#absPromEspectro1").val(response.model.Promedio.toFixed(3)); 
                                $("#absPromEspectro2").val(response.model.Promedio.toFixed(3)); 
                                $("#resultadoEspectro").val(response.model.Resultado.toFixed(3)); 
                                $("#fDilucionEspectro1").val(response.model.Vol_dilucion.toFixed(3));
                                $("#fDilucionEspectro2").val(response.model.Vol_dilucion.toFixed(3));
      
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
                    idLote:idLote,
                    idMuestra: idMuestra,
                    R:$("#resultadoGA").val(),
                    H:$("#hGA1").val(),
                    J:$("#jGA1").val(),
                    K:$("#kGA1").val(),
                    C:$("#cGA1").val(),
                    L:$("#lGA1").val(),
                    I:$("#iGA1").val(),
                    G:$("#gGA1").val(),
                    E:$("#eGA1").val(),
                    P:$("#pGA").val(),
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
                        $('#resultadoGA').val(response.model.Resultado.toFixed(4));
        
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
                        idLote:idLote,
                        idMuestra: idMuestra,
                        resultado: $("#resultadoModalSolidosDir").val(),
                        inmhoff: $("#inmhoffSolidosDir").val(),
                        temperaturaLlegada: $("#temperaturaLlegadaSolidosDir").val(),
                        temperaturaAnalizada: $("#temperaturaAnalizadaSolidosDir").val(),
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
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/laboratorio/" + area + "/setDetalleMuestra", 
                        data: {
                            idLote:idLote,
                            idMuestra: idMuestra,
                            resultado: $("#preResDifSolidosDif").val(),
                            val1: $("#val11SolidosDif").val(),
                            val2: $("#val21SolidosDif").val(),
                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            $("#resultadoSolidosDif").val(response.res);
                        }
                    });
                    break;
                default: // Default
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/laboratorio/" + area + "/setDetalleMuestra", 
                        data: {
                            idLote:idLote,
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
                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            if ($("#resultadoSolidos").val() != "") {
                                $('#crisolSolidos').val(response.model.Crisol);
                                $("#m11Solidos").val(response.model.Masa1);
                                $("#m21Solidos").val(response.model.Masa2);
                                $("#pcm11Solidos").val(response.model.Peso_muestra1);
                                $("#pcm21Solidos").val(response.model.Peso_muestra2);
                                $("#pc1Solidos").val(response.model.Peso_constante1);
                                $("#pc21Solidos").val(response.model.Peso_constante2);
                            } else {
                                $('#resultadoSolidos').val(response.model.Resultado.toFixed(4));   
                            }
                        }
                    });
                    break;
            }
        break;
        default:
            break;
    }
    
    getCapturaLote()

 }
 function getDetalleMuestra(id)
 {
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/"+area+"/getDetalleMuestra",
        data: {
            id:$("#idMuestra"+id).val(),
            idLote:idLote,
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
                                 $("#abs11COT").val(response.model.Abs1);
                                 $("#abs21COT").val(response.model.Abs2);
                                 $("#abs31COT").val(response.model.Abs3);
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
                            // $("#phIni1F").val(response.model.Ph_ini);
                            // $("#phFin1F").val(response.model.Ph_fin);
                            // $("#nitratos1F").val(response.model.Nitratos);
                            // $("#nitritos1F").val(response.model.Nitritos);
                            // $("#sulfuros1F").val(response.model.Sulfuros);
                            $("#fDilucion1SulfatosF").val(response.model.Vol_dilucion);
                            $("#fDilucion2SulfatosF").val(response.model.Vol_dilucion);
                            $("#volMuestra1SulfatosF").val(response.model.Vol_muestra);
                            $("#volMuestra2SulfatosF").val(response.model.Vol_muestra);
                            $("#abs11SulfatosF").val(response.model.Abs1);
                            $("#abs12SulfatosF").val(response.model.Abs1);
                            $("#abs21SulfatosF").val(response.model.Abs2);
                            $("#abs22SulfatosF").val(response.model.Abs2);
                            $("#abs31SulfatosF").val(response.model.Abs3);
                            $("#abs32SulfatosF").val(response.model.Abs3);
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
                        default:
                            $("#observacion").val(response.model.Observacion);
                            $("#absPromEspectro1").val(response.model.Promedio);
                            $("#absPromEspectro2").val(response.model.Promedio);
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
                            $("#nitratosEspectro1").val(response.model.Nitratos);
                            $("#nitritosEspectro1").val(response.model.Nitritos);
                            $("#sulfurosEspectro1").val(response.model.Sulfuros);
                            $("#fDilucion1").val(response.model.Vol_dilucion);
                            $("#fDilucion2").val(response.model.Vol_dilucion);
                            $("#volMuestraEspectro1").val(response.model.Vol_muestra);
                            $("#volMuestraEspectro2").val(response.model.Vol_muestra);
                            $("#abs1Espectro1").val(response.model.Abs1);
                            $("#abs2Espectro1").val(response.model.Abs2);
                            $("#abs3Espectro1").val(response.model.Abs3);
                            $("#abs1Espectro2").val(response.model.Abs1);
                            $("#abs2Espectro2").val(response.model.Abs2);
                            $("#abs3Espectro2").val(response.model.Abs3);
                            $("#resultadoEspectro").val(response.model.Resultado);
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
                    if(response.model.Id_control != 5)
                    {
                        $("#gGA1").val(blanco);   
                        $("#gGA2").val(blanco);
                    }else{
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
                    if (response.model.Id_parametro == 4) {
                        document.getElementById('titulomasa1Solidos').innerHTML = 'Masa 2'
                        document.getElementById('titulomasa2Solidos').innerHTML = 'Masa 6'
                    } else {
                        document.getElementById('titulomasa1Solidos').innerHTML = 'Masa B'
                        document.getElementById('titulomasa2Solidos').innerHTML = 'Masa A'
                    }
                    switch (parseInt(response.model.Id_parametro)) { 
                        case 3: // Directos
                            
                            break;
                        case 47: // Por diferencia
                        case 88:
                        case 44:
                        case 45:
                            $("#nomParametro1SolidosDif").val(response.nom1);
                            $("#val11SolidosDif").val(response.dif1.Resultado);
                            $("#nomParametro2SolidosDif").val(response.nom2);
                            $("#val21SolidosDif").val(response.dif2.Resultado);
                            let res = (response.dif1.Resultado) - (response.dif2.Resultado);
                            $("#preResDifSolidosDif").val(res);
                            $("#resultadoSolidosDif").val(response.model.Resultado);
                            $("#observacionSolidosDif").val(response.model.Observacion);
                            break;
                        default: // Default

                            $("#m11Solidos").val(response.model.Masa1);
                            $("#m12Solidos").val(response.model.Masa1);
                            $("#m21Solidos").val(response.model.Masa2);
                            $("#m22Solidos").val(response.model.Masa2);
                            $("#pcm11Solidos").val(response.model.Peso_constante1);
                            $("#pcm12Solidos").val(response.model.Peso_constante1);
                            $("#pcm21Solidos").val(response.model.Peso_constante2);
                            $("#pcm22Solidos").val(response.model.Peso_constante2);
                            $("#pc1Solidos").val(response.model.Peso_muestra1);
                            $("#pc2Solidos").val(response.model.Peso_muestra1);
                            $("#pc21Solidos").val(response.model.Peso_muestra2);
                            $("#pc22Solidos").val(response.model.Peso_muestra2);
                            $("#v1Solidos").val(response.model.Vol_muestra);
                            $("#v2Solidos").val(response.model.Vol_muestra);
                            $("#f1Solidos").val(response.model.Factor_conversion);
                            $("#f1Solidos").val(response.model.Factor_conversion);
        
                            $("#crisolSolidos").val(response.model.Crisol);
                            $("#resultadoSolidos").val(response.model.Resultado);
                                $("#observacionSolidos").val(response.model.Observacion);
                            break;
                    }
                default:
 
                break;
            }
        }
    });
 }
 function getCapturaLote()
 {
    blanco = 0
    let tabla = document.getElementById('divCaptura');
    let tab = '';
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/"+area+"/getCapturaLote",
        data: {
            idLote:idLote,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            tab += '<table id="tabCaptura" class="table table-sm">';
            tab += '    <thead>';
            tab += '        <tr>';
            tab += '          <th>Opc</th>';
            tab += '          <th>Folio</th>';
            tab += '          <th>Norma</th>';
            tab += '          <th>Resultado</th>';
            tab += '          <th>Observación</th>';
            tab += '        </tr>';
            tab += '    </thead>'; 
            tab += '    <tbody>';
            $.each(response.model, function (key, item) {
                if (item.Liberado != 1) {
                    status = "";
                    color = "success";
                } else { 
                    status = "disabled";
                    color = "warning"
                }
                tab += '<tr>';
                if (item.Id_control == 5) {
                    blanco = item.Resultado
                } 

                switch (parseInt(response.lote[0].Id_area)) {
                    case 16: // Espectrofotometria 
                    case 5: // Fisicoquimicos
                        switch (parseInt(item.Id_parametro)) { 
                            case 152:
                                tab += '<td><input hidden id="idMuestra'+item.Id_detalle+'" value="'+item.Id_detalle+'"><button '+status+' type="button" class="btn btn-'+color+'" onclick="getDetalleMuestra('+item.Id_detalle+');" data-toggle="modal" data-target="#modalCapturaCOT">Capturar</button>';
                                break;
                            case 113:
                                tab += '<td><input hidden id="idMuestra'+item.Id_detalle+'" value="'+item.Id_detalle+'"><button '+status+' type="button" class="btn btn-'+color+'" onclick="getDetalleMuestra('+item.Id_detalle+');" data-toggle="modal" data-target="#modalCapturaSulfatos">Capturar</button>';
                                break;
                            default:
                                tab += '<td><input hidden id="idMuestra'+item.Id_detalle+'" value="'+item.Id_detalle+'"><button '+status+' type="button" class="btn btn-'+color+'" onclick="getDetalleMuestra('+item.Id_detalle+');" data-toggle="modal" data-target="#modalCapturaEspectro">Capturar</button>';
                                break;
                        }
                        break;
                    case 13: // G&A
                        tab += '<td><input hidden id="idMuestra'+item.Id_detalle+'" value="'+item.Id_detalle+'"><button '+status+' type="button" class="btn btn-'+color+'" onclick="getDetalleMuestra('+item.Id_detalle+');" data-toggle="modal" data-target="#modalCapturaGA">Capturar</button>';
                        break;
                    case 15: // Solidos
                        switch (parseInt(item.Id_parametro)) { 
                            case 3: // Directos
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button '+status+' type="button" class="btn btn-'+color+'" onclick="getDetalleMuestra(' + item.Id_detalle + ',2);" data-toggle="modal" data-target="#modalCapturaSolidosDir">Capturar</button>';
                                break;
                            case 47: // Por diferencia
                            case 88:
                            case 44:
                            case 45:
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button '+status+' type="button" class="btn btn-'+color+'" onclick="getDetalleMuestra(' + item.Id_detalle + ',2);" data-toggle="modal" data-target="#modalCapturaSolidosDif">Capturar</button>';
                                break;
                            default: // Default
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button '+status+' type="button" class="btn btn-'+color+'" onclick="getDetalleMuestra(' + item.Id_detalle + ',1);" data-toggle="modal" data-target="#modalCapturaSolidos">Capturar</button>';
                                break;
                        }
                        break;
                    case 14: // Volumetria
                            switch (parseInt(item.Id_parametro)) { 
                                case 3: // Cloro
                                    tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button '+status+' type="button" class="btn btn-'+color+'" onclick="getDetalleMuestra(' + item.Id_detalle + ',2);" data-toggle="modal" data-target="#modalCapturaSolidosDir">Capturar</button>';
                                    break;
                                case 47: // Por diferencia
                                case 88:
                                case 44:
                                case 45:
                                    tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button '+status+' type="button" class="btn btn-'+color+'" onclick="getDetalleMuestra(' + item.Id_detalle + ',2);" data-toggle="modal" data-target="#modalCapturaSolidosDif">Capturar</button>';
                                    break;
                                default: // Default
                                    tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button '+status+' type="button" class="btn btn-'+color+'" onclick="getDetalleMuestra(' + item.Id_detalle + ',1);" data-toggle="modal" data-target="#modalCapturaSolidos">Capturar</button>';
                                    break;
                            }
                        break;
                    default:
                        break;
                }
                if (item.Id_control != 1) 
                {
                    tab += '<br> <small class="text-danger">'+item.Control+'</small></td>';
                }else{
                    tab += '<br> <small class="text-info">'+item.Control+'</small></td>';
                }
                tab += '<td><input disabled style="width: 100px" value="'+item.Folio_servicio+'"></td>';
                tab += '<td><input disabled style="width: 200px" value="'+item.Clave_norma+'"></td>';
                if(item.Resultado != null){
                    tab += '<td><input disabled style="width: 100px" value="'+item.Resultado+'"></td>';
                }else{
                    tab += '<td><input disabled style="width: 80px" value=""></td>';
                }
                if(item.Observacion != null){
                    tab += '<td>'+item.Observacion+'</td>';
                }else{
                    tab += '<td></td>';
                }
                tab += '</tr>';

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
                }
                else {
                    t2.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            }); 
            $('#tabCaptura tr').on('click', function () {
                let dato = $(this).find('td:first');
                idMuestra = dato[0].firstElementChild.value;
            });

        }
    });
 }
 function setMuestraLote()
 {
     let muestra = document.getElementsByName("stdCkAsignar")
    let codigos = new Array();
    for(let i = 0; i < muestra.length;i++){
        if (muestra[i].checked) {
            codigos.push(muestra[i].value);
        }
    }
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/"+area+"/setMuestraLote",
        data: {
            codigos:codigos,
            idLote:idLote,
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
 function getMuestraSinAsignar()
 {
    let tabla = document.getElementById('devAsignarLote');
    let tab = '';
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/"+area+"/getMuestraSinAsignar",
        data: {
            idLote:idLote,
            idParametro: $("#parametro").val(),
            fecha:$("#fechaAsignar").val(),
            _token: $('input[name="_token"]').val(), 
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response)
            $("#tipoFormulaAsignar").val(response.lote.Tipo_formula)
            $("#parametroAsignar").val(response.lote.Parametro)
            $("#fechaAnalisisAsignar").val(response.lote.Fecha)
            $("#asignadoLote").val(response.lote.Asignado)
            $("#liberadoLote").val(response.lote.Liberado)
            tab += '<table id="tabAsignar" class="table table-sm">'
            tab += '    <thead>'
            tab += '        <tr>'
            tab += '          <th>Opc</th>'
            tab += '          <th># Muestra</th> '
            tab += '          <th>Norma</th> '
            tab += '          <th>Punto muestreo</th> '
            tab += '          <th>Fecha recepción</th> '
            tab += '        </tr>'
            tab += '    </thead>'
            tab += '    <tbody>'
            for (let i = 0; i < response.model.length; i++) {
                tab += '<tr>'
                tab += '<td><input type="checkbox" value="'+response.model[i].Id_codigo+'" name="stdCkAsignar"></td>'
                tab += '<td>'+response.folio[i]+'</td>'
                tab += '<td>'+response.norma[i]+'</td>'
                tab += '<td>'+response.punto[i]+'</td>'
                tab += '<td>'+response.fecha[i]+'</td>'
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
            $('div.toolbar').html('<button onclick="setMuestraLote()" id="btnAsignarMuestra" class="btn-info"><i class="fas fa-paper-plane"></i></button>');
        }
    });
 }
 function setLote()
 {

    if ($("#parametro").val() != "0" && $("#fechaLote").val() != "") {
        if (confirm("¿Estas seguro de crear el lote?")) {
            $.ajax({
                type: 'POST',
                url: base_url + "/admin/laboratorio/"+area+"/getLote",
                data: {
                    id:$("#parametro").val(),
                    fecha:$("#fechaLote").val(),
                    _token: $('input[name="_token"]').val(),
                },
                dataType: "json",
                async: false,
                success: function (response) {            
                    if (response.model.length > 0) {
                        if (confirm("Ya tienes un lote en esta fecha, ¿Quieres crear otro lote?")) {
                            $.ajax({
                                type: 'POST',
                                url: base_url + "/admin/laboratorio/"+area+"/setLote",
                                data: {
                                    id:$("#parametro").val(),
                                    fecha:$("#fechaLote").val(),
                                    _token: $('input[name="_token"]').val(),
                                },
                                dataType: "json",
                                async: false,
                                success: function (response) {            
                                    console.log(response)
                                    getLote()
                                }
                            });
                        }else{
                            getLote()
                        }
                    } else{
                        $.ajax({
                            type: 'POST',
                            url: base_url + "/admin/laboratorio/"+area+"/setLote",
                            data: {
                                id:$("#parametro").val(),
                                fecha:$("#fechaLote").val(),
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
    }else{
        alert("Para buscar un lote tienes que seleccionar un parametro y una fecha")
    }
 }
function getLote()
{
    if ($("#parametro").val() != "0" ) {
        let tabla = document.getElementById('divLote');
        let tab = '';
        $.ajax({ 
            type: 'POST',
            url: base_url + "/admin/laboratorio/"+area+"/getLote",
            data: {
                id:$("#parametro").val(),
                fecha:$("#fechaLote").val(),
                folio:$("#folio").val(), 
                _token: $('input[name="_token"]').val(),
            },
            dataType: "json",
            async: false,
            success: function (response) {            
                console.log(response);
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
                $.each(response.model,function (key,item){
                    tab += '<tr>'
                    tab += '<td>'+item.Id_lote+'</td>'
                    tab += '<td>'+item.Fecha+'</td>'
                    tab += '<td>'+item.Parametro+'</td>'
                    tab += '<td>'+item.Asignado+'</td>'
                    tab += '<td>'+item.Liberado+'</td>'
                    tab += '<td>'
                    tab +='     <button class="btn-info" onclick="exportBitacora('+item.Id_lote+')"><i class="voyager-download"></i></button><br>'
                    tab +='     <button onclick="getDetalleLote('+item.Id_lote+',\''+item.Parametro+'\')" class="btn-info" id="btnEditarBitacora"><i class="voyager-edit"></i></button>'
                    tab += '</td>' 
                    tab += '</tr>'
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
    }else{
        alert("Para buscar un lote tienes que seleccionar un parametro y una fecha")
    }
}
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
            tab += '<table class="table table-sm" style="font-size:10px" id="tablePendientes">';
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