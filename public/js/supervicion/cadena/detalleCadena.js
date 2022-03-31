var idCodigo;
var dataModel;
$(document).ready(function () {
    
    let tablePunto = $('#tablePuntos').DataTable({        
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        }
    });    

    $('#tablePuntos tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            tablePunto.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            getParametros();
        }
    } );

    $('#tableParametros').DataTable({        
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        }
    });   
    $('#tableDescricion').DataTable({        
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        }
    });   

});

function getParametros()
{
    let color = "";
    let tabla = document.getElementById('divTableParametros');
    let tab = '';
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/supervicion/cadena/getParametroCadena",
        data: {
            idSol: $("#idSol").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {      
            tab += '<table id="tableParametros" class="table table-sm">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>Id</th>';
            tab += '          <th>Parametro</th>';
            tab += '          <th>Tipo formula</th>';
            tab += '          <th>Resultado</th> '; 
            tab += '          <th>Resultado escrito</th> '; 
            // tab += '          <th>Liberado</th> '; 
            // tab += '          <th>Nombre</th> '; 
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            $.each(response.model, function (key, item) {
                if (item.Resultado != "NULL") {
                    color = "success";
                } else { 
                    color = "warning"
                }
                tab += '<tr class="bg-'+color+'">';
                tab += '<td>'+item.Id_codigo+'</td>';
                tab += '<td>'+item.Parametro+'</td>';
                tab += '<td>'+item.Tipo_formula+'</td>';
                tab += '<td><input style="width:75px;" id="res'+item.Id_codigo+'" value="'+item.Resultado+'"></td>';
                // tab += '<td>'+item.Resultado+'</td>';
                // tab += '<td>'+item.Resultado+'</td>';
                tab += '</tr>';
            });
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;
            let tableParametro = $('#tableParametros').DataTable({        
                "ordering": false,
                "language": {
                    "lengthMenu": "# _MENU_ por pagina",
                    "zeroRecords": "No hay datos encontrados",
                    "info": "Pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay datos encontrados",
                },
                "scrollY":        "200px",
                "scrollCollapse": true,
                "paging":         false
            });  
            $('#tableParametros tbody').on( 'click', 'tr', function () {
                if ( $(this).hasClass('selected') ) {
                    $(this).removeClass('selected');
                }
                else {
                    tableParametro.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                    getDetalleAnalisis(idCodigo);
                }
            } );

            $('#tableParametros tr').on('click', function(){
                let dato = $(this).find('td:first').html();
                idCodigo = dato;
              });
        
        } 
    }); 
}
function getDetalleAnalisis(idCodigo)
{
   
    $.ajax({ 
        type: 'POST',
        url: base_url + "/admin/supervicion/cadena/getDetalleAnalisis",
        data: {
            idSol: $("#idSol").val(),
            idCodigo:idCodigo,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {      
            dataModel = response.model
        } 
    });
}