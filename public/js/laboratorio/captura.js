var base_url = 'https://dev.sistemaacama.com.mx';

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

/* $('#btnImprimir').click(function() {
    console.log("dentro de jquery function");
    let formulaTipo = $('select[name="formulaTipo"] option:selected').text(); */

    //let nuMuestra = $('#numeroMuestra').val();

    /*$.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/lote/procedimiento/busquedaFiltros",
        data: {
            numMuestra: numMuestra,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            
        }
    });*/

/*     window.location = base_url + "/admin/laboratorio/captura/exportPdfCaptura/"+formulaTipo;
}); */

//Botón buscar; Realiza la búsqueda
$('#btnBuscar').click(function(){
    console.log("Dentro de evento btnBuscar");

    //Obtiene los valores introducidos en Fórmula tipo, Núm. muestra, Fecha análisis
    let formulaTipo = $('select[name="formulaTipo"] option:selected').text();    
    let numMuestra = $('#numeroMuestra').val();
    //let fechaAnalisis = $('#fechaAnalisis').val();

    console.log("Valor de la variable formulaTipo: " + formulaTipo);
    //console.log("Valor de la variable fechaAnalisis: " + fechaAnalisis);

    //Función Ajax; Realiza la búsqueda en la BD usando los valores de las variables
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/lote/procedimiento/busquedaFiltros",
        data: {
            formulaTipo: formulaTipo,            
            numMuestra: numMuestra,
            //fechaAnalisis: fechaAnalisis,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {            
            console.log(response.loteDetail);            

            $('#btnImprimir').click(function() {
                console.log("dentro de jquery function");                                                                                                

                window.location = base_url + "/admin/laboratorio/captura/exportPdfCaptura/"+response.loteDetail.Parametro+'/'
                +response.loteDetail.Folio_servicio+'/'+response.loteDetail.Id_lote;
            });
        }
    });
});

var numMuestras = new Array();
function getDataCaptura()
{
    numMuestras = new Array();
    let tabla = document.getElementById('divLote');
    let tab = '';

    let tabla2 = document.getElementById('divTablaControles');
    let tab2 = '';

        $.ajax({
            type: "POST",
            url: base_url + "/admin/laboratorio/getDataCaptura",
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
                tab += '          <th>Tipo formula</th>';
                tab += '          <th>Fecha lote</th> ';
                tab += '          <th>Total asignado</th> ';
                tab += '          <th>Total liberados</th> ';
                tab += '          <th>Opc</th> ';
                tab += '        </tr>';
                tab += '    </thead>';
                tab += '    <tbody>';
                tab += '<tr>';
                tab += '<td>'+response.lote.Tipo_formula+'</td>';
                tab += '<td>'+response.lote.Fecha+'</td>';
                tab += '<td>1</td>';
                tab += '<td>0</td>';
                tab += '<td><button class="btn btn-success" id="btnImprimir"><i class="fas fa-file-download"></i></button></td>';
              tab += '</tr>';
                tab += '    </tbody>';
                tab += '</table>';
                tabla.innerHTML = tab;


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
                tab2 += '          <th>Vol. disolución digerida v</th>';
                tab2 += '        </tr>';
                tab2 += '    </thead>';
                tab2 += '    <tbody>';
                $.each(response.detalle, function (key, item) {
                    tab2 += '<tr>';
                    tab2 += '<td>'+item.Folio_servicio+'</td>';
                    tab2 += '<td>'+item.Empresa+' <br> <small>'+item.Descripcion+'</small></td>';
                    tab2 += '<td><input id="volMuestra" value="50"></td>';
                    tab2 += '<td><input id="x" value="'+item.Abs1+'"></td>';
                    tab2 += '<td><input id="y" value="'+item.Abs2+'"></td>';
                    tab2 += '<td><input id="z" value="'+item.Abs3+'"></td>';
                    tab2 += '<td><input id="absPromedio" value="'+item.Abs_promedio+'"></td>';
                    tab2 += '<td><input id="factorDilucion" value="'+item.Factor_dilucion+'"></td>';
                    tab2 += '<td><input id="factorConversion" value="'+item.Factor_conversion+'"></td>';
                    tab2 += '<td><input id="VolDisolucion" value="'+item.Vol_disolucion+'"></td>';
                    tab2 += '</tr>';
                    numMuestras.push(item.Id_detalle);
                });
                tab2 += '    </tbody>';
                tab2 += '</table>'; 
                tabla2.innerHTML = tab2;

            }
        });
}
function operacion()
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/operacion",
        data: {
            idlote:1,
             x:$("#x").val(),
             y:$("#y").val(),
             z:$("#z").val(),
             FD:$("#factorDilucion").val(),
            _token: $('input[name="_token"]').val()
        }, 
        dataType: "json",
        success: function (response) {            
            console.log(response);
            let fix = response.resultado.toFixed(3); 
            $("#absPromedio").val(response.promedio);
            $("#VolDisolucion").val(fix);
        }
    });
}

function generarControles()
{
    var ranCon = new Array();

    ranCon.push(random(1,numMuestras.length));
    ranCon.push(random(1,numMuestras.length));
    ranCon.push(random(1,numMuestras.length));
    ranCon.push(random(1,numMuestras.length));
    ranCon.push(random(1,numMuestras.length));

    let tabla2 = document.getElementById('divTablaControles');
    let tab2 = '';

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/setControlCalidad",
        data: {
            ranCon:ranCon,
            numMuestra:numMuestras,
            idDetalle:1,
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
            tab2 += '          <th>Vol. disolución digerida v</th>';
            tab2 += '        </tr>';
            tab2 += '    </thead>';
            tab2 += '    <tbody>';
            $.each(response.detalle, function (key, item) {
                tab2 += '<tr>';
                tab2 += '<td>'+item.Folio_servicio+'</td>';
                tab2 += '<td>'+item.Empresa+' <br> '+item.Descripcion+'</td>';
                tab2 += '<td><input id="volMuestra" value="50"></td>';
                tab2 += '<td><input id="x" value="'+item.Abs1+'"></td>';
                tab2 += '<td><input id="y" value="'+item.Abs2+'"></td>';
                tab2 += '<td><input id="z" value="'+item.Abs3+'"></td>';
                tab2 += '<td><input id="absPromedio" value="'+item.Abs_promedio+'"></td>';
                tab2 += '<td><input id="factorDilucion" value="'+item.Factor_dilucion+'"></td>';
                tab2 += '<td><input id="factorConversion" value="'+item.Factor_conversion+'"></td>';
                tab2 += '<td><input id="VolDisolucion" value="'+item.Vol_disolucion+'"></td>';
                tab2 += '</tr>';
                numMuestras.push(item.Id_detalle);
            });
            tab2 += '    </tbody>';
            tab2 += '</table>'; 
            tabla2.innerHTML = tab2;
        }
    });
}
function random(min, max) {
  return Math.floor(Math.random() * (max - min + 1) + min);
}