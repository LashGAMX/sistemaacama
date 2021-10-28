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

$('#btnImprimir').click(function() {
    console.log("dentro de jquery function");
    let formulaTipo = $('select[name="formulaTipo"] option:selected').text();

    window.location = base_url + "/admin/laboratorio/captura/exportPdfCaptura/"+formulaTipo;
});

//Botón buscar; Realiza la búsqueda
$('#btnBuscar').click(function(){
    
    //Obtiene los valores introducidos en Fórmula tipo, Parámetro, Núm. muestra, Fecha análisis
    let formulaTipo = $('select[name="formulaTipo"] option:selected').text();
    let parametros = $('select[name="parametros"] option:selected').text();
    let numMuestra = $('#numeroMuestra').val();
    let fechaAnalisis = $('#fechaAnalisis').val();

    //console.log("Valor de la variable fechaAnalisis: " + fechaAnalisis);

    //Función Ajax; Realiza la búsqueda en la BD usando los valores de las variables
    $.ajax({
        type: "GET",
        url: "url",
        data: {
            formulaTipo: formulaTipo,
            parametros: parametros,
            numMuestra: numMuestra,
            fechaAnalisis: fechaAnalisis
        },
        dataType: "json",
        success: function (response) {
            
        }
    });
});