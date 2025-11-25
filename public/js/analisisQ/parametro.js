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
        $("#dias_analisis").val(0)



    });
    $("#btnEditar").click(function()
    {
        // create(); 
        $("#sw").val("false")
        $("#titulo").text("Editar parámetro")
    });
}); 


function getParametros() {
  const tablaExistente = $.fn.DataTable.isDataTable('#parametros');
  let tablaParametros = tablaExistente ? $('#parametros').DataTable() : null;

  if (tablaExistente) {
    tablaParametros.clear(); // Limpiar tabla si ya está inicializada
  }

  $.ajax({
    url: base_url + '/admin/analisisQ/getParametros',
    type: 'POST',
    data: {
      _token: $('input[name="_token"]').val(),
    },
    dataType: 'json',
    success: function (response) {
      const dataFilas = [];

      response.model.forEach((item, index) => {
        const fila = [
          item.Id_parametro,
          item.Sucursal,
          item.Rama,
          item.Parametro,
          item.Unidad,
          item.Metodo_prueba,
          item.Clave_metodo,
          response.norma[index],
          item.Limite,
          item.Dias_analisis,
          `<button class="btn btn-warning" data-toggle="modal" id="btnEditar" data-target="#modalParametro" onclick="getNormasParametro(${item.Id_parametro})">
             <i class="fas fa-edit"></i> Editar
           </button>`
        ];

        dataFilas.push(fila);
      });

      if (!tablaExistente) {
        tablaParametros = $('#parametros').DataTable({
          data: dataFilas,
          ordering: false,
          language: {
            lengthMenu: "# _MENU_ por página",
            zeroRecords: "No hay datos encontrados",
            info: "Página _PAGE_ de _PAGES_",
            infoEmpty: "No hay datos encontrados",
            search: "Buscar:",
          }
        });

        // Filtros individuales por columna
        $('#parametros thead tr:eq(1) th').each(function (i) {
          $('input', this).on('keyup change', function () {
            if (tablaParametros.column(i).search() !== this.value) {
              tablaParametros
                .column(i)
                .search(this.value)
                .draw();
            }
          });
        });

      } else {
        // Si ya existe la tabla, solo agregar datos y redibujar
        tablaParametros.rows.add(dataFilas).draw();
      }
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
      $("#CurvaPadre").val(0),
          
      $("#dias_analisis").val("");
      $("#usuarioDef").val("");
    


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
      // $("#CurvaPadre").val(response.model.Padre);
      $('#CurvaPadre').val(response.model.Padre).trigger('change.select2');
      // $("#usuarioDef").val(response.model.Usuario_default);
      $('#usuarioDef').val(response.model.Usuario_default).trigger('change.select2');
      $("#dias_analisis").val(response.model.Dias_analisis);
      

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
      dias_analisis: $("#dias_analisis").val(), 
      usuarioDef: $("#usuarioDef").val(), 

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
      dias_analisis:$("#dias_analisis").val(),

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
 
