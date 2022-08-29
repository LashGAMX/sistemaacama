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
                tab += '<td>'+item.Precio+'</td>';
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
