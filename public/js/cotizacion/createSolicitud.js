$(document).ready(function () {
    //todo Incializadores
    $('#datos-tab').click();
    _constructor()
    $('#addRow').click(function () {
        getPuntoMuestro();
     });
     addColPunto()
     $('#parametro-tab').click(function () {
        createTabParametros();
      });
    
});
//todo Goblales
var model = new Array()
var parametros = new Array()
function _constructor()
{
    getDataCotizacion()
    getDatoIntermediario()
    getClienteRegistrado()
    getSucursalCliente()
    getDireccionReporte()
    getDataContacto()
    getSubNormas()
    createTabParametros()
}
function addColPunto()
{
  var t = $('#puntoMuestro').DataTable({
    "ordering": false,
    "pageLength": 100,
    "language": {
        "lengthMenu": "# _MENU_ por pagina",
        "zeroRecords": "No hay datos encontrados",
        "info": "Pagina _PAGE_ de _PAGES_",
        "infoEmpty": "No hay datos encontrados",
    },
    "scrollY": 200,
    "scrollCollapse": true
});

  $('#puntoMuestro tbody').on( 'click', 'tr', function () {
    if ( $(this).hasClass('selected') ) {
        $(this).removeClass('selected');
    }
    else {
        t.$('tr.selected').removeClass('selected');
        $(this).addClass('selected');
    }
} );

$('#delRow').click( function () {
    t.row('.selected').remove().draw( false );
    setPuntoMuestro()
} );

}
function getDatoIntermediario() {
    $.ajax({
        url: base_url + '/admin/cotizacion/solicitud/getDatoIntermediario', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            id: $('#intermediario').val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response);
            $("#nombreInter").val(response.model.Nombres);
            $("#celularInter").val(response.model.Celular1);
            $("#telefonoInter").val(response.model.Tel_oficina);
        }
    });
}
function getDataCotizacion()
{
    $.ajax({
        url: base_url + '/admin/cotizacion/solicitud/getDataCotizacion', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            id: $('#idCot').val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response);
            model = response.model
            parametros = response.parametro
        }
    });
}
function getClienteRegistrado()
{
    let sec = document.getElementById("clientes")
    let tab  = '';
    $.ajax({
        url: base_url + '/admin/cotizacion/solicitud/getClienteRegistrado', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            id: $('#idCot').val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response);
            $.each(response.model, function(key, item){
                if (model.Id_cliente == item.Id_cliente) {
                    tab += '<option value="'+item.Id_cliente+'" selected>'+item.Empresa+'</option>'
                } else {
                    tab += '<option value="'+item.Id_cliente+'">'+item.Empresa+'</option>'
                }
                
            })
            sec.innerHTML = tab
        }
    });
}
function getSucursalCliente()
{
    let sec = document.getElementById("sucursal")
    let sec2 = document.getElementById("contacto")
    let tab  = '';
    let tab2  = '';
    $.ajax({
        url: base_url + '/admin/cotizacion/solicitud/getSucursalCliente', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            id: $('#clientes').val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response);
            $.each(response.model, function(key, item){
                if (model.Id_sucursal == item.Id_cliente) {
                    tab += '<option value="'+item.Id_sucursal+'" selected>('+item.Id_sucursal+') '+item.Empresa+'</option>'
                } else {
                    tab += '<option value="'+item.Id_sucursal+'">('+item.Id_sucursal+') '+item.Empresa+'</option>'
                }
                
            })
            sec.innerHTML = tab
            $.each(response.contacto, function (key, item) {
                if (model.Id_contacto == item.Id_contacto) {
                    console.log("Id Contacto"+model.Id_contacto);
                    tab2 += '<option value="' + item.Id_contacto + '" selected>' + item.Nombres + '</option>';
                } else {
                    tab2 += '<option value="' + item.Id_contacto + '">' + item.Nombres + '</option>';
                }
            });
            sec2.innerHTML = tab2
        }
    });
}
function getDireccionReporte()
{
    let sec = document.getElementById("direccionReporte")
    let tab  = '';
    let siralab = document.getElementById('siralab');
    $.ajax({
        url: base_url + '/admin/cotizacion/solicitud/getDireccionReporte', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            id: $('#sucursal').val(),
            siralab:siralab.checked,
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response);
            $.each(response.model, function(key, item){
                if (siralab.checked == true) {
                    // if (model.Direccion == item.Direccion) {
                    //     tab += '<option value="'+item.Id_direccion+'" selected>'+item.Calle+' '+item.Num_exterior+' '+item.Num_interior+' '+item.Colonia+' '+item.Ciudad+' '+item.Localidad+'</option>'
                    // } else {
                    //     tab += '<option value="'+item.Id_direccion+'">'+item.Calle+' '+item.Num_exterior+' '+item.Num_interior+' '+item.Colonia+' '+item.Ciudad+' '+item.Localidad+'</option>'
                    // }
                    tab += '<option value="'+item.Id_cliente_siralab+'">'+item.Calle+' '+item.Num_exterior+' '+item.Num_interior+' '+item.Colonia+' '+item.Ciudad+' '+item.Localidad+'</option>'
                } else {
                    tab += '<option value="'+item.Id_direccion+'">'+item.Direccion+'</option>'
                    // if (model.Direccion == item.Direccion) {
                    //     tab += '<option value="'+item.Id_direccion+'" selected>'+item.Direccion+'</option>'
                    // } else {
                    //     tab += '<option value="'+item.Id_direccion+'">'+item.Direccion+'</option>'
                    // }   
                }
                
            })
            sec.innerHTML = tab
        }
    });
}
function getDataContacto() {
    $.ajax({
        url: base_url + '/admin/cotizacion/solicitud/getDataContacto', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            id: $("#contacto").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response);
            $("#idCont").val(response.model.Id_contacto);
            $("#nombreCont").val(response.model.Nombres);
            $("#apellidoCont").val(response.model.A_paterno);
            $("#emailCont").val(response.model.Email);
            $("#celCont").val(response.model.Celular);
            $("#telCont").val(response.model.Telefono);
        }
    });
}
function setContacto() {
    let element = [
        inputText('Nombre', 'nombreContacto', 'nombreContacto', 'Nombre'),
        inputText('Apellido paterno', 'paternoContacto', 'paternoContacto', 'Paterno'),
        inputText('Apellido materno', 'maternoContacto', 'maternoContacto', 'Materno'),
        inputText('Celular', 'celularContacto', 'celularContacto', 'Celular'),
        inputText('Telefono', 'telefonoContacto', 'telefonoContacto', 'Telefono'),
        inputText('Correo', 'correoContacto', 'correoContacto', 'Correo'),
    ];
    itemModal[0] = element;
    newModal('divModal', 'setContacto', 'Crear contacto cliente', 'lg', 3, 2, 0, inputBtn('', '', 'Guardar', 'save', 'success', 'createContacto()'));
}
function editContacto()
{
    let element;
    $.ajax({
        url: base_url + '/admin/cotizacion/solicitud/getDataContacto', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            id: $("#contacto").val(),
            _token: $('input[name="_token"]').val(),
        }, 
        dataType: 'json',
        async: false,
        success: function (response) { 
            console.log(response);
            element = [
                inputText('Nombre', 'nombreContacto', 'nombreContacto', 'Nombre',response.model.Nombres),
                inputText('Apellido paterno', 'paternoContacto', 'paternoContacto', 'Paterno',response.model.A_paterno),
                inputText('Apellido materno', 'maternoContacto', 'maternoContacto', 'Materno',response.model.A_materno),
                inputText('Celular', 'celularContacto', 'celularContacto', 'Celular',response.model.Celular),
                inputText('Telefono', 'telefonoContacto', 'telefonoContacto', 'Telefono',response.model.Telefono),
                inputText('Correo', 'correoContacto', 'correoContacto', 'Correo',response.model.Email),
            ];
            itemModal[1] = element;
            newModal('divModal', 'editContacto', 'Editar contacto cliente', 'lg', 3, 2, 1, inputBtn('', '', 'Guardar', 'save', 'success', 'storeContacto('+response.model.Id_contacto+','+response.model.Id_cliente+')'));
        }
    });

}
function createContacto() {
    let contacto = document.getElementById('contacto');
    let tab = '';
    $.ajax({
        url: base_url + '/admin/cotizacion/solicitud/setContacto', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            id: $("#clientes").val(),
            nombre: $("#nombreContacto").val(),
            paterno: $("#paternoContacto").val(),
            materno: $("#maternoContacto").val(),
            celular: $("#celularContacto").val(),
            telefono: $("#telefonoContacto").val(),
            correo: $("#correoContacto").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response);
            tab += '<option value="0">Sin seleccionar</option>';
            $.each(response.model, function (key, item) {
                tab += '<option value="' + item.Id_contacto + '">' + item.Nombres + '</option>';
            });
            contacto.innerHTML = tab;
            swal("Registro!", "Registro guardado correctamente!", "success");
            $('#setContacto').modal('hide')
        }
    });
}
function storeContacto(idContacto,idCliente)
{
    let tab = '';
    $.ajax({
        url: base_url + '/admin/cotizacion/solicitud/storeContacto', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            idCliente: idCliente,
            idContacto:idContacto,
            nombre: $("#nombreContacto").val(),
            paterno: $("#paternoContacto").val(),
            materno: $("#maternoContacto").val(),
            celular: $("#celularContacto").val(),
            telefono: $("#telefonoContacto").val(),
            correo: $("#correoContacto").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response);
            tab += '<option value="0">Sin seleccionar</option>';
            $.each(response.model, function (key, item) {
                tab += '<option value="' + item.Id_contacto + '">' + item.Nombres + '</option>';
            });
            contacto.innerHTML = tab;
            swal("Registro!", "Registro guardado correctamente!", "success");
            $('#setContacto').modal('hide')
        }
    });
}
function getSubNormas() {
    let sub = document.getElementById('subnorma');
    let tab = '';
    $.ajax({
      url: base_url + '/admin/cotizacion/getSubNormas', //archivo que recibe la peticion
      type: 'POST', //método de envio
      data: {
        id: $('#norma').val(),
        _token: $('input[name="_token"]').val(),
      },
      dataType: 'json',
      async: false,
      success: function (response) {
        tab += '<option value="0">Sin seleccionar</option>';
        $.each(response.model, function (key, item) {
          if (model.Id_subnorma == item.Id_subnorma) {
            tab += '<option value="' + item.Id_subnorma + '" selected>' + item.Clave + '</option>';
          } else {
            tab += '<option value="' + item.Id_subnorma + '">' + item.Clave + '</option>'; 
          }
        });
        sub.innerHTML = tab;
      }
    });
  }
  function getPuntoMuestro() 
{
    let punto = new Array();
    let puntoId = new Array();
    let tab = '';
    let siralab = document.getElementById('siralab');
    $.ajax({
        url: base_url + '/admin/cotizacion/solicitud/getPuntoMuestro', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          siralab:siralab.checked,
          idSuc:$("#sucursal").val(),
          _token: $('input[name="_token"]').val(),
        },
        dataType: 'json', 
        async: false,
        success: function (response) {
          console.log(response);
          $.each(response.model,function(key,item){
            if (siralab.checked == true) {
                punto.push(item.Punto +" "+item.Anexo +" " +item.Agua);
                puntoId.push(item.Id_punto);
            } else {
                punto.push(item.Punto_muestreo);
                puntoId.push(item.Id_punto);   
            }
          });
        }
      });
    let element = [
        inputSelect('puntoMuestreo','','Punto de muestreo',punto,puntoId),
    ];
    itemModal[1] = element; 
    newModal('divModal', 'setPuntoMuestro', 'Agregar punto muestreo', 'lg', 1, 1, 1, inputBtn('btnAddPunto','btnAddPunto', 'Guardar', 'save', 'success', 'btnAddPunto()'));    
}
function btnAddPunto()
{
    var t = $('#puntoMuestro').DataTable();
    //   counterPunto = 0;

          t.row.add( [
            $("#puntoMuestreo").val(),
            $('select[id="puntoMuestreo"] option:selected').text(),
          ] ).draw( false );
}
function createTabParametros()
{
  let table = document.getElementById("tableParametros")
  let tab = '';
  let cont = 1;
  $("#normaPa").val($("#norma option:selected").text())
  $.each(parametros, function (key, item) {
    if ($("#idCot").val() != "") {
      if (item.Extra == 1) {
        tab += '<tr class="bg-danger">'
      } else {
        tab += '<tr>'
      }  
    } else {
    tab += '<tr>' 
    }
    temp = ''
    if(item.Reporte == 1){
        temp = "checked"
      }
      tab += '<td><input type="checkbox" '+temp+'></td>';
    tab += '<td>'+cont+'</td>';
    tab += '<td>'+item.Id_subnorma+'</td>';
    tab += '<td>'+item.Parametro+'('+item.Tipo_formula+')</td>';
    tab += '</tr>'
    cont++
  });  
  table.innerHTML = tab 
}
function setSolicitud()
{
    console.log("entro a setSolicitud")
    let tab = document.getElementById("puntoMuestro")
    let tab2 = document.getElementById("tableParametros")
    let puntos = new Array()
    let parametro = new Array()
    let chParam = new Array() 
    for (let i = 1; i < tab.rows.length; i++) {
        puntos.push(tab.rows[i].children[0].textContent)
    }
    for (let i = 1; i < tab2.rows.length; i++) {
        parametro.push(tab2.rows[i].children[2].textContent)
        chParam.push(tab2.rows[i].children[0].children[0].checked)
    }
    console.log(puntos)
    console.log(parametro)
    $.ajax({
        url: base_url + '/admin/cotizacion/solicitud/setSolicitud', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          id:$("#idCot").val(),
          inter:$("#intermediario").val(),
          clientes:$("#clientes").val(),
          sucursal:$("#sucursal").val(),
          direccionReporte:$("#direccionReporte").val(),
          siralab:$("#siralab").prop('checked'),
          contacto:$("#contacto").val(),
          atencion:$("#atencion").val(),
          observacion:$("#observacion").val(),
          tipoServicio:$("#tipoServicio").val(),    
          tipoDescarga:$("#tipoDescarga").val(),
          norma:$("#norma").val(),
          subnorma:$("#subnorma").val(),
          fechaMuestreo:$("#fechaMuestreo").val(),
          frecuencia:$("#frecuencia").val(),
          numTomas:$("#numTomas").val(),
          tipoMuestra:$("#tipoMuestra").val(),
          promedio:$("#promedio").val(),
          tipoReporte:$("#tipoReporte").val(),
          puntos:puntos,
          parametros:parametro,
          chParam,chParam,
          _token: $('input[name="_token"]').val(),
        },
        dataType: 'json', 
        async: false,
        success: function (response) {
            console.log("Resiviendo repuesta");
          console.log(response);
          alert("Orden generada")
          window.location = base_url + "/admin/cotizacion/solicitud"
        }
      });
}