$(document).ready(function() {
    $('#norma').select2();
    $('#lab').select2();
    $('#precios').DataTable();
    getParametros();
});

function getParametros()
{
    let tabla = document.getElementById('divPrecios');
    let tab = '';
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/precios/catalogo/getParametros",
        data: {
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {      

            tab += '<table id="precios" class="table table-sm">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>#</th>';
            tab += '          <th>Sucursal</th>';
            tab += '          <th># Parametro</th> '; 
            tab += '          <th>Parametro</th>';
            tab += '          <th>Formula</th>';
            tab += '          <th>Precio</th>';
            tab += '          <th>Opc</th>';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>'; 
            $.each(response.model, function (key, item) {
                tab += '<tr>';
                tab += '<td>'+item.Id_precio+'</td>';
                tab += '<td>'+item.Sucursal+'</td>';
                tab += '<td>'+item.Id_parametro+'</td>';
                tab += '<td>'+item.Parametro+'</td>';
                tab += '<td>'+item.Tipo_formula+'</td>';
                tab += '<td><input type="number" id="precio'+item.Id_precio+'" value="'+item.Precio+'"></td>';
                tab += '<td><i class="fa fa-check text-success" onclick="savePrecioCat('+item.Id_precio+')"></i></td>';
                tab += '</tr>';
            });
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;
            var t = $('#precios').DataTable({
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
function savePrecioCat(id){
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/precios/catalogo/savePrecioCat",
        data: {
            id:id,
            precio:$("#precio"+id).val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {      
            console.log(response)
            alert("Precio Modificado")
            getParametros();
        } 
    });
}
