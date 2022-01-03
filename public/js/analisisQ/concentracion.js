$(document).ready(function() {
    
});

function getParametroNorma(idNorma)
{
    let table = document.getElementById('tableParametros');
    let tab = '';
    $.ajax({
        url: base_url + '/admin/analisisQ/concentracion/getParametroNorma', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          idNorma:idNorma,
          _token: $('input[name="_token"]').val(),
        },
        dataType: 'json', 
        async: false, 
        success: function (response) {
          console.log(response.model)
          tab += '<table id="tableParametros" class="table table-sm  table-striped table-bordered">';
          tab += '    <thead class="thead-dark">';
          tab += '        <tr>';
          tab += '            <th style="width: 5%;">Id</th>';
          tab += '            <th style="width: 30%;">Parametro</th>';
          tab += '            <th>T. Formula</th>';
          tab += '            <th>Opc</th>';
          tab += '        </tr>';
          tab += '    </thead>';
          tab += '    <tbody>';
          $.each(response.model, function (key, item) {
              tab += '<tr>';
            tab += '<td>'+item.Id_parametro+'</td>';
            tab += '<td>'+item.Parametro+'</td>';
            tab += '<td>'+item.Tipo_formula+'</td>';
            tab += '<td><button class="btn btn-info fa fa-plus" onclick="getConcentracionParametro('+item.Id_parametro+',\''+item.Parametro+'\')"></button></td>';
            tab += '</tr>';
          });
          tab += '    </tbody>';
          tab += '</table>';
          table.innerHTML = tab;
        }
    });
}

var arrCon = new Array();
var numStd = 0;
var idParam = 0;
function getConcentracionParametro(idParametro,parametro)
{
    idParam = idParametro;
    let table = document.getElementById('tableStd');
    let tab = '';
    $.ajax({
        url: base_url + '/admin/analisisQ/concentracion/getConcentracionParametro', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          idParametro:idParametro,
          _token: $('input[name="_token"]').val(),
        },
        dataType: 'json', 
        async: false, 
        success: function (response) {
          console.log(response)
          let cont = 1;
          tab += '<table id="tableParametros" class="table table-sm  table-striped table-bordered">';
          tab += '    <h6>Parametro: <input type="text" value="'+parametro+'" disabled>&nbsp;<button class="btn btn-success" onclick="setConcentracionParametro()">Guardar</button></h6>';
          tab += '    <thead class="thead-dark">';
          tab += '        <tr>';
          tab += '            <th style="width: 5%;">#</th>';
          tab += '            <th style="width: 30%;">Concentracion</th>';
          tab += '        </tr>';
          tab += '    </thead>';
          tab += '    <tbody>';
          $.each(response.model, function (key, item) {
            tab += '<tr>';
            tab += '<td>'+cont+'</td>';
            tab += '<td><input id="con'+cont+'" type="text" value="'+item.Concentracion+'"></td>';
            tab += '</tr>';
            cont++;
            numStd = cont;
          });
          tab += '    </tbody>';
          tab += '</table>';
          table.innerHTML = tab;
        }
    });
}
function setConcentracionParametro()
{
    arrCon = new Array();
    for (let i = 0; i < (numStd -1); i++) {
        arrCon.push($("#con"+(i+1)+"").val());
    }
    $.ajax({
        url: base_url + '/admin/analisisQ/concentracion/setConcentracionParametro', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          idParametro:idParam,
          concentracion:arrCon,
          _token: $('input[name="_token"]').val(),
        },
        dataType: 'json', 
        async: false, 
        success: function (response) {
          console.log(response)
          swal("Registro!", "Registro guardado correctamente!", "success");
        }
    });   
}
