
//Múltiple selección
$(document).ready(function() {
   // getMuestras(1)
    $('#btnBuscar').click(function () {
        getMuestras(2)
    });
    $('#btnSeleccionar').click(function () {
        allSelectCheck("std");
    });
    $('#btnAgregar').click(function () {
        sendMuestrasLote()
    });
    $('#btnPendiente').click(function () {
        getPendientes()
    });
    $('.select2').select2();
});
document.addEventListener("keydown", function(event) {
    if (event.altKey && event.code === "KeyS")
    {
        allSelectCheck("std");
    }
    if (event.altKey && event.code === "KeyB"){
        getMuestras(2)
    }
    if (event.altKey && event.code === "KeyA"){
        sendMuestrasLote()
    }
});
function getPendientes()
{ 
    let tabla = document.getElementById('divPendientes');
    let tab = '';
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/metales/getPendientes",
        data: {
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            model = response.model
            tab += '<table id="tablePendientes" class="table table-sm" style="font-size:10px">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>Folio</th>';
            tab += '          <th>Parametro</th>';
            tab += '          <th>Fecha recepción</th>';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            for (let i = 0; i < model.length; i++) {
                tab += '<tr>';
                tab += '<td>'+model[i][0]+'</td>';
                tab += '<td>'+model[i][1]+'</td>';
                tab += '<td>'+model[i][2]+'</td>';
                tab += '</tr>';   
            }
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;

            table = $('#tablePendientes').DataTable({
                "ordering": false,
                "language": {
                    "lengthMenu": "# _MENU_ por pagina",
                    "zeroRecords": "No hay datos encontrados",
                    "info": "Pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay datos encontrados",
                }
            });
        }
    });
}
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
            norma: $("#norma").val(),
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
                    // tab += '    <td><input type="time" value="'+model[i][6]+'"></td>'  
                } else {
                    tab += '    <td>'+model[i][6]+'</td>'
                    tab += '    <td class="text-warning">'+$("#fechaLote").val()+'</td>'
                    // tab += '    <td><input type="time" value="'+model[i][6]+'"></td>'  
                }
                tab += '</tr>';
            }
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;
        }
    });
}
function sendMuestrasLote()
{ 

    let tab = document.getElementById("tablaLote")
    let ids = new Array()
    for (let i = 1; i < tab.rows.length; i++) {
        if(tab.rows[i].children[0].children[0].checked){
            ids.push(tab.rows[i].children[0].children[0].value)
        }
    }
    console.log(ids)
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/metales/sendMuestrasLote",
        data: {
            ids:ids,
            fechaLote: $("#fechaLote").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            getMuestras(2)
            if (response.sw == false) {
                alert("Hay muestras que no coinciden con la fecha de recepcion")
            } else {
                alert("Muestras Asignadas correctamente")
            }
        }
    });
}