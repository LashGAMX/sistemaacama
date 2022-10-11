$(document).ready(function()
{
    // $("#guardar").click(function()
    // {
    //     create(); 
    // });
    getParametros();

}); 
function getParametros(){
  let tabla = document.getElementById('divTabla');
  let tab = '';
  let cont = 0;
    $.ajax({
        url: base_url + '/admin/analisisQ/getParametros', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: { 
            _token: $('input[name="_token"]').val(),
          },
        dataType: 'json', 
        async: false, 
        success: function (response) {
          console.log(response);
          tab += '<table id="parametros" class="table table-sm">';
          tab += '    <thead class="thead-dark">';
          tab += '        <tr>';
          tab += '          <th>Id</th>';
          tab += '          <th>Sucursal</th>';
          tab += '          <th>Rama</th>';
          tab += '          <th>Parámetro</th>';
          tab += '          <th>Unidad</th>';
          tab += '          <th>Método prueba</th>';
          tab += '          <th>C. Metodo</th>';
          tab += '          <th>Norma</th>';
          tab += '          <th>Limite</th>';
          tab += '          <th>Opc</th>';
          tab += '        </tr>';
          tab += '    </thead>';
          tab += '    <tbody>'; 
          $.each(response.model, function (key, item) {
            tab += '<tr>';
            tab += '    <td>'+item.Id_parametro+'</td>';
            tab += '    <td>'+item.Sucursal+'</td>';
            tab += '    <td>'+item.Rama+'</td>';
            tab += '    <td>'+item.Parametro+'</td>';
            tab += '    <td>'+item.Unidad+'</td>';
            tab += '    <td>'+item.Metodo_prueba+'</td>';
            tab += '    <td>'+item.Clave_metodo+'</td>';
            tab += '    <td>'+response.norma[cont]+'</td>';
            tab += '    <td>'+item.Limite+'</td>';
            tab += '    <td><button class="btn btn-warning" data-toggle="modal" data-target="#modalParametro" onclick="getNormasParametro('+item.Id_parametro+')"><i class="fas fa-edit"></i> Editar</button></td>';
            tab += '</tr>';
            cont++;
          }); 
          tab += '    </tbody>';
          tab += '</table>';
          tabla.innerHTML = tab;

          table = $('#parametros').DataTable({
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

function getNormasParametro(id){
  let model = '';
  $.ajax({
    url: base_url + '/admin/analisisQ/getNormaParametro', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: { 
      id:id,
        _token: $('input[name="_token"]').val(),
      },
    dataType: 'json', 
    async:false,
    success: function (response) {
      // var options = ["2", "3"];
      // $("#norma ").val(options);
    } 
})
}
 