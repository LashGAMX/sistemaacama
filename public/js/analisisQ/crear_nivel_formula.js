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
            $("#modalProbar").modal('hide');
             alert("FALTA DEFINIR LAS DECIMALES");
            
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
    $("#btnGuardar").click(function()
    {
        createNivel();
        alert("Registro guardado con exito");
        
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
          tab += '<table id="tablaVariables" class="table table-striped table-bordered">';
          tab += '    <thead class="thead-dark">';
          tab += '        <tr>';
          tab += '            <th style="width: 5%;">Formula</th>';
          tab += '            <th style="width: 30%;">Tipo</th>';
          tab += '            <th>Valor</th>';
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
                        tab += '<td> <select class="form-control">';
                          $.each(response.nivel1, function (key, nivel) {
                            tab +='<option value="'+nivel.Resultado+'">'+nivel.Nombre+'</option>';
                        });
                            break;
                        case 4: // Formula Nivel 2
                        tab += '<td> <select class="form-control">';
                          $.each(response.nivel2, function (key, nivel) {
                            tab +='<option value="'+nivel.Resultado+'">'+nivel.Nombre+'</option>';
                        })
                            break;
                        case 5: // Formula Nivel 3
                        tab += '<td> <select class="form-control">';
                        $.each(response.nivel3, function (key, nivel) {
                          tab +='<option value="'+nivel.Resultado+'">'+nivel.Nombre+'</option>';
                        });
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


function getProbarFormula()
{
    let cont = 0;
    let inputVar = document.getElementById('inputVar');
    let tab = '';
    let t = document.getElementById("tablaVariables");
    let campo;
    let variable;
    var campos = new Array();

    for (let i = 0; i < datosFormula.variables.length; i++ )
     {
        variable = datosFormula.variableSis[i];

       if (variable == "n1") 
       {
         campo = t.rows[i+1].cells[2].children[0].value;
         campos.push(campo);
       } 
       else if (variable == "n2")
       {
        campo = t.rows[i+1].cells[2].children[0].value;
        campos.push(campo);
       }
       else if (variable == "n3")
       {
        campo = t.rows[i+1].cells[2].children[0].value;
        campos.push(campo);
       }
       else
       {
        valor = datosFormula.variables[i];
        campo  = $('#'+valor+'Valor').val();
        campos.push(campo);
       }
     }

    contVar = 0;
    $("#formulaGen").val($("#formula").val());
    tab += '<div class="row">';
    $.each(datosFormula.variables, function (key, item) {
        tab += '<div class="col-md-4">';
        tab += inputText(item+'?','dato'+cont,'',item,campos[cont],'disabled');
        tab += '</div>';    
        cont++;
        contVar++;
    });
    tab += '</div>';
    inputVar.innerHTML = tab;
    console.log(campos); 
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
function createNivel()
{
    

    $.ajax({
        url: base_url + '/admin/analisisQ/formulas/createNiveles', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            nombre:$("#nombre").val(),
            nivel:$("#nivel").val(),
            formula:$("#formula").val(), 
            formulaSis:$("#formulaSis").val(),
            resultado:$("#resultadoCal").val(),
            _token: $('input[name="_token"]').val(),
          },
        dataType: 'json', 
        async: false, 
        success: function (response) {
          console.log(response);
        }
    }); 

}