$(document).ready(function () {
    getBitacoras()
    $('#btnGuardar').click(function () {
        setPlantilla()
    });
    $('#btnCrear').click(function () {
        $('#summmerModal').summernote({
            placeholder: '',
            tabsize: 2,
            height: 300,            
        });
    });
    $('#btnCrearPlantilla').click(function () {
        setNewPlantilla()
    });
});
function setNewPlantilla()
{
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/config/plantillas/setNewPlantilla",
        data: {
            id: $("#parametros").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {      
            console.log(response);
            alert("Plantilla creada")
            location.reload();
        } 
    });
}
function getBitacoras()
{
    let tabla = document.getElementById('tabPlantillas');
    let tab = '';
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/config/plantillas/getPlantillas",
        data: {
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
                // tab += '<td><button type="button"  onclick="getDetalleBitacora('+item.Id_plantilla+',\' ('+item.Id_parametro+') '+item.Parametro+'\')"  data-toggle="modal" data-target="#modalDetalle" class="btn btn-primary"><i class="fas fa-pen"></i> Editar</button></td>';
                tab += '<td>';
                tab += '<button type="button" onclick="getDetalleBitacora('+item.Id_plantilla+',\' ('+item.Id_parametro+') '+item.Parametro+'\')" data-toggle="modal" data-target="#modalDetalle" class="btn btn-primary"><i class="fas fa-pen"></i> Editar</button> ';
                tab += '<button type="button" onclick="PdfBitacora('+item.Id_parametro+')" class="btn btn-danger"><i class="fas fa-file-pdf"></i> PDF</button>';
                tab += '</td>';

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
function PdfBitacora(id) {
    console.log('Parametro:', id);
    let url = base_url + '/admin/config/plantillas/pdfBitacora/' + id; 
    window.open(url, '_blank');
}

function getDetalleBitacora(id,parametro){
    $("#parametro").val(parametro)
    let summerTitulo = document.getElementById("divSummerTitulo");
    let summer = document.getElementById("divSummer");
    let summer2 = document.getElementById("divSummer2");
    let temp = ''
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/config/plantillas/getDetalleBitacora",
        data: {
            id: id,
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
            summerTitulo.innerHTML = '<div id="summernoteTitulo">'+response.model[0].Titulo+'</div>';
            $('#summernoteTitulo').summernote({
                placeholder: '',
                tabsize: 2,
                height: 100,       
                fontNames: ['Arial',],
            });
            summer.innerHTML = '<div id="summernote">'+temp+'</div>';
            $('#summernote').summernote({
                placeholder: '',
                tabsize: 2,
                height: 300,            
            });
            summer2.innerHTML = '<div id="summernote2">'+response.model[0].Rev+'</div>';
            $('#summernote2').summernote({
                placeholder: '',
                tabsize: 2,
                height: 100,            
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
            id: idPlantilla,
            texto: $("#summernote").summernote('code'),
            rev: $("#summernote2").summernote('code'),
            titulo: $("#summernoteTitulo").summernote('code'),
            // texto: $("#summernote").summernote('code'),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);                        
            alert("Plantilla editada")
        }
    });
}