$(document).ready(function () {
    $('#summernote').summernote({
        placeholder: '', 
        tabsize: 2,
        height: 100,
        theme: 'cosmo',
      });

      $('#btnCrearLote').click(function () {
        createLote();
      });
      $('#btnBuscarLote').click(function () {
        buscarLote();
      });
});


function createLote()
{
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/metales/createLoteIcp",
        data: {
            fecha: $("#fechaLote").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            swal("Registro!", "Lote creado correctamente!", "success");
            buscarLote()
        }
    });
}
function buscarLote()
{
    let tabla = document.getElementById('divLote');
    let tab = '';
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/metales/buscarLoteIcp",
        data: {
            fecha: $("#fechaLote").val(),
            _token: $('input[name="_token"]').val(), 
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            tab += '<table id="tablaLote" class="table table-sm">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th># Lote</th>';
            tab += '          <th>Fecha lote</th>';
            tab += '          <th>Opc</th> ';
            tab += '          <th></th> ';
            tab += '          <th></th> ';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            $.each(response.model, function (key, item) {
              tab += '<form action="'+base_url+'/admin/laboratorio/metales/importCvs" method="POST" enctype="multipart/form-data" >'
                tab += '<tr><input name="_token" hidden>';
                tab += '<td>'+item.Id_lote+'</td>';
                tab += '<td>'+item.Fecha+'</td>';
                tab += '<td><div class="custom-file"><input type="file" class="custom-file-input" id="customFile"></div></td>';
                tab += '<td><button type="submit" class="btn btn-success"><i class="fas fa-file-import"></i> Datos</button></td>';
                tab += '<td><button class="btn btn-info"><i class="fas fa-print"></i></button><button class="btn btn-secondary" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-file-word"></i></button></td>';
              tab += '</tr>';
              tab += '</form>';
            });
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;
        }
    });
}