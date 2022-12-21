var area = "fq";
var numMuestras = new Array();
var idMuestra = 0; 
var idLote = 0;
var tecnica = 0;
var blanco = 0;

$(document).ready(function () {
    $('#formulaTipo').select2();
});
$('#guardarSulfato').click(function () {
    guardarSulfatos(); 
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
    alert("Guardado");
});
$('#btnLiberar').click(function () {
    // operacion();
    liberarMuestra();
});
$('#btnLiberarTodo').click(function () {
    // operacion();
    liberarTodoEspectro();
});
$('#btnGenControles').click(function () {
    // operacion();
    createControlesCalidad();
});



function getDataCaptura() {
    numMuestras = new Array();
    cleanTable();
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
                    tab += '<td><button class="btn btn-success" id="btnImprimir" onclick="imprimir('+item.Id_lote+');"><i class="fas fa-file-download"></i></button></td>';
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
    let color = "";

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
                if (item.Liberado != 1) {
                    status = "";
                    color = "success";
                } else { 
                    status = "disabled";
                    color = "warning"
                }
                tab += '<tr>';
                switch ($("#formulaTipo").val()) { 
         
                    case '95':
                        tab += '<td><input hidden id="idMuestra'+item.Id_detalle+'" value="'+item.Id_detalle+'"><button '+status+' type="button" class="btn btn-'+color+'" onclick="getDetalleEspectroSulfatos('+item.Id_detalle+');" data-toggle="modal" data-target="#modalCapturaSulfatos">Capturar</button>';    
                        break;
                    case "152":
                        tab += '<td><input hidden id="idMuestra'+item.Id_detalle+'" value="'+item.Id_detalle+'"><button '+status+' type="button" class="btn btn-'+color+'" onclick="getDetalleEspectro('+item.Id_detalle+');" data-toggle="modal" data-target="#modalCaptura">Capturar</button>';
                        if(item.Id_control == "14"){
                            blanco = item.Resultado;
                        }
                        break;
                    default:
                        tab += '<td><input hidden id="idMuestra'+item.Id_detalle+'" value="'+item.Id_detalle+'"><button '+status+' type="button" class="btn btn-'+color+'" onclick="getDetalleEspectro('+item.Id_detalle+');" data-toggle="modal" data-target="#modalCaptura">Capturar</button>';
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
function guardarSulfatos(){
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/guardarSulfatos",
        data: {
            idMuestra:idMuestra,
            fechaAnalisis: $("#fechaAnalisis").val(),
            parametro: $('#formulaTipo').val(),
            resultado:$('#resultadoF').val(),
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
            resultado: $('#resultado').val(),
            observacion: $('#obs').val(),
            ABS:$('#abs1').val(),
            CA:$('#blanco1').val(),
            CB:$('#b1').val(),
            CM:$('#m1').val(),
            CR:$('#r1').val(),
            phIni:$('#phIni1').val(),
            phFin:$('#phFin1').val(),
            nitratos:$('#nitratos1').val(),
            nitritos:$('#nitritos1').val(),
            sulfuros:$('#sulfuros1').val(),
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
            getLoteCapturaEspectro();        
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
    switch (parseInt($("#formulaTipo").val())) {
        case 69:
            $("#conPh").show();
            $("#conPh2").show();

            $("#conN1").hide();
            $("#conN2").hide();
            $("#conN3").hide();
        break;
        case 70:
            $("#conPh").show();
            $("#conPh2").show();

            $("#conN1").hide();
            $("#conN2").hide();
            $("#conN3").hide();
            break;
        case 19:
            $("#conN1").show();
            $("#conN2").show();
            $("#conN3").show();

            $("#conPh").hide();
            $("#conPh2").hide();
            break;
        default:
            $("#conPh").hide();
            $("#conPh2").hide();

            $("#conN1").hide();
            $("#conN2").hide();
            $("#conN3").hide();
            break;
    }
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getDetalleEspectro",
        data: {
            formulaTipo: $("#formulaTipo").val(), 
            fechaAnalisis: $("#fechaAnalisis").val(),
            idDetalle: idDetalle,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) { 
            console.log(response);
            $("#observacion").val(response.model.Observacion);
            $("#abs1").val(response.model.Promedio);
            $("#abs2").val(response.model.Promedio);
            $("#idMuestra").val(idDetalle);
            $("#blanco1").val(response.model.Blanco);
            $("#blanco2").val(response.model.Blanco);
            $("#b1").val(response.curva.B);
            $("#m1").val(response.curva.M);
            $("#r1").val(response.curva.R);
            $("#b2").val(response.curva.B);
            $("#m2").val(response.curva.M);
            $("#r2").val(response.curva.R);
            $("#phIni1").val(response.model.Ph_ini);
            $("#phFin1").val(response.model.Ph_fin);
            $("#nitratos1").val(response.model.Nitratos);
            $("#nitritos1").val(response.model.Nitritos);
            $("#sulfuros1").val(response.model.Sulfuros);
            $("#fDilucion1").val(response.model.Vol_dilucion);
            $("#fDilucion2").val(response.model.Vol_dilucion);
            $("#volMuestra1").val(response.model.Vol_muestra);
            $("#volMuestra2").val(response.model.Vol_muestra);
            $("#abs11").val(response.model.Abs1);
            $("#abs21").val(response.model.Abs2);
            $("#abs31").val(response.model.Abs3);
            $("#abs12").val(response.model.Abs1);
            $("#abs22").val(response.model.Abs2);
            $("#abs32").val(response.model.Abs3);
            $("#resultado").val(response.model.Resultado);
            if($("#formulaTipo").val() == 152){
                $("#blanco1").val(blanco);
            }
        }
    });
}
function getDetalleEspectroSulfatos(idDetalle)
{
    switch (parseInt($("#formulaTipo").val())) {
        case 70:
            $("#conPh").show();
            $("#conPh2").show();

            $("#conN1").hide();
            $("#conN2").hide();
            $("#conN3").hide();
            break;
        case 20:
            $("#conN1").show();
            $("#conN2").show();
            $("#conN3").show();

            $("#conPh").hide();
            $("#conPh2").hide();
            break;
        default:
            $("#conPh").hide();
            $("#conPh2").hide();

            $("#conN1").hide();
            $("#conN2").hide();
            $("#conN3").hide();
            break;
    }
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getDetalleEspectroSulfatos",
        data: {
            formulaTipo: $("#formulaTipo").val(), 
            fechaAnalisis: $("#fechaAnalisis").val(),
            idDetalle: idDetalle,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) { 
            console.log(response);
            $("#observacionF").val(response.model.Observacion);
            $("#abs1F").val(response.model.Promedio);
            $("#abs2F").val(response.model.Promedio);
            $("#idMuestraF").val(idDetalle);
            $("#blanco1F").val(response.model.Blanco);
            $("#blanco2F").val(response.model.Blanco);
            $("#b1F").val(response.curva.B);
            $("#m1F").val(response.curva.M);
            $("#r1F").val(response.curva.R);
            $("#b2F").val(response.curva.B);
            $("#m2F").val(response.curva.M);
            $("#r2F").val(response.curva.R);
            $("#phIni1F").val(response.model.Ph_ini);
            $("#phFin1F").val(response.model.Ph_fin);
            $("#nitratos1F").val(response.model.Nitratos);
            $("#nitritos1F").val(response.model.Nitritos);
            $("#sulfuros1F").val(response.model.Sulfuros);
            $("#fDilucion1F").val(response.model.Vol_dilucion);
            $("#fDilucion2F").val(response.model.Vol_dilucion);
            $("#volMuestra1F").val(response.model.Vol_muestra);
            $("#volMuestra2F").val(response.model.Vol_muestra);
            $("#abs11F").val(response.model.Abs1);
            $("#abs21F").val(response.model.Abs2);
            $("#abs31F").val(response.model.Abs3);
            $("#abs12F").val(response.model.Abs1);
            $("#abs22F").val(response.model.Abs2);
            $("#abs32F").val(response.model.Abs3);
            $("#abs41F").val(response.model.Abs4);
            $("#abs42F").val(response.model.Abs4);
            $("#abs51F").val(response.model.Abs5);
            $("#abs52F").val(response.model.Abs5);
            $("#abs61F").val(response.model.Abs6);
            $("#abs62F").val(response.model.Abs6);
            $("#abs71F").val(response.model.Abs7);
            $("#abs72F").val(response.model.Abs7);
            $("#abs81F").val(response.model.Abs8);
            $("#abs82F").val(response.model.Abs8);
            $("#resultadoF").val(response.model.Resultado);
        }
    });
}

//Función imprimir PDF
function imprimir(id) {            
    // window.location(base_url + "/admin/laboratorio/"+area+"/captura/exportPdfCapturaEspectro/" + id);
    window.location = base_url + "/admin/laboratorio/"+area+"/captura/exportPdfCapturaEspectro/" + id;    
}

function operacion() {

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/operacionEspectro",
        data: {
            idMuestra: idMuestra,
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
          blanco = resultado;
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
            alert("Observación Envidada!");
        }
    }); 
}
function updateObsMuestraEspectroSulfatos()
{
    
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/updateObsMuestraEspectroSulfatos",
        data: {
            idMuestra: idMuestra,
            observacion: $("#observacionSulfatos").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            getLoteCapturaEspectro();
        }
    }); 
}
function updateObsMuestraEspectroSulfatos()
{
    
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/updateObsMuestraEspectroSulfatos",
        data: {
            idMuestra: idMuestra,
            observacion: $("#observacionSulfatos").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            getLoteCapturaEspectro();
        }
    }); 
}
function liberarMuestra()
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/liberarMuestraEspectro",
        data: {
            idMuestra: idMuestra,
            idLote:idLote,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            if(response.sw == true)
            {
                getDataCaptura();
                getLoteCapturaEspectro();
            }else{
                alert("La muestra no se pudo liberar");
            }
        }
    });
}
function liberarTodoEspectro()
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/liberarTodoEspectro",
        data: {
            idLote:idLote,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            if(response.sw == true)
            {
                getDataCaptura();
                getLoteCapturaEspectro();
            }else{
                alert("La muestra no se pudo liberar");
            }
        }
    });
}
function createControlCalidad()
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/createControlCalidadEspectro",
        data: {
            idMuestra: idMuestra,
            idLote:idLote,
            idControl: $("#controlCalidad").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            getDataCaptura();
            getLoteCapturaEspectro();
        }
    });
}
function createControlesCalidad()
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/createControlesCalidadEspectro",
        data: {
            idLote:idLote,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            getDataCaptura();
            getLoteCapturaEspectro();
        }
    });
}
function random(min, max) {
    return Math.floor(Math.random() * (max - min + 1) + min);
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

document.addEventListener("keydown", function(event) {
    if (event.altKey && event.code === "KeyB")
    {
        getDataCaptura();
        event.preventDefault();
    }
});