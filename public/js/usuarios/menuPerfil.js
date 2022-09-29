$(document).ready(function() {
    $("#perfil").select2();
    $('#btnGuardar').click(function () {
        setMenuPerfil()
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