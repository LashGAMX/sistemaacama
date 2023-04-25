

$(document).ready(function()
{
    $("#guardar").click(function()
    {
        create();
    });

});

function create()
{
    $.ajax({
        url: base_url + '/admin/analisisQ/formulas/constante_create', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            constante:$("#constante").val(),
            valor:$("#valor").val(),
            descripcion:$("#descripcion").val(), 
            _token: $('input[name="_token"]').val(),
          },
        dataType: 'json', 
        async: false, 
        success: function (response) {
          console.log(response);
        }
    }); 
}