
var area = "fq";
var idLote;

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


$('#ejecutarModal').click(function () {
    operacion();
    validacionModal(); 
});
$('#guardar').click(function (){
    
});
$('#btnLiberar').click(function () {
    // operacion();
    liberarMuestraMetal();
});

var numMuestras = new Array();
var idMuestra = 0;
function getDataCaptura() {
    numMuestras = new Array();
    let tabla = document.getElementById('divLote');
    let tab = '';

    let tabla2 = document.getElementById('divTablaControles');
    let tab2 = '';
    let cont = 1;

    let conte = document.getElementById('infoGlobal');
    let tab3 = '';
    let conte2 = document.getElementById('infoGen');
    let tab4 = '';
    let status = "";

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getDataCapturaEspectro",
        data: {
            formulaTipo: $("#formulaTipo").val(),
            fechaAnalisis: $("#fechaAnalisis").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);

            $("#idLote").val(response.lote.Id_lote);

            tab3 += '<p>B: ' + response.curvaConst.B + '</p>';
            tab3 += '<p>M: ' + response.curvaConst.M + '</p>';
            tab3 += '<p>R: ' + response.curvaConst.R + '</p>';
            conte.innerHTML = tab3;


            tab4 += '<p>Formula: x = [( y - b ) / m]</p>';
            conte2.innerHTML = tab4;

            tab += '<table id="tablaLote" class="table table-sm">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>Tipo formula</th>';
            tab += '          <th>Fecha lote</th> ';
            tab += '          <th>Total asignado</th> ';
            tab += '          <th>Total liberados</th> ';
            tab += '          <th>Opc</th> ';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            tab += '<tr>';
            tab += '<td>' + response.lote.Tipo_formula + '</td>';
            tab += '<td>' + response.lote.Fecha + '</td>';
            tab += '<td>' + response.lote.Asignado + '</td>';
            tab += '<td>' + response.lote.Liberado + '</td>';
            tab += '<td><button class="btn btn-success" id="btnImprimir" onclick="imprimir();"><i class="fas fa-file-download"></i></button></td>';
            tab += '</tr>';
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;


            tab2 += '<table id="tablaControles" class="table table-sm">';
            tab2 += '    <thead>';
            tab2 += '        <tr>';
            tab2 += '          <th>Opc</th>';
            tab2 += '          <th>folio</th>';
            tab2 += '          <th># Toma</th>';
            tab2 += '          <th>Norma</th>';
            tab2 += '          <th>Resultado</th>';
            tab2 += '          <th>Tipo análisis</th>';
            tab2 += '          <th>Observación</th>';
            tab2 += '        </tr>';
            tab2 += '    </thead>';
            tab2 += '    <tbody>';
            $.each(response.detalle, function (key, item) {
                tab2 += '<tr>';
                if (item.Liberado != 0) {
                    status = "";
                } else {
                    status = "disabled";
                }
                tab2 += '<td><button type="button" class="btn btn-success" onclick="getDetalleEspectro('+item.Id_detalle+');" data-toggle="modal" data-target="#modalCaptura">Capturar</button></td>';
                tab2 += '<td>'+item.Folio_servicio+'</td>';
                tab2 += '<td>'+item.Folio_servicio+'</td>';
                tab2 += '<td>Norma</td>';
                tab2 += '<td>Res</td>';
                tab2 += '<td>Tipo</td>';
                tab2 += '<td>Obs</td>';
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


            $('#tablaControles tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                }
                else {
                    table.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            });
            $('#tablaControles tr').on('click', function () {
                let dato = $(this).find('td:first').html();
                idMuestra = dato;
            });
            
            idLote = response.lote.Id_lote;            
            //imprimir(response.lote.Id_lote);
        }
    });
}
function validacionModal(){
    if($("#blanco1").val()!= $("#blanco2").val())
    {
        let atribute = document.getElementById("#blanco2");
        atribute.setAttribute("style", "border-color:#dc3545");
        console.log('no valido'); 
    }
    if($("#volMuestra1").val()!= $("#volMuestra2").val())
    {

    }
    if($("#abs11").val()!= $("#abs12").val())
    {
 
    }
    if($("#abs21").val()!= $("#abs22").val())
    {
        
    }
    if($("#abs31").val()!= $("#abs32").val())
    {

    }
    else
    {
        //colocar contorno en verde
    }
}
function getDetalleEspectro(idDetalle)
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getDetalleEspectro",
        data: {
            idDetalle: idDetalle,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) { 
            console.log(response);
            $("#blanco1").val(response.model.Blanco);
            $("#b1").val(response.curva.B);
            $("#m1").val(response.curva.M);
            $("#r1").val(response.curva.R);
            $("#b2").val(response.curva.B);
            $("#m2").val(response.curva.M);
            $("#r2").val(response.curva.R);
            $("#fDilucion1").val(response.model.Vol_muestra);
            // $("#volMuestra1").val(response.model);
            $("#abs11").val(response.model.Abs1);
            $("#abs21").val(response.model.Abs2);
            $("#abs31").val(response.model.Abs3);
        }
    });
}

//Función imprimir PDF
function imprimir() {
    /* console.log("Dentro de evento btnBuscar");
    $('#btnImprimir').click(function () { */
        //window.location = base_url + "/admin/laboratorio/"+area+"/captura/exportPdfCapturaGA/"+idLote;
        window.location = base_url + "/admin/laboratorio/"+area+"/captura/exportPdfCapturaEspectro/" + idLote;
    //});
}
function guardar() {
    
}

function operacion() {

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/operacionEspectro",
        data: {
            parametro: $('#formulaTipo').val(),
            ABS:$('#abs1').val(),
            CA:$('#blanco1').val(),
            CB:$('#b1').val(),
            CM:$('#m1').val(),
            CR:$('#r1').val(),
            D:$('#fDilucion1').val(),
            E:$('#e1').val(),
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
          let resultado = response.resultado.toFixed(3);
          $("#resultadoF").val(resultado); 
        }
    });
}

function liberarMuestraMetal() {
    let tabla = document.getElementById('divLote');
    let tab = '';

    let tabla2 = document.getElementById('divTablaControles');
    let tab2 = '';
    let cont = 1;

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/liberarMuestraMetal",
        data: {
            idDetalle: $("#idDetalle" + idMuestra).val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            tab += '<table id="tablaLote" class="table table-sm">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>Tipo formula</th>';
            tab += '          <th>Fecha lote</th> ';
            tab += '          <th>Total asignado</th> ';
            tab += '          <th>Total liberados</th> ';
            tab += '          <th>Opc</th> ';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            tab += '<tr>';
            tab += '<td>' + response.lote.Tipo_formula + '</td>';
            tab += '<td>' + response.lote.Fecha + '</td>';
            tab += '<td>' + response.lote.Asignado + '</td>';
            tab += '<td>' + response.lote.Liberado + '</td>';
            tab += '<td><button class="btn btn-success" id="btnImprimir"><i class="fas fa-file-download"></i></button></td>';
            tab += '</tr>';
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;


            tab2 += '<table id="tablaControles" class="table table-sm">';
            tab2 += '    <thead>';
            tab2 += '        <tr>';
            tab2 += '          <th>#</th>';
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
            tab2 += '          <th>Vol. disolución digerida v</th>';
            tab2 += '        </tr>';
            tab2 += '    </thead>';
            tab2 += '    <tbody>';
            $.each(response.detalleModel, function (key, item) {
                tab2 += '<tr>';
                tab2 += '<input style="width: 80px" hidden id="idDetalle' + cont + '" value="' + item.Id_detalle + '">';
                tab2 += '<td>' + cont + '</td>';
                tab2 += '<td>' + item.Folio_servicio + '</td>';
                if (item.Descripcion != 'Resultado') {
                    tab2 += '<td>' + item.Empresa + ' <br> <small class="text-danger">' + item.Descripcion + '</small></td>';
                } else {
                    tab2 += '<td>' + item.Empresa + ' <br> <small class="text-info">' + item.Descripcion + '</small></td>';
                }
                if (item.Liberado != 0) {
                    tab2 += '<td><input disabled style="width: 80px" id="volMuestra' + cont + '" value="50"></td>';
                    tab2 += '<td><input disabled style="width: 80px" id="abs1' + cont + '" value="' + item.Abs1 + '"></td>';
                    tab2 += '<td><input disabled style="width: 80px" id="abs2' + cont + '" value="' + item.Abs2 + '"></td>';
                    tab2 += '<td><input disabled style="width: 80px" id="abs3' + cont + '" value="' + item.Abs3 + '"></td>';
                    tab2 += '<td><input disabled style="width: 80px" id="absPromedio' + cont + '" value="' + item.Abs_promedio + '"></td>';
                    tab2 += '<td><input disabled style="width: 80px" id="factorDilucion' + cont + '" value="' + item.Factor_dilucion + '"></td>';
                    tab2 += '<td><input disabled style="width: 80px" id="factorConversion' + cont + '" value="' + item.Factor_conversion + '"></td>';
                    tab2 += '<td><input disabled style="width: 80px" id="VolDisolucion' + cont + '" value="' + item.Vol_disolucion + '"></td>';
                } else {
                    tab2 += '<td><input style="width: 80px" id="volMuestra' + cont + '" value="50"></td>';
                    tab2 += '<td><input style="width: 80px" id="abs1' + cont + '" value="' + item.Abs1 + '"></td>';
                    tab2 += '<td><input style="width: 80px" id="abs2' + cont + '" value="' + item.Abs2 + '"></td>';
                    tab2 += '<td><input style="width: 80px" id="abs3' + cont + '" value="' + item.Abs3 + '"></td>';
                    tab2 += '<td><input style="width: 80px" id="absPromedio' + cont + '" value="' + item.Abs_promedio + '"></td>';
                    tab2 += '<td><input style="width: 80px" id="factorDilucion' + cont + '" value="' + item.Factor_dilucion + '"></td>';
                    tab2 += '<td><input style="width: 80px" id="factorConversion' + cont + '" value="' + item.Factor_conversion + '"></td>';
                    tab2 += '<td><input style="width: 80px" id="VolDisolucion' + cont + '" value="' + item.Vol_disolucion + '"></td>';
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


            $('#tablaControles tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                }
                else {
                    table.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            });
            $('#tablaControles tr').on('click', function () {
                let dato = $(this).find('td:first').html();
                idMuestra = dato;
            });
        }
    });
}

function generarControles() {
    var ranCon = new Array();

    ranCon.push(random(0, numMuestras.length - 1));
    ranCon.push(random(0, numMuestras.length - 1));
    ranCon.push(random(0, numMuestras.length - 1));
    ranCon.push(random(0, numMuestras.length - 1));
    ranCon.push(random(0, numMuestras.length - 1));

    let tabla2 = document.getElementById('divTablaControles');
    let tab2 = '';

    let cont = 1;

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getDataCapturaEspectro",
        data: {
            ranCon: ranCon,
            numMuestra: numMuestras,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);

            tab2 += '<table id="tablaControles" class="table table-sm">';
            tab2 += '    <thead class="">';
            tab2 += '        <tr>';
            tab2 += '          <th>#</th>';
            tab2 += '          <th>Abs1</th>';
            tab2 += '          <th>Abs2</th>';
            tab2 += '          <th>Abs3</th>';
            tab2 += '          <th>Mililitros D Color</th>';
            tab2 += '          <th>Nitratos</th>';
            tab2 += '          <th>Nitritos</th>';
            tab2 += '          <th>Sulfuros</th>';
            tab2 += '          <th>Blanco A.</th>';
            tab2 += '          <th>Vol. Aforo</th>';
            tab2 += '          <th>Vol. Aforo Des</th>';
            tab2 += '          <th>Vol. Muestra</th>';
            tab2 += '        </tr>';
            tab2 += '    </thead>';
            tab2 += '    <tbody>';
            $.each(response.detalle, function (key, item) {
                tab2 += '<tr>';
                tab2 += '<input style="width: 80px" hidden id="idDetalle' + cont + '" value="' + item.Id_detalle + '">';
                tab2 += '<td>' + item.Folio_servicio + '</td>';
                if (item.Descripcion != 'Resultado') {
                    tab2 += '<td>' + item.Empresa + ' <br> <small class="text-danger">' + item.Descripcion + '</small></td>';
                } else {
                    tab2 += '<td>' + item.Empresa + ' <br> <small class="text-info">' + item.Descripcion + '</small></td>';
                }
                if (item.Liberado != 0) {
                    tab2 += '<td><input disabled style="width: 80px" id="volMuestra' + cont + '" value="50"></td>';
                    tab2 += '<td><input disabled style="width: 80px" id="abs1' + cont + '" value="' + item.Abs1 + '"></td>';
                    tab2 += '<td><input disabled style="width: 80px" id="abs2' + cont + '" value="' + item.Abs2 + '"></td>';
                    tab2 += '<td><input disabled style="width: 80px" id="abs3' + cont + '" value="' + item.Abs3 + '"></td>';
                    tab2 += '<td><input disabled style="width: 80px" id="absPromedio' + cont + '" value="' + item.Abs_promedio + '"></td>';
                    tab2 += '<td><input disabled style="width: 80px" id="factorDilucion' + cont + '" value="' + item.Factor_dilucion + '"></td>';
                    tab2 += '<td><input disabled style="width: 80px" id="factorConversion' + cont + '" value="' + item.Factor_conversion + '"></td>';
                    tab2 += '<td><input disabled style="width: 80px" id="VolDisolucion' + cont + '" value="' + item.Vol_disolucion + '"></td>';
                } else {
                    tab2 += '<td><input style="width: 80px" id="volMuestra' + cont + '" value="50"></td>';
                    tab2 += '<td><input style="width: 80px" id="abs1' + cont + '" value="' + item.Abs1 + '"></td>';
                    tab2 += '<td><input style="width: 80px" id="abs2' + cont + '" value="' + item.Abs2 + '"></td>';
                    tab2 += '<td><input style="width: 80px" id="abs3' + cont + '" value="' + item.Abs3 + '"></td>';
                    tab2 += '<td><input style="width: 80px" id="absPromedio' + cont + '" value="' + item.Abs_promedio + '"></td>';
                    tab2 += '<td><input style="width: 80px" id="factorDilucion' + cont + '" value="' + item.Factor_dilucion + '"></td>';
                    tab2 += '<td><input style="width: 80px" id="factorConversion' + cont + '" value="' + item.Factor_conversion + '"></td>';
                    tab2 += '<td><input style="width: 80px" id="VolDisolucion' + cont + '" value="' + item.Vol_disolucion + '"></td>';
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


            $('#tablaControles tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                }
                else {
                    table.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            });
            $('#tablaControles tr').on('click', function () {
                let dato = $(this).find('td:first').html();
                idMuestra = dato;
            });
        }
    });
}
function random(min, max) {
    return Math.floor(Math.random() * (max - min + 1) + min);
}