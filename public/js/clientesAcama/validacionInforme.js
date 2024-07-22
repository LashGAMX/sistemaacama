var base_url = "http://sistemasofia.ddns.net:85/sofiadev"
$(document).ready(function () {

    $('#btnValidar').click(function () {
        // let contenido = document.getElementById('contenido')
        $.ajax({
            type: 'POST',
            url: base_url + "/clientes/getFirmaEncriptada",
            data: {
                codigo: $("#codigo").val(),
                "_token": $("meta[name='csrf-token']").attr("content"),
            },
            dataType: "json",
            async: false,
            success: function (response) {
                console.log(response)
                contenido.innerText = response.folioEncript
            }
        });
    });

});