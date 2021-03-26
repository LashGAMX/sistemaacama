var base_url = "https://dev.sistemaacama.com.mx";


$(document).ready(function() {
  tablaParametros();
});


function tablaParametros()
{
  let table = document.getElementById('tabParametros');
  let idSub = document.getElementById('idSub');
  let tab = '';
  $.ajax({
      url: base_url + '/admin/analisisQ/detalle_normas/getParametro', //archivo que recibe la peticion
      type: 'POST', //método de envio
      data: {
        idSub:idSub.value,
        _token: $('input[name="_token"]').val(),
      },
      dataType: 'json', 
      async: false, 
      success: function (response) {
        console.log(response.model)
        tab += '<div class="row justify-content-end">' + inputBtn('', '', 'Agreagar', 'plus', 'success','agregarParametros('+idSub.value+')' , 'botton') + '</div><br>';
        tab += '<table id="tablaParametro" class="table table-sm  table-striped table-bordered">';
        tab += '    <thead class="thead-dark">';
        tab += '        <tr>';
        tab += '            <th style="width: 5%;">Id</th>';
        tab += '            <th style="width: 30%;">Parametro</th>';
        tab += '            <th>Matriz</th>';
        tab += '        </tr>';
        tab += '    </thead>';
        tab += '    <tbody>';
        $.each(response.model, function (key, item) {
            tab += '<tr>';
          tab += '<td>'+item.Id_parametro+'</td>';
          tab += '<td>'+item.Parametro+'</td>';
          tab += '<td>'+item.Matriz+'</td>';
          tab += '</tr>';
        });
        tab += '    </tbody>';
        tab += '</table>';
        table.innerHTML = tab;
 
        // $('.duallistbox').bootstrapDualListbox({
        //   nonSelectedListLabel: 'No seleccionado',
        //   selectedListLabel: 'Seleccionado',
        //   preserveSelectionOnMove: 'Mover',
        //   moveOnSelect: true,
        //   infoText:'Mostrar todo {0}',
        //   filterPlaceHolder:'Filtro' 
        //   });   
        // crearTabla2("tablaParametro");
      }
  });
}
function agregarParametros(idSub)
{
  let idNorma = document.getElementById('idNorma').value;
  let parametro = new Array();
  let parametroId = new Array();
  let normaId = new Array(); //Alacena parametros agregados de la sub-norma
  $.ajax({
    url: base_url + '/admin/analisisQ/detalle_normas/getParametroNorma', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      idSub:idSub,
      idNorma:idNorma,
      _token: $('input[name="_token"]').val(),
    },
    dataType: 'json', 
    async: false,
    success: function (response) {
      $.each(response.sqlParametro,function(key,item){
        parametro.push("Pa: "+item.Parametro + '/ Mat:' + item.Matriz);
        parametroId.push(item.Id_parametro);
      });
      $.each(response.sqlNorma,function(key,item){
        normaId.push(item.Id_parametro);
      });
    }
  });
  let element = [
    inputMultiple('parametros','','Lista de parametros',parametro,parametroId,'Parametros',normaId,'duallistbox'),
  ];
  itemModal[0] = element;
  newModal('divModal','listaParametros','Asignar parametros','lg',1,1,0,inputBtn('','','Guardar','save','success','createNormaParametro('+idSub+')'));
  $('.duallistbox').bootstrapDualListbox({
    nonSelectedListLabel: 'No seleccionado',
    selectedListLabel: 'Seleccionado',
    preserveSelectionOnMove: 'Mover',
    moveOnSelect: true,
    infoText:'Mostrar todo {0}',
    filterPlaceHolder:'Filtro'
  });
}
function createNormaParametro(idSub)
{
  let parametros = document.getElementById('parametros');
  let itemP = new Array();
  for (let i = 0; i < parametros.length; i++) {
    if(parametros[i].selected == true)
    {
      itemP.push(parametros[i].value);
    }
  }
  $.ajax({
    url: base_url + '/admin/analisisQ/detalle_normas/createNormaParametro', //archivo que recibe la peticion
    type: 'POST', //método de envio
    data: {
      idSub:idSub,
      parametros:itemP,
      _token: $('input[name="_token"]').val(),
    },
    dataType: 'json', 
    async: false,
    success: function (response) {
      console.log(response);
      if (response.sw == true) {
        tablaParametros();
        swal("Registro!", "Registro exitoso!", "success");
        $('#listaParametros').modal('hide');
      } else if (response.sw == false) {
        tablaParametros();
        swal("Error!", "Error en el registro !Por favor verifique los datos¡", "error");
        $('#listaParametros').modal('hide');
      }
    }
  });
}