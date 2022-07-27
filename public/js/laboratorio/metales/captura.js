var area = "metales"
var numMuestras = new Array();
var idMuestra = 0; 
var idLote = 0;
var tecnica = 0;
$(document).ready(function () {

});


$('#ejecutar').click(function(){
    operacion();
});
$('#btnLiberar').click(function(){
    // operacion();
    liberarMuestraMetal();
});
function getDataCaptura() {
    cleanTable(); 
    numMuestras = new Array();
    let tabla = document.getElementById('divLote');
    let tab = '';


        $.ajax({ 
            type: "POST",
            url: base_url + "/admin/laboratorio/"+area+"/getLote",
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
    let tabla = document.getElementById('divTablaControles');
    let tab = '';
    let cont = 1;

    let status = "";

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

            tab += '<table id="tablaControles" class="table table-sm">';
            tab += '    <thead>';
            tab += '        <tr>';
            // tab += '          <th>#</th>';
            tab += '          <th>Muestra</th>';
            tab += '          <th>Cliente</th>';
            //tab2 += '          <th>PuntoMuestreo</th>';
            tab += '          <th>Vol. Muestra E</th>';
            tab += '          <th>Abs1</th>';
            tab += '          <th>Abs2</th>';
            tab += '          <th>Abs3</th>';
            tab += '          <th>Absorción promedio</th>';
            tab += '          <th>Factor dilución D</th>';
            tab += '          <th>Factor conversion G</th>';
            tab += '          <th>Resultado</th>';
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
                tab += '<td><input '+status+' style="width: 80px" id="volMuestra'+item.Id_detalle+'" value="50"></td>';
                tab += '<td><input '+status+' style="width: 80px" id="abs1'+item.Id_detalle+'" value="'+item.Abs1+'"></td>';
                tab += '<td><input '+status+' style="width: 80px" id="abs2'+item.Id_detalle+'" value="'+item.Abs2+'"></td>';
                tab += '<td><input '+status+' style="width: 80px" id="abs3'+item.Id_detalle+'" value="'+item.Abs3+'"></td>';
                tab += '<td><input '+status+' style="width: 80px" id="absPromedio'+item.Id_detalle+'" value="'+item.Abs_promedio+'"></td>';
                tab += '<td><input '+status+' style="width: 80px" id="factorDilucion'+item.Id_detalle+'" value="'+item.Factor_dilucion+'"></td>';
                tab += '<td><input '+status+' style="width: 80px" id="factorConversion'+item.Id_detalle+'" value="'+item.Factor_conversion+'"></td>';
                tab += '<td><input '+status+' style="width: 80px" id="VolDisolucion'+item.Id_detalle+'" value="'+item.Vol_disolucion+'"></td>';

                tab += '</tr>';
                numMuestras.push(item.Id_detalle);
                cont++; 
         
            }); 
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;

            var t = $('#tablaControles').DataTable({
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
function imprimir(){        
    window.open(base_url + "/admin/laboratorio/captura/exportPdfCaptura/"+idLote);
    //window.location = base_url + "/admin/laboratorio/captura/exportPdfCaptura/"+idLote;
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
             x:$("#abs1"+idMuestra).val(),
             y:$("#abs2"+idMuestra).val(),
             z:$("#abs3"+idMuestra).val(),
             FD:$("#factorDilucion"+idMuestra).val(),
            _token: $('input[name="_token"]').val()
        }, 
        dataType: "json",
        success: function (response) {            
            console.log(response);
            let fix = response.resultado.toFixed(3); 
            $("#absPromedio"+idMuestra).val(response.promedio);
            $("#VolDisolucion"+idMuestra).val(fix);
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
                        tab2 += '<td>'+item.Empresa+' <br> <small class="text-danger">'+item.Descripcion+'</small></td>';
                    }else{
                        tab2 += '<td>'+item.Empresa+' <br> <small class="text-info">'+item.Descripcion+'</small></td>';
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