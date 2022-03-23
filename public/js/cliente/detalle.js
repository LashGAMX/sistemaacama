$(document).ready(function (){

    // $("#guardarTelefono").click(function(){
    //     console.log('btn guardar')
    //     datosGenerales();
    // });


});

function datosGenerales(){
    console.log('btn guardar')
    $.ajax({
        url: base_url + '/admin/datosGenerales', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            idUser:$("#idUser").val(),
          telefono:$("#telefono").val(),
          correo:$("#correo").val(),
          direccion:$("#direccion").val(),
          atencion:$("#atencion").val(),
          
          _token: $('input[name="_token"]').val(),
        },
        dataType: 'json', 
        async: false, 
        success: function (response) {
         console.log(response);
        if (response.sw == true) {
            swal("Good job!", "Guardado!", "success");
            console.log(response);
        }
        }
    });   
}