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
      $('#btnLiberar').click(function () {
        liberarIcp();
      });
      $('#btnSetPlantilla').click(function () {
        setPlantilla();
      });
      
      
});
function setUpdateRestultado(id) {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/metales/setUpdateRestultado",
        data: {
            id: id,
            cps: $("#cps" + id).val(),
            res: $("#res" + id).val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        success: function (response) {
            if (response.status === "success") {
                alert(response.message); // Ej: "Resultado actualizado correctamente"
                console.log(response.data); // Puedes mostrar los datos devueltos
            } else {
                alert("Error: " + response.message);
                console.warn(response.errors); // Si tienes detalles
            }
        },
        error: function (xhr, status, error) {
            alert("Error al procesar la solicitud");
            console.error(xhr.responseText); // Para depurar en consola
        }
    });
}

function setPlantilla(){
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/metales/setPlantilla",
        data: {
            id: idLote,
            texto: $("#summernote").summernote('code'),
            titulo: $("#tituloBit").val(),
            rev: $("#revBit").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            alert("Plantilla modificada")
        }
    });
}
function getPlantilla(id)
{
    console.log("getPlantilla")
    let summer = document.getElementById("divSummer")
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/metales/getPlantilla",
        data: {
            id: id,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            $("#tituloBit").val(response.plantilla[0].Titulo)
            $("#revBit").val(response.plantilla[0].Rev)
            summer.innerHTML = '<div id="summernote">' + response.plantilla[0].Texto + '</div>';
            $('#summernote').summernote({
                placeholder: '',
                tabsize: 2,
                height: 300,
                theme:"bs4-dark",
                toolbar: [
                    // [groupName, [list of button]]
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']]
                  ]
            });
        }
    });
}
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
            getLoteCaptura()

          });
  });   
});

function liberarIcp()
{
    if(idLote != ''){
        $.ajax({
            type: 'POST',
            url: base_url + "/admin/laboratorio/metales/liberarIcp",
            data: {
                id: idLote,
                _token: $('input[name="_token"]').val(),
            },
            dataType: "json",
            async: false,
            success: function (response) {            
                console.log(response);
                swal("Liberado!", "Liberado creado correctamente!", "success");
                
            }
        });
    }else{
        alert("No has seleccionado una lote")
    }
}

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
function bitacora(id)
{
    window.open(base_url + "/admin/laboratorio/metales/bitacoraIcp/" + id);
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
                tab += '<td><button type="button" id="btnGetPlantilla" onclick="getPlantilla('+item.Id_lote+')" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-file-word"></i></button></td>';
                tab += '<td><button type="button" class="btn btn-info" onclick="bitacora('+item.Id_lote+')"><i class="fas fa-print"></i></button></td>';
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
          tab += '          <th>Opc</th>';
          tab += '        </tr>';
          tab += '    </thead>';
          tab += '    <tbody>';
          $.each(response.model, function (key, item) {
            if (item.Id_control == 1) {
                switch (item.Id_parametro) {
                    case 207:// Al
                    case 212://Cr
                    case 300://Ni
                    case 209://Ba    
                    case 211://Cu
                    case 214://Mn
                    case 233://Se
                    case 217://Ag
                        if (item.Resultado >= 0.030 && item.Resultado <= 0.390) {
                            tab += '<tr class="bg-primary">';    
                        }else{
                            tab += '<tr>';   
                        }
                        break;
                    case 213:// Fe
                        if (item.Resultado >= 0.090 && item.Resultado <= 1.170) {
                            tab += '<tr class="bg-primary">';    
                        }else{
                            tab += '<tr>';               
                        }
                        break;
                    default:
                        break;
                }
            } else {
                tab += '<tr>';   
            }
              tab += '  <td>'+item.Id_codigo+'</td>';
              tab += '  <td>'+item.Parametro+'</td>';
              tab += '  <td><input id="cps'+item.Id_detalle+'" style="width:50%;" value="'+item.Cps+'"></td>';
              tab += '  <td><input id="res'+item.Id_detalle+'" style="width:50%;" value="'+item.Resultado+'"></td>';
              tab += '  <td>'+item.Fecha+'</td>';
              if (item.Id_control == 1) {
                tab += '  <td class="bg-success">Resultado</td>';
              } else {
                tab += '  <td>Datos Equipo</td>';                
              }
              tab += '<td><button type="button" onclick="setUpdateRestultado('+item.Id_detalle+')" class="btn-success"><i class="fas fa-check"></button></td>';
              tab += '</tr>';
       
          }); 
          tab += '    </tbody>';
          tab += '</table>';
          tabla.innerHTML = tab;

          var t = $('#tablaControles').DataTable({
            "ordering": false,
            "pageLength": 100,
            "language": {
                "lengthMenu": "# _MENU_ por pagina",
                "zeroRecords": "No hay datos encontrados",
                "info": "Pagina _PAGE_ de _PAGES_",
                "infoEmpty": "No hay datos encontrados",
            },
            "scrollY": 400,
            "scrollCollapse": true
        });



      }
  });
}