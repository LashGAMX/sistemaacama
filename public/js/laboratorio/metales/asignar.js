
//Múltiple selección
$(document).ready(function() {
    getMuestras(1)
    $('#btnBuscar').click(function () {
        getMuestras(2)
    });
    $('#btnSeleccionar').click(function () {
        allSelectCheck("std");
    });
    $('#btnSeleccionar').click(function () {
        
    });
});
document.addEventListener("keydown", function(event) {
    if (event.altKey && event.code === "KeyS")
    {
        allSelectCheck("std");
    }
    if (event.altKey && event.code === "KeyB"){
        getMuestras(2)
    }
    if (event.altKey && event.code === "KeyC"){
        // createLote()
    }
});
function getMuestras(sw)
{ 
    console.log("getMuestra")
    let tabla = document.getElementById('divMuestra');
    let tab = '';
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/metales/getMuestras",
        data: {
            tecnica: $("#tecnica").val(),
            tipo: $("#tipo").val(),
            fechaRecepcion: $("#fechaRecepcion").val(),
            fechaLote: $("#fechaLote").val(),
            sw:sw,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            model = response.model
            tab += '<table id="tablaLote" class="table table-sm">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>Seleccionar</th>';
            tab += '          <th>Id</th>';
            tab += '          <th>Num Muestra</th>';
            tab += '          <th>Cliente</th>';
            tab += '          <th>Punto Muestreo</th>';
            tab += '          <th>Norma</th> ';
            tab += '          <th>Formula</th> ';
            tab += '          <th>Lote</th> ';
            tab += '          <th>Fecha Lote</th> ';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            for (let i = 0; i < model.length; i++) {
                tab += '<tr>'; 
                if (model[i][6] == "") {
                    tab += '    <td><input type="checkbox" name="std"  value="'+model[i][0]+'"></td>';
                } else {
                    tab += '    <td><input type="checkbox" name="std" checked value="'+model[i][0]+'"></td>';   
                }
                tab += '    <td>'+model[i][0]+'</td>'
                tab += '    <td>'+model[i][1]+'</td>'
                tab += '    <td>'+model[i][2]+'</td>'
                tab += '    <td>'+model[i][3]+'</td>'
                tab += '    <td>'+model[i][4]+'</td>'
                tab += '    <td>'+model[i][5]+'</td>'
                if (model[i][7] == "") {
                    tab += '    <td>N/A</td>'
                    tab += '    <td>'+$("#fechaLote").val()+'</td>'   
                } else {
                    tab += '    <td>'+model[i][6]+'</td>'
                    tab += '    <td class="text-warning">'+$("#fechaLote").val()+'</td>'
                }
                tab += '</tr>';
            }
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;
        }
    });
}