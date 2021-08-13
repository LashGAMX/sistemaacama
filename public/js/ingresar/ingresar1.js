var base_url = 'https://dev.sistemaacama.com.mx';
var table;
var folioAsignar;
let idSolicitud = 0; 
let folio;

$(document).ready(function (){        
    table = $('#tablaSolicitud').DataTable({
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",   
        }
    });
    
    $('#tablaSolicitud tbody').on( 'click', 'tr', function () {          
        //console.log("Dentro de tablaSolicitud");        
        
        if ( $(this).hasClass('selected') ) {
            //console.log("Dentro de selected1");
            $(this).removeClass('selected');
        }
        else {            
            //console.log("Dentro de selected1 else");
            table.$('tr.selected').removeClass('selected');            
            $(this).addClass('selected');              
        }
        
        //$('#tablaSolicitud tr').on('click', function(){
            //console.log("Dentro de tablaSolicitud2");
            let dato = $(this).find('td:eq(0)').html();            
            let dato2 =$(this).find('td:eq(1)').html();
            folio = dato2;
            idSolicitud = dato;
        //});                
        
        //console.log("Valor de idSolicitud: " + idSolicitud);
        //console.log("Valor de folio: " + folio);        
        generarDatos(idSolicitud, folio);
    } );
 
    let idCot = 0; 
    $('#tablaSolicitud tr').on('click', function(){
        let dato = $(this).find('td:first').html();
        idCot = dato;        
    });
});  

function mostrarDatos()
 {
    tableAsignar = $("#tablaDatos").DataTable ({
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",   
        },
        scrollY:        '30vh',
        scrollCollapse: true,
        paging:         false
    });    
                    
    $('#tablaDatos tbody').on( 'click', 'tr', function () { 
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');            
        }
        else {
            tableAsignar.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');        
        }
        } );  
}

function generarDatos(idSolicitud, folio) 
{
    //console.log("Valor de idSolicitud: " + idSolicitud);
    //console.log("Dentro de Generar");
    let tabla = document.getElementById('divTablaDatos');
    let tab = '';
    
    $.ajax({
        url: base_url + '/admin/ingresar/listar', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            idSolicitud:idSolicitud,
            folio:folio,
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json', 
        async: false, 
        success: function (response) {
          //console.log("Dentro de función Ajax"); 
          console.log(response); 
          console.log();
          tab += '<table id="tablaDatos" class="table">';
          tab += '    <thead class="thead-dark">';
          tab += '        <tr>';
          tab += '            <th style="font-size: 200%">Folio</th>';
          tab += '            <th style="font-size: 200%">Descarga</th>';
          tab += '            <th style="font-size: 200%">Cliente o Intermediario</th>';
          tab += '            <th style="font-size: 200%">Empresa</th>';
          tab += '            <th style="font-size: 200%">Hora Recepción</th>';
          tab += '            <th style="font-size: 200%">Hora Entrada</th>';
          tab += '        </tr>';
          tab += '    </thead>';
          tab += '    <tbody>';
          $.each(response.model, function (key, item) {
            tab += '<tr>';
            tab += '    <td style="font-size: 150%">'+item.Folio+'</td>';
            tab += '    <td style="font-size: 150%">'+item.Descarga+'</td>';
            tab += '    <td style="font-size: 150%">'+item.NomInter+'</td>';
            tab += '    <td style="font-size: 150%">'+item.Nombre+'</td>';            
            tab += '</tr>';
          });
          tab += '    </tbody>';
          tab += '</table>';
          tabla.innerHTML = tab;
        }
    });  
}
