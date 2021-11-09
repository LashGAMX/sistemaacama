var base_url = "https://dev.sistemaacama.com.mx";

$(document).ready(function (){
    
    tabla = $("#tableStd").DataTable({
        "responsive": true,
        "autoWidth": true,
         stateSave: true,
        "language": {
          "lengthMenu": "Mostrar _MENU_ por pagina",
          "zeroRecords": "Datos no encontrados",
          "info": "Mostrando _PAGE_ de _PAGES_",
          "infoEmpty": "No hay datos",
          "infoFiltered": "(filtered from _MAX_ total records)",
        }
      });

   
    //* Acciones para los botones
    $('#btnEdit tr').on('click', function(){
        let dato = $(this).find('td:first').html();
        idlot = dato;
      });


  $("#CreateStd").click(function(){
        createStd();
    });

    $("#buscar").click(function(){
        buscar();
        
    });
    $('#calcular').click(function(){
        promedio();
    });
    $('#guardar').click(function(){
      
    });

    $("#editar").click(function(){

    });
    $("#formula").click(function(){
        formula();
    });
    // $("#idLote").addEventListener('change', (event)=> {
    //     getParametro();
    // });
    $("#idLote").on("click",function(){
        getParametro();
    });

});

function getParametro(){

    let div = document.getElementById("divParametro");
    let tab = "";

    $.ajax({
        url: base_url + '/admin/laboratorio/getParametro', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          idLote:$("#idLote").val(),
          _token: $('input[name="_token"]').val(),
        },
        dataType: 'json', 
        async: false, 
        success: function (response) {
         console.log(response);
            tab+= '<select class="form-control" id="parametro">';
            tab+= '<option value="">Selecciona Parametro</option>';
            $.each(response.model, function (key, item) {
                tab+= '<option value="'+item.Id_parametro+'">'+item.Parametro+'</option>';
            });
            tab+= '</select>';
            div.innerHTML = tab;

        }
    });        
}

function promedio(){
    var suma = 0;
    var prom = 0;
    var abs1 = $('#ABS1').val();
    var abs2 = $('#ABS2').val();
    var abs3 = $('#ABS3').val();
    suma = abs1+abs2+abs3; 
    prom = suma/3;  
    
    $.ajax({
        url: base_url + '/admin/laboratorio/promedio', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          promedio:prom,
          abs1:abs1,
          abs2:abs2,
          abs3:abs3,
          _token: $('input[name="_token"]').val(),
        },
        dataType: 'json', 
        async: false, 
        success: function (response) {
         console.log(response);
         fix = response.resultado.toFixed(4);
            $("#promedio").val(fix);

        }
    });        
}

function guardar(){

    let stdSelected = document.getElementById("std");

    $.ajax({
        url: base_url + '/admin/laboratorio/guardar', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          idLote:$("#idLote").val(),
          std:stdSelected.options[stdSelected.selectedIndex].text,
          concentracion:$("#concentracion").val(),
          abs1:$("#ABS1").val(),
          abs2:$("#ABS2").val(),
          abs3:$("#ABS3").val(),
          promedio:$("#promedio").val(),
          _token: $('input[name="_token"]').val(),
        },
        dataType: 'json', 
        async: false, 
        success: function (response) {
         console.log(response);
        // window.location="https://dev.sistemaacama.com.mx/admin/laboratorio/curva";
         window.location = base_url + '/admin/laboratorio/buscar/' + $("#idLote").val();   
        }
    });        
}
function guardarSTd(){
    table = document.getElementById("tablaLote");
    
}
function formula(){


    $.ajax({
        url: base_url + '/admin/laboratorio/formula', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          idLote:$("#idLote").val(),
          _token: $('input[name="_token"]').val(),
        },
        dataType: 'json', 
        async: false, 
        success: function (response) {
         console.log(response);
         let fixb = response.b.toFixed(5);
         let fixm = response.m.toFixed(5);
         let fixr = response.r.toFixed(5);
         $("#b").val(fixb);
         $("#m").val(fixm);
         $("#r").val(fixr);
        }
    });        
    
}

function createStd(){
    let tabla = document.getElementById('divTablaStd');
    let tab = '';

    $.ajax({
        url: base_url + '/admin/laboratorio/createStd', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          idLote:$("#idLote").val(),
          numEstandares:$("#numEstandares").val(),
          _token: $('input[name="_token"]').val(),
        },
        dataType: 'json', 
        async: false, 
        success: function (response) { 
         console.log(response);
         if(response.sw == false){
             alert("Este lote ya tiene estandare creados");
         }else{
            tab += '<table id="tablaLote" class="table table-sm">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>Id</th>';
            tab += '          <th>Id Lote</th> ';
            tab += '          <th>STD</th> ';
            tab += '          <th>Concentracion</th> ';
            tab += '          <th>STD1</th> ';
            tab += '          <th>STD2</th> ';
            tab += '          <th>STD3</th> ';
            tab += '          <th>Promedio</th> ';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            $.each(response.stdModel, function (key, item) {
                tab += '<tr>';
                tab += '<td>'+item.Id_std+'</td>';
                tab += '<td>'+item.Id_lote+'</td>';
                tab += '<td>'+item.STD+'</td>';
                tab += '<td><input value="'+item.Concentracion+'"></td>';
                tab += '<td><input value="'+item.ABS1+'"></td>';
                tab += '<td><input value="'+item.ABS2+'"></td>';
                tab += '<td><input value="'+item.ABS3+'"></td>';
                tab += '<td>'+item.Promedio+'</td>';
              tab += '</tr>';
            });
        
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;
         }
        }
    });           
}

function buscar(){
    let tabla = document.getElementById('divTablaStd');
    let tab = '';
    $.ajax({
        url: base_url + '/admin/laboratorio/buscar', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          idLote:$("#idLote").val(),
          _token: $('input[name="_token"]').val()
        },
        dataType: 'json', 
        async: false, 
        success: function (response) {
         console.log(response);
         if(response.sw == false){
             alert("Necesitas generar estandares para este lote")
         }else{
            tab += '<table id="tablaLote" class="table table-sm">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>Id</th>';
            tab += '          <th>Id Lote</th> ';
            tab += '          <th>STD</th> ';
            tab += '          <th>Concentracion</th> ';
            tab += '          <th>STD1</th> ';
            tab += '          <th>STD2</th> ';
            tab += '          <th>STD3</th> ';
            tab += '          <th>Promedio</th> ';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            $.each(response.stdModel, function (key, item) {
                tab += '<tr>';
                tab += '<td>'+item.Id_std+'</td>';
                tab += '<td>'+item.Id_lote+'</td>';
                tab += '<td>'+item.STD+'</td>';
                tab += '<td><input value=""'+item.Concentracion+'"></td>';
                tab += '<td><input value=""'+item.ABS1+'"></td>';
                tab += '<td><input value=""'+item.ABS2+'"></td>';
                tab += '<td><input value=""'+item.ABS3+'"></td>';
                tab += '<td>'+item.Promedio+'</td>';
              tab += '</tr>';
            });
        
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;
         }
        }
    });           
}