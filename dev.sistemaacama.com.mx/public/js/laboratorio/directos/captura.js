var area = "directos"
$(document).ready(function () {

    $('#btnLiberar').click(function () {
        // operacion();
        liberarMuestra();
    });
    $('#formulaTipo').select2();
    

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
                    case "14":
                    case "67":
                    case "110":
                        tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button type="button" '+status+' class="'+clase+'" onclick="getDetalleDirecto(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modal">Capturar</button>';
                        console.log("Entro a directos");
                        break;
                    case "218":
                        tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button type="button" '+status+' class="'+clase+'" onclick="getDetalleDirecto(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalCloro">Capturar</button>';
                        console.log("Entro a directos");
                        break;
                    case "97":
                    case "33":
                        tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button type="button" '+status+' class="'+clase+'" onclick="getDetalleDirecto(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalTemperatura">Capturar</button>';
                        console.log("Entro a temperatura");
                        break;
                    case "102":
                    case "66":
                    case "65":
                        tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button type="button" '+status+' class="'+clase+'" onclick="getDetalleDirecto(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalColor">Capturar</button>';
                        console.log("Entro a color");
                        break;
                        case "98":
                            tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button type="button" '+status+' class="'+clase+'" onclick="getDetalleDirecto(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modalTurbiedad">Capturar</button>';
                            console.log("Entro a turbiedad");
                            break;
                    default:
                        tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button type="button" '+status+' class="'+clase+'" onclick="getDetalleDirecto(' + item.Id_detalle + ');" data-toggle="modal" data-target="#modal">Capturar</button>';
                        console.log("Entro a directos");
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
function getDetalleDirecto(idMuestra)
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getDetalleDirecto",
        data: {
            idDetalle: idMuestra,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            let model = response.model
            // Directos
            $("#obsMuestraDirecto").val(model.Observacion)
            
            $("#lecturaUno1").val(model.Lectura1)
            $("#lecturaDos1").val(model.Lectura2)
            $("#lecturaTres1").val(model.Lectura3)
            $("#temperatura1").val(model.Temperatura)
            $("#resultado").val(model.Resultado)

            //Temperatura
            $("#obsMuestraTemperatura").val(model.Observacion)
            $("#lecturaUno1T").val(model.Lectura1)
            $("#lecturaDos1T").val(model.Lectura2)
            $("#lecturaTres1T").val(model.Lectura3)
            $("#resultadoT").val(model.Resultado)
            //Color
            $("#obsMuestraTemperatura").val(model.Observacion)
            $("#aparente1").val(model.Color_a)
            $("#verdadero1").val(model.Color_v)
            $("#dilusion1").val(model.Factor_dilucion)
            $("#volumen1").val(model.Vol_muestra)
            $("#ph1").val(model.Ph)
            $("#factor1").val(model.Factor_correcion)
            $("#resultadoColor").val(model.Resultado)
            //Turbiedad 
            $("#obsMuestraTurbiedad").val(model.Observacion)
            $("#dilusionTurb1").val(model.Factor_dilucion);
            $("#valumenTurb1").val(model.Vol_muestra);
            $("#lecturaUnoTurb1").val(model.Lectura1);
            $("#lecturaDosTurb1").val(model.Lectura2);
            $("#lecturaTresTurb1").val(model.Lectura3);
            $("#promedioTurb1").val(model.Promedio);
            $("#resultadoTurbiedad").val(model.Resultado);
            //Cloro
            $("#dilucionCloro1").val(model.Factor_dilucion)
            $("#obsMuestraCloro").val(model.Observacion)
            $("#volumenCloro1").val(model.Vol_muestra);
            $("#lecturaUnoCloro1").val(model.Lectura1);
            $("#lecturaDosCloro1").val(model.Lectura2);
            $("#lecturaTresCloro1").val(model.Lectura3);
            $("#promedioCloro1").val(model.Promedio);
            $("#resultadoCloro").val(model.Resultado);
        }

        }); 
}
function limpiar()
{

    $("#obsMuestraDirecto").val()
    $("#obsMuestraTemperatura").val()
    $("#obsMuestraTemperatura").val()
    $("#obsMuestraTurbiedad").val()
    $("#obsMuestraCloro").val()
                // Directos
                $("#lecturaUno1").val()
                $("#lecturaDos1").val()
                $("#lecturaTres1").val()
                $("#temperatura1").val()
                $("#resultado").val()
    
                //Temperatura
                $("#lecturaUno1T").val()
                $("#lecturaDos1T").val()
                $("#lecturaTres1T").val()
                $("#resultadoT").val()
                //Color
                $("#aparente1").val()
                $("#verdadero1").val()
                $("#dilusion1").val()
                $("#volumen1").val()
                $("#ph1").val()
                $("#factor1").val()
                $("#resultadoColor").val()
                //Turbiedad 
                $("#dilusionTurb1").val();
                $("#valumenTurb1").val();
                $("#lecturaUnoTurb1").val();
                $("#lecturaDosTurb1").val();
                $("#lecturaTresTurb1").val();
                $("#promedioTurb1").val();
                $("#resultadoTurbiedad").val();
                //Cloro
                $("#dilucionCloro1").val()
                $("#volumenCloro1").val();
                $("#lecturaUnoCloro1").val();
                $("#lecturaDosCloro1").val();
                $("#lecturaTresCloro1").val();
                $("#promedioCloro1").val();
                $("#resultadoCloro").val();
}
function createControlCalidad()
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/createControlCalidadDirectos",
        data: {
            idMuestra: idMuestra,
            idLote:idLote,
            idControl: $("#controlCalidad").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response); 
            getLoteCapturaDirecto();
        }
    });
}

function operacion(){
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/operacion",
        data: {
            idDetalle: idMuestra,
            id: $("#formulaTipo").val(),
            fecha: $("#fechaAnalisis").val(),
            l1: $("#lecturaUno1").val(),
            l2: $("#lecturaDos1").val(),
            l3: $("#lecturaTres1").val(),
            temp: $("#temperatura1").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            $("#resultado").val(response.resultado)
            getLoteCapturaDirecto()
        }

        });
}

function operacionTurbiedad(){
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/operacionTurbiedad",
        data: {
            idDetalle: idMuestra,
            id: $("#formulaTipo").val(),
            fecha: $("#fechaAnalisis").val(),
            factor: $("#dilusionTurb1").val(),
            volumen: $("#valumenTurb1").val(),
            l1: $("#lecturaUnoTurb1").val(),
            l2: $("#lecturaDosTurb1").val(),
            l3: $("#lecturaTresTurb1").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            $("#promedioTurb1").val(response.promedio)
            $("#resultadoTurbiedad").val(response.res)
            getLoteCapturaDirecto()
        }

        });
}
function operacionCloro(){
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/operacionCloro",
        data: {
            idDetalle: idMuestra,
            id: $("#formulaTipo").val(),
            fecha: $("#fechaAnalisis").val(),
            dilucion: $("#dilucionCloro1").val(),
            volumen: $("#volumenCloro1").val(),
            l1: $("#lecturaUnoCloro1").val(),
            l2: $("#lecturaDosCloro1").val(),
            l3: $("#lecturaTresCloro1").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            $("#promedioCloro1").val(response.promedio)
            $("#resultadoCloro").val(response.res)
            getLoteCapturaDirecto()
        }

        });
}
function operacionTemperatura(){
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/operacionTemperatura",
        data: {
            idDetalle: idMuestra,
            id: $("#formulaTipo").val(),
            fecha: $("#fechaAnalisis").val(),
            l1: $("#lecturaUno1T").val(),
            l2: $("#lecturaDos1T").val(),
            l3: $("#lecturaTres1T").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            $("#resultadoT").val(response.res)
            getLoteCapturaDirecto()
        }

        });
}
function operacionColor(){
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/operacionColor",
        data: {
            idDetalle: idMuestra,
            id: $("#formulaTipo").val(),
            fecha: $("#fechaAnalisis").val(),
            aparente: $("#aparente1").val(),
            verdadero: $("#verdadero1").val(),
            dilusion: $("#dilusion1").val(),
            volumen: $("#volumen1").val(),
            factor: $("#factor1").val(),
            ph: $("#ph1").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            $("#resultadoColor").val(response.resultado)
            $("#dilusion1").val(response.dilusion)
            $("#factor1").val(response.factor)
            getLoteCapturaDirecto()
        }

        });
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
function updateObsMuestra(id){
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/updateObsMuestra",
        data: {
            idParametro: $("#formulaTipo").val(),
            idDetalle: idMuestra,
            idLote: idLote,
            observacion: $("#"+id).val(),
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
            idMuestra: idMuestra,
            idLote: idLote,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            if (response.sw == true) {
                getLote()
                getLoteCapturaDirecto()

            } else {
                alert("La muestra no se pudo liberar");
            }
        }
    });
}
//Función imprimir PDF
function imprimir(idLote){
    console.log("Dentro de evento btnBuscar");
    window.location = base_url + "/admin/laboratorio/"+area+"/captura/exportPdfDirecto/"+idLote;
}