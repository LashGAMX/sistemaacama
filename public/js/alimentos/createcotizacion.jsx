$(document).ready(function () {
    $(".select2").select2();
    intermediario();
    clienteGen(); 

    $("#intermediario").on("change", function () {
        let selectedOption = $(this).find("option:selected");
        Idintermediario = selectedOption.data("id-intermediario");
        clienteGen(Idintermediario);
     //   console.log("El intermediario seleccionado es: " + Idintermediario);
    });
    
    $("#cliente").on("change", function () {
        let selectedOption = $(this).find("option:selected");
        IdCliente = selectedOption.data("id-cliente");
        sucursalCliente(IdCliente);
      //  console.log("El cliente seleccionado es: " + IdCliente);
    });
 
    $("#clienteSucursal").on("change", function () {
        let selectedOption = $(this).find("option:selected");
        IdSucursal = selectedOption.data("id-sucursal");
       
        console.log("La sucursal  seleccionada es: " + IdSucursal);
    });
});


let Idintermediario = '';
let IdCliente = '';
let IdSucursal='';
function intermediario() {
    $.ajax({
        url: base_url + "/admin/alimentos/setIntermediarios",
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        dataType: "json",
        success: function (response) {
            $("#intermediario").empty();

            $("#intermediario").append(
                '<option value="0">Sin seleccionar</option>'
            );

            $.each(response, function (index, intermediario) {
                let optionText =
                    "(" +
                    intermediario.Id_intermediario +
                    ") " +
                    intermediario.Nombres +
                    " " +
                    intermediario.A_paterno;
                $("#intermediario").append(
                    $("<option>", {
                        value: intermediario.Id_intermediario,
                        text: optionText,
                        "data-id-intermediario": intermediario.Id_intermediario,
                    })
                );
            });
            $("#intermediario").select2();
        },
        error: function (error) {
            console.log(error);
        },
    });
}
function clienteGen(id) {
    $.ajax({
        url: base_url + "/admin/alimentos/setClienteGen",
        type: "POST",
        data: {
            id:id,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        dataType: "json",
        success: function (response) {
            // Limpiar el select antes de añadir las nuevas opciones
            $("#cliente").empty();

            // Añadir la opción por defecto
            $("#cliente").append('<option value="0">Sin seleccionar</option>');

            // Iterar sobre los clientes recibidos
            $.each(response, function (index, cliente) {
                let optionText = cliente.Empresa;
                $("#cliente").append(
                    $("<option>", {
                        value: cliente.Id_cliente,
                        text: optionText,
                        "data-id-cliente": cliente.Id_cliente, // Guardar el Id_cliente en el atributo data
                    })
                );
            });

            // Refrescar el select2 para que muestre los cambios
            $("#cliente").select2();
        },
        error: function (error) {
            console.log(error);
        },
    });
}
function sucursalCliente(id) {
    console.log('cliente'+id)
    $.ajax({
        url: base_url + "/admin/alimentos/setSucursalCliente",
        type: "POST",
        data: {
            id:id,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        dataType: "json",
        success: function (response) {
            // Limpiar el select antes de añadir las nuevas opciones
            $("#clienteSucursal").empty();

            // Añadir la opción por defecto
            $("#clienteSucursal").append('<option value="0">Sin seleccionar</option>');

            // Iterar sobre los clientes recibidos
            $.each(response, function (index, sucursal) {
                let optionText = "(" +
                    sucursal.Id_sucursal +
                    ") " + sucursal.Empresa;
                $("#clienteSucursal").append(
                    $("<option>", {
                        value: sucursal.Id_sucursal,
                        text: optionText,
                        "data-id-sucursal": sucursal.Id_sucursal, // Guardar el Id_cliente en el atributo data
                    })
                );
            });

            // Refrescar el select2 para que muestre los cambios
            $("#clienteSucursal").select2();
        },
        error: function (error) {
            console.log(error);
        },
    });
}

// function getClientesIntermediarios() {
//     let sub = document.getElementById('cliente');
//     let tab = '';
//     $.ajax({
//       url: base_url + '/admin/alimentos/getClientesIntermediarios', //archivo que recibe la peticion
//       type: 'POST', //método de envio
//       data: {
//         id: $('#intermediario').val(),
//         _token: $('input[name="_token"]').val(),
//       },
//       dataType: 'json',
//       async: false,
//       success: function (response) {
//         tab += '<option value="0">Sin seleccionar</option>';
//         $.each(response.model, function (key, item) {
//           if (data.Id_cliente == item.Id_cliente) {
//             tab += '<option value="' + item.Id_cliente + '" selected>' + item.Empresa + '</option>';
//           } else {
//             tab += '<option value="' + item.Id_cliente + '">' + item.Empresa + '</option>';
//           }
//         });
//         sub.innerHTML = tab;
//       }
//     });
//   }

// function getSucursal() {
//     let clienteId = $('#cliente').val();
//     if (clienteId == '0' || clienteId == null) {
//         $('#clienteSucursal').html('<option value="0">Sin seleccionar</option>');
//         return;
//     }

//     let sub = document.getElementById('clienteSucursal');
//     let tab = '';
//     $.ajax({
//         url: base_url + '/admin/alimentos/getSucursal',
//         type: 'POST',
//         data: {
//             id: clienteId,
//             _token: $('meta[name="csrf-token"]').attr('content'),
//         },
//         dataType: 'json',
//         success: function (response) {
//             console.log(response); // Verifica la respuesta
//             tab += '<option value="0">Sin seleccionar</option>';
//             $.each(response.model, function (key, item) {
//                 tab += `<option value="${item.Id_sucursal}">(${item.Id_sucursal}) ${item.Empresa}</option>`;
//             });
//             sub.innerHTML = tab;
//             $('#clienteSucursal').trigger('change.select2');
//         },
//         error: function (error) {
//             console.log(error);
//         }
//     });
// }

// function getDataCliente() {
//     let clienteSucursalId = $('#clienteSucursal').val();
//     if (clienteSucursalId == '0' || clienteSucursalId == null) {
//         $('#clienteDir').html('<option value="0">Sin seleccionar</option>');
//         $('#clienteGen').html('<option value="0">Sin seleccionar</option>');
//         return;
//     }

//     let sub = document.getElementById('clienteDir');
//     let tab = '';
//     let sub2 = document.getElementById('clienteGen');
//     let tab2 = '';
//     $.ajax({
//         url: base_url + '/admin/alimentos/getDataCliente',
//         type: 'POST',
//         data: {
//             id: clienteSucursalId,
//             _token: $('meta[name="csrf-token"]').attr('content'),
//         },
//         dataType: 'json',
//         success: function (response) {
//             console.log(response); // Verifica la respuesta
//             tab += '<option value="0">Sin seleccionar</option>';
//             $.each(response.direccion, function (key, item) {
//                 tab += `<option value="${item.Id_direccion}">${item.Direccion}</option>`;
//             });
//             sub.innerHTML = tab;
//             $('#clienteDir').trigger('change.select2');

//             tab2 += '<option value="0">Sin seleccionar</option>';
//             $.each(response.contacto, function (key, item) {
//                 tab2 += `<option value="${item.Id_contacto}">${item.Nombre}</option>`;
//             });
//             sub2.innerHTML = tab2;
//             $('#clienteGen').trigger('change.select2');

//             $("#nomCli").val(response.model.Empresa);
//         },
//         error: function (error) {
//             console.log(error);
//         }
//     });
// }

// function setDireccionCliente() {
//     $("#dirCli").val($("#clienteDir option:selected").text());
// }

// function setDatoGeneral() {
//     let clienteGenId = $("#clienteGen").val();
//     if (clienteGenId == '0' || clienteGenId == null) {
//         $("#telCli").val('');
//         $("#correoCli").val('');
//         $("#atencion").val('');
//         return;
//     }

//     $.ajax({
//         url: base_url + '/admin/alimentos/setDatoGeneral',
//         type: 'POST',
//         data: {
//             id: clienteGenId,
//             _token: $('meta[name="csrf-token"]').attr('content'),
//         },
//         dataType: 'json',
//         success: function (response) {
//             console.log(response); // Verifica la respuesta
//             $("#telCli").val(response.model.Telefono);
//             $("#correoCli").val(response.model.Email);
//             $("#atencion").val(response.model.Nombre);
//         },
//         error: function (error) {
//             console.log(error);
//         }
//     });
// }
