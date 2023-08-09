$(document).ready(function () {

    table = $('#codigos').DataTable({
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        },
        "scrollY": 400,
        "scrollCollapse": true
    });
    $('#puntos').DataTable({
        "ordering": false,
        "pageLength": 500,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        },
        "scrollY": 400,
        "scrollCollapse": true
    });
    $('#btnSetCodigos').click(function () {
        if (confirm("Deseas generar codigos?")) {
            setGenFolio()
        }
    });
    $('#btnIngresar').click(function () {
        if (confirm("Esta segur@ de darle ingrseo a la muestra?")) {
            setIngresar()
        }
    });

});
function setGenFolio() {
    let sw = true
    let conductividad = new Array()
    let cloruros = new Array();
    let aux = ""
    if (idNorma == 27) {
        let puntos = document.getElementById("puntos")
        for (let i = 1; i < puntos.rows.length; i++) {
            if (puntos.rows[i].children[2].children[0].value == "" || puntos.rows[i].children[2].children[1].value == 0) {
                sw = false
            }
        }
    }
    if (sw == true) {
        let puntos = document.getElementById("puntos")
        for (let i = 1; i < puntos.rows.length; i++) {
            switch (puntos.rows[i].children[2].children[1].value) {
                case "1":
                    aux = "500"
                    break
                case "2":
                    aux = "1000"
                    break
                case "3":
                    aux = "1500"
                    break
                case "4":
                    aux = "2000"
                    break
                case "5":
                    aux = ">3000"
                    break
                default: 
                    aux = ""
                    break
            }
            conductividad.push(puntos.rows[i].children[2].children[0].value)
            cloruros.push(aux)
        }
        $.ajax({
            type: "POST",
            url: base_url + '/admin/ingresar/setGenFolio',
            data: {
                id: $("#idSol").val(),
                conductividad:conductividad,
                cloruros:cloruros,
            },
            dataType: "json",
            async: false,
            success: function (response) {
                console.log(response);
                alert(response.msg)
                tableCodigos(dataPunto) 
            }
        });
    } else {
        alert("Para generar los codigos es necesario ingresar la conductividad y el cloruros en los puntos de muestreo")
    }

}
var idNorma = 0
function buscarFolio() {
    let std = document.getElementById("stdMuestra")
    let temp = ''
    let phProm = 0
    let aux = 0
    idNorma = 0
    $.ajax({
        type: "POST",
        url: base_url + '/admin/ingresar/buscarFolio',
        data: {
            folioSol: $("#folioSol").val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            idNorma = response.cliente.Id_norma
            if (response.sw > 0) {
                $("#idSol").val(response.cliente.Id_solicitud);
                $("#folio").val(response.cliente.Folio_servicio);
                $("#descarga").val(response.cliente.Descarga);
                $("#cliente").val(response.cliente.Nombres);
                $("#empresa").val(response.cliente.Empresa);


                tablePuntos(response.cliente.Id_solicitud)
                
                if (response.std == true) {
                    temp = '<p class="text-success">Muestra ingresada</p>'
                    $("#hora_recepcion1").val(response.proceso[0].Hora_recepcion)
                    $("#hora_entrada").val(response.proceso[0].Hora_entrada)
                    
                } else {
                    temp = '<p class="text-warning">Falta ingreso</p>'
                }
                std.innerHTML = temp

            } else {
                $("#idSol").val("")
                $("#folio").val("")
                $("#descarga").val("")
                $("#cliente").val("")
                $("#empresa").val("")
                $("#hora_recepcion1").val("")
                $("#hora_entrada").val("")
            }
        }
    });
}
function tableCodigos(model) {
    let tabla = document.getElementById('divCodigos');
    let tab = '';
    tab += '<table id="codigos" class="table table-sm">';
    tab += '    <thead class="thead-dark">';
    tab += '        <tr>';
    tab += '          <th>Codigo</th>';
    tab += '          <th>Parametro</th>';
    tab += '    </thead>';
    tab += '    <tbody>';
    $.each(model, function (key, item) {
        $.ajax({
            type: "POST",
            url: base_url + '/admin/ingresar/getCodigoRecepcion',
            data: {
                idSol: item.Id_solicitud,
            },
            dataType: "json",
            async: false,
            success: function (response) {
                $.each(response.model, function (key, item2) {
                    tab += '<tr>';
                    tab += '<td>' + item2.Codigo + '</td>';
                    tab += '<td>' + item2.Parametro + '</td>';
                    tab += '</tr>';
                });
            }
        });
    });
    tab += '    </tbody>';
    tab += '</table>';
    tabla.innerHTML = tab;

    $('#codigos').DataTable({
        "ordering": false,
        "pageLength": 500,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        },
        "scrollY": 400,
        "scrollCollapse": true
    });

}
var idSol = 0;
var muestreo;
var dataPunto = new Array()
function tablePuntos(id) {
    let aux = 0
    $.ajax({
        type: "POST",
        url: base_url + '/admin/ingresar/getPuntoMuestreo',
        data: {
            id: id,
        },
        dataType: "json", 
        async: false,
        success: function (response) {
            dataPunto = response.model
            console.log(response);
            let tabla = document.getElementById('divPuntos');
            let tab = '';
            tab += '<table id="puntos" class="table table-sm">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>'
            tab += '            <th style="width: 10%">#</th>'
            tab += '            <th style="width: 70%">...</th>'
            tab += '            <th style="width: 20%">Opc</th>'
            tab += '        </tr>'
            tab += '    </thead>';
            tab += '    <tbody>';
            $.each(response.model, function (key, item) {
                tab += '<tr>';
                tab += '<td>' + item.Id_solicitud + '</td>';
                tab += '<td>' + item.Punto + '</td>';
                tab += '<td><input placeholder="Conduct" value="' + response.conductividad[aux] + '">'
                tab += '<select id="sel' + item.Id_solicitud + '">'
                if (response.cloruro[aux] == 0) { tab += '    <option selected value="0" >Sin seleccionar</option>' } else { tab += '    <option value="0" >Sin seleccionar</option>' }
                if (response.cloruro[aux] == 1) { tab += '    <option selected value="1" >500</option>' } else { tab += '    <option value="1" >500</option>' }
                if (response.cloruro[aux] == 2) { tab += '    <option selected value="2" >1000</option>' } else { tab += '    <option value="2" >1000</option>' }
                if (response.cloruro[aux] == 3) { tab += '    <option selected value="3" >1500</option>' } else { tab += '    <option value="3" >1500</option>' }
                if (response.cloruro[aux] == 4) { tab += '    <option selected value="4" >2000</option>' } else { tab += '    <option value="4" >2000</option>' }
                if (response.cloruro[aux] == 5) { tab += '    <option selected value="5" > > 3000</option>' } else { tab += '    <option value="5" > > 3000</option>' }
                tab += '</select></td>'
                tab += '</tr>';
                aux++
            });
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;

            tableCodigos(dataPunto)

            $('#puntos').DataTable({
                "ordering": false,
                "pageLength": 500,
                "language": {
                    "lengthMenu": "# _MENU_ por pagina",
                    "zeroRecords": "No hay datos encontrados",
                    "info": "Pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay datos encontrados",
                },
                "scrollY": 400,
                "scrollCollapse": true
            });
            $('#puntos tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                    idSol = 0;

                    $("#finMuestreo").val('');
                    $("#conformacion").val('');
                    $("#procedencia").val('');
                }
                else {
                    table.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                    let dato = $(this).find('td:first').html();
                    idSol = dato;
                    $.ajax({
                        type: "POST",
                        url: base_url + "/admin/ingresar/getDataPuntoMuestreo",
                        data: {
                            idSol: idSol,
                            _token: $('input[name="_token"]').val()
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response)
                            dataPunto = response
                            $("#finMuestreo").val(response.model.Fecha);
                            $("#conformacion").val(response.fecha2);
                            $("#procedencia").val(response.procedencia.Estado);
                        }
                    });
                }
            });
        }
    });


}
function setIngresar() {
    console.log("Click en btnIngresar");
    if ($("#hora_recepcion1").val() != "") {
        $.ajax({
            type: "POST",
            url: base_url + '/admin/ingresar/setIngresar',
            data: {
                idSol: $("#idSol").val(),
                folio: $("#folio").val(),
                descarga: $("#descarga").val(),
                cliente: $("#cliente").val(),
                empresa: $("#empresa").val(),
                ingreso: "Establecido",
                horaRecepcion: $("#hora_recepcion1").val(),
                horaEntrada: $("#hora_entrada").val(),
            },
            dataType: "json",
            async: false,
            success: function (response) {
                console.log(response);
                alert(response.msg)
            }
        });
    } else {
        alert("Hace falta agregar hora de recepcion")
    }
}

