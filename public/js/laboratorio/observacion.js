var base_url = 'https://dev.sistemaacama.com.mx';
$(document).ready(function () {
    table = $('.tablaObservacion').DataTable({        
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        }
    });
});

//Múltiple selección
$(document).ready(function() {
    var table = $('#primeraTabla').DataTable();
    var indiceFilaSelect;

    $('#primeraTabla tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');                
        
        //indiceFilaSelect = this.rowIndex;
        //console.log("Valor de indiceFila: " + indiceFilaSelect);

        //$('#segundaTabla tbody tr').toggleClass('selected');        
    });

    $('#segundaTabla tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
        //$('#primeraTabla tbody tr').toggleClass('selected');
    });
    
    $('#button').click( function () {
        alert( table.rows('.selected').data().length +' row(s) selected');
    });

    $('#btnBuscar').click( function () {
        console.log($("#tipoFormula").val());
        getServicio($("#tipoFormula").val());
    });
});

function getServicio(id){
    let tabla = document.getElementById('tablaObservacion');
    let tab = '';
    $.ajax({
        url: base_url + '/admin/laboratorio/getObservacionanalisis', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            id:id,
            _token: $('input[name="_token"]').val(),
          },
        dataType: 'json', 
        async: false, 
        success: function (response) {
          console.log(response);
          tab += '<table id="solicitudGenerada" class="table table-sm">';
          tab += '    <thead class="thead-dark">';
          tab += '        <tr>';
          tab += '          <th>Folio servicio</th>';
          tab += '          <th>Nombre cliente</th>';
          tab += '          <th scope="col">Fecha recepción</th>';
          tab += '          <th>FechaCreación</th>       ';
          tab += '          <th>Punto de muestreo</th>';
          tab += '          <th>Norma</th>';
          tab += '          <th>Parámetros</th>';
          tab += '          <th>Es pH < 2</th>';
          tab += '          <th>Sólidos</th> ';
          tab += '          <th>Olor</th> ';
          tab += '          <th>Color</th> ';
          tab += '          <th>Observaciones</th>';
          tab += '        </tr>';
          tab += '    </thead>';
          tab += '    <tbody>';
          $.each(response.model, function (key, item) {
              tab += '<tr>';
              tab += '<td>'+item.Folio+'</td>';
              tab += '<td>'+item.Empresa+'</td>';
              tab += '<td>'+item.Hora_entrada+'</td>';
              tab += '<td></td>';
              tab += '<td></td>';
              tab += '<td></td>';
              tab += '<td></td>';
              tab += '<td>'+item.Ph+'</td>';
              tab += '<td>'+item.Solido+'</td>';
              tab += '<td>'+item.olor+'</td>';
              tab += '<td>'+item.Color+'</td>';
              tab += '<td>'+item.Observacion+'</td>';
            tab += '</tr>';
          });
          tab += '    </tbody>';
          tab += '</table>';
          tabla.innerHTML = tab;
   
        }
    });  
}

