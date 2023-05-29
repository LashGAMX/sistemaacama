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
    $('#divSummer').summernote({
        placeholder: '', 
        tabsize: 2,
        height: 300,

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
    $('#btnAsignarMuestra').click(function(){
        setMuestraLote()
    }); 

});
 //todo Variables globales
var tableLote
var idLote
var idMuestra
 //todo funciones
 function getDetalleLote()
 {
    $("#modalDetalleLote").modal("show")
 }
 function getCapturaLote()
 {
    let tabla = document.getElementById('divCaptura');
    let tab = '';
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/"+area+"/getCapturaLote",
        data: {
            idLote:idLote,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            tab += '<table id="tabCaptura" class="table table-sm">';
            tab += '    <thead>';
            tab += '        <tr>';
            tab += '          <th>Opc</th>';
            tab += '          <th>Folio</th>';
            tab += '          <th>Norma</th>';
            tab += '          <th>Resultado</th>';
            tab += '          <th>Observación</th>';
            tab += '        </tr>';
            tab += '    </thead>'; 
            tab += '    <tbody>';
            $.each(response.model, function (key, item) {
                if (item.Liberado != 1) {
                    status = "";
                    color = "success";
                } else { 
                    status = "disabled";
                    color = "warning"
                }
                tab += '<tr>';
                tab += '<td><input hidden id="idMuestra'+item.Id_detalle+'" value="'+item.Id_detalle+'"><button '+status+' type="button" class="btn btn-'+color+'" onclick="getDetalleEspectro('+item.Id_detalle+');" data-toggle="modal" data-target="#modalCaptura">Capturar</button>';
                if (item.Id_control != 1) 
                {
                    tab += '<br> <small class="text-danger">'+item.Control+'</small></td>';
                }else{
                    tab += '<br> <small class="text-info">'+item.Control+'</small></td>';
                }
                tab += '<td><input disabled style="width: 100px" value="'+item.Folio_servicio+'"></td>';
                tab += '<td><input disabled style="width: 200px" value="'+item.Clave_norma+'"></td>';
                if(item.Resultado != null){
                    tab += '<td><input disabled style="width: 100px" value="'+item.Resultado+'"></td>';
                }else{
                    tab += '<td><input disabled style="width: 80px" value=""></td>';
                }
                if(item.Observacion != null){
                    tab += '<td>'+item.Observacion+'</td>';
                }else{
                    tab += '<td></td>';
                }
                tab += '</tr>';

            }); 
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;

            var t2 = $('#tabCaptura').DataTable({
                "ordering": false,
                paging: false,
                "language": {
                    "lengthMenu": "# _MENU_ por pagina",
                    "zeroRecords": "No hay datos encontrados",
                    "info": "Pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay datos encontrados",
                }
            });


            $('#tabCaptura tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                }
                else {
                    t2.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            });
            $('#tabCaptura tr').on('click', function () {
                let dato = $(this).find('td:first');
                idMuestra = dato[0].firstElementChild.value;
            });

        }
    });
 }
 function setMuestraLote()
 {
     let muestra = document.getElementsByName("stdCkAsignar")
    let codigos = new Array();
    for(let i = 0; i < muestra.length;i++){
        if (muestra[i].checked) {
            codigos.push(muestra[i].value);
        }
    }
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/"+area+"/setMuestraLote",
        data: {
            codigos:codigos,
            idLote:idLote,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            alert("Muestras asignadas")
            getMuestraSinAsignar()
            getLote()
        }
    });
 }
 function getMuestraSinAsignar()
 {
    let tabla = document.getElementById('devAsignarLote');
    let tab = '';
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/"+area+"/getMuestraSinAsignar",
        data: {
            idLote:idLote,
            idParametro: $("#parametro").val(),
            fecha:$("#fechaAsignar").val(),
            _token: $('input[name="_token"]').val(), 
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response)
            $("#tipoFormulaAsignar").val(response.lote.Tipo_formula)
            $("#parametroAsignar").val(response.lote.Parametro)
            $("#fechaAnalisisAsignar").val(response.lote.Fecha)
            $("#asignadoLote").val(response.lote.Asignado)
            $("#liberadoLote").val(response.lote.Liberado)
            tab += '<table id="tabAsignar" class="table table-sm">'
            tab += '    <thead>'
            tab += '        <tr>'
            tab += '          <th>Opc</th>'
            tab += '          <th># Muestra</th> '
            tab += '          <th>Norma</th> '
            tab += '          <th>Punto muestreo</th> '
            tab += '          <th>Fecha recepción</th> '
            tab += '        </tr>'
            tab += '    </thead>'
            tab += '    <tbody>'
            for (let i = 0; i < response.model.length; i++) {
                tab += '<tr>'
                tab += '<td><input type="checkbox" value="'+response.model[i].Id_codigo+'" name="stdCkAsignar"></td>'
                tab += '<td>'+response.folio[i]+'</td>'
                tab += '<td>'+response.norma[i]+'</td>'
                tab += '<td>'+response.punto[i]+'</td>'
                tab += '<td>'+response.fecha[i]+'</td>'
                tab += '</tr>'        
            }
        
            tab += '    </tbody>'
            tab += '</table>'
            tabla.innerHTML = tab;

            //Inicializacion de tabla
            tableLote = $('#tabAsignar').DataTable({        
                "ordering": false,
                paging: false,
                "language": {
                    "lengthMenu": "# _MENU_ por pagina",
                    "zeroRecords": "No hay datos encontrados",
                    "info": "Pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay datos encontrados",
                },
                dom: '<"toolbar">frtip',
            });
            $('div.toolbar').html('<button onclick="setMuestraLote()" id="btnAsignarMuestra" class="btn-info"><i class="fas fa-paper-plane"></i></button>');
        }
    });
 }
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
                if (response.model.length > 0) {
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
                        tab +='     <button class="btn-info" id="btnBitacora"><i class="voyager-download"></i></button><br>'
                        tab +='     <button onclick="getDetalleLote()" class="btn-info" id="btnEditarBitacora"><i class="voyager-edit"></i></button>'
                        tab += '</td>'
                        tab += '</tr>'
                    })
                    tab += '    </tbody>'
                    tab += '</table>'
                    tabla.innerHTML = tab;
        
                    //Inicializacion de tabla
                    tableLote = $('#tabLote').DataTable({        
                        "ordering": false,
                        "language": {
                            "lengthMenu": "# _MENU_ por pagina",
                            "zeroRecords": "No hay datos encontrados",
                            "info": "Pagina _PAGE_ de _PAGES_",
                            "infoEmpty": "No hay datos encontrados",
                        }
                    });
                    //Funcion de seleccionar
                    $('#tabLote tbody').on('click', 'tr', function () {
                        if ($(this).hasClass('selected')) {
                            $(this).removeClass('selected');
                        } else {
                            tableLote.$('tr.selected').removeClass('selected');
                            $(this).addClass('selected');
                            idLote = $(this).children(':first').html();
                            getCapturaLote()

                        }
                    });
                    $('#tabLote tbody').on('dblclick', 'tr', function () {
                        $("#modalAsignar").modal("show") 
                        idLote = $(this).children(':first').html();
                        getMuestraSinAsignar()
                    });
                } else {
                    alert("No hay lotes en esta fecha")
                }
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