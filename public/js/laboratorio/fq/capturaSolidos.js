var area = "fq";
var numMuestras = new Array();
var idMuestra = 0; 
var idLote = 0;
var tecnica = 0;


$(document).ready(function () {

});

$('#guardar').click(function () { 
    if($("#resultado").val() != ''){
        console.log("Metodo corto");
        // validacionModal();

        operacionSimple();
    }else{
        console.log("Metodo largo");
        operacionLarga();
    }
});
$('#guardarDif').click(function () { 
    operacionSolidosDif();
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
            url: base_url + "/admin/laboratorio/"+area+"/getLoteSolidos",
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
                    getLoteCapturaSolidos();
                  });
            }
        });
}

function getLoteCapturaSolidos() {
    numMuestras = new Array();
    let tabla = document.getElementById('divTablaControles');
    let tab = '';
    let cont = 1;

    let status = "";

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getLoteCapturaSolidos",
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
                if($("#formulaTipo").val() != 89)
                {
                    tab += '<td><input hidden id="idMuestra'+item.Id_detalle+'" value="'+item.Id_detalle+'"><button type="button" class="btn btn-success" onclick="getDetalleSolidos('+item.Id_detalle+',1);" data-toggle="modal" data-target="#modalCaptura">Capturar</button>';
                }
                else 
                {
                    tab += '<td><input hidden id="idMuestra'+item.Id_detalle+'" value="'+item.Id_detalle+'"><button type="button" class="btn btn-success" onclick="getDetalleSolidos('+item.Id_detalle+',2);" data-toggle="modal" data-target="#modalDiferencia">Capturar</button>';
                }
                
                if (item.Id_control != 1) 
                {
                    tab += '<br> <small class="text-danger">'+item.Control+'</small></td>';
                }else{
                    tab += '<br> <small class="text-info">'+item.Control+'</small></td>';
                }
                tab += '<td><input disabled style="width: 80px" value="'+item.Folio_servicio+'"></td>';
                tab += '<td><input disabled style="width: 80px" value="'+item.Clave_norma+'"></td>';
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
function imprimir(id) {    
    window.open(base_url + "/admin/laboratorio/"+area+"/captura/exportPdfCapturaSolidos/"+id);   
    //window.location = base_url + "/admin/laboratorio/"+area+"/captura/exportPdfCapturaSolidos/"+idLote;
}

function validacionModal(){
    let sw = true;
    
 if(validarCampos("campos")){
    if($("#m11").val()!= $("#m12").val())
    {
        //alert('Los valores de BLANCO no son iguales')
        // $("#blanco2").attr("class", "bg-danger");
        // $("#blanco2").attr("class", "bg-danger");
        inputFocus("m11")
        inputFocus("m12")
        sw = false;d
        
        console.log('no valido'); 
    }
    if($("#m21").val()!= $("#m22").val())
    {
        //alert('Los valores de VOLUMEN MUESTRA no son iguales')
        inputFocus("m21")
        inputFocus("m22")
        sw = false;
    }
    if($("#pcm11").val()!= $("#pcn12").val())
    {
        //alert('Los valores de ABS no son iguales')
        inputFocus("pcm11")
        inputFocus("pcm12")
        sw = false;
    }
    if($("#pcm21").val()!= $("#pcm22").val())
    {
        //alert('Los valores de ABS no son iguales')
        inputFocus("pcm21")
        inputFocus("pcm22")
        sw = false;
    }
    if($("#pc1").val()!= $("#pc2").val())
    {
        //alert('Los valores de ABS no son iguales')
        inputFocus("pc1")
        inputFocus("pc2")
        sw = false;
    }
    if($("#v1").val()!= $("#v2").val())
    {
        //alert('Los valores de ABS no son iguales')
        inputFocus("v1")
        inputFocus("v2")
        sw = false;
    }
    
    if(sw == true)
    {
        operacionSimple();
        inputDesFocus();
    }
 }
 
}

function operacionSimple() {

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/operacionSolidosSimple", 
        data: {
            idMuestra: idMuestra,
            R:$("#resultado").val(),
            masa1:$("#m11").val(),
            masa2:$("#m21").val(),
            pesoConMuestra1:$("#pcm11").val(),
            pesoConMuestra2:$("#pcm21").val(),
            pesoC1:$("#pc1").val(),
            pesoC2:$("#pc21").val(),
            volumen:$("#v1").val(),
            factor:$("#f1").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            
            $('#p').val(response.serie); 
            $("#m11").val(response.masa1);
            $("#m21").val(response.masa2);
            $("#pcm11").val(response.pesoConMuestra1);
            $("#pcm21").val(response.pesoConMuestra2); 
            $("#pc1").val(response.pesoC1);
            $("#pc21").val(response.pesoC2);
            getLoteCapturaSolidos();
        }
    });
}
function operacionLarga() {

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/operacionSolidosLarga", 
        data: {
            idMuestra:idMuestra,
            crisol:$("#crisol").val(),
            masa1:$("#m11").val(),
            masa2:$("#m21").val(),
            pesoConMuestra1:$("#pcm11").val(),
            pesoConMuestra2:$("#pcm21").val(),
            pesoC1:$("#pc1").val(),
            pesoC2:$("#pc21").val(),
            volumen:$("#v1").val(),
            factor:$("#f1").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            $('#resultado').val(response.res.toFixed(4));
            getLoteCapturaSolidos();
        }
    });
}

function operacionSolidosDif() {

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/operacionSolidosDif", 
        data: {
            idMuestra: idMuestra,
            resultado:$("#preResDif").val(),
            resultado:$("#preResDif").val(),
            resultado:$("#preResDif").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            $("#resultadoDif").val(response.res);
            getLoteCapturaSolidos();
        }
    });
}


function getDetalleSolidos(idDetalle,num)
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getDetalleSolidos",
        data: {
            num:num,
            idLote: idLote,
            idParametro: $("#formulaTipo").val(),
            idDetalle: idDetalle,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
           switch (num) {
               case 1:
                $("#m11").val(response.detalle.Masa1);
                $("#m12").val(response.detalle.Masa1);
                $("#m21").val(response.detalle.Masa2);
                $("#m22").val(response.detalle.Masa2);
                $("#pcm11").val(response.detalle.Peso_muestra1);
                $("#pcm12").val(response.detalle.Peso_muestra1);
                $("#pcm21").val(response.detalle.Peso_muestra2);
                $("#pcm22").val(response.detalle.Peso_muestra2);
                $("#pc1").val(response.detalle.Peso_constante1);
                $("#pc2").val(response.detalle.Peso_constante1);
                $("#pc21").val(response.detalle.Peso_constante2);
                $("#pc22").val(response.detalle.Peso_constante2);
                $("#v1").val(response.detalle.Vol_muestra);
                $("#v2").val(response.detalle.Vol_muestra);
                $("#f1").val(response.detalle.Factor_conversion);
                $("#f1").val(response.detalle.Factor_conversion);

                $("#crisol").val(response.detalle.Crisol);
                $("#resultado").val(response.detalle.Resultado);
                $("#observacion").val(response.detalle.Observacion);
                   break;
           case 2:
                $("#nomParametro1").val(response.nom1);
                $("#val11").val(response.dif1.Resultado);
                $("#nomParametro2").val(response.nom2);
                $("#val21").val(response.dif2.Resultado);
                let res = response.dif1.Resultado - response.dif2.Resultado;
                $("#preResDif").val(res);
                $("#resultadoDif").val(response.detalle.Resultado);
                $("#observacionDif").val(response.detalle.Observacion);
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
function updateObsMuestraSolidos() 
{
    
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/updateObsMuestraSolidos",
        data: {
            idMuestra: idMuestra,
            observacion: $("#observacion").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            getLoteCapturaSolidos();
        }
    }); 
}
function updateObsMuestraSolidosDif() 
{
    
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/updateObsMuestraSolidosDif",
        data: {
            idMuestra: idMuestra,
            observacion: $("#observacionDif").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            getLoteCapturaSolidos();
        }
    }); 
}
function createControlCalidad()
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/createControlCalidadSolidos",
        data: {
            idMuestra: idMuestra,
            idControl: $("#controlCalidad").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            getLoteCapturaSolidos();
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