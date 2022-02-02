
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


$('#guardar').click(function () {
    if($("#resultado").val() != ''){
        console.log("Metodo corto");
        operacionSimple();
    }else{
        console.log("Metodo largo");
        operacionLarga();
    }
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
        url: base_url + "/admin/laboratorio/" + area + "/getDataCapturaGA",
        data: {
            formulaTipo: $("#formulaTipo").val(),
            fechaAnalisis: $("#fechaAnalisis").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);

            $("#idLote").val(response.lote.Id_lote);

            tab4 += '<p>Formula: (mf-mi/volumen)1000000- blanco </p>';
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
            tab2 += '          <th>Folio</th>';
            tab2 += '          <th># toma</th>';
            tab2 += '          <th>Norma</th>';
            tab2 += '          <th>Resultado</th>';
            tab2 += '          <th>Tipo Análisis</th>';
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
                tab2 += '<td><input hidden id="idMuestra'+item.Id_detalle+'" value="'+item.Id_detalle+'"><button type="button" class="btn btn-success" onclick="getDetalleGA('+item.Id_detalle+');" data-toggle="modal" data-target="#modalCaptura">Capturar</button>';
                if (item.Id_control != 1) 
                {
                    tab2 += '<br> <small class="text-danger">'+item.Control+'</small></td>';
                }else{
                    tab2 += '<br> <small class="text-info">'+item.Control+'</small></td>';
                }
                tab2 += '<td><input disabled style="width: 80px" value="'+item.Folio_servicio+'"></td>';
                tab2 += '<td><input disabled style="width: 80px" value="-"></td>';
                tab2 += '<td><input disabled style="width: 80px" value="'+item.Clave_norma+'"></td>';
                tab2 += '<td><input disabled style="width: 80px" value="-"></td>';
                tab2 += '<td><input disabled style="width: 80px" value="-"></td>';
                tab2 += '<td>'+item.Observacion+'</td>';
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
                let dato = $(this).find('td:first');
                idMuestra = dato[0].firstElementChild.value;
                // console.log(idMuestra);
            });

            idLote = response.lote.Id_lote;

            /* console.log("Valor de idLote: " + response.lote.Id_lote);
            imprimir(response.lote.Id_lote); */
        }
    });
}

//Función imprimir PDF
function imprimir() {    
    window.open(base_url + "/admin/laboratorio/"+area+"/captura/exportPdfCapturaGA/"+idLote);
    //window.location = base_url + "/admin/laboratorio/"+area+"/captura/exportPdfCapturaGA/"+idLote;
}

function operacionSimple() {

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/operacionGASimple", 
        data: {
            P:$("#p").val(),
            R:$("#resultado").val(),
            H:$("#h1").val(),
            J:$("#j1").val(),
            K:$("#k1").val(),
            C:$("#c1").val(),
            L:$("#l1").val(),
            I:$("#i1").val(),
            G:$("#g1").val(),
            E:$("#e1").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            let fixh1 = response.mf.toFixed(4);
            let fixj1 = response.m1.toFixed(4);
            let fixk1 = response.m2.toFixed(4);
            let fixc1 = response.m3.toFixed(4); 
        
            $("#h1").val(fixh1); 
            $("#j1").val(fixj1);
            $("#k1").val(fixk1);
            $("#c1").val(fixc1);
            $('#p').val(response.serie);
         
        }
    });
}
function operacionLarga() {

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/operacionGALarga", 
        data: {
            idMuestra:idMuestra,
            P:$("#p").val(),
            //R:$("#resultado").val(),
            H:$("#h1").val(),
            J:$("#j1").val(),
            K:$("#k1").val(),
            C:$("#c1").val(),
            L:$("#l1").val(),
            I:$("#i1").val(),
            G:$("#g1").val(),
            E:$("#e1").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            $('#resultado').val(response.res.toFixed(4));
         
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
            getDataCaptura();
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
function getDetalleGA(idDetalle)
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getDetalleGA",
        data: {
            idDetalle: idDetalle,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            $("#h1").val(response.model.M_final);
            $("#j1").val(response.model.M_inicial1);
            $("#k1").val(response.model.M_inicial2);
            $("#c1").val(response.model.M_inicial3);
            $("#l1").val(response.model.Ph);
            $("#i1").val(response.model.Vol_muestra);
            $("#g1").val(response.model.Blanco);
            $("#e1").val(response.model.F_conversion);
            $("#observacion").val(response.model.Observacion);
        }
    });
}
function random(min, max) {
    return Math.floor(Math.random() * (max - min + 1) + min);
}
function updateObsMuestraGA()
{
    
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/updateObsMuestraGA",
        data: {
            idMuestra: idMuestra,
            observacion: $("#observacion").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
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
            getDataCaptura();
        }
    });
}


function validacionModal(){
    let sw = true;
    
 if(validarCampos("campos")){
    if($("#blanco1").val()!= $("#blanco2").val())
    {
        //alert('Los valores de BLANCO no son iguales')
        // $("#blanco2").attr("class", "bg-danger");
        // $("#blanco2").attr("class", "bg-danger");
        inputFocus("blanco2")
        inputFocus("blanco1")
        sw = false;
        
        console.log('no valido'); 
    }
    if($("#volMuestra1").val()!= $("#volMuestra2").val())
    {
        //alert('Los valores de VOLUMEN MUESTRA no son iguales')
        inputFocus("volMuestra1")
        inputFocus("volMuestra2")
        sw = false;
    }
    if($("#abs11").val()!= $("#abs12").val())
    {
        //alert('Los valores de ABS no son iguales')
        inputFocus("abs11")
        inputFocus("abs12")
        sw = false;
    }
    if($("#abs21").val()!= $("#abs22").val())
    {
        //alert('Los valores de ABS no son iguales')
        inputFocus("abs11")
        inputFocus("abs22")
        sw = false;
    }
    if($("#abs31").val()!= $("#abs32").val())
    {
        //alert('Los valores de ABS no son iguales')
        inputFocus("abs31")
        inputFocus("abs32")
        sw = false;
    }
    
    if(sw == true)
    {
        operacion();
        inputDesFocus();
    }
 }
 
}