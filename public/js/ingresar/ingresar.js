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


});
function buscarFolio() {
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
            //data = response;                         
            $("#idSol").val(response.cliente.Id_solicitud);
            $("#folio").val(response.cliente.Folio_servicio);
            $("#descarga").val(response.cliente.Descarga);
            $("#cliente").val(response.cliente.Nombres);
            $("#empresa").val(response.cliente.Empresa);
            tableCodigos(response.model);
            tablePuntos(response.puntos, response.siralab)
        }
    });
}
function tableCodigos(model) {
    let tabla = document.getElementById('divCodigos');
    let tab = '';
    tab += '<table id="codigos" class="table table-sm">';
    tab += '    <thead class="thead-dark">';
    tab += '        <tr>';
    tab += '          <th>Tipo</th>';
    tab += '          <th>Número Muestra</th>';
    tab += '          <th>Cant. Total</th>';
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

                tab += '<tr>';
                tab += '<td></td>';
                tab += '<td>' + item.Folio_servicio + '</td>';
                tab += '<td>' + response.model.length + '</td>';
                tab += '</tr>';
                $.each(response.model, function (key, item2) {
                    tab += '<tr>';
                    switch (item2.Id_parametro) {
                        case "13": //GA
                            tab += '<td>G</td>';
                            break;
                        case "12"://COLIFORMES
                            tab += '<td>C</td>';
                            break;
                        case "5"://DBO
                            tab += '<td>D</td>';
                            break;
                        default:
                            tab += '<td></td>';
                            break;
                    }
                    tab += '<td>' + item2.Codigo + '</td>';
                    tab += '<td>' + item.Folio_servicio + '</td>';
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
function tablePuntos(model, siralab) {
    let tabla = document.getElementById('divPuntos');
    let tab = '';
    tab += '<table id="puntos" class="table table-sm">';
    tab += '    <thead class="thead-dark">';
    tab += '        <tr>';
    tab += '          <th>Id</th>';
    tab += '          <th>...</th>';
    tab += '    </thead>';
    tab += '    <tbody>';
    $.each(model, function (key, item) {
        tab += '<tr>'; 
        tab += '<td>' + item.Id_solicitud + '</td>';
        if (siralab == true) { 
            tab += '<td>' + item.Punto + '</td>';
        } else {
            tab += '<td>' + item.Punto_muestreo + '</td>';
        }
        tab += '</tr>';
    });
    tab += '    </tbody>';
    tab += '</table>';
    tabla.innerHTML = tab;

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
    $('#puntos tbody').on( 'click', 'tr', function () { 
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
            idSol = 0;
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
                    let con = new Date(response.model.Fecha);
                    $("#finMuestreo").val(con);
                    con.setMinutes(con.getMinutes() + 30);
                    $("#conformacion").val(con);
                    $("#procedencia").val(response.muestreo.NomEstado);
                }
            });
        }
    } );

}
function setIngresar() {
    console.log("Click en btnIngresar");
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
           
        }
    });
}

