var idMuestra = 0; 
var idLote = 0;
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

$(function(){
  $("#formuploadajax").on("submit", function(e){
      e.preventDefault();
      var f = $(this);
      $.ajax({
          url: base_url + "/admin/laboratorio/metales/importCvs",
          type: "post",
          dataType: "html",
          data: new FormData(this),
          cache: false,
          contentType: false,
          processData: false
      })
          .done(function(res){
            console.log(res)
            alert("Datos importados correctamente") 
          });
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
          idLote:idLote,
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
            tab += '          <th></th> ';
            tab += '          <th></th> ';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            $.each(response.model, function (key, item) {
                tab += '<tr>';
                tab += '<td>'+item.Id_lote+'</td>';
                tab += '<td>'+item.Fecha+'</td>';
                tab += '<td><button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-file-word"></i></button></td>';
                tab += '<td><button type="button" class="btn btn-info"><i class="fas fa-print"></i></button></td>';
                tab += '</tr>';
            });
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;

            var t = $('#tablaLote').DataTable({        
              "ordering": false, 
              "language": {
                  "lengthMenu": "# _MENU_ por pagina",
                  "zeroRecords": "No hay datos encontrados", 
                  "info": "Pagina _PAGE_ de _PAGES_",
                  "infoEmpty": "No hay datos encontrados",
              }
          });
            $('#tablaLote tbody').on( 'click', 'tr', function () {
              if ( $(this).hasClass('selected') ) {
                  $(this).removeClass('selected');
              }
              else {
                  t.$('tr.selected').removeClass('selected');
                  $(this).addClass('selected');
              }
          } );
          $('#tablaLote tr').on('click', function(){
              let dato = $(this).find('td:first').html();
              idLote = dato;
              $("#idLote").val(idLote);
              // getLoteCapturaVol();
              getLoteCaptura()
            });
        }
    });
}

function getLoteCaptura() {
  numMuestras = new Array();
  let tabla = document.getElementById('divTablaControles');
  let tab = '';

  $.ajax({
      type: "POST",
      url: base_url + "/admin/laboratorio/metales/getLoteCapturaIcp",
      data: {
          idLote: idLote,
          _token: $('input[name="_token"]').val() 
      },
      dataType: "json",
      success: function (response) {
          console.log(response);

          tab += '<table id="tablaControles" class="table table-sm">';
          tab += '    <thead>';
          tab += '        <tr>';
          tab += '          <th>Folio</th>';
          tab += '          <th>Parametro</th>';
          tab += '          <th>CPS Prom</th>';
          tab += '          <th>Resultado</th>';
          tab += '          <th>F/H Análisis</th>';
          tab += '          <th>Tipo</th>';
          tab += '        </tr>';
          tab += '    </thead>';
          tab += '    <tbody>';
          $.each(response.model, function (key, item) {
              tab += '<tr>';
              tab += '  <td>'+item.Id_lote+'</td>';
              tab += '  <td>'+item.Id_parametro+'</td>';
              tab += '  <td>'+item.Cps+'</td>';
              tab += '  <td>'+item.Resultado+'</td>';
              tab += '  <td>'+item.Fecha+'</td>';
              tab += '  <td>'+item.Id_control+'</td>';
              tab += '</tr>';
       
          }); 
          tab += '    </tbody>';
          tab += '</table>';
          tabla.innerHTML = tab;


      }
  });
}