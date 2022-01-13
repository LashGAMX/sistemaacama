
var area = "fq";

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
    operacion();
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
                tab2 += '<td><button type="button" class="btn btn-success" onclick="getDetalleGA('+item.Id_detalle+');" data-toggle="modal" data-target="#modalCaptura">Capturar</button></td>';
                tab2 += '<td><input disabled style="width: 80px" value="'+item.Folio_servicio+'"></td>';
                tab2 += '<td><input disabled style="width: 80px" value="-"></td>';
                tab2 += '<td><input disabled style="width: 80px" value="'+item.Clave_norma+'"></td>';
                tab2 += '<td><input disabled style="width: 80px" value="-"></td>';
                tab2 += '<td><input disabled style="width: 80px" value="-"></td>';
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

            // imprimir(response.lote.Id_lote);
        }
    });
}

//Función imprimir PDF
function imprimir() {
    console.log("Dentro de evento btnBuscarprueb");
    /* $('#btnImprimir').click(function () {
        console.log("Clic en botón imprimir"); */
        window.location = base_url + "/admin/laboratorio/"+area+"/captura/exportPdfCapturaGA";
   /*  }); */
}

function operacion() {

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/operacionGA", 
        data: {
            P:$("#p").val(),
            R:$("#resultado").val(),
            h:$("#h1").val(),
            J:$("#j1").val(),
            K:$("#k1").val(),
            C:$("#c1").val(),
            l:$("#l1").val(),
            I:$("#i1").val(),
            G:$("#g1").val(),
            E:$("#e1").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            //let fix = response.resultado.toFixed(3);
            $("#h1").val(response.mf);
         
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
        }
    });
}
function random(min, max) {
    return Math.floor(Math.random() * (max - min + 1) + min);
}