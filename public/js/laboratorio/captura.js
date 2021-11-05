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
    let parametros = $('select[name="parametros"] option:selected').text();
    let numMuestra = $('#numeroMuestra').val();
    let fechaAnalisis = $('#fechaAnalisis').val();        

    //console.log("Valor de la variable fechaAnalisis: " + fechaAnalisis);

    //Función Ajax; Realiza la búsqueda en la BD usando los valores de las variables
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/lote/procedimiento/busquedaFiltros",
        data: {
            formulaTipo: formulaTipo,
            parametros: parametros,
            numMuestra: numMuestra,
            fechaAnalisis: fechaAnalisis,
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

                window.location = base_url + "/admin/laboratorio/captura/exportPdfCaptura/"+response.tipoFormula+'/'+response.loteDetail.Folio_servicio+'/'+response.loteDetail.Parametro+'/'+response.loteDetail.Id_lote;

                /* window.location = base_url + "/admin/laboratorio/captura/exportPdfCaptura/"+response.tipoFormula+'/'+response.numeroMuestra+'/'+response.parameters; */
            });
        }
    });
});