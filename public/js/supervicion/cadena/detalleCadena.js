var idCodigo;
var dataModel;
var idPunto;
$(document).ready(function () { 
    
    let tablePunto = $('#tablePuntos').DataTable({        
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        },
        "scrollY":        "300px",
        "scrollCollapse": true,
        "paging":         false
    });    
    $('#btnCadena').click(function(){
        window.location = base_url + "/admin/informes/exportPdfCustodiaInterna/"+idPunto;
    });

    $('#tablePuntos tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            tablePunto.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            let dato = $(this).find('td:first').html();
            idPunto = dato;
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
    $('#tableResultado').DataTable({        
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        }
    });   
    $('#ckLiberado').click(function (){
        if( $('#ckLiberado').prop('checked') == true ) {
            console.log("Seleccionado");
            liberarSolicitud()
        }else{
            console.log("No Seleccionado");
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
            idPunto: idPunto,
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
            // tab += '          <th>Liberado</th> '; 
            // tab += '          <th>Nombre</th> '; 
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            $.each(response.model, function (key, item) {
                if (item.Resultado != null) {
                    color = "success";
                } else { 
                    color = "warning"
                }
                tab += '<tr>';
                tab += '<td>'+item.Id_codigo+'</td>';
                tab += '<td class="bg-'+color+'">'+item.Parametro+'</td>';
                tab += '<td>'+item.Tipo_formula+'</td>';
                tab += '<td>'+item.Resultado+'</td>';
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
                "scrollY":        "300px",
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
    let tabla = document.getElementById('divTabDescripcion');
    let tab = '';
    tabla.innerHTML = tab;
    
    $("#resDes").val(0.0)
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
            console.log(response) 
            dataModel = response.model

            switch (response.paraModel.Id_area) {
                case "2":
                        console.log("entro a caso 2");
                        tab += '<button class="btn btn-danger" id="btnRegresar">Regresar resultado</button>'
                        tab += '<table id="tableResultado" class="table table-sm">';
                        tab += '    <thead class="thead-dark">';
                        tab += '        <tr>';
                        tab += '          <th>Descripcion</th>';
                        tab += '          <th>Valor</th>';
                        tab += '        </tr>';
                        tab += '    </thead>';
                        tab += '    <tbody>';
                        $.each(response.model, function (key, item) {
                            tab += '<tr>'; 
                            tab += '<td>'+response.paraModel.Parametro+'</td>';
                            tab += '<td>'+item.Vol_disolucion+'</td>';
                            tab += '</tr>';
                        });
                        tab += '    </tbody>';
                        tab += '</table>';
                        tabla.innerHTML = tab;
                    break;
            
                default:
                    console.log("entro a break");
                    break;
            }


            $('#btnRegresar').click(function (){
                if(confirm('Estas seguro de cancelar la muestra?')){
                    regresarRes();
                }
            });


            let tableResultado = $('#tableResultado').DataTable({        
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
function regresarRes()
{
    $.ajax({ 
        type: 'POST',
        url: base_url + "/admin/supervicion/cadena/regresarRes",
        data: {
            idSol: $("#idSol").val(),
            idCodigo:idCodigo,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {      
            swal("Analisis!", "Analisis regresado", "success");
            getParametros();
        } 
    });
}
function liberarSolicitud()
{
    $.ajax({ 
        type: 'POST',
        url: base_url + "/admin/supervicion/cadena/liberarSolicitud",
        data: {
            idSol: $("#idSol").val(),
            liberado:$('#ckLiberado').prop('checked'),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {      
            if(response.sw == true)
            {
                swal("Registro!", "Solicitud liberada", "success");
            }else{
                swal("Registro!", "Liberacion modificada", "success");
            }
        } 
    });
}