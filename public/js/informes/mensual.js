var idSol = 0;
$(document).ready(function () {

    let table = $('#tableServicios').DataTable({        
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        }
    });    

    $('#tableServicios tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            getPreReporteMensual();
        }
    } );
    $('#tableServicios tr').on('click', function(){
        let dato = $(this).find('td:first').html();
        idSol = dato;
      });

    $('#btnImprimir').on('click', function(){

        console.log("Valor de tipoReporte: " + $("#tipoReporte").val());

        if($("#tipoReporte").val() == 1){
            window.open(base_url+"/admin/informes/exportPdfConComparacion/"+idSol);
        }else if($("#tipoReporte").val() == 2){
            window.open(base_url+"/admin/informes/exportPdfSinComparacion/"+idSol);
        }        
    });

    $("#btnSC").click(function()
    {
        window.open(base_url+"/admin/informes/informeMensualSinComparacion/"+idSol);
    });
    $("#btnCc").click(function()
    {
        window.open(base_url+"/admin/informes/informeMensualConComparacion/"+idSol);
    });
}); 
var model = new Array();
function getPreReporteMensual()
{
    let tabla = document.getElementById('divReporte');
    let tab = '';

    $.ajax({
        url: base_url + '/admin/informes/getPreReporteMensual',
        type: 'POST', //método de envio
        data: {
            idSol: idSol,
            _token: $('input[name="_token"]').val(),
          },
        dataType: 'json', 
        async: false, 
        success: function (response) {
          console.log(response);
          model = response;
          let cont = 0;
          
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
                tab += '<td>'+item.Parametro+'</td>';
                tab += '<td>'+item.Unidad+'</td>';
                tab += '<td>'+item.Clave_metodo+'</td>';
                tab += '<td>'+item.Resultado+'</td>';
                tab += '<td>'+response.model2[cont].Resultado+'</td>';
                tab += '<td>'+((response.model2[cont].Resultado + item.Resultado) /2)+'</td>';
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