var area = "fq";
var numMuestras = new Array();
var idMuestra = 0; 
var idLote = 0;
var tecnica = 0;

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
        url: base_url + "/admin/laboratorio/" + area + "/getDataCapturaVolumetria",
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
    window.open(base_url + "/admin/laboratorio/"+area+"/captura/exportPdfCapturaVolumetria/"+idLote);
    //window.location = base_url + "/admin/laboratorio/"+area+"/captura/exportPdfCapturaVolumetria/"+idLote;
}

function operacion() {

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/operacionVolumetria", 
        data: {
            idParametro:$("#formulaTipo").val(),
            B:$("#b1").val(),
            C:$("#c1").val(),
            CA:$("#ca1").val(),
            D:$("#d1").val(),
            E:$("#e1").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            $("#resultado").val(response.res.toFixed(2));
            
         
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

function getDetalleGA(idDetalle)
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getDetalleVolumetria",
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