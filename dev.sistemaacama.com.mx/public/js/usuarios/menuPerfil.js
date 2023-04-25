$(document).ready(function() {
    $("#perfil").select2();
    $('#btnGuardar').click(function () {
        setMenuPerfil()
    });
    $('#btnBuscar').click(function () {
        getMenuPerfil()
    });
});


function setMenuPerfil()
{
    let menus = new Array()
    let menusTemp = $('input:checkbox[name=menus]:checked')
    for (let i = 0; i < menusTemp.length; i++) {
        menus.push(menusTemp[i].value)
    }
    console.log(menus)
    if($("#perfil").val() != 0)
    {
        $.ajax({
            type: 'POST',
            url: base_url + "/admin/usuarios/setMenuPerfil",
            data: {
                menus:menus,
                perfil:$("#perfil").val(),
                _token: $('input[name="_token"]').val(),
            },
            dataType: "json",
            async: false,
            success: function (response) {      
                console.log(response)
                alert("Datos guardados correctamente")
            } 
        });
    }else{
        alert("No puedes guardar datos sin haber seleccionado un perfil")
    }
}
function getMenuPerfil()
{
    let tabla = document.getElementById('divTable');
    let tab = '';
    let temp = '';
    if($("#perfil").val() != 0)
    {
        $.ajax({
            type: 'POST', 
            url: base_url + "/admin/usuarios/getMenuPerfil",
            data: {
                perfil:$("#perfil").val(),
                _token: $('input[name="_token"]').val(),
            },
            dataType: "json",
            async: false,
            success: function (response) {
                console.log(response);
                tab += '<table id="tablaMenu" class="table">';
                tab += '    <thead>';
                tab += '        <tr>';
                tab += '          <th>Menu</th>';
                tab += '          <th>Sub-Menu</th>';
                tab += '        </tr>';
                tab += '    </thead>';
                tab += '    <tbody>';
                $.each(response.padre, function (key, item) {
                    tab += '<tr>';
                    tab += '<td><div class="custom-control custom-checkbox">';
                    $.each(response.model , function (key, mod){
                        if(mod.Id_item == item.id){
                            temp = 'checked';
                        } 
                    });
                    tab += '    <input type="checkbox" '+temp+' class="custom-control-input" name="menus" value="'+item.id+'">';
                    tab += '    <label for="">'+item.title+'</label>';
                    tab += '</div></td><td>';
                    temp = '';
                    $.each(response.hijo, function(key , item2){
                        if(item.id == item2.parent_id){
                            $.each(response.model , function (key, mod){
                                if(mod.Id_item == item2.id){
                                    temp = 'checked';
                                } 
                            });
                        tab +='  <div class="custom-control custom-checkbox">';
                        tab +='    <input type="checkbox" '+temp+' class="custom-control-input" name="menus" value="'+item2.id+'">';
                        tab +='    <label for="">'+item2.title+'</label>';
                        tab +='  </div>';
                        }
                        temp = '';
                    });
                    tab += '</td></tr>';
                    temp = '';
                });
                tab += '    </tbody>';
                tab += '</table>';
                tabla.innerHTML = tab;
            }
        });
    }else{
        alert("Selecciona un perfil por favor!")
    }
}