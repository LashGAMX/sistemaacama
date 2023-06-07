
var area = "fq";
$(document).ready(function () {

    $('#summernote').summernote({
        placeholder: '', 
        tabsize: 2,
        height: 300,

      });

      $('#tipo').select2();
    //   $('#tipoFormula').select2();
      

    table = $('#table').DataTable({        
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        }
    });
    $('#btnPendiente').click(function () {
        getPendientes()
    });
    $('#btnBitacora').click(function () {
        setPlantillaDetalleFq()
    });
});

function createLote() 
{
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/"+area+"/createLote",
        data: {
            tipo: $("#tipoFormula").val(),
            fecha: $("#fechaLote").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            swal("Registro!", "Lote creado correctamente!", "success");
            $('#modalCrearLote').modal('hide')
        }
    });
}
function getPendientes()
{ 
    let tabla = document.getElementById('divPendientes');
    let tab = '';
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/"+area+"/getPendientes",
        data: {
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            model = response.model
            tab += '<table class="table table-sm" style="font-size:10px" id="tablePendientes">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>Folio</th>';
            tab += '          <th>Parametro</th>';
            tab += '          <th>Fecha recepción</th>';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            for (let i = 0; i < model.length; i++) {
                tab += '<tr>';
                tab += '<td>'+model[i][0]+'</td>';
                tab += '<td>'+model[i][1]+'</td>';
                tab += '<td>'+model[i][2]+'</td>';
                tab += '</tr>';   
            }
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;

            table = $('#tablePendientes').DataTable({        
                "ordering": false,
                "language": {
                    "lengthMenu": "# _MENU_ por pagina",
                    "zeroRecords": "No hay datos encontrados",
                    "info": "Pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay datos encontrados",
                }
            });
        }
    });
}

function buscarLote()
{
    let tabla = document.getElementById('divTable');
    let tab = '';
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/"+area+"/buscarLote",
        data: {
            tipo: $("#tipo").val(),
            fecha: $("#fecha").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            tab += '<table id="tablaLote" class="table table-sm">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>#</th>';
            tab += '          <th>Tipo formula</th>';
            tab += '          <th>Fecha lote</th> ';
            tab += '          <th>Fecha creacion</th> ';
            tab += '          <th>Opc</th> ';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
                if(response.sw == true)
                {
                    $.each(response.model, function (key, item) {
                        tab += '<tr>';
                        tab += '<td>'+item.Id_lote+'</td>';
                        tab += '<td>'+item.Parametro+' ('+item.Tipo_formula+')</td>';
                        tab += '<td>'+item.Fecha+'</td>';
                        tab += '<td>'+item.created_at+'</td>';
                        tab += '<td>'
                        tab += '<button type="button" id="btnAsignar" onclick="setAsignar('+item.Id_lote+')"  class="btn btn-primary"><i class="fas fa-plus"></i> Agregar</button>'
                        tab += '&nbsp<button type="button" class="btn btn-warning" onclick="getDetalleLoteFq('+item.Id_lote+',\''+item.Parametro+'\')" data-toggle="modal" data-target="#modalDetalle"><i class="fas fa-info"></i> Detalle</button>'
                        tab += '</td>';
                      tab += '</tr>';
                    });
                }else{
                    tab += '<h5 style="color:red;">No hay datos</h5>';
                }
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;
            $("#numLotes").val(response.lotes);
        }
    });
}
var idLote = 0;
function getDetalleLoteFq(id,parametro)
{
    $("#idLote").val(id+' '+parametro)
    idLote = id;
    let summer = document.getElementById("divSummer");
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/"+area+"/getDetalleLoteFq",
        data: {
            id: id,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);                        
            $("#tituloBit").val(response.plantilla[0].Titulo)
            $("#revBit").val(response.plantilla[0].Rev)
            summer.innerHTML = '<div id="summernote">'+response.plantilla[0].Texto+'</div>';

            // summer.innerHTML = '<div id="summernote">Hola Modal</div>';
            $('#summernote').summernote({
                placeholder: '', 
                tabsize: 2,
                height: 300,         
            }); 
            // LLENADO DE GRASAS DETALLE
            $('#temp1').val(response.grasasDetalle.Calentamiento_temp1);
            $('#entrada1').val(response.grasasDetalle.Calentamiento_entrada1);
            $('#salida1').val(response.grasasDetalle.Calentamiento_salida1);
            $('#temp2').val(response.grasasDetalle.Calentamiento_temp2);
            $('#entrada2').val(response.grasasDetalle.Calentamiento_entrada2);
            $('#salida2').val(response.grasasDetalle.Calentamiento_salida2);
            $('#temp3').val(response.grasasDetalle.Calentamiento_temp3);
            $('#entrada3').val(response.grasasDetalle.Calentamiento_entrada3);
            $('#salida3').val(response.grasasDetalle.Calentamiento_salida3);

            $('#2entrada1').val(response.grasasDetalle.Enfriado_entrada1);
            $('#2salida1').val(response.grasasDetalle.Enfriado_salida1);
            $('#2pesado1').val(response.grasasDetalle.Enfriado_pesado1);
            $('#2entrada2').val(response.grasasDetalle.Enfriado_entrada2);
            $('#2salida2').val(response.grasasDetalle.Enfriado_salida2);
            $('#2pesado2').val(response.grasasDetalle.Enfriado_pesado2);
            $('#2entrada3').val(response.grasasDetalle.Enfriado_entrada3);
            $('#2salida3').val(response.grasasDetalle.Enfriado_salida3);
            $('#2pesado3').val(response.grasasDetalle.Enfriado_pesado3);

            $('#3temperatura').val(response.grasasDetalle.Secado_temp);
            $('#3entrada').val(response.grasasDetalle.Secado_entrada);
            $('#3salida').val(response.grasasDetalle.Secado_salida);

            $('#4entrada').val(response.grasasDetalle.Reflujo_entrada);
            $('#4salida').val(response.grasasDetalle.Reflujo_salida);

            $('#5entrada').val(response.grasasDetalle.Enfriado_matraces_entrada);
            $('#5salida').val(response.grasasDetalle.Enfriado_matraces_salida);
        }
    });
}
function guardarDetalleGrasas(){
    $.ajax({ 
        type: "POST",
        url: base_url + "/admin/laboratorio/"+area+"/guardarDetalleGrasas",
        data: {
            id: idLote,
            temp1:$('#temp1').val(),
            entrada1:$('#entrada1').val(),
            salida1:$('#salida1').val(),
            temp2:$('#temp2').val(),
            entrada2:$('#entrada2').val(),
            salida2:$('#salida2').val(),
            temp3:$('#temp3').val(),
            entrada3:$('#entrada3').val(),
            salida3:$('#salida3').val(),
            dosentrada1:$('#2entrada1').val(),
            dosalida1:$('#2salida1').val(),
            dospesado1:$('#2pesado1').val(),
            dosentrada2:$('#2entrada2').val(),
            dosalida2:$('#2salida2').val(),
            dospesado2:$('#2pesado2').val(),
            dosentrada3:$('#2entrada3').val(),
            dosalida3:$('#2salida3').val(),
            dospesado3:$('#2pesado3').val(),
            trestemperatura:$('#3temperatura').val(),
            tresentrada:$('#3entrada').val(),
            tressalida:$('#3salida').val(),
            cuatroentrada:$('#4entrada').val(),
            cuatrosalida:$('#4salida').val(),
            cincoentrada:$('#5entrada').val(),
            cincosalida:$('#5salida').val(),

            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);                        
            alert("Plantilla modificada")
        }
    });
}
function setPlantillaDetalleFq(){
    $.ajax({ 
        type: "POST",
        url: base_url + "/admin/laboratorio/"+area+"/setPlantillaDetalleFq",
        data: {
            id: idLote,
            texto: $("#summernote").summernote('code'),
            titulo: $("#tituloBit").val(),
            rev:$("#revBit").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);                        
            alert("Plantilla modificada")
        }
    });
}

function setAsignar(id)
{
    window.location = base_url + "/admin/laboratorio/"+area+"/asgnarMuestraLote/"+id;
}

//Adaptando para FQ
function getDatalote()
{
    let tabla = document.getElementById('divTableFormulaGlobal');
    let tab = '';
    let summer = document.getElementById("divSummer");
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/"+area+"/getDatalote",
        data: {
            idLote:$("#idLoteHeader").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.group(response);
            
            //console.log("Valor de idLote: " + response[5]);
            
            if(response.idLoteIf == 0 || response.idLoteIf < 0 || !response.idLoteIf){
                tab += '<table id="tableFormulasGlobales" class="table table-sm">';
                tab += '<thead>'
                tab +=      '<tr>'
                tab +=          '<th scope="col">Fórmula</th>'
                tab +=          '<th scope="col">Resultado</th>'
                tab +=          '<th scope="col">Núm.Decimales</th>'
                tab +=      '</tr>'
                tab += '</thead>'
                tab += '<tbody>'
                tab +=      '<tr>'
                //tab +=          '<td></td>'
                //tab +=          '<td></td>'
                //tab +=          '<td></td>'
                tab +=      '</tr>'
                tab += '</tbody>'
                tab += '</table>';
                tabla.innerHTML = tab;
            }else if(response.idLoteIf > 0){
                tab += '<table id="tableFormulasGlobales" class="table table-sm">';
                tab += '    <thead class="thead-dark">';
                tab += '        <tr>';
                tab += '          <th>Fórmula</th>';
                tab += '          <th>Resultado</th> ';
                tab += '          <th>Núm. Decimales</th> ';
                tab += '        </tr>';
                tab += '    </thead>';
                tab += '    <tbody>';
                
                if(response.curvaConstantes !== null){
                    tab +=          '<tr>';
                    tab +=              '<td>B</td>';
                    tab +=              '<td>'+response.curvaConstantes.B+'</td>';
                    tab +=              '<td>3</td>';
                    tab +=          '</tr>';

                    tab +=          '<tr>';
                    tab +=              '<td>M</td>';
                    tab +=              '<td>'+response.curvaConstantes.M+'</td>';
                    tab +=              '<td>3</td>';
                    tab +=          '</tr>';

                    tab +=          '<tr>';
                    tab +=              '<td>R</td>';
                    tab +=              '<td>'+response.curvaConstantes.R+'</td>';
                    tab +=              '<td>3</td>';
                    tab +=          '</tr>';
                }
                

                }); */ 

                tab += '    </tbody>';
                tab += '</table>';
                tabla.innerHTML = tab;
            }


            //------------------------Grasas
            //console.log("Arreglo grasas: " + response.dataGrasas[0][0].Temperatura);

            //calentamiento de matraces
            if(response.dataGrasas[0] !== null){
                for(let i = 0; i < 3; i++){
                    $("#calLote" + (i+1)).val(response.dataGrasas[0][i].Id_lote);
                    $("#calMasa" + (i+1)).val(response.dataGrasas[0][i].Masa_constante);
                    $("#calTemp" + (i+1)).val(response.dataGrasas[0][i].Temperatura);
                    $("#calEntrada" + (i+1)).val(response.dataGrasas[0][i].Entrada);
                    $("#calSalida" + (i+1)).val(response.dataGrasas[0][i].Salida);
                }
            }else{
                for(let i = 0; i < 3; i++){
                    $("#calLote" + (i+1)).val('');
                    $("#calMasa" + (i+1)).val('');
                    $("#calTemp" + (i+1)).val('');
                    $("#calEntrada" + (i+1)).val('');
                    $("#calSalida" + (i+1)).val('');
                }
            }

            //enfriado de matraces
            if(response.dataGrasas[1] !== null){
                for(let i = 0; i < 3; i++){
                    $("#enfLote" + (i+1)).val(response.dataGrasas[1][i].Id_lote);
                    $("#enfMasa" + (i+1)).val(response.dataGrasas[1][i].Masa_constante);
                    $("#enfEntrada" + (i+1)).val(response.dataGrasas[1][i].Entrada);
                    $("#enfSalida" + (i+1)).val(response.dataGrasas[1][i].Salida);
                    $("#enfPesado" + (i+1)).val(response.dataGrasas[1][i].Pesado_matraz);
                }
            }else{
                for(let i = 0; i < 3; i++){
                    $("#enfLote" + (i+1)).val('');
                    $("#enfMasa" + (i+1)).val('');
                    $("#enfEntrada" + (i+1)).val('');
                    $("#enfSalida" + (i+1)).val('');
                    $("#enfPesado" + (i+1)).val('');
                }
            }

            //secado de cartuchos
            if(response.dataGrasas[2] !== null){
                $("#secadoLote1").val(response.dataGrasas[2].Id_lote);
                $("#secadoTemp1").val(response.dataGrasas[2].Temperatura);
                $("#secadoEntrada1").val(response.dataGrasas[2].Entrada);
                $("#secadoSalida1").val(response.dataGrasas[2].Salida);
            }else{
                $("#secadoLote1").val('');
                $("#secadoTemp1").val('');
                $("#secadoEntrada1").val('');
                $("#secadoSalida1").val('');
            }

            //tiempo de reflujo
            if(response.dataGrasas[3] !== null){
                $("#tiempoLote1").val(response.dataGrasas[3].Id_lote);
                $("#tiempoEntrada1").val(response.dataGrasas[3].Entrada);
                $("#tiempoSalida1").val(response.dataGrasas[3].Salida);
            }else{
                $("#tiempoLote1").val('');
                $("#tiempoEntrada1").val('');
                $("#tiempoSalida1").val('');
            }

            //enfriado de matraces
            if(response.dataGrasas[4] !== null){
                $("#enfriadoLote1").val(response.dataGrasas[4].Id_lote);
                $("#enfriadoEntrada1").val(response.dataGrasas[4].Entrada);
                $("#enfriadoSalida1").val(response.dataGrasas[4].Salida);
            }else{
                $("#enfriadoLote1").val('');
                $("#enfriadoEntrada1").val('');
                $("#enfriadoSalida1").val('');
            }

            //------------------------Coliformes
            
            if((response.dataColi[0] !== null) && (response.dataColi[1] !== null) && (response.dataColi[2] !== null)){
                //Formatea la fecha a un formato admitido por el input datetime
                let fecha = response.dataColi[0].Sembrado;
                let fechaIngresada = moment(fecha, 'YYYY-MM-DDTHH:mm');
                let fechaFormateada = moment(fechaIngresada).format('yyyy-MM-DDTHH:mm');                                

                $("#sembrado_loteId").val(response.dataColi[0].Id_lote);
                $("#sembrado_sembrado").val(fechaFormateada);                
                $("#sembrado_fechaResiembra").val(response.dataColi[0].Fecha_resiembra);
                $("#sembrado_tuboN").val(response.dataColi[0].Tubo_n);
                $("#sembrado_bitacora").val(response.dataColi[0].Bitacora);

                //--------------------------
                fecha = response.dataColi[1].Preparacion;
                fechaIngresada = moment(fecha, 'YYYY-MM-DDTHH:mm');
                fechaFormateada = moment(fechaIngresada).format('yyyy-MM-DDTHH:mm');
                $('#pruebaPresuntiva_preparacion').val(fechaFormateada);
                
                fecha = response.dataColi[1].Lectura;
                fechaIngresada = moment(fecha, 'YYYY-MM-DDTHH:mm');
                fechaFormateada = moment(fechaIngresada).format('yyyy-MM-DDTHH:mm');
                $('#pruebaPresuntiva_lectura').val(fechaFormateada);

                //--------------------------
                $('#pruebaConfirmativa_medio').val(response.dataColi[2].Medio);

                fecha = response.dataColi[2].Preparacion;
                fechaIngresada = moment(fecha, 'YYYY-MM-DDTHH:mm');
                fechaFormateada = moment(fechaIngresada).format('yyyy-MM-DDTHH:mm');
                $('#pruebaConfirmativa_preparacion').val(fechaFormateada);

                fecha = response.dataColi[2].Lectura;
                fechaIngresada = moment(fecha, 'YYYY-MM-DDTHH:mm');
                fechaFormateada = moment(fechaIngresada).format('yyyy-MM-DDTHH:mm');
                $('#pruebaConfirmativa_lectura').val(fechaFormateada);

            }else{                
                $("#sembrado_loteId").val('');
                $("#sembrado_sembrado").val('');
                $("#sembrado_fechaResiembra").val('');
                $("#sembrado_tuboN").val('');
                $("#sembrado_bitacora").val('');                

                $('#pruebaPresuntiva_preparacion').val('');
                $('#pruebaPresuntiva_lectura').val('');                

                $('#pruebaConfirmativa_medio').val('');
                $('#pruebaConfirmativa_preparacion').val('');
                $('#pruebaConfirmativa_lectura').val('');                                
            }    
            
            //------------DQO------------
            if(response.dataDqo !== null){
                $("#ebullicion_loteId").val(response.dataDqo.Id_lote);
                
                /* let fecha = response.dataDqo.Inicio;
                let fechaIngresada = moment(fecha, 'DD-MM-YYYY');
                let fechaFormateada = moment(fechaIngresada).format('yyyy-MM-DD'); */
                $("#ebullicion_inicio").val(response.dataDqo.Inicio);
                
                /* fecha = response.dataDqo.Fin;
                fechaIngresada = moment(fecha, 'DD-MM-YYYY');
                fechaFormateada = moment(fechaIngresada).format('yyyy-MM-DD'); */
                $("#ebullicion_fin").val(response.dataDqo.Fin);

                $("#ebullicion_invlab").val(response.dataDqo.Invlab);
            }else{
                $("#ebullicion_loteId").val('');
                $("#ebullicion_inicio").val('');
                $("#ebullicion_fin").val('');
                $("#ebullicion_invlab").val('');
            }
            //-----------------------------------------

            console.log("actualizado");            

            if(response.reporte !== null){
                summer.innerHTML = '<div id="summernote">'+response.reporte.Texto+'</div>';
                $('#summernote').summernote({
                    placeholder: '',
                    tabsize: 2,
                    height: 100,
            
                });
            }else{
                $.ajax({
                    type: "POST",
                    url: base_url + "/admin/laboratorio/"+area+"/getDataLote/plantillaPredeterminada",
                    data: {
                        idLote: $("#idLoteHeader").val(),
                        _token: $('input[name="_token"]').val(),
                    },
                    dataType: "json",
                    async: false,
                    success: function (response) {
                        //console.log(response);                        
                        summer.innerHTML = '<div id="summernote">'+response.Texto+'</div>';
                        $('#summernote').summernote({
                            placeholder: '',
                            tabsize: 2,
                            height: 100,
                    
                        });
                    }
                });
            }
        }
    });
}


function isSelectedProcedimiento(procedimientoTab){
    let valorProcedimientoTab = 'https://dev.sistemaacama.com.mx/admin/laboratorio/lote#procedimiento';
    let pestañaProcedimiento = document.getElementById(procedimientoTab);
    let btnActualizar = document.getElementById('btnRefresh');
    let annex = '';
    let evento = "(onclick='busquedaPlantilla('idLoteHeader');')";

    if(pestañaProcedimiento == valorProcedimientoTab){        
        annex+= '<button type="button" class="btn btn-primary" evento.value><i class="fas fa-sync-alt"></i></button>';
    }else{        
        annex = '';
    }

    btnActualizar.innerHTML = annex;
}

//Método que guarda el texto ingresado en el editor de texto Quill en la BD
function guardarTexto(idLote){  
    let lote = document.getElementById(idLote).value;
    let texto = document.getElementById("summernote");
    let summer = document.getElementById("divSummer");

    console.log("Antes de ajax");
    
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/"+area+"/lote/procedimiento",
        data: {            
            texto: $("#summernote").summernote('code'), 
            lote: lote,
            idArea: 5
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log("REGISTRO EXITOSO");            
            //console.log(response);
            summer.innerHTML = '<div id="summernote">'+response.texto.Texto+'</div>';
            $('#summernote').summernote({
                placeholder: '',
                tabsize: 2,
                height: 100,            
            });
        }
    });
}

//Función que guarda todos los input de la vista Lote > Modal > [Grasas, Coliformes, DBO, DQO, Metales]
$('#guardarTodo').click(function() {
    //console.log("Valor de IDLote: " + $('#idLoteHeader').val());
    
    //Calentamiento de matraces
    let calentamiento = new Array();
    
    for(let i = 0; i < 3; i++){
        row = new Array();

        row.push($("#calLote" + (i+1)).val());
        row.push($("#calMasa" + (i+1)).val());
        row.push($("#calTemp" + (i+1)).val());
        row.push($("#calEntrada" + (i+1)).val());
        row.push($("#calSalida" + (i+1)).val());
        calentamiento.push(row);
    }    

    console.log("Array calentamiento: " + calentamiento);


    //Enfriado de matraces
    let enfriado = new Array();

    for(let i = 0; i < 3; i++){
        row = new Array();
        row.push($("#enfLote" + (i+1)).val());
        row.push($("#enfMasa" + (i+1)).val());
        row.push($("#enfEntrada" + (i+1)).val());
        row.push($("#enfSalida" + (i+1)).val());
        row.push($("#enfPesado" + (i+1)).val());
        enfriado.push(row);
    }
    console.log("Array enfriado: " + enfriado);

    console.log("secadoLote: " + $("#secadoLote1").val());
    console.log("secadoTemp: " + $("#secadoTemp1").val());
    console.log("secadoEntrada: " + $("#secadoEntrada1").val());
    console.log("secadoSalida: " + $("#secadoSalida1").val());
    
    console.log("tiempoLote: " + $("#tiempoLote1").val());
    console.log("tiempoEntrada: " + $("#tiempoEntrada1").val());
    console.log("tiempoSalida: " + $("#tiempoSalida1").val());

    console.log("enfriadoLote: " + $("#enfriadoLote1").val());
    console.log("enfriadoEntrada: " + $("#enfriadoEntrada1").val());
    console.log("enfriadoSalida: " + $("#enfriadoSalida1").val());    

    //Guardado de datos
    $.ajax({
        type: "POST",
        url: base_url + "/admin/laboratorio/"+area+"/lote/guardarDatos",
        data: {
            idLote: $('#idLoteHeader').val(),

            //-----------------Grasas----------------------
            grasas_calentamiento: calentamiento,
            
            grasas_enfriado: enfriado,
            
            grasas_secadoLote: $("#secadoLote1").val(),
            grasas_secadoTemp: $("#secadoTemp1").val(),
            grasas_secadoEntrada: $("#secadoEntrada1").val(),
            grasas_secadoSalida: $("#secadoSalida1").val(),
            
            grasas_tiempoLote: $("#tiempoLote1").val(),
            grasas_tiempoEntrada: $("#tiempoEntrada1").val(),
            grasas_tiempoSalida: $("#tiempoSalida1").val(),
            
            grasas_enfriadoLote: $("#enfriadoLote1").val(),
            grasas_enfriadoEntrada: $("#enfriadoEntrada1").val(),
            grasas_enfriadoSalida: $("#enfriadoSalida1").val(),
            
            
            
            //-----------------Coliformes------------------
            sembrado_loteId: $('#sembrado_loteId').val(),
            sembrado_sembrado: $('#sembrado_sembrado').val(),
            sembrado_fechaResiembra: $('#sembrado_fechaResiembra').val(),
            sembrado_tuboN: $('#sembrado_tuboN').val(),
            sembrado_bitacora: $('#sembrado_bitacora').val(),

            pruebaPresuntiva_preparacion: $('#pruebaPresuntiva_preparacion').val(),
            pruebaPresuntiva_lectura: $('#pruebaPresuntiva_lectura').val(),           

            pruebaConfirmativa_medio: $('#pruebaConfirmativa_medio').val(),
            pruebaConfirmativa_preparacion: $('#pruebaConfirmativa_preparacion').val(),
            pruebaConfirmativa_lectura: $('#pruebaConfirmativa_lectura').val(),            
            
            
            
            //--------------------DQO---------------------
            ebullicion_loteId: $("#ebullicion_loteId").val(),
            ebullicion_inicio: $("#ebullicion_inicio").val(),
            ebullicion_fin: $("#ebullicion_fin").val(),
            ebullicion_invlab: $("#ebullicion_invlab").val(),            

            _token: $('input[name="_token"]').val()
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            //swal("Registro!", "Datos guardados correctamente!", "success");
            alert("Datos guardados correctamente")             
        }
    });
});