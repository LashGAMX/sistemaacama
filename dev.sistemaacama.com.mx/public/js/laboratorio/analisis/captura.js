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
    // $('#divSummer').summernote({
    //     placeholder: '', 
    //     tabsize: 2,
    //     height: 300,

    //   });

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
    $('#btnEjecutar').click(function(){
        setDetalleMuestra()
    }); 
});

 //todo Variables globales
var tableLote
var idLote
var idMuestra
var idMuestra = 0
var idArea = 0
 //todo funciones
 function exportBitacora(id)
 {
    window.open(base_url+"/admin/laboratorio/" + area + "/bitacora/impresion/"+id);       
 }
 function getDetalleLote(id,parametro)
 {
    $("#modalDetalleLote").modal("show")
    $("#tituloLote").val(id+' - '+parametro)
    let summer = document.getElementById("divSummer")
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getDetalleLote",
        data: {
            id:id,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response); 
            $("#tituloBit").val(response.plantilla[0].Titulo)
            $("#revBit").val(response.plantilla[0].Rev)
            summer.innerHTML = '<div id="summernote">'+response.plantilla[0].Texto+'</div>';

            $('#summernote').summernote({
                placeholder: '', 
                tabsize: 2,
                height: 300,         
            }); 
        }
    });
 }
 function setDetalleMuestra()
 {
    switch (parseInt(idArea)) {
        case 16: // Espectofotometria
                switch (parseInt($('#parametro').val())) {
                    case 152: // COT
                        $.ajax({
                            type: "POST",
                            url: base_url + "/admin/laboratorio/" + area + "/setDetalleMuestra",
                            data: {
                                idLote:idLote,
                                idMuestra: idMuestra,
                                parametro: $('#parametro').val(),
                                ABS:$('#abs1COT').val(),
                                CA:$('#blanco1COT').val(),
                                CB:$('#b1COT').val(),
                                CM:$('#m1COT').val(),
                                CR:$('#r1COT').val(),
                                D:$('#fDilucion1COT').val(),
                                E:$('#volMuestra1COT').val(),
                                X:$('#abs11COT').val(),
                                Y:$('#abs21COT').val(),
                                Z:$('#abs31COT').val(),
                            
                                _token: $('input[name="_token"]').val()
                            },
                            dataType: "json",
                            success: function (response) {
                                console.log(response);
                                if (response.idControl == 5){
                                    $("#resultadoCOT").val(response.model.Resultado); 
                                } else {
                                    $("#abs1COT").val(response.model.Promedio.toFixed(3)); 
                                    $("#abs2COT").val(response.model.Promedio.toFixed(3)); 
                                    $("#resultadoCOT").val(response.model.Resultado.toFixed(3)); 
                                    $("#fDilucion1COT").val(response.model.Vol_dilucion.toFixed(3));
                                    $("#fDilucion2COT").val(response.model.Vol_dilucion.toFixed(3));

                                } 
                            }
                        });
                        break;
                    default:
                        break;
                }
            break;
    
        default:
            break;
    }

 }
 function getDetalleMuestra(id)
 {
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/"+area+"/getDetalleMuestra",
        data: {
            id:$("#idMuestra"+id).val(),
            idLote:idLote,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response)
            
            switch (parseInt(response.lote[0].Id_area)) {
                case 16: // Espectrofotometria 
                    switch (parseInt(response.lote[0].Id_tecnica)) { 
                        case 152: // COT
                                 $("#observacion").val(response.model.Observacion);
                                 $("#abs1COT").val(response.model.Promedio);
                                 $("#abs2COT").val(response.model.Promedio);
                                 $("#idMuestra").val(id);
                                 if (response.blanco != null) {
                                    $("#blanco1COT").val(response.blanco.Resultado);
                                    $("#blanco2COT").val(response.blanco.Resultado);  
                                 } 
                                 $("#b1COT").val(response.curva.B);
                                 $("#m1COT").val(response.curva.M);
                                 $("#r1COT").val(response.curva.R);
                                 $("#b2COT").val(response.curva.B);
                                 $("#m2COT").val(response.curva.M);
                                 $("#rCOT2").val(response.curva.R);
                                 $("#volMuestra1COT").val(response.model.Vol_muestra);
                                 $("#volMuestra2COT").val(response.model.Vol_muestra);
                                 $("#abs11COT").val(response.model.Abs1);
                                 $("#abs21COT").val(response.model.Abs2);
                                 $("#abs31COT").val(response.model.Abs3);
                                 $("#resultadoCOT").val(response.model.Resultado);
                            break;
                        default:
                            break;
                    }
                    break;
                case 5: // Fisicoquimicos
                
                break;
                default:

                break;
            }
        }
    });
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

                switch (parseInt(response.lote[0].Id_area)) {
                    case 16: // Espectrofotometria 
                        switch (parseInt(item.Id_parametro)) { 
                            case 152:
                                tab += '<td><input hidden id="idMuestra'+item.Id_detalle+'" value="'+item.Id_detalle+'"><button '+status+' type="button" class="btn btn-'+color+'" onclick="getDetalleMuestra('+item.Id_detalle+');" data-toggle="modal" data-target="#modalCapturaCOT">Capturar</button>';
                                break;
                            default:
                                break;
                        }
                        break;
                    case 5: // Fisicoquimicos
                    
                    break;
                    default:

                    break;
                }
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
    if ($("#parametro").val() != "0" ) {
        let tabla = document.getElementById('divLote');
        let tab = '';
        $.ajax({ 
            type: 'POST',
            url: base_url + "/admin/laboratorio/"+area+"/getLote",
            data: {
                id:$("#parametro").val(),
                fecha:$("#fechaLote").val(),
                folio:$("#folio").val(), 
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
                    tab +='     <button class="btn-info" onclick="exportBitacora('+item.Id_lote+')"><i class="voyager-download"></i></button><br>'
                    tab +='     <button onclick="getDetalleLote('+item.Id_lote+',\''+item.Parametro+'\')" class="btn-info" id="btnEditarBitacora"><i class="voyager-edit"></i></button>'
                    tab += '</td>' 
                    tab += '</tr>'
                })
                tab += '    </tbody>'
                tab += '</table>'
                tabla.innerHTML = tab
                idArea = response.model[0].Id_area
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
                if (response.model.length > 0) {

                } else {
                    alert("No hay lotes en esta fecha")
                    idLote = 0
                    getCapturaLote()
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