var area = "micro";

$(document).ready(function () {

});

$('#metodoCortoCol').click(function (){
    metodoCortoCol();
    $('#indicador').val(1);
    console.log("metodo corto");
});

$('#ejecutar').click(function () {
    operacion();
});
$('#btnLiberar').click(function () {
    // operacion();
    liberarMuestra();
});

$('#limpiar').click(function () {
    limpiar();
});

var numMuestras = new Array();
var idMuestra = 0;
var idLote = 0;
var tecnica = 0;

function getLoteMicro() {
    cleanTable();
    numMuestras = new Array();
    let tabla = document.getElementById('divLote');
    let tab = '';


    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getLoteMicro",
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
                getLoteCapturaMicro();
            });
        }
    });
}


function getLoteCapturaMicro() {
    numMuestras = new Array();
    let tabla = document.getElementById('divTablaControles');
    let tab = '';
    let cont = 1;

    let status = "";

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getLoteCapturaMicro",
        data: {
            idLote: idLote,
            formulaTipo: $("#formulaTipo").val(),
            tecnica: tecnica,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);

            tab += '<table id="tablaControles" class="table table-sm">';
            tab += '    <thead>';
            tab += '        <tr>';
            tab += '          <th>Opc</th>';
            tab += '          <th>Folio</th>';
            tab += '          <th>#Tipo</th>'; //agregado radio button
            tab += '          <th>Norma</th>';
            tab += '          <th>Resultado</th>';
            tab += '          <th>Observación</th>';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            $.each(response.detalle, function (key, item) {
                tab += '<tr>';
                if (item.Liberado == null) {
                    status = "";
                    clase = "btn btn-success";
                } else {
                    status = "disabled";
                    clase = "btn btn-warning";
                }
                switch ($("#formulaTipo").val()) {
                    case "12":
                    
                        tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button type="button" '+status+' class="'+clase+'" onclick="getDetalleCol(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalCapturaCol">Capturar</button>';
                        console.log("Entro a 12");
                        break;
                        case "35":
                    case "253":
                        tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button type="button" '+status+' class="'+clase+'" onclick="getDetalleCol(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalCapturaEnt">Capturar</button>';
                        break;
                    case "5":
                        if (item.Id_control == 5) {
                            tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button type="button" '+status+' class="'+clase+'" onclick="getDetalleDbo(' + item.Id_detalle + ', 1);"data-toggle="modal" data-target="#modalCapturaDboBlanco">Capturar</button>';
                        } else {
                            tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button type="button" '+status+' class="'+clase+'" onclick="getDetalleDbo(' + item.Id_detalle + ', 1);" data-toggle="modal" data-target="#modalCapturaDbo">Capturar</button>';
                        }
                        break;
                    case "16":
                        tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button type="button" '+status+' class="'+clase+'" onclick="getDetalleHH(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalCapturaHH">Capturar</button>';
                        break;
                    case "134":
                        tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button type="button" '+status+' class="'+clase+'" onclick="getDetalleColiAlimentos(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalColiformesAlimentos">Capturar</button>';
                        break;
                    default:
                        console.log("Entro a al limbo");
                        break;
                }
                if (item.Id_control != 1) {
                    tab += '<br> <small class="text-danger">' + item.Control + '</small></td>';
                } else {
                    tab += '<br> <small class="text-info">' + item.Control + '</small></td>';
                }
                // Radio para el tipo de la muestra
                tab += '<td><radio type="radio" name="radio" id="radioTipo"></radio></td>';
                tab += '<td><input disabled style="width: 200px" value="' + item.Codigo + '"></td>';
                // tab += '<td><input disabled style="width: 80px" value="-"></td>';
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
function operacionColAlimentos(idDetalle){
    var presuntiva1 = $("#pres124").val();
    var presuntiva2 = $("#pres148").val();
    var confir1 = $("#confir124").val();
    var confir2 = $("#confir148").val();
    if (presuntiva2 > presuntiva1)
    {
        alert("La presuntiva de 48hrs no puede ser mayor a la de 24hrs")
    }else if (confir2 > confir1)
    {
        alert("La confirmativas de 48hrs no puede ser mayor a la de 24hrs")
    }
    else{

        $.ajax({
            type: "POST",
            url: base_url + "/admin/laboratorio/" + area + "/operacionColAlimentos",
            data: {
                idDetalle: idMuestra,
                presuntiva1:$("#pres124").val(),
                presuntiva2:$("#pres148").val(),
                confirmativa1:$("#confir124").val(),
                confirmativa2:$("#confir148").val(),
                _token: $('input[name="_token"]').val()
            },
            dataType: "json",
            success: function (response) {
                console.log(response)
                if (response.resultado == 8.0){
                    $("#resultadoColAlimentos").val(">"+response.resultado)
                } else {
                    $("#resultadoColAlimentos").val(response.resultado)
                }
                getLoteCapturaMicro();
            }
        });
    
    }
    
}
function getDetalleColiAlimentos(idMuestra){
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getDetalleColiAlimentos",
        data: {
            idDetalle: idMuestra,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response)
            $("#pres124").val(response.model.Presuntiva1)
            $("#pres148").val(response.model.Presuntiva2)
            $("#confir124").val(response.model.Confirmativa1)
            $("#confir148").val(response.model.Confirmativa2)
            $("#resultadoColAlimentos").val(response.model.Resultado)
        }
    });
}

function getDetalleCol(idDetalle) {
    limpiar();
    $('#indicador').val("");
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getDetalleCol",
        data: {
            idDetalle: idDetalle,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            $("#dil1").val(response.model.Dilucion1);
            $("#dil2").val(response.model.Dilucion2);
            $("#dil3").val(response.model.Dilucion3);
            $("#nmp1").val(response.model.Indice);
            $("#todos1").val(response.model.Muestra_tubos);
            $("#negativos1").val(response.model.Tubos_negativos);
            $("#positivo1").val(response.model.Tubos_positivos);
            $("#con1").val(response.model.Confirmativa1);
            $("#con2").val(response.model.Confirmativa2);
            $("#con3").val(response.model.Confirmativa3);
            $("#con4").val(response.model.Confirmativa4);
            $("#con5").val(response.model.Confirmativa5);
            $("#con6").val(response.model.Confirmativa6);
            $("#con7").val(response.model.Confirmativa7);
            $("#con8").val(response.model.Confirmativa8);
            $("#con9").val(response.model.Confirmativa9);
            $("#pre1").val(response.model.Presuntiva1);
            $("#pre2").val(response.model.Presuntiva2);
            $("#pre3").val(response.model.Presuntiva3);
            $("#pre4").val(response.model.Presuntiva4);
            $("#pre5").val(response.model.Presuntiva5);
            $("#pre6").val(response.model.Presuntiva6);
            $("#pre7").val(response.model.Presuntiva7);
            $("#pre8").val(response.model.Presuntiva8);
            $("#pre9").val(response.model.Presuntiva9);

            $("#resultadoCol").val(response.model.Resultado);
            $("#observacionCol").val(response.model.Observacion);
        }
    });
}

function getDetalleHH(idDetalle) {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getDetalleHH",
        data: {
            idDetalle: idDetalle,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            $("#lum1").val(response.model.A_alumbricoides);
            $("#na1").val(response.model.H_nana);
            $("#sp1").val(response.model.Taenia_sp);
            $("#tri1").val(response.model.T_trichiura);
            $("#uni1").val(response.model.Uncinarias);
            $("#volH1").val(response.model.Vol_muestra);
            $("#resultadoHH").val(response.model.Resultado);

            
        }
    });
}
function getDetalleDbo(idDetalle, tipo) {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getDetalleDbo",
        data: {
            idDetalle: idDetalle,
            tipo:tipo,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            switch (tipo) {
                case 1: // ResNormal
                    $('#botellaF1').val(response.model.Botella_final);
                    $('#od1').val(response.model.Botella_od);
                    $('#oxiFinal1').val(response.model.Odf);
                    $('#oxiInicial1').val(response.model.Odi);
                    $('#phF1').val(response.model.Ph_final);
                    $('#phIni1').val(response.model.Ph_inicial);
                    $('#volDbo1').val(response.model.Vol_muestra);
                    $('#dil1').val(response.model.Dilucion);
                    $('#win1').val(response.model.Vol_botella);
        
                    $("#observacionDbo").val(response.model.Observacion);
                    $("#resDbo").val(response.model.Resultado);
                    if(response.model.Sugerido == 1)
                    {
                        document.getElementById("sugeridoDbo").checked = true;
                    }else{
                        document.getElementById("sugeridoDbo").checked = false;
                    }
                    if (response.model2 == NULL) {
                        $('#resDqo').val("N/A");
                    } else {
                        $('#resDqo').val(response.model2.Resultado);
                    }
                    break;
                case 2: // Res blanco
                    $('#oxigenoIncialB1').val(response.model.Odi);
                    $('#oxigenofinalB1').val(response.model.Odf);
                    $('#volMuestraB1').val(response.model.Vol_muestra);
                    $('#resresDboBDqo').val(response.model.Resultado);
                    break;

                default:
                    break;
            }
      

        }
    });
}

function createControlesCalidadMb()
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/createControlesCalidadMb",
        data: {
            idLote:idLote,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            getDataCaptura();
            getLoteCapturaMicro();
        }
    });
}


//Función imprimir PDF
function imprimir(id) {
    //ABRE LA RUTA ESPECÍFICADA EN UNA NUEVA PESTAÑA
    window.open(base_url + "/admin/laboratorio/" + area + "/captura/exportPdfCapturaMb/" + id);
    //window.location = base_url + "/admin/laboratorio/"+area+"/captura/exportPdfCaptura/" + idLote;    
}
function metodoCortoCol(){
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/metodoCortoCol",
        data: {
            tecnica: tecnica,
            idDetalle: idMuestra,
            indicador: $('#indicador').val(), 
            resultadoCol: $("#resultadoCol").val(),
            idParametro: $('#formulaTipo').val(),
            NMP: $('#nmp1').val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            getLoteCapturaMicro();
            // inicio metodo corto
        
          
                let positivos = response.positivos;
 
                $('#nmp1').val(response.convinacion.Nmp);
                $('#positivos1').val(positivos);
                $('#negativos1').val(9 - positivos);
                let cont1 = 1;
                let cont2= 4;
                let cont3  = 7;
                // Confirmativas
                for (var i = 0; i < response.convinacion.Col1; i++){
                    $('#con'+cont1).val(1);
                    console.log(cont1);
                    cont1++; 
                }
                for (var j = 0; j < response.convinacion.Col2; j++){
                    
                    $('#con'+cont2).val(1);
                    console.log(cont2);
                    cont2++;
                    
                }
                for (var k = 0; k < response.convinacion.Col3; k++){
                    $('#con'+cont3).val(1);
                    console.log(cont3);
                    cont3++;
                }
                // presuntivas 
                let c1 = 1;
                let c2 = 4;
                let c3 = 7;
                let ran1 = Math.random() * response.convinacion.Col1;
                let ran2 = Math.random() * response.convinacion.Col2;
                let ran3 = Math.random() * response.convinacion.Col3;
                for (var i = 0; i < 3; i++){
                    $('#pre'+c1).val(1);
                    console.log(ran1);
                    c1++; 
                }
                for (var i = 0; i < ran2; i++){
                    $('#pre'+c2).val(1);
                    console.log(ran2);
                    c2++; 
                }
                for (var i = 0; i < ran3; i++){
                    $('#pre'+c3).val(1);
                    console.log(ran3);
                    c3++; 
                }


            if (response.convinacion.Nmp == 0) {
                $('#resultadoCol').val("< 3");
            } else {
                $('#resultadoCol').val(response.convinacion.Nmp);
            }
            $('#nmp1').val(response.convinacion.Nmp)
            
    }
    });
}

function operacionCol() {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/operacion",
        data: {
            tecnica: tecnica,
            idDetalle: idMuestra,
            resultadoCol: $("#resultadoCol").val(),
            idParametro: $('#formulaTipo').val(),
            D1: $('#dil1').val(),
            D2: $('#dil2').val(),
            D3: $('#dil3').val(),
            NMP: $('#nmp1').val(),
            G3: $('#todos1').val(),
            G2: $('#negativos1').val(),
            G1: $('#positivos1').val(),
            con3: $("#con3").val(),
            con2: $("#con2").val(),
            con1: $("#con1").val(),
            con4: $("#con4").val(),
            con5: $("#con5").val(),
            con6: $("#con6").val(),
            con7: $("#con7").val(),
            con8: $("#con8").val(),
            con9: $("#con9").val(),
            pre1: $("#pre1").val(),
            pre2: $("#pre2").val(),
            pre3: $("#pre3").val(),
            pre4: $("#pre4").val(),
            pre5: $("#pre5").val(),
            pre6: $("#pre6").val(),
            pre7: $("#pre7").val(),
            pre8: $("#pre8").val(),
            pre9: $("#pre9").val(),

            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            getLoteCapturaMicro();
            // inicio metodo corto
            if (response.metodoCorto == 1) {
                console.log("metodo corto hecho!");
            } else {

            if (response.res == 0) {
                $('#resultadoCol').val("< 3");
            } else {
                $('#resultadoCol').val(response.res);
            }
            $('#nmp1').val(response.res)
            $('#indicador').val("");
        }
    }
    });
}

function operacionEnt() {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/operacion",
        data: {
            tecnica: tecnica,
            idDetalle: idMuestra,
            resultadoCol: $("#resultadoEnt").val(),
            idParametro: $('#formulaTipo').val(),
            D1: $('#endil1').val(),
            D2: $('#endil2').val(),
            D3: $('#endil3').val(),
            NMP: $('#ennmp1').val(),
            G3: $('#entodos1').val(),
            G2: $('#ennegativos1').val(),
            G1: $('#enpositivos1').val(),
            
            Presuntiva11: $("#enPre1").val(),
            Presuntiva12: $("#enPre2").val(),
            Presuntiva13: $("#enPre3").val(),
            Presuntiva14: $("#enPre4").val(),
            Presuntiva15: $("#enPre5").val(),
            Presuntiva16: $("#enPre6").val(),
            Presuntiva17: $("#enPre7").val(),
            Presuntiva18: $("#enPre8").val(),
            Presuntiva19: $("#enPre9").val(),
            
            Presuntiva21: $("#enPre12").val(),
            Presuntiva22: $("#enPre22").val(),
            Presuntiva23: $("#enPre32").val(),
            Presuntiva24: $("#enPre42").val(),
            Presuntiva25: $("#enPre52").val(),
            Presuntiva26: $("#enPre62").val(),
            Presuntiva27: $("#enPre72").val(),
            Presuntiva28: $("#enPre82").val(),
            Presuntiva29: $("#enPre92").val(),

            Confirmativa11: $("#enCon1").val(),
            Confirmativa12: $("#enCon2").val(),
            Confirmativa13: $("#enCon3").val(),
            Confirmativa14: $("#enCon4").val(),
            Confirmativa15: $("#enCon5").val(),
            Confirmativa16: $("#enCon6").val(),
            Confirmativa17: $("#enCon7").val(),
            Confirmativa18: $("#enCon8").val(),
            Confirmativa19: $("#enCon9").val(),
            
            Confirmativa21: $("#enCon12").val(),
            Confirmativa22: $("#enCon22").val(),
            Confirmativa23: $("#enCon32").val(),
            Confirmativa24: $("#enCon42").val(),
            Confirmativa25: $("#enCon52").val(),
            Confirmativa26: $("#enCon62").val(),
            Confirmativa27: $("#enCon72").val(),
            Confirmativa28: $("#enCon82").val(),
            Confirmativa29: $("#enCon92").val(),

            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            getLoteCapturaMicro();
            // inicio metodo corto
            if (response.metodoCorto == 1) {
                console.log("metodo corto hecho!");
            } else {

            if (response.res == 0) {
                $('#resultadoEnt').val("< 3");
            } else {
                $('#resultadoEnt').val(response.res);
            }
            $('#ennmp1').val(response.res)
            $('#indicador').val("");
        }
    }
    });
}


function operacionDbo(tipo) {
    let sug = 0;
    if (document.getElementById("sugeridoDbo").checked == true) {
        sug = 1;
    }
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/operacion",
        data: {
            tipo:tipo,
            tecnica: tecnica,
            idParametro: $("#formulaTipo").val(),
            idDetalle: idMuestra,
            Observacion: $('#observacion').val(),
            H: $('#botellaF1').val(),
            G: $('#od1').val(),
            B: $('#oxiFinal1').val(),
            A: $('#oxiInicial1').val(),
            J: $('#phF1').val(),
            I: $('#phIni1').val(),
            D: $('#volDbo1').val(),
            E: $('#dil1').val(),
            C: $('#win1').val(),
            OI: $('#oxigenoIncialB1').val(),
            OF: $('#oxigenofinalB1').val(),
            V: $('#volMuestraB1').val(),
            S: sug,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            if (tipo == 1) {
                $('#resDbo').val(response.res);   
            } else {
                $('#resDboB').val(response.res);
            }
            getLoteCapturaMicro();
        }
    });
}

function operacionHH() {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/operacion",
        data: {
            idParametro: $("#formulaTipo").val(),
            idDetalle: idMuestra,
            tecnica: tecnica,
            lum1: $("#lum1").val(),
            na1: $("#na1").val(),
            sp1: $("#sp1").val(),
            tri1: $("#tri1").val(),
            uni1: $("#uni1").val(),
            volH1: $("#volH1").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            $("#resultadoHH").val(response.res);
            getLoteCapturaMicro();
        }
    });
}
function updateObsMuestra(caso, obs) {

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/updateObsMuestra",
        data: {
            caso: caso,
            idMuestra: idMuestra,
            observacion: $("#" + obs).val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            getLoteCapturaMicro();
        }
    });
}
function createControlCalidad() {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/createControlCalidadMb",
        data: {
            idParametro: $("#formulaTipo").val(),
            idMuestra: idMuestra,
            idControl: $("#controlCalidad").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            getLoteCapturaMicro();
        }
    });
}
function liberarMuestra() {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/liberarMuestra",
        data: {
            idMuestra: idMuestra,
            idLote: idLote,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            if (response.sw == true) {
                getLoteMicro();
                getLoteCapturaMicro();
            } else {
                alert("La muestra no se pudo liberar");
            }
        }
    });
}
function random(min, max) {
    return Math.floor(Math.random() * (max - min + 1) + min);
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
function limpiar()
{
        $("#resultadoCol").val("")
        $('#nmp1').val("")
        $('#todos1').val("")
        $('#negativos1').val(0)
        $('#positivos1').val(0)
        $("#con3").val(0)
        $("#con2").val(0)
        $("#con1").val(0)
        $("#con4").val(0)
        $("#con5").val(0)
        $("#con6").val(0)
        $("#con7").val(0)
        $("#con8").val(0)
        $("#con9").val(0)
        $("#pre1").val(0)
        $("#pre2").val(0)
        $("#pre3").val(0)
        $("#pre4").val(0)
        $("#pre5").val(0)
        $("#pre6").val(0)
        $("#pre7").val(0)
        $("#pre8").val(0)
        $("#pre9").val(0)
        $("#indicador").val("")
}
