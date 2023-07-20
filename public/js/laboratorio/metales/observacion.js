

$(document).ready(function () {
    table = $('#solicitudGenerada').DataTable({
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        }
    });
    getServicio(0,1)

    $('#btnBuscar').click(function () {
        getServicio($("#tipoFormula").val(),2);
    });
});

document.addEventListener("keydown", function(event) {
    if (event.altKey && event.code === "KeyA")
    {
        aplicar()
    }
    if (event.altKey && event.code === "KeyB"){
        alert("Buscar")
    }
});

var idServicio = "";
function getServicio(id,tipo) {
    let tabla = document.getElementById('divClientes');
    let tab = '';
    $.ajax({
        url: base_url + '/admin/laboratorio/metales/getObservacionanalisis', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            id: id,
            tipo:tipo,
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response);
            tab += '<table id="tablaClientes" class="table table-sm">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>Id</th>';
            tab += '          <th>Folio</th>';
            tab += '          <th>Cliente</th>';
            tab += '          <th>Recepción</th>';
            tab += '          <th>Creación</th>';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            for (let i = 0; i < response.ids.length; i++) {
                tab += '<tr>';
                tab += '<td>' + response.ids[i] + '</td>';
                tab += '<td>' + response.folios[i] + '</td>';
                tab += '<td>' + response.empresas[i] + '</td>';
                tab += '<td>' + response.recepciones[i] + '</td>';
                tab += '<td>' + response.recepciones2[i] + '</td>';
                tab += '</tr>';   
            }
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;

            var table =  $('#tablaClientes').DataTable({
                "ordering": false,
                "pageLength": 100,
                "language": {
                    "lengthMenu": "# _MENU_ por pagina",
                    "zeroRecords": "No hay datos encontrados",
                    "info": "Pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay datos encontrados",
                },
                "scrollY": 300,
                "scrollCollapse": true
            });

            $('#tablaClientes tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                }
                else {
                    table.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                    getPuntoAnalisis(idServicio)
                }
            });

            $('#tablaClientes tr').on('click', function () {
                let dato = $(this).find('td:first').html();
                idServicio = dato;
            });
            
        }
    });
}
function getPuntoAnalisis(id)
{
    let tabla = document.getElementById('divPuntos');
    let tab = '';
    $.ajax({
        url: base_url + '/admin/laboratorio/metales/getPuntoAnalisis', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            idSol: id,
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response);
            tab += '<table id="tablaPuntos" class="table table-sm">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>Folio</th>';
            tab += '          <th>Punto Muestre</th>';
            tab += '          <th>Norma</th>';
            tab += '          <th>Parametros</th>';
            tab += '          <th>Observación</th>';
            tab += '          <th>pH <2</th>';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            for (let i = 0; i < response.model.length; i++) {
                tab += '<tr>';
                tab += '<td>' + response.model[i][0] + '</td>';
                tab += '<td>' + response.model[i][1] + '</td>';
                tab += '<td>' + response.model[i][2] + '</td>';
                tab += '<td></td>';
                tab += '<td>' + response.model[i][3] + '</td>';
                tab += '<td>' + response.model[i][4] + '</td>';
                tab += '</tr>';
            }
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;

            var table = $('#tablaPuntos').DataTable();

       
        }
    });
}

//Debe ir función AJAX
function aplicar() {

    $.ajax({
        type: "POST",
        url: base_url + '/admin/laboratorio/metales/aplicarObservacion',
        data: {
            idSol: idServicio,
            obs: $('#obs').val(),
            ph: $('#ph').val(),
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            alert("Observacion aplicada")
            getPuntoAnalisis(idServicio)
        }
    });

}
