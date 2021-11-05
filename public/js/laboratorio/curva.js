var base_url = "https://dev.sistemaacama.com.mx";
var table;
$(document).ready(function (){
    // var idLot = 0;
    // tabla = $("#tableStd").DataTable({
    //     "responsive": true,
    //     "autoWidth": true,
    //      stateSave: true,
    //     "language": {
    //       "lengthMenu": "Mostrar _MENU_ por pagina",
    //       "zeroRecords": "Datos no encontrados",
    //       "info": "Mostrando _PAGE_ de _PAGES_",
    //       "infoEmpty": "No hay datos",
    //       "infoFiltered": "(filtered from _MAX_ total records)",
    //     }
    //   });
    // table = $('#tableStd').DataTable({
    //     "ordering": false,
    //     "language": {
    //         "lengthMenu": "# _MENU_ por pagina",
    //         "zeroRecords": "No hay datos encontrados",
    //         "info": "Pagina _PAGE_ de _PAGES_",
    //         "infoEmpty": "No hay datos encontrados",   
    //     }
    // });
    $("#btnEdit").prop('disabled', true);
    $('#tableStd tbody').on( 'click', 'tr', function () { 
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
            // console.log("no selecionado");
            // selectedRow = false;
            $("#btnEdit").prop('disabled', true);
            idLot = 0;
        }
        else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            // console.log("Seleccionado");
            // selectedRow = true;
            $("#btnEdit").prop('disabled', false);
        }
    });
    //* Acciones para los botones
    $('#btnEdit tr').on('click', function(){
        let dato = $(this).find('td:first').html();
        idlot = dato;
      });

    $("#create").click(function(){
        create();
    });

    $("#buscar").click(function(){
        buscar();
    })
    $('#calcular').click(function(){
        promedio();
    });
    $('#guardar').click(function(){
        guardar();
    });

    $("#editar").click(function(){
        
        
    });
    $("#formula").click(function(){
        formula();
    });
});

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
function create(){

    $.ajax({
        url: base_url + '/admin/laboratorio/create', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          idLote:$("#idLote").val(),
          b:$("#b").val(),
          m:$("#m").val(),
          r:$("#r").val(),
          _token: $('input[name="_token"]').val(),
        },
        dataType: 'json', 
        async: false, 
        success: function (response) {
         console.log(response);
        
        }
    });        

}

function buscar(){
    // $.ajax({
    //     url: base_url + '/admin/laboratorio/buscar', //archivo que recibe la peticion
    //     type: 'POST', //método de envio
    //     data: {
    //       idLote:$("#idLote").val(),
    //     },
    //     dataType: 'json', 
    //     async: false, 
    //     success: function (response) {
    //      console.log(response);
    //     }
    // });        
    window.location = base_url + '/admin/laboratorio/buscar/' + $("#idLote").val();     
}