$(document).ready(function()
{
  $('.select2').select2();
    $("#btnGuardar").click(function()
    {
        // create(); 
        if ($("#sw").val() == "false") {
          updateParametro()
          // console.log("Actualziacion")
        } else {
          setParametros()
          // console.log("Registro")
        }
    });
    getParametros();
    $("#btnCreate").click(function()
    {
      $("#sw").val("true")
        $("#titulo").text("Crear parámetro")
        $("#sucursal").val(0)
        $("#parametro").val("")
        $("#unidad").val(0)
        $("#tipo").val(0)
        $("#area").val(0)
        $("#norma").val(0)
        $("#limite").val("")
        $("#matriz").val(0)
        $("#rama").val(0)
        $("#metodo").val(0)
        $("#tecnica").val(0)
        $("#procedimiento").val(0)
        $("#simbologia").val(0)
        $("#simbologiaInf").val(0)
        $("#CurvaPadre").val(0)

    });
    $("#btnEditar").click(function()
    {
        // create(); 
        $("#sw").val("false")
        $("#titulo").text("Editar parámetro")
    });
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
            tab += '    <td><button class="btn btn-warning" data-toggle="modal" id="btnEditar" data-target="#modalParametro" onclick="getNormasParametro('+item.Id_parametro+')"><i class="fas fa-edit"></i> Editar</button></td>';
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
    url: base_url + '/admin/analisisQ/getDatoParametro', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: { 
      id:id,
        _token: $('input[name="_token"]').val(),
      },
    dataType: 'json', 
    async:false,
    success: function (response) {

      $("#idParametro").val(""),
      $('#curva').prop('checked',0),
      $("#sucursal").val(""),
      $("#parametro").val(""),
      $("#unidad").val(""),
      $("#tipo").val(""),
      $("#area").val(""),
      $("#norma").val(""),
      $("#limite").val(""),
      $("#matriz").val(""),
      $("#rama").val(""),
      $("#metodo").val(""),
      $("#tecnica").val(""),
      $("#procedimiento").val(""),
      $("#simbologia").val(""),
      $("#simbologiaInf").val(""),
      $("#CurvaPadre").val(""),

      console.log(response);
      let temp = new Array();
      $.each(response.norma, function(key,item){
        temp.push(item.Id_norma)
      });
      
      let c = response.model.Curva
      $('#curva').prop('checked', parseInt(c));
      $("#idParametro").val(response.model.Id_parametro)
      $("#sucursal").val(response.model.Id_laboratorio)
      $("#parametro").val(response.model.Parametro)
      $("#unidad").val(response.model.Id_unidad)
      $("#tipo").val(response.model.Id_tipo_formula)
      $("#area").val(response.model.Id_area)
      $("#norma").val(temp);
      $("#limite").val(response.model.Limite);
      $("#matriz").val(response.model.Id_matriz);
      $("#rama").val(response.model.Id_rama);
      $("#metodo").val(response.model.Id_metodo);
      $("#tecnica").val(response.model.Id_tecnica);
      $("#procedimiento").val(response.model.Id_procedimiento);
      $("#simbologia").val(response.model.Id_simbologia);
      $("#simbologiaInf").val(response.model.Id_simbologia_info);
      $("#CurvaPadre").val(response.model.Padre);
    } 
})
}
 function updateParametro(){
  $.ajax({
    url: base_url + '/admin/analisisQ/updateParametro', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: { 
      id:$("#idParametro").val(),
      curva:$('#curva').prop('checked'),
      sucursal:$("#sucursal").val(),
      parametro:$("#parametro").val(),
      unidad:$("#unidad").val(),
      tipo:$("#tipo").val(),
      area:$("#area").val(),
      norma:$("#norma").val(),
      limite:$("#limite").val(),
      matriz:$("#matriz").val(),
      rama:$("#rama").val(),
      metodo:$("#metodo").val(),
      tecnica:$("#tecnica").val(),
      procedimiento:$("#procedimiento").val(),
      simbologia:$("#simbologia").val(),
      simbologiaInf:$("#simbologiaInf").val(),
      padre:$("#CurvaPadre").val(),
        _token: $('input[name="_token"]').val(),
      },
    dataType: 'json', 
    async:false,
    success: function (response) {
      alert("Parametro modificado correctamente!", "success")
      getParametros();
    } 
})
 }
 function setParametros(){
  $.ajax({
    url: base_url + '/admin/analisisQ/setParametros', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: { 
      id:$("#idParametro").val(),
      curva:$('#curva').prop('checked'),
      sucursal:$("#sucursal").val(),
      parametro:$("#parametro").val(),
      unidad:$("#unidad").val(),
      tipo:$("#tipo").val(),
      area:$("#area").val(),
      norma:$("#norma").val(),
      limite:$("#limite").val(),
      matriz:$("#matriz").val(),
      rama:$("#rama").val(),
      metodo:$("#metodo").val(),
      tecnica:$("#tecnica").val(),
      procedimiento:$("#procedimiento").val(),
      simbologia:$("#simbologia").val(),
      simbologiaInf:$("#simbologiaInf").val(),
      padre:$("#CurvaPadre").val(),
        _token: $('input[name="_token"]').val(),
      },
    dataType: 'json', 
    async:false,
    success: function (response) {
      alert("Parametro modificado correctamente!", "success")
      getParametros();
    } 
})
 }
