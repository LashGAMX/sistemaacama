
var table;
 $(document).ready(function (){
    listaSolicitudes();
    solicitudGenerada();
    puntoOrden();
 });
 
function puntoOrden()
{
    table = $("#muestreadorAsignado").DataTable ({
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
}
var folioAsignar;
 function listaSolicitudes()
 {
    tableGenerar = $("#listaAsignar").DataTable ({
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

        $("#btnEdit").prop('disabled', true);


        $('#listaAsignar tbody').on( 'click', 'tr', function () {
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
            }
            else {
                tableGenerar.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        } );

    let idSolicitud = 0; 
    let folio;
    $('#listaAsignar tr').on('click', function(){
        let dato = $(this).find('td:eq(0)').html();
        let dato2 =$(this).find('td:eq(1)').html();
        folio = dato2;
        idSolicitud = dato;
    });
    
    $('#solicitudGenerada tr').on('click', function(){
        let dato = $(this).find('td:eq(0)').html();
        folioAsignar = dato;
    });

    $('#btnImprimir').click( function () {
        alert("Imprimir"+" Id: "+idSolicitud);
        

        // window.location = base_url+"/admin/cotizacion/update/"+idSolicitud;
    } );
    $('#btnGenerar').click( function () {
        // alert("Generar");
        generar(idSolicitud, folio);
        // window.location = base_url+"/admin/campo/asignar";
        // window.location = base_url+"/admin/cotizacion/exportPdfOrden/"+idSolicitud;
    });
    $('#btnPlanMuestreo').click( function () {
        window.open(base_url+"/admin/campo/planMuestreo/"+idSolicitud);
        // window.location = base_url+"/admin/cotizacion/exportPdfOrden/"+idSolicitud;
    });
    $('#btnGuardar').click( function () {
        
        getFolio(folio);
        swal("Registro!", "Registro guardado correctamente!", "success");
        $('#modalAsignar').modal('hide')
        generar(idSolicitud, folio);
    });
 }
 function solicitudGenerada()
 {
    tableAsignar = $("#solicitudGenerada").DataTable ({
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

            
        $("#btnEdit").prop('disabled', true);
        $('#solicitudGenerada tbody').on( 'click', 'tr', function () { 
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
                // console.log("no selecionado");
                // selectedRow = false;
                $("#btnEdit").prop('disabled', true);
            }
            else {
                tableAsignar.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
                // console.log("Seleccionado");
                // selectedRow = true;
                $("#btnEdit").prop('disabled', false);
            }
        } );  
 }

 function generar(idSolicitud, folio) 
{
    let tabla = document.getElementById('divSolGenerada');
    let tab = '';
    $.ajax({
        url: base_url + '/admin/campo/asignar/generar', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            idSolicitud:idSolicitud,
            folio:folio,
            idUser:$("#idUsuarios").val(),
            _token: $('input[name="_token"]').val(),
          },
        dataType: 'json', 
        async: false, 
        success: function (response) {            
            console.log(response);
          tab += '<table id="solicitudGenerada" class="table table-sm">';
          tab += '    <thead class="thead-dark">';
          tab += '        <tr>';
          tab += '            <th>Folio</th>';
          tab += '            <th>Punto de muestreo</th>';
          tab += '            <th>Captura</th>';
          tab += '            <th>Id muestreador</th>';
          tab += '            <th>Nombres</th>';
          tab += '        </tr>';
          tab += '    </thead>';
          tab += '    <tbody>';
          $.each(response.model, function (key, item) {
            tab += '<tr>';
            tab += '    <td>'+item.Folio+'</td>';
            tab += '    <td>'+item.Punto_muestreo+'</td>';
            tab += '    <td>'+item.Captura+'</td>';
            tab += '    <td>'+item.Id_muestreador+'</td>';
            tab += '    <td>'+item.Nombres+'</td>';
            tab += '</tr>';
          });
          tab += '    </tbody>';
          tab += '</table>';
          tabla.innerHTML = tab;
   
        }
    });  
}
function getFolio(folioAsignar)
{
    console.log(folioAsignar); 
    $.ajax({
        url: base_url + '/admin/campo/asignar/getFolio', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            folioAsignar:folioAsignar,
            idUser:$("#idUsuarios").val(),
            _token: $('input[name="_token"]').val(),
          },
        dataType: 'json', 
        async: false, 
        success: function (response) {
          console.log(response)
        }
    }); 

}