$(document).ready(function () {
    getBitacoras()
    $('#btnGuardar').click(function () {
        setPlantilla()
    });
});
function getBitacoras()
{
    let tabla = document.getElementById('tabPlantillas');
    let tab = '';
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/config/plantillas/getPlantillas",
        data: {
            tipo: $("#tipo").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {      
            console.log(response);
            tab += '<table id="tablaBitacoras" class="table table-sm">'; 
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>#</th>';
            tab += '          <th>Parametro</th>';
            tab += '          <th>Opc</th> '; 
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            $.each(response.model, function (key, item) {
                tab += '<tr>';
                tab += '<td>'+item.Id_plantilla+'</td>';
                tab += '<td>('+item.Id_parametro+') '+item.Parametro+'</td>';
                tab += '<td><button type="button"  onclick="getDetalleBitacora('+item.Id_plantilla+',\' ('+item.Id_parametro+') '+item.Parametro+'\')"  data-toggle="modal" data-target="#modalDetalle" class="btn btn-primary"><i class="fas fa-pen"></i> Editar</button></td>';
                tab += '</tr>';
            });
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;

            $('#tablaBitacoras').DataTable();
         
        } 
    });
}
var idPlantilla = 0
function getDetalleBitacora(id,parametro){
    $("#parametro").val(parametro)
    let summer = document.getElementById("divSummer");
    let temp = ''
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/config/plantillas/getDetalleBitacora",
        data: {
            id: id,
            tipo: $("#tipo").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {      
            console.log(response);
            idPlantilla = id
            $.each(response.model, function (key, item) {
                temp += ''+item.Texto
            });
            summer.innerHTML = '<div id="summernote">'+temp+'</div>';
            $('#summernote').summernote({
                placeholder: '',
                tabsize: 2,
                height: 300,            
            });
        } 
    });
}
function setPlantilla()
{
    $.ajax({ 
        type: "POST",
        url: base_url + "/admin/config/plantillas/setPlantilla",
        data: {
            tipo:$("#tipo").val(),
            id: idPlantilla,
            texto: $("#summernote").summernote('code'),
            // texto: $("#summernote").summernote('code'),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);                        
         
        }
    });
}