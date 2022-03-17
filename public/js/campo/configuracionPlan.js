
var idSub;
$(document).ready(function () {
   getPaquetes();
});

$('#btnAddPlan').click(function(){
    setPlanMuestreo();
});

function getPaquetes()
{

    let tabla = document.getElementById('divTablePaquetes');
    let tab = '';
        $.ajax({ 
            type: "POST",
            url: base_url + "/admin/campo/configuracion/getPaquetes",
            data: {
                _token: $('input[name="_token"]').val()
            },
            dataType: "json",
            success: function (response) {            
                console.log(response);
                tab += '<table id="tablePaquetes" class="display compact cell-border" style="width:100%">';
                tab += '    <thead>';
                tab += '        <tr>';
                tab += '          <th>Id</th>';
                tab += '          <th>Norma</th>';
                tab += '          <th>Clave</th>';
                tab += '          <th>Tipo</th>';
                tab += '        </tr>';
                tab += '    </thead>';
                tab += '    <tbody>';
                $.each(response.model, function (key, item) {
                    tab += '<tr>';
                    tab += '<td>'+item.Id_subnorma+'</td>';
                    tab += '<td>'+item.Norma+'</td>';
                    tab += '<td>'+item.Clave+'</td>';
                    tab += '<td>Residual</td>';
                    tab += '</tr>';
                }); 
                tab += '    </tbody>';
                tab += '</table>';
                tabla.innerHTML = tab;


                var t = $('#tablePaquetes').DataTable({        
                    "ordering": false, 
                    "language": {
                        "lengthMenu": "# _MENU_ por pagina",
                        "zeroRecords": "No hay datos encontrados", 
                        "info": "Pagina _PAGE_ de _PAGES_",
                        "infoEmpty": "No hay datos encontrados",
                    }
                });


                $('#tablePaquetes tbody').on( 'click', 'tr', function () {
                    if ( $(this).hasClass('selected') ) {
                        $(this).removeClass('selected');
                    }
                    else {
                        t.$('tr.selected').removeClass('selected');
                        $(this).addClass('selected');
                    }
                } );
                $('#tablePaquetes tr').on('click', function(){
                    let dato = $(this).find('td:first').html();
                    getEnvase(dato);
                    idSub = dato;
                  });
            }
        });
}
function getEnvase(id)
{

    let tabla = document.getElementById('divTableEnvase');
    let tab = '';
        $.ajax({ 
            type: "POST",
            url: base_url + "/admin/campo/configuracion/getEnvase",
            data: {
                idPaquete:id,
                _token: $('input[name="_token"]').val()
            },
            dataType: "json",
            success: function (response) {            
                console.log(response);
                tab += '<table id="tableEnvase" class="display compact cell-border" style="width:100%">';
                tab += '    <thead>';
                tab += '        <tr>';
                // tab += '          <th>Paquete</th>';
                tab += '          <th>Analisis</th>';
                tab += '          <th>Cantidad</th>';
                tab += '          <th>Recipiente</th>';
                tab += '        </tr>';
                tab += '    </thead>';
                tab += '    <tbody>';
                $.each(response.model, function (key, item) {
                    tab += '<tr>';
                    tab += '<td>'+item.Area+'</td>';
                    tab += '<td>'+item.Cantidad+'</td>';
                    tab += '<td>'+item.Envase+'</td>';
                    tab += '</tr>';
                }); 
                tab += '    </tbody>';
                tab += '</table>';
                tabla.innerHTML = tab;


                var t = $('#tableEnvase').DataTable({        
                    "ordering": false, 
                    "language": {
                        "lengthMenu": "# _MENU_ por pagina",
                        "zeroRecords": "No hay datos encontrados", 
                        "info": "Pagina _PAGE_ de _PAGES_",
                        "infoEmpty": "No hay datos encontrados",
                    }
                });


                $('#tableEnvase tbody').on( 'click', 'tr', function () {
                    if ( $(this).hasClass('selected') ) {
                        $(this).removeClass('selected');
                    }
                    else {
                        t.$('tr.selected').removeClass('selected');
                        $(this).addClass('selected');
                    }
                } );
                $('#tableEnvase tr').on('click', function(){
                    let dato = $(this).find('td:first').html();
                  });
            }
        });
}

function setPlanMuestreo()
{
    console.log("Entra")
    let tab = '';
  $.ajax({
    url: base_url + "/admin/campo/configuracion/setPlanMuestreo",
    type: 'POST', //método de envio
    data: {
      idSub:idSub,
      _token: $('input[name="_token"]').val(),
    },
    dataType: 'json', 
    async: false,
    success: function (response) {

        tab += '<table id="tableAreas" class="table cell-border" style="width:100%">';
        tab += '    <thead>';
        tab += '        <tr>';
        tab += '          <th>Activo</th>';
        tab += '          <th>Area</th>';
        tab += '        </tr>';
        tab += '    </thead>';
        tab += '    <tbody>';
        $.each(response.model, function (key, item) {
            tab += '<tr>';
            tab += '<td><input type="checkbox" class="custom-control-input" id="customCheck1"></td>';
            tab += '<td>'+item.Area+'</td>';
            tab += '</tr>';
        }); 
        tab += '    </tbody>';
        tab += '</table>';
        tabla.innerHTML = tab; 

        itemModal[0] = [
            tab,
        ]
        newModal('divModal','modalAreas','Areas plan','',1,1,0,'')

        var t = $('#tableAreas').DataTable({        
            "ordering": false, 
            "language": {
                "lengthMenu": "# _MENU_ por pagina",
                "zeroRecords": "No hay datos encontrados", 
                "info": "Pagina _PAGE_ de _PAGES_",
                "infoEmpty": "No hay datos encontrados",
            }
        });
    }
  });
}