var area = "potable"
$(document).ready(function () {

    $('#formulaTipo').select2();
    $('#btnLiberar').click(function () {
        // operacion();
        liberarMuestra();
    });
    

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
                tab += '<td>'+item.Parametro +' (' + item.Tipo_formula + ')</td>';
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
                getLoteCapturaPotable();
            });
        }
    });
}

function getLoteCapturaPotable() {
    numMuestras = new Array();
    let tabla = document.getElementById('divTablaControles');
    let tab = '';
    let cont = 1;

    let status = ""; 

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getLoteCapturaPotable",
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
                    case "77":
                    case "251":
                    case "252":
                    case "103":
                        tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button type="button" '+status+' class="'+clase+'" onclick="getDetallePotable(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalDureza">Capturar</button>';
                        console.log("Entro a directos");
                        break;
                    default:
                        tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button type="button" '+status+' class="'+clase+'" onclick="getDetallePotable(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalPotable">Capturar</button>';
                        console.log("Entro a Potable");
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
            });


        }
    });
}
function getDetallePotable(idMuestra)
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getDetallePotable",
        data: {
            idDetalle: idMuestra,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            let model = response.model
            // Directos
            $("#lecturaUno1").val(model.Lectura1)
            $("#lecturaDos1").val(model.Lectura2)
            $("#lecturaTres1").val(model.Lectura3)
            $("#temperatura1").val(model.Temperatura)
            $("#resultado").val(model.Resultado)

            //Temperatura
            $("#lecturaUno1T").val(model.Lectura1)
            $("#lecturaDos1T").val(model.Lectura2)
            $("#lecturaTres1T").val(model.Lectura3)
            $("#resultadoT").val(model.Resultado)
            //Color
            $("#aparente1").val(model.Color_a)
            $("#verdadero1").val(model.Color_v)
            $("#dilusion1").val(model.Factor_dilucion)
            $("#volumen1").val(model.Vol_muestra)
            $("#ph1").val(model.Ph)
            $("#factor1").val(model.Factor_correcion)
            $("#resultadoColor").val(model.Resultado)
        }

        }); 
}

function operacion(sw){
    switch (sw) {
        case 1:
            $.ajax({
                type: "POST",
                url: base_url + "/admin/laboratorio/" + area + "/operacion",
                data: {        
                    sw:sw,    
                    idDetalle: idMuestra,
                    id: $("#formulaTipo").val(),
                    fecha: $("#fechaAnalisis").val(),
                    edta: $("#edta1").val(),
                    ph: $("#ph1").val(),
                    vol: $("#vol1").val(),
                    real: $("#real1").val(),
                    conversion: $("#conversion1").val(),
                    _token: $('input[name="_token"]').val()
                },
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    $("#resultado").val(response.resultado)
                }
                });
            break;
        case 2:
            $.ajax({
                type: "POST",
                url: base_url + "/admin/laboratorio/" + area + "/operacion",
                data: {        
                    sw:sw,    
                    idDetalle: idMuestra,
                    id: $("#formulaTipo").val(),
                    fecha: $("#fechaAnalisis").val(),
                    dilucion: $("#dilucion1").val(),
                    lectura1: $("#lectura11").val(),
                    lectura2: $("#lectura21").val(),
                    lectura3: $("#lectura31").val(),
                    vol: $("#volM1").val(),
                    promedio: $("#prom1").val(),
                    _token: $('input[name="_token"]').val()
                },
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    $("#resultadoPotable").val(response.resultado)
                }
                });
            break;
        default:
            break;
    }

    getLoteCapturaPotable();

}

function enviarObsGeneral(){
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/enviarObsGeneral",
        data: {
            idParametro: $("#formulaTipo").val(),
            idDetalle: idMuestra,
            idLote: idLote,
            observacion: $("#observacion").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            if(response != null){
                alert("Observacion acttualizada")
                getLoteCapturaDirecto()
            }
        }
    });
}
function updateObsMuestra(){
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/updateObsMuestra",
        data: {
            idParametro: $("#formulaTipo").val(),
            idDetalle: idMuestra,
            idLote: idLote,
            observacion: $("#obsMuestra").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            if(response != null){
                alert("Observacion acttualizada")
                getLoteCapturaDirecto()
            }
        }
    });
}
function liberarMuestra() {
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/liberarMuestra",
        data: {
            idParametro: $("#formulaTipo").val(),
            idMuestra: idMuestra,
            idLote: idLote,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            if (response.sw == true) {
                getLote()
                getLoteCapturaPotable()

            } else {
                alert("La muestra no se pudo liberar");
            }
        }
    });
}
//Función imprimir PDF
function imprimir(idLote){
    console.log("Dentro de evento btnBuscar");
    $('#btnImprimir').click(function() {
        window.location = base_url + "/admin/laboratorio/"+area+"/captura/exportPdfPotable/"+idLote;
    });}