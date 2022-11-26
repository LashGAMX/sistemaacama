$(document).ready(function() {
    getPaquetes();
    $("#btnCrear").click(function (){
        setPrecioPaquete()
    })
});


function getPaquetes()
{
    let tabla = document.getElementById('divPaquete');
    let tab = '';
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/precios/paquete/getPaquetes",
        data: {
            idNorma: $("#norma").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {      

            tab += '<table id="tabPaquete" class="table table-sm">';
            tab += '    <thead class="thead-dark">';
            tab += '          <tr> ';
            tab += '          <th>#</th> ';
            tab += '          <th rowspan="1"></th> ';
            tab += '          <th colspan="6"><center>Precios</center></th> ';
            tab += '      </tr> ';
            tab += '      <tr> ';
            tab += '          <th>Id</th> ';
            tab += '          <th>Paquete</th> ';
            tab += '          <th>Instantaneo</th> ';
            tab += '          <th>1 - 4 Hrs</th> ';
            tab += '          <th>4 - 8 Hrs</th> ';
            tab += '          <th>8 - 12 Hrs</th> ';
            tab += '          <th>12 - 18 Hrs</th> ';
            tab += '          <th>18 - 24 Hrs</th> ';
            tab += '          <th>Opc</th> ';
            tab += '      </tr> ';
            tab += '    </thead>';
            tab += '    <tbody>'; 
            $.each(response.model, function (key, item) {
                tab += '<tr>';
                tab += '<td>'+item.Id_precio+'</td>';
                tab += '<td>'+item.Clave+'</td>';
                tab += '<td><input style="width:90px" type="number" id="precio1'+item.Id_precio+'" value="'+item.Precio1+'"></td>';
                tab += '<td><input style="width:90px" type="number" id="precio2'+item.Id_precio+'" value="'+item.Precio2+'"></td>';
                tab += '<td><input style="width:90px" type="number" id="precio3'+item.Id_precio+'" value="'+item.Precio3+'"></td>';
                tab += '<td><input style="width:90px" type="number" id="precio4'+item.Id_precio+'" value="'+item.Precio4+'"></td>';
                tab += '<td><input style="width:90px" type="number" id="precio5'+item.Id_precio+'" value="'+item.Precio5+'"></td>';
                tab += '<td><input style="width:90px" type="number" id="precio6'+item.Id_precio+'" value="'+item.Precio6+'"></td>';
                tab += '<td><i class="fa fa-check text-success" onclick="savePrecioPaq('+item.Id_precio+')"></i></td>';
                tab += '</tr>';
            });
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;
            var t = $('#tabPaquete').DataTable({
                "ordering": false,
                "language": {
                    "lengthMenu": "# _MENU_ por pagina",
                    "zeroRecords": "No hay datos encontrados",
                    "info": "Pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay datos encontrados",
                }
            });
         
        } 
    });
}

function savePrecioPaq(id){
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/precios/paquete/savePrecioPaq",
        data: {
            id:id,
            precio1:$("#precio1"+id).val(),
            precio2:$("#precio2"+id).val(),
            precio3:$("#precio3"+id).val(),
            precio4:$("#precio4"+id).val(),
            precio5:$("#precio5"+id).val(),
            precio6:$("#precio6"+id).val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {      
            console.log(response)
            alert("Precio Modificado")
            getPaquetes();
        } 
    });
}
function setPrecioPaquete(){
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/precios/paquete/setPrecioPaquete",
        data: {
            id:$("#paquete").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",qr
        async: false,
        success: function (response) {      
            console.log(response)
            if (response.sw == true) {
                alert("Paquete creado")   
            } else {
                alert("El paquete ya se encuentra creado")
            }
            getPaquetes();
        } 
    });
}