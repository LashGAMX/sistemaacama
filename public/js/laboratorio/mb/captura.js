var area = "micro";

$(document).ready(function () {
    table = $('#tableAnalisis').DataTable({        
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        }
    });
    table2 = $('#tableDatos2').DataTable({        
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        }
    });    

});


$('#ejecutar').click(function(){
    operacion();
});
$('#btnLiberar').click(function(){
    // operacion();
    liberarMuestraMetal();
}); 

var numMuestras = new Array();
var idMuestra = 0; 
var idLote = 0;
var tecnica = 0;
function getLoteMicro()
{
    numMuestras = new Array();
    let tabla = document.getElementById('divLote');
    let tab = '';


        $.ajax({ 
            type: "POST",
            url: base_url + "/admin/laboratorio/"+area+"/getLoteMicro",
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
                    tab += '<td>'+item.Id_lote+'</td>';
                    tab += '<td>'+item.Tipo_formula+'</td>';
                    tab += '<td>'+item.Fecha+'</td>';
                    tab += '<td>'+item.Asignado+'</td>';
                    tab += '<td>'+item.Liberado+'</td>';
                    tab += '<td><button class="btn btn-success" id="btnImprimir" onclick="imprimir();"><i class="fas fa-file-download"></i></button></td>';
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


                $('#tablaLote tbody').on( 'click', 'tr', function () {
                    if ( $(this).hasClass('selected') ) {
                        $(this).removeClass('selected');
                    }
                    else {
                        t.$('tr.selected').removeClass('selected');
                        $(this).addClass('selected');
                    }
                } );
                $('#tablaLote tr').on('click', function(){
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
            tab += '          <th># toma</th>';
            tab += '          <th>Norma</th>';
            tab += '          <th>Resultado</th>';
            tab += '          <th>Tipo Análisis</th>';
            tab += '          <th>Observación</th>';
            tab += '        </tr>';
            tab += '    </thead>'; 
            tab += '    <tbody>';
            $.each(response.detalle, function (key, item) {
                tab += '<tr>';
                if (item.Liberado != 0) {
                    status = "";
                } else { 
                    status = "disabled";
                }
                switch ($("#formulaTipo").val()) {
                    case "13":
                        tab += '<td><input hidden id="idMuestra'+item.Id_detalle+'" value="'+item.Id_detalle+'"><button type="button" class="btn btn-success" onclick="getDetalleCol('+item.Id_detalle+');" data-toggle="modal" data-target="#modalCapturaCol">Capturar</button>';
                        console.log("Entro a 13");
                        break;
                    case "262":
                        tab += '<td><input hidden id="idMuestra'+item.Id_detalle+'" value="'+item.Id_detalle+'"><button type="button" class="btn btn-success" onclick="getDetalleCol('+item.Id_detalle+');" data-toggle="modal" data-target="#modalCapturaCol">Capturar</button>';
                        break;
                    case "72":
                        tab += '<td><input hidden id="idMuestra'+item.Id_detalle+'" value="'+item.Id_detalle+'"><button type="button" class="btn btn-success" onclick="getDetalleDbo('+item.Id_detalle+');" data-toggle="modal" data-target="#modalCapturaDbo">Capturar</button>';
                        break;
                    case "17":
                        tab += '<td><input hidden id="idMuestra'+item.Id_detalle+'" value="'+item.Id_detalle+'"><button type="button" class="btn btn-success" onclick="getDetalleHH('+item.Id_detalle+');" data-toggle="modal" data-target="#modalCapturaHH">Capturar</button>';
                        break;
                    default:
                        console.log("Entro a al limbo");
                        break;
                }
                if (item.Id_control != 1) 
                {
                    tab += '<br> <small class="text-danger">'+item.Control+'</small></td>';
                }else{
                    tab += '<br> <small class="text-info">'+item.Control+'</small></td>';
                } 
                tab += '<td><input disabled style="width: 80px" value="'+item.Folio_servicio+'"></td>';
                tab += '<td><input disabled style="width: 80px" value="-"></td>';
                tab += '<td><input disabled style="width: 80px" value="'+item.Clave_norma+'"></td>';
                tab += '<td><input disabled style="width: 80px" value="-"></td>';
                tab += '<td><input disabled style="width: 80px" value="-"></td>';
                tab += '<td>'+item.Observacion+'</td>';
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
function getDetalleCol(idDetalle)
{
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
            
        }
    });
}

function getDetalleHH(idDetalle)
{
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
        }
    });
}

//Función imprimir PDF
function imprimir(){        
    //ABRE LA RUTA ESPECÍFICADA EN UNA NUEVA PESTAÑA
    window.open(base_url + "/admin/laboratorio/"+area+"/captura/exportPdfCaptura/" + idLote);
    //window.location = base_url + "/admin/laboratorio/"+area+"/captura/exportPdfCaptura/" + idLote;    
} 

function operacionCol()
{  
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/operacion",
        data: {
            tecnica:tecnica,
            idDetalle: idMuestra,
            idParametro:$('#tipoFormula').val(),
            D1:$('#dil1').val(),
            D2:$('#dil2').val(),
            D3:$('#dil3').val(),
            NMP:$('#nmp1').val(),
            G3:$('#todos1').val(),
            G2:$('#negativos1').val(),
            G1:$('#positivos1').val(),
            con3:$("#con3").val(),
            con2:$("#con2").val(),
            con1:$("#con1").val(),
            con4:$("#con4").val(),
            con5:$("#con5").val(),
            con6:$("#con6").val(),
            con7:$("#con7").val(),
            con8:$("#con8").val(),
            con9:$("#con9").val(),
            pre1:$("#pre1").val(),
            pre2:$("#pre2").val(),
            pre3:$("#pre3").val(),
            pre4:$("#pre4").val(),
            pre5:$("#pre5").val(),
            pre6:$("#pre6").val(),
            pre7:$("#pre7").val(),
            pre8:$("#pre8").val(),
            pre9:$("#pre9").val(),
            
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
           
            $('#resCol').val(response.res);
        }
    }); 
}


function operacionDbo()
{  
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/operacion",
        data: {
            tecnica:tecnica,
            idParametro: $("#formulaTipo").val(),
            idDetalle:idMuestra,
            H:$('#botellaF1').val(),
            G:$('#od1').val(),
            B:$('#oxiFinal1').val(),
            A:$('#oxiInicial1').val(),
            J:$('#phF1').val(),
            I:$('#phIni1').val(),
            D:$('#volDbo1').val(),
            E:$('#dil1').val(),
            C:$('#win1').val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
           
            $('#resDbo').val(response.res);
        }
    }); 
}

function operacionHH()
{  
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/operacion",
        data: {
            idParametro: $("#formulaTipo").val(), 
            idDetalle: idMuestra,
            tecnica:tecnica,
            lum1:$("#lum1").val(),
            na1:$("#na1").val(),
            sp1:$("#sp1").val(),
            tri1:$("#tri1").val(),
            uni1:$("#uni1").val(),
            volH1:$("#volH1").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            $("#resHH").val(response.res); 
        }
    }); 
}

function random(min, max) {
  return Math.floor(Math.random() * (max - min + 1) + min);
}