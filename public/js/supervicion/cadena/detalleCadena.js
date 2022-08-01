var idCodigo;
var dataModel;
var idPunto;
var detPa;
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
        window.location = base_url + "/admin/informes/exportPdfCustodiaInterna/" + idPunto;
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
        if ($('#ckLiberado').prop('checked') == true) {
            console.log("Seleccionado");
            liberarSolicitud()
        } else {
            console.log("No Seleccionado");
        }
    });
    $('#btnLiberar').click(function () {
       liberarResultado();
    });
});

function getParametros() {
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
                tab += '<td>' + item.Id_codigo + '</td>';
                tab += '<td class="bg-' + color + '">' + item.Parametro + '</td>';
                tab += '<td>' + item.Tipo_formula + '</td>';
                tab += '<td>' + item.Resultado2 + '</td>';
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
                "scrollY": "300px",
                "scrollCollapse": true,
                "paging": false
            });
            $('#tableParametros tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                }
                else {
                    tableParametro.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                    getDetalleAnalisis(idCodigo);
                }
            });

            $('#tableParametros tr').on('click', function () {
                let dato = $(this).find('td:first').html();
                idCodigo = dato;
            });

        }
    });
}
var resLiberado = 0;
var idCod = idCodigo;
function getDetalleAnalisis(idCodigo) {
    let tabla = document.getElementById('divTabDescripcion');
    let tab = '';
    let aux = 0;
    let cont = 0;
    tabla.innerHTML = tab;

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
            switch (response.paraModel.Id_area) {
                case "2": //Metales
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
                        tab += '<td>' + response.paraModel.Parametro + '</td>';
                        resLiberado = item.Vol_disolucion;
                        tab += '<td>' + item.Vol_disolucion + '</td>';
                        tab += '</tr>';
                    });
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                case "16": //Espectro
                    console.log("entro a caso 16");
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
                        tab += '<td>' + response.paraModel.Parametro + '</td>';
                        tab += '<td>' + item.Resultado + '</td>';
                        resLiberado = item.Resultado;
                        tab += '</tr>';
                    });
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                case "14": // Volumetria
                    console.log("entro a caso 14");
                    tab += '<button class="btn btn-danger" id="btnRegresar">Regresar resultado</button>'
                    tab += '<table id="tableResultado" class="table table-sm">';
                    tab += '    <thead class="thead-dark">';
                    tab += '        <tr>';
                    tab += '          <th>Descripcion</th>';
                    tab += '          <th>Valor</th>';
                    tab += '        </tr>';
                    tab += '    </thead>';
                    tab += '    <tbody>';
                    if (response.paraModel.Id_parametro == 11) {
                            tab += '<tr>';
                            tab += '<td>' + response.model.Parametro + '</td>';
                            tab += '<td>' + response.model.Resultado2+ '</td>';
                            aux =  aux+parseFloat(response.model.Resultado2);
                            tab += '</tr>';
                        $.each(response.aux, function (key, item) {
                            if (item.Id_parametro == 7 || item.Id_parametro == 8) {
                                tab += '<tr>';
                                tab += '<td>' + item.Parametro + '</td>';
                                tab += '<td>' + item.Resultado + '</td>';
                                aux =  aux+parseFloat(item.Resultado);
                                tab += '</tr>';
                            } 
                        });
                        resLiberado = aux;
                    } else if(response.paraModel.Id_parametro == 6){
                        $.each(response.model, function (key, item) {
                            tab += '<tr>';
                            tab += '<td>' + item.Parametro + '</td>';
                            tab += '<td>' + item.Resultado + '</td>';
                            resLiberado = item.Resultado;
                            tab += '</tr>';
                        });
                    } else if(response.paraModel.Id_parametro == 9 || response.paraModel.Id_parametro == 10){
                        $.each(response.model, function (key, item) {
                            tab += '<tr>';
                            tab += '<td>' + item.Parametro + '</td>';
                            tab += '<td>' + item.Resultado + '</td>';
                            resLiberado = item.Resultado;
                            tab += '</tr>';
                        });
                    } else if(response.paraModel.Id_parametro == 83){
                        $.each(response.model, function (key, item) {
                            tab += '<tr>';
                            tab += '<td>' + item.Parametro + '</td>';
                            tab += '<td>' + item.Resultado + '</td>';
                            resLiberado = resLiberado + parseFloat(item.Resultado);
                            tab += '</tr>';
                        });
                    }
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                case "13":// Graasas & Aceites
                    console.log("entro a caso 13");
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
                        tab += '<td>' + item.Parametro + '</td>';
                        tab += '<td>' + item.Resultado + '</td>';
                        tab += '</tr>';
                    });
                    resLiberado = (response.aux);
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                case "6":// Micro
                    console.log("entro a caso 6");
                    tab += '<button class="btn btn-danger" id="btnRegresar">Regresar resultado</button>'
                    tab += '<table id="tableResultado" class="table table-sm">';
                    tab += '    <thead class="thead-dark">';
                    tab += '        <tr>';
                    tab += '          <th>Descripcion</th>';
                    tab += '          <th>Valor</th>';
                    tab += '        </tr>';
                    tab += '    </thead>';
                    tab += '    <tbody>';
                    if (response.codigoModel.Id_parametro == 5) {
                        $.each(response.model, function (key, item) {
                            tab += '<tr>';
                            if(item.Sugerido == 1)
                            {
                                tab += '<td class="bg-success">';
                            }else{
                                tab += '<td>';
                            }
                            tab += '' + item.Parametro + '</td>';
                            tab += '<td>' + item.Resultado + '</td>';
                            tab += '</>';
                            resLiberado = item.Resultado;
                        });
                    } else if(response.codigoModel.Id_parametro == 12) {
                        aux = 1;
                        $.each(response.model, function (key, item) {
                            aux = aux * parseFloat(item.Resultado);
                            tab += '<tr>';
                            tab += '<td>' + item.Parametro + '</td>';
                            tab += '<td>' + item.Resultado + '</td>';
                            tab += '</tr>';
                            cont++;
                        });
                        // resLiberado = (aux / cont);
                        resLiberado = (Math.pow(aux,1/cont)); 
                        console.log(resLiberado);
                    } else if(response.codigoModel.Id_parametro == 16) {
                        $.each(response.model, function (key, item) {
                            tab += '<tr>';
                            tab += '<td>' + item.Parametro + '</td>';
                            tab += '<td>' + item.Resultado + '</td>';
                            tab += '</tr>';
                            resLiberado = item.Resultado;
                        });
                    }
                    
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                    case "15":// Solidos
                        console.log("entro a caso 15");
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
                            tab += '<td>' + item.Parametro + '</td>';
                            tab += '<td>' + item.Resultado + '</td>';
                            tab += '</tr>';
                            resLiberado = item.Resultado;
                        });
                        tab += '    </tbody>';
                        tab += '</table>';
                        tabla.innerHTML = tab;
                        break;
                        case "7":// Campo
                            console.log("entro a caso 7");
                            tab += '<button class="btn btn-danger" id="btnRegresar">Regresar resultado</button>'
                            tab += '<table id="tableResultado" class="table table-sm">';
                            tab += '    <thead class="thead-dark">';
                            tab += '        <tr>';
                            tab += '          <th>Descripcion</th>';
                            tab += '          <th>Valor</th>';
                            tab += '        </tr>';
                            tab += '    </thead>';
                            tab += '    <tbody>';
                            if (response.codigoModel.Id_parametro == 26) { // Gasto
                                aux = 0;
                                cont = 0;
                                $.each(response.model, function (key, item) {
                                    tab += '<tr>';
                                    tab += '<td>Gasto Campo - '+(cont+ 1)+'</td>';
                                    tab += '<td>' + item.Promedio + '</td>';
                                    tab += '</tr>';
                                    aux = aux + parseFloat(item.Promedio);
                                    cont++
                                });
                                resLiberado = (aux / cont);
                            } else if(response.codigoModel.Id_parametro == 2) { // Materia flotante
                                aux = 0;
                                cont = 0;
                                $.each(response.model, function (key, item) {
                                    tab += '<tr>';
                                    tab += '<td> Materia - '+(cont + 1)+'</td>';
                                    tab += '<td>' + item.Materia + '</td>';
                                    tab += '</tr>';
                                    if(item.Materia == "Presente")
                                    {
                                        aux = 1;
                                    }
                                    cont++;
                                });
                                resLiberado = aux; 
                            } else if(response.codigoModel.Id_parametro == 14) { // PH
                                aux = 0;
                                cont = 0;
                                $.each(response.model, function (key, item) {
                                    tab += '<tr>';
                                    tab += '<td> pH - '+(cont + 1)+'</td>';
                                    tab += '<td>' + item.Promedio + '</td>';
                                    tab += '</tr>';
                                    aux = aux + parseFloat(item.Promedio);
                                    cont++;
                                });
                                resLiberado = (aux / cont);
                            } else if(response.codigoModel.Id_parametro == 97) { // Temperatura
                                aux = 0;
                                cont = 0;
                                $.each(response.model, function (key, item) {
                                    tab += '<tr>';
                                    tab += '<td> Temperatura - '+(cont + 1)+'</td>';
                                    tab += '<td>' + item.Promedio + '</td>';
                                    tab += '</tr>';
                                    aux = aux + parseFloat(item.Promedio);
                                    cont++;
                                });
                                resLiberado = (aux / cont);
                            }
                            tab += '    </tbody>';
                            tab += '</table>';
                            tabla.innerHTML = tab;
                        break;
                default:
                    console.log("entro a break");
                    break;
            }
            $("#resDes").val(resLiberado);


            $('#btnRegresar').click(function () {
                if (confirm('Estas seguro de cancelar la muestra?')) {
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
function liberarResultado(){
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/supervicion/cadena/liberarMuestra",
        data: {
            idSol: $("#idSol").val(),
            idPunto: idPunto,
            idCod:idCod,
            resLiberado:resLiberado,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            // swal("Analisis!", "Analisis regresado", "success");
            getParametros();
        }
    });
}
function regresarRes() {
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/supervicion/cadena/regresarRes",
        data: {
            idSol: $("#idSol").val(),
            idCodigo: idCodigo,
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