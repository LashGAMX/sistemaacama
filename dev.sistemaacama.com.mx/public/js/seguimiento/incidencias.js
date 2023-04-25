$(document).ready(function () {

});

$('#modulo').on("change", function () { 
  getSubmodulos();
});
function getSubmodulos() {
  let div = document.getElementById("divSubmodulo");
  let tab = "";
  $.ajax({
    type: "POST",
    url: base_url + "/admin/seguimiento/incidencias/getsubmodulos",
    data: {
      modulo: $("#modulo").val(),
      _token: $('input[name="_token"]').val()
    },
    dataType: "json",
    success: function (response) {
      console.log(response);
      tab += '<select class="form-control" id="submodulo" name="submodulo">';
      tab += '<option value="">Sin selecionar</option>';
      $.each(response.submodulos, function (key, item) {
        tab += '<option value="' + item.id + '">' + item.title + '</option>';
      });
      tab += '</select>';
      div.innerHTML = tab;

    }
  });
}

