$(document).ready(function (){

    // $("#guardarTelefono").click(function(){
    //     console.log('btn guardar')
    //     datosGenerales();
    // });

 
    datosGenerales()

});

function datosGenerales(){
    $.ajax({
        url: base_url + '/admin/clientes/datosGenerales', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            idUser:$("#idUser").val(),
          telefono:$("#telefono").val(),
          correo:$("#correo").val(),
          direccion:$("#direccion").val(),
          atencion:$("#atencion").val(),
          
          _token: $('input[name="_token"]').val(),
        },
        dataType: 'json', 
        async: false, 
        success: function (response) {
         console.log(response);
        if (response.sw == true) {
            swal("Good job!", "Guardado!", "success");
            console.log(response);
        }
        }
    });   
}
function getDatosGenerales(){
    let tabla = document.getElementById('tabGenerales');
    let tab = '';
    $.ajax({
        type: 'POST',
        url: base_url + '/admin/clientes/getDatosGenerales', 
        data: {
            id:$("#idUser").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            model = response.model
            tab += '<table id="tablaDatosGenerales" class="table table-sm" style="font-size:10px">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>#</th>';
            tab += '          <th>Nombre</th>';
            tab += '          <th>Departamento</th>';
            tab += '          <th>Puesto</th>';
            tab += '          <th>Email</th>';
            tab += '          <th>Celular</th>';
            tab += '          <th>Telefono</th>';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            $.each(response.model, function (key, item) {
                tab2 += '<tr>';
                tab2 += '<td>'+item.Id_contacto+'</td>';
                tab2 += '<td>'+item.Nombre+'</td>';
                tab2 += '<td>'+item.Departamento+'</td>';
                tab2 += '<td>'+item.Puesto+'</td>';
                tab2 += '<td>'+item.Celular+'</td>';
                tab2 += '<td>'+item.Telefono+'</td>';
                tab2 += '</tr>';
            }); 
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;
        }
    });
}

/*function TablaSucursal(){
    tabla=document.getElementById('SucurcalCliente');
    tab ='';
    $.ajax({
      type: 'POST',
      url: base_url +'/admin/Clientes/TablaSucursal',
      datos:{

      },
      dataType: "json",
      ansync:false,
      success:function(response){
        listaclientes=response.model;
        tab += '<table id="TablaScursal" class="table table-sm">';
        tab += '<thead class="thead-dark">';
        tab +=   '<tr>';
        tab +=      ' <th>Id</th>';
        tab +=      ' <th>Nombre</th>';
        tab +=      ' <th>Estado</th>';
        tab +=      '  <th>Tipo Cliente</th> ';
        tab +=      ' <th>Acción</th> ';
        tab +=   '</tr> ';
        tab += '</thead> ';
        tab += '      <tbody> ';
       listaclientes.forEach(element => {
        if(element.Id_)
       })
        tab +=          '<tr>';

        tab +=          '</tr> ';
        tab += '      </tbody> ';

      }

    });
}*/