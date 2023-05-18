var area = "analisis";

$(document).ready(function () {
    $('#tabLote').DataTable({        
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        }
    });
    $('#tabCaptura').DataTable({        
        "ordering": false,
        "language": { 
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        }
    });    
    $('.select2').select2();
    $('#btnPendientes').click(function(){
        getPendientes()
    }); 
    $('#btnBuscarLote').click(function(){
        getLote()
    }); 
    $('#btnCrearLote').click(function(){
        setLote()
    }); 
});
 //todo Variables globales
var tableLote

 //todo funciones
 function setLote()
 {

    if ($("#parametro").val() != "0" && $("#fechaLote").val() != "") {
        if (confirm("¿Estas seguro de crear el lote?")) {
            $.ajax({
                type: 'POST',
                url: base_url + "/admin/laboratorio/"+area+"/getLote",
                data: {
                    id:$("#parametro").val(),
                    fecha:$("#fechaLote").val(),
                    _token: $('input[name="_token"]').val(),
                },
                dataType: "json",
                async: false,
                success: function (response) {            
                    if (response.model.length > 0) {
                        if (confirm("Ya tienes un lote en esta fecha, ¿Quieres crear otro lote?")) {
                            $.ajax({
                                type: 'POST',
                                url: base_url + "/admin/laboratorio/"+area+"/setLote",
                                data: {
                                    id:$("#parametro").val(),
                                    fecha:$("#fechaLote").val(),
                                    _token: $('input[name="_token"]').val(),
                                },
                                dataType: "json",
                                async: false,
                                success: function (response) {            
                                    console.log(response)
                                    getLote()
                                }
                            });
                        }else{
                            getLote()
                        }
                    } else{
                        $.ajax({
                            type: 'POST',
                            url: base_url + "/admin/laboratorio/"+area+"/setLote",
                            data: {
                                id:$("#parametro").val(),
                                fecha:$("#fechaLote").val(),
                                _token: $('input[name="_token"]').val(),
                            },
                            dataType: "json",
                            async: false,
                            success: function (response) {            
                                console.log(response)
                                getLote()
                            }
                        });
                    }
                }
            });
        }
    }else{
        alert("Para buscar un lote tienes que seleccionar un parametro y una fecha")
    }
 }
function getLote()
{
    if ($("#parametro").val() != "0" && $("#fechaLote").val() != "") {
        let tabla = document.getElementById('divLote');
        let tab = '';
        $.ajax({
            type: 'POST',
            url: base_url + "/admin/laboratorio/"+area+"/getLote",
            data: {
                id:$("#parametro").val(),
                fecha:$("#fechaLote").val(),
                _token: $('input[name="_token"]').val(),
            },
            dataType: "json",
            async: false,
            success: function (response) {            
                console.log(response);
                tab += '<table id="tabLote" class="table table-sm">'
                tab += '    <thead>'
                tab += '        <tr>'
                tab += '          <th>Id</th>'
                tab += '          <th>Fecha</th> '
                tab += '          <th>Parametro</th> '
                tab += '          <th>Asignados</th> '
                tab += '          <th>Liberados</th> '
                tab += '          <th>Opc</th> '
                tab += '        </tr>'
                tab += '    </thead>'
                tab += '    <tbody>'
                $.each(response.model,function (key,item){
                    tab += '<tr>'
                    tab += '<td>'+item.Id_lote+'</td>'
                    tab += '<td>'+item.Fecha+'</td>'
                    tab += '<td>'+item.Parametro+'</td>'
                    tab += '<td>'+item.Asignado+'</td>'
                    tab += '<td>'+item.Liberado+'</td>'
                    tab += '<td>'
                    tab +='     <button class="btn-info" id="btnBitacora"><i class="voyager-download"></i></button>'
                    tab +='     <button class="btn-info" id="btnEditarBitacora"><i class="voyager-edit"></i></button>'
                    tab += '</td>'
                    tab += '</tr>'
                })
                tab += '    </tbody>'
                tab += '</table>'
                tabla.innerHTML = tab;
    
                tableLote = $('#tabLote').DataTable({        
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
    }else{
        alert("Para buscar un lote tienes que seleccionar un parametro y una fecha")
    }
}
function getPendientes()
{ 
    let tabla = document.getElementById('divPendientes');
    let tab = '';
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/"+area+"/getPendientes",
        data: {
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            model = response.model
            tab += '<table class="table table-sm" style="font-size:10px" id="tablePendientes">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>Folio</th>';
            tab += '          <th>Parametro</th>';
            tab += '          <th>Fecha recepción</th>';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            for (let i = 0; i < model.length; i++) {
                tab += '<tr>';
                tab += '<td>'+model[i][0]+'</td>';
                tab += '<td>'+model[i][1]+'</td>';
                tab += '<td>'+model[i][2]+'</td>';
                tab += '</tr>';   
            }
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;

            table = $('#tablePendientes').DataTable({        
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