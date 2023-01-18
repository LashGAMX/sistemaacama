var swParametros = 0;
var sw = $("#sw").val();
$(document).ready(function () {

    $('#datos-tab').click();

    //Recuperacion de datos modificados
    if(sw == 1)
    {
      update();
      // precioCampo();
      if($('#descuento').val() != 0)
      {
        swDescuento = 1;
        $("#activarDescuento").css("display", "");
      }
      swParametros == 1;
      getLocalidad();
    }

    $('#parametro-tab').click(function () {
        getDatos1();
        if(swParametros == 0)
        {
          tablaParametros();
        }
    }); 

    $('#cotizacion-tab').click(function () {
        getDatos2();
    });

    $('#clientes').click(function () {
        dataCliente();
        
    });
    
    $('#tipoDescarga').click(function () {
      dataNorma(); 
    });

    $('#estado').click(function () {
      getLocalidad(); 
    });

    $('#norma').click(function () {
      dataSubnorma();
    });
    $('#frecuencia').click(function () {
      dataTomas();
    });

    $("#clientes").on("change", function(){
      clienteSucursal()
      // clienteSucursal();
    });
 

    $("#clienteSucursal").on("change", function(){
      console.log('btn brandon')
      //DatosClienteSucursal();
    });

    $('#tipoMuestra').click(function ()
    {
      if($('#tipoMuestra').val() == "INSTANTANEA")
      {
        $("#frecuencia option[value=6]").attr("selected",true);
        $('#tomas').val("1");
      } 
    });
    addColPunto(); 

});
function getClienteInter()
{

  let sub = document.getElementById('clientes');
  let tab = '';
  $.ajax({
      url: base_url + '/admin/cotizacion/getClienteInter', //archivo que recibe la peticion
      type: 'POST', //método de envio
      data: {
        intermediario: $('#intermediario').val(),
          _token: $('input[name="_token"]').val(),
      },
      dataType: 'json',
      async: false, 
      success: function (response) {
        console.log(response)
          $.each(response.model, function (key, item) {              
            tab += '<option value="'+item.Id_cliente+'">'+item.Empresa+'</option>'; 
            // if(sw == 1) 
            // {
            //   if (modelMu.Localidad == item.Id_localidad) {
            //     tab += '<option value="'+item.Id_localidad+'" selected>'+item.Nombre+'</option>';
            //   } else {
            //     tab += '<option value="'+item.Id_localidad+'">'+item.Nombre+'</option>'; 
            //   }
            // }else{ 
            //   tab += '<option value="'+item.Id_localidad+'">'+item.Nombre+'</option>';
            // }
          });
          sub.innerHTML = tab;
      }
  });
}
function cantGasolinaTeorico() 
{
  let km = document.getElementById('km')
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
        suma = parseInt(precioAnalisis.toFixed()) + parseInt(totalMuestreo.toFixed()) + parseInt(precioCatalogo.toFixed());
        iva = (suma * 16) / 100;
        $('#subTotal').val(suma);
        sumatotal = suma + iva;
        $('#precioTotal').val(sumatotal.toFixed());
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
            $.each(response.model, function (key, item) {              
              if(sw == 1) 
              {
                if (modelMu.Localidad == item.Id_localidad) {
                  tab += '<option value="'+item.Id_localidad+'" selected>'+item.Nombre+'</option>';
                } else {
                  tab += '<option value="'+item.Id_localidad+'">'+item.Nombre+'</option>'; 
                }
              }else{ 
                tab += '<option value="'+item.Id_localidad+'">'+item.Nombre+'</option>';
              }
            });
            sub.innerHTML = tab;
        }
    });
    if(sw == 1)
    {
      dataSubnorma();
    }
}
var modelCot;
var modelMu;
function update()
{
  $.ajax({
    url: base_url + '/admin/cotizacion/getCotizacionId', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      idCotizacion: $('#idCotizacion').val(), 
        _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) {
        modelCot = response.model;
        modelMu = response.modelMuestreo;
        clienteSucursal();
        $("#nombreCliente").val(modelCot.Nombre);
    }
});
dataNorma();
}
var norma;
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
var counterPunto = 0;
function addColPunto()
{
  var t = $('#puntoMuestro').DataTable();
  counterPunto = 0;
  if(sw == 1)
  {
    counterPunto = parseInt($("#contPunto").val());
  }
  $('#addRow').on( 'click', function () {
      t.row.add( [
        counterPunto + 1,
        inputText('Punto de muestreo','punto'+counterPunto,'punto','',''),
      ] ).draw( false );

      counterPunto++;
      $("#contPunto").val(counterPunto);
  } );
  $('#puntoMuestro tbody').on( 'click', 'tr', function () {
    if ( $(this).hasClass('selected') ) {
        $(this).removeClass('selected');
        counterPunto--;
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
var precioTotal = 0;
var swUpdateParam = 0;
var precioAnalisis = 0;
var precioCatalogo = 0;

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
            idFrecuencia: $('#frecuencia').val(),
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
            $('#textEstado').val("Cotización");
            $('#textServicio').val(response.servicio.Servicio);
            $('#textDescarga').val(response.descarga.Descarga);

            $('#textCliente').val($('#nombreCliente').val());
            $('#textAtencion').val($('#atencion').val());
            $('#textDireccion').val($('#direccion').val());
            $('#textTelefono').val($('#telefono').val());
            $('#textEmail').val($('#correo').val());

            $('#textNorma').val(response.subnorma.Clave);
            $('#textMuestreo').val($('#tipoMuestra').val());
            $('#textTomas').val($('#tomas').val());
            $('#tomasMuestreo').val($('#tomas').val());
            $('#fechaMuestreo').val($('#fecha').val());

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
 
            $("#parametrosCotizacion").val(normaParametro);
            $("#puntosCotizacion").val(puntosMuestro);
            precioAnalisis = parseInt(response.precioAnalisis) * counterPunto;
            precioCatalogo = parseInt(response.precioCat);
            if(sw != 1)
            {
              $("#precioAnalisis").val(precioAnalisis.toFixed());
              $("#precioCat").val(precioCatalogo.toFixed());
              suma = parseInt(precioAnalisis.toFixed()) + parseInt(totalMuestreo.toFixed()) + parseInt(precioCatalogo.toFixed());
              iva = (suma * 16) / 100;
              sumaTotal = (suma + iva);
              $('#subTotal').val(suma);
              $('#precioTotal').val(sumaTotal.toFixed());
            }
            if(swUpdateParam == 1)
            {
              console.log("sw = 0")
              $("#precioAnalisis").val(precioAnalisis.toFixed());
              $("#precioCat").val(precioCatalogo.toFixed());
              suma = parseInt(precioAnalisis.toFixed()) + parseInt(totalMuestreo.toFixed()) + parseInt(precioCatalogo.toFixed());
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
    if(sw == 1)
    {      
      puntosMuestro.push($("#"+puntos.rows[i].cells[1].children[0].id).val());
      tab += '<tr>';
      tab += '<td>'+i+'</td>';
      tab += '<td>'+$("#"+puntos.rows[i].cells[1].children[0].id).val()+'</td>';
      tab += '</tr>';
    }else{
      puntosMuestro.push($("#"+puntos.rows[i].cells[1].children[1].id).val());
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
function clienteSucursal(){
  let div = document.getElementById("divClienteSucursal");
  let tab = "";

  $.ajax({
    url: base_url + '/admin/cotizacion/clienteSucursal', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      idCliente: $('#clientes').val(),
        _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) { 
        console.log(response)
        tab+= ' <label for="clienteSucursal">Clientes Sucursal (Hijos) </label> ';
        tab+= '<select onchange="DatosClienteSucursal()" class="form-control" name="clienteSucursal" id="clienteSucursal">';
        
            tab+= '<option value="">Selecciona cliente</option>';
            $.each(response.model, function (key, item) {
                if (sw != 1) {
                  tab+= '<option value="'+item.Id_sucursal+'">'+item.Empresa+'</option>';
                } else {
                  if (item.Id_sucursal == modelCot.Id_sucursal) {
                    tab+= '<option value="'+item.Id_sucursal+'" selected>'+item.Empresa+'</option>';
                  } else {
                    tab+= '<option value="'+item.Id_sucursal+'">'+item.Empresa+'</option>'; 
                  }
                }
            });
            tab+= '</select>';
            div.innerHTML = tab;
    }
});
}

function DatosClienteSucursal(){

  $.ajax({
    url: base_url + '/admin/cotizacion/DatosClienteSucursal', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
        idSucursal: $('#clienteSucursal').val(),
        _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    async: false,
    success: function (response) { 
        console.log(response)
            $("#nombreCliente").val(response.info.Empresa);
            $("#direccion").val(response.direccion.Direccion);
            $("#atencion").val(response.info.Atencion);
            $("#telefono").val(response.info.Telefono);
            $("#correo").val(response.info.Correo);
    }
});
}

function dataCliente() {
    $.ajax({
        url: base_url + '/admin/cotizacion/getCliente', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          cliente: $('#clientes').val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) { 
            console.log(response)
            $('#nombreCliente').val(response.Empresa);
        }
    });
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
          $('#tomas').val(response.Tomas);
          $('#tomas2').val(response.Tomas);
      }
  });
}
var model;
function dataSubnorma()
{
    let sub = document.getElementById('subnorma');
    let tab = '';
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
              if(sw == 1)
              {
                if(modelCot.Id_subnorma == item.Id_paquete)
                {
                  tab += '<option value="'+item.Id_paquete+'" selected>'+item.Clave+'</option>';
                }else{
                  tab += '<option value="'+item.Id_paquete+'">'+item.Clave+'</option>';
                }
              }else{
                  tab += '<option value="'+item.Id_paquete+'">'+item.Clave+'</option>';
              }
            
            });
            sub.innerHTML = tab;
        }
    });
}
function dataNorma()
{
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
              if(sw == 1)
              {
                if(modelCot.Id_norma == item.Id_norma)
                {
                  tab += '<option value="'+item.Id_norma+'" selected>'+item.Clave_norma+'</option>';
                }else{
                  tab += '<option value="'+item.Id_norma+'">'+item.Clave_norma+'</option>';
                } 
              }else{
                tab += '<option value="'+item.Id_norma+'">'+item.Clave_norma+'</option>';
              }
            });
            sub.innerHTML = tab;
        }
    });
    if(sw == 1)
    {
      dataSubnorma();
    }
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
}
function tablaParametrosCot()
{
  let table = document.getElementById('tabParametros');
  let idSub = document.getElementById('subnorma');

  let tab = '';
  $.ajax({
      url: base_url + '/admin/analisisQ/detalle_normas/getParametroCot', //archivo que recibe la peticion
      type: 'POST', //método de envio
      data: {
        idCot:$('#idCotizacion').val(),
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

function valDatos(){
  // if($("#direccion").val() == ""){
  //   alert("La dirección del cliente no puede estar vacia")
  // }
  // if($("#atencion").val() == ""){
  //   alert("El campo atencion del cliente no puede estar vacia")
  // }
  // if($("#telefono").val() == ""){
  //   alert("El telefono del cliente no puede estar vacia")
  // }
  // if($("#correo").val() == ""){
  //   alert("El correo del cliente no puede estar vacia")
  // }
  alert("Datos")
  return false
}