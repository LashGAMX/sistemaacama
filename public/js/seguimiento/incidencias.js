$(document).ready(function () {

});
$('#enviar').click(function () {
   create();
   alert('Enviar');
});
$('#modulo').on("change", function () {
  getSubmodulos();
});
function getSubmodulos(){
  let div = document.getElementById("divSubmodulo");
  let tab = "";
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
        tab += '<select class="form-control" id="submodulo">';
        tab += '<option value="">Sin selecionar</option>';
        $.each(response.submodulos, function (key, item) {
            tab += '<option value="' + item.Id_submodulo + '">'+item.Submodulo+'</option>';
        });
        tab += '</select>';
        div.innerHTML = tab;

       
    }
});
}

function create() {

   $.ajax({
      type: "POST",
      url: base_url + "/admin/seguimiento/incidencias/create",
      data: {
            modulo:$("#modulo").val(),
            submodulo:$("#submodulo").val(),
            prioridad:$("#priodidad").val(),
            descripcion:$("#descripcion").val(),
          _token: $('input[name="_token"]').val()
      }, 
      dataType: "json",
      success: function (response) {            
          console.log(response);
         
      }
  });
}
