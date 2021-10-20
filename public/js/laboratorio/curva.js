var base_url = "https://dev.sistemaacama.com.mx";

$(document).ready(function (){
    //* Acciones para los botones

    $('#calcular').click(function(){
        promedio();
    });
});

function promedio(){
    let suma;
    let prom;
    let abs1 = $('#ABS1').val();
    let abs2 = $('#ABS2').val();
    let abs3 = $('#ABS3').val();
    suma = abs1 + abs2 + abs3; 
    prom = suma/3;  
    
    $.ajax({
        url: base_url + '/admin/laboratorio/promedio', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          promedio:prom,
          _token: $('input[name="_token"]').val(),
        },
        dataType: 'json', 
        async: false, 
        success: function (response) {
          console.log(prom);
          resultado = prom;
          fix = response.resultado.toFixed(4);
            $("#promedio").val(fix);

        }
    });        
}