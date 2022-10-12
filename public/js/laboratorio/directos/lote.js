 
var area = "directos";

$(document).ready(function () {

    $('#summernote').summernote({
        placeholder: '', 
        tabsize: 2,
        height: 100,

      });

    $('#tablaLote').DataTable({        
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        }
    });

    $('#btnBuscar').click(function(){
        getLote()
    })
    $("#btnCrear").click(function(){
        setLote()
    })

});

function getLote(){
    let tabla = document.getElementById('divTable');
    let tab = '';
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/"+area+"/getLote",
        data: {
            id:$("#parametro").val(),
            fecha:$("#fecha").val(),
            _token: $('input[name="_token"]').val()
        }, 
        dataType: "json",
        success: function (response) {            
            console.log(response);
            tab += '<table id="tablaLote" class="table table-sm">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>#</th>';
            tab += '          <th>Tipo formula</th>';
            tab += '          <th>Fecha lote</th> ';
            tab += '          <th>Fecha creacion</th> ';
            tab += '          <th>Opc</th> '; 
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
                if(response.sw == true)
                {
                    $.each(response.model, function (key, item) {
                        tab += '<tr>';
                        tab += '<td>'+item.Id_lote+'</td>';
                        tab += '<td>'+item.Parametro+' ('+item.Tipo_formula+')</td>';
                        tab += '<td>'+item.Fecha+'</td>';
                        tab += '<td>'+item.created_at+'</td>';
                        tab += '<td><button type="button" onclick="loteDetalle('+item.Id_lote+')" class="btn btn-primary">Agregar</button></td>';
                      tab += '</tr>';
                    });
                }else{
                    tab += '<h5 style="color:red;">No hay datos</h5>';
                }
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;

            $('#tablaLote').DataTable({        
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
function loteDetalle(id){
    window.location = base_url + "/admin/laboratorio/"+area+"/loteDetalle/"+id
}
function setLote(){
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/"+area+"/setLote",
        data: {
            id:$("#parametro").val(),
            fecha:$("#fecha").val(),
            _token: $('input[name="_token"]').val()
        }, 
        dataType: "json",
        success: function (response) {            
            console.log(response);
            getLote();
        }
    });
}