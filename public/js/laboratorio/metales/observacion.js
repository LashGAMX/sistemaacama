var base_url = 'https://dev.sistemaacama.com.mx';

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

    $('#btnBuscar').click(function () {
        console.log($("#tipoFormula").val());
        getServicio($("#tipoFormula").val());
    });
});

var folioSel = "";
function getServicio(id) {
    let tabla = document.getElementById('solicitudGenerada');
    let tab = '';
    $.ajax({
        url: base_url + '/admin/laboratorio/metales/getObservacionanalisis', //archivo que recibe la peticion
        type: 'POST', //método de envio
        data: {
            id: id,
            _token: $('input[name="_token"]').val(),
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            console.log(response);
            tab += '<table id="solicitudGenerada" class="table table-sm">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>Folio servicio</th>';
            tab += '          <th>Nombre cliente</th>';
            tab += '          <th scope="col">Fecha recepción</th>';
            tab += '          <th>FechaCreación</th>       ';
            // tab += '          <th>Punto de muestreo</th>';
            tab += '          <th>Norma</th>';
            // tab += '          <th>Parámetros</th>';
            tab += '          <th>Es pH < 2</th>';
            tab += '          <th>Sólidos</th> ';
            tab += '          <th>Olor</th> ';
            tab += '          <th>Color</th> ';
            tab += '          <th>Observaciones</th>';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            $.each(response.model, function (key, item) {
                tab += '<tr>';
                tab += '<td>' + item.Folio + '</td>';
                tab += '<td>' + item.Empresa + '</td>';
                tab += '<td>' + item.Hora_entrada + '</td>';
                tab += '<td>'+item.created_at+'</td>';
                // tab += '<td></td>';
                tab += '<td>'+item.Clave_norma+'</td>';
                // tab += '<td></td>';
                tab += '<td>' + item.Ph + '</td>';
                tab += '<td>' + item.Solido + '</td>';
                tab += '<td>' + item.Olor + '</td>';
                tab += '<td>' + item.Color + '</td>';
                tab += '<td contenteditable="true">' + item.Observaciones + '</td>';
                tab += '</tr>';
            });
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;

            var table = $('#solicitudGenerada').DataTable();

            $('#solicitudGenerada tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                }
                else {
                    table.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            });

            $('#tablaControles tr').on('click', function () {
                let dato = $(this).find('td:first').html();
                folioSel = dato;
            });
        }
    });
}

//Debe ir función AJAX
function aplicar() {
    let tabla = document.getElementById('solicitudGenerada');
    let tab = '';

    $.ajax({
        type: "POST",
        url: base_url + '/admin/laboratorio/metales/aplicarObservacion',
        data: {
            idTipo: $("#tipoFormula").val(),
            folioActual: folioSel,
            ph: $('#condicionPh').val(),
            solidos: $('#solidos').val(),
            olor: $('#olor').val(),
            color: $('#color').val(),
            observacionGeneral: $('#observacionesGenerales').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            tab += '<table id="solicitudGenerada" class="table table-sm">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>Folio servicio</th>';
            tab += '          <th>Nombre cliente</th>';
            tab += '          <th scope="col">Fecha recepción</th>';
            tab += '          <th>FechaCreación</th>       ';
            // tab += '          <th>Punto de muestreo</th>';
            tab += '          <th>Norma</th>';
            // tab += '          <th>Parámetros</th>';
            tab += '          <th>Es pH < 2</th>';
            tab += '          <th>Sólidos</th> ';
            tab += '          <th>Olor</th> ';
            tab += '          <th>Color</th> ';
            tab += '          <th>Observaciones</th>';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            $.each(response.model, function (key, item) {
                tab += '<tr>';
                tab += '<td>' + item.Folio + '</td>';
                tab += '<td>' + item.Empresa + '</td>';
                tab += '<td>' + item.Hora_entrada + '</td>';
                tab += '<td>'+item.created_at+'</td>';
                // tab += '<td></td>';
                tab += '<td>'+item.Clave_norma+'</td>';
                // tab += '<td></td>';
                tab += '<td>' + item.Ph + '</td>';
                tab += '<td>' + item.Solido + '</td>';
                tab += '<td>' + item.Olor + '</td>';
                tab += '<td>' + item.Color + '</td>';
                tab += '<td>' + item.Observaciones + '</td>';
                tab += '</tr>';
            });
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;

            var table = $('#solicitudGenerada').DataTable();

            $('#solicitudGenerada tbody').on('click', 'tr', function () {
                $(this).toggleClass('selected');
            });

            $('#button').click(function () {
                alert(table.rows('.selected').data().length + ' row(s) selected');
            });
        }
    });

}
