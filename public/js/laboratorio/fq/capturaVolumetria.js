var area = "fq";
var numMuestras = new Array();
var idMuestra = 0; 
var idLote = 0;
var tecnica = 0;

$(document).ready(function () {
 
});
// $('#btnAplicarObsCloro').click(function (){
//     updateObsVolumetriaCloro();
// });
// $('#btnAplicarObs').click(function (){
//     updateObsVolumetria();
// });

$('#btnEjecutar').click(function (){
    operacion();
})
$('#btnEjecutarCloro').click(function (){
    operacionCloro();
})

$('#btnGuardarCloro').click(function (){
    // operacionCloro();
    guardarCloro();
});
$('#guardar').click(function () {
    operacion();
});
$('#btnLiberar').click(function () {
    // operacion();
    liberarMuestraMetal();
});


function getDataCaptura() {
    cleanTable();
    numMuestras = new Array();
    let tabla = document.getElementById('divLote');
    let tab = '';


        $.ajax({ 
            type: "POST",
            url: base_url + "/admin/laboratorio/"+area+"/getLotevol",
            data: {
                formulaTipo: $("#formulaTipo").val(), 
                fechaAnalisis: $("#fechaAnalisis").val(),
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
                $.each(response.lote, function (key, item) {
                    tab += '<tr>';
                    tab += '<td>'+item.Id_lote+'</td>';
                    tab += '<td>'+item.Tipo_formula+'</td>';
                    tab += '<td>'+item.Fecha+'</td>';
                    tab += '<td>'+item.Asignado+'</td>';
                    tab += '<td>'+item.Liberado+'</td>';
                    tab += '<td><button class="btn btn-success" id="btnImprimir" onclick="imprimir();"><i class="fas fa-file-download"></i></button></td>';
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


                $('#tablaLote tbody').on( 'click', 'tr', function () {
                    if ( $(this).hasClass('selected') ) {
                        $(this).removeClass('selected');
                    }
                    else {
                        t.$('tr.selected').removeClass('selected');
                        $(this).addClass('selected');
                    }
                } );
                $('#tablaLote tr').on('click', function(){
                    let dato = $(this).find('td:first').html();
                    idLote = dato;
                    getLoteCapturaVol();
                  });
            }
        });
}

function getLoteCapturaVol() {
    numMuestras = new Array();
    let tabla = document.getElementById('divTablaControles');
    let tab = '';
    let cont = 1;

    let status = "";

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getLoteCapturaVol",
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
            // tab += '          <th># toma</th>';
            tab += '          <th>Norma</th>';
            tab += '          <th>Resultado</th>';
            tab += '          <th>Observación</th>';
            tab += '        </tr>';
            tab += '    </thead>'; 
            tab += '    <tbody>';
            $.each(response.detalle, function (key, item) {
                tab += '<tr>';
                if (item.Liberado != 0) {
                    status = "";
                } else { 
                    status = "disabled";
                }
                switch ($("#formulaTipo").val()) {
                    case '295': // CLORO RESIDUAL LIBRE
                        // tab += '<td><input hidden id="idMuestra'+item.Id_detalle+'" value="'+item.Id_detalle+'"><button type="button" class="btn btn-success" onclick="getDetalleVol('+item.Id_detalle+');" data-toggle="modal" data-target="#modalCaptura">Capturar</button>';    
                        tab += '<td><input hidden id="idMuestra'+item.Id_detalle+'" value="'+item.Id_detalle+'"><button type="button" class="btn btn-success" onclick="getDetalleVol('+item.Id_detalle+',1);" data-toggle="modal" data-target="#modalCloro">Capturar</button>';
                        break;
                    case '7':
                        tab += '<td><input hidden id="idMuestra'+item.Id_detalle+'" value="'+item.Id_detalle+'"><button type="button" class="btn btn-success" onclick="getDetalleVol('+item.Id_detalle+',1);" data-toggle="modal" data-target="#modalDqo">Capturar</button>';
                        break;
                    default:
                        tab += '<td><input hidden id="idMuestra'+item.Id_detalle+'" value="'+item.Id_detalle+'"><button type="button" class="btn btn-success" onclick="getDetalleVol('+item.Id_detalle+');" data-toggle="modal" data-target="#modalCloro">Capturar</button>';
                        break;
                }
                    
                if (item.Id_control != 1) 
                {
                    tab += '<br> <small class="text-danger">'+item.Control+'</small></td>';
                }else{
                    tab += '<br> <small class="text-info">'+item.Control+'</small></td>';
                }
                tab += '<td><input disabled style="width: 100px" value="'+item.Folio_servicio+'"></td>';
                // tab += '<td><input disabled style="width: 80px" value="-"></td>';
                tab += '<td><input disabled style="width: 200px" value="'+item.Clave_norma+'"></td>';
                if(item.Resultado != null){
                    tab += '<td><input disabled style="width: 100px" value="'+item.Resultado+'"></td>';
                }else{
                    tab += '<td><input disabled style="width: 80px" value=""></td>';
                }
                if(item.Observacion != null){
                    tab += '<td>'+item.Observacion+'</td>';
                }else{
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

//Función imprimir PDF
function imprimir() {       
    window.open(base_url + "/admin/laboratorio/"+area+"/captura/exportPdfCapturaVolumetria/"+idLote);
    //window.location = base_url + "/admin/laboratorio/"+area+"/captura/exportPdfCapturaVolumetria/"+idLote;
}
function operacionCloro()
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/operacionVolumetriaCloro", 
        data: {
            idParametro:$("#formulaTipo").val(),
            A:$("#cloroA1").val(),
            E:$("#cloroE1").val(),
            H:$("#cloroH1").val(),
            G:$("#cloroG1").val(),
            B:$("#cloroB1").val(),
            C:$("#cloroC1").val(),
            D:$("#cloroD1").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            $("#resultadoCloro").val(response.res);
            
         
        }
    });

}

function guardarCloro()
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/guardarCloro", 
        data: {
            idDetalle:idMuestra,
            A:$("#cloroA1").val(),
            E:$("#cloroE1").val(),
            H:$("#cloroH1").val(),
            G:$("#cloroG1").val(),
            B:$("#cloroB1").val(),
            C:$("#cloroC1").val(),
            D:$("#cloroD1").val(),
            Resultado:$("#resultadoCloro").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            getLoteCapturaVol();
        }
    });

}


function operacion() {

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/operacionVolumetria", 
        data: {
            idParametro:$("#formulaTipo").val(),
            B:$("#b1").val(),
            C:$("#c1").val(),
            CA:$("#ca1").val(),
            D:$("#d1").val(),
            E:$("#e1").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            $("#resultado").val(response.res.toFixed(2));
            
         
        }
    });
}

function updateObsVolumetria(caso,obs)
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/updateObsVolumetria",
        data: {
            caso:caso,
            idDetalle: idMuestra,
            observacion: $("#"+obs).val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            getLoteCapturaVol();
        }
    }); 

}

function getDetalleVol(idDetalle,caso)
{
    /*
        Caso 1 = Cloro
        caso 2 = DQO
        Caso 3 = Nitrogeno
    */
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getDetalleVol",
        data: {
            caso:caso,
            formulaTipo: $("#formulaTipo").val(),
            idDetalle: idDetalle,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            switch (caso) {
                case 1: // Cloro
                $("#cloroA1").val(response.model.Vol_muestra);
                $("#cloroE1").val(response.model.Ml_muestra);
                $("#calroH1").val(response.model.Ph_final);
                $("#cloroG1").val(response.model.Ph_inicial);
                $("#cloroB1").val(response.model.Vol_blanco);
                $("#cloroC1").val(response.model.Normalidad);
                $("#cloroD1").val(response.model.Factor_conversion);
                $("#resultadoCloro").val(response.model.Resultado);
                $("#observacionCloro").val(response.model.Observacion);
                    break;
            
                default:
                    break;
            }
        
        }
    });
}
function random(min, max) {
    return Math.floor(Math.random() * (max - min + 1) + min);
}
function createControlCalidad()
{ 
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/createControlCalidad",
        data: {
            idParametro:$("#formulaTipo").val(),
            idMuestra: idMuestra,
            idControl: $("#controlCalidad").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            getLoteCapturaVol();
        }
    });
}
function cleanTable() {

    let tabla = document.getElementById('divTablaControles');
    let tab = '';

            tab += '<table id="tablaControles" class="table table-sm">';
            tab += '    <thead>';
            tab += '        <tr>';
            tab += '          <th>Opc</th>';
            tab += '          <th>Folio</th>';
            // tab += '          <th># toma</th>';
            tab += '          <th>Norma</th>';
            tab += '          <th>Resultado</th>';
            tab += '          <th>Observación</th>';
            tab += '        </tr>';
            tab += '    </thead>'; 
            tab += '    <tbody>';
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;
}