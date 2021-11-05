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
    //Obtiene los valores introducidos en Fórmula tipo, Parámetro, Núm. muestra, Fecha análisis
    let formulaTipo = $('select[name="formulaTipo"] option:selected').text();
    //let parametros = $('select[name="parametros"] option:selected').text();
    let numMuestra = $('#numeroMuestra').val();
    let fechaAnalisis = $('#fechaAnalisis').val();        

    //console.log("Valor de la variable fechaAnalisis: " + fechaAnalisis);

    //Función Ajax; Realiza la búsqueda en la BD usando los valores de las variables
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/lote/procedimiento/busquedaFiltros",
        data: {
            formulaTipo: formulaTipo,
            //parametros: parametros,
            numMuestra: numMuestra,
            //fechaAnalisis: fechaAnalisis,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {            
            console.log(response.loteDetail);
            /* console.log(response.tipoFormula);            
            console.log(response.parameters);            
            console.log(response.numeroMuestra); */

            $('#btnImprimir').click(function() {
                console.log("dentro de jquery function");                                                                                

                //window.location = base_url + "/admin/laboratorio/captura/exportPdfCaptura/"+response.loteDetail.Id_lote+'/'+response.tipoFormula+'/'+response.loteDetail.Parametro+'/'+response.loteDetail.Folio_servicio;

                //window.location = base_url + "/admin/laboratorio/captura/exportPdfCaptura/"+response.tipoFormula+'/'+response.loteDetail.Folio_servicio+'/'+response.loteDetail.Parametro+'/'+response.loteDetail.Id_lote;

                window.location = base_url + "/admin/laboratorio/captura/exportPdfCaptura/"+response.tipoFormula+'/'+response.loteDetail.Folio_servicio;

                //window.location = base_url + "/admin/laboratorio/captura/exportPdfCaptura/"+response.tipoFormula+'/'+response.numeroMuestra;
            });
        }
    });
});

function getDataCaptura()
{
    let tabla = document.getElementById('divLote');
    let tab = '';

    let tabla2 = document.getElementById('tableDatos2');
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
                tab2 += '    <thead class="thead-dark">';
                tab2 += '        <tr>';
                tab2 += '          <th>NumMuestra</th>';
                tab2 += '          <th>NomCliente</th>';
                tab2 += '          <th>PuntoMuestreo</th>';
                tab2 += '          <th>Vol. Muestra E</th>';
                tab2 += '          <th>x</th>';
                tab2 += '          <th>y</th>';
                tab2 += '          <th>z</th>';
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
                    tab2 += '<td>Laboratorio ACAMA</td>';
                    tab2 += '<td>Descarga Final</td>';
                    tab2 += '<td><input id="volMuestra"></td>';
                    tab2 += '<td><input id="x"></td>';
                    tab2 += '<td><input id="y"></td>';
                    tab2 += '<td><input id="z"></td>';
                    tab2 += '<td><input id="absPromedio"></td>';
                    tab2 += '<td><input id="factorDilucion"></td>';
                    tab2 += '<td><input id="factorConversion" value="1"></td>';
                    tab2 += '<td><input id="VolDisolucion"></td>';
                    tab2 += '</tr>';
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
