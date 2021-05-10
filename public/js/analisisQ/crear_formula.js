var base_url = "https://dev.sistemaacama.com.mx";


$(document).ready(function() {
    $("#divProbar").css("display", "none");
    $("#btnAsignar").click(function()
    {
        $("#divProbar").css("display", "");
    });
    $("#btnProbar").click(function()
    {
        getProbarFormula();
    });
    $("#btnCalcular").click(function()
    {
        probarFormula();
    });
});

var datosFormula = new Array();
function tablaVariables()
{
    datosFormula = new Array();
    let table = document.getElementById('tablaVariables');
    let i = 0;
    let tab = '';
    let op1 = false;
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
          datosFormula = response;
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
            // tab += '<td><input id="'+item+'Valor" placeholder="Valor"></td>';
            $.each(response.reglas, function (key, reg) {
                if(response.variableSis[i] == reg.Regla)
                {
                    switch(reg.Id_regla) {
                        case 1: // Cosntante
                          // code block
                          tab += '<td> <select class="form-control">';
                          $.each(response.constantes, function (key, con) {
                            tab +='<option value="'+con.Id_constante+'">'+con.Constante+'</option>';
                        });
                        tab +='</select></td>';
                          break;
                        case 2: // Variable
                          // code block
                          tab += '<td><input id="'+item+'Valor" placeholder="Valor"></td>';
                          break;
                        case 3: // Formula Nivel1
                            break;
                        case 4: // Formula Nivel 2
                            break;
                        default:
                            tab += '<td><input id="'+item+'Valor" placeholder="Valor"></td>';
                          // code block
                      }
                      op1 = true;
                }
            });
            if(op1 != true)
            {
                tab += '<td><input id="'+item+'Valor" placeholder="Valor"></td>';
            }
            tab += '<td><input id="'+item+'Decimal" placeholder="Decimales"></td>';
            tab += '</tr>';
            i++;
            op1 = false;
        });
        tab += '    </tbody>';
        tab += '</table>';
        table.innerHTML = tab;
        }
    }); 
}

var contVar = 0;
function getProbarFormula()
{
    contVar = 0;
    let inputVar = document.getElementById('inputVar');
    let tab = '';
    $("#formulaGen").val($("#formula").val());
    tab += '<div class="row">';
    $.each(datosFormula.variables, function (key, item) {
        tab += '<div class="col-md-4">';
        tab += inputText(item+'?','dato'+contVar,'',item,'');
        tab += '</div>';    
        contVar++;
    });
    tab += '</div>';
    inputVar.innerHTML = tab;
}
function probarFormula()
{
    let valores = new Array();

    for (let index = 0; index < contVar; index++) {
        valores.push($("#dato"+index).val());
    }
    console.log(valores);
    $.ajax({
        url: base_url + '/admin/analisisQ/formulas/probarFormula', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          formula:$("#formulaGen").val(),
          valores:valores,
          _token: $('input[name="_token"]').val(),
        },
        dataType: 'json', 
        async: false, 
        success: function (response) {
          console.log(response);
            $("#resultadoCal").val(response.resultado);
        }
    }); 
}

function createFormula()
{
    let formula = new Array;
    
    

}