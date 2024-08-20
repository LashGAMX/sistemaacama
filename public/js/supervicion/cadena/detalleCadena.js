var idCodigo;
var dataModel;
var name;
var idPunto;
var detPa;
$(document).on('change', '.sugeridoCheckbox', function() {
    var Id_codigo = $(this).data('id');
    var sugerido_sup = this.checked ? 1 : 0;

    $.ajax({
        type: "POST",
        url: base_url + "/admin/supervicion/cadena/sugerido",
        data: {
            Id_codigo: Id_codigo,
            sugerido_sup: sugerido_sup
        },
        success: function(response) 
        {
            if (sugerido_sup === 1) {
                alert('Has marcado este resultado para que tu analista lo libere');
            }  else if(sugerido_sup===0) {
                alert('Has desmarcado el resultado');
            }
        },
        error: function(xhr, status, error) 
        {
           alert('Hubo un error al procesar la solicitud.');
        }
    });
});



$(document).ready(function () {

    let tablePunto = $('#tablePuntos').DataTable({
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        },
        "scrollY": "300px",
        "scrollCollapse": true,
        "paging": false
    });
    $('#btnCadena').click(function () {
        window.open(base_url+"/admin/informes/cadena/pdf/"+idPunto)
    });
    $('#btnSetEmision').click(function () {
        setEmision()
    });
    $('#tablePuntos tbody').on('click', 'tr', function () { 
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }
        else {
            tablePunto.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            let dato = $(this).find('td:first').html();
            idPunto = dato;
            getParametros(); 
            getFotos()
        }
    });

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
    $('#ckLiberado').click(function () {
        setLiberar()
    });
    $('#ckSupervisado').click(function () {
        setSupervicion();
    });
    $('#ckHistorial').click(function () {
        var historialValor = $(this).is(':checked') ? 1 : 0; //verifica si esta activado o desactivado 
    
        setHistorial(historialValor);
    
    });
    $('#btnLiberar').click(function () {
        liberarResultado();
    });
});
function getFotos(){
    let divfotos = document.getElementById('fotos')
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/supervicion/cadena/getFotos",
        data: {
            id:idPunto,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            let fotosHTML = '';

            response.model.forEach(item => {
                fotosHTML += `<img src="data:image/jpeg;base64,${item.Foto}"  onclick="modalImagenMuestra('${item.Foto}')" style="width:100%" alt="Foto">`;
            });
            
            $("#fotos").html(`
                <div class="row">
                    <div class="col-md-12">
                        ${fotosHTML}
                    </div>
                </div>
            `);
            
        }
    });
}
function modalImagenMuestra(id){
    $('#modalImgFoto').modal('show')
    console.log(id)
    let img = document.getElementById('divImagen')
    img.innerHTML = '<img src="data:image/png;base64,' + id+ '" style="width:100%;height:auto;">'
}
function setEmision(){
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/supervicion/cadena/setEmision",
        data: {
            idSol: $("#idSol").val(),
            fecha: $("#fechaEmision").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            alert(response.msg)
        }
    });
}
function setSupervicion(){
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/supervicion/cadena/setSupervicion",
        data: {
            idSol: $("#idSol").val(),
            std: $('#ckSupervisado').prop('checked'),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response)
            alert(response.msg)
        }
    });
}
function setLiberar(){
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/supervicion/cadena/setLiberar",
        data: {
            idSol: $("#idSol").val(),
            std: $('#ckLiberado').prop('checked'),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            alert(response.msg)
        }
    });
}
function setHistorial(historialValor) {
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/supervicion/cadena/setHistorial",
        data: {
            idSol: $("#idSol").val(),
            historial: historialValor,
        
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            if (response.sw == true) {
                swal("Registro!", "Proceso por historial", "success");

                $('#tableParametros tbody tr').each(function () {
                    $(this).find('td:eq(5)').text(historialValor);

                });
            } else {
                swal("Registro!", "Proceso por historial cancelado", "success");
            }
        }
    });
}

function getParametros() {
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/supervicion/cadena/getParametroCadena",
        data: {
            idPunto: idPunto,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        success: function (response) {
            let tab = '<table id="tableParametros" class="table">';
            tab += '<thead class="thead-dark">';
            tab += '<tr>';
                tab += '<th>Id</th>';
                tab += '<th>Parametro</th>';
                tab += '<th>Tipo formula</th>';
                tab += '<th style="width:50px">Ejec.</th>';
                tab += '<th style="width:50px">Res.</th>';
                tab += '<th>his</th>';
                tab += '<th>Limite</th>'; 
                tab += '<th>%</th>'; 
            tab += '</tr>';
            tab += '</thead>';
            tab += '<tbody>';
        
            let countDanger = 0; // Contador para parámetros fuera de rango
            let cont = 0
            $.each(response.model, function (key, item) {
                let color = "";
                let AP = "";
        
                if (item.Resultado2 != null) {
                    color = "success";
                } else {
                    color = "warning";
                }
        
                switch (parseInt(item.Id_parametro)) {
                    case 14: //Ph
                    case 100:
                    case 26:// Gasto
                    case 13: // GA
                    case 12: //Coliformes
                    case 137:
                    case 51:
                    case 134: 
                    case 132:
                    case 67: //conductividad
                    case 2: //Materia Floatante
                    case 97: //Températura 
                    case 100:
                    case 5:
                    case 71:
                    case 35:
                    case 253:
                        if (item.Liberado != 1) {
                            color = "danger";
                      
                        } else {
                            color = "success";
                        }
                        break;
                    default:
                        break;
                }
        
                if (item.Cadena == 0) {
                    AP = "primary";
                } else {
                    AP = "";
                }
              
        
                let LOL = ""; 
    
                if (item.Limite == 'N/A' || item.Limite == null || item.Resultado2 ==null ) {
                    LOL = 'success';
                } else if (item.Limite.includes('-')) {
                    
                    var limites = item.Limite.split('-');
                    var limiteMin = parseFloat(limites[0]);
                    var limiteMax = parseFloat(limites[1]);
                    
                    var resultado2 = parseFloat(item.Resultado2);
                     if(item.resultado2 < item.Limite)
                     {
                        LOL = 'success';

                     }
                    if (!isNaN(resultado2) && resultado2 >= limiteMin && resultado2 <= limiteMax) {
                        LOL = 'success';
                    } else {
                        LOL = 'danger';
                        countDanger++; 
                    }
                } else if (parseFloat(item.Resultado2) == parseFloat(item.Limite)) {
                    LOL = 'success';
                } else if (parseFloat(item.Resultado2) < parseFloat(item.Limite)) {
                    LOL = 'success';
                } else if (parseFloat(item.Resultado2) > parseFloat(item.Limite)) {
                    LOL = 'danger';
                    countDanger++; 
                }
                else {
                    LOL = 'success';
                }
                
                
                tab += '<tr>';
                tab += '<td>' + item.Id_codigo + '</td>';
                tab += '<td class="bg-' + color + '">(' + item.Id_parametro + ') ' + item.Parametro + '</td>';
                tab += '<td class="bg-' + AP + '">' + item.Tipo_formula + '</td>';
                tab += '<td class="bg-' + AP + '">' + item.Resultado + '</td>';
                tab += '<td class="bg-' + AP + '">' + item.Resultado2 + '</td>';
                tab += '<td><button class="btn-warning" onclick="getHistorial(' + item.Id_codigo + ')" data-toggle="modal" data-target="#modalHistorial"><i class="fas fa-info"></i></button></td>';
                tab += '<td class="bg-' + LOL + '">' + item.Limite + '</td>'; 
                tab += '<td>'+response.porcentaje[cont]+'</td>'; 
                tab += '</tr>';
                cont++
            });
        
            tab += '</tbody>';
            tab += '</table>';
        
            $('#divTableParametros').html(tab);

            const mensaje = $('#mensaje'); 


        
            if (countDanger == 0) {
                mensaje.text('No Hay Parametros Fuera de Rango');
                mensaje.css('background-color', 'green');
            } else if (countDanger > 0) {
                mensaje.text('Parametros Fuera de Rango:  ');
                mensaje.css('background-color', 'red');
        
                const span = $('<span>').addClass('badge rounded-pill').css('background-color', 'rgb(3, 196, 245)');
                span.text(' ' + countDanger);
                
                mensaje.append(span);
            }
        
            let tableParametro = $('#tableParametros').DataTable({
                "ordering": false,
                "language": {
                    "lengthMenu": "# _MENU_ por pagina",
                    "zeroRecords": "No hay datos encontrados",
                    "info": "Pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay datos encontrados",
                },
                "scrollY": "300px",
                "scrollCollapse":true,
                "paging": false,
               
            });
            $('#tableParametros tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                }
                else 
                {
                    tableParametro.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                    getDetalleAnalisis(idCodigo);
                }
            });
            $('#tableParametros tr').on('click', function () {
                let dato = $(this).find('td:first').html();
                idCodigo = dato;
            });
        },
        
        error: function (xhr, status, error) {
            console.error('Error en la solicitud AJAX:', status, error);
        }
    });
}


function regresarMuestra () {
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/supervicion/cadena/regresarMuestra",
        data: {
            idSol: $("#idSol").val(),
            idCodigo: idCod,
           
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response)
                alert("Muestra regresada");

                var table = $('#tableParametros').DataTable();
                table.rows().every(function () {
                var rowData = this.data();
                var rowId = rowData[0];

                if (rowId == idCodigo) {
                    this.cell(this.index(), 4).data(response.resLiberado);
                    
                    $(this.node()).find('td:eq(1)').removeClass('bg-success').addClass('bg-warning');
                    return false; 
                }
             
                
            });
          
        }
    });
}
function reasignarMuestra () {
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/supervicion/cadena/reasignarMuestra",
        data: {
            idSol: $("#idSol").val(),
            idCodigo: idCod,
           
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response)
           
                alert("Muestra Para Reasignar");
          
        }
    });
}
function desactivarMuestra() {
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/supervicion/cadena/desactivarMuestra",
        data: {
            idSol: $("#idSol").val(),
            idCodigo: idCod,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            var table = $('#tableParametros').DataTable();
            table.rows().every(function () {
                var rowData = this.data();
                var rowId = rowData[0];

                if (rowId == idCodigo) {
            alert("Muestra desactivada");
            $(this.node()).find('td:eq(2)').addClass('bg-primary');
            $(this.node()).find('td:eq(3)').addClass('bg-primary');
            $(this.node()).find('td:eq(4)').addClass('bg-primary');
            
        }
    });
        }
    });
}


var resLiberado = 0;
var idCod = idCodigo;
var name = ""
function getDetalleAnalisis(idCodigo) {
    let tabla = document.getElementById('divTabDescripcion');
    let tab = '';
    let aux = 0;
    let cont = 0;
    tabla.innerHTML = tab;
    resLiberado = 0;
    $("#resDes").val(0.0)
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/supervicion/cadena/getDetalleAnalisis",
        data: {
            idSol: $("#idSol").val(),
            idCodigo: idCodigo,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response)
            dataModel = response.model
            idCod = idCodigo;
            switch (parseInt(response.paraModel.Id_parametro)) {
                case 17: // Arsenico
                case 208:
                case 207: //Aluminio 
                case 231:
                case 20: // Cobre
                case 22: //Mercurio
                case 215:
                case 25: //Zinc
                case 227:
                case 24: //Plomo
                case 216:  
                case 21: //Cromoa
                case 264:
                case 18: //Cadmio
                case 210:
                case 300: //Niquel
                case 233: // Seleneio
                case 213: //Fierro 
                case 197:
                case 188:
                case 189:
                case 190:
                case 191:
                case 192:
                case 194:
                case 195:
                case 196:
                case 204:
                case 219:
                case 230:
                case 23:
                    console.log("entro a caso 2");
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
                        tab += '<td>' + response.paraModel.Parametro + '</td>';
                        resLiberado = item.Vol_disolucion;
                        tab += '<td>' + item.Vol_disolucion + '</td>';
                        tab += '</tr>';
                    });
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                   
                case 15: // fosforo
                case 19: // Cianuros
                case 7: //Nitrats
                case 8: //Nitritos
                case 152: //COT
                case 99: //Cianuros 127
                case 105: //Floururos 127
                case 106:
                case 107:
                case 96:
                case 95: // Sulfatos
                case 87: // silice 
                case 79:
                case 80:
                    console.log("entro a caso 16");
                  
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
                        tab += '<td>' + response.paraModel.Parametro + '</td>';
                        tab += '<td>' + parseFloat(item.Resultado).toFixed(3) + '</td>';
                        resLiberado = parseFloat(item.Resultado).toFixed(3);
                        tab += '</tr>';
                    });
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                //Volumnetria

                case 11:
                    let swT = 0
                    
                    tab += '<table id="tableResultado" class="table table-sm">';
                    tab += '    <thead class="thead-dark">';
                    tab += '        <tr>';
                    tab += '          <th>Descripcion</th>';
                    tab += '          <th>Valor</th>';
                    tab += '        </tr>';
                    tab += '    </thead>';
                    tab += '    <tbody>';
                    tab += '<tr>';
                    tab += '<td>' + response.model.Parametro + '</td>';
                    if (response.model.Resultado2 < response.model.Limite) {
                        tab += '<td>< ' + response.model.Limite + '</td>';
                        aux = aux + parseFloat(response.model.Limite);
                    } else {
                        tab += '<td>' + response.model.Resultado2 + '</td>';
                        aux = aux + parseFloat(response.model.Resultado2);
                        swT = 1
                    }
                    console.log("Ken: "+ aux)
                    tab += '</tr>';
                    let tempR
                    $.each(response.aux, function (key, item) {
                        if (item.Id_parametro == "7" || item.Id_parametro == "8") {
                            tab += '<tr>';
                            tab += '<td>' + item.Parametro + '</td>';
                            if (item.Resultado < item.Limite) {
                                tab += '<td>< ' + item.Limite + '</td>';
                                aux = aux + parseFloat(item.Limite);
                            } else {
                                tab += '<td>' + parseFloat(item.Resultado).toFixed(3) + '</td>';
                                tempR = parseFloat(item.Resultado).toFixed(3)
                                aux = aux + parseFloat(tempR);
                            }
                            tab += '</tr>';
                            if (parseFloat(item.Resultado) >= parseFloat(item.Limite)) {
                                swT = 1
                            } 
                        }
                    });
                    resLiberado = parseFloat(aux).toFixed(3)
                    if (swT != 1) {
                        resLiberado = 1.50;
                    } 
                    console.log(swT)
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                case 9:
                case 10:
                case 108: 
                   
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
                        tab += '<td>' + item.Parametro + '</td>';
                        tab += '<td>' + item.Resultado + '</td>';
                        resLiberado = item.Resultado;
                        tab += '</tr>';
                    });
                    tab += '    </tbody>'; 
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                case 6:
                   
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
                        tab += '<td>' + item.Parametro + '</td>';
                        tab += '<td>' + item.Resultado + '</td>';
                        resLiberado = item.Resultado;
                        tab += '</tr>';
                    });
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                case 83:
                    let swK = 0;
                    let resN1 = 0.0
                    let resN2 = 0.0
                    console.log("Entro a kendal")
                  
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
                        tab += '<td>' + item.Parametro + '</td>';
                        if (item.Resultado < item.Limite) {
                            tab += '<td> < ' + item.Limite + '</td>';
                            resLiberado = resLiberado + parseFloat(item.Limite);
                        } else {
                            tab += '<td>' + item.Resultado + '</td>';
                            // resLiberado = resLiberado + parseFloat(item.Resultado); 
                            resN1 = parseFloat(item.Resultado).toFixed(2)
                            resLiberado = resLiberado + parseFloat(resN1)
                        }
                        tab += '</tr>';
                        if (parseFloat(item.Resultado) >= item.Limite) {
                            swK = 1
                        } 
                    });
                    $.each(response.aux, function (key, item) {
                        tab += '<tr>';
                        tab += '<td>' + item.Parametro + '</td>';
                        if (item.Resultado <= item.Limite) {
                            tab += '<td> < ' + item.Limite + '</td>';
                            resLiberado = resLiberado + parseFloat(item.Limite);
                        } else {
                            tab += '<td>' + item.Resultado + '</td>';
                            // resLiberado = resLiberado + parseFloat(item.Resultado);   
                            resN2 = parseFloat(item.Resultado).toFixed(2)
                            resLiberado = resLiberado + parseFloat(resN2)
                        }
                        tab += '</tr>';
                        if (parseFloat(item.Resultado) >= item.Limite) {
                            swK = 1
                        } 
                    });

                        if (swK != 1) {
                            resLiberado = 1.39;
                        } 
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                // case 218:
                case 64:
                
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
                        
                            tab += '<td>' + item.Parametro + '</td>';
                            tab += '<td>' + item.Resultado + '</td>';
                            resLiberado = resLiberado + parseFloat(item.Resultado);
                        tab += '</tr>';
                    });
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                case 358:
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
                            tab += '<td>Cloruros Totales (Cl¯)</td>';
                            tab += '<td>' + item.Cloruros + '</td>';
                            resLiberado = resLiberado + parseFloat(item.Cloruros);
                        tab += '</tr>';
                    });
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                case 13:// Graasas & Aceites
                    console.log("entro a caso 13");
                    let swG = 0;
                    tab += '<table id="tableResultado" class="table table-sm">';
                    tab += '    <thead class="thead-dark">';
                    tab += '        <tr>';
                    tab += '          <th>Descripcion</th>';
                    tab += '          <th>Valor</th>';
                    if ($("#idNorma").val() == "27") {
                        tab += '          <th>%</th>';
                    }
                    tab += '        </tr>';
                    tab += '    </thead>';
                    tab += '    <tbody>';

                    aux = 0; 
                    cont = 0;
                $.each(response.model, function (key, item) {
                        tab += '<tr>';
                        tab += '<td> GA - ' + item.Num_tomas + '</td>';
                        tab += '<td>' + item.Resultado + '</td>';
                        
                        if (item.Resultado != null) {
                            if (response.solModel.Id_servicio != "3") {
                                if (parseInt(response.solModel.Num_tomas) > 1) {
                                    // tab += '<td>' + (response.aux[cont] * item.Resultado).toFixed(2) + '</td>';
                                    // aux = aux + (response.aux[cont] * item.Resultado);
                                    
                                    if (item.Resultado < item.Limite) {
                                        tab += '<td>< ' + item.Limite + '</td>';
                                        aux = aux + (response.aux[cont] * item.Limite)
                                    } else {
                                        tab += '<td>' + (response.aux[cont] * item.Resultado).toFixed(2) + '</td>';
                                        aux = aux + (response.aux[cont] * item.Resultado)
                                    }
                                    cont++;
                                } else {
                                    aux = parseFloat(item.Resultado)
                                    cont++;
                                }
                            } else {
                                aux = parseFloat(item.Resultado)
                                cont++;
                            }
                           
                        }
                        tab += '</tr>';
                    });
                    console.log(aux)
                    if (aux.toFixed(2) <= 10) {
                        aux = 9.9
                    }
                    resLiberado = (aux).toFixed(2);  
                    
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                    case 5:
                    case 70:
                    case 71:
                            console.log("Entro a id 5")
                            tab += '<table id="tableResultado" class="table table-sm">';
                            tab += '    <thead class="thead-dark">';
                            tab += '        <tr>';
                            tab += '          <th>Descripcion</th>';
                            tab += '          <th>Valor</th>';
                            tab += '          <th>Sup</th>';
                            tab += '        </tr>';
                            tab += '    </thead>';
                            tab += '    <tbody>';
                            $.each(response.model, function (key, item) {
                                tab += '<tr>';
                                if (item.Sugerido == 1) {
                                    tab += '<td class="bg-success">';
                                    resLiberado = item.Resultado;
                                } else {
                                    tab += '<td>';
                                }
                                tab += '' + item.Parametro + '</td>';
                                tab += '<td>' + item.Resultado + '</td>';                                                  
                                tab += '<td><input type="checkbox" class="sugeridoCheckbox" data-id="' + item.Id_codigo + '" ' + (item.Sugerido_sup === 1 ? 'checked' : '') + '></td>';    
                             });
                            tab += '    </tbody>';
                            tab += '</table>';
                          
                            tabla.innerHTML = tab;
                            break;
                case 12:
                case 133:
                case 134:
                case 137:
                case 51:
                case 35: 
                    let swC = 0;
                    tab += '<table id="tableResultado" class="table table-sm">';
                    tab += '    <thead class="thead-dark">';
                    tab += '        <tr>';
                    tab += '          <th>Descripcion</th>';
                    tab += '          <th>Valor</th>';
                    tab += '        </tr>';
                    tab += '    </thead>';
                    tab += '    <tbody>';
                    aux = 1;
                    $.each(response.model, function (key, item) {
                        if (parseInt(item.Resultado) < parseInt(item.Limite)) {
                            aux = aux * parseFloat(3);
                            tab += '<tr>';
                            tab += '<td>' + item.Parametro + '</td>';
                            tab += '<td> < '+item.Limite+'</td>';
                            tab += '</tr>';
                        } else {
                            aux = aux * parseFloat(item.Resultado);
                            tab += '<tr>';
                            tab += '<td>' + item.Parametro + '</td>';
                            tab += '<td>' + item.Resultado + '</td>';
                            tab += '</tr>';
                        }
                        if (parseInt(item.Resultado) > 0) {
                            swC = 1
                        } 
                        cont++;

                    });
                    // resLiberado = (aux / cont);
                    if (swC == 1) {
                        resLiberado = (Math.pow(aux, 1 / cont));   
                    } else {
                        resLiberado = 0;
                    }
                    console.log("Res: "+resLiberado);
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                case 253:
                    let swEn = 0
                    
                    tab += '<table id="tableResultado" class="table table-sm">';
                    tab += '    <thead class="thead-dark">';
                    tab += '        <tr>';
                    tab += '          <th>Descripcion</th>';
                    tab += '          <th>Valor</th>';
                    tab += '        </tr>';
                    tab += '    </thead>';
                    tab += '    <tbody>';
                    aux = 1; 


                    $.each(response.model, function (key, item) {
                        if (parseInt(item.Resultado) < parseInt(item.Limite)) {
                            aux = aux * 3;
                            tab += '<tr>';
                            tab += '<td>' + item.Parametro + '</td>';
                            tab += '<td> < 3</td>';
                            tab += '</tr>';
                        } else {
                            aux = aux * parseFloat(item.Resultado);
                            tab += '<tr>';
                            tab += '<td>' + item.Parametro + '</td>';
                            tab += '<td>' + item.Resultado + '</td>';
                            tab += '</tr>';   
                        }
                        if (parseInt(item.Resultado) > 0) {
                            swEn = 1
                        } 
                        cont++;
                    });
                    // resLiberado = (aux / cont);
                    // resLiberado = (Math.pow(aux, 1 / cont));
                    if (swEn == 1) {
                        resLiberado = (Math.pow(aux, 1 / cont));   
                    } else {
                        resLiberado = 0;
                    }
                    console.log(resLiberado);
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                case 35:
                   
                    tab += '<table id="tableResultado" class="table table-sm">';
                    tab += '    <thead class="thead-dark">';
                    tab += '        <tr>';
                    tab += '          <th>Descripcion</th>';
                    tab += '          <th>Valor</th>';
                    tab += '        </tr>';
                    tab += '    </thead>';
                    tab += '    <tbody>';
                    aux = 1;

                    $.each(response.model, function (key, item) {
                        if (parseInt(item.Resultado) < parseInt(item.Limite)) {
                            aux = aux * parseFloat(3);
                            tab += '<tr>';
                            tab += '<td>' + item.Parametro + '</td>';
                            tab += '<td> < 3</td>';
                            tab += '</tr>';
                        } else {
                            aux = aux * parseFloat(item.Resultado);
                            tab += '<tr>';
                            tab += '<td>' + item.Parametro + '</td>';
                            tab += '<td>' + item.Resultado + '</td>';
                            tab += '</tr>';
                        }
                        cont++;
                    });
                    // resLiberado = (aux / cont);
                    resLiberado = (Math.pow(aux, 1 / cont));
                    console.log(resLiberado);
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                case 16:
                  
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
                        tab += '<td>' + item.Parametro + '</td>';
                        tab += '<td>' + item.Resultado + '</td>';
                        tab += '</tr>';
                        resLiberado = item.Resultado;
                    });
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                case 78:
                    
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
                        tab += '<td>' + item.Parametro + '</td>';
                        tab += '<td>' + item.Resultado + '</td>';
                        tab += '</tr>';
                        resLiberado = item.Resultado;
                    });
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;

                case 3:// Solidos
                case 4:// Solidos
                case 112:// Solidos
                case 90:
                    console.log("entro a caso 15");
                   
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
                        tab += '<td>' + item.Parametro + '</td>';
                        tab += '<td>' + item.Resultado + '</td>';
                        tab += '</tr>';
                        resLiberado = item.Resultado;
                    });
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                //Campo
                case 26://Gasto
                case 2: //Materia flotante
                case 14: //ph
                case 110: //ph
                case 97: //Temperatura
                case 67://Conductividad
                case 68:
                    console.log("entro a caso 7");
                    
                    tab += '<table id="tableResultado" class="table table-sm">';
                    tab += '    <thead class="thead-dark">';
                    tab += '        <tr>';
                    tab += '          <th>Descripcion</th>';
                    tab += '          <th>Valor</th>';
                    if ($("#idNorma").val() == "27") {
                        tab += '          <th>%</th>';
                    }
                    tab += '        </tr>';
                    tab += '    </thead>';
                    tab += '    <tbody>';
                    if (parseInt(response.codigoModel.Id_parametro) == 26) { // Gasto
                        aux = 0;
                        cont = 0;
                        $.each(response.model, function (key, item) {
                            tab += '<tr>';
                            tab += '<td>Gasto Campo - ' + (cont + 1) + '</td>';
                            if (parseInt(response.solModel.Id_servicio) != 3) {
                                tab += '<td>' + item.Promedio + '</td>';   
                                aux = aux + parseFloat(item.Promedio);
                                console.log("Entra en campo")
                            } else { 
                                tab += '<td>' + item.Resultado + '</td>';
                                aux = aux + parseFloat(item.Resultado);
                                console.log("Entra en Lab")
                            }
                            tab += '</tr>';
                            cont++
                        });
                        resLiberado = (aux / cont);
                    } else if (response.codigoModel.Id_parametro == 67 || response.codigoModel.Id_parametro == 68) { // Gasto
                        aux = 0;
                        cont = 0;
                        if ($("#idNorma").val() == "27") {
                            // $.each(response.model, function (key, item) {
                            //     tab += '<tr>';
                            //     tab += '<td>Conductividad Campo - ' + (cont + 1) + '</td>';
                            //     tab += '<td>' + item.Promedio + '</td>';
                            //     tab += '<td>' + (response.aux[cont] * item.Promedio) + '</td>';
                            //     tab += '</tr>';
                            //     if (item.Promedio != null) {
                            //         aux = aux + (response.aux[cont] * item.Promedio);
                            //         cont++;
                            //     }
                            // });
                            // resLiberado = (aux).toFixed(2);
                            $.each(response.model, function (key, item) {
                                tab += '<tr>';
                                tab += '<td> Conductividad Campo - ' + (cont + 1) + '</td>';
                                if (parseInt(response.solModel.Id_servicio) != 3) {
                                    tab += '<td>' + item.Promedio + '</td>';   
                                } else { 
                                    tab += '<td>' + item.Resultado + '</td>';
                                }
                                tab += '</tr>';
                                if (item.Promedio != null || item.Resultado != null) {
                                    if (parseInt(response.solModel.Id_servicio) != 3) {
                                        aux = aux + parseFloat(item.Promedio);
                                    } else { 
                                        aux = aux + parseFloat(item.Resultado);
                                    }
                                    cont++; 
                                }
                            });
                            resLiberado = (aux /cont);
                        } else {
                            $.each(response.model, function (key, item) {
                                tab += '<tr>';
                                tab += '<td> Conductividad Campo - ' + (cont + 1) + '</td>';
                                if (parseInt(response.solModel.Id_servicio) != 3) {
                                    tab += '<td>' + item.Promedio + '</td>';   
                                } else { 
                                    tab += '<td>' + item.Resultado + '</td>';
                                }
                                tab += '</tr>';
                                if (item.Promedio != null || item.Resultado != null) {
                                    if (parseInt(response.solModel.Id_servicio) != 3) {
                                        aux = aux + parseFloat(item.Promedio);
                                    } else { 
                                        aux = aux + parseFloat(item.Resultado);
                                    }
                                    cont++; 
                                }
                            });
                            resLiberado = (aux /cont);
                        }
                    } else if (response.codigoModel.Id_parametro == 2) { // Materia flotante
                        aux = 0;
                        cont = 0;
                        $.each(response.model, function (key, item) {
                            tab += '<tr>';
                            tab += '<td> Materia - ' + (cont + 1) + '</td>';
                            if (parseInt(response.solModel.Id_servicio) != 3) {
                                tab += '<td>' + item.Materia + '</td>';   
                                if (item.Materia == "Presente") {
                                    aux = 1;
                                }
                            } else { 
                                tab += '<td>' + item.Resultado + '</td>';
                                if (item.Resultado == "Presente" || item.Resultado == "PRESENTE") {
                                    aux = 1;
                                }
                            }
                            tab += '</tr>';
                            cont++;
                        });
                        resLiberado = aux;
                    } else if (response.codigoModel.Id_parametro == 14 || response.codigoModel.Id_parametro == 110) { // PH
                        aux = 0;
                        cont = 0;
                        $.each(response.model, function (key, item) {
                            tab += '<tr>';
                            if (parseInt(response.solModel.Id_servicio) != 3) {
                                tab += '<td>Ph Campo - ' + (cont + 1) + '</td>';
                                tab += '<td>' + item.Promedio + '</td>';   
                                aux = aux + parseFloat(item.Promedio);  
                            } else {  
                                tab += '<td>Ph Laboratorio</td>';
                                tab += '<td>' + item.Resultado + '</td>';
                                aux = aux + parseFloat(item.Resultado); 
                            }
                            tab += '<td></td>'; 
                            tab += '</tr>'; 
                            cont++ 
                        }); 
                        resLiberado = (aux / cont);
                        // aux = 0;
                        // cont = 0;
                        // if ($("#idNorma").val() == "27") {
                        //     $.each(response.model, function (key, item) {
                        //         tab += '<tr>';
                        //         if (response.solModel.Id_muestra == 1) {
                        //             tab += '<td> Ph</td>';
                        //             tab += '<td>' + item.Promedio + '</td>';   
                        //             aux =  item.Promedio;
                        //         } else {  
                        //             tab += '<td> pH compuesto</td>';
                        //             tab += '<td>' + item.Ph_muestraComp + '</td>';
                        //             tab += '<td>------</td>';
                        //             // tab += '<td>' + (response.aux[cont] * item.Promedio).toFixed(2) + '</td>';
                        //             if (item.Ph_muestraComp != null) {
                        //                 aux =  item.Ph_muestraComp;
                        //                 cont++;
                        //             }
                        //         }
                        //         tab += '</tr>';
                        //     });
                        //     resLiberado = parseFloat(aux).toFixed(2);
                        // } else {
                        //     $.each(response.model, function (key, item) {
                        //         tab += '<tr>';
                        //         if (parseInt(response.solModel.Id_servicio) != 3) {
                        //             if (response.solModel.Id_muestra == 1) {
                        //                 tab += '<td> Ph</td>';
                        //                 tab += '<td>' + item.Promedio + '</td>';   
                        //             } else { 
                        //                 tab += '<td> pH Compuesto</td>';
                        //                 tab += '<td>' + item.Ph_muestraComp + '</td>';      
                        //             }
                        //         } else { 
                        //             tab += '<td> pH - ' + (cont + 1) + '</td>';
                        //             tab += '<td>' + item.Resultado + '</td>';
                        //         }
                        //         tab += '</tr>';
                        //         if (item.Ph_muestraComp != null || item.Resultado != null || item.Promedio != null) {
                        //             if (parseInt(response.solModel.Id_servicio) != 3) {
                        //                 if (response.solModel.Id_muestra == 1) {
                        //                     aux = aux + parseFloat(item.Promedio); 
                        //                 } else {
                        //                     aux = aux + parseFloat(item.Ph_muestraComp);   
                        //                 }
                        //             } else { 
                        //                 aux = aux + parseFloat(item.Resultado);
                        //             }
                        //             cont++; 
                        //         }
                        //     });
                        //     resLiberado = (aux / cont).toFixed(2);
                        // }
                    } else if (response.codigoModel.Id_parametro == "97" || response.codigoModel.Id_parametro == "100") { // Temperatura
                        aux = 0;
                        cont = 0;
                        if ($("#idNorma").val() == "27") {
                      
                            $.each(response.model, function (key, item) {
                                tab += '<tr>';
                                tab += '<td> Temperatura - ' + (cont + 1) + '</td>';
                                if (parseInt(response.solModel.Id_servicio) != 3) {
                                    tab += '<td>' + item.Promedio + '</td>';   
                                } else { 
                                    tab += '<td>' + item.Resultado + '</td>';
                                }
                                tab += '</tr>';
                                if (item.Promedio != null || item.Resultado != null) {
                                    if (parseInt(response.solModel.Id_servicio) != 3) {
                                        aux = aux + parseFloat(item.Promedio);
                                    } else { 
                                        aux = aux + parseFloat(item.Resultado);
                                    }
                                    cont++; 
                                }
                            });
                            resLiberado = (aux / cont).toFixed(2);
                        } else {
                            $.each(response.model, function (key, item) {
                                tab += '<tr>';
                                tab += '<td> Temperatura - ' + (cont + 1) + '</td>';
                                if (parseInt(response.solModel.Id_servicio) != 3) {
                                    tab += '<td>' + item.Promedio + '</td>';   
                                } else { 
                                    tab += '<td>' + item.Resultado + '</td>';
                                }
                                tab += '</tr>';
                                if (item.Promedio != null || item.Resultado != null) {
                                    if (parseInt(response.solModel.Id_servicio) != 3) {
                                        aux = aux + parseFloat(item.Promedio);
                                    } else { 
                                        aux = aux + parseFloat(item.Resultado);
                                    }
                                    cont++; 
                                }
                            });
                            resLiberado = (aux / cont).toFixed(2);
                        }
                    }
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                case 95:// Potable
                case 116:
                    console.log("entro a caso 8");
                    
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
                        tab += '<td>' + item.Parametro + '</td>';
                        tab += '<td>' + item.Resultado + '</td>';
                        tab += '</tr>';
                        resLiberado = item.Resultado;
                    });
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                case 66:
                case 65:
                case 98:
                case 89:
                case 218:
                case 84: // Olor
                case 86: // Sabor
                    console.log("entro a caso 8");
                    
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
                        tab += '<td>' + item.Parametro + '</td>';
                        tab += '<td>' + item.Resultado + '</td>';
                        tab += '</tr>';
                        resLiberado = item.Resultado;
                    });
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                case 365:
                case 370:
                case 372:
                    tab += '<table id="tableResultado" class="table table-sm">';
                    tab += '    <thead class="thead-dark">';
                    tab += '        <tr>';
                    tab += '          <th>Descripcion</th>';
                    tab += '          <th>Valor</th>';
                    tab += '          <th>Ph</th>';
                    tab += '        </tr>';
                    tab += '    </thead>';
                    tab += '    <tbody>';
                    $.each(response.model, function (key, item) {
                        tab += '<tr>';
                        tab += '<td>' + item.Parametro + '</td>';
                        tab += '<td>' + item.Resultado + '</td>';
                        tab += '<td>' + item.Ph + '</td>';
                        tab += '</tr>';
                        resLiberado = item.Resultado;
                    });
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                default:
                    console.log("entro a break");
                   
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
                        tab += '<td>' + item.Parametro + '</td>';
                        tab += '<td>' + item.Resultado + '</td>';
                        tab += '</tr>';
                        resLiberado = item.Resultado;
                    });
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
            }
            $("#resDes").val(resLiberado);
            let tableResultado = $('#tableResultado').DataTable({
                "ordering": false,
                "language": {
                    "lengthMenu": "# _MENU_ por pagina",
                    "zeroRecords": "No hay datos encontrados",
                    "info": "Pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay datos encontrados",
                }
            });
            $('#tableResultado tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                }
                else {
                    tableResultado.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                    let dato = $(this).find('td:eq(1)').html();
                    detPa = dato;
                    resLiberado = detPa;
                }
            });

        }
    });
}



function liberarResultado() {
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/supervicion/cadena/liberarMuestra",
        data: {
            idSol: $("#idSol").val(),
            idPunto: idPunto,
            idCod: idCod,
            resLiberado: resLiberado,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        success: function (response) {
            var table = $('#tableParametros').DataTable();
            table.rows().every(function () {
                var rowData = this.data();
                var rowId = rowData[0];

                if (rowId == idCodigo) {
                    this.cell(this.index(), 4).data(response.resLiberado);
                    
                    $(this.node()).find('td:eq(4)').text(resLiberado); 
                    $(this.node()).find('td:eq(1)').removeClass('bg-warning').removeClass('bg-danger').addClass('bg-success');
                    return false; 
                }
             
                
            });
        }
    });
}

function liberarSolicitud() {
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/supervicion/cadena/liberarSolicitud",
        data: {
            idSol: $("#idSol").val(),
            liberado: $('#ckLiberado').prop('checked'),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            if (response.sw == true) {
                swal("Registro!", "Solicitud liberada", "success");
            } else {
                swal("Registro!", "Liberacion modificada", "success");
            }
        }
    });
}

function getHistorial(id)
{
    console.log("Get Historial");
    let tabla1 = document.getElementById('divTablaHist');
    let tab1 = ''

  
    $.ajax({
        type: "POST",
        url: base_url + "/admin/supervicion/cadena/getHistorial",
        data: {
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
                               $.map(response.idsLotes, function (item,index){
                                let    estilo =   parseInt(response.historialHist[index]) == 1 ? 'background-color:#e5e5ff;' : ''
                                    return `
                                 
                                    
                                        <tr>
                                            <td>${item}</td>
                                            <td style ="${estilo}">${response.fechaLote[index]}</td>
                                            <td style ="${estilo}">${response.Codigohist[index]}</td>
                                            <td style ="${estilo}"">${response.parametrohist[index]}</td>
                                            <td style ="${estilo}">${response.resultadoHist[index]}</td>
                                            
                                        </tr>
                                    `
                               }).join('') 
                            }
                    </tbody>
                 </table>
            `

            tabla1.innerHTML = tab1

            
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
