$(document).ready(function () {
    buscar();
});
$('#nueva').click(function () {
    window.location = base_url + '/admin/seguimiento/incidencias';
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
  function getIncidencia(id){
    let tab = "";
    let divModulo = document.getElementById("divModulo");
    let divSubmodulo = document.getElementById("divSubmoduloModal");
    let divPrioridad = document.getElementById("divPrioridad");
    let divEstado = document.getElementById("divEstado");
    let divFecha = document.getElementById("divFecha");
    let divDescripcion = document.getElementById("divDescripcion");
    let divObservacion = document.getElementById("divObservacion");
    let divImagen = document.getElementById("divImagen");
    let divIdIncidencia = document.getElementById("divIdIncidencia");

    $.ajax({
        type: "POST",
        url: base_url + "/admin/seguimiento/incidencias/getIncidencia",
        data: {
              id:id,
            _token: $('input[name="_token"]').val()
        }, 
        dataType: "json",
        success: function (response){
            console.log(response);
            tab += '<input type="text" value="'+response.model.Modulo+'"disabled>'
            divModulo.innerHTML = tab;
            tab = "";
            tab += '<input type="text" value="'+response.model.Submodulo+'"disabled>'
            divSubmodulo.innerHTML = tab;
            tab = "";
            tab += '<input type="text" value="'+response.model.Prioridad+'"disabled>'
            divPrioridad.innerHTML = tab;
            tab = "";
            tab += '<input type="text" value="'+response.model.created_at+'"disabled>'
            divFecha.innerHTML = tab;
            tab = "";
            tab += '<textarea type="text" disabled  cols="30" rows="10">'+response.model.Descripcion+'</textarea>'
            divDescripcion.innerHTML = tab;
            tab = "";
            tab += '<textarea type="text" cols="30" rows="10" id="observacionModal">'+response.model.Observacion+'</textarea>'
            divObservacion.innerHTML = tab;
            tab = "";
            tab += '<select id="estadoModal">';
            $.each(response.estado, function (key, item){
                tab += '<option value="'+item.Id_estado+'">'+item.Estado+'</option>';
            });
            tab += '</select>';
            divEstado.innerHTML = tab;
            tab = "";
            tab+= '<img class="zoom" src="data:image/gif;base64,'+response.model.Imagen+'" style="width: 100px;height: auto;">'
            divImagen.innerHTML = tab;
            tab = "";
            tab += '<input id="idIncidencia" hidden value="'+response.model.Id_incidencia+'">'
            divIdIncidencia.innerHTML = tab;
        }
    });
  }
  function update(){
    $.ajax({
        type: "POST",
        url: base_url + "/admin/seguimiento/incidencias/update",
        data: {
              id:$("#idIncidencia").val(),
              observacion:$("#observacionModal").val(),
              estado: $("#estadoModal").val(),
            _token: $('input[name="_token"]').val()
        }, 
        dataType: "json",
        success: function (response) {            
            console.log(response);
            swal("Datos guardados!", "Guardado!", "success");
            
            
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
            tab += '          <th>Usuario</th> ';
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
                tab += '<td>' + item.Usuario + '</td>';
                tab += '<td>' + item.Descripcion + '</td>';
                tab += '<td>' + item.created_at + '</td>';
                tab += '<td><button class="btn btn-info" data-toggle="modal" data-target="#modalIncidencia" id="ver" onclick="getIncidencia(' + item.Id_incidencia + ');">Ver</button></td>';
                tab += '</tr>';
            });
          tab += '</table>';
          divTable.innerHTML = tab;
      }
  });
  }