var idSol = 0;
$(document).ready(function () {

    let table = $('#tableServicios').DataTable({        
        "ordering": false,
        paging: false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",   
        },
    });    

    $('#tableServicios tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
        dato = table;
    } );
    
    $('#buscaPreInforme').on('click', function(){
        if(dato.rows('.selected').data().length > 2){
           alert("No puedes seleccionar mas de dos elementos")  
        }else{
            getPreReporteMensual();
        }
    });

    $("#btnImprimir").click(function() 
    { 
        console.log("Imprimir reporte")
        let id1 = dato.rows('.selected').data()[0][0]
        let id2 = dato.rows('.selected').data()[1][0]
        switch ($("#tipoReporte").val()) {
            case "1":
                window.open(base_url+"/admin/informes/exportPdfInformeMensual/"+id1+"/"+id2+"/1");       
                break;
            case "2":
                window.open(base_url+"/admin/informes/exportPdfInformeMensual/"+id1+"/"+id2+"/2");
                break;
            case "3":
                window.open(base_url+"/admin/informes/exportPdfInformeMensualCampo/"+id2+"/"+id1);
                break;
            default:
                break;
        }
    });
}); 
var model = new Array();
function getPreReporteMensual()
{
    let tabla = document.getElementById('divReporte');
    let tab = '';
    let id1 = dato.rows('.selected').data()[0][0]
    let id2 = dato.rows('.selected').data()[1][0]
    $.ajax({
        url: base_url + '/admin/informes/getPreReporteMensual',
        type: 'POST', //método de envio
        data: {
            id1: id1,
            id2: id2,
            _token: $('input[name="_token"]').val(),
          },
        dataType: 'json', 
        async: false, 
        success: function (response) {
          console.log(response);
          model = response;
          let cont = 0;
          
          if(response.sw == false){
            $("#btnImprimir").attr("disabled",true)
            alert("Estos informes no se pueden comparar, porque pertenecen a diferentes clientes y/o punto de muestreo")
          }else{
            $("#btnImprimir").attr("disabled",false)
          }

            tab += '<table id="tableReporte" class="table" style="width: 100%; font-size: 10px">';
            tab += '    <thead>';
            tab += '        <tr>';
            tab += '            <th style="width: 5%;">Id</th>';
            tab += '            <th>Fórmula</th>';
            tab += '            <th>Unidad</th>';
            tab += '            <th>Método P.</th>';
            tab += '            <th>Promedio D1</th>';
            tab += '            <th>Promedio D2</th>';
            tab += '            <th>Promedio M.</th>';
            tab += '            <th>Concentracion</th>';
            tab += '            <th>Diagnostico</th>';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            $.each(response.model, function (key, item) {
                tab += '<tr>';
                tab += '<td>'+item.Id_codigo+'</td>';
                tab += '<td>'+response.parametro[cont]+'</td>';
                tab += '<td>'+response.unidad[cont]+'</td>';
                tab += '<td>'+response.metodo[cont]+'</td>';
                tab += '<td>'+item.Resultado2+'</td>';
                tab += '<td>'+response.model2[cont].Resultado2+'</td>';
                let prom = (parseFloat(response.model2[cont].Resultado) + parseFloat(item.Resultado)) / 2
                tab += '<td>'+prom+'</td>';
                tab += '<td></td>';
                tab += '<td></td>';
               tab += '</tr>'; 
               cont++;
            });
            tab += '    </tbody>';
            tab += '</table>';
            tab += '<h6>No hay datos para comparar</h6>';
            tabla.innerHTML = tab;
        }
    });  
}