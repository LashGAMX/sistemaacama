var base_url = "https://dev.sistemaacama.com.mx";

$(document).ready(function (){
    //* Acciones para los botones
    // table = $('#tableStd').DataTable({
    //     "ordering": false,
    //     "language": {
    //         "lengthMenu": "# _MENU_ por pagina",
    //         "zeroRecords": "No hay datos encontrados",
    //         "info": "Pagina _PAGE_ de _PAGES_",
    //         "infoEmpty": "No hay datos encontrados",   
    //     }
    // });
    $(document).ready(function() {
        var table = $('#tableStd').DataTable(); 
        $('#tableStd tbody').on( 'click', 'tr', function () {                 
            $(this).toggleClass('selected'); } 
        ); 
        
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
         window.location="https://dev.sistemaacama.com.mx/admin/laboratorio/curva";
        }
    });        
}
function formula(){


    $.ajax({
        url: base_url + '/admin/laboratorio/formula', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          idLote:$("#idLote").val(),
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