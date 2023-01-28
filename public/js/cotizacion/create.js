$(document).ready(function () {
  $('#datos-tab').click();
});
function getClientesIntermediarios()
{
  let sub = document.getElementById('cliente');
  let tab = '';
  $.ajax({
      url: base_url + '/admin/cotizacion/getClientesIntermediarios', //archivo que recibe la peticion
      type: 'POST', //método de envio
      data: {
        id: $('#intermediario').val(),
          _token: $('input[name="_token"]').val(),
      },
      dataType: 'json',
      async: false, 
      success: function (response) {
        tab += '<option value="0">Sin seleccionar</option>'; 
          $.each(response.model, function (key, item) {              
            tab += '<option value="'+item.Id_cliente+'">'+item.Empresa+'</option>'; 
          });
          sub.innerHTML = tab;
      }
  });
}
function getSucursal()
{
  let sub = document.getElementById('clienteSucursal');
  let tab = '';
  $.ajax({
      url: base_url + '/admin/cotizacion/getSucursal', //archivo que recibe la peticion
      type: 'POST', //método de envio
      data: {
        id: $('#cliente').val(),
          _token: $('input[name="_token"]').val(),
      },
      dataType: 'json',
      async: false, 
      success: function (response) {
        tab += '<option value="0">Sin seleccionar</option>'; 
          $.each(response.model, function (key, item) {              
            tab += '<option value="'+item.Id_sucursal+'">'+item.Empresa+'</option>'; 
          });
          sub.innerHTML = tab;
      }
  });
}
function getDataCliente()
{
  let sub = document.getElementById('clienteDir');
  let tab = '';
  $.ajax({
      url: base_url + '/admin/cotizacion/getDataCliente', //archivo que recibe la peticion
      type: 'POST', //método de envio
      data: {
        id: $('#clienteSucursal').val(),
          _token: $('input[name="_token"]').val(),
      },
      dataType: 'json',
      async: false, 
      success: function (response) {
        console.log(response)
        tab += '<option value="0">Sin seleccionar</option>'; 
          $.each(response.direccion, function (key, item) {              
            tab += '<option value="'+item.Id_direccion+'">'+item.Direccion+'</option>'; 
          });
          sub.innerHTML = tab;
          $("#nomCli").val(response.model.Empresa)
          $("#atencion").val(response.model.Atencion)
          $("#telCli").val(response.model.Telefono)
          $("#correoCli").val(response.model.Correo)
      }
  });
}
function setDireccionCliente()
{
  $("#dirCli").val($("#clienteDir option:selected").text())
}