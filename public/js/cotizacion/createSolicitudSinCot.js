var base_url = 'https://dev.sistemaacama.com.mx';
var model; 
var swSol = $("#sw").val();;
$(document).ready(function () {      
    $("#contacto").click(function(){
        console.log("Click en contacto");

        var value = $("#emailCont").val();
        $("#emailCont2").val(value);

        var value = $("#telCont").val();
        $("#telCont2").val(value);
    });    

    getDatoIntermediario()

    if($("#sw").val() == true)
    {
        update();
    }
    $('#datos-tab').click();
    $('#tipoDescarga').click(function () {
        dataNorma();
    });
    $('#norma').click(function () {
        dataSubnorma();
    });

    $('#cotizacion-tab').click(function () {
        getDatos2();
        getPuntoMuestro();
    });

    /* $('#clientes').click(function () {
        getSucursal();
    }); */

    $('#parametro-tab').click(function () {
        getDatos1();
        tablaParametros();
        // if (swParametros == 0) {
        //     tablaParametros();
        // }
    });

    $('#estado').click(function () {
        getLocalidad(); 
    });
    
    addColPunto();        

    $('#frecuencia').click(function () {
        dataTomas();
    });
});
function update()
{
    console.log("Actualizacion sw"+swSol);
    getDataSolicitud();
    getDatoIntermediario();
    getSucursal();
    getDireccionReporte();
    getDataContacto();
    dataNorma();
    dataSubnorma();
}
function getDataSolicitud()
{
    $.ajax({
        url: base_url + '/admin/cotizacion/solicitud/getDataSolicitud', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            idSol: $('#idSol').val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response);
            model = response.model;
        }
    });
}
// Get datos intermedario
function getDatoIntermediario() {
    $.ajax({
        url: base_url + '/admin/cotizacion/solicitud/getDatoIntermediario', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            idCliente: $('#intermediario').val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response);
            $("#nombreInter").val(response.intermediario.Nombres + "" + response.intermediario.A_paterno);
            $("#celularInter").val(response.intermediario.Celular1);
            $("#telefonoInter").val(response.intermediario.Tel_oficina);
        }
    });
}
//Get Sucursal cliente
function getSucursal() {
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
            console.log("Dentro de getSucursal");
            
            console.log(response)
            // console.log("Id Contacto"+model.Id_contacto);
            $.each(response.sucursal, function (key, item) {
                if (swSol == true) {
                    if (model.Id_sucursal == item.Id_sucursal) {
                        tab += '<option value="' + item.Id_sucursal + '" selected>' + item.Empresa + '</option>';
                    } else {
                        tab += '<option value="' + item.Id_sucursal + '">' + item.Empresa + '</option>';
                    }
                } else {
                    tab += '<option value="' + item.Id_sucursal + '">' + item.Empresa + '</option>';   
                }
            });
            tab2 += '<option value="0">Sin seleccionar</option>';
            $.each(response.contacto, function (key, item) {
                if (swSol == true) {
                    if (model.Id_contacto == item.Id_contacto) {
                        console.log("Id Contacto"+model.Id_contacto);
                        tab2 += '<option value="' + item.Id_contacto + '" selected>' + item.Nombres + '</option>';
                    } else {
                        tab2 += '<option value="' + item.Id_contacto + '">' + item.Nombres + '</option>';
                    }
                } else {
                    tab2 += '<option value="' + item.Id_contacto + '">' + item.Nombres + '</option>';
                }
            });
            sucursal.innerHTML = tab;
            contacto.innerHTML = tab2;
        }
    });
}
function getDireccionReporte() {
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
            $.each(response.direccion, function (key, item) {
                if (swSol == true) {
                    if (model.Id_reporte == item.Id_direccion) {
                        tab += '<option value="' + item.Id_direccion + '" selected>' + item.Direccion + '</option>';
                    } else {
                        tab += '<option value="' + item.Id_direccion + '">' + item.Direccion + '</option>';
                    }
                } else {
                    tab += '<option value="' + item.Id_direccion + '">' + item.Direccion + '</option>';
                }
               
            });
            direccion.innerHTML = tab;
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
            idContacto: $("#contacto").val(),
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
            idCliente: $("#clientes").val(),
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
function getDataContacto() {
    $.ajax({
        url: base_url + '/admin/cotizacion/solicitud/getDataContacto', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            idContacto: $("#contacto").val(),
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
function dataNorma() {
    let sub = document.getElementById('norma');
    let tab = '';
    $.ajax({
        url: base_url + '/admin/cotizacion/getNorma', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            idDescarga: $('#tipoDescarga').val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response)
            model = response;
            $.each(response.model, function (key, item) {
                tab += '<option value="' + item.Id_norma + '">' + item.Clave_norma + '</option>';
            });
            sub.innerHTML = tab;
        }
    });

}
function dataSubnorma() {
    let sub = document.getElementById('subnorma');
    let tab = '';

    if($('#idCotizacion').val() !== null){
        
        $.ajax({
            url: base_url + '/admin/cotizacion/getSubNorma', //archivo que recibe la peticion
            type: 'POST', //método de envio
            data: {
                idCotizacion: $('#idCotizacion').val(),
                norma: $('#norma').val(),
                _token: $('input[name="_token"]').val(),
            },
            dataType: 'json',
            async: false,
            success: function (response) {
                console.log(response)
                model = response;
                $.each(response.model, function (key, item) {
                    if (swSol == true) {
                        if (model.Id_subnorma == item.Id_paquete) {
                            tab += '<option value="' + item.Id_paquete + '">' + item.Clave + '</option>';
                        } else {
                            tab += '<option value="' + item.Id_paquete + '">' + item.Clave + '</option>';
                        }
                    } else {
                        tab += '<option value="' + item.Id_paquete + '">' + item.Clave + '</option>';
                    }

                });
                sub.innerHTML = tab;
            }
        });

    }else{

        $.ajax({
            url: base_url + '/admin/cotizacion/getSubNorma', //archivo que recibe la peticion
            type: 'POST', //método de envio
            data: {
                norma: $('#norma').val(),
                _token: $('input[name="_token"]').val(),
            },
            dataType: 'json',
            async: false,
            success: function (response) {
                console.log(response)
                model = response;
                $.each(response.model, function (key, item) {
                    if (swSol == true) {
                        if (model.Id_subnorma == item.Id_paquete) {
                            tab += '<option value="' + item.Id_paquete + '">' + item.Clave + '</option>';
                        } else {
                            tab += '<option value="' + item.Id_paquete + '">' + item.Clave + '</option>';
                        }
                    } else {
                        tab += '<option value="' + item.Id_paquete + '">' + item.Clave + '</option>';
                    }

                });
                sub.innerHTML = tab;
            }
        });

    }
}
function getDatos1()
{
    let subnorma = document.getElementById('subnorma')
    $.ajax({
      url: base_url + '/admin/cotizacion/getSubNormaId', //archivo que recibe la peticion
      type: 'POST', //método de envio
      data: {
        idSub: $('#subnorma').val(),
          _token: $('input[name="_token"]').val(),
      },
      dataType: 'json',
      async: false,
      success: function (response) {
          console.log(response) 
          $('#normaPa').val(response.model.Clave);
      }
  });
}
var normaParametro = new Array();
function tablaParametros()
{
  let table = document.getElementById('tabParametros');
  let idSub = document.getElementById('subnorma');

  let tab = '';
  $.ajax({
      url: base_url + '/admin/analisisQ/detalle_normas/getParametro', //archivo que recibe la peticion
      type: 'POST', //método de envio
      data: {
        idSub:idSub.value,
        _token: $('input[name="_token"]').val(),
      },
      dataType: 'json', 
      async: false, 
      success: function (response) {
        console.log(response.model)
        normaParametro = new Array();
        tab += '<div class="row justify-content-end">' + inputBtn('', '', 'Agregar', 'voyager-list-add', 'success','agregarParametros('+idSub.value+')' , 'botton') + '</div><br>';
        tab += '<table id="tablaParametro" class="table table-sm  table-striped table-bordered">';
        tab += '    <thead class="thead-dark">';
        tab += '        <tr>';
        tab += '            <th style="width: 5%;">Id</th>';
        tab += '            <th style="width: 30%;">Parametro</th>';
        tab += '            <th>Matriz</th>';
        tab += '        </tr>'; 
        tab += '    </thead>';
        tab += '    <tbody>';
        $.each(response.model, function (key, item) {
          normaParametro.push(item.Id_parametro);
            tab += '<tr>';
          tab += '<td>'+item.Id_parametro+'</td>';
          tab += '<td>'+item.Parametro+'<sup> ('+item.Simbologia+')</sup></td>';
          tab += '<td>'+item.Matriz+'</td>';
          tab += '</tr>';
        });
        tab += '    </tbody>';
        tab += '</table>';
        table.innerHTML = tab;

      }
  });
  $("#parametrosSolicitud").val(normaParametro);
}
var parametro = new Array();
var parametroId = new Array();
var matriz = new Array();
var simbologia = new Array();
function agregarParametros(idSub)
{
  swParametros = 1;
  let idNorma = document.getElementById('norma').value;
  parametro = new Array();
  parametroId = new Array();
  matriz = new Array();
  let normaId = new Array(); //Alacena parametros agregados de la sub-norma
  $.ajax({
    url: base_url + '/admin/analisisQ/detalle_normas/getParametroNorma', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      idSub:idSub,
      idNorma:idNorma,
      _token: $('input[name="_token"]').val(),
    },
    dataType: 'json', 
    async: false,
    success: function (response) {
      console.log(response);
      $.each(response.sqlParametro,function(key,item){
        parametro.push("Pa: "+item.Parametro + '/ Mat:' + item.Matriz);
        parametroId.push(item.Id_parametro);
        matriz.push(item.Matriz);
        simbologia.push(item.Simbologia);
      });
      // $.each(normaParametro,function(key,item){
      //   normaId.push(item.Id_parametro);
      // });
    }
  });
  let element = [
    inputMultiple('parametros','','Lista de parametros',parametro,parametroId,'Parametros',normaParametro,'duallistbox'),
  ];
  itemModal[0] = element;
  newModal('divModal','listaParametros','Asignar parametros','lg',1,1,0,inputBtn('','','Guardar','save','success','updateNormaParametro()'));
  $('.duallistbox').bootstrapDualListbox({
    nonSelectedListLabel: 'No seleccionado',
    selectedListLabel: 'Seleccionado',
    preserveSelectionOnMove: 'Mover',
    moveOnSelect: true,
    infoText:'Mostrar todo {0}',
    filterPlaceHolder:'Filtro'
  });
}

function updateNormaParametro()
{
  let table = document.getElementById('tabParametros');
  let idSub = document.getElementById('subnorma');

  let tab = '';
  let param = document.getElementById('parametros');
  normaParametro = new Array();

  tab += '<div class="row justify-content-end">' + inputBtn('', '', 'Agregar', 'voyager-list-add', 'success','agregarParametros('+idSub.value+')' , 'botton') + '</div><br>';
  tab += '<table id="tablaParametro" class="table table-sm  table-striped table-bordered">';
  tab += '    <thead class="thead-dark">';
  tab += '        <tr>';
  tab += '            <th style="width: 5%;">Id</th>';
  tab += '            <th style="width: 30%;">Parametro</th>';
  tab += '            <th>Matriz</th>';
  tab += '        </tr>'; 
  tab += '    </thead>';
  tab += '    <tbody>';
  for (let i = 0; i < param.length; i++) {
    if(param[i].selected == true)
    {
      normaParametro.push(param[i].value);
      tab += '<tr>';
      tab += '<td>'+parametroId[i]+'</td>';
      tab += '<td>'+parametro[i]+'<sup> ('+simbologia[i]+')</sup></td>';
      tab += '<td>'+matriz[i]+'</td>';
      tab += '</tr>';
    }
  }
  tab += '    </tbody>';
  tab += '</table>';
  table.innerHTML = tab;

  swUpdateParam = 1;
  $('#listaParametros').modal('hide');
  $("#parametrosSolicitud").val(normaParametro);
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
          if(siralab.checked == false)
          {
            $.each(response.model,function(key,item){
                punto.push(item.Punto_muestreo);
                puntoId.push(item.Id_punto);
              });
          }else{
            $.each(response.model,function(key,item){
                tab = item.Punto + ' '+ item.Anexo+ ' '+ item.Cuerpo_receptor; 
                punto.push(tab);
                puntoId.push(item.Id_punto);
                tab = '';
              });
          }
        }
      });
    let element = [
        inputSelect('puntoMuestreo','','Punto de muestreo',punto,puntoId),
    ];
    itemModal[1] = element;
    //newModal('divModal', 'setPuntoMuestro', 'Agregar punto muestreo', 'lg', 1, 1, 1, inputBtn('btnAddPunto','btnAddPunto', 'Guardar', 'save', 'success', 'btnAddPunto()'));    
}
var puntos = new Array();
function setPuntoMuestro()
{
    puntos = new Array();
    let puntosMuestreo = document.getElementById('puntoMuestro');
    for (let i = 1; i < puntosMuestreo.rows.length; i++) {
        puntos.push(puntosMuestreo.rows[i].cells[0].textContent);
    }
    $("#puntosSolicitud").val(puntos);
    $("#puntosSolicitud2").val(puntos);
}
function btnAddPunto()
{
    var t = $('#puntoMuestro').DataTable();
    //   counterPunto = 0;

          t.row.add( [
            $("#puntoMuestreo").val(),
            $("#puntoMuestreo").text(),
          ] ).draw( false );

          setPuntoMuestro();
}
/* function addColPunto()
{
  var t = $('#puntoMuestro').DataTable();
//   counterPunto = 0;

//   $('#btnAddPunto').on( 'click', function () {
//       t.row.add( [
//         // counterPunto + 1,
//         $("#puntoMuestreo").val(),
//       ] ).draw( false );

//     //   counterPunto++;
//   } );
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

} */

var counterPunto = 0;
function addColPunto()
{
  var t = $('#puntoMuestro').DataTable();
  counterPunto = 0;
  if(swSol == true)
  {
    counterPunto = parseInt($("#contPunto").val());
  }
  $('#addRow').on( 'click', function () {
      t.row.add( [
        counterPunto + 1,
        inputText('Punto de muestreo','punto'+counterPunto,'punto','',''),
      ] ).draw( false );

      counterPunto++;

      setPuntoMuestro();
  } );
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
    } );

}

function getDireccionReporteSir()
{   
    let siralab = document.getElementById("siralab");
    let direccion = document.getElementById('direccionReporte');
    let tab = '';
    $.ajax({
        url: base_url + '/admin/cotizacion/solicitud/getReporteSir', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            siralab: siralab.checked,
            idSucursal: $('#sucursal').val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response);
            tab += '<option value="0">Sin seleccionar</option>';
            $.each(response.direccion, function (key, item) {
                if(siralab.checked == false)
                {
                    if (swSol == true) {
                        if (model.Id_reporte == item.Id_direccion) {
                            tab += '<option value="' + item.Id_direccion + '" selected>' + item.Direccion + '</option>';
                        } else {
                            tab += '<option value="' + item.Id_direccion + '">' + item.Direccion + '</option>';
                        }
                    } else {
                        tab += '<option value="' + item.Id_direccion + '">' + item.Direccion + '</option>';
                    }
                }else if(siralab.checked == true){
                    if (swSol == true) {
                        if (model.Id_reporte == item.Id_cliente_siralab) {
                            tab += '<option value="' + item.Id_cliente_siralab + '">' + item.Calle + ' '+item.Num_exterior+' '+item.Num_interior+' '+item.Colonia+' '+item.CP+' '+item.Ciudad+' '+item.Localidad+' '+item.Estado+'</option>';
                        } else {
                            tab += '<option value="' + item.Id_cliente_siralab + '">' + item.Calle + ' '+item.Num_exterior+' '+item.Num_interior+' '+item.Colonia+' '+item.CP+' '+item.Ciudad+' '+item.Localidad+' '+item.Estado+'</option>';
                        }
                    } else {
                        tab += '<option value="' + item.Id_cliente_siralab + '">' + item.Calle + ' '+item.Num_exterior+' '+item.Num_interior+' '+item.Colonia+' '+item.CP+' '+item.Ciudad+' '+item.Localidad+' '+item.Estado+'</option>';
                    }
                }
            
              
               
            });
            direccion.innerHTML = tab;
        }
    });
}

var precioTotal = 0;
var swUpdateParam = 0;
function getDatos2()
{
  let suma = 0;
  let iva = 0;
  let sumaTotal = 0;
    $.ajax({
        url: base_url + '/admin/cotizacion/getDatos2', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            intermediario: $('#intermediario').val(),
            idSub:$('#subnorma').val(),
            idParametros:normaParametro,
            idServicio:$('#tipoServicio').val(),
            idDescarga:$('#tipoDescarga').val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response)
            $('#textInter').val(response.intermediarios.Nombres +' '+response.intermediarios.A_paterno);
            //$('#textEstado').val("Cotización");
            $('#textServicio').val(response.servicio.Servicio);
            $('#textDescarga').val(response.descarga.Descarga);

            //Obtiene el texto del select de Contacto cliente
            let cliente = document.getElementById("clientes");
            let selected = cliente.options[cliente.selectedIndex].text;
            $('#textCliente').val(selected);

            $('#textAtencion').val($('#atencion').val());
            
            //Obtiene el texto del select de Contacto cliente
            let direccion = document.getElementById("direccionReporte");
            let selectedDir = direccion.options[direccion.selectedIndex].text;
            $('#textDireccion').val(selectedDir);            
            
            $('#textTelefono').val($('#telCont2').val());
            $('#textEmail').val($('#emailCont2').val());

            $('#textNorma').val(response.subnorma.Clave);
            
            let tipoMuestra = document.getElementById("tipoMuestra");
            let selectedMuestra = tipoMuestra.options[tipoMuestra.selectedIndex].text;

            $('#textMuestreo').val(selectedMuestra);
            $('#textTomas').val($('#numTomas').val());
            $('#tomasMuestreo').val($('#numTomas').val());
            $('#fechaMuestreoCot').val($('#fechaMuestreo').val());            

            if($("#tipoServicio").val() == 1 || $("#tipoServicio").val() == 2)
            {
              $("#divMuestreo").css("display", "block");
              $("#divMuestreo2").css("display", "block");
            }else{
              $("#divMuestreo").css("display", "none");
              $("#divMuestreo2").css("display", "none");
            }

            getDataParametros();
            getDataMuestreo();

            $("#parametrosSolicitud").val(normaParametro);
            $("#puntosSolicitud").val(puntosMuestro);
            precioAnalisis = response.precioTotal;
            if(swSol != true)
            {
              $("#precioAnalisis").val(precioAnalisis.toFixed());
              suma = parseInt(precioAnalisis.toFixed()) + parseInt(totalMuestreo.toFixed());
              iva = (suma * 16) / 100;
              sumaTotal = (suma + iva);
              $('#subTotal').val(suma);
              $('#precioTotal').val(sumaTotal.toFixed());
            }
            if(swUpdateParam == 1)
            {
              $("#precioAnalisis").val(precioAnalisis.toFixed());
              suma = parseInt(precioAnalisis.toFixed()) + parseInt(totalMuestreo.toFixed());
              iva = (suma * 16) / 100;
              sumaTotal = (suma + iva);
              $('#subTotal').val(suma);
              $('#precioTotal').val(sumaTotal.toFixed());
              $('#descuento').val(0);
            }

        }
    });    
}

var puntosMuestro = new Array();
function getDataMuestreo()
{
  let puntos = document.getElementById('puntoMuestro');
  let table = document.getElementById('puntoMuestreo3');
  let tab = '';
  puntosMuestro = new Array();
  
  tab += '<table id="tablaPuntoMuestreo3" class="table table-sm  table-striped table-bordered">';
  tab += '    <thead class="thead-dark">';
  tab += '        <tr>';
  tab += '            <th style="width: 5%;">#</th>';
  tab += '            <th style="width: 30%;">Descripción</th>';
  tab += '        </tr>'; 
  tab += '    </thead>';
  tab += '    <tbody>';

  for (let i = 1; i < puntos.rows.length; i++) {
    if(swSol == true)//Se actualiza
    {      
        puntosMuestro.push($("#"+puntos.rows[i].cells[1].children[0].id).val());
        console.log("Arreglo en if: " + puntosMuestro);

        tab += '<tr>';
        tab += '<td>'+i+'</td>';
        tab += '<td>'+$("#"+puntos.row+s[i].cells[1].children[0].id).val()+'</td>';
        tab += '</tr>';
    }else{//Se crea por 1era vez
        puntosMuestro.push($("#"+puntos.rows[i].cells[1].children[1].id).val());
        console.log("Arreglo en else: " + puntosMuestro);

        tab += '<tr>';
        tab += '<td>'+i+'</td>';
        tab += '<td>'+$("#"+puntos.rows[i].cells[1].children[1].id).val()+'</td>';
        tab += '</tr>';
    }
  }
  tab += '    </tbody>';
  tab += '</table>';
  table.innerHTML = tab;
}
function getDataParametros()
{
  let table = document.getElementById('parametros3');

  let tab = '';
  let param = document.getElementById('tablaParametro');
  normaParametro = new Array();

  tab += '<table id="tablaParametros" class="table table-sm  table-striped table-bordered">';
  tab += '    <thead class="thead-dark">';
  tab += '        <tr>';
  tab += '            <th style="width: 5%;">Id</th>';
  tab += '            <th style="width: 30%;">Parametro</th>';
  tab += '            <th>Matriz</th>';
  tab += '        </tr>'; 
  tab += '    </thead>';
  tab += '    <tbody>';
  for (let i = 1; i < param.rows.length; i++) {
      normaParametro.push(param.rows[i].cells[0].textContent);
      tab += '<tr>';
      tab += '<td>'+param.rows[i].cells[0].textContent+'</td>';
      tab += '<td>'+param.rows[i].cells[1].textContent+'</sup></td>';
      tab += '<td>'+param.rows[i].cells[2].textContent+'</td>';
      tab += '</tr>';
  }
  tab += '    </tbody>';
  tab += '</table>';
  table.innerHTML = tab;

  $('#listaParametros').modal('hide');
}

function getLocalidad()
{
    let sub = document.getElementById('localidad');
    let tab = '';
    $.ajax({
        url: base_url + '/admin/cotizacion/getLocalidad', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          idLocalidad: $('#estado').val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            // console.log(response)
            // model = response;
            $.each(response.model, function (key, item) {
              if(sw == 1)
              {
                // if(modelCot.Id_norma == item.Id_norma)
                // {
                //   tab += '<option value="'+item.Id_norma+'" selected>'+item.Clave_norma+'</option>';
                // }else{
                //   tab += '<option value="'+item.Id_norma+'">'+item.Clave_norma+'</option>';
                // } 
              }else{
                tab += '<option value="'+item.Id_localidad+'">'+item.Nombre+'</option>';
              }
            });
            sub.innerHTML = tab;
        }
    });
    if(sw == true)
    {
      dataSubnorma();
    }
}

function cantGasolinaTeorico() 
{
  let km = document.getElementById('km')
  // console.log(km.value);
 if(km.value != '' && $('#kmExtra').val() != '')
 {
   $.ajax({
    url: base_url + '/admin/cotizacion/cantidadGasolina', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      kmExtra: $('#kmExtra').val(),
      km: $('#km').val(),
        _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
        console.log(response)
        $("#gasolinaTeorico").val(response.total);
    }
  });
 }
}

var totalMuestreo = 0;
function precioCampo()
{

  let suma = 0;
  let sumatotal = 0;
  let iva = 0;
  $.ajax({
    url: base_url + '/admin/cotizacion/precioMuestreo', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      tomasMuestreo: $('#tomasMuestreo').val(),
      diasHospedaje: $('#diasHospedaje').val(),
      hospedaje: $('#hospedaje').val(),
      diasMuestreo: $('#diasMuestreo').val(),
      numeroMuestreo: $('#numeroMuestreo').val(),
      caseta: $('#caseta').val(),
      kmExtra: $('#kmExtra').val(),
      km: $('#km').val(),
      cantidadGasolina: $('#cantidadGasolina').val(),
      paqueteria: $('#paqueteria').val(),
      gastosExtras: $('#gastosExtras').val(),
      numeroServicio: $('#numeroServicio').val(),
      numMuestreador: $('#numMuestreador').val(),
      
        _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
        console.log(response)
        totalMuestreo = response.total;

        $("#totalMuestreo").val(totalMuestreo.toFixed());
        $("#precioMuestra").val(totalMuestreo.toFixed());
        suma = parseInt(precioAnalisis.toFixed()) + parseInt(totalMuestreo.toFixed());
        iva = (suma * 16) / 100;
        $('#subTotal').val(suma);
        sumatotal = suma + iva;
        $('#precioTotal').val(suma.toFixed());
    }
});
}

var swDescuento = 0;
function btnDescuento()
{
  if(swDescuento != 0)
  {
    $("#activarDescuento").css("display", "none");
    swDescuento = 0;
  }else{
    $("#activarDescuento").css("display", "");
    $("#descuento").val(0);
    swDescuento = 1;
  }
}

var swAplicarDescuento = 0;
function aplicarTotal()
{
  if(swAplicarDescuento == 0)
  {
    // console.log("Funcion: aplicar total");
  let total = 0;
  let descuento =  0;
  let iva = 0;
  let sumaTotal = 0;
  let analisis = parseFloat($("#precioAnalisis").val());

  descuento = (analisis * parseFloat($("#descuento").val())) / 100;
  descuentoAnalisis = (analisis - descuento);
  total = parseFloat($("#precioMuestra").val()) + descuentoAnalisis;
  iva = (total * 16) / 100;
  sumaTotal = (total + iva);

  $("#precioAnalisis").val(descuentoAnalisis.toFixed());
  $("#subTotal").val(total.toFixed());
  $("#precioTotal").val(sumaTotal.toFixed());
  }else{
    alert("Solo se puede aplicar una vez el descuento");
  }
}

function dataTomas() {

    $.ajax({ 
        url: base_url + '/admin/cotizacion/getTomas', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            idFrecuencia: $('#frecuencia').val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response)
            $('#numTomas').val(response.Tomas);
            $('#numTomas2').val(response.Tomas);
        }
    });
  }