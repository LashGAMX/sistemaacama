var area = "directos"
$(document).ready(function () {


});


function getLote() {
  
    numMuestras = new Array();
    let tabla = document.getElementById('divLote');
    let tab = '';


    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getLote",
        data: {
            id: $("#formulaTipo").val(),
            fecha: $("#fechaAnalisis").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            tab += '<table id="tablaLote" class="table table-sm">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>Lote</th>';
            tab += '          <th>Tipo formula</th>';
            tab += '          <th>Fecha lote</th> ';
            tab += '          <th>Total asignado</th> ';
            tab += '          <th>Total liberados</th> ';
            tab += '          <th>Opc</th> ';
            tab += '        </tr>';
            tab += '    </thead>';  
            tab += '    <tbody>';
            $.each(response.model, function (key, item) {
                tab += '<tr>';
                tab += '<td>' + item.Id_lote + '</td>';
                tab += '<td>' + item.Tipo_formula + '</td>';
                tab += '<td>' + item.Fecha + '</td>';
                tab += '<td>' + item.Asignado + '</td>';
                tab += '<td>' + item.Liberado + '</td>';
                tab += '<td><button class="btn btn-success" id="btnImprimir" onclick="imprimir(' + item.Id_lote + ');"><i class="fas fa-file-download"></i></button></td>';
                tab += '</tr>';
            });
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;

            tecnica = response.tecnica;


            var t = $('#tablaLote').DataTable({
                "ordering": false,
                "language": {
                    "lengthMenu": "# _MENU_ por pagina",
                    "zeroRecords": "No hay datos encontrados",
                    "info": "Pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay datos encontrados",
                }
            });


            $('#tablaLote tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                }
                else {
                    t.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            });
            $('#tablaLote tr').on('click', function () {
                let dato = $(this).find('td:first').html();
                idLote = dato;
                getLoteCapturaDirecto();
            });
        }
    });
}

function getLoteCapturaDirecto() {
    numMuestras = new Array();
    let tabla = document.getElementById('divTablaControles');
    let tab = '';
    let cont = 1;

    let status = "";

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getLoteCapturaDirecto",
        data: {
            idLote: idLote,
            formulaTipo: $("#formulaTipo").val(),
            tecnica: tecnica,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);

            tab += '<table id="tablaControles" class="table table-sm">';
            tab += '    <thead>';
            tab += '        <tr>';
            tab += '          <th>Opc</th>';
            tab += '          <th>Folio</th>';
            tab += '          <th>#Tipo</th>'; //agregado radio button
            tab += '          <th>Norma</th>';
            tab += '          <th>Resultado</th>';
            tab += '          <th>Observación</th>';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            $.each(response.detalle, function (key, item) {
                tab += '<tr>';
                if (item.Liberado == null) {
                    status = "";
                    clase = "btn btn-success";
                } else {
                    status = "disabled";
                    clase = "btn btn-warning";
                }
                switch ($("#formulaTipo").val()) {
                    case "12":
                    
                        tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button type="button" '+status+' class="'+clase+'" onclick="getDetalleDirecto(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modal">Capturar</button>';
                        console.log("Entro a directos");
                        break;
                   
                    default:
                        console.log("Entro a al limbo");
                        break;
                }
                if (item.Id_control != 1) {
                    tab += '<br> <small class="text-danger">' + item.Control + '</small></td>';
                } else {
                    tab += '<br> <small class="text-info">' + item.Control + '</small></td>';
                }
                // Radio para el tipo de la muestra
                tab += '<td><radio type="radio" name="radio" id="radioTipo"></radio></td>';
                tab += '<td><input disabled style="width: 200px" value="' + item.Codigo + '"></td>';
                // tab += '<td><input disabled style="width: 80px" value="-"></td>';
                tab += '<td><input disabled style="width: 200px" value="' + item.Clave_norma + '"></td>';
                if (item.Resultado != null) {
                    tab += '<td><input disabled style="width: 100px" value="' + item.Resultado + '"></td>';
                } else {
                    tab += '<td><input disabled style="width: 80px" value=""></td>';
                }
                if (item.Observacion != null) {
                    tab += '<td>' + item.Observacion + '</td>';
                } else {
                    tab += '<td></td>';
                }
                tab += '</tr>';
                numMuestras.push(item.Id_detalle);
                cont++;
            });
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;

            var t2 = $('#tablaControles').DataTable({
                "ordering": false,
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
                    t2.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            });
            $('#tablaControles tr').on('click', function () {
                let dato = $(this).find('td:first');
                idMuestra = dato[0].firstElementChild.value;
                // console.log(idMuestra);
            });


        }
    });
}