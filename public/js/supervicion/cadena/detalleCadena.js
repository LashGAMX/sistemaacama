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
        window.open("/admin/informes/cadena/pdf/"+idPunto)
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
            // tab += '          <th>Res. Sin. Sup</th> ';
            tab += '          <th>Resultado</th> ';
            // tab += '          <th>Liberado</th> '; 
            // tab += '          <th>Nombre</th> '; 
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            $.each(response.model, function (key, item) {
                if (item.Resultado2 != null) {
                    color = "success";
                } else {
                    color = "warning"
                }
                switch (parseInt(item.Id_parametro)) {
                    case 14: //Ph
                    case 100:
                    case 26:// Gasto
                    case 13: // GA
                    case 12: //Coliformes
                    case 134: 
                    case 132:
                    case 67: //conductividad
                    case 2: //Materia Floatante
                    case 97: //Températura 
                    case 100:
                    case 5:
                        if(item.Liberado != 1)
                        {
                            color = "danger"
                        }else{
                            color = "success";
                        }
                        break;
                
                    default:
                        break;
                }
                tab += '<tr>';
                tab += '<td>' + item.Id_codigo + '</td>';
                tab += '<td class="bg-' + color + '">' + item.Parametro + '</td>';
                tab += '<td>' + item.Tipo_formula + '</td>';
                // tab += '<td>' + item.Resultado + '</td>';
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
            switch (response.paraModel.Id_parametro) {
                case "17": // Arsenico
                case "208":
                case "207": //Aluminio 
                case "231":
                case "20": // Cobre
                case "22": //Mercurio
                case "215":
                case "25": //Zinc
                case "227":
                case "24": //Plomo
                case "216":  
                case "21": //Cromoa
                case "264":
                case "18": //Cadmio
                case "210":
                case "300": //Niquel
                case "233": // Seleneio
                case "213": //Fierro 
                case "197":
                case "188":
                case "189":
                case "190":
                case "191":
                case "192":
                case "194":
                case "195":
                case "196":
                case "204":
                case "219":
                case "230":
                case "23":
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
                   
                case "15": // fosforo
                case "19": // Cianuros
                case "7": //Nitrats
                case "8": //Nitritos
                case "152": //COT
                case "99": //Cianuros 127
                case "105": //Floururos 127
                case "106":
                case "107":
                case "96":
                case "95": // Sulfatos
                case "87": // silice 
                case "79":
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
                //Volumnetria

                case "11":
                    tab += '<button class="btn btn-danger" id="btnRegresar">Regresar resultado</button>'
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
                    tab += '<td>' + response.model.Resultado2 + '</td>';
                    aux = aux + parseFloat(response.model.Resultado2);
                    tab += '</tr>';
                    $.each(response.aux, function (key, item) {
                        if (item.Id_parametro == "7" || item.Id_parametro == "8") {
                            tab += '<tr>';
                            tab += '<td>' + item.Parametro + '</td>';
                            if (item.Resultado <= item.Limite) {
                                tab += '<td>< ' + item.Limite + '</td>';
                                aux = aux + parseFloat(item.Limite);
                            } else {
                                tab += '<td>' + item.Resultado + '</td>';
                                aux = aux + parseFloat(item.Resultado);
                            }
                            tab += '</tr>';
                        }
                    });
                    resLiberado = aux.toFixed(2);
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                case "9":
                case "10":
                case "108": 
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
                        resLiberado = item.Resultado;
                        tab += '</tr>';
                    });
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                case 6:
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
                        resLiberado = item.Resultado;
                        tab += '</tr>';
                    });
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                case "83":
                    console.log("Entro a kendal")
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
                        if (item.Resultado <= item.Limite) {
                            tab += '<td> < ' + item.Limite + '</td>';
                            resLiberado = resLiberado + parseFloat(item.Limite);
                        } else {
                            tab += '<td>' + item.Resultado + '</td>';
                            resLiberado = resLiberado + parseFloat(item.Resultado);   
                        }
                        tab += '</tr>';
                    });
                    $.each(response.aux, function (key, item) {
                        tab += '<tr>';
                        tab += '<td>' + item.Parametro + '</td>';
                        if (item.Resultado <= item.Limite) {
                            tab += '<td> < ' + item.Limite + '</td>';
                            resLiberado = resLiberado + parseFloat(item.Limite);
                        } else {
                            tab += '<td>' + item.Resultado + '</td>';
                            resLiberado = resLiberado + parseFloat(item.Resultado);   
                        }
                        tab += '</tr>';
                    });
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                // case 218:
                case "64":
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
                        if ($("#idNorma").val() == "27") {
                            tab += '<td>Cloruros Totales (Cl¯)</td>';
                            tab += '<td>' + item.Cloruros + '</td>';
                            resLiberado = resLiberado + parseFloat(item.Cloruros);
                        } else {
                            tab += '<td>' + item.Parametro + '</td>';
                            tab += '<td>' + item.Resultado + '</td>';
                            resLiberado = resLiberado + parseFloat(item.Resultado);
                        }
                        tab += '</tr>';
                    });
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
                        tab += '<td>' + (response.aux[cont] * item.Resultado).toFixed(2) + '</td>';
                        tab += '</tr>';
                        if (item.Resultado != null) {
                            aux = aux + (response.aux[cont] * item.Resultado);
                            cont++;
                        }
                    });
                    resLiberado = (aux).toFixed(2);
                    // if ($("#idNorma").val() == "27") {
                    //     aux = 0; 
                    //     cont = 0;
                    // $.each(response.model, function (key, item) {
                    //         tab += '<tr>';
                    //         tab += '<td> GA - ' + item.Num_tomas + '</td>';
                    //         tab += '<td>' + item.Resultado + '</td>';
                    //         tab += '<td>' + (response.aux[cont] * item.Resultado).toFixed(2) + '</td>';
                    //         tab += '</tr>';
                    //         if (item.Resultado != null) {
                    //             aux = aux + (response.aux[cont] * item.Resultado);
                    //             cont++;
                    //         }
                    //     });
                    //     resLiberado = (aux).toFixed(2);
                    // } else {
                    //     $.each(response.model, function (key, item) {
                    //         tab += '<tr>';
                    //         tab += '<td>' + item.Parametro + '</td>';
                    //         tab += '<td>' + item.Resultado + '</td>';
                    //         tab += '</tr>';
                    //     });
                    //     resLiberado = (response.aux);
                    // }
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                case "5":
                    console.log("Entro a id 5")
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
                        if (item.Sugerido == 1) {
                            tab += '<td class="bg-success">';
                            resLiberado = item.Resultado;
                        } else {
                            tab += '<td>';
                        }
                        tab += '' + item.Parametro + '</td>';
                        tab += '<td>' + item.Resultado + '</td>';
                        tab += '</>';
                    });
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                case "12":
                case "133":
                case "134":
                    tab += '<button class="btn btn-danger" id="btnRegresar">Regresar resultado</button>'
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
                        aux = aux * parseFloat(item.Resultado);
                        tab += '<tr>';
                        tab += '<td>' + item.Parametro + '</td>';
                        tab += '<td>' + item.Resultado + '</td>';
                        tab += '</tr>';
                        cont++;
                    });
                    // resLiberado = (aux / cont);
                    resLiberado = (Math.pow(aux, 1 / cont));
                    console.log("Res: "+resLiberado);
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                case "253":
                    tab += '<button class="btn btn-danger" id="btnRegresar">Regresar resultado</button>'
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
                        aux = aux * parseFloat(item.Resultado);
                        tab += '<tr>';
                        tab += '<td>' + item.Parametro + '</td>';
                        tab += '<td>' + item.Resultado + '</td>';
                        tab += '</tr>';
                        cont++;
                    });
                    // resLiberado = (aux / cont);
                    resLiberado = (Math.pow(aux, 1 / cont));
                    console.log(resLiberado);
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                case "35":
                    tab += '<button class="btn btn-danger" id="btnRegresar">Regresar resultado</button>'
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
                        aux = aux * parseFloat(item.Resultado);
                        tab += '<tr>';
                        tab += '<td>' + item.Parametro + '</td>';
                        tab += '<td>' + item.Resultado + '</td>';
                        tab += '</tr>';
                        cont++;
                    });
                    // resLiberado = (aux / cont);
                    resLiberado = (Math.pow(aux, 1 / cont));
                    console.log(resLiberado);
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    break;
                case "16":
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
                case "78":
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

                case "3":// Solidos
                case "4":// Solidos
                case "112":// Solidos
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
                //Campo
                case "26"://Gasto
                case "2": //Materia flotante
                case "14": //ph
                case "110": //ph
                case "97": //Temperatura
                case "67"://Conductividad
                case "68":
                    console.log("entro a caso 7");
                    tab += '<button class="btn btn-danger" id="btnRegresar">Regresar resultado</button>'
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
                    if (response.codigoModel.Id_parametro == 26) { // Gasto
                        aux = 0;
                        cont = 0;
                        $.each(response.model, function (key, item) {
                            tab += '<tr>';
                            tab += '<td>Gasto Campo - ' + (cont + 1) + '</td>';
                            if (parseInt(response.solModel.Id_servicio) != 3) {
                                tab += '<td>' + item.Promedio + '</td>';   
                                aux = aux + parseFloat(item.Promedio);
                            } else { 
                                tab += '<td>' + item.Resultado + '</td>';
                                aux = aux + parseFloat(item.Promedio);
                            }
                            tab += '</tr>';
                            cont++
                        });
                        resLiberado = (aux / cont);
                    } else if (response.codigoModel.Id_parametro == 67 || response.codigoModel.Id_parametro == 68) { // Gasto
                        aux = 0;
                        cont = 0;
                        if ($("#idNorma").val() == "27") {
                            $.each(response.model, function (key, item) {
                                tab += '<tr>';
                                tab += '<td>Conductividad Campo - ' + (cont + 1) + '</td>';
                                tab += '<td>' + item.Promedio + '</td>';
                                tab += '<td>' + (response.aux[cont] * item.Promedio) + '</td>';
                                tab += '</tr>';
                                if (item.Promedio != null) {
                                    aux = aux + (response.aux[cont] * item.Promedio);
                                    cont++;
                                }
                            });
                            resLiberado = (aux).toFixed(2);
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
                        if ($("#idNorma").val() == "27") {
                            $.each(response.model, function (key, item) {
                                tab += '<tr>';
                                if (response.solModel.Id_muestra == 1) {
                                    tab += '<td> Ph</td>';
                                    tab += '<td>' + item.Promedio + '</td>';   
                                    aux =  item.Promedio;
                                } else {  
                                    tab += '<td> pH compuesto</td>';
                                    tab += '<td>' + item.Ph_muestraComp + '</td>';
                                    tab += '<td>------</td>';
                                    // tab += '<td>' + (response.aux[cont] * item.Promedio).toFixed(2) + '</td>';
                                    if (item.Ph_muestraComp != null) {
                                        aux =  item.Ph_muestraComp;
                                        cont++;
                                    }
                                }
                                tab += '</tr>';
                            });
                            resLiberado = parseFloat(aux).toFixed(2);
                        } else {
                            $.each(response.model, function (key, item) {
                                tab += '<tr>';
                                if (parseInt(response.solModel.Id_servicio) != 3) {
                                    if (response.solModel.Id_muestra == 1) {
                                        tab += '<td> Ph</td>';
                                        tab += '<td>' + item.Promedio + '</td>';   
                                    } else { 
                                        tab += '<td> pH Compuesto</td>';
                                        tab += '<td>' + item.Ph_muestraComp + '</td>';      
                                    }
                                } else { 
                                    tab += '<td> pH - ' + (cont + 1) + '</td>';
                                    tab += '<td>' + item.Resultado + '</td>';
                                }
                                tab += '</tr>';
                                if (item.Ph_muestraComp != null || item.Resultado != null || item.Promedio != null) {
                                    if (parseInt(response.solModel.Id_servicio) != 3) {
                                        if (response.solModel.Id_muestra == 1) {
                                            aux = aux + parseFloat(item.Promedio); 
                                        } else {
                                            aux = aux + parseFloat(item.Ph_muestraComp);   
                                        }
                                    } else { 
                                        aux = aux + parseFloat(item.Resultado);
                                    }
                                    cont++; 
                                }
                            });
                            resLiberado = (aux / cont).toFixed(2);
                        }
                    } else if (response.codigoModel.Id_parametro == "97" || response.codigoModel.Id_parametro == "100") { // Temperatura
                        aux = 0;
                        cont = 0;
                        if ($("#idNorma").val() == "27") {
                            $.each(response.model, function (key, item) {
                                tab += '<tr>';
                                tab += '<td> Temperatura - ' + item.Num_toma + '</td>';
                                tab += '<td>' + item.Promedio + '</td>';
                                tab += '<td>' + (response.aux[cont] * item.Promedio).toFixed(2) + '</td>';
                                tab += '</tr>';
                                if (item.Promedio != null) {
                                    aux = aux + (response.aux[cont] * item.Promedio);
                                    cont++;
                                }
                            });
                            resLiberado = (aux).toFixed();
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
                case "95":// Potable
                case "116":
                    console.log("entro a caso 8");
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
                case "66":
                case "65":
                case "98":
                case "89":
                case "218":
                case "84": // Olor
                case "86": // Sabor
                    console.log("entro a caso 8");
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
                default:
                    console.log("entro a break");
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