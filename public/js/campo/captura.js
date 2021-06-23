var base_url = 'https://dev.sistemaacama.com.mx';

// var selectedRow = false;
$(document).ready(function () {
    $("#datosGenerales-tab").click();
    datosGenerales();
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

