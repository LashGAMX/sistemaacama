var idUser = $("#idUser").val();
var idBMR = 0;
$(document).ready(function () {
    getParametro();

    tabla = $("#tableStd").DataTable({
        "responsive": true,
        "autoWidth": true,
        stateSave: true,
        "language": {
            "lengthMenu": "Mostrar _MENU_ por pagina",
            "zeroRecords": "Datos no encontrados",
            "info": "Mostrando _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos",
            "infoFiltered": "(filtered from _MAX_ total records)",
        }
    });

    
    //* Acciones para los botones
    $('#btnEdit tr').on('click', function () {
        let dato = $(this).find('td:first').html();
        idlot = dato;
    });

    $("#CreateStd").click(function () {
        if ($("#fechaInicio").val() == "" || $("#fechaFin").val() == "") {
            alert("Ingresa una fecha valida")
        } else {
            createStd();
        }
    });

    $("#buscar").click(function () {
        buscar();
        tablaVigencias();
    });
    $("#replicar").click(function () {
        curvaHijos();  
    });
  
    $('#setConstantes').click(function () {
        setConstantes();
    });
    $("#formula").click(function () {
        setCalcular();
        
    });
    
    $("#idArea").on("change", function () {
        console.log('getParametro')
        getParametro();
    });

    $("#idAreaModal").on("change", function () {
        console.log('evento activado area');
        getLote();
        getParametroModal();
    });

    $("#idLoteModal").on('change', function () {
        console.log('evento activado parametro');
    });



});

function getParametroDetalle() {
    $.ajax({
        url: base_url + '/admin/laboratorio/getParametroDetalle', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            idLote: $("#idLoteModal").val(),

            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response);

        }
    });
}
function setConstantes() {

    //fecha = fecha.toLocaleDateString();
    console.log(fecha);

    $.ajax({
        url: base_url + '/admin/laboratorio/setConstantes', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            idLote: $("#idLote").val(),
            fecha: $("#fecha").val(),
            area: $('#idArea').val(),
            parametro: $("#parametro").val(),
            b: $("#b").val(),
            m: $("#m").val(),
            r: $("#r").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response);
            if (response) {
                swal("Datos guardados!", "Guardado!", "success");
            }
        }
    });
}

function getParametro() {

    let div = document.getElementById("divParametro");
    let tab = "";

    $.ajax({
        url: base_url + '/admin/laboratorio/getParametro', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            idUser:idUser,
            idLote: $("#idLote").val(),
            idArea: $("#idArea").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response);
            tab += '<select class="form-control" id="parametro">';
            tab += '<option value="">Selecciona Parametro</option>';
            $.each(response.parametro, function (key, item) {
                tab += '<option value="' + item.Id_parametro + '">('+item.Id_parametro+') '+ item.Parametro + ' / '+item.Tipo_formula+' / '+item.Area_analisis+' ('+item.Id_area+')</option>';
            });
            tab += '</select>';
            div.innerHTML = tab;

        }
    });

}
function getParametroModal() {

    let div = document.getElementById("divParametroModal");
    let tab = "";

    $.ajax({
        url: base_url + '/admin/laboratorio/getParametroModal', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {

            idArea: $("#idArea").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response);
            tab += '<select class="form-control" id="idParametroModal">';
            tab += '<option value="">Selecciona Parametro</option>';
            $.each(response.parametro, function (key, item) {
                tab += '<option value="' + item.Id_parametro + '">('+item.Id_parametro+') '+ item.Parametro + ' / '+item.Tipo_formula+' / '+item.Id_area+'</option>';
            });
            tab += '</select>';
            div.innerHTML = tab;

        }
    });

}

function getLote() {  // motodo para obtener el lote en la modal

    let div = document.getElementById("DivLoteModal");
    let tab = "";

    $.ajax({
        url: base_url + '/admin/laboratorio/getLote', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            //idLote:$("#idLote").val(),
            idArea: $("#idArea").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response);
            tab += '<select class="form-control" id="idLoteModal">';
            tab += '<option value="">Selecciona Lote</option>';
            $.each(response.lote, function (key, item) {
                tab += '<option value="' + item.Id_lote + '">' + item.Fecha + ' : ' + item.Id_lote + '</option>';
            });
            tab += '</select>';
            div.innerHTML = tab;


        }
    });

}
function curvaHijos(){
    $.ajax({
        url: base_url + '/admin/laboratorio/curvaHijos', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            parametro: $("#parametro").val(),
            idArea: $("#idArea").val(),
            idBMR: idBMR,
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response);
            swal("Correcto!", "Se replicó BMR!", "success");
        }
    });

}

function setCalcular() {
    getMatriz();
    $.ajax({
        url: base_url + '/admin/laboratorio/setCalcular', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            fecha: $("#fecha").val(),
            area: $('#idArea').val(),
            parametro: $("#parametro").val(),
            conArr: cont,
            arrCon: arrCon,
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response);
            $("#b").val(response.b.toFixed(5));
            $("#m").val(response.m.toFixed(5));
            $("#r").val(response.r.toFixed(5));
            buscar();
        }
    });
}

function promedio() {
    var suma = 0;
    var prom = 0;
    var abs1 = $('#ABS1').val();
    var abs2 = $('#ABS2').val();
    var abs3 = $('#ABS3').val();
    suma = abs1 + abs2 + abs3;
    prom = suma / 3;

    $.ajax({
        url: base_url + '/admin/laboratorio/promedio', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            promedio: prom,
            abs1: abs1,
            abs2: abs2,
            abs3: abs3,
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response);
            fix = response.resultado.toFixed(4);
            $("#promedio").val(fix);

        }
    });
}


function guardarSTd() {
    table = document.getElementById("tablaLote");

}
//------------------Formula BMR-----------------
function formula() {


    $.ajax({
        url: base_url + '/admin/laboratorio/formula', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            idLote: $("#idLote").val(),
            fecha: $("#fecha").val(),
            area: $('#idArea').val(),
            parametro: $("#parametro").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response);
            let fixb = response.b.toFixed(5);
            let fixm = response.m.toFixed(5);
            let fixr = response.r.toFixed(5);
            $("#b").val(fixb);
            $("#m").val(fixm);
            $("#r").val(fixr);
        }
    });

}

//------Create----------------
function createStd() {
    let tabla = document.getElementById('divTablaStd');
    let tab = '';



    $.ajax({
        url: base_url + '/admin/laboratorio/createStd', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            idArea: $("#idArea").val(),
            idParametro: $("#parametro").val(),
            fechaInicio: $("#fechaInicio").val(),
            fechaFin: $("#fechaFin").val(),

            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response);

///////////////////////////////Inicio de comparación del parametro 95 con 9 ABS/////////////////////////////////////////////////

            if (response.parametro == 113){
                if (response.valFecha == 1) {
                        
                    swal("Ups!", "Olvidaste definir un rango de fechas", "error")
                } else if (response.swCon == 1) {
                
                    swal("Ups!", "Este parametro no tiene concentraciones registradas", "error")
                } else if (response.sw == 1) {
                
                    swal("Ups!", "Ya existe una curva vigente para este parametro", "error")
                } else {

                    let i = 0;

                    tab += '<table id="tablaLote" class="table table-sm">';
                    tab += '    <thead class="thead-dark">';
                    tab += '        <tr>';
                    tab += '          <th>Id</th>';
                    tab += '          <th>Id Lote</th> ';
                    tab += '          <th>STD</th> ';
                    tab += '          <th>Concentracion</th> ';
                    tab += '          <th>ABS1</th> ';
                    tab += '          <th>ABS2</th> ';
                    tab += '          <th>ABS3</th> ';
                    tab += '          <th>ABS4</th> ';
                    tab += '          <th>ABS5</th> ';
                    tab += '          <th>ABS6</th> ';
                    tab += '          <th>ABS7</th> ';
                    tab += '          <th>ABS8</th> ';
                    tab += '          <th>Promedio</th> ';
                    tab += '        </tr>';
                    tab += '    </thead>';
                    tab += '    <tbody>';
                    $.each(response.stdModel, function (key, item) {
                        tab += '<tr>';
                        tab += '<td>' + item.Id_std + '</td>';
                        tab += '<td>' + item.Id_lote + '</td>';
                        tab += '<td>' + item.STD + '</td>';
                        if (item.Concentracion != '') {
                            tab += '<td><input value="' + response.concentracion[i].Concentracion + '"></td>';
                        } else {
                            tab += '<td><input value="' + item.Concentracion + '"></td>';
                        }
                        tab += '<td><input value="' + item.ABS1 + '"></td>';
                        tab += '<td><input value="' + item.ABS2 + '"></td>';
                        tab += '<td><input value="' + item.ABS3 + '"></td>';
                        tab += '<td><input value="' + item.ABS4 + '"></td>';
                        tab += '<td><input value="' + item.ABS5 + '"></td>';
                        tab += '<td><input value="' + item.ABS6 + '"></td>';
                        tab += '<td><input value="' + item.ABS7 + '"></td>';
                        tab += '<td><input value="' + item.ABS8 + '"></td>';
                        tab += '<td><input value="' + item.Promedio + '"></td>';
                        tab += '</tr>';
                        i++;
                    });

                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    $("#modalCrear").modal('hide');
                    swal("Registro!", "Se crearon los estandares!", "success")
                }
////////////////////////////////////////////// Fin de condicion de sulfatos 95/////////////////////////////////////////////////////////////
            } else {
                    if (response.valFecha == 1) {
                        
                        swal("Ups!", "Olvidaste definir un rango de fechas", "error")
                    } else if (response.swCon == 1) {
                    
                        swal("Ups!", "Este parametro no tiene concentraciones registradas", "error")
                    } else if (response.sw == 1) {
                    
                        swal("Ups!", "Ya existe una curva vigente para este parametro", "error")
                    } else {

                        let i = 0;

                        tab += '<table id="tablaLote" class="table table-sm">';
                        tab += '    <thead class="thead-dark">';
                        tab += '        <tr>';
                        tab += '          <th>Id</th>';
                        tab += '          <th>Id Lote</th> ';
                        tab += '          <th>STD</th> ';
                        tab += '          <th>Concentracion</th> ';
                        tab += '          <th>ABS1</th> ';
                        tab += '          <th>ABS2</th> ';
                        tab += '          <th>ABS3</th> ';
                        tab += '          <th>Promedio</th> ';
                        tab += '        </tr>';
                        tab += '    </thead>';
                        tab += '    <tbody>';
                        $.each(response.stdModel, function (key, item) {
                            tab += '<tr>';
                            tab += '<td>' + item.Id_std + '</td>';
                            tab += '<td>' + item.Id_lote + '</td>';
                            tab += '<td>' + item.STD + '</td>';
                            if (item.Concentracion != '') {
                                tab += '<td><input value="' + response.concentracion[i].Concentracion + '"></td>';
                            } else {
                                tab += '<td><input value="' + item.Concentracion + '"></td>';
                            }
                            tab += '<td><input value="' + item.ABS1 + '"></td>';
                            tab += '<td><input value="' + item.ABS2 + '"></td>';
                            tab += '<td><input value="' + item.ABS3 + '"></td>';
                            tab += '<td><input value="' + item.Promedio + '"></td>';
                            tab += '</tr>';
                            i++;
                        });

                        tab += '    </tbody>';
                        tab += '</table>';
                        tabla.innerHTML = tab;
                        $("#modalCrear").modal('hide');
                        swal("Registro!", "Se crearon los estandares!", "success")


                    }
            }

        }

    });
}
function tablaVigencias(){
    let tabla = document.getElementById('divTablaVigencias');
    let tab = '';
    $.ajax({
        url: base_url + '/admin/laboratorio/tablaVigencias', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            fecha: $("#fecha").val(),
            area: $('#idArea').val(),
            parametro: $("#parametro").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response);
            if (response.model != null) {
                tab += '<table id="tablaVigencias" class="table" style="width: 100%; font-size: 14px">';
                tab += '    <thead class="thead-dark">';
                tab += '        <tr>';
                tab += '          <th>ID Parametro</th>';
                tab += '          <th>Fecha inicio</th> ';
                tab += '          <th>Fecha fin</th> ';
                tab += '        </tr>';
                tab += '    </thead>';
                tab += '    <tbody>';
                $.each(response.model, function (key, items) {
                    tab += '<tr>';
                    tab += '<td>' + items.Id_parametro + '</td>';
                    tab += '<td>' + items.Fecha_inicio + '</td>';
                    tab += '<td>' + items.Fecha_fin + '</td>';
                    tab += '</tr>'; 
                });
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
            } else {
                tab += '<table id="tablaVigencias" class="table table-sm">';
                tab += '    <thead class="thead-dark">';
                tab += '        <tr>';
                tab += '          <th>ID Parametro</th>';
                tab += '          <th>Fecha inicio</th> ';
                tab += '          <th>Fecha fin</th> ';
                tab += '        </tr>';
                tab += '    </thead>';
                tab += '    <tbody>';

                    tab += '<tr>';
                    tab += '<td></td>';
                    tab += '<td></td>';
                    tab += '<td></td>';
                    tab += '</tr>'; 

                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
            }
           
        }
    });
}
//---------buscar ----------------------------
var res = new Array();
var cont = 0;
var idLote = 0;
function buscar() {
    $("#b").val("");
    $("#m").val("");
    $("#r").val("");
    let tabla = document.getElementById('divTablaStd');
    let tablaHijos = document.getElementById('divTablaHijos');
    let tab = '';
    let tabH = '';
    $.ajax({
        url: base_url + '/admin/laboratorio/buscar', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            //idLote:$("#idLote").val(),
            fecha: $("#fecha").val(),
            area: $('#idArea').val(),
            parametro: $("#parametro").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response);
            idBMR = response.idBMR;
            console.log(idBMR);
            if (response.valbmr != false) {
                $("#b").val(response.bmr.B);
                $("#m").val(response.bmr.M);
                $("#r").val(response.bmr.R);
                $("#vigencia").text(response.bmr.Fecha_inicio + " / " + response.bmr.Fecha_fin)
            } 
            res = response.concentracion;
            cont = 0;
            if (response.parametro == 113) {
                if (response.sw == false) {
                    /////////////////////////////// en caso de que no se encuentre ningun registro se limpia la tabla de registros anteriores.
                   
                    tab += '<table id="tablaLote" class="table table-sm">';
                    tab += '    <thead class="thead-dark">';
                    tab += '        <tr>';
                    tab += '          <th>Id</th>';
                    tab += '          <th>STD</th> ';
                    tab += '          <th>Concentracion</th> ';
                    tab += '          <th>ABS1</th> ';
                    tab += '          <th>ABS2</th> ';
                    tab += '          <th>ABS3</th> ';
                    tab += '          <th>Promedio</th> ';
                    tab += '        </tr>';
                    tab += '    </thead>';
                    tab += '    <tbody>';
                    tab += '<tr>';
                    tab += '<td></td>';
                    tab += '<td></td>';
                    tab += '<td></td>';
                    tab += '<td></td>';
                    tab += '<td></td>';
                    tab += '<td></td>';
                    tab += '<td></td>';
                    tab += '</tr>';
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    // tabla Hijos---------------------------------------
                    tabH += '<table id="tablaHijos" class="table">';
                    tabH += '    <thead class="thead-dark">';
                    tabH += '        <tr>';
                    tabH += '          <th>Id</th>';
                    tabH += '          <th>Parametro</th> ';
                    tabH += '        </tr>';
                    tabH += '    </thead>';
                    tabH += '    <tbody>';
                                tabH += '<tr>';
                                tabH += '</tr>'; 
                    tabH += '    </tbody>';
                    tabH += '</table>';
                    tablaHijos.innerHTML = tabH;
                    swal("Ups!", "Necesitas generar estandares para este parametro", "error");
    
                    /////////////////////////////////////////////////////////////////
                } else {
                    tab += '<table id="tablaLote" class="table table-sm">';
                    tab += '    <thead class="thead-dark">';
                    tab += '        <tr>';
                    tab += '          <th>Id</th>';
                    tab += '          <th>STD</th> ';
                    tab += '          <th>Concentracion</th> ';
                    tab += '          <th>ABS1</th> ';
                    tab += '          <th>ABS2</th> ';
                    tab += '          <th>ABS3</th> ';
                    tab += '          <th>ABS4</th> ';
                    tab += '          <th>ABS5</th> ';
                    tab += '          <th>ABS6</th> ';
                    tab += '          <th>ABS7</th> ';
                    tab += '          <th>ABS8</th> ';
                    tab += '          <th>Promedio</th> ';
                    tab += '        </tr>';
                    tab += '    </thead>';
                    tab += '    <tbody>';
                    
                    //todo Crea el blanco
                    $.each(response.stdModel, function (key, item) {
                        if (response.area == 2 || response.parametro == 113 || response.parametro == 243 ) {
                            if (cont == 0){
                                tab += '<tr>';
                                tab += '<td>' + item.Id_std + '</td>';
                                tab += '<td>' + item.STD + '</td>';
                                tab += '<td><input id="curCon' + cont + '" value="0"></td>';
                                tab += '<td><input id="curStd1' + cont + '" value="0.0"></td>';
                                tab += '<td><input id="curStd2' + cont + '" value="0.0"></td>'; 
                                tab += '<td><input id="curStd3' + cont + '" value="0.0"></td>';
                                tab += '<td><input id="curStd4' + cont + '" value="0.0"></td>';
                                tab += '<td><input id="curStd5' + cont + '" value="0.0"></td>';
                                tab += '<td><input id="curStd6' + cont + '" value="0.0"></td>';
                                tab += '<td><input id="curStd7' + cont + '" value="0.0"></td>';
                                tab += '<td><input id="curStd8' + cont + '" value="0.0"></td>';
                                tab += '<td><input id="curProm' + cont + '" value="0.0" readonly></td>';
                                tab += '</tr>'; 
                            } else {
                                tab += '<tr>';
                            tab += '<td>' + item.Id_std + '</td>';
                            tab += '<td>' + item.STD + '</td>';
                            tab += '<td><input  id="curCon' + cont + '" value="' + response.concentracion[cont -1].Concentracion + '"></td>';
                            tab += '<td><input id="curStd1' + cont + '" value="' + item.ABS1 + '"></td>';
                            tab += '<td><input id="curStd2' + cont + '" value="' + item.ABS2 + '"></td>';
                            tab += '<td><input id="curStd3' + cont + '" value="' + item.ABS3 + '"></td>';
                            tab += '<td><input id="curStd4' + cont + '" value="' + item.ABS4 + '"></td>';
                            tab += '<td><input id="curStd5' + cont + '" value="' + item.ABS5 + '"></td>';
                            tab += '<td><input id="curStd6' + cont + '" value="' + item.ABS6 + '"></td>';
                            tab += '<td><input id="curStd7' + cont + '" value="' + item.ABS7 + '"></td>';
                            tab += '<td><input id="curStd8' + cont + '" value="' + item.ABS8 + '"></td>';
                            tab += '<td><input id="curProm' + cont + '" value="' + item.Promedio + '" readonly></td>';
                            tab += '</tr>';
                            }
                            
                        }else{
                            tab += '<tr>';
                            tab += '<td>' + item.Id_std + '</td>';
                            tab += '<td>' + item.STD + '</td>';
                            tab += '<td><input  id="curCon' + cont + '" value="' + response.concentracion[cont].Concentracion + '"></td>';
                            tab += '<td><input id="curStd1' + cont + '" value="' + item.ABS1 + '"></td>';
                            tab += '<td><input id="curStd2' + cont + '" value="' + item.ABS2 + '"></td>';
                            tab += '<td><input id="curStd3' + cont + '" value="' + item.ABS3 + '"></td>';
                            tab += '<td><input id="curStd4' + cont + '" value="' + item.ABS4 + '"></td>';
                            tab += '<td><input id="curStd5' + cont + '" value="' + item.ABS5 + '"></td>';
                            tab += '<td><input id="curStd6' + cont + '" value="' + item.ABS6 + '"></td>';
                            tab += '<td><input id="curStd7' + cont + '" value="' + item.ABS7 + '"></td>';
                            tab += '<td><input id="curStd8' + cont + '" value="' + item.ABS8 + '"></td>';
                            tab += '<td><input id="curProm' + cont + '" value="' + item.Promedio + '" readonly></td>';
                            tab += '</tr>';
                        }
                        cont++;
                    });
                    
                            if (response.hijos != null) {
                                tab += '    </tbody>';
                                tab += '</table>';
                                tabla.innerHTML = tab;
    
                                tabH += '<table id="tablaHijos" class="table">';
                                tabH += '    <thead class="thead-dark">';
                                tabH += '        <tr>';
                                tabH += '          <th>Id</th>';
                                tabH += '          <th>Parametro</th> ';
                                tabH += '          <th>Parametro Padre</th> ';
                                tabH += '        </tr>';
                                tabH += '    </thead>';
                                tabH += '    <tbody>';
                                $.each(response.hijos, function (key, items) {
                                            tabH += '<tr>';
                                            tabH += '<td>' + items.Id_parametro + '</td>';
                                            tabH += '<td>' + items.Parametro + '</td>';
                                            tabH += '<td>' + items.Padre + '</td>';
                                            tabH += '</tr>'; 
                                });
                                tabH += '    </tbody>';
                                tabH += '</table>';
                                tablaHijos.innerHTML = tabH;
                            } else {
                                tab += '    </tbody>';
                                tab += '</table>';
                                tabla.innerHTML = tab;
    
                                tabH += '<table id="tablaHijos" class="table">';
                                tabH += '    <thead class="thead-dark">';
                                tabH += '        <tr>';
                                tabH += '          <th>Id</th>';
                                tabH += '          <th>Parametro</th> ';
                                tabH += '        </tr>';
                                tabH += '    </thead>';
                                tabH += '    <tbody>';
                                            tabH += '<tr>';
                                            tabH += '</tr>'; 
                                tabH += '    </tbody>';
                                tabH += '</table>';
                                tablaHijos.innerHTML = tabH;
                            }   
    
                }
                
            } else {
                if (response.sw == false) {
                    /////////////////////////////// en caso de que no se encuentre ningun registro se limpia la tabla de registros anteriores.
                
                    tab += '<table id="tablaLote" class="table table-sm">';
                    tab += '    <thead class="thead-dark">';
                    tab += '        <tr>';
                    tab += '          <th>Id</th>';
                    tab += '          <th>STD</th> ';
                    tab += '          <th>Concentracion</th> ';
                    tab += '          <th>ABS1</th> ';
                    tab += '          <th>ABS2</th> ';
                    tab += '          <th>ABS3</th> ';
                    tab += '          <th>Promedio</th> ';
                    tab += '        </tr>';
                    tab += '    </thead>';
                    tab += '    <tbody>';
                    tab += '<tr>';
                    tab += '<td></td>';
                    tab += '<td></td>';
                    tab += '<td></td>';
                    tab += '<td></td>';
                    tab += '<td></td>';
                    tab += '<td></td>';
                    tab += '<td></td>';
                    tab += '</tr>';
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    tab += '    </tbody>';
                    tab += '</table>';
                    tabla.innerHTML = tab;
                    // tabla Hijos---------------------------------------
                    tabH += '<table id="tablaHijos" class="table">';
                    tabH += '    <thead class="thead-dark">';
                    tabH += '        <tr>';
                    tabH += '          <th>Id</th>';
                    tabH += '          <th>Parametro</th> ';
                    tabH += '        </tr>';
                    tabH += '    </thead>';
                    tabH += '    <tbody>';
                                tabH += '<tr>';
                                tabH += '</tr>'; 
                    tabH += '    </tbody>';
                    tabH += '</table>';
                    tablaHijos.innerHTML = tabH;
                    swal("Ups!", "Necesitas generar estandares para este parametro", "error");

                    /////////////////////////////////////////////////////////////////
                } else {
                    tab += '<table id="tablaLote" class="table table-sm">';
                    tab += '    <thead class="thead-dark">';
                    tab += '        <tr>';
                    tab += '          <th>Id</th>';
                    tab += '          <th>STD</th> ';
                    tab += '          <th>Concentracion</th> ';
                    tab += '          <th>ABS1</th> ';
                    tab += '          <th>ABS2</th> ';
                    tab += '          <th>ABS3</th> ';
                    tab += '          <th>Promedio</th> ';
                    tab += '        </tr>';
                    tab += '    </thead>';
                    tab += '    <tbody>';
                    
                    //todo Crea el blanco
                    $.each(response.stdModel, function (key, item) {
                        if (response.area == 2 || response.parametro == 95 || response.parametro == 243 ) {
                            if (cont == 0){
                                tab += '<tr>';
                                tab += '<td>' + item.Id_std + '</td>';
                                tab += '<td>' + item.STD + '</td>';
                                tab += '<td><input id="curCon' + cont + '" value="0"></td>';
                                tab += '<td><input id="curStd1' + cont + '" value="0.0"></td>';
                                tab += '<td><input id="curStd2' + cont + '" value="0.0"></td>'; 
                                tab += '<td><input id="curStd3' + cont + '" value="0.0"></td>';
                                tab += '<td><input id="curProm' + cont + '" value="0.0" readonly></td>';
                                tab += '</tr>'; 
                            } else {
                                tab += '<tr>';
                            tab += '<td>' + item.Id_std + '</td>';
                            tab += '<td>' + item.STD + '</td>';
                            tab += '<td><input  id="curCon' + cont + '" value="' + response.concentracion[cont -1].Concentracion + '"></td>';
                            tab += '<td><input id="curStd1' + cont + '" value="' + item.ABS1 + '"></td>';
                            tab += '<td><input id="curStd2' + cont + '" value="' + item.ABS2 + '"></td>';
                            tab += '<td><input id="curStd3' + cont + '" value="' + item.ABS3 + '"></td>';
                            tab += '<td><input id="curProm' + cont + '" value="' + item.Promedio + '" readonly></td>';
                            tab += '</tr>';
                            }
                            
                        }else{
                            tab += '<tr>';
                            tab += '<td>' + item.Id_std + '</td>';
                            tab += '<td>' + item.STD + '</td>';
                            tab += '<td><input  id="curCon' + cont + '" value="' + response.concentracion[cont].Concentracion + '"></td>';
                            tab += '<td><input id="curStd1' + cont + '" value="' + item.ABS1 + '"></td>';
                            tab += '<td><input id="curStd2' + cont + '" value="' + item.ABS2 + '"></td>';
                            tab += '<td><input id="curStd3' + cont + '" value="' + item.ABS3 + '"></td>';
                            tab += '<td><input id="curProm' + cont + '" value="' + item.Promedio + '" readonly></td>';
                            tab += '</tr>';
                        }
                        cont++;
                    });
                    
                            if (response.hijos != null) {
                                tab += '    </tbody>';
                                tab += '</table>';
                                tabla.innerHTML = tab;

                                tabH += '<table id="tablaHijos" class="table">';
                                tabH += '    <thead class="thead-dark">';
                                tabH += '        <tr>';
                                tabH += '          <th>Id</th>';
                                tabH += '          <th>Parametro</th> ';
                                tabH += '          <th>Parametro Padre</th> ';
                                tabH += '        </tr>';
                                tabH += '    </thead>';
                                tabH += '    <tbody>';
                                $.each(response.hijos, function (key, items) {
                                            tabH += '<tr>';
                                            tabH += '<td>' + items.Id_parametro + '</td>';
                                            tabH += '<td>' + items.Parametro + '</td>';
                                            tabH += '<td>' + items.Padre + '</td>';
                                            tabH += '</tr>'; 
                                });
                                tabH += '    </tbody>';
                                tabH += '</table>';
                                tablaHijos.innerHTML = tabH;
                            } else {
                                tab += '    </tbody>';
                                tab += '</table>';
                                tabla.innerHTML = tab;

                                tabH += '<table id="tablaHijos" class="table">';
                                tabH += '    <thead class="thead-dark">';
                                tabH += '        <tr>';
                                tabH += '          <th>Id</th>';
                                tabH += '          <th>Parametro</th> ';
                                tabH += '        </tr>';
                                tabH += '    </thead>';
                                tabH += '    <tbody>';
                                            tabH += '<tr>';
                                            tabH += '</tr>'; 
                                tabH += '    </tbody>';
                                tabH += '</table>';
                                tablaHijos.innerHTML = tabH;
                            }   

                }
                
        }
    
        }

    });
}

var arrCon = new Array();
var conArrCon = new Array();
var conArrStd1 = new Array();
var conArrStd2 = new Array();
var conArrStd3 = new Array();
var conArrStdProm = new Array();

function getMatriz() {
    arrCon = new Array();
    conArrCon = new Array();
    conArrStd1 = new Array();
    conArrStd2 = new Array();
    conArrStd3 = new Array();
    conArrStd4 = new Array();
    conArrStd5 = new Array();
    conArrStd6 = new Array();
    conArrStd7 = new Array();
    conArrStd8 = new Array();
    conArrStdProm = new Array();
    
    if ($("#parametro").val() == 113){
       
        for (let i = 0; i < B; i++) {
            conArrCon.push($("#curCon" + i).val());
            conArrStd1.push($("#curStd1" + i).val());
            conArrStd2.push($("#curStd2" + i).val());
            conArrStd3.push($("#curStd3" + i).val());
            conArrStd4.push($("#curStd4" + i).val());
            conArrStd5.push($("#curStd5" + i).val());
            conArrStd6.push($("#curStd6" + i).val());
            conArrStd7.push($("#curStd7" + i).val());
            conArrStd8.push($("#curStd8" + i).val());
            conArrStdProm.push($("#curProm" + i).val());
        }
    
        arrCon.push(conArrCon);
        arrCon.push(conArrStd1);
        arrCon.push(conArrStd2);
        arrCon.push(conArrStd3);
        arrCon.push(conArrStd4);
        arrCon.push(conArrStd5);
        arrCon.push(conArrStd6);
        arrCon.push(conArrStd7);
        arrCon.push(conArrStd8);
        arrCon.push(conArrStdProm);
    } else {
        for (let i = 0; i < cont; i++) {
            conArrCon.push($("#curCon" + i).val());
            conArrStd1.push($("#curStd1" + i).val());
            conArrStd2.push($("#curStd2" + i).val());
            conArrStd3.push($("#curStd3" + i).val());
            conArrStdProm.push($("#curProm" + i).val());
        }

        arrCon.push(conArrCon);
        arrCon.push(conArrStd1);
        arrCon.push(conArrStd2);
        arrCon.push(conArrStd3);
        arrCon.push(conArrStdProm);
    }
}