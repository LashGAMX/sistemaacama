var base_url = "https://dev.sistemaacama.com.mx";

$(document).ready(function () {
    muestraSinAsignar();
    getMuestraAsignada();
});


function muestraSinAsignar()
{
    let tabla = document.getElementById('divTable');
    let tab = '';
    let idLote = $("#idLote").val();
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/muestraSinAsignar",
        data: {
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            tab += '<table id="tablaParamSin" class="table table-sm">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>Folio</th>';
            tab += '          <th>Parametros</th>';
            tab += '          <th>Opc</th> ';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            $.each(response.model, function (key, item) {
                tab += '<tr>';
                tab += '<td>'+item.Folio_servicio+'</td>';
                tab += '<td>'+item.Parametro+'</td>';
                tab += '<td><button type="button" id="btnAsignar" onclick="asignarMuestraLote('+idLote+','+item.Id_solicitud+','+item.Id_parametro+','+item.Id_solParam+')"  class="btn btn-primary">Agregar</button></td>';
                tab += '</tr>';
            });
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;
            $('#tablaParamSin').DataTable();
            var table = $('#tablaParamSin').DataTable();
 
            $('#tablaParamSin tbody').on( 'click', 'tr', function () {
                $(this).toggleClass('selected');
            } );
         
        } 
    });
}  
function getMuestraAsignada()
{
    let tabla = document.getElementById('divTable2');
    let tab = '';
    let idLote = $("#idLote").val();
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/getMuestraAsignada",
        data: {
            idLote:$("#idLote").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            tab += '<table id="tablaParamSin" class="table table-sm">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>Folio</th>';
            tab += '          <th>Parametros</th>';
            tab += '          <th>Opc</th> ';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            $.each(response.model, function (key, item) {
                tab += '<tr>';
                tab += '<td>'+item.Folio_servicio+'</td>';
                tab += '<td>'+item.Parametro+'</td>';
                tab += '<td><button type="button" id="btnAsignar" onclick="asignarMuestraLote('+idLote+','+item.Id_solicitud+','+item.Id_parametro+','+item.Id_solParam+')"  class="btn btn-primary">Agregar</button></td>';
                tab += '</tr>';
            });
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;
        } 
    });
}  

function asignarMuestraLote(idLote,idAnalisis,idParametro,idSol)
{
    let tabla = document.getElementById('divTable2');
    let tab = '';
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/asignarMuestraLote",
        data: {
            idLote:idLote,
            idAnalisis:idAnalisis,
            idParametro:idParametro,
            idSol:idSol,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            tab += '<table id="tablaParamSin" class="table table-sm">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>Folio</th>';
            tab += '          <th>Parametros</th>';
            tab += '          <th>Opc</th> ';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            $.each(response.model, function (key, item) {
                tab += '<tr>';
                tab += '<td>'+item.Folio_servicio+'</td>';
                tab += '<td>'+item.Parametro+'</td>';
                tab += '<td></td>';
              tab += '</tr>';
            });
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;
            muestraSinAsignar()
        } 
    });
} 