$(document).ready(function () {

});
$('#nueva').click(function () {
    window.location = base_url + '/admin/seguimiento/incidencias';
});
$('#administrar').click(function () {
  window.location = base_url + '/admin/seguimiento/incidencias/admin';
});
$('#modulo').on("change", function () {
    getSubmodulos();
  });
function getSubmodulos(){
    let divTable = document.getElementById("divSubmodulo");
    let tabs = "";
    $.ajax({
      type: "POST",
      url: base_url + "/admin/seguimiento/incidencias/getsubmodulos",
      data: {
            modulo:$("#modulo").val(),
          _token: $('input[name="_token"]').val()
      }, 
      dataType: "json",
      success: function (response) {            
          console.log(response);
          tabs += '<select class="form-control" id="submodulo">';
          tabs += '<option value="">Sin selecionar</option>';
          $.each(response.submodulos, function (key, item) {
              tabs += '<option value="' + item.id + '">'+item.title+'</option>';
          });
          tabs += '</select>';
          divTable.innerHTML = tabs;
      }
  });
  }
  function buscar(){
    let divTable = document.getElementById("tabla");
    let tab = "";
    $.ajax({
      type: "POST",
      url: base_url + "/admin/seguimiento/incidencias/buscar",
      data: {
            modulo:$("#modulo").val(),
            submodulo:$("#submodulo").val(),
            prioridad:$("#prioridad").val(),
          _token: $('input[name="_token"]').val()
      }, 
      dataType: "json",
      success: function (response) {            
          console.log(response);
          tab += '<table class="table" id="lista">';
          tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>ID</th>';
            tab += '          <th>Módulo</th> ';
            tab += '          <th>Submodulo</th> ';
            tab += '          <th>Prioridad</th> ';
            tab += '          <th>Estado</th> ';
            tab += '          <th>Descripción</th> ';
            tab += '          <th>Fecha</th> ';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            $.each(response.model, function (key, item) {
                tab += '<tr>';
                tab += '<td>' + item.Id_incidencia + '</td>';
                tab += '<td>' + item.Modulo + '</td>';
                tab += '<td>'+ item.Submodulo + '</td>';
                tab += '<td>' + item.Prioridad + '</td>';
                tab += '<td>' + item.Estado + '</td>';
                tab += '<td>' + item.Descripcion + '</td>';
                tab += '<td>' + item.created_at + '</td>';
                tab += '</tr>';
            });
          tab += '</table>';
          divTable.innerHTML = tab;
      }
  });
  }