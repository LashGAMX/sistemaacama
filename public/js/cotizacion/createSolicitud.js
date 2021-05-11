var base_url = 'https://dev.sistemaacama.com.mx';
$(document).ready(function () {
    $('#datos-tab').click();

});
// Get datos intermedario
function getDatoIntermediario()
{
    $.ajax({
        url: base_url + '/admin/cotizacion/solicitud/getDatoIntermediario', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          idCliente: $('#intermediario').val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        // async: false,
        success: function (response) { 
            console.log(response);
          $("#nombreInter").val(response.intermediario.Nombres+""+response.intermediario.A_paterno);
          $("#celularInter").val(response.intermediario.Celular1);
          $("#telefonoInter").val(response.intermediario.Tel_oficina);
        } 
    });  
}
//Get Sucursal cliente
function getSucursal()
{
    let sucursal = document.getElementById('sucursal');
    let contacto = document.getElementById('contacto');
    let tab = '';
    let tab2 = '';
    $.ajax({
        url: base_url + '/admin/cotizacion/solicitud/getSucursal', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          cliente: $('#clientes').val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) { 
            console.log(response)
            $.each(response.sucursal, function (key,item){
                tab += '<option value="'+item.Id_sucursal+'">'+item.Empresa+'</option>';
            });
            $.each(response.contacto, function (key,item){
                tab2 += '<option value="'+item.Id_contacto+'">'+item.Nombres+'</option>';
            });
            sucursal.innerHTML = tab;
            contacto.innerHTML = tab2;
        } 
    });   
}
function getDireccionReporte()
{
    let direccion = document.getElementById('direccionReporte');
    let tab = '';
    $.ajax({
        url: base_url + '/admin/cotizacion/solicitud/getDireccionReporte', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          idSucursal: $('#sucursal').val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) { 
            console.log(response);
            tab += '<option value="0">Sin seleccionar</option>';
            $.each(response.direccion, function (key,item){
                tab += '<option value="'+item.Id_direccion+'">'+item.Direccion+'</option>';
            });
            direccion.innerHTML = tab;
        } 
    });   
}

function setContacto()
{
    let element = [
        inputText('Nombre','nombreContacto','nombreContacto','Nombre'),
        inputText('Apellido paterno','paternoContacto','paternoContacto','Paterno'),
        inputText('Apellido materno','maternoContacto','maternoContacto','Materno'),
        inputText('Celular','celularContacto','celularContacto','Celular'),
        inputText('Telefono','telefonoContacto','telefonoContacto','Telefono'),
        inputText('Correo','correoContacto','correoContacto','Correo'),
      ];
      itemModal[0] = element;
      newModal('divModal','setContacto','Crear contacto cliente','lg',3,2,0,inputBtn('','','Guardar','save','success',''));
}
function createContacto()
{
    $.ajax({
        url: base_url + '/admin/cotizacion/solicitud/setContacto', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
         idCliente:$("#clientes").val(),
          nombre:$("#nombreContacto").val(),
          paterno:$("#paternoContacto").val(),
          materno:$("#maternoContacto").val(),
          celular:$("#celularContacto").val(),
          telefono:$("#telefonoContacto").val(),
          correo:$("#correoContacto").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) { 
            console.log(response);
            tab += '<option value="0">Sin seleccionar</option>';
            $.each(response.direccion, function (key,item){
                tab += '<option value="'+item.Id_direccion+'">'+item.Direccion+'</option>';
            });
            direccion.innerHTML = tab;
        } 
    });  
}