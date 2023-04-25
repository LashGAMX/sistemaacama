var area = "micro";

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
        url: base_url + "/admin/laboratorio/"+area+"/muestraSinAsignar",
        data: {
            idLote: $("#idLote").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {      
            console.log("Muestra sin asignar")      ;
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
                tab += '<td>'+item.Codigo+'</td>';
                tab += '<td>'+item.Parametro+'</td>';
                tab += '<td><button type="button" id="btnAsignar" onclick="asignarMuestraLote('+item.Id_solicitud+','+item.Id_codigo+')"  class="btn btn-primary">Agregar</button></td>';
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
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/"+area+"/getMuestraAsignada",
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
                tab += '<td>'+item.Codigo+'</td>';
                tab += '<td>'+item.Parametro+'</td>';
                tab += '<td><button type="button" id="btnAsignar" onclick="delMuestraLote('+item.Id_detalle+','+item.Id_analisis+','+item.Id_parametro+')"  class="btn btn-danger">Eliminar</button></td>';
                tab += '</tr>';
            });
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;
        } 
    });
}  

function asignarMuestraLote(idAnalisis,idSol)
{

    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/"+area+"/asignarMuestraLote",
        data: {
            idLote:$("#idLote").val(),
            idAnalisis:idAnalisis,
            idSol:idSol,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
    
            muestraSinAsignar()
            getMuestraAsignada()

            if(response.sw == false)
            {
                swal("Registro!", "Esta muestra no puede ser asignada!", "error");
            }
        } 
    });
} 

function delMuestraLote(idDetalle,idSol,idParam){
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/"+area+"/delMuestraLote",
        data: {
            idLote:$("#idLote").val(),
            idSol:idSol,
            idDetalle:idDetalle,
            idParametro:idParam,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            muestraSinAsignar();
            getMuestraAsignada();
        } 
    });
}   