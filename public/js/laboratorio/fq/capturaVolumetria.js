var area = "fq";
var numMuestras = new Array();
var idMuestra = 0;
var idLote = 0;
var tecnica = 0;

$(document).ready(function () {
    $('#formulaTipo').select2();
});

$('#btnLiberar').click(function () {
    // operacion();
    liberarMuestra();
});

$('#btnLiberarTodo').click(function () {
    // operacion();
    liberarTodo();
});

$('#enviarObservacion').click(function () {
    enviarObsGeneralVol();
})

$('#btnEjecutarDqo').click(function () {
    // operacionDqo();
})
$('#btnEjecutarCloro').click(function () {
    operacionCloro();
})
$('#btnEjecutarNitro').click(function () {
    operacionNitrogeno();
})

$('#btnGuardarCloro').click(function () {
    // operacionCloro();
    guardarCloro();
});
$('#btnGuardarDqo').click(function () {
    // guardarDqo();
});
$('#btnGuardarNitro').click(function () {
    guardarNitrogeno();
});
$('#btnGenControles').click(function () {
    // operacion();
    createControlesCalidad();
});



function getDataCaptura() {
    cleanTable();
    numMuestras = new Array(); 
    let tabla = document.getElementById('divLote');
    let tab = '';


    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getLotevol",
        data: {
            formulaTipo: $("#formulaTipo").val(),
            fechaAnalisis: $("#fechaAnalisis").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json", 
        success: function (response) {
            console.log(response);

            tab += '<table id="tablaLote" class="table table-sm">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>Lote</th>';
            tab += '          <th>Tipo formula</th>';
            tab += '          <th>Fecha lote</th> ';
            tab += '          <th>Total asignado</th> ';
            tab += '          <th>Total liberados</th> ';
            tab += '          <th>Opc</th> ';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            $.each(response.lote, function (key, item) {
                tab += '<tr>';
                tab += '<td>' + item.Id_lote + '</td>';
                tab += '<td>' + item.Tipo_formula + '</td>';
                tab += '<td>' + item.Fecha + '</td>';
                tab += '<td>' + item.Asignado + '</td>';
                tab += '<td>' + item.Liberado + '</td>';
                tab += '<td><button class="btn btn-success" id="btnImprimir" onclick="imprimir(' + item.Id_lote + ');"><i class="fas fa-file-download"></i></button></td>';
                tab += '</tr>';
            });
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;

            tecnica = response.tecnica;


            var t = $('#tablaLote').DataTable({
                "ordering": false,
                "language": {
                    "lengthMenu": "# _MENU_ por pagina",
                    "zeroRecords": "No hay datos encontrados",
                    "info": "Pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay datos encontrados",
                }
            });


            $('#tablaLote tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                }
                else {
                    t.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            });
            $('#tablaLote tr').on('click', function () {
                let dato = $(this).find('td:first').html();
                idLote = dato;
                getLoteCapturaVol();
            });
        }
    });
}

function getLoteCapturaVol() {
    $("#tipoInfo").text("Tipo: ")
    numMuestras = new Array();
    let tabla = document.getElementById('divTablaControles');
    let tab = '';
    let cont = 1;

    let status = "";
    let color = "";

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getLoteCapturaVol",
        data: {
            idLote: idLote,
            formulaTipo: $("#formulaTipo").val(),
            tecnica: tecnica,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            $("#tipoInfo").text("Tipo: "+response.tipo) //imprime el tipo de DQO

            tab += '<table id="tablaControles" class="table table-sm">';
            tab += '    <thead>';
            tab += '        <tr>';
            tab += '          <th>Opc</th>';
            tab += '          <th>Folio</th>';
            tab += '          <th>Tipo</th>'; 
            tab += '          <th>Norma</th>';
            tab += '          <th>Resultado</th>';
            tab += '          <th>Observación</th>';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            $.each(response.detalle, function (key, item) {
                tab += '<tr>';
                if (item.Liberado != 1) {
                    status = "";
                    color = "success";
                } else {
                    status = "disabled";
                    color = "warning"
                }
                
                switch ($("#formulaTipo").val()) {
                        case '218':
                        case '33': // CLORO RESIDUAL LIBRE
                        case '64':
                            // tab += '<td><input hidden id="idMuestra'+item.Id_detalle+'" value="'+item.Id_detalle+'"><button type="button" class="btn btn-success" onclick="getDetalleVol('+item.Id_detalle+');" data-toggle="modal" data-target="#modalCaptura">Capturar</button>';    
                            tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleVol(' + item.Id_detalle + ',1);" data-toggle="modal" data-target="#modalCloro">Capturar</button>';
                            break;
                        case '6':
                            if (item.Tecnica == 2) {
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleVol(' + item.Id_detalle + ',2);" data-toggle="modal" data-target="#modalDqo">Capturar</button>';
                            } else {
                                tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleVol(' + item.Id_detalle + ',2);" data-toggle="modal" id="captura'+item.Id_detalle+'" data-target="#modalEspectroDbo">Capturar</button>';
                            }
                            break;
                        case '9':
                        case '287':
                            tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleVol(' + item.Id_detalle + ',3);" data-toggle="modal" data-target="#modalNitrogeno">Capturar</button>';
                            break;
                        case '108':
                            tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleVol(' + item.Id_detalle + ',3);" data-toggle="modal" data-target="#modalNitrogenoEquipo">Capturar</button>';    
                        break;
                        case '10':
                            tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleVol(' + item.Id_detalle + ',3);" data-toggle="modal" data-target="#modalNitrogeno">Capturar</button>';
                            break;
                        case '11':
                            tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button ' + status + ' type="button" class="btn btn-' + color + '" onclick="getDetalleVol(' + item.Id_detalle + ',3);" data-toggle="modal" data-target="#modalNitrogeno">Capturar</button>';
                            break;
                        default:
                            // tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button type="button" class="btn btn-success" onclick="getDetalleVol(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalCloro">Capturar</button>';
                            tab += '<td></td>';
                            break;
                    }

                if (item.Id_control != 1) {
                    tab += '<br> <small class="text-danger">' + item.Control + '</small></td>';
                } else {
                    tab += '<br> <small class="text-info">' + item.Control + '</small></td>';
                }
                tab += '<td><input disabled style="width: 100px" value="' + item.Folio_servicio + '"></td>';
                //todo  --Sección de radioButtons --
                if (response.tipodetalle == 1 || response.tipodetalle == 2 ){
                    tab += '<td><input type="radio" checked id="radioVolumetrico" value="2" name="tipo'+item.Id_detalle+'"><label>&nbspVolumetrico</label></td>';
                } else {
                    if (item.Tecnica == "1" || item.Tecnica == "0"){
                        tab += '<td><input type="radio" checked id="radioEspectro" value="1" name="tipo'+item.Id_detalle+'"><label>&nbspEspectro</label><br>';
                        tab += '<input type="radio" id="radioVolumetrico" value="2" name="tipo'+item.Id_detalle+'"><label>&nbspVolumetrico</label></td>';
                    }else{
                        tab += '<td><input type="radio" id="radioEspectro" value="1" name="tipo'+item.Id_detalle+'"><label>&nbspEspectro</label><br>';
                        tab += '<input type="radio" checked id="radioVolumetrico" value="2" name="tipo'+item.Id_detalle+'"><label>&nbspVolumetrico</label></td>';
                    }
                  
                }
                
                //todo -- --
                tab += '<td><input disabled style="width: 200px" value="' + item.Clave_norma + '"></td>';
                if (item.Resultado != null) {
                    tab += '<td><input disabled style="width: 100px" value="' + item.Resultado + '"></td>';
                } else {
                    tab += '<td><input disabled style="width: 80px" value=""></td>';
                } 
                if (item.Observacion != null) {
                    tab += '<td>' + item.Observacion + '</td>';
                } else {
                    tab += '<td></td>';
                }
                tab += '</tr>';
                numMuestras.push(item.Id_detalle);
                cont++;
            });
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;

            var t2 = $('#tablaControles').DataTable({
                "ordering": false,
                paging: false,
                "language": {
                    "lengthMenu": "# _MENU_ por pagina",
                    "zeroRecords": "No hay datos encontrados",
                    "info": "Pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay datos encontrados",
                }
            });


            $('#tablaControles tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                }
                else {
                    t2.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            });
            $('#tablaControles tr').on('click', function () {
                let dato = $(this).find('td:first');
                idMuestra = dato[0].firstElementChild.value;
                // console.log(idMuestra);
            });


        }
    });
}

//Función imprimir PDF
function imprimir(id) {
    window.open(base_url + "/admin/laboratorio/" + area + "/captura/exportPdfBitacoraVol/" + id);
    //window.location = base_url + "/admin/laboratorio/"+area+"/captura/exportPdfCapturaVolumetria/"+idLote;
}
function operacionNitrogeno() {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/operacionVolumetriaNitrogeno",
        data: {
            idDetalle: idMuestra,
            A: $("#tituladosNitro1").val(),
            B: $("#blancoNitro1").val(),
            C: $("#molaridadNitro1").val(),
            D: $("#factorNitro1").val(),
            E: $("#conversion1").val(),
            G: $("#volNitro1").val(),

            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            $("#resultadoNitro").val(response.res.toFixed(2));


        }
    });
}
function operacionNitrogenoEquipo() {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/operacionVolumetriaNitrogenoEquipo",
        data: {
            idDetalle: idMuestra,
            A: $("#factor1E").val(),
            B: $("#concentracion1E").val(),
            C: $("#volAñadidoStd1E").val(),
            D: $("#VolAñadidoMuestra1E").val(),
            resultado: $("#resultadoNitroEquipo").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            $("#resultadoNitroEquipo").val(response.res.toFixed(2));


        }
    });
}

function operacionCloro() {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/operacionVolumetriaCloro",
        data: {
            idParametro: $("#formulaTipo").val(),
            A: $("#cloroA1").val(),
            E: $("#cloroE1").val(),
            H: $("#cloroH1").val(),
            G: $("#cloroG1").val(),
            B: $("#cloroB1").val(),
            C: $("#cloroC1").val(),
            D: $("#cloroD1").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            $("#resultadoCloro").val(response.res);


        }
    });

}

function guardarCloro() {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/guardarCloro",
        data: {
            idDetalle: idMuestra,
            A: $("#cloroA1").val(),
            E: $("#cloroE1").val(),
            H: $("#cloroH1").val(),
            G: $("#cloroG1").val(),
            B: $("#cloroB1").val(),
            C: $("#cloroC1").val(),
            D: $("#cloroD1").val(),
            Resultado: $("#resultadoCloro").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            getLoteCapturaVol();
        }
    });

}


function operacionDqo(sw) {
    if (sw == 1) {
        $.ajax({
            type: "POST",
            url: base_url + "/admin/laboratorio/" + area + "/operacionVolumetriaDqo",
            data: {
                sw:sw,
                idDetalle: idMuestra,
                B: $("#tituladoDqo1").val(),
                C: $("#MolaridadDqo1").val(),
                CA: $("#blancoDqo1").val(),
                D: $("#factorDqo1").val(),
                E: $("#volDqo1").val(),
                resultado: $("#resultadoDqo").val(),
                _token: $('input[name="_token"]').val()
            },
            dataType: "json",
            success: function (response) {
                console.log(response);
                $("#resultadoDqo").val(response.res);
                getLoteCapturaVol();
            }
        });
    } else {
        $.ajax({
            type: "POST",
            url: base_url + "/admin/laboratorio/" + area + "/operacionVolumetriaDqo",
            data: {
                sw:sw,
                parametro: $('#formulaTipo').val(),
                ABS:$('#abs1').val(),
                CA:$('#blanco1').val(),
                CB:$('#b1').val(),
                CM:$('#m1').val(),
                CR:$('#r1').val(),
                D:$('#fDilucion1').val(),
                E:$('#volMuestra1').val(),
                X:$('#abs11').val(),
                Y:$('#abs21').val(),
                Z:$('#abs31').val(),
                _token: $('input[name="_token"]').val()
            },
            dataType: "json",
            success: function (response) {
                console.log(response);
                let x = response.x.toFixed(3);
              $("#abs1").val(x); 
              $("#abs2").val(x); 
              let resultado = response.res.toFixed(3);
              $("#resultado").val(resultado); 
              let d = response.d.toFixed(3);
              $("#fDilucion1").val(d);
              $("#fDilucion2").val(d);
            }
        });
    }
}

function guardarDqo(sw) {
if (sw == 1) {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/guardarDqo",
        data: {
            sw:sw,
            idDetalle: idMuestra,
            B: $("#tituladoDqo1").val(),
            C: $("#MolaridadDqo1").val(),
            CA: $("#blancoDqo1").val(),
            D: $("#factorDqo1").val(),
            E: $("#volDqo1").val(),
            radio: $("#estadoRadio").val(),
            resultado: $("#resultadoDqo").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            alert("GUARDADO");
            getLoteCapturaVol();
        }
    });
} else {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/guardarDqo",
        data: {
            sw:sw,
            idDetalle: idMuestra,
            fechaAnalisis: $("#fechaAnalisis").val(),
            parametro: $('#formulaTipo').val(),
            resultado: $('#resultado').val(),
            observacion: $('#obs').val(),
            ABS:$('#abs1').val(),
            CA:$('#blanco1').val(),
            CB:$('#b1').val(),
            CM:$('#m1').val(),
            CR:$('#r1').val(),
            D:$('#fDilucion1').val(),
            E:$('#volMuestra1').val(),
            X:$('#abs11').val(),
            Y:$('#abs21').val(), 
            Z:$('#abs31').val(),
            radio: $("#estadoRadio").val(),
            _token: $('input[name="_token"]').val() 
        },
        dataType: "json",
        success: function (response) { 
            console.log(response);
            alert("¡GUARDADO!");
            getLoteCapturaVol();   
        }
    });
}
}
function guardarNitrogeno() {

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/guardarNitrogeno",
        data: {
            idDetalle: idMuestra,
            A: $("#tituladosNitro1").val(),
            B: $("#blancoNitro1").val(),
            C: $("#molaridadNitro1").val(),
            D: $("#factorNitro1").val(),  
            E: $("#conversion1").val(),
            G: $("#volNitro1").val(),
            resultado: $("#resultadoNitro").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            getLoteCapturaVol();
        }
    });
}
function guardarNitrogenoEquipo() {

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/guardarNitrogenoEquipo",
        data: {
            idDetalle: idMuestra,
            A: $("#factor1E").val(),
            B: $("#concentracion1E").val(),
            C: $("#volAñadidoStd1E").val(),
            D: $("#VolAñadidoMuestra1E").val(),
            V: $("#volumenMuestraE1").val(),
            O: $("#observacionNitroE").val(),
            resultado: $("#resultadoNitroEquipo").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            getLoteCapturaVol();
        }
    });
}

function updateObsVolumetria(caso) {
    switch (caso) {
        //Cloro
        case 1:
                $.ajax({
                type: "POST",
                url: base_url + "/admin/laboratorio/" + area + "/updateObsVolumetria",
                data: {
                    idParametro: $("#formulaTipo").val(),
                    idDetalle: idMuestra,
                    caso:1,
                    observacion: $("#observacionCloro").val(),
                    _token: $('input[name="_token"]').val()
                },
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    alert("Observacion Aplicada");
                    getLoteCapturaVol();
                }
            });
            break; 
            case 2: //dbo
                $.ajax({
                type: "POST",
                url: base_url + "/admin/laboratorio/" + area + "/updateObsVolumetria",
                data: {
                    idParametro: $("#formulaTipo").val(),
                    idDetalle: idMuestra,
                    caso:2,
                    observacion: $("#observacionModal").val(),
                    _token: $('input[name="_token"]').val()
                },
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    alert("Observacion Aplicada");
                    getLoteCapturaVol();
                }
            });
            break; 
        case 3:
            $.ajax({
                type: "POST",
                url: base_url + "/admin/laboratorio/" + area + "/updateObsVolumetria",
                data: {
                    idParametro: $("#formulaTipo").val(),
                    idDetalle: idMuestra,
                    caso:3,
                    observacion: $("#observacionNitro").val(),
                    _token: $('input[name="_token"]').val()
                },
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    alert("Observacion Aplicada");
                    getLoteCapturaVol();
                }
            });
            break;
        default:
            return
    }


}
function enviarObsGeneralVol(){
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/enviarObsGeneralVol",
        data: {
            idParametro: $("#formulaTipo").val(),
            idDetalle: idMuestra,
            idLote: idLote,
            observacion: $("#observacion").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            getLoteCapturaVol();
        }
    });
}

function getDetalleVol(idDetalle, caso) {

    var radio = $('input:radio[name=tipo'+idDetalle+']:checked').val();
    if (radio == 2) {
        $('#captura'+idDetalle+'').attr('data-target','#modalDqo')
    } else if (radio == 1) {
        $('#captura'+idDetalle+'').attr('data-target','#modalEspectroDbo')
    }
    /*
        Caso 1 = Cloro
        caso 2 = DQO
        Caso 3 = Nitrogeno
    */
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getDetalleVol",
        data: {
            caso: caso,
            formulaTipo: $("#formulaTipo").val(),
            idDetalle: idDetalle,
            radio:radio,
            fechaAnalisis: $('#fechaAnalisis').val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            $("#estadoRadio").val(response.estadoRadio);
            $("#estadoRadioEspectro").val(response.estadoRadio);
            switch (caso) {
                case 1: // Cloro
                    if(response.model.Resultado != null)
                    {
                        $("#cloroA1").val(response.model.Vol_muestra);
                        $("#cloroE1").val(response.model.Ml_muestra);
                        $("#cloroH1").val(response.model.Ph_final);
                        $("#cloroG1").val(response.model.Ph_inicial);
                        $("#cloroB1").val(response.valoracion.Blanco);
                        $("#cloroC1").val(response.valoracion.Resultado);
                        $("#cloroD1").val(response.model.Factor_conversion);
                        $("#resultadoCloro").val(response.model.Resultado);
                        $("#observacionCloro").val(response.model.Observacion);
                    }else{
                        $("#cloroB1").val(response.valoracion.Blanco);
                        $("#cloroC1").val(response.valoracion.Resultado);
                    }
                    break;
                case 2: // DQO
                    if(response.valoracion == ""){ // Tubo sellado
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
                        $("#resultado").val(response.model.Resultado);
                       
                        console.log("Tubo sellado");
                    }else{
                        if(response.model.Resultado != null)
                        {
                            $("#tituladoDqo1").val(response.model.Titulo_muestra);
                            $("#MolaridadDqo1").val(response.valoracion.Resultado);
                            $("#blancoDqo1").val(response.valoracion.Blanco);
                            $("#factorDqo1").val(response.model.Equivalencia);
                            $("#volDqo1").val(response.model.Vol_muestra);
                            $("#resultadoDqo").val(response.model.Resultado);
                            $("#observacionDqo").val(response.model.Observacion);
                           
                            
                        }else{
                            $("#MolaridadDqo1").val(response.valoracion.Resultado);
                            $("#blancoDqo1").val(response.valoracion.Blanco); 
                        }
                        console.log("Tubo Reflujo");
                    }
                    break;
                case 3: // NITROGENO TOTAL
                    if(response.model.Resultado != null)
                    {
                        $("#tituladosNitro1").val(response.model.Titulado_muestra);
                        $("#blancoNitro1").val(response.valoracion.Blanco);
                        $("#molaridadNitro1").val(response.valoracion.Resultado);
                       // $("#factorNitro1").val(response.model.Factor_equivalencia);
                        //$("#conversion1").val(response.model.Factor_conversion);
                        $("#volNitro1").val(response.model.Vol_muestra);
                        $("#observacionNitro").val(response.model.Observacion);
                        $("#resultadoNitro").val(response.model.Resultado);
                    }else{
                        $("#blancoNitro1").val(response.valoracion.Blanco);
                        $("#molaridadNitro1").val(response.valoracion.Resultado);
                    }

                    break;
                default:
                    break;
            }

        }
    });
}
function liberarMuestra() {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/liberarMuestraVol",
        data: {
            idMuestra: idMuestra,
            idLote: idLote,
            formulaTipo: $("#formulaTipo").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            if (response.sw == true) {
                getDataCaptura();
                getLoteCapturaVol();
            } else {
                alert("La muestra no se pudo liberar");
            }
        }
    });
}
function liberarTodo() 
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/liberarTodo",
        data: {
            idLote:idLote,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            if(response.sw == true)
            {
                getDataCaptura();
                getLoteCapturaVol();
            }else{
                alert("La muestra no se pudo liberar");
            }
        }
    });
}

function createControlesCalidad()
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/createControlesCalidadVol",
        data: {
            idLote:idLote,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            getDataCaptura();
            getLoteCapturaVol();
        }
    });
}

function random(min, max) {
    return Math.floor(Math.random() * (max - min + 1) + min);
}
function createControlCalidad() {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/createControlCalidadVol",
        data: {
            idParametro: $("#formulaTipo").val(),
            idMuestra: idMuestra,
            idControl: $("#controlCalidad").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            getLoteCapturaVol();
        }
    });
}
function cleanTable() {

    let tabla = document.getElementById('divTablaControles');
    let tab = '';

    tab += '<table id="tablaControles" class="table table-sm">';
    tab += '    <thead>';
    tab += '        <tr>';
    tab += '          <th>Opc</th>';
    tab += '          <th>Folio</th>';
    // tab += '          <th># toma</th>';
    tab += '          <th>Norma</th>';
    tab += '          <th>Resultado</th>';
    tab += '          <th>Observación</th>';
    tab += '        </tr>';
    tab += '    </thead>';
    tab += '    <tbody>';
    tab += '    </tbody>';
    tab += '</table>';
    tabla.innerHTML = tab;
}