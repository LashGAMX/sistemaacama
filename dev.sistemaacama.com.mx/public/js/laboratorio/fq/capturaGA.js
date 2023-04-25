var area = "fq";
var numMuestras = new Array();
var idMuestra = 0; 
var idLote = 0;
var tecnica = 0;
var blanco = 0;
$(document).ready(function () {
    $('#formulaTipo').select2();
});

$('#btnLiberarTodo').click(function () {
    liberarTodo();
});
$('#guardar').click(function () {
    if($("#resultado").val() != ''){
        console.log("Metodo corto");
        operacionSimple();
    }else{
        console.log("Metodo largo");
        operacionLarga();
    }
});
$('#btnLiberar').click(function () {
    // operacion();
    // liberarMuestraMetal
    liberarMuestra();
});

function getDataCaptura() {
    cleanTable();
    numMuestras = new Array();
    let tabla = document.getElementById('divLote');
    let tab = '';


        $.ajax({ 
            type: "POST",
            url: base_url + "/admin/laboratorio/"+area+"/getLoteGA",
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
                    getLoteCapturaGA();
                  });
            }
        });
}

function getLoteCapturaGA() {
    numMuestras = new Array();
    let tabla = document.getElementById('divTablaControles');
    let tab = '';
    let cont = 1;

    let status = "";
    let color = "";

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/getLoteCapturaGA",
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
                if (item.Id_control == 5) {
                  blanco = item.Resultado
                } 
                if (item.Liberado != 1) {
                    status = "";
                    color = "success";
                } else { 
                    status = "disabled";
                    color = "warning"
                }
                tab += '<td><input hidden id="idMuestra'+item.Id_detalle+'" value="'+item.Id_detalle+'"><button '+status+' type="button" class="btn btn-'+color+'" onclick="getDetalleGA('+item.Id_detalle+');" data-toggle="modal" data-target="#modalCaptura">Capturar</button>';
                if (item.Id_control != 1) 
                {
                    tab += '<br> <small class="text-danger">'+item.Control+'</small></td>';
                }else{
                    tab += '<br> <small class="text-info">'+item.Control+'</small></td>';
                }
                tab += '<td><input disabled style="width: 120px" value="'+item.Codigo+'"></td>';
                tab += '<td><input disabled style="width: 100px" value="'+item.Clave_norma+'"></td>';
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
                "pageLength": 100,
                "language": {
                    "lengthMenu": "# _MENU_ por pagina",
                    "zeroRecords": "No hay datos encontrados",
                    "info": "Pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay datos encontrados",
                },
                "scrollY": 400,
                "scrollCollapse": true
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
    window.open(base_url + "/admin/laboratorio/"+area+"/captura/exportPdfCapturaGA/"+id);
    //window.location = base_url + "/admin/laboratorio/"+area+"/captura/exportPdfCapturaGA/"+idLote;
}

function operacionSimple() {

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/operacionGASimple", 
        data: {
            idMuestra:idMuestra,
            P:$("#p").val(),
            R:$("#resultado").val(),
            H:$("#h1").val(),
            J:$("#j1").val(),
            K:$("#k1").val(),
            C:$("#c1").val(),
            L:$("#l1").val(),
            I:$("#i1").val(),
            G:$("#g1").val(),
            E:$("#e1").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            if (response.std == true) {
                let fixh1 = response.mf.toFixed(4);
                let fixj1 = response.m1.toFixed(4);
                let fixk1 = response.m2.toFixed(4);
                let fixc1 = response.m3.toFixed(4); 
            
                $("#h1").val(fixh1); 
                $("#j1").val(fixj1);
                $("#k1").val(fixk1);
                $("#c1").val(fixc1);
                $('#p').val(response.serie);
                $('#resultado').val(response.res.toFixed(4));

                getLoteCapturaGA();   
                alert("Datos guardados y calculados")
            } else {
                alert("No hay matraz disponible")
            }
        }
    });
}
function operacionLarga() {

    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/operacionGALarga", 
        data: {
            idMuestra:idMuestra,
            P:$("#p").val(),
            //R:$("#resultado").val(),
            H:$("#h1").val(),
            J:$("#j1").val(),
            K:$("#k1").val(),
            C:$("#c1").val(),
            L:$("#l1").val(),
            I:$("#i1").val(),
            G:$("#g1").val(),
            E:$("#e1").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            $('#resultado').val(response.res.toFixed(4));
            getLoteCapturaGA();
        }
    });
}


function getDetalleGA(idDetalle)
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
            $("#p").val(response.model.Matraz);
            $("#resultado").val(response.model.Resultado);
            $("#h1").val(response.model.M_final);
            $("#j1").val(response.model.M_inicial1);
            $("#k1").val(response.model.M_inicial2);
            $("#c1").val(response.model.M_inicial3);
            $("#l1").val(response.model.Ph);
            $("#i1").val(response.model.Vol_muestra);
            if(response.model.Id_control != 5)
            {
                $("#g1").val(blanco);   
                $("#g2").val(blanco);
            }else{
                $("#g1").val(response.model.Blanco);   
                $("#g2").val(response.model.Blanco);
            }
            $("#e1").val(response.model.F_conversion);
            $("#observacion").val(response.model.Observacion);


            $("#h2").val(response.model.M_final);
            $("#j2").val(response.model.M_inicial1);
            $("#k2").val(response.model.M_inicial2);
            $("#c2").val(response.model.M_inicial3);
            $("#l2").val(response.model.Ph);
            $("#i2").val(response.model.Vol_muestra);
            $("#e2").val(response.model.F_conversion);
        }
    });
}
function random(min, max) {
    return Math.floor(Math.random() * (max - min + 1) + min);
}
function updateObsMuestraGA()
{
    
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/updateObsMuestraGA",
        data: {
            idMuestra: idMuestra,
            observacion: $("#observacion").val(),
            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
            getLoteCapturaGA();
        }
    }); 
}
function createControlCalidad()
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/createControlCalidad",
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
        }
    });
}

function liberarMuestra()
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/liberarMuestraGa",
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
                getLoteCapturaGA();
            }else{
                alert("La muestra no se pudo liberar");
            }
        }
    });
}
function liberarTodo() 
{
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/" + area + "/liberarTodoGA",
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
                getLoteCapturaVol();
            }else{
                alert("La muestra no se pudo liberar");
            }
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