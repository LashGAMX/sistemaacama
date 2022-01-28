var area = "fq";
var idLote;

$(document).ready(function () {
    table = $('#tableAnalisis').DataTable({
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        }
    });
    table2 = $('#tableDatos2').DataTable({
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        }
    });
});


$('#guardar').click(function () {
    if($("#resultado").val() != ''){
        console.log("Metodo corto");
        validacionModal();
        //operacionSimple();
    }else{
        console.log("Metodo largo");
        operacionLarga();
    }
});
$('#btnLiberar').click(function () {
    // operacion();
    liberarMuestraMetal();
});

var numMuestras = new Array();
var idMuestra = 0;
function getDataCaptura() {
    numMuestras = new Array();
    let tabla = document.getElementById('divLote');
    let tab = '';

    let tabla2 = document.getElementById('divTablaControles');
    let tab2 = '';
    let cont = 1;

    let conte = document.getElementById('infoGlobal');
    let tab3 = '';
    let conte2 = document.getElementById('infoGen');
    let tab4 = '';
    let status = "";

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getDataCapturaSolidos",
        data: {
            formulaTipo: $("#formulaTipo").val(),
            fechaAnalisis: $("#fechaAnalisis").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);

            $("#idLote").val(response.lote.Id_lote);

            tab4 += '<p>Formula: (mf-mi/volumen)1000000- blanco </p>';
            conte2.innerHTML = tab4;

            tab += '<table id="tablaLote" class="table table-sm">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>Tipo formula</th>';
            tab += '          <th>Fecha lote</th> '; 
            tab += '          <th>Total asignado</th> ';
            tab += '          <th>Total liberados</th> ';
            tab += '          <th>Opc</th> ';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            tab += '<tr>';
            tab += '<td>' + response.lote.Tipo_formula + '</td>';
            tab += '<td>' + response.lote.Fecha + '</td>';
            tab += '<td>' + response.lote.Asignado + '</td>';
            tab += '<td>' + response.lote.Liberado + '</td>';
            tab += '<td><button class="btn btn-success" id="btnImprimir" onclick="imprimir();"><i class="fas fa-file-download"></i></button></td>';
            tab += '</tr>';
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;


            tab2 += '<table id="tablaControles" class="table table-sm">';
            tab2 += '    <thead>';
            tab2 += '        <tr>';
            tab2 += '          <th>Opc</th>';
            tab2 += '          <th>Folio</th>';
            tab2 += '          <th># toma</th>';
            tab2 += '          <th>Norma</th>';
            tab2 += '          <th>Resultado</th>';
            tab2 += '          <th>Tipo Análisis</th>';
            tab2 += '          <th>Observación</th>';
            tab2 += '        </tr>';
            tab2 += '    </thead>'; 
            tab2 += '    <tbody>';
            $.each(response.detalle, function (key, item) {
                tab2 += '<tr>';
                if (item.Liberado != 0) {
                    status = "";
                } else { 
                    status = "disabled";
                }
                tab2 += '<td><input hidden id="idMuestra'+item.Id_detalle+'" value="'+item.Id_detalle+'"><button type="button" class="btn btn-success" onclick="getDetalleSolidos('+item.Id_detalle+');" data-toggle="modal" data-target="#modalCaptura">Capturar</button>';
                if (item.Id_control != 1) 
                {
                    tab2 += '<br> <small class="text-danger">'+item.Control+'</small></td>';
                }else{
                    tab2 += '<br> <small class="text-info">'+item.Control+'</small></td>';
                }
                tab2 += '<td><input disabled style="width: 80px" value="'+item.Folio_servicio+'"></td>';
                tab2 += '<td><input disabled style="width: 80px" value="-"></td>';
                tab2 += '<td><input disabled style="width: 80px" value="'+item.Clave_norma+'"></td>';
                tab2 += '<td><input disabled style="width: 80px" value="'+item.Resultado+'"></td>';
                tab2 += '<td><input disabled style="width: 80px" value="-"></td>';
                tab2 += '<td>'+item.Observacion+'</td>';
                tab2 += '</tr>';
                numMuestras.push(item.Id_detalle);
                cont++;
            }); 
            tab2 += '    </tbody>';
            tab2 += '</table>';
            tabla2.innerHTML = tab2;

            var t = $('#tablaControles').DataTable({
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
                    table.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            });
            $('#tablaControles tr').on('click', function () {
                let dato = $(this).find('td:first');
                idMuestra = dato[0].firstElementChild.value;
                // console.log(idMuestra);
            });

            idLote = response.lote.Id_lote;

            /* console.log("Valor de idLote: " + response.lote.Id_lote);
            imprimir(response.lote.Id_lote); */
        }
    });
}

//Función imprimir PDF
function imprimir() {
    /* console.log("Dentro de evento imprimir modif");
    $('#btnImprimir').click(function () {
        console.log("Valor de idLote: " + idLote);   */     
        window.location = base_url + "/admin/laboratorio/"+area+"/captura/exportPdfCapturaSolidos/"+idLote;
        //window.location = base_url + "/admin/laboratorio/"+area+"/captura/exportPdfCapturaGA";
    //});
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
        sw = false;
        
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
         
        }
    });
}
function operacionLarga() {

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/operacionSolidosLarga", 
        data: {
            
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
        }
    });
}


function getDetalleSolidos(idDetalle)
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getDetalleGA",
        data: {
            idDetalle: idDetalle,
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            $("#h1").val(response.model.M_final);
            $("#j1").val(response.model.M_inicial1);
            $("#k1").val(response.model.M_inicial2);
            $("#c1").val(response.model.M_inicial3);
            $("#l1").val(response.model.Ph);
            $("#i1").val(response.model.Vol_muestra);
            $("#g1").val(response.model.Blanco);
            $("#e1").val(response.model.F_conversion);
            $("#observacion").val(response.model.Observacion);
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
            getDataCaptura();
        }
    });
}