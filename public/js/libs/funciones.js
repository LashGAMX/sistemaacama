
var validarRFC = (function (rfc = '') {
  let swRfc = false;
  if (rfc.length >= 12 && rfc.length <= 13) {
    $.ajax({
      url: base_url + '/clientes/validarRFC', //archivo que recibe la peticion
      type: 'POST', //método de envio
      data: {
        rfc: rfc,
      },
      dataType: 'json',
      async: false,
      success: function (response) {
        //    console.log(response);
        if (response.sw == true) {
          swRfc = false;
          swal("Error!", "Este rfc ya se encuentra registrado¡", "error");
        } else if (response.sw == false) {
          swRfc = true;
        }
      }
    });
  } else {
    swRfc = false;
    swal("Error!", "La logitud del rfc no es la correcta¡", "error");
  }
  return swRfc;
});


var validarCampos = (function (item) {
  let campo;
  let sw = false;
  campo = document.getElementsByName(item);
  for (let i = 0; i < campo.length; i++) {
    if (campo[i].value != '') {
      $("#" + campo[i].id).css("border", "");
      sw = true;
    } else {
      //inputFocus(campo[i].id);
      $("#" + campo[i].id).css("border", "solid 1px #dc3545");
      $("#" + campo[i].id).append(" <b>Appended text</b>.");
      $("#" + campo[i].id).click(function () {
        $("#" + campo[i].id).css("border", "");
      });
      swal("Error!", "Por favor verifique sus datos!", "warning");
      sw = false;

    }
  }
  return sw;
});

var inputFocus = (function (id) {
  $('#' + id).css("background-color", "#fffa86");
});

var validarCambio = (function (last, now) {
  let sw = false;
  if (last != now) {
    sw = true;
  }
  return sw;
});

var getEstado = (function () {
  let estado = new Array();
  let idEstado = new Array();
  let data;
  $.ajax({
    url: base_url + '/DBConsult/getEstado', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
    },
    dataType: 'json',
    async: false,
    success: function (response) {
      $.each(response.estados,function(key,item){
        estado.push(item.Nombre);
        idEstado.push(item.Id_estado);
      });
    }
  });
  data = [estado,idEstado];
  // data = {'estado':[estado],'idEstado':[idEstado]};
  return data;
});
var getMunicipio = (function (estado = null) {
  let municipio = new Array();
  $.ajax({
    url: base_url + '/assets/js/json/estados-municipios.json', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
    },
    dataType: 'json',
    async: false,
    success: function (response) {
      if (estado != null) {
        municipio = response[estado];
      } else{
        municipio = response;
      }
    }
  });
  return municipio;
});
var getCuerpoReceptor = (function () {
  let cuerpo = new Array();
  let idCuerpo = new Array();
  let data;
  $.ajax({
    url: base_url + '/DBConsult/getCuerpoReceptor', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
    },
    dataType: 'json',
    async: false,
    success: function (response) {
      $.each(response.cuerpos,function(key,item){
        cuerpo.push(item.Nombre);
        idCuerpo.push(item.Id_cuerpo);
      });
    }
  });
  data = [cuerpo,idCuerpo];
  // data = {'estado':[estado],'idEstado':[idEstado]};
  return data;
});