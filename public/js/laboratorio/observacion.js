var base_url = 'https://dev.sistemaacama.com.mx';

$(document).ready(function () {
    table = $('#solicitudGenerada').DataTable({        
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        }
    });

    $('#btnBuscar').click( function () {
        console.log($("#tipoFormula").val());
        getServicio($("#tipoFormula").val());
    });
});

function getServicio(id){    
    let tabla = document.getElementById('solicitudGenerada');
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
              tab += '<td contenteditable="true">'+item.Observacion+'</td>';
              tab += '</tr>';
          });
          tab += '    </tbody>';
          tab += '</table>';
          tabla.innerHTML = tab;          

          var table = $('#solicitudGenerada').DataTable();    

          $('#solicitudGenerada tbody').on( 'click', 'tr', function () {
              $(this).toggleClass('selected');
          });

          $('#button').click( function () {
            alert( table.rows('.selected').data().length +' row(s) selected');
          });
        }        
    });
}

//Debe ir función AJAX
function aplicar(){
    let tabla = document.getElementById('solicitudGenerada');
    let ph = $('select[name="condicionPh"] option:selected').text();
    let solidos = $('select[name="solidos"] option:selected').text();
    let olor = $('select[name="olor"] option:selected').text();
    let color = $('select[name="color"] option:selected').text();
    let observacionGeneral = $('#observacionesGenerales').val();    

    //let celdaPh;
    let folioActual;
    let numeroFilas = ($('#solicitudGenerada tr').length)-1;    

    for(let i = 0 ; i < numeroFilas; i++){
        if($('#solicitudGenerada tbody tr').hasClass('selected')){
            folioActual = $('#solicitudGenerada tbody tr:eq(' + i + ') td:eq(' + 0 + ')').text();;
            
            $.ajax({
                type: "POST",
                url: base_url + '/admin/laboratorio/aplicarObservacion',
                data: {
                    folioActual: folioActual,
                    ph: ph,
                    solidos: solidos,
                    olor: olor,
                    color: color,
                    observacionGeneral: observacionGeneral
                },
                dataType: "json",
                success: function (response) {
                    console.log(response);
                }
            });
                                                                                                                        
            
            //celdaPh = $('#solicitudGenerada tr:eq(' + i + ') td:eq(' + 7 + ')').text();
            /*celdaPh = (document.getElementById("solicitudGenerada").rows[i].cells[0]).value;
            //this.innerHTML = '<td>'+ celdaPh +'</td>';
            console.log("Valor de celdaPh: " + celdaPh);*/
            
            console.log("La fila: " + (i) + " está seleccionada");
        }
    }    
}
