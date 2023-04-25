$(document).ready(function()
{
    $("#btnBuscar").click(function()
    {
        getParametros();
        console.log('getParametros')
    });

});
function getParametros()
{
    console.log('getParametros')
    $.ajax({
        url: base_url + '/admin/analisisQ/LimitesNorma/parametros', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            norma:$("#norma").val(),
            
            _token: $('input[name="_token"]').val(),
          },
        dataType: 'json', 
        async: false, 
        success: function (response) {
          console.log(response);
        }
    }); 
}