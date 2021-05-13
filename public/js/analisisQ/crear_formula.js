var base_url = "https://dev.sistemaacama.com.mx";


$(document).ready(function() {
    $("#divProbar").css("display", "none");
    $("#divDecimales").css("display", "none");

    $("#btnAsignar").click(function()
    {
        $("#divProbar").css("display", "");
        $("#divDecimales").css("display", "");
    });
    $("#btnProbar").click(function()
    {
        if($("#decimales").val()!="")
        {
            getProbarFormula();
        }
        else{
             alert("FALTA DEFINIR LAS DECIMALES");
            // $("#modalProbar").modal('hide');
            // Swal.fire({
            //     icon: 'error',
            //     title: 'Oops...',
            //     text: 'Falta definir las decimales!',
            //   });
              
        }
    });
    $("#btnCalcular").click(function()
    {
        probarFormula();
    });
    $('#btnGuardar').click(function()
    {
        create();
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
        //   tab += '            <th>Decimal</th>';
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
                            tab += '<td><Select id="'+item+'Valor" placeholder="Valor"></select></td>';
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
            // tab += '<td><input id="'+item+'Decimal" placeholder="Decimales"></td>';
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
function getProbarFormula() //botón probar
{
    var campos = new Array();
    let valor;
    let campo;
    let cont = 0;
    let inputVar = document.getElementById('inputVar');
    let tab = '';

    for (let i = 0; i < datosFormula.variables.length; i++ )
    {
        valor = datosFormula.variables[i];
        campo  = $('#'+valor+'Valor').val();
        campos.push(campo);
    }
    contVar = 0;
    $("#formulaGen").val($("#formula").val());
    tab += '<div class="row">';
    $.each(datosFormula.variables, function (key, item) {
        tab += '<div class="col-md-4">';
        tab += inputText(item+'?','dato'+cont,'',item,campos[cont]);
        tab += '</div>';    
        cont++;
        contVar++;
        console.log(campos[cont]);
    });
    tab += '</div>';
    inputVar.innerHTML = tab;
}
function probarFormula() //operacion en modal
{  
        let valores = new Array();
        let fix; //resultado con las decimas solicitadas
        let decimales = $('#decimales').val();
    
        for (let index = 0; index < datosFormula.variables.length; index++) {
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
              fix = response.resultado.toFixed(decimales);
                $("#resultadoCal").val(fix);
            }
        });         
    
}
function create()
{
    
    // let limite =  datosFormula.variables.length;

    // for (let i = 0; i < limite; i++ )
    // {
    //     valor = datosFormula.variables[i];
    //     campo  = $('#'+valor+'Valor').val();
    //     campos.push(campo);
    // }
    


    $.ajax({
        url: base_url + '/admin/analisisQ/formulas/create', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            area:$("#area").val(),
            parametro:$("#parametro").val(),
            tecnica:$("#tecnica").val(), 
            formula:$("#formula").val(), 
            formulaSis:$("#formulaSis").val(),
            campos:campos,
            _token: $('input[name="_token"]').val(),
          },
        dataType: 'json', 
        async: false, 
        success: function (response) {
          console.log(response);
        }
    }); 

}

