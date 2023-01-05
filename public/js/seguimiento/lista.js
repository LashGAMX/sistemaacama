$(document).ready(function () {

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