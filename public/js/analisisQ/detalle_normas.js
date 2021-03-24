var base_url = "https://dev.sistemaacama.com.mx";


  tablaParametros();


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
        console.log(response)
        //tab += '<div class="row justify-content-start">'+inputFiltroFecha('fInicio','fFin','filtroFecha',inputBtn('','','Buscar','search','success',"buscarFecha('filtroFecha')"))+'</div>';
        // tab += '<div class="row justify-content-end">' + inputBtn('', '', 'Agreagar', 'plus', 'success','' , 'botton') + '</div><br>';
        // tab += '<table id="tablaParametro" class="table table-sm  table-striped table-bordered">';
        // tab += '    <thead class="thead-dark">';
        // tab += '        <tr>';
        // tab += '            <th style="width: 5%;">Id</th>';
        // tab += '            <th style="width: 30%;">Norma</th>';
        
        // tab += '            <th>Parametro</th>';
        // tab += '        </tr>';
        // tab += '    </thead>';
        // tab += '    <tbody>';
        // $.each(response.model, function (key, item) {
        //     tab += '<tr>';
        //   tab += '<td>'+item.Id_norma_param+'</td>';
        //   tab += '<td>'+item.Clave+'</td>';
        //   tab += '<td>'+item.Parametro+'</td>';
        //   tab += '</tr>';
        // });
        // tab += '    </tbody>';
        // tab += '</table>';
        // table.innerHTML = tab;

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
// function setParametros(id,idSub)
// {
//   let parametros = document.getElementById('parametros');
//   let itemP = new Array();
//   for (let i = 0; i < parametros.length; i++) {
//     if(parametros[i].selected == true)
//     {
//       itemP.push(parametros[i].value);
//     }
//   }
//   $.ajax({
//     url: base_url + 'admin/nalisisQ/detalle_normas/create', //archivo que recibe la peticion
//     type: 'POST', //método de envio
//     data: {
//       id:id,
//       idSub:idSub,
//       parametros:itemP,
//     },
//     dataType: 'json', 
//     async: false,
//     success: function (response) {
//       console.log(response);
//       if (response.sw == true) {
//         tablaParametros(idSub);
//         swal("Registro!", "Registro exitoso!", "success");
//       } else if (response.sw == false) {
//         tablaParametros(idSub);
//         swal("Error!", "Error en el registro !Por favor verifique los datos¡", "error");
//       }
//     }
//   });
// }