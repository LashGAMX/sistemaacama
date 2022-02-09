var area = "fq";
var numMuestras = new Array();
var idMuestra = 0; 
var idLote = 0;
var tecnica = 0;

$(document).ready(function () {

});


$('#btnEjecutar').click(function () {
        // validacionModal(); 
        operacion();
});
$('#ejecutarModalSulfato').click(function () {
    operacionSulfatos(); 
});

$('#btnGuardar').click(function (){
    guardar();
    // alert('Guardado');
});
$('#btnLiberar').click(function () {
    // operacion();
    liberarMuestraMetal();
});


function getDataCaptura() {
    numMuestras = new Array();
    let tabla = document.getElementById('divLote');
    let tab = '';


        $.ajax({ 
            type: "POST",
            url: base_url + "/admin/laboratorio/"+area+"/getLoteEspectro",
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
                    getLoteCapturaEspectro();
                  });
            }
        });
}

function getLoteCapturaEspectro() {
    numMuestras = new Array();
    let tabla = document.getElementById('divTablaControles');
    let tab = '';
    let cont = 1;

    let status = "";

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getLoteCapturaEspectro",
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
                if($("#formulaTipo").val() != 96)
                {
                    tab += '<td><input hidden id="idMuestra'+item.Id_detalle+'" value="'+item.Id_detalle+'"><button type="button" class="btn btn-success" onclick="getDetalleEspectro('+item.Id_detalle+');" data-toggle="modal" data-target="#modalCaptura">Capturar</button>';
                }else{
                    tab += '<td><input hidden id="idMuestra'+item.Id_detalle+'" value="'+item.Id_detalle+'"><button type="button" class="btn btn-success" onclick="" data-toggle="modal" data-target="#modalCapturaSulfatos">Capturar</button>';
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
function guardarSulfatos(){
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/guardarSulfatos",
        data: {
            idMuestra: $("#idMuestra").val(),
            fechaAnalisis: $("#fechaAnalisis").val(),
            parametro: $('#formulaTipo').val(),
            ABS:$('#abs1F').val(),
            CA:$('#blanco1F').val(),
            CB:$('#b1F').val(),
            CM:$('#m1F').val(),
            CR:$('#r1F').val(),
            D:$('#fDilucion1F').val(),
            E:$('#volMuestra1F').val(),
            X:$('#abs11F').val(),
            Y:$('#abs21F').val(),
            Z:$('#abs31F').val(),
            ABS4:$('#abs41F').val(),
            ABS5:$('#abs51F').val(),
            ABS6:$('#abs61F').val(),
            ABS7:$('#abs71F').val(),
            ABS8:$('#abs81F').val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) { 
            console.log(response);
            
            
        }
    });
}
function guardar(){
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/guardarEspectro",
        data: {
            idMuestra: $("#idMuestra").val(),
            fechaAnalisis: $("#fechaAnalisis").val(),
            parametro: $('#formulaTipo').val(),
            resultado: $('#resultadoF').val(),
            observacion: $area('#obs').val(),
            ABS:$('#abs1').val(),
            CA:$('#blanco1').val(),
            CB:$('#b1').val(),
            CM:$('#m1').val(),
            CR:$('#r1').val(),
            D:$('#fDilucion1').val(),
            E:$('#volMuestra1').val(),
            X:$('#abs11').val(),
            Y:$('#abs21').val(),
            Z:$('#abs31').val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) { 
            console.log(response);
            
            
        }
    });
}
function validacionModal(){
    let sw = true;
    
 if(validarCampos("campos")){
    if($("#blanco1").val()!= $("#blanco2").val())
    {
        //alert('Los valores de BLANCO no son iguales')
        // $("#blanco2").attr("class", "bg-danger");
        // $("#blanco2").attr("class", "bg-danger");
        inputFocus("blanco2")
        inputFocus("blanco1")
        sw = false;
        
        console.log('no valido'); 
    }
    if($("#volMuestra1").val()!= $("#volMuestra2").val())
    {
        //alert('Los valores de VOLUMEN MUESTRA no son iguales')
        inputFocus("volMuestra1")
        inputFocus("volMuestra2")
        sw = false;
    }
    if($("#abs11").val()!= $("#abs12").val())
    {
        //alert('Los valores de ABS no son iguales')
        inputFocus("abs11")
        inputFocus("abs12")
        sw = false;
    }
    if($("#abs21").val()!= $("#abs22").val())
    {
        //alert('Los valores de ABS no son iguales')
        inputFocus("abs11")
        inputFocus("abs22")
        sw = false;
    }
    if($("#abs31").val()!= $("#abs32").val())
    {
        //alert('Los valores de ABS no son iguales')
        inputFocus("abs31")
        inputFocus("abs32")
        sw = false;
    }
    
    if(sw == true)
    {
        operacion();
        inputDesFocus();
    }
 }
 
}
function getDetalleEspectro(idDetalle)
{
    switch ($("#formulaTipo").val()) {
        case 70:
            $("#conPh").show();
            $("#conPh2").show();
            // $("#"+id2).hide();
            break;
        default:
            $("#conPh").show();
            $("#conPh2").hide();
            break;
    }
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getDetalleEspectro",
        data: {
            idDetalle: idDetalle,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) { 
            console.log(response);
            $("#observacion").val(response.model.Observacion);
            $("#abs1").val(response.curva.Promedio);
            $("#abs2").val(response.curva.Promedio);
            $("#blanco1").val(0);
            $("#blanco1").val(0);
            $("#idMuestra").val(idDetalle);
            $("#blanco1").val(response.model.Blanco);
            $("#blanco2").val(response.model.Blanco);
            $("#b1").val(response.curva.B);
            $("#m1").val(response.curva.M);
            $("#r1").val(response.curva.R);
            $("#b2").val(response.curva.B);
            $("#m2").val(response.curva.M);
            $("#r2").val(response.curva.R);
            $("#fDilucion1").val(response.model.Vol_dilusion);
            $("#fDilucion2").val(response.model.Vol_dilusion);
            $("#volMuestra1").val(response.model.Vol_muestra);
            $("#volMuestra2").val(response.model.Vol_muestra);
            $("#abs11").val(response.model.Abs1);
            $("#abs21").val(response.model.Abs2);
            $("#abs31").val(response.model.Abs3);
            $("#abs12").val(response.model.Abs1);
            $("#abs22").val(response.model.Abs2);
            $("#abs32").val(response.model.Abs3);
        }
    });
}

//Función imprimir PDF
function imprimir() {            
    window.open(base_url + "/admin/laboratorio/"+area+"/captura/exportPdfCapturaEspectro/" + idLote);
    //window.location = base_url + "/admin/laboratorio/"+area+"/captura/exportPdfCapturaEspectro/" + idLote;    
}

function operacion() {

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/operacionEspectro",
        data: {
            parametro: $('#formulaTipo').val(),
            ABS:$('#abs1').val(),
            CA:$('#blanco1').val(),
            CB:$('#b1').val(),
            CM:$('#m1').val(),
            CR:$('#r1').val(),
            D:$('#fDilucion1').val(),
            E:$('#volMuestra1').val(),
            X:$('#abs11').val(),
            Y:$('#abs21').val(),
            Z:$('#abs31').val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            let x = response.x.toFixed(3);
          $("#abs1").val(x); 
          $("#abs2").val(x); 
          let resultado = response.resultado.toFixed(3);
          $("#resultado").val(resultado); 
          let d = response.d.toFixed(3);
          $("#fDilucion1").val(d);
          $("#fDilucion2").val(d);
        }
    });
}
function operacionSulfatos() {

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/operacionSulfatos",
        data: {
            parametro: $('#formulaTipo').val(),
            ABS:$('#abs1F').val(),
            CA:$('#blanco1F').val(),
            CB:$('#b1F').val(),
            CM:$('#m1F').val(),
            CR:$('#r1F').val(),
            D:$('#fDilucion1F').val(),
            E:$('#volMuestra1F').val(),
            X:$('#abs11F').val(),
            Y:$('#abs21F').val(),
            Z:$('#abs31F').val(),
            ABS4:$('#abs41F').val(),
            ABS5:$('#abs51F').val(),
            ABS6:$('#abs61F').val(),
            ABS7:$('#abs71F').val(),
            ABS8:$('#abs81F').val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            let x = response.x.toFixed(3);
          $("#abs1F").val(x); 
          $("#abs2F").val(x); 
          let resultado = response.resultado.toFixed(3);
          $("#resultadoF").val(resultado); 
         // let d = response.d.toFixed(3);
          let d = response.d;
          $("#fDilucion1F").val(d);
          $("#fDilucion2F").val(d);
        }
    });
}

function updateObsMuestraEspectro()
{
    
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/updateObsMuestraEspectro",
        data: {
            idMuestra: idMuestra,
            observacion: $("#observacion").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            getLoteCapturaEspectro();
        }
    }); 
}

function random(min, max) {
    return Math.floor(Math.random() * (max - min + 1) + min);
}