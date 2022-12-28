$(document).ready(function () {

});
$('#enviar').click(function () {
   create();
});

function create() {

   $.ajax({
      type: "POST",
      url: base_url + "/admin/laboratorio/metales/enviarObservacion",
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
