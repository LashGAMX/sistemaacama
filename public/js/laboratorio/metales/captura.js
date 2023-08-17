var area = "metales"
var numMuestras = new Array();
var idMuestra = 0; 
var idLote = 0;
var tecnica = 0;
$(document).ready(function () {
    $('#formulaTipo').select2();
    $('.select2').select2();
    
});

$("#formulaTipo").click(function () {
    $("#resDato").val("");
});
$('#enviarObservacion').click(function () {
    enviarObservacion();
    console.log("observacion")
    swal("Observación enviada!", "Enviado!", "success");
});

$('#btnGenerarControles').click(function(){
    createControlCalidadMetales();
});

$('#ejecutar').click(function(){
    operacion();
});
$('#btnLiberar').click(function(){
    // operacion();
    liberarMuestraMetal();
});
$('#btnLiberarTodo').click(function () {
    liberarTodo();
});

function getDataCaptura() {
    console.log("getDataCaptura")
    cleanTable(); 
    numMuestras = new Array();
    let tabla = document.getElementById('divLote');
    let tab = '';


        $.ajax({ 
            type: "POST",
            url: base_url + "/admin/laboratorio/"+area+"/getCapturaLote",
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
                    tab += '<td><button class="btn btn-success" id="btnImprimir" onclick="imprimir('+item.Id_lote+');"><i class="fas fa-file-download"></i></button></td>';
                    tab += '</tr>';
                }); 
                tab += '    </tbody>';
                tab += '</table>';
                tabla.innerHTML = tab;

                tecnica = response.tecnica;

                if(response.curva == null ){
                    alert("Este lote no tiene Curva de calibración")
                }
                $("#b").val(response.curva.B);
                $("#m").val(response.curva.M);
                $("#r").val(response.curva.R);


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
                    // getLoteCapturaVol();
                    getLoteCaptura()
                  });
            }
        });
}
var numMuestras = new Array();
function getLoteCaptura() {
    numMuestras = new Array();
    idMuestra = 0;
    let tabla = document.getElementById('divTablaControles');
    let tab = '';
    let cont = 1;

    let status = "";
    let hg = "hidden"

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getLoteCaptura",
        data: {
            idLote: idLote,
            formulaTipo: $("#formulaTipo").val(), 
            tecnica: tecnica,
            _token: $('input[name="_token"]').val() 
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            let aux = 0
            switch (parseInt(response.detalle[0].Id_parametro)) {
                case 215:
                case 195:
                case 230:
                case 188:
                case 189:
                case 196:
                case 190:
                case 194:
                case 192:
                case 191:
                case 204:
                case 219:
                    hg = ""    
                    break;
            
                default:
                    break;
            }
            tab += '<table id="tablaControles" class="table table-sm">';
            tab += '    <thead>';
            tab += '        <tr>';
            // tab += '          <th>#</th>';
            tab += '          <th>Muestra</th>';
            tab += '          <th>Cliente</th>';
            //tab2 += '          <th>PuntoMuestreo</th>';
            tab += '          <th>Vol. Muestra E</th>';
            tab += '          <th '+hg+'>Vol. D</th>';
            tab += '          <th>Abs1</th>';
            tab += '          <th>Abs2</th>';
            tab += '          <th>Abs3</th>';
            tab += '          <th>Absorbancia Prom.</th>';
            tab += '          <th>Factor dilución D</th>';
            tab += '          <th>Factor conversion G</th>';
            tab += '          <th>Resultado</th>';
            tab += '          <th>Observacion</th>';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            $.each(response.detalle, function (key, item) {
                tab += '<tr>';
                if (parseInt(item.Liberado) == 0) {
                    status = "";
                } else { 
                    status = "disabled";
                }
                
                tab += '<td><input style="width: 80px" hidden id="idDetalle'+item.Id_detalle+'" value="'+item.Id_detalle+'">'+item.Folio_servicio;
                if (item.Id_control != 1) 
                {
                    tab += '<br> <small class="text-danger">'+item.Control+'</small></td>';
                }else{
                    tab += '<br> <small class="text-info">'+item.Control+'</small></td>';
                }
                tab += '<td>'+item.Empresa_suc+'</td>';
                            
            switch (parseInt(response.detalle[0].Id_parametro)) {             
                case 189:
                case 196:
                case 190:
                case 194:
                case 192:
                case 191:
                case 204:
                case 230:
                    tab += '<td><input '+status+' style="width: 80px" id="volMuestra'+item.Id_detalle+'" value="100"></td>';
                    break;
                case 188:
                case 219:
                case 215:  
                case 195: 
                    tab += '<td><input '+status+' style="width: 80px" id="volMuestra'+item.Id_detalle+'" value="80"></td>';
                break;
                default:
                    tab += '<td><input '+status+' style="width: 80px" id="volMuestra'+item.Id_detalle+'" value="50"></td>';
                    break;
            }
                tab += '<td '+hg+'><input '+status+' style="width: 80px" id="volDirigido'+item.Id_detalle+'" value="100"></td>';
                tab += '<td><input '+status+' style="width: 80px" id="abs1'+item.Id_detalle+'" value="'+item.Abs1+'"></td>';
                tab += '<td><input '+status+' style="width: 80px" id="abs2'+item.Id_detalle+'" value="'+item.Abs2+'"></td>';
                tab += '<td><input '+status+' style="width: 80px" id="abs3'+item.Id_detalle+'" value="'+item.Abs3+'"></td>';
                tab += '<td><input '+status+' style="width: 80px" id="absPromedio'+item.Id_detalle+'" value="'+item.Abs_promedio+'"></td>';
                tab += '<td><input '+status+' style="width: 80px" id="factorDilucion'+item.Id_detalle+'" value="'+item.Factor_dilucion+'"></td>';
                
                tab += '<td><input '+status+' style="width: 80px" id="factorConversion'+item.Id_detalle+'" value="'+item.Factor_conversion+'"></td>';
                if (item.Vol_disolucion != null) {
                    tab += '<td><input '+status+' style="width: 80px" id="VolDisolucion'+item.Id_detalle+'" value="'+item.Vol_disolucion+'"></td>';   
                } else {
                    tab += '<td><input '+status+' style="width: 80px" id="VolDisolucion'+item.Id_detalle+'" value=""></td>';
                }
                if (item.Observacion != "") {
                    tab += '<td><input '+status+' style="width: 80px" id="obs'+item.Id_detalle+'" value="'+response.obs[aux]+'"></td>';   
                } else {
                    tab += '<td><input '+status+' style="width: 80px" id="obs'+item.Id_detalle+'" value="'+item.Observacion+'"></td>';
                }
                tab += '</tr>';
                numMuestras.push(item.Id_detalle);
                cont++; 
                aux++
         
            }); 
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;

            var t = $('#tablaControles').DataTable({
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
                    t.$('tr.selected').removeClass('selected');
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
function imprimir(idLote){        
    window.open(base_url + "/admin/laboratorio/metales/exportPdfCaptura/"+idLote);
    //window.location = base_url + "/admin/laboratorio/captura/exportPdfCaptura/"+idLote;
}


function enviarObservacion(){
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/metales/enviarObservacion",
        data: {
            // fecha:$("#fechaAnalisis").val(),
            // idParametro:$("#formulaTipo").val(),
            // idlote:idLote,
            idMuestra:$("#idDetalle"+idMuestra).val(),
            // ph:$("#ph").val(),
            // solidos:$("#solidos").val(),
            // olor: $("#olor").val(),
            // color: $("#color").val(),
            observacion:$("#observacion").val(),
            _token: $('input[name="_token"]').val()
        }, 
        dataType: "json",
        success: function (response) {            
            console.log(response);
            getLoteCaptura();
           
        }
    });
}

function operacion()
{ 

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/metales/operacion",
        data: {
            fecha:$("#fechaAnalisis").val(),
            idParametro:$("#formulaTipo").val(),
            idlote:idLote,
            idDetalle:$("#idDetalle"+idMuestra).val(),
            volMuestra:$("#volMuestra"+idMuestra).val(),
            volDirigido:$("#volDirigido"+idMuestra).val(),
             x:$("#abs1"+idMuestra).val(),
             y:$("#abs2"+idMuestra).val(),
             z:$("#abs3"+idMuestra).val(),
             FD:$("#factorDilucion"+idMuestra).val(),
             FC:$("#factorConversion"+idMuestra).val(),
             obs:$("#obs"+idMuestra).val(),
            _token: $('input[name="_token"]').val()
        }, 

        dataType: "json",
        success: function (response) {            
            console.log(response);
            let fix = response.resultado.toFixed(3); 
            $("#absPromedio"+idMuestra).val(response.promedio);
            $("#VolDisolucion"+idMuestra).val(fix);
            $("#resDato").val(fix)
        }
    });
}
function createControlCalidadMetales()
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/createControlCalidadMetales",
        data: {
            idLote: idLote,
            idMuestra: idMuestra,
            idControl: $("#controlCalidad").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            getDataCaptura()
            getLoteCaptura()
        }
    });
}

function createControlCalidad()
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/createControlCalidad",
        data: {
            idMuestra: idMuestra,
            idLote: idLote,
            idControl: $("#controlCalidad").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            getLoteCaptura()
        
        }
    });
}

function generarControles()
{
    var ranCon = new Array();

    ranCon.push(random(0,numMuestras.length - 1));
    ranCon.push(random(0,numMuestras.length - 1));
    ranCon.push(random(0,numMuestras.length - 1));
    ranCon.push(random(0,numMuestras.length - 1));
    ranCon.push(random(0,numMuestras.length - 1));

    let tabla2 = document.getElementById('divTablaControles');
    let tab2 = '';

    let cont = 1;

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/metales/setControlCalidad",
        data: {
            ranCon:ranCon,
            numMuestra:numMuestras, 
            _token: $('input[name="_token"]').val()
        }, 
        dataType: "json",
        success: function (response) {            
            console.log(response);
    
            tab2 += '<table id="tablaControles" class="table table-sm">';
            tab2 += '    <thead class="">';
            tab2 += '        <tr>';
            tab2 += '          <th>NumMuestra</th>';
            tab2 += '          <th>NomCliente</th>';
            //tab2 += '          <th>PuntoMuestreo</th>';
            tab2 += '          <th>Vol. Muestra E</th>';
            tab2 += '          <th>Abs1</th>';
            tab2 += '          <th>Abs1</th>';
            tab2 += '          <th>Abs1</th>';
            tab2 += '          <th>Absorción promedio</th>';
            tab2 += '          <th>Factor dilución D</th>';
            tab2 += '          <th>Factor conversion G</th>';
            tab2 += '          <th>Resultado</th>';
            tab2 += '        </tr>';
            tab2 += '    </thead>';
            tab2 += '    <tbody>';
            $.each(response.detalle, function (key, item) {
                tab2 += '<tr>';
                tab2 += '<input style="width: 80px" hidden id="idDetalle'+cont+'" value="'+item.Id_detalle+'">';
                tab2 += '<td>'+item.Folio_servicio+'</td>';
                if(item.Descripcion != 'Resultado')
                    {
                        tab2 += '<td>'+item.Empresa+' <br> <small class="text-danger"><strong>'+item.Descripcion+'/</strong></small></td>';
                    }else{
                        tab2 += '<td>'+item.Empresa+' <br> <small class="text-info"><strong>'+item.Descripcion+'/</strong></small></td>';
                    }
                    if(item.Liberado != 0){
                        tab2 += '<td><input disabled style="width: 80px" id="volMuestra'+cont+'" value="50"></td>';
                        tab2 += '<td><input disabled style="width: 80px" id="abs1'+cont+'" value="'+item.Abs1+'"></td>';
                        tab2 += '<td><input disabled style="width: 80px" id="abs2'+cont+'" value="'+item.Abs2+'"></td>';
                        tab2 += '<td><input disabled style="width: 80px" id="abs3'+cont+'" value="'+item.Abs3+'"></td>';
                        tab2 += '<td><input disabled style="width: 80px" id="absPromedio'+cont+'" value="'+item.Abs_promedio+'"></td>';
                        tab2 += '<td><input disabled style="width: 80px" id="factorDilucion'+cont+'" value="'+item.Factor_dilucion+'"></td>';
                        tab2 += '<td><input disabled style="width: 80px" id="factorConversion'+cont+'" value="'+item.Factor_conversion+'"></td>';
                        tab2 += '<td><input disabled style="width: 80px" id="VolDisolucion'+cont+'" value="'+item.Vol_disolucion+'"></td>';
                    }else{
                        tab2 += '<td><input style="width: 80px" id="volMuestra'+cont+'" value="50"></td>';
                        tab2 += '<td><input style="width: 80px" id="abs1'+cont+'" value="'+item.Abs1+'"></td>';
                        tab2 += '<td><input style="width: 80px" id="abs2'+cont+'" value="'+item.Abs2+'"></td>';
                        tab2 += '<td><input style="width: 80px" id="abs3'+cont+'" value="'+item.Abs3+'"></td>';
                        tab2 += '<td><input style="width: 80px" id="absPromedio'+cont+'" value="'+item.Abs_promedio+'"></td>';
                        tab2 += '<td><input style="width: 80px" id="factorDilucion'+cont+'" value="'+item.Factor_dilucion+'"></td>';
                        tab2 += '<td><input style="width: 80px" id="factorConversion'+cont+'" value="'+item.Factor_conversion+'"></td>';
                        tab2 += '<td><input style="width: 80px" id="VolDisolucion'+cont+'" value="'+item.Vol_disolucion+'"></td>';
                    }
                tab2 += '</tr>';
                numMuestras.push(item.Id_detalle);
                cont++;
            });
            tab2 += '    </tbody>';
            tab2 += '</table>'; 
            tabla2.innerHTML = tab2;
            var t = $('#tablaControles').DataTable({        
                "ordering": false, 
                "language": {
                    "lengthMenu": "# _MENU_ por pagina",
                    "zeroRecords": "No hay datos encontrados",
                    "info": "Pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay datos encontrados",
                }
            });


            $('#tablaControles tbody').on( 'click', 'tr', function () {
                if ( $(this).hasClass('selected') ) {
                    $(this).removeClass('selected');
                }
                else {
                    table.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            } ); 
            $('#tablaControles tr').on('click', function(){
                let dato = $(this).find('td:first').html();
                idMuestra = dato;
              });
        }
    });
}
function liberarMuestraMetal()
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/liberarMuestraMetal",
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
                getDataCaptura();
                getLoteCaptura();
            }else{
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
                getLoteCaptura();
            }else{
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