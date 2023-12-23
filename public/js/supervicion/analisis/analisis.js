
$(document).ready(function () {
    $(".select2").select2()
    $("#btnBuscar").click(function () {
        getLotes()
    });

});
function supervisarBitacora(id)
{
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/supervicion/analisis/supervisarBitacora",
        data: {
            id:id,
            parametro:$("#parametro").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response) 
            alert(response.msg)
        } 
    }); 
}
function getLotes()
{
    let table = document.getElementById("divLote")
    let tab = ''
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/supervicion/analisis/getLotes",
        data: {
            parametro:$("#parametro").val(),
            tipo:$("#tipo").val(),
            mes:$("#mes").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response) 
            tab += '<table id="tabLote" class="table table-sm">'
            tab += '    <thead>'
            tab += '        <tr>'
            tab += '          <th></th>'
            tab += '          <th>Id</th>'
            tab += '          <th>Fecha</th> '
            tab += '          <th>Parametro</th> '
            tab += '          <th>Asignados</th> '
            tab += '          <th>Liberados</th> '
            tab += '          <th>Opc</th> '
            tab += '        </tr>'
            tab += '    </thead>'
            tab += '    <tbody>'
            $.each(response.model, function (key, item) {
                tab += '<tr>'
                if (item.Supervisado == 0) {
                    tab += '<td><input class="form-check-input" onclick="supervisarBitacora('+item.Id_lote+')" id="ckHistorial'+item.Id_lote+'" type="checkbox" ></td>'
                } else {
                    tab += '<td><input class="form-check-input" onclick="supervisarBitacora('+item.Id_lote+')" id="ckHistorial'+item.Id_lote+'" type="checkbox" checked ></td>'
                }
                tab += '<td>' + item.Id_lote + '</td>'
                tab += '<td>' + item.Fecha + '</td>'
                if (response.parametro.Id_area == 17) {
                    tab += '<td>LOTE DE ICP</td>'
                    tab += '<td>N/A</td>'
                    tab += '<td>N/A</td>'
                } else {
                    tab += '<td>('+item.Id_tecnica+') ' + item.Parametro + '</td>'   
                    tab += '<td>' + item.Asignado + '</td>'
                    tab += '<td>' + item.Liberado + '</td>'
                }
                tab += '<td><button class="btn btn-info" onclick="getBitacora('+item.Id_lote+')"><i class="voyager-external"></i></button></td>'
                tab += '</tr>'
            })
            tab += '    </tbody>'
            tab += '</table>'
            table.innerHTML = tab
        } 
    }); 
}
function getBitacora(id){
    window.open(base_url + "/admin/laboratorio/analisis/bitacora/impresion/" + id);
}