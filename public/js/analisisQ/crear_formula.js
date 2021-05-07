var base_url = "https://dev.sistemaacama.com.mx";


$(document).ready(function() {
    $("#divProbar").css("display", "none");
    $("#btnAsignar").click(function()
    {
        $("#divProbar").css("display", "");
    });
});

function tablaVariables()
{
    let table = document.getElementById('tablaVariables');
    let i = 0;
    let tab = '';
    $.ajax({
        url: base_url + '/admin/analisisQ/formulas/getVariables', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          formula:$("#formula").val(),
          formulaSis:$("#formulaSis").val(),
          _token: $('input[name="_token"]').val(),
        },
        dataType: 'json', 
        async: false, 
        success: function (response) {
          console.log(response);
          tab += '<table id="tablaVaribales" class="table table-striped table-bordered">';
          tab += '    <thead class="thead-dark">';
          tab += '        <tr>';
          tab += '            <th style="width: 5%;">Formula</th>';
          tab += '            <th style="width: 30%;">Tipo</th>';
          tab += '            <th>Valor</th>';
          tab += '            <th>Decimal</th>';
          tab += '        </tr>';
          tab += '    </thead>';
          tab += '    <tbody>';
          $.each(response.variables, function (key, item) {
            tab += '<tr>';
            tab += '<td>'+item+'</td>';
            tab += '<td>'+response.variableSis[i]+'</td>';
            tab += '<td><input id="'+item+'Valor" placeholder="Valor"></td>';
            tab += '<td><input id="'+item+'Decimal" placeholder="Decimales"></td>';
            tab += '</tr>';
            i++;
        });
        tab += '    </tbody>';
        tab += '</table>';
        table.innerHTML = tab;
        }
    });
}