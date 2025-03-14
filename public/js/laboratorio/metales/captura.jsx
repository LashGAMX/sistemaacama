var area = "metales"
var numMuestras = new Array();
var idMuestra = 0; 
var idLote = 0;
var tecnica = 0;
$(document).ready(function () {
    $('#formulaTipo').select2();
    $('.select2').select2();
    
});
document.addEventListener("keydown", function(event) {
    if (event.altKey && event.code === "KeyR")
    {
        operacion();
    }
    if (event.altKey && event.code === "KeyB"){
        getDataCaptura()
    }
    if (event.altKey && event.code === "KeyL"){
        liberarMuestraMetal()
        // liberarTodo()
    }
    if (event.altKey && event.code === "KeyK"){
        liberarTodo()
    }
});

$("#formulaTipo").click(function () {
    $("#resDato").val("");
});
$('#enviarObservacion').click(function () {
    enviarObservacion();
    console.log("observacion")
    swal("Observación enviada!", "Enviado!", "success");
});

$('#btnGenerarControles').click(function(){
    createControlCalidadMetales();
});

$('#ejecutar').click(function(){
    operacion();
});
$('#btnEjecutarTodo').click(function(){
    setEjecutarTodo();
});
$('#btnLiberar').click(function(){
    // operacion();
    liberarMuestraMetal();
});
$('#btnLiberarTodo').click(function () {
    liberarTodo();
});
$('#btnEliminarControl').click(function () {
    eliminarControl();
});
$('#btnDesliberar').click(function () {
    desliberarControl();
});
$('#btnHistorial').click(function () {
    console.log("Boton de historial")
    getHistorial();
});
function eliminarControl()
{
    if (confirm("Estas seguro de eliminar este control?")) {
        $.ajax({
            type: "POST",
            url: base_url + "/admin/laboratorio/" + area + "/eliminarContro",
            data: { 
                idMuestra: idMuestra,
                _token: $('input[name="_token"]').val()
            },
            dataType: "json",
            success: function (response) {
                console.log(response);
                alert(response.msg)
            }
        });
    }
    
}
function desliberarControl()
{
    if (confirm("Estas seguro de desliberar este control?")) {
        $.ajax({
            type: "POST",
            url: base_url + "/admin/laboratorio/" + area + "/desliberarControl",
            data: { 
                idMuestra: idMuestra,
                _token: $('input[name="_token"]').val()
            },
            dataType: "json",
            success: function (response) {
                console.log(response);
                alert(response.msg)
                $('#tablaControles tr').each(function () {
                    // Selecciona todos los inputs dentro de este tr y quita el atributo 'disabled'
                    $(this).find('input').removeAttr('disabled');
                });
            }
        });
    }
    
}
function getUltimoLote()
{
    let tabla = document.getElementById('divUltimoLote');
    let tab = '';
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getUltimoLote",
        data: {
            id: $("#formulaTipo").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            tab += 'Fec. Ult. Lote: '+response.model.Fecha
            tabla.innerHTML = tab
        }
    });
}
function setTituloBit(id)
{
    if (confirm("Estas seguro de editar el titulo")) {
        $.ajax({
            type: "POST",
            url: base_url + "/admin/laboratorio/" + area + "/setTituloBit",
            data: {
                id: id,
                _token: $('input[name="_token"]').val()
            },
            dataType: "json",
            success: function (response) {
                console.log(response);
                alert("Titulo corregido")
            }
        });
    }
}
function getHistorial(id)
{
    console.log("Get Historial" + id);
    let tabla1 = document.getElementById('divTablaHist');
    let tabla2 = document.getElementById('divTablaCodigos');
    let tab1 = ''
    let tab2 = ''

  
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getHistorial",
        data: {
            idLote: idLote,
            idCodigo:id,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            tab1 += `
                <table id="tablaLoteModal" class="table table-sm">
                    <thead class="thead-dark">
                        <tr>
                            <th>Id lote</th>
                            <th>Fecha lote</th>
                            <th>Codigo</th>
                            <th>Parametro</th>
                            <th>Resultado</th>
                    
                        </tr>
                    </thead>
                    <tbody>
                            ${
                                $.map(response.idsLotes, function (item, index) {
                                    if (parseInt(response.historialHist[index]) === 0) {
                                        return ''; // No devuelve nada si historialHist[index] es 0
                                    }
                                
                                    let estilo = parseInt(response.historialHist[index]) === 1 ? 'background-color:#e5e5ff;' : '';
                                
                                    return `
                                        <tr>
                                            <td>${item}</td>
                                            <td style="${estilo}">${response.fechaLote[index]}</td>
                                            <td style="${estilo}">${response.Codigohist[index]}</td>
                                            <td style="${estilo}">${response.parametrohist[index]}</td>
                                            <td style="${estilo}">${response.resultadoHist[index]}</td>
                                        </tr>
                                    `;
                                }).join('')
                            }
                    </tbody>
                 </table>
            `

            tab2 = `
                    <table id="tablaCodigosHistorial" class="table table-sm">
                        <thead>
                            <tr>
                                <th>Id_codigo</th>
                                <th>Codigo</th>
                                <th>Parametro</th>
                                <th>Resultado Ejec.</th>
                                <th>Resultado Lib.</th>
                                <th>Analizo</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            ${
                                $.map(response.model, function (item){
                                    let estilo2 = item.Liberado == 1 ? 'bg-success' : '';
                                    return `
                                        <tr>
                                        
                                            <td class="${estilo2}">${item.Id_codigo}</td>
                                            <td class="${estilo2}">${item.Codigo}</td>
                                            <td class="${estilo2}">${item.Parametro} (${item.Tipo_formula})</td>
                                            <td class="${estilo2}">${item.Resultado != null ? item.Resultado : ''}</td>
                                            <td class="${estilo2}">${item.Resultado2 != null ? item.Resultado2 : ''}</td>
                                            <td class="${estilo2}">${item.Analizo != 1 ? item.name : ''}</td>
                                            
                                        </tr>
                                    `;
                                }).join('') 
                            }
                        </tbody>

                    </table>
                 `

            tabla1.innerHTML = tab1
            tabla2.innerHTML= tab2

            
            var t2 = $('#tablaCodigosHistorial').DataTable({
                "ordering": false,
                paging: false,
                scrollY: '300px',
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
function getDataCaptura() {
    console.log("getDataCaptura")
    cleanTable(); 
    numMuestras = new Array();
    let tabla = document.getElementById('divLote');
    let tab = '';


        $.ajax({ 
            type: "POST",
            url: base_url + "/admin/laboratorio/"+area+"/getCapturaLote",
            data: {
                folio: $("#folio").val(),
                formulaTipo: $("#formulaTipo").val(), 
                fechaAnalisis: $("#fechaAnalisis").val(),
                _token: $('input[name="_token"]').val()
            },
            dataType: "json",
            success: function (response) {            
                console.log(response);

                tab += '<table id="tablaLote" class="table table-sm">';
                tab += '    <thead class="thead-dark">';
                tab += '        <tr>';
                tab += '          <th>Lote2</th>';
                tab += '          <th>Tipo formula</th>';
                tab += '          <th>Fecha lote</th> ';
                tab += '          <th>Total asignado</th> ';
                tab += '          <th>Total liberados</th> ';
                tab += '          <th>Opc</th> ';
                tab += '        </tr>';
                tab += '    </thead>';
                tab += '    <tbody>';
                $.each(response.lote, function (key, item) {
                    tab += '<tr>';
                    tab += '<td>'+item.Id_lote+'</td>';
                    tab += '<td>('+item.Id_tecnica+') '+item.Parametro+' ('+item.Tipo_formula+')</td>';
                    tab += '<td>'+item.Fecha+'</td>';
                    tab += '<td>'+item.Asignado+'</td>';
                    tab += '<td>'+item.Liberado+'</td>';
                    tab += '<td><button class="btn btn-danger" id="btnrefresch" onclick="update('+item.Id_lote+');"><i class="fas fa-file-download"></i></button><button class="btn btn-success" id="btnImprimir" onclick="imprimir('+item.Id_lote+');"><i class="fas fa-file-download"></i></button> <button class="btn-info" onclick="setTituloBit('+item.Id_lote+')"><i class="fas fa-file"></i></button></td>';
                    tab += '</tr>';
                }); 
                tab += '    </tbody>';
                tab += '</table>';
                tabla.innerHTML = tab;

                // tecnica = response.tecnica;

                if(response.curva == null ){
                    alert("Este lote no tiene Curva de calibración")
                }
                $("#b").val(response.curva.B);
                $("#m").val(response.curva.M);
                $("#r").val(response.curva.R);


                var t = $('#tablaLote').DataTable({        
                    "ordering": false, 
                    "language": {
                        "lengthMenu": "# _MENU_ por pagina",
                        "zeroRecords": "No hay datos encontrados", 
                        "info": "Pagina _PAGE_ de _PAGES_",
                        "infoEmpty": "No hay datos encontrados",
                    }
                });


                $('#tablaLote tbody').on( 'click', 'tr', function () {
                    if ( $(this).hasClass('selected') ) {
                        $(this).removeClass('selected');
                    }
                    else {
                        t.$('tr.selected').removeClass('selected');
                        $(this).addClass('selected');
                    }
                } );
                $('#tablaLote tr').on('click', function(){
                    let dato = $(this).find('td:first').html();
                    idLote = dato;
                    // getLoteCapturaVol();
                    getLoteCaptura()
                    // console.log("Lote")
                  });
            }
        });
}
var numMuestras = new Array();
var trTab;
function getLoteCaptura() {

    // console.log("getLoteCaptura")

    numMuestras = new Array();
    idMuestra = 0;
    let tabla = document.getElementById('divTablaControles');
    let tab = '';
    let cont = 1;
 
    let status = "";
    let hg = "hidden"
    let volF = "hidden"


    $.ajax({ 
        type: "POST",
        url: base_url + "/admin/laboratorio/"+area+"/getLoteCaptura",
        data: {
            idLote: idLote,
            formulaTipo: $("#formulaTipo").val(), 
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {            
            console.log(response);

                       let aux = 0
            
            switch (parseInt(response.detalle[0].Id_parametro)) {
                //NMX  FLAMA
                case 20: 
                case 21:
                case 23:
                case 18:
                case 24:
                case 25:
                case 232:
                case 187:
                case 226:
                case 197:
                case 351:
                case 41:
                case 354:
                case 353:
                case 55:
                    tab += '<table id="tablaControles" class="table display compact">';
                    tab += '    <thead>';
                    tab += '        <tr>';
                    tab += '          <th>Muestra</th>';
                    tab += '          <th>Cliente</th>';
                    tab += '          <th>Punto</th>';
                    tab += '          <th>Vol. Muestra E</th>';
                    tab += '          <th>Abs1</th>';
                    tab += '          <th>Abs2</th>';
                    tab += '          <th>Abs3</th>';
                    tab += '          <th>Absorbancia Prom.</th>';
                    tab += '          <th>Factor dilución D</th>';
                    tab += '          <th>Resultado</th>';
                    tab += '          <th>Observacion</th>';
                    tab += '          <th>*</th>';
                    tab += '        </tr>'
                    tab += '    </thead>';
                    tab += '    <tbody>';
                    $.each(response.detalle, function (key, item) {
                        tab += '<tr>';
                        if (parseInt(item.Liberado) == 0) {
                            status = "";
                        } else { 
                            status = "disabled";
                        }
                        
                        tab += '<td><input style="width: 80px" hidden id="idDetalle'+item.Id_detalle+'" value="'+item.Id_detalle+'">'+item.Folio_servicio;
                        if (item.Id_control != 1) 
                        {
                            tab += '<br> <small class="text-danger">'+item.Control+'</small></td>';
                        }else{
                            tab += '<br> <small class="text-info">'+item.Control+'</small></td>';
                        }
                        tab += '<td>'+item.Empresa_suc+'</td>';
                        tab += '<td><textarea '+status+' style="width: 100px;height: 60px" > '+response.punto[aux]+'</textarea></td>';   
                        tab += '<td><input '+status+' style="width: 80px" id="volMuestra'+item.Id_detalle+'" value="'+item.Vol_muestra+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="abs1'+item.Id_detalle+'" value="'+item.Abs1+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="abs2'+item.Id_detalle+'" value="'+item.Abs2+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="abs3'+item.Id_detalle+'" value="'+item.Abs3+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="absPromedio'+item.Id_detalle+'" value="'+item.Abs_promedio+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="factorDilucion'+item.Id_detalle+'" value="'+item.Factor_dilucion+'"></td>';
                        if (item.Vol_disolucion != null) {
                            tab += '<td><input '+status+' style="width: 80px" id="VolDisolucion'+item.Id_detalle+'" value="'+item.Vol_disolucion+'"></td>';   
                        } else {
                            tab += '<td><input '+status+' style="width: 80px" id="VolDisolucion'+item.Id_detalle+'" value=""></td>';
                        }
                        if (item.Observacion == "" || item.Observacion == null) {
                            tab += '<td><textarea '+status+' style="width: 150px;height: 60px" id="obs'+item.Id_detalle+'" value="" > '+response.obs[aux]+'</textarea></td>';   
                        } else {
                            tab += '<td><textarea '+status+' style="width: 150px;height: 60px" id="obs'+item.Id_detalle+'" value="">'+item.Observacion+' </textarea></td>';
                        }
                        tab += '<td><button class="btn-info" onclick="getHistorial('+item.Id_codigo+')" data-toggle="modal" data-target="#modalHistorial"><i class="fas fa-lightbulb"></i></button> <button class="btn-info" onclick="getImagenMuestra(' +
                            item.Id_analisis +
                            ')" data-toggle="modal" data-target="#modalImgFoto"><i class="fas fa-image"></i></button></td>';
                        
                        tab += '</tr>';
                        numMuestras.push(item.Id_detalle);
                        cont++; 
                        aux++ 
                 
                    }); 
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                    // 051 HORNO DE GRAMIFOTO
                case 355:
                case 356:
                case 360:
                    tab += '<table id="tablaControles" class="table display compact">';
                    tab += '    <thead>';
                    tab += '        <tr>';
                    tab += '          <th>Muestra</th>';
                    tab += '          <th>Cliente</th>';
                    tab += '          <th>Punto</th>';
                    tab += '          <th>Vol. Muestra E</th>';
                    tab += '          <th>Abs1</th>';
                    tab += '          <th>Abs2</th>';
                    tab += '          <th>Abs3</th>';
                    tab += '          <th>Absorbancia Prom.</th>';
                    tab += '          <th>Factor dilución D</th>';
                    tab += '          <th>Factor conversion G</th>';
                    tab += '          <th>Resultado</th>';
                    tab += '          <th>Observacion</th>';
                    tab += '          <th>*</th>';
                    tab += '        </tr>'
                    tab += '    </thead>';
                    tab += '    <tbody>';
                    $.each(response.detalle, function (key, item) {
                        tab += '<tr>';
                        if (parseInt(item.Liberado) == 0) {
                            status = "";
                        } else { 
                            status = "disabled";
                        }
                        
                        tab += '<td><input style="width: 80px" hidden id="idDetalle'+item.Id_detalle+'" value="'+item.Id_detalle+'">'+item.Folio_servicio;
                        if (item.Id_control != 1) 
                        {
                            tab += '<br> <small class="text-danger">'+item.Control+'</small></td>';
                        }else{
                            tab += '<br> <small class="text-info">'+item.Control+'</small></td>';
                        }
                        tab += '<td>'+item.Empresa_suc+'</td>';
                        tab += '<td><textarea '+status+' style="width: 100px;height: 60px" > '+response.punto[aux]+'</textarea></td>';   
                        tab += '<td><input '+status+' style="width: 80px" id="volMuestra'+item.Id_detalle+'" value="'+item.Vol_muestra+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="abs1'+item.Id_detalle+'" value="'+item.Abs1+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="abs2'+item.Id_detalle+'" value="'+item.Abs2+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="abs3'+item.Id_detalle+'" value="'+item.Abs3+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="absPromedio'+item.Id_detalle+'" value="'+item.Abs_promedio+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="factorDilucion'+item.Id_detalle+'" value="'+item.Factor_dilucion+'"></td>';
                        
                        tab += '<td><input '+status+' style="width: 80px" id="factorConversion'+item.Id_detalle+'" value="'+item.Factor_conversion+'"></td>';
                        if (item.Vol_disolucion != null) {
                            tab += '<td><input '+status+' style="width: 80px" id="VolDisolucion'+item.Id_detalle+'" value="'+item.Vol_disolucion+'"></td>';   
                        } else {
                            tab += '<td><input '+status+' style="width: 80px" id="VolDisolucion'+item.Id_detalle+'" value=""></td>';
                        }
                        if (item.Observacion == "" || item.Observacion == null) {
                            tab += '<td><textarea '+status+' style="width: 150px;height: 60px" id="obs'+item.Id_detalle+'" value="" > '+response.obs[aux]+'</textarea></td>';   
                        } else {
                            tab += '<td><textarea '+status+' style="width: 150px;height: 60px" id="obs'+item.Id_detalle+'" value="">'+item.Observacion+' </textarea></td>';
                        }
                        tab += '<td><button class="btn-info" onclick="getHistorial('+item.Id_codigo+')" data-toggle="modal" data-target="#modalHistorial"><i class="fas fa-lightbulb"></i></button> <button class="btn-info" onclick="getImagenMuestra(' +
                        item.Id_analisis +
                        ')" data-toggle="modal" data-target="#modalImgFoto"><i class="fas fa-image"></i></button></td>';                        tab += '</tr>';
                        numMuestras.push(item.Id_detalle);
                        cont++; 
                        aux++ 
                 
                    }); 
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                    // Hidruros
                case 17:
                case 22:
                case 352:
                    tab += '<table id="tablaControles" class="table display compact">';
                    tab += '    <thead>';
                    tab += '        <tr>';
                    tab += '          <th>Muestra</th>';
                    tab += '          <th>Cliente</th>';
                    tab += '          <th>Punto</th>';
                    tab += '          <th>Vol. Muestra E</th>';
                    tab += '          <th>Abs1</th>';
                    tab += '          <th>Abs2</th>';
                    tab += '          <th>Abs3</th>';
                    tab += '          <th>Absorbancia Prom.</th>';
                    tab += '          <th>Factor dilución D</th>';
                    tab += '          <th>Resultado</th>';
                    tab += '          <th>Observacion</th>';
                    tab += '          <th>*</th>';
                    tab += '        </tr>'
                    tab += '    </thead>';
                    tab += '    <tbody>';
                    $.each(response.detalle, function (key, item) {
                        tab += '<tr>';
                        if (parseInt(item.Liberado) == 0) {
                            status = "";
                        } else { 
                            status = "disabled";
                        }
                        
                        tab += '<td><input style="width: 80px" hidden id="idDetalle'+item.Id_detalle+'" value="'+item.Id_detalle+'">'+item.Folio_servicio;
                        if (item.Id_control != 1) 
                        {
                            tab += '<br> <small class="text-danger">'+item.Control+'</small></td>';
                        }else{
                            tab += '<br> <small class="text-info">'+item.Control+'</small></td>';
                        }
                        tab += '<td>'+item.Empresa_suc+'</td>';
                        tab += '<td><textarea '+status+' style="width: 100px;height: 60px" > '+response.punto[aux]+'</textarea></td>';   
                        tab += '<td><input '+status+' style="width: 80px" id="volMuestra'+item.Id_detalle+'" value="'+item.Vol_muestra+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="abs1'+item.Id_detalle+'" value="'+item.Abs1+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="abs2'+item.Id_detalle+'" value="'+item.Abs2+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="abs3'+item.Id_detalle+'" value="'+item.Abs3+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="absPromedio'+item.Id_detalle+'" value="'+item.Abs_promedio+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="factorDilucion'+item.Id_detalle+'" value="'+item.Factor_dilucion+'"></td>';
                        if (item.Vol_disolucion != null) {
                            tab += '<td><input '+status+' style="width: 80px" id="VolDisolucion'+item.Id_detalle+'" value="'+item.Vol_disolucion+'"></td>';   
                        } else {
                            tab += '<td><input '+status+' style="width: 80px" id="VolDisolucion'+item.Id_detalle+'" value=""></td>';
                        }
                        if (item.Observacion == "" || item.Observacion == null) {
                            tab += '<td><textarea '+status+' style="width: 150px;height: 60px" id="obs'+item.Id_detalle+'" value="" > '+response.obs[aux]+'</textarea></td>';   
                        } else {
                            tab += '<td><textarea '+status+' style="width: 150px;height: 60px" id="obs'+item.Id_detalle+'" value="">'+item.Observacion+' </textarea></td>';
                        }
                        tab += '<td><button class="btn-info" onclick="getHistorial('+item.Id_codigo+')" data-toggle="modal" data-target="#modalHistorial"><i class="fas fa-lightbulb"></i></button> <button class="btn-info" onclick="getImagenMuestra(' +
                        item.Id_analisis +
                        ')" data-toggle="modal" data-target="#modalImgFoto"><i class="fas fa-image"></i></button></td>';                        tab += '</tr>';
                        numMuestras.push(item.Id_detalle);
                        cont++; 
                        aux++ 
                 
                    }); 
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                //Horno de grafito 127
                case 216:
                case 210:
                case 208:
                    tab += '<table id="tablaControles" class="table display compact">';
                    tab += '    <thead>';
                    tab += '        <tr>';
                    tab += '          <th>Muestra</th>';
                    tab += '          <th>Cliente</th>';
                    tab += '          <th>Punto</th>';
                    tab += '          <th>Vol. Muestra E</th>';
                    tab += '          <th>Vol Final</th>';
                    tab += '          <th>Abs1</th>';
                    tab += '          <th>Abs2</th>';
                    tab += '          <th>Abs3</th>';
                    tab += '          <th>Absorbancia Prom.</th>';
                    tab += '          <th>Factor dilución D</th>';
                    tab += '          <th>Factor conversion G</th>';
                    tab += '          <th>Resultado</th>';
                    tab += '          <th>Observacion</th>';
                    tab += '          <th>*</th>';
                    tab += '        </tr>'
                    tab += '    </thead>';
                    tab += '    <tbody>';
                    $.each(response.detalle, function (key, item) {
                        tab += '<tr>';
                        if (parseInt(item.Liberado) == 0) {
                            status = "";
                        } else { 
                            status = "disabled";
                        }
                        
                        tab += '<td><input style="width: 80px" hidden id="idDetalle'+item.Id_detalle+'" value="'+item.Id_detalle+'">'+item.Folio_servicio;
                        if (item.Id_control != 1) 
                        {
                            tab += '<br> <small class="text-danger">'+item.Control+'</small></td>';
                        }else{
                            tab += '<br> <small class="text-info">'+item.Control+'</small></td>';
                        }
                        tab += '<td>'+item.Empresa_suc+'</td>';
                        tab += '<td><textarea '+status+' style="width: 100px;height: 60px" > '+response.punto[aux]+'</textarea></td>';   
                        tab += '<td><input '+status+' style="width: 80px" id="volMuestra'+item.Id_detalle+'" value="'+item.Vol_muestra+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="volFinal'+item.Id_detalle+'" value="'+item.Vol_final+'" ></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="abs1'+item.Id_detalle+'" value="'+item.Abs1+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="abs2'+item.Id_detalle+'" value="'+item.Abs2+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="abs3'+item.Id_detalle+'" value="'+item.Abs3+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="absPromedio'+item.Id_detalle+'" value="'+item.Abs_promedio+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="factorDilucion'+item.Id_detalle+'" value="'+item.Factor_dilucion+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="factorConversion'+item.Id_detalle+'" value="'+item.Factor_conversion+'"></td>';
                        if (item.Vol_disolucion != null) {
                            tab += '<td><input '+status+' style="width: 80px" id="VolDisolucion'+item.Id_detalle+'" value="'+item.Vol_disolucion+'"></td>';   
                        } else {
                            tab += '<td><input '+status+' style="width: 80px" id="VolDisolucion'+item.Id_detalle+'" value=""></td>';
                        }
                        if (item.Observacion == "" || item.Observacion == null) {
                            tab += '<td><textarea '+status+' style="width: 150px;height: 60px" id="obs'+item.Id_detalle+'" value="" > '+response.obs[aux]+'</textarea></td>';   
                        } else {
                            tab += '<td><textarea '+status+' style="width: 150px;height: 60px" id="obs'+item.Id_detalle+'" value="">'+item.Observacion+' </textarea></td>';
                        }
                        tab += '<td><button class="btn-info" onclick="getHistorial('+item.Id_codigo+')" data-toggle="modal" data-target="#modalHistorial"><i class="fas fa-lightbulb"></i></button> <button class="btn-info" onclick="getImagenMuestra(' +
                        item.Id_analisis +
                        ')" data-toggle="modal" data-target="#modalImgFoto"><i class="fas fa-image"></i></button></td>';                        tab += '</tr>';
                        numMuestras.push(item.Id_detalle);
                        cont++; 
                        aux++ 
                 
                    }); 
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                    // 201 Flama
                case 191:
                case 194:
                case 189:
                    tab += '<table id="tablaControles" class="table display compact">';
                    tab += '    <thead>';
                    tab += '        <tr>';
                    tab += '          <th>Muestra</th>';
                    tab += '          <th>Cliente</th>';
                    tab += '          <th>Punto</th>';
                    tab += '          <th>Vol. Muestra E</th>';
                    tab += '          <th>Vol Final</th>';
                    tab += '          <th>Abs1</th>';
                    tab += '          <th>Abs2</th>';
                    tab += '          <th>Abs3</th>';
                    tab += '          <th>Absorbancia Prom.</th>';
                    tab += '          <th>Factor dilución D</th>';
                    tab += '          <th>Resultado</th>';
                    tab += '          <th>Observacion</th>';
                    tab += '          <th>*</th>';
                    tab += '        </tr>'
                    tab += '    </thead>';
                    tab += '    <tbody>';
                    $.each(response.detalle, function (key, item) {
                        tab += '<tr>';
                        if (parseInt(item.Liberado) == 0) {
                            status = "";
                        } else { 
                            status = "disabled";
                        }
                        
                        tab += '<td><input style="width: 80px" hidden id="idDetalle'+item.Id_detalle+'" value="'+item.Id_detalle+'">'+item.Folio_servicio;
                        if (item.Id_control != 1) 
                        {
                            tab += '<br> <small class="text-danger">'+item.Control+'</small></td>';
                        }else{
                            tab += '<br> <small class="text-info">'+item.Control+'</small></td>';
                        }
                        tab += '<td>'+item.Empresa_suc+'</td>';
                        tab += '<td><textarea '+status+' style="width: 100px;height: 60px" > '+response.punto[aux]+'</textarea></td>';   
                        tab += '<td><input '+status+' style="width: 80px" id="volMuestra'+item.Id_detalle+'" value="'+item.Vol_muestra+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="volFinal'+item.Id_detalle+'" value="'+item.Vol_final+'" ></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="abs1'+item.Id_detalle+'" value="'+item.Abs1+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="abs2'+item.Id_detalle+'" value="'+item.Abs2+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="abs3'+item.Id_detalle+'" value="'+item.Abs3+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="absPromedio'+item.Id_detalle+'" value="'+item.Abs_promedio+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="factorDilucion'+item.Id_detalle+'" value="'+item.Factor_dilucion+'"></td>';
                        if (item.Vol_disolucion != null) {
                            tab += '<td><input '+status+' style="width: 80px" id="VolDisolucion'+item.Id_detalle+'" value="'+item.Vol_disolucion+'"></td>';   
                        } else {
                            tab += '<td><input '+status+' style="width: 80px" id="VolDisolucion'+item.Id_detalle+'" value=""></td>';
                        }
                        if (item.Observacion == "" || item.Observacion == null) {
                            tab += '<td><textarea '+status+' style="width: 150px;height: 60px" id="obs'+item.Id_detalle+'" value="" > '+response.obs[aux]+'</textarea></td>';   
                        } else {
                            tab += '<td><textarea '+status+' style="width: 150px;height: 60px" id="obs'+item.Id_detalle+'" value="">'+item.Observacion+' </textarea></td>';
                        }
                        tab += '<td><button class="btn-info" onclick="getHistorial('+item.Id_codigo+')" data-toggle="modal" data-target="#modalHistorial"><i class="fas fa-lightbulb"></i></button> <button class="btn-info" onclick="getImagenMuestra(' +
                        item.Id_analisis +
                        ')" data-toggle="modal" data-target="#modalImgFoto"><i class="fas fa-image"></i></button></td>';                        tab += '</tr>';
                        numMuestras.push(item.Id_detalle);
                        cont++; 
                        aux++ 
                 
                    }); 
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                    // 201 Horno de grafito
                case 192:
                case 204:
                case 190:
                case 196:
                    tab += '<table id="tablaControles" class="table display compact">';
                    tab += '    <thead>';
                    tab += '        <tr>';
                    tab += '          <th>Muestra</th>';
                    tab += '          <th>Cliente</th>';
                    tab += '          <th>Punto</th>';
                    tab += '          <th>Vol. Muestra E</th>';
                    tab += '          <th>Vol Final</th>';
                    tab += '          <th>Abs1</th>';
                    tab += '          <th>Abs2</th>';
                    tab += '          <th>Abs3</th>';
                    tab += '          <th>Absorbancia Prom.</th>';
                    tab += '          <th>Factor dilución D</th>';
                    tab += '          <th>Factor conversion G</th>';
                    tab += '          <th>Resultado</th>';
                    tab += '          <th>Observacion</th>';
                    tab += '          <th>*</th>';
                    tab += '        </tr>'
                    tab += '    </thead>';
                    tab += '    <tbody>';
                    $.each(response.detalle, function (key, item) {
                        tab += '<tr>';
                        if (parseInt(item.Liberado) == 0) {
                            status = "";
                        } else { 
                            status = "disabled";
                        }
                        
                        tab += '<td><input style="width: 80px" hidden id="idDetalle'+item.Id_detalle+'" value="'+item.Id_detalle+'">'+item.Folio_servicio;
                        if (item.Id_control != 1) 
                        {
                            tab += '<br> <small class="text-danger">'+item.Control+'</small></td>';
                        }else{
                            tab += '<br> <small class="text-info">'+item.Control+'</small></td>';
                        }
                        tab += '<td>'+item.Empresa_suc+'</td>';
                        tab += '<td><textarea '+status+' style="width: 100px;height: 60px" > '+response.punto[aux]+'</textarea></td>';   
                        tab += '<td><input '+status+' style="width: 80px" id="volMuestra'+item.Id_detalle+'" value="'+item.Vol_muestra+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="volFinal'+item.Id_detalle+'" value="'+item.Vol_final+'" ></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="abs1'+item.Id_detalle+'" value="'+item.Abs1+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="abs2'+item.Id_detalle+'" value="'+item.Abs2+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="abs3'+item.Id_detalle+'" value="'+item.Abs3+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="absPromedio'+item.Id_detalle+'" value="'+item.Abs_promedio+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="factorDilucion'+item.Id_detalle+'" value="'+item.Factor_dilucion+'"></td>';
                        
                        tab += '<td><input '+status+' style="width: 80px" id="factorConversion'+item.Id_detalle+'" value="'+item.Factor_conversion+'"></td>';
                        if (item.Vol_disolucion != null) {
                            tab += '<td><input '+status+' style="width: 80px" id="VolDisolucion'+item.Id_detalle+'" value="'+item.Vol_disolucion+'"></td>';   
                        } else {
                            tab += '<td><input '+status+' style="width: 80px" id="VolDisolucion'+item.Id_detalle+'" value=""></td>';
                        }
                        if (item.Observacion == "" || item.Observacion == null) {
                            tab += '<td><textarea '+status+' style="width: 150px;height: 60px" id="obs'+item.Id_detalle+'" value="" > '+response.obs[aux]+'</textarea></td>';   
                        } else {
                            tab += '<td><textarea '+status+' style="width: 150px;height: 60px" id="obs'+item.Id_detalle+'" value="">'+item.Observacion+' </textarea></td>';
                        }
                        tab += '<td><button class="btn-info" onclick="getHistorial('+item.Id_codigo+')" data-toggle="modal" data-target="#modalHistorial"><i class="fas fa-lightbulb"></i></button> <button class="btn-info" onclick="getImagenMuestra(' +
                        item.Id_analisis +
                        ')" data-toggle="modal" data-target="#modalImgFoto"><i class="fas fa-image"></i></button></td>';
                        tab += '</tr>';
                        numMuestras.push(item.Id_detalle);
                        cont++; 
                        aux++ 
                 
                    }); 
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                    // 201 Hidrudos
                case 188:
                case 219:
                case 195:
                case 230:
                case 215:
                    tab += '<table id="tablaControles" class="table display compact">';
                    tab += '    <thead>';
                    tab += '        <tr>';
                    tab += '          <th>Muestra</th>';
                    tab += '          <th>Cliente</th>';
                    tab += '          <th>Punto</th>';
                    tab += '          <th>Vol. Muestra E</th>';
                    tab += '          <th>Vol. Final</th>';
                    tab += '          <th>Abs1</th>';
                    tab += '          <th>Abs2</th>';
                    tab += '          <th>Abs3</th>';
                    tab += '          <th>Absorbancia Prom.</th>';
                    tab += '          <th>Factor dilución D</th>';
                    tab += '          <th>Factor conversion G</th>';
                    tab += '          <th>Resultado</th>';
                    tab += '          <th>Observacion</th>';
                    tab += '          <th>*</th>';
                    tab += '        </tr>'
                    tab += '    </thead>';
                    tab += '    <tbody>';
                    $.each(response.detalle, function (key, item) {
                        tab += '<tr>';
                        if (parseInt(item.Liberado) == 0) {
                            status = "";
                        } else { 
                            status = "disabled";
                        }
                        
                        tab += '<td><input style="width: 80px" hidden id="idDetalle'+item.Id_detalle+'" value="'+item.Id_detalle+'">'+item.Folio_servicio;
                        if (item.Id_control != 1) 
                        {
                            tab += '<br> <small class="text-danger">'+item.Control+'</small></td>';
                        }else{
                            tab += '<br> <small class="text-info">'+item.Control+'</small></td>';
                        }
                        tab += '<td>'+item.Empresa_suc+'</td>';
                        tab += '<td><textarea '+status+' style="width: 100px;height: 60px" > '+response.punto[aux]+'</textarea></td>';   
                        tab += '<td><input '+status+' style="width: 80px" id="volMuestra'+item.Id_detalle+'" value="'+item.Vol_muestra+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="volFinal'+item.Id_detalle+'" value="'+item.Vol_final+'" ></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="abs1'+item.Id_detalle+'" value="'+item.Abs1+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="abs2'+item.Id_detalle+'" value="'+item.Abs2+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="abs3'+item.Id_detalle+'" value="'+item.Abs3+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="absPromedio'+item.Id_detalle+'" value="'+item.Abs_promedio+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="factorDilucion'+item.Id_detalle+'" value="'+item.Factor_dilucion+'"></td>';
                        
                        tab += '<td><input '+status+'  style="width: 80px" id="factorConversion'+item.Id_detalle+'" value="'+item.Factor_conversion+'"></td>';
                        if (item.Vol_disolucion != null) {
                            tab += '<td><input '+status+' style="width: 80px" id="VolDisolucion'+item.Id_detalle+'" value="'+item.Vol_disolucion+'"></td>';   
                        } else {
                            tab += '<td><input '+status+' style="width: 80px" id="VolDisolucion'+item.Id_detalle+'" value=""></td>';
                        }
                        if (item.Observacion == "" || item.Observacion == null) {
                            tab += '<td><textarea '+status+' style="width: 150px;height: 60px" id="obs'+item.Id_detalle+'" value="" > '+response.obs[aux]+'</textarea></td>';   
                        } else {
                            tab += '<td><textarea '+status+' style="width: 150px;height: 60px" id="obs'+item.Id_detalle+'" value="">'+item.Observacion+' </textarea></td>';
                        }
                        tab += '<td><button class="btn-info" onclick="getHistorial('+item.Id_codigo+')" data-toggle="modal" data-target="#modalHistorial"><i class="fas fa-lightbulb"></i></button> <button class="btn-info" onclick="getImagenMuestra(' +
                        item.Id_analisis +
                        ')" data-toggle="modal" data-target="#modalImgFoto"><i class="fas fa-image"></i></button></td>';
                        tab += '</tr>';
                        numMuestras.push(item.Id_detalle);
                        cont++; 
                        aux++ 
                    }); 
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                default:
                    switch (parseInt(response.detalle[0].Id_parametro)) {
                        case 215:
                        case 195:
                        case 230:
                        case 188:
                        case 219:
                            hg = ""    
                            break;
                        case 192:
                        case 204:
                        case 190:
                        case 196:
                        case 191:
                        case 194:
                        case 189:
                        case 208:
                        case 216:
                        case 210:
                            volF = ""
                            break;
                        default:
                            break;
                    }
                    tab += '<table id="tablaControles" class="table display compact">';
                    tab += '    <thead>';
                    tab += '        <tr>';
                    // tab += '          <th><</th>';
                    tab += '          <th>Muestra</th>';
                    tab += '          <th>Cliente</th>';
                    tab += '          <th>Punto</th>';
                    //tab2 += '          <th>PuntoMuestreo</th>';
                    tab += '          <th>Vol. Muestra E</th>';
                    tab += '          <th '+volF+'>Vol Final</th>';
                    tab += '          <th '+hg+'>Vol. D</th>';
                    tab += '          <th>Abs1</th>';
                    tab += '          <th>Abs2</th>';
                    tab += '          <th>Abs3</th>';
                    tab += '          <th>Absorbancia Prom.</th>';
                    tab += '          <th>Factor dilución D</th>';
                    tab += '          <th>Factor conversion G</th>';
                    tab += '          <th>Resultado</th>';
                    tab += '          <th>Observacion</th>';
                    tab += '          <th>*</th>';
                    tab += '        </tr>'
                    tab += '    </thead>';
                    tab += '    <tbody>';
                    $.each(response.detalle, function (key, item) {
                        tab += '<tr>';
                        if (parseInt(item.Liberado) == 0) {
                            status = "";
                        } else { 
                            status = "disabled";
                        }
                        
                        tab += '<td><input style="width: 80px" hidden id="idDetalle'+item.Id_detalle+'" value="'+item.Id_detalle+'">'+item.Folio_servicio;
                        if (item.Id_control != 1) 
                        {
                            tab += '<br> <small class="text-danger">'+item.Control+'</small></td>';
                        }else{
                            tab += '<br> <small class="text-info">'+item.Control+'</small></td>';
                        }
                        tab += '<td>'+item.Empresa_suc+'</td>';
                        tab += '<td><textarea '+status+' style="width: 100px;height: 60px" > '+response.punto[aux]+'</textarea></td>';   
                        tab += '<td><input '+status+' style="width: 80px" id="volMuestra'+item.Id_detalle+'" value="'+item.Vol_muestra+'"></td>';
                        // tab += '<td '+hg+'><input '+status+' style="width: 80px" id="volDirigido'+item.Id_detalle+'" value="100"></td>';
                        tab += '<td '+volF+'><input '+status+' style="width: 80px" id="volFinal'+item.Id_detalle+'" value="'+item.Vol_final+'" ></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="abs1'+item.Id_detalle+'" value="'+item.Abs1+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="abs2'+item.Id_detalle+'" value="'+item.Abs2+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="abs3'+item.Id_detalle+'" value="'+item.Abs3+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="absPromedio'+item.Id_detalle+'" value="'+item.Abs_promedio+'"></td>';
                        tab += '<td><input '+status+' style="width: 80px" id="factorDilucion'+item.Id_detalle+'" value="'+item.Factor_dilucion+'"></td>';
                        
                        tab += '<td><input '+status+' style="width: 80px" id="factorConversion'+item.Id_detalle+'" value="'+item.Factor_conversion+'"></td>';
                        if (item.Vol_disolucion != null) {
                            tab += '<td><input '+status+' style="width: 80px" id="VolDisolucion'+item.Id_detalle+'" value="'+item.Vol_disolucion+'"></td>';   
                        } else {
                            tab += '<td><input '+status+' style="width: 80px" id="VolDisolucion'+item.Id_detalle+'" value=""></td>';
                        }
                        if (item.Observacion == "" || item.Observacion == null) {
                            tab += '<td><textarea '+status+' style="width: 150px;height: 60px" id="obs'+item.Id_detalle+'" value="" > '+response.obs[aux]+'</textarea></td>';   
                        } else {
                            tab += '<td><textarea '+status+' style="width: 150px;height: 60px" id="obs'+item.Id_detalle+'" value="">'+item.Observacion+' </textarea></td>';
                        }
                        tab += '<td><button class="btn-info" onclick="getHistorial('+item.Id_codigo+')" data-toggle="modal" data-target="#modalHistorial"><i class="fas fa-lightbulb"></i></button> <button class="btn-info" onclick="getImagenMuestra(' +
                        item.Id_analisis +
                        ')" data-toggle="modal" data-target="#modalImgFoto"><i class="fas fa-image"></i></button></td>';
                        tab += '</tr>';
                        numMuestras.push(item.Id_detalle);
                        cont++; 
                        aux++ 
                 
                    }); 
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
            }



            var t = $('#tablaControles').DataTable({
                "ordering": false,
                paging: false,
                scrollCollapse: true,
                scrollY: '380px',
                "language": {
                    "lengthMenu": "# _MENU_ por pagina",
                    "zeroRecords": "No hay datos encontrados",
                    "info": "Pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay datos encontrados",
                }
            });


            $('#tablaControles tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                }
                else {
                    t.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            });
            trTab = $('#tablaControles tr').on('click', function () {
                let dato = $(this).find('td:first');
                idMuestra = dato[0].firstElementChild.value;
                
                // Selecciona todos los inputs dentro del tr y quita el atributo 'disabled'
                // $(this).find('input').removeAttr('disabled');
                
                // console.log(idMuestra);
            });
        }
    });

}


//Función imprimir PDF
function imprimir(idLote){        
    window.open(base_url + "/admin/laboratorio/metales/exportPdfCaptura/"+idLote);
    //window.location = base_url + "/admin/laboratorio/captura/exportPdfCaptura/"+idLote;
}
function update(idLote) {
    console.log('Este es el lote a buscar:', idLote);

    $.ajax({
        url: base_url + "/admin/laboratorio/metales/refrescar",
        type: "POST",
        data: { 
            idLote: idLote 
        },
        success: function(response) {
            if (response.success) {
                console.log('Asignado:', response.Asignado);
                console.log('Liberado:', response.Liberado);

                $('#tablaLote').remove(); 
                $('#divLote').empty();  

                
                getDataCaptura(); 
            } else {
                console.error('Error en la respuesta:', response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error en la solicitud:', error);
        }
    });
}




function enviarObservacion(){
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/metales/enviarObservacion",
        data: {
            // fecha:$("#fechaAnalisis").val(),
            idParametro:$("#formulaTipo").val(),
            // idlote:idLote,
            idMuestra:$("#idDetalle"+idMuestra).val(),
            // ph:$("#ph").val(),
            // solidos:$("#solidos").val(),
            // olor: $("#olor").val(),
            // color: $("#color").val(),
            observacion:$("#observacion").val(),
            _token: $('input[name="_token"]').val()
        }, 
        dataType: "json",
        success: function (response) {            
            console.log(response);
            getLoteCaptura();
           
        }
    });
}
function setEjecutarTodo(){

    let ids = new Array()
    let voMuestras = new Array()
    let volFinals = new Array()
    let xs = new Array()
    let ys = new Array()
    let zs = new Array()
    let fds = new Array()
    let fcs = new Array()
    let obs = new Array()

    switch (parseInt($("#formulaTipo").val())) {
        //NMX  FLAMA
        case 20: 
        case 21:
        case 23:
        case 18:
        case 24:
        case 25:
        case 232:
        case 187:
        case 226:
        case 197:
        case 351:
        case 41:
        case 354:
        case 353:
        case 55:
     
            break;
            // 051 HORNO DE GRAMIFOTO
        case 355:
        case 356:
        case 360:
 
            break;
            // Hidruros
        case 17:
        case 22:
        case 352:
 
            break;
        //Horno de grafito 127
        case 216:
        case 210:
        case 208:
      
            break;
            // 201 Flama
        case 191:
        case 194:
        case 189:
            for (let i = 1; i < parseInt(trTab.length); i++) {
                ids.push(trTab[i].children[0].children[0].value)
                voMuestras.push(trTab[i].children[3].children[0].value)
                volFinals.push(trTab[i].children[4].children[0].value)
                xs.push(trTab[i].children[5].children[0].value)
                ys.push(trTab[i].children[6].children[0].value)
                zs.push(trTab[i].children[7].children[0].value)
                fds.push(trTab[i].children[9].children[0].value)
                fcs.push(trTab[i].children[10].children[0].value)
                obs.push(trTab[i].children[12].children[0].value)
               }
            break;
            // 201 Horno de grafito
        case 192:
        case 204:
        case 190:
        case 196:
            for (let i = 1; i < parseInt(trTab.length); i++) {
                ids.push(trTab[i].children[0].children[0].value)
                voMuestras.push(trTab[i].children[3].children[0].value)
                volFinals.push(trTab[i].children[4].children[0].value)
                xs.push(trTab[i].children[5].children[0].value)
                ys.push(trTab[i].children[6].children[0].value)
                zs.push(trTab[i].children[7].children[0].value)
                fds.push(trTab[i].children[9].children[0].value)
                fcs.push(trTab[i].children[10].children[0].value)
                obs.push(trTab[i].children[12].children[0].value)
               }
            break;
            // 201 Hidrudos
        case 188:
        case 219:
        case 195:
        case 230:
        case 215:
           for (let i = 1; i < parseInt(trTab.length); i++) {
            ids.push(trTab[i].children[0].children[0].value)
            voMuestras.push(trTab[i].children[3].children[0].value)
            volFinals.push(trTab[i].children[4].children[0].value)
            xs.push(trTab[i].children[5].children[0].value)
            ys.push(trTab[i].children[6].children[0].value)
            zs.push(trTab[i].children[7].children[0].value)
            fds.push(trTab[i].children[9].children[0].value)
            fcs.push(trTab[i].children[10].children[0].value)
            obs.push(trTab[i].children[12].children[0].value)
           }
            break;
        default:
           
       
            break;
    }
   
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/metales/setEjecutarTodo",
        data: {
            fecha:$("#fechaAnalisis").val(),
            idParametro:$("#formulaTipo").val(),
            idlote:idLote,
            ids:ids,
            voMuestras:voMuestras,
            volFinals:volFinals,
             xs:xs,
             ys:ys,
             zs:zs,
             fds:fds,
             fcs:fcs,
             obs:obs,
            _token: $('input[name="_token"]').val()
        }, 

        dataType: "json", 
        success: function (response) {     
            console.log(response)       
           alert(response.msg)
        }
    });
}
function operacion()
{ 

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/metales/operacion",
        data: {
            fecha:$("#fechaAnalisis").val(),
            idParametro:$("#formulaTipo").val(),
            idlote:idLote,
            idDetalle:$("#idDetalle"+idMuestra).val(),
            volMuestra:$("#volMuestra"+idMuestra).val(),
            // volDirigido:$("#volDirigido"+idMuestra).val(),
            volFinal:$("#volFinal"+idMuestra).val(),
             x:$("#abs1"+idMuestra).val(),
             y:$("#abs2"+idMuestra).val(),
             z:$("#abs3"+idMuestra).val(),
             FD:$("#factorDilucion"+idMuestra).val(),
             FC:$("#factorConversion"+idMuestra).val(),
             obs:$("#obs"+idMuestra).val(),
            _token: $('input[name="_token"]').val()
        }, 

        dataType: "json", 
        success: function (response) {            
            console.log(response);
            if (response.sw == true) {
                let fix = response.resultado.toFixed(3); 
                $("#absPromedio"+idMuestra).val(response.promedio);
                $("#VolDisolucion"+idMuestra).val(fix);
                $("#resDato").val(fix)   
            }
            
        }
    });
}
function createControlCalidadMetales()
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/createControlCalidadMetales",
        data: {
            idLote: idLote,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            getDataCaptura()
            getLoteCaptura()
            alert(response.msg)
        }
    });
}

function createControlCalidad()
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/createControlCalidad",
        data: {
            idMuestra: idMuestra,
            idLote: idLote,
            idControl: $("#controlCalidad").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            getLoteCaptura()
            alert(response.msg)
        }
    });
}

function generarControles()
{
    var ranCon = new Array();

    ranCon.push(random(0,numMuestras.length - 1));
    ranCon.push(random(0,numMuestras.length - 1));
    ranCon.push(random(0,numMuestras.length - 1));
    ranCon.push(random(0,numMuestras.length - 1));
    ranCon.push(random(0,numMuestras.length - 1));

    let tabla2 = document.getElementById('divTablaControles');
    let tab2 = '';

    let cont = 1;

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/metales/setControlCalidad",
        data: {
            ranCon:ranCon,
            numMuestra:numMuestras, 
            _token: $('input[name="_token"]').val()
        }, 
        dataType: "json",
        success: function (response) {            
            console.log(response);
    
            tab2 += '<table id="tablaControles" class="table table-sm">';
            tab2 += '    <thead class="">';
            tab2 += '        <tr>';
            tab2 += '          <th>NumMuestra</th>';
            tab2 += '          <th>NomCliente</th>';
            //tab2 += '          <th>PuntoMuestreo</th>';
            tab2 += '          <th>Vol. Muestra E</th>';
            tab2 += '          <th>Abs1</th>';
            tab2 += '          <th>Abs1</th>';
            tab2 += '          <th>Abs1</th>';
            tab2 += '          <th>Absorción promedio</th>';
            tab2 += '          <th>Factor dilución D</th>';
            tab2 += '          <th>Factor conversion G</th>';
            tab2 += '          <th>Resultado</th>';
            tab2 += '        </tr>';
            tab2 += '    </thead>';
            tab2 += '    <tbody>';
            $.each(response.detalle, function (key, item) {
                tab2 += '<tr>';
                tab2 += '<input style="width: 80px" hidden id="idDetalle'+cont+'" value="'+item.Id_detalle+'">';
                tab2 += '<td>'+item.Folio_servicio+'</td>';
                if(item.Descripcion != 'Resultado')
                    {
                        tab2 += '<td>'+item.Empresa+' <br> <small class="text-danger"><strong>'+item.Descripcion+'/</strong></small></td>';
                    }else{
                        tab2 += '<td>'+item.Empresa+' <br> <small class="text-info"><strong>'+item.Descripcion+'/</strong></small></td>';
                    }
                    if(item.Liberado != 0){
                        tab2 += '<td><input disabled style="width: 80px" id="volMuestra'+cont+'" value="50"></td>';
                        tab2 += '<td><input disabled style="width: 80px" id="abs1'+cont+'" value="'+item.Abs1+'"></td>';
                        tab2 += '<td><input disabled style="width: 80px" id="abs2'+cont+'" value="'+item.Abs2+'"></td>';
                        tab2 += '<td><input disabled style="width: 80px" id="abs3'+cont+'" value="'+item.Abs3+'"></td>';
                        tab2 += '<td><input disabled style="width: 80px" id="absPromedio'+cont+'" value="'+item.Abs_promedio+'"></td>';
                        tab2 += '<td><input disabled style="width: 80px" id="factorDilucion'+cont+'" value="'+item.Factor_dilucion+'"></td>';
                        tab2 += '<td><input disabled style="width: 80px" id="factorConversion'+cont+'" value="'+item.Factor_conversion+'"></td>';
                        tab2 += '<td><input disabled style="width: 80px" id="VolDisolucion'+cont+'" value="'+item.Vol_disolucion+'"></td>';
                    }else{
                        tab2 += '<td><input style="width: 80px" id="volMuestra'+cont+'" value="50"></td>';
                        tab2 += '<td><input style="width: 80px" id="abs1'+cont+'" value="'+item.Abs1+'"></td>';
                        tab2 += '<td><input style="width: 80px" id="abs2'+cont+'" value="'+item.Abs2+'"></td>';
                        tab2 += '<td><input style="width: 80px" id="abs3'+cont+'" value="'+item.Abs3+'"></td>';
                        tab2 += '<td><input style="width: 80px" id="absPromedio'+cont+'" value="'+item.Abs_promedio+'"></td>';
                        tab2 += '<td><input style="width: 80px" id="factorDilucion'+cont+'" value="'+item.Factor_dilucion+'"></td>';
                        tab2 += '<td><input style="width: 80px" id="factorConversion'+cont+'" value="'+item.Factor_conversion+'"></td>';
                        tab2 += '<td><input style="width: 80px" id="VolDisolucion'+cont+'" value="'+item.Vol_disolucion+'"></td>';
                    }
                tab2 += '</tr>';
                numMuestras.push(item.Id_detalle);
                cont++;
            });
            tab2 += '    </tbody>';
            tab2 += '</table>'; 
            tabla2.innerHTML = tab2;
            var t = $('#tablaControles').DataTable({        
                "ordering": false, 
                "language": {
                    "lengthMenu": "# _MENU_ por pagina",
                    "zeroRecords": "No hay datos encontrados",
                    "info": "Pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay datos encontrados",
                }
            });


            $('#tablaControles tbody').on( 'click', 'tr', function () {
                if ( $(this).hasClass('selected') ) {
                    $(this).removeClass('selected');
                }
                else {
                    table.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            } ); 
            $('#tablaControles tr').on('click', function(){
                let dato = $(this).find('td:first').html();
                idMuestra = dato;
              });
        }
    });
}
function liberarMuestraMetal()
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/liberarMuestraMetal",
        data: {
            idMuestra: idMuestra,
            idLote:idLote,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            if(response.sw == true)
            {
                getDataCaptura();
                getLoteCaptura();
            }else{
                alert("La muestra no se pudo liberar");
            }
        }
    });
}
function liberarTodo()
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/liberarTodo",
        data: {
            idLote:idLote,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            if(response.sw == true)
            {
                getDataCaptura();
                getLoteCaptura();
            } else {
                alert("La muestra no se pudo liberar");
            }
        }
    });
    
}

function random(min, max) {
  return Math.floor(Math.random() * (max - min + 1) + min);
}
function cleanTable() {

    let tabla = document.getElementById('divTablaControles');
    let tab = '';

            tab += '<table id="tablaControles" class="table table-sm">';
            tab += '    <thead>';
            tab += '        <tr>';
            tab += '          <th>Opc</th>';
            tab += '          <th>Folio</th>';
            // tab += '          <th># toma</th>';
            tab += '          <th>Norma</th>';
            tab += '          <th>Resultado</th>';
            tab += '          <th>Observación</th>';
            tab += '        </tr>';
            tab += '    </thead>'; 
            tab += '    <tbody>';
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;
}
function getImagenMuestra(id) {
    // console.log("EJEMPLO", id);
    $("#spinner").show(); 
    $("#divImagen").html(""); 
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getImagenMuestra",
        data: {
            id: id,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            if (response.model) {
                $("#divImagen").html(
                    `<img src="data:image/jpeg;base64,${response.model}" style="width:45%" alt="Foto">`
                );
            } else {
                $("#divImagen").html(`<h1> 📸 No hay imagen disponible 📸. Favor de verifiacarlo en Recepcion </h1>`);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error al obtener la imagen:", error);
            $("#divImagen").html(`<p style="color: red;">Error al cargar la imagen.</p>`);
        },
        complete: function () {
          
            $("#spinner").hide();
            $("#modalImgFoto").modal("show"); 
        },
    });
}



