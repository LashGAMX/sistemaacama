
var idSub;
$(document).ready(function () {
    getPaquetes();    
});

$('#btnAddPlan').click(function () {
    getPlanMuestreo();
});

$('#btnAddMaterial').click(function () {
    getComplemento(1);
});
$('#btnAddEquipo').click(function () {
    getComplemento(2);
});
$('#btnAddComplemento').click(function () {
    getComplemento(3);
});



function getPaquetes() {

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
                tab += '<td>' + item.Id_subnorma + '</td>';
                tab += '<td>' + item.Norma + '</td>';
                tab += '<td>' + item.Clave + '</td>';
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
                },
                "scrollY": 200,
                "scrollCollapse": true
            });


            $('#tablePaquetes tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                }
                else {
                    t.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            });
            $('#tablePaquetes tr').on('click', function () {
                let dato = $(this).find('td:first').html();
                getEnvase(dato);
                getMaterial(dato);
                getEquipo(dato);
                getComplementoCamp(dato);
                idSub = dato;
            });
        }
    });
}
function getEnvase(id) {

    let tabla = document.getElementById('divTableEnvase');
    let tab = '';
    $.ajax({
        type: "POST",
        url: base_url + "/admin/campo/configuracion/getEnvase",
        data: {
            idPaquete: id,
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
                tab += '<td>' + item.Area + '</td>';
                tab += '<td>' + item.Cantidad + '</td>';
                tab += '<td>' + item.Envase + '</td>';
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
                },
                "scrollY": 150,
                "scrollCollapse": true,
                "pageLength": 30
            });


            $('#tableEnvase tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                }
                else {
                    t.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            });
            $('#tableEnvase tr').on('click', function () {
                let dato = $(this).find('td:first').html();
            });
        }
    });
}

function getPlanMuestreo() {
    let tab = '';
    let cont = 0;
    let sw = false;
    let temp = 0;
    $.ajax({
        url: base_url + "/admin/campo/configuracion/getPlanMuestreo",
        type: 'POST', //método de envio
        data: {
            idSub: idSub,
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response)
            tab += '<table id="tableAreas" class="display compact cell-border" style="width:100%">';
            tab += '    <thead>';
            tab += '        <tr>';
            tab += '          <th>Activo</th>';
            tab += '          <th>Area</th>';
            tab += '          <th>Cantidad</th>';
            tab += '          <th>Recipiente</th>';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            tab += '    <button class="btn btn-info" id="btnAllArea"><i class="fas fa-check-square"></i></button>';
            $.each(response.model, function (key, item) {
                temp = 0;
                sw = false;
                $.each(response.datoModel, function (key, item3) {
                    temp++;
                    if (item.Id_area == item3.Id_area) {
                        sw = true;
                        cont = temp;
                    }
                });
                console.log(cont)
                tab += '<tr>';
                if (sw == true) {
                    tab += '<td><input checked type="checkbox" class="custom-control-input" name="ckAreas" value="' + item.Id_area + '"></td>';
                } else {
                    tab += '<td><input type="checkbox" class="custom-control-input" name="ckAreas" value="' + item.Id_area + '"></td>';
                }
                tab += '<td>' + item.Area + '</td>';
                if (sw == true) {
                    tab += '<td><input type="number" id="cantArea' + item.Id_area + '" value="' + response.datoModel[cont - 1].Cantidad + '"></td>';
                } else {
                    tab += '<td><input type="number" id="cantArea' + item.Id_area + '"></td>';
                }
                tab += '<td>';
                tab += '<select id="envArea' + item.Id_area + '">'
                $.each(response.envase, function (key, item2) {
                    if (sw == true) {
                        if (item2.Id_envase == response.datoModel[cont - 1].Id_recipiente) {
                            tab += '<option value="' + item2.Id_envase + '" selected>' + item2.Nombre + '</option>';
                        } else {
                            tab += '<option value="' + item2.Id_envase + '">' + item2.Nombre + '</option>';
                        }
                    } else {
                        tab += '<option value="' + item2.Id_envase + '">' + item2.Nombre + '</option>';
                    }

                });
                tab += '<select>'
                tab += '    </td>';
                tab += '</tr>';
            });
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;

            itemModal[0] = [
                tab,
            ]
            newModal('divModal', 'modalAreas', 'Areas plan', '', 1, 1, 0, inputBtn('', '', 'Guardar', 'fas fa-save', 'btn btn-success', 'setPlanMuestreo()'))

            var t = $('#tableAreas').DataTable({
                "ordering": false,
                "language": {
                    "lengthMenu": "# _MENU_ por pagina",
                    "zeroRecords": "No hay datos encontrados",
                    "info": "Pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay datos encontrados",
                },
                "scrollY": 300,
                "scrollCollapse": true,
                "paging": false
            });

            $('#btnAllArea').click(function () {
                allSelectCheck("ckAreas");
            });
        }
    });
}

function setPlanMuestreo() {
    let elementos = document.getElementsByName("ckAreas");
    let areas = new Array();
    let cant = new Array();
    let envase = new Array();

    for (var i = 0; i < elementos.length; i++) {
        if (elementos[i].checked) {            
            areas.push(elementos[i].value);
            cant.push($("#cantArea" + elementos[i].value).val())
            envase.push($("#envArea" + elementos[i].value).val())
        }
    }    

    $.ajax({
        type: "POST",
        url: base_url + "/admin/campo/configuracion/setPlanMuestreo",
        data: {
            idSub: idSub,
            areas: areas,
            cantidad: cant,
            envase: envase,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            getEnvase(idSub);
        }
    });

}
function getMaterial(id) {

    let tabla = document.getElementById("divTableMaterial");
    let tab = '';
    $.ajax({
        type: "POST",
        url: base_url + "/admin/campo/configuracion/getMaterial",
        data: {
            idSub: id,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            tab += '<table id="tableMaterial" class="display compact cell-border" style="width:100%">';
            tab += '    <thead>';
            tab += '        <tr>';
            // tab += '          <th>Paquete</th>';
            tab += '          <th>Material</th>';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            $.each(response.model, function (key, item) {
                tab += '<tr>';
                tab += '<td>' + item.Complemento + '</td>';
                tab += '</tr>';
            });
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;


            var t = $('#tableMaterial').DataTable({
                "ordering": false,
                "language": {
                    "lengthMenu": "# _MENU_ por pagina",
                    "zeroRecords": "No hay datos encontrados",
                    "info": "Pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay datos encontrados",
                },
                "scrollY": 150,
                "scrollCollapse": true,
                "pageLength": 30
            });

        }
    });
}

function getEquipo(id) {

    let tabla = document.getElementById("divTableEquipo");
    let tab = '';
    $.ajax({
        type: "POST",
        url: base_url + "/admin/campo/configuracion/getEquipo",
        data: {
            idSub: id,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            tab += '<table id="tableEquipo" class="display compact cell-border" style="width:100%">';
            tab += '    <thead>';
            tab += '        <tr>';
            // tab += '          <th>Paquete</th>';
            tab += '          <th>Material</th>';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            $.each(response.model, function (key, item) {
                tab += '<tr>';
                tab += '<td>' + item.Complemento + '</td>';
                tab += '</tr>';
            });
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;


            var t = $('#tableEquipo').DataTable({
                "ordering": false,
                "language": {
                    "lengthMenu": "# _MENU_ por pagina",
                    "zeroRecords": "No hay datos encontrados",
                    "info": "Pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay datos encontrados",
                },
                "scrollY": 150,
                "scrollCollapse": true,
                "pageLength": 30
            });

        }
    });
}
function getComplementoCamp(id) {

    let tabla = document.getElementById("divTableComplementoCamp");
    let tab = '';
    $.ajax({
        type: "POST",
        url: base_url + "/admin/campo/configuracion/getComplementoCamp",
        data: {
            idSub: id,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            tab += '<table id="tableComplementoCamp" class="display compact cell-border" style="width:100%">';
            tab += '    <thead>';
            tab += '        <tr>';
            // tab += '          <th>Paquete</th>';
            tab += '          <th>Material</th>';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            $.each(response.model, function (key, item) {
                tab += '<tr>';
                tab += '<td>' + item.Complemento + '</td>';
                tab += '</tr>';
            });
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;


            var t = $('#tableComplementoCamp').DataTable({
                "ordering": false,
                "language": {
                    "lengthMenu": "# _MENU_ por pagina",
                    "zeroRecords": "No hay datos encontrados",
                    "info": "Pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay datos encontrados",
                },
                "scrollY": 150,
                "scrollCollapse": true,
                "pageLength": 30
            });

        }
    });
}

function getComplemento(tipo) {    
    let tab = '';
    let sw = false;
    let temp = 0;
    $.ajax({
        url: base_url + "/admin/campo/configuracion/getComplemento",
        type: 'POST', //método de envio
        data: {
            idSub: idSub,
            tipo:tipo,
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response)
            
            tab += '<table id="tableComplemento" class="display compact cell-border" style="width:100%">';
            tab += '    <thead>';
            tab += '        <tr>';
            tab += '          <th>Activo</th>';
            tab += '          <th>Complemento</th>';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            tab += '    <button class="btn btn-info" id="btnAllComplemento"><i class="fas fa-check-square"></i></button>';
            $.each(response.model, function (key, item) {
                sw = false;
                $.each(response.comModel, function (key, item2) {
                    temp++;
                    if (item.Id_complemento == item2.Id_complemento) {
                        sw = true;
                    }
                });
                tab += '<tr>';
                if (sw == true) {
                    tab += '<td><input checked type="checkbox" class="custom-control-input" name="ckComplemento" value="' + item.Id_complemento + '"></td>';
                } else {
                    tab += '<td><input type="checkbox" class="custom-control-input" name="ckComplemento" value="' + item.Id_complemento + '"></td>';
                }
                tab += '<td>' + item.Complemento + '</td>';
                tab += '</tr>';
            });
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;

            itemModal[1] = [
                tab,
            ]
            switch (tipo) {
                case 1:
                    newModal('divModal', 'modalComplemento', 'Material de medicion', '', 1, 1, 1, inputBtn('', '', 'Guardar', 'fas fa-save', 'btn btn-success', 'setComplemento(1)'))       
                    break;
                case 2:
                    newModal('divModal', 'modalComplemento', 'Equipo de muestreo', '', 1, 1, 1, inputBtn('', '', 'Guardar', 'fas fa-save', 'btn btn-success', 'setComplemento(2)'))       
                    break;
                case 3:
                    newModal('divModal', 'modalComplemento', 'Complemento', '', 1, 1, 1, inputBtn('', '', 'Guardar', 'fas fa-save', 'btn btn-success', 'setComplemento(3)'))       
                    break;
                default:
                    break;
            }

            $('#tableComplemento').DataTable({
                "ordering": false,
                "language": {
                    "lengthMenu": "# _MENU_ por pagina",
                    "zeroRecords": "No hay datos encontrados",
                    "info": "Mostrando resultados",
                    /* "info": "Pagina _PAGE_ de _PAGES_", */
                    "infoEmpty": "No hay datos encontrados",
                },
                "scrollY": 250,
                "scrollCollapse": true,
                "paging": false
            });

            $('#btnAllComplemento').click(function () {
                allSelectCheck("ckComplemento");
            });
        }
    });
}

function setComplemento(tipo) {
    let elementos = document.getElementsByName("ckComplemento");
    let com = new Array();

    let table = $("#tableComplemento input:checked")    

    for (var i = 0; i < elementos.length; i++) {
        if (elementos[i].checked) {
            com.push(elementos[i].value);
        }
    }

    $.ajax({
        type: "POST",
        url: base_url + "/admin/campo/configuracion/setComplemento",
        data: {
            idSub: idSub,
            tipo:tipo,
            complemento:com,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            switch (tipo) {
                case 1:
                    getMaterial(idSub)
                    break;
                case 2:
                    getEquipo(idSub)
                    break;
                case 3:
                    getComplementoCamp(idSub)
                    break;
                default:
                    break;
            }
        }
    });

}
function allSelectCheck(id) {
    let elementos = document.getElementsByName(id);
    let sw = false;      

    //comprobar estado
    for (var i = 0; i < elementos.length; i++) {
        if (elementos[i].checked) {
            sw = true;
        }
    }
    console.log("Sw:" + sw)    

    if (sw == true) {
        for (var i = 0; i < elementos.length; i++) {
            elementos[i].checked = false;            
        }
    } else {
        for (var i = 0; i < elementos.length; i++) {
            elementos[i].checked = true;            
        }
    }
}