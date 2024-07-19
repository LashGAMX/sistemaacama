var base_url = "http://sistemasofia.ddns.net:85/sofiadev"
$(document).ready(function () {

    $('#btnValidar').click(function () {
        // let contenido = document.getElementById('contenido')
        $.ajax({
            type: 'POST',
            url: base_url + "/clientes/getFirmaEncriptada",
            data: {
                codigo: $("#codigo").val(),
                _token: $('input[name="_token"]').val(),
            },
            dataType: "json",
            async: false,
            success: function (response) {
                console.log(response)
                // contenido.innerText = response.folioEncript
            }
        });
    });

});