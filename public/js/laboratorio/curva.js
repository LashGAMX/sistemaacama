
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
      console.log('btn creare estandares')
        createStd();
    });

    $("#buscar").click(function(){
        buscar();
        
        
    });
    $('#calcular').click(function(){
        // promedio();
        // setCalcular();
    });
    $('#setConstantes').click(function(){
        setConstantes();
    });

    $("#editar").click(function(){
 
    });
    $("#formula").click(function(){
        // formula();
        setCalcular();
    });
    // $("#idLote").addEventListener('change', (event)=> {
    //     getParametro();
    // });
    $("#idArea").on("change",function(){ 
        console.log('getParametro')
        getParametro();
    });

    $("#idAreaModal").on("change",function(){ 
        console.log('evento activado area');
        getLote();
       getParametroModal();
    });
    
    
    $("#idLoteModal").on('change', function(){
        console.log('evento activado parametro');
    });

    

});
function getParametroDetalle(){ 
    $.ajax({
        url: base_url + '/admin/laboratorio/getParametroDetalle', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          idLote:$("#idLoteModal").val(),
         
          _token: $('input[name="_token"]').val(),
        },
        dataType: 'json', 
        async: false, 
        success: function (response) {
         console.log(response);

        }
    });   
}
function setConstantes()
{
   
    //fecha = fecha.toLocaleDateString();
    console.log(fecha);

    $.ajax({
        url: base_url + '/admin/laboratorio/setConstantes', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          idLote:$("#idLote").val(),
          fecha:$("#fecha").val(),
          b:$("#b").val(),
          m:$("#m").val(),
          r:$("#r").val(),
          _token: $('input[name="_token"]').val(),
        },
        dataType: 'json', 
        async: false, 
        success: function (response) {
         console.log(response);
        if (response.sw == true) {
            swal("Good job!", "Guardado!", "success");
        }
        }
    });   
}
 
function getParametro(){

    let div = document.getElementById("divParametro");
    let tab = "";

    $.ajax({
        url: base_url + '/admin/laboratorio/getParametro', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          idLote:$("#idLote").val(),
          idArea:$("#idArea").val(),
          _token: $('input[name="_token"]').val(),
        },
        dataType: 'json', 
        async: false, 
        success: function (response) {
         console.log(response);
            tab+= '<select class="form-control" id="parametro">';
            tab+= '<option value="">Selecciona Parametro</option>';
            $.each(response.parametro, function (key, item) {
                tab+= '<option value="'+item.Id_parametro+'">'+item.Parametro+'</option>';
            });
            tab+= '</select>';
            div.innerHTML = tab;

        }
    });        
    
}
function getParametroModal(){

    let div = document.getElementById("divParametroModal");
    let tab = "";

    $.ajax({
        url: base_url + '/admin/laboratorio/getParametroModal', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
       
         idArea:$("#idAreaModal").val(),
          _token: $('input[name="_token"]').val(),
        },
        dataType: 'json', 
        async: false, 
        success: function (response) {
         console.log(response);
            tab+= '<select class="form-control" id="idParametroModal">';
            tab+= '<option value="">Selecciona Parametro</option>';
            $.each(response.parametro, function (key, item) {
                tab+= '<option value="'+item.Id_parametro+'">'+item.Parametro+'</option>';
            });
            tab+= '</select>';
            div.innerHTML = tab;

        }
    });        
    
}

function getLote(){  // motodo para obtener el lote en la modal

    let div = document.getElementById("DivLoteModal");
    let tab = "";

    $.ajax({
        url: base_url + '/admin/laboratorio/getLote', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          //idLote:$("#idLote").val(),
          idArea:$("#idAreaModal").val(),
          _token: $('input[name="_token"]').val(),
        },
        dataType: 'json', 
        async: false, 
        success: function (response) {
         console.log(response);
            tab+= '<select class="form-control" id="idLoteModal">';
            tab+= '<option value="">Selecciona Lote</option>';
            $.each(response.lote, function (key, item) {
                tab+= '<option value="'+item.Id_lote+'">'+item.Fecha+' : '+item.Id_lote+'</option>';
            });
            tab+= '</select>';
            div.innerHTML = tab;

            
        }
    });        
    
}


function setCalcular()
{
    getMatriz();
    let tabla = document.getElementById('divTablaStd');
    let tab = '';
    $.ajax({
        url: base_url + '/admin/laboratorio/setCalcular', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            fecha:$("#fecha").val(),
            area:$('#idArea').val(),
            parametro: $("#parametro").val(),
          conArr:cont,
          arrCon:arrCon,
          _token: $('input[name="_token"]').val(),
        },
        dataType: 'json', 
        async: false, 
        success: function (response) {
         console.log(response);
         tab += '<table id="tablaLote" class="table table-sm">';
         tab += '    <thead class="thead-dark">';
         tab += '        <tr>';
         tab += '          <th>Id</th>';
         tab += '          <th>Id Lote</th> ';
         tab += '          <th>STD</th> ';
         tab += '          <th>Concentracion</th> ';
         tab += '          <th>ABS1</th> ';
         tab += '          <th>ABS2</th> ';
         tab += '          <th>ABS3</th> ';
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
             tab += '<td><input value="'+item.Promedio+'"></td>'; 
           tab += '</tr>'; 
         });
     
         tab += '    </tbody>';
         tab += '</table>';
         tabla.innerHTML = tab;

         $("#b").val(response.b.toFixed(5));
         $("#m").val(response.m.toFixed(5));
         $("#r").val(response.r.toFixed(5));
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


function guardarSTd(){
    table = document.getElementById("tablaLote");
    
}
//------------------Formula BMR-----------------
function formula(){


    $.ajax({
        url: base_url + '/admin/laboratorio/formula', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          idLote:$("#idLote").val(),
          fecha:$("#fecha").val(),
          area:$('#idArea').val(),
          parametro: $("#parametro").val(),
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

//------Create----------------
function createStd(){
    let tabla = document.getElementById('divTablaStd');
    let tab = '';

    $.ajax({
        url: base_url + '/admin/laboratorio/createStd', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            idAreaModal:$("#idAreaModal").val(),
            idParametroModal:$("#idParametroModal").val(),
            fechaInicio:$("#fechaInicio").val(),
            fechaFin:$("#fechaFin").val(),
    
          _token: $('input[name="_token"]').val(),
        },
        dataType: 'json', 
        async: false, 
        success: function (response) { 
         console.log(response);
         swal("Registro!", "Se crearon los estandares!", "success")
         let i = 0;
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
            tab += '          <th>ABS1</th> ';
            tab += '          <th>ABS2</th> ';
            tab += '          <th>ABS3</th> ';
            tab += '          <th>Promedio</th> '; 
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            $.each(response.stdModel, function (key, item) {
                tab += '<tr>';
                tab += '<td>'+item.Id_std+'</td>';
                tab += '<td>'+item.Id_lote+'</td>';
                tab += '<td>'+item.STD+'</td>';
                if(item.Concentracion != ''){
                    tab += '<td><input value="'+response.concentracion[i].Concentracion+'"></td>';
                }else{
                    tab += '<td><input value="'+item.Concentracion+'"></td>';
                }
                tab += '<td><input value="'+item.ABS1+'"></td>';
                tab += '<td><input value="'+item.ABS2+'"></td>';
                tab += '<td><input value="'+item.ABS3+'"></td>'; 
                tab += '<td><input value="'+item.Promedio+'"></td>'; 
              tab += '</tr>'; 
              i++;
            });
        
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;
         }
        }
    });           
}
//---------buscar ----------------------------
var res = new Array();
var cont = 0;
var idLote = 0;
function buscar(){
    let tabla = document.getElementById('divTablaStd');
    let tab = '';
    $.ajax({
        url: base_url + '/admin/laboratorio/buscar', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
          //idLote:$("#idLote").val(),
          fecha:$("#fecha").val(),
          area:$('#idArea').val(),
          parametro: $("#parametro").val(),
          _token: $('input[name="_token"]').val()
        },
        dataType: 'json', 
        async: false, 
        success: function (response) {
            console.log(response);
            if (response.valbmr != false){ 
                $("#b").val(response.bmr.B);
                $("#m").val(response.bmr.M);
                $("#r").val(response.bmr.R); 
            }else{ 
                $("#b").val("");
                $("#m").val("");
                $("#r").val(""); 
            }
            res = response.concentracion;   
            cont = 0;
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
                tab += '          <th>ABS1</th> ';
                tab += '          <th>ABS2</th> ';
                tab += '          <th>ABS3</th> ';
                tab += '          <th>Promedio</th> ';
                tab += '        </tr>';
                tab += '    </thead>';
                tab += '    <tbody>'; 
                console.log('cont inicial:'+cont)
                $.each(response.stdModel, function (key, item) {
                   //idLote = item.Id_std;
                  if(cont == 0) 
                  {
                    tab += '<tr>';
                    tab += '<td>'+item.Id_std+'</td>';
                    tab += '<td>'+item.Id_lote+'</td>';
                    tab += '<td>'+item.STD+'</td>';
                    tab += '<td><input id="curCon'+cont+'" value="0"></td>';
                    tab += '<td><input id="curStd1'+cont+'" value="0.0"></td>'; 
                    tab += '<td><input id="curStd2'+cont+'" value="0.0"></td>';
                    tab += '<td><input id="curStd3'+cont+'" value="0.0"></td>';
                    tab += '<td><input id="curProm'+cont+'" value="0.0" readonly></td>';
                    tab += '</tr>';
                    cont++;
                    console.log('primerCont:'+cont)
                  }else{
                    tab += '<tr>';
                    tab += '<td>'+item.Id_std+'</td>';
                    tab += '<td>'+item.Id_lote+'</td>';
                    tab += '<td>'+item.STD+'</td>';
                    tab += '<td><input  id="curCon'+cont+'" value="'+response.concentracion[cont-1].Concentracion+'"></td>';
                    tab += '<td><input id="curStd1'+cont+'" value="'+item.ABS1+'"></td>'; 
                    tab += '<td><input id="curStd2'+cont+'" value="'+item.ABS2+'"></td>';
                    tab += '<td><input id="curStd3'+cont+'" value="'+item.ABS3+'"></td>';
                    tab += '<td><input id="curProm'+cont+'" value="'+item.Promedio+'" readonly></td>';
                    tab += '</tr>';
                    cont++;
                    console.log('con:'+cont)
                  }
                  
                });
            
                tab += '    </tbody>';
                tab += '</table>';
                tabla.innerHTML = tab;
         }
          
           
            
         

        }    

    });           
}

var arrCon = new Array();

var conArrCon = new Array();
var conArrStd1 = new Array();
var conArrStd2 = new Array();
var conArrStd3 = new Array();
var conArrStdProm = new Array();

function getMatriz()
{   
    arrCon = new Array();

    conArrCon = new Array();
    conArrStd1 = new Array();
    conArrStd2 = new Array();
    conArrStd3 = new Array();
    conArrStdProm = new Array();
    
    for (let i = 0; i < cont; i++) {
        conArrCon.push($("#curCon"+i).val());
        conArrStd1.push($("#curStd1"+i).val());
        conArrStd2.push($("#curStd2"+i).val());
        conArrStd3.push($("#curStd3"+i).val());
        conArrStdProm.push($("#curProm"+i).val());
    }

    arrCon.push(conArrCon);
    arrCon.push(conArrStd1);
    arrCon.push(conArrStd2);
    arrCon.push(conArrStd3);
    arrCon.push(conArrStdProm);
}