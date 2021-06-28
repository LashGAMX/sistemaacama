var base_url = 'https://dev.sistemaacama.com.mx';

// var selectedRow = false;
$(document).ready(function () {
    $("#datosGenerales-tab").click();
    datosGenerales();
    datosMuestreo();
});

function datosGenerales()
{
    table = $("#materialUsado").DataTable ({
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",   
        },
        scrollY:        '15vh',
        scrollCollapse: true,
        paging:         false
    });

    $("#termometro").click(function(){getFactorCorreccion()});
    $("#phTrazable1").click(function(){setPhTrazable($("#phTrazable1").val(),"phTNombre1","phTMarca1","phTLote1")});
    $("#phTrazable2").click(function(){setPhTrazable($("#phTrazable2").val(),"phTNombre2","phTMarca2","phTLote2")});

    $("#phCalidad1").click(function(){setPhCalidad($("#phCalidad1").val(),"phCNombre1","phCMarca1","phCLote1")});
    $("#phCalidad2").click(function(){setPhCalidad($("#phCalidad2").val(),"phCNombre2","phCMarca2","phCLote2")});

    $("#conTrazable").click(function(){setConTrazable($("#conTrazable").val(),"conNombre","conMarca","conLote")});
    $("#conCalidad").click(function(){setConCalidad($("#conCalidad").val(),"conCNombre","conCMarca","conCLote")});
}
function datosMuestreo()
{

}
function valPhTrazable(lec1,lec2,lec3,estado)
{
  let sw = false;
  let std = document.getElementById(estado);
  let l1 = parseFloat(document.getElementById(lec1).value);
  let l2 = parseFloat(document.getElementById(lec2).value);
  let l3 = parseFloat(document.getElementById(lec3).value);

  // Val if rango 4 - 9

  if(l1 > 4 && l1 < 9)
  {
    sw = true;
  }else if(l2 > 4 && l2 < 9){
    sw = true;
  }else if(l1 > 4 && l1 < 9){
    sw = true;
  }else{
    sw = false;
  }

  // val if 0.003

  r1 = (l1 - l2);
  r2 = (l1 - l3);
 
  r3 = (l2 - l1);
  r4 = (l2 - l3);

  r5 = (l3 - l1);
  r6 = (l3 - l2);



  if(r1 <= -0.03 || r1 >= 0.03)
  {
    sw = false;
  }else{
    sw = true;
  }

  if(r2 <= -0.03 || r2 >= 0.03)
    {
      sw = false;
    }else{
      sw = true;
    }

    if(r3 <= -0.03 || r3 >= 0.03)
    {
      sw = false;
    }else{
      sw = true;
    }

    if(r4 <= -0.03 || r4 >= 0.03)
    {
      sw = false;
    }else{
      sw = true;
    }
    if(r5 <= -0.03 || r5 >= 0.03)
    {
      sw = false;
    }else{
      sw = true;
    }

    if(r6 <= -0.03 || r6 >= 0.03)
    {
      sw = false;
    }else{
      sw = true;
    }

  if(sw == true)
  {
    std.value = "Aprobado";
  }else{
    std.value = "Rechazado";
  }

  return sw;
}

function valPhCalidad(lec1,lec2,lec3,estado,prom)
{

  let sw = false;
  let p = document.getElementById(prom);
  let std = document.getElementById(estado);
  let l1 = parseFloat(document.getElementById(lec1).value);
  let l2 = parseFloat(document.getElementById(lec2).value);
  let l3 = parseFloat(document.getElementById(lec3).value);


  if(l1 > 4 && l1 < 9)
  {
    sw = true;
  }else if(l2 > 4 && l2 < 9){
    sw = true;
  }else if(l1 > 4 && l1 < 9){
    sw = true;
  }else{
    sw = false;
  }

  // val if 0.003

  r1 = (l1 - l2);
  r2 = (l1 - l3);
 
  r3 = (l2 - l1);
  r4 = (l2 - l3);

  r5 = (l3 - l1);
  r6 = (l3 - l2);



  if(r1 <= -0.03 || r1 >= 0.03)
  {
    sw = false;
  }else{
    sw = true;
  }

  if(r2 <= -0.03 || r2 >= 0.03)
    {
      sw = false;
    }else{
      sw = true;
    }

    if(r3 <= -0.03 || r3 >= 0.03)
    {
      sw = false;
    }else{
      sw = true;
    }

    if(r4 <= -0.03 || r4 >= 0.03)
    {
      sw = false;
    }else{
      sw = true;
    }
    if(r5 <= -0.03 || r5 >= 0.03)
    {
      sw = false;
    }else{
      sw = true;
    }

    if(r6 <= -0.03 || r6 >= 0.03)
    {
      sw = false;
    }else{
      sw = true;
    }

  if(sw == true)
  {
    std.value = "Aprobado";
  }else{
    std.value = "Rechazado";
  }

  p.value = (l1 + l2 + l3) / 3;
}

function valConTrazable(lec1,lec2,lec3,estado)
{

}

function valConCalidad(lec1,lec2,lec3,estado,prom)
{
  let sw = false; 
  let std = document.getElementById(estado);
  let p = document.getElementById(prom);
  let l1 = parseFloat(document.getElementById(lec1).value);
  let l2 = parseFloat(document.getElementById(lec2).value);
  let l3 = parseFloat(document.getElementById(lec3).value);

  p.value = (l1 + l2 + l3 ) / 3
}

function valPhMuestra(lec1,lec2,lec3,prom)
{
  let sw = false;
  let p = document.getElementById(prom);
  let l1 = parseFloat(document.getElementById(lec1).value);
  let l2 = parseFloat(document.getElementById(lec2).value);
  let l3 = parseFloat(document.getElementById(lec3).value);

  // Val if rango 4 - 9

  if(l1 > 4 && l1 < 9)
  {
    sw = true;
  }else if(l2 > 4 && l2 < 9){
    sw = true;
  }else if(l1 > 4 && l1 < 9){
    sw = true;
  }else{
    sw = false;
  }

  // val if 0.003

  r1 = (l1 - l2);
  r2 = (l1 - l3);
 
  r3 = (l2 - l1);
  r4 = (l2 - l3);

  r5 = (l3 - l1);
  r6 = (l3 - l2);



  if(r1 <= -0.03 || r1 >= 0.03)
  {
    sw = false;
  }else{
    sw = true;
  }

  if(r2 <= -0.03 || r2 >= 0.03)
    {
      sw = false;
    }else{
      sw = true;
    }

    if(r3 <= -0.03 || r3 >= 0.03)
    {
      sw = false;
    }else{
      sw = true;
    }

    if(r4 <= -0.03 || r4 >= 0.03)
    {
      sw = false;
    }else{
      sw = true;
    }
    if(r5 <= -0.03 || r5 >= 0.03)
    {
      sw = false;
    }else{
      sw = true;
    }

    if(r6 <= -0.03 || r6 >= 0.03)
    {
      sw = false;
    }else{
      sw = true;
    }

  if(sw == true)
  {
    // std.value = "Aprobado";
  }else{
    // std.value = "Rechazado";
  }

  p.value = (l1 + l2 +l3) / 3;

  return sw;
}

function valTempMuestra(lec1,lec2,lec3,prom)
{
  let sw = true;
  let p = document.getElementById(prom);
  let l1 = parseFloat(document.getElementById(lec1).value);
  let l2 = parseFloat(document.getElementById(lec2).value);
  let l3 = parseFloat(document.getElementById(lec3).value);

  if(l1 < 1 && l1 > 40)
  {
    sw = false;
  }
  if(l2 < 1 && l2 > 40){
    sw = false;
  }
  if(l3 < 1 && l3 > 40){
    sw = false;
  }

  if((l1 - l2) > 1 || (l1 - l2) < -1){
    sw = false;
  }
  if((l1 - l3) > 1 || (l1 - l3) < -1){
    sw = false;
  }
  if((l2 - l1) > 1 || (l2 - l1) < -1){
    sw = false;
  }
  if((l2 - l3) > 1 || (l2 - l3) < -1){
    sw = false;
  }
  if((l3 - l1) > 1 || (l3 - l1) < -1){
    sw = false;
  }
  if((l3 - l2) > 1 || (l3 - l2) < -1){
    sw = false;
  }

  p.value = ((l1 + l2 +l3) / 3).toFixed();

  if(sw == true)
  {
    console.log("Aceptado");
  }else{
    console.log("Rechazado");
  }

}
function valConMuestra(lec1,lec2,lec3,prom)
{
  let sw = true;
  let p = document.getElementById(prom);
  let l1 = parseFloat(document.getElementById(lec1).value);
  let l2 = parseFloat(document.getElementById(lec2).value);
  let l3 = parseFloat(document.getElementById(lec3).value);

 
  if((l1 - l2) > 5 || (l1 - l2) < -5){
    sw = false;
  }
  if((l1 - l3) > 5 || (l1 - l3) < -5){
    sw = false;
  }
  if((l2 - l1) > 5 || (l2 - l1) < -5){
    sw = false;
  }
  if((l2 - l3) > 5 || (l2 - l3) < -5){
    sw = false;
  }
  if((l3 - l1) > 5 || (l3 - l1) < -5){
    sw = false;
  }
  if((l3 - l2) > 5 || (l3 - l2) < -5){
    sw = false;
  }

  p.value = ((l1 +l2 +l3) / 3).toFixed(2);
}
function promedioPh(ph1,ph2,ph3,res)
{
  let p1 = document.getElementById(ph1).value;
  let p2 = document.getElementById(ph2).value;
  let p3 = document.getElementById(ph3).value;
  let r = document.getElementById(res);
  let prom = ((parseFloat(p1) + parseFloat(p2) + parseFloat(p3)) / 3);
  r.value = prom.toFixed(0)
}
function calPromedios(ph1,ph2,ph3,res,dec)
{
  let p1 = document.getElementById(ph1).value;
  let p2 = document.getElementById(ph2).value;
  let p3 = document.getElementById(ph3).value;
  let r = document.getElementById(res);
  let prom = ((parseFloat(p1) + parseFloat(p2) + parseFloat(p3)) / 3);
  r.value = prom.toFixed(dec)
}
function calPromedioGasto(ph1,ph2,ph3,res)
{
  let p1 = document.getElementById(ph1).value;
  let p2 = document.getElementById(ph2).value;
  let p3 = document.getElementById(ph3).value;
  let r = document.getElementById(res);
  let prom = ((parseFloat(p1) + parseFloat(p2) + parseFloat(p3)) / 3);
  r.value = (prom / 0.012)
}
function getFactorCorreccion()
{
    let table = document.getElementById('factorDeConversion');
    let tab = '';
    $.ajax({
        url: base_url + '/admin/campo/captura/getFactorCorreccion', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          idFactor: $("#termometro").val(),
          _token: $('input[name="_token"]').val(), 
        },
        dataType: 'json', 
        async: false, 
        success: function (response) {
          console.log(response.model)
          tab += '<table id="tablaFactorCorreccion" class="table table-sm  table-striped table-bordered">';
          tab += '    <thead class="thead-dark">';
          tab += '        <tr>';
          tab += '              <th>De °C</th>';
          tab += '              <th>a °C</th>';
          tab += '              <th>Factor corección</th>';
          tab += '              <th>Factor de corección aplicada</th>';
          tab += '        </tr>';
          tab += '    </thead>';
          tab += '    <tbody>';
          $.each(response.model, function (key, item) {
              tab += '<tr>';
            tab += '<td>'+item.De_c+'</td>';
            tab += '<td>'+item.A_c+'</td>';
            tab += '<td>'+item.Factor+'</td>';
            tab += '<td>'+item.Factor_aplicado+'</td>';
            tab += '</tr>';
          });
          tab += '    </tbody>';
          tab += '</table>';
          table.innerHTML = tab;
        }
    });
}
function setPhTrazable(idPh,nombre,marca,lote)
{
    let nom = document.getElementById(nombre);
    let mar = document.getElementById(marca);
    let lot = document.getElementById(lote);
    $.ajax({
        url: base_url + '/admin/campo/captura/getPhTrazable', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          idPh:idPh,
          _token: $('input[name="_token"]').val(), 
        },
        dataType: 'json', 
        async: false, 
        success: function (response) {
            console.log(response);
          nom.innerText = response.model.Ph;
          mar.innerText = response.model.Marca;
          lot.innerText = response.model.Lote;
        }
    });

}
function setPhCalidad(idPh,nombre,marca,lote)
{
    let nom = document.getElementById(nombre);
    let mar = document.getElementById(marca);
    let lot = document.getElementById(lote);
    $.ajax({
        url: base_url + '/admin/campo/captura/getPhCalidad', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          idPh:idPh,
          _token: $('input[name="_token"]').val(), 
        },
        dataType: 'json', 
        async: false, 
        success: function (response) {
            console.log(response);
          nom.innerText = response.model.Ph_calidad;
          mar.innerText = response.model.Marca;
          lot.innerText = response.model.Lote;
        }
    });
    
}
function setConTrazable(idCon,nombre,marca,lote)
{
    let nom = document.getElementById(nombre);
    let mar = document.getElementById(marca);
    let lot = document.getElementById(lote);
    $.ajax({
        url: base_url + '/admin/campo/captura/getConTrazable', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
        idCon:idCon,
          _token: $('input[name="_token"]').val(), 
        },
        dataType: 'json', 
        async: false, 
        success: function (response) {
            console.log(response);
          nom.innerText = response.model.Conductividad;
          mar.innerText = response.model.Marca;
          lot.innerText = response.model.Lote;
        }
    });
}
function setConCalidad(idCon,nombre,marca,lote)
{
    let nom = document.getElementById(nombre);
    let mar = document.getElementById(marca);
    let lot = document.getElementById(lote);
    $.ajax({
        url: base_url + '/admin/campo/captura/getConCalidad', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          idCon:idCon,
          _token: $('input[name="_token"]').val(), 
        },
        dataType: 'json', 
        async: false, 
        success: function (response) {
            console.log(response);
          nom.innerText = response.model.Conductividad;
          mar.innerText = response.model.Marca;
          lot.innerText = response.model.Lote;
        }
    });
}

