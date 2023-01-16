
//Múltiple selección
$(document).ready(function() {
    $('#btnBuscar').click(function () {
        // createLote()
    });
});
document.addEventListener("keydown", function(event) {
    if (event.altKey && event.code === "KeyA")
    {
        
    }
    if (event.altKey && event.code === "KeyB"){
        // getLote()
    }
    if (event.altKey && event.code === "KeyC"){
        // createLote()
    }
});
function getMuestras()
{
    let tabla = document.getElementById('divMuestra');
    let tab = '';
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/metales/getLote",
        data: {
            tecnica: $("#fecha").val(),
            tipo: $("#tipo").val(),
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
                tab += '    <td></td>';
                tab += '</tr>';
            }
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;
        }
    });
}