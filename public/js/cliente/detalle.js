$(document).ready(function () {
    // $("#guardarTelefono").click(function(){
    //     console.log('btn guardar')
    //     datosGenerales();
    // });
    getDatosGenerales()
});

function datosGenerales() {
    console.log('btn guardar')
    $.ajax({
        url: base_url + '/admin/clientes/datosGenerales', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            idUser: $("#idUser").val(),
            telefono: $("#telefono").val(),
            correo: $("#correo").val(),
            direccion: $("#direccion").val(),
            atencion: $("#atencion").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response);
            if (response.sw == true) {
                swal("Registro", "Datos guardados correctamente", "success");
                console.log(response);
            }
        }
    });
}
function getDatosGenerales(){
    let tabla = document.getElementById('tabGenerales');
    let tab = '';
    $.ajax({
        type: 'POST',
        url: base_url + '/admin/clientes/getDatosGenerales', //archivo que recibe la peticion
        data: {
            id:$("#idUser").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            model = response.model
            tab += '<table class="table table-sm" style="font-size:10px">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>#</th>';
            tab += '          <th>Nombre</th>';
            tab += '          <th>Departamento</th>';
            tab += '          <th>Puesto</th>';
            tab += '          <th>Email</th>';
            tab += '          <th>Celular</th>';
            tab += '          <th>Telefono</th>';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            $.each(response.model, function (key, item) {
                tab += '<tr>';
                tab += '<td>'+item.Id_contacto+'</td>';
                tab += '<td>'+item.Nombre+'</td>';
                tab += '<td>'+item.Departamento+'</td>';
                tab += '<td>'+item.Puesto+'</td>';
                tab += '<td>'+item.Celular+'</td>';
                tab += '<td>'+item.Telefono+'</td>';
                tab += '</tr>';
            }); 
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;
        }
    });
}