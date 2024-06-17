
$(document).ready(function () {
    $(".select2").select2()
    $("#btnBuscar").click(function () {
        getMuestreos()
    });

});
function getMuestreos()
{
    let table = document.getElementById("divMuestreo")
    let tab = ''
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/supervicion/campo/getMuestreos",
        data: {
            muestreador:$("#muestreador").val(),
            mes:$("#mes").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response) 
            tab += '<button class="btn-info" onclick="selecionarCkeck()"><i class="fas fa-check-double"></i></button> <button class="btn-success" onclick="setLiberarTodoCampo()"><i class="fas fa-share"></i></button>'
            tab += '<table id="tabLote" class="table table-sm">'
            tab += '    <thead>'
            tab += '        <tr>'
            tab += '          <th></th>'
            tab += '          <th>Folio</th>'
            tab += '          <th>Captura</th> '
            tab += '          <th>Muestreador</th> '
            tab += '          <th>Fecha muestreo</th> '
            tab += '          <th>Opc</th> '
            tab += '        </tr>'
            tab += '    </thead>'
            tab += '    <tbody>'
            $.each(response.model, function (key, item) {
                tab += '<tr>'
                if (item.Estado != 4) {
                    tab += '<td><input class="form-check-input" value="'+item.Id_solicitud+'" onclick="supervisarBitacora('+item.Id_solicitud+')" name="ckHistorial" id="ckHistorial'+item.Id_solicitud+'" type="checkbox" ></td>'
                } else {
                    tab += '<td><input class="form-check-input" value="'+item.Id_solicitud+'" onclick="supervisarBitacora('+item.Id_solicitud+')" name="ckHistorial" id="ckHistorial'+item.Id_solicitud+'" type="checkbox" checked ></td>'
                }
                tab += '<td>' + item.Folio + '</td>'
                tab += '<td>' + item.Captura + '</td>'
                tab += '<td>' + item.Nombres + '</td>'
                tab += '<td>' + item.Fecha_muestreo + '</td>'
                tab += '<td><button class="btn btn-info" onclick="getBitacora('+item.Id_solicitud+')"><i class="voyager-external"></i> B</button> <button class="btn btn-info" onclick="getHojaCampo('+item.Id_solicitud+')"><i class="voyager-external"></i> H</button></td>'
                tab += '</tr>'
            })
            tab += '    </tbody>'
            tab += '</table>'
            table.innerHTML = tab
        } 
    }); 
}
function supervisarBitacora(id)
{
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/supervicion/campo/supervisarBitacoraCampo",
        data: {
            id:id,
            user:$("#user").val(),
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
function getBitacora(id){
    window.open(base_url + "/admin/campo/bitacoraCampo/" + id);
}
function getHojaCampo(id){
    window.open(base_url + "/admin/campo/hojaCampo/" + id);
}
function selecionarCkeck()
{
    allSelectCheck("ckHistorial")
}
function setLiberarTodoCampo()
{
    let tab = document.getElementById("tabLote")
    let ids = new Array()
    for (let i = 1; i < tab.rows.length; i++) {
        if(tab.rows[i].children[0].children[0].checked){
            ids.push(tab.rows[i].children[0].children[0].value)
        }
    }
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/supervicion/campo/setLiberarTodoCampo",
        data: {
            ids:ids,
            user:$("#user").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            if (response.sw == false) {
                alert("Error al liberar")
            } else {
                alert("Lotes liberadso")
            }
        }
    });
}