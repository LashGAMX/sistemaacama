$(document).ready(function() {
    $('#norma').select2();
    $('#lab').select2();
    $('#precios').DataTable();
});

function getParametros()
{
    let tabla = document.getElementById('divPrecios');
    let tab = '';
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/"+area+"/muestraSinAsignarVol",
        data: {
            idLote:$("#idLote").val(),
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
            tab += '          <th>Norma</th>';
            tab += '          <th>Precio</th>';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            $.each(response.model, function (key, item) {
                tab += '<tr>';
                tab += '<td>'+item.Id_+'</td>';
                tab += '<td>'+item.Parametro+'</td>';
                tab += '<td></td>';
                tab += '</tr>';
            });
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;
            $('#precios').DataTable();
         
        } 
    });
}
