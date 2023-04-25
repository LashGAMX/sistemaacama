 
var area = "directos";

$(document).ready(function () {

    $('#summernote').summernote({
        placeholder: '', 
        tabsize: 2,
        height: 100,

      });
      
      $('#parametro').select2();

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
    $("#btnGuardarPlantilla").click(function(){
        setPlantilla()
    })
    $('#btnPendiente').click(function () {
        getPendientes()
    });
});
function getPendientes()
{ 
    let tabla = document.getElementById('divPendientes');
    let tab = '';
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/"+area+"/getPendientes",
        data: {
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            model = response.model
            tab += '<table class="table table-sm" style="font-size:10px">';
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
        }
    });
}
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
                        tab += '<td><button type="button" onclick="loteDetalle('+item.Id_lote+')" class="btn btn-primary">Agregar</button>&nbsp<button type="button" onclick="getDetalleLote('+item.Id_lote+','+item.Id_tecnica+')" data-toggle="modal" data-target="#modalLote" class="btn btn-primary">Detalle</button></td>';
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
function getDetalleLote(idLote,idParametro)
{
    $("#idLote").val(idLote)
    let summer = document.getElementById("divSummer")
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/"+area+"/getDetalleLote",
        data: {
            idLote: idLote,
            idParametro:idParametro,
            fecha:$("#fecha").val(), 
            _token: $('input[name="_token"]').val()
        }, 
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);                        
            $("#tituloBit").val(response.plantilla[0].Titulo)
            $("#revBit").val(response.plantilla[0].Rev)
            summer.innerHTML = '<div id="summernote">'+response.plantilla[0].Texto+'</div>';

            // summer.innerHTML = '<div id="summernote">Hola Modal</div>';
            $('#summernote').summernote({
                placeholder: '', 
                tabsize: 2, 
                height: 300,         
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
function setPlantilla()
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/"+area+"/setPlantilla",
        data: {
            id:$("#idLote").val(),
            texto: $("#summernote").summernote('code'),
            titulo: $("#tituloBit").val(),
            rev:$("#revBit").val(),
            _token: $('input[name="_token"]').val()
        }, 
        dataType: "json",
        success: function (response) {       
            console.log(response);
            alert("Plantilla guardada")
        }
    });
} 