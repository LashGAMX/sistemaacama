
$(document).ready(function () {

    $('#summernote').summernote({
        placeholder: '', 
        tabsize: 2,
        height: 100,

      });

    table = $('#table').DataTable({        
        "ordering": false,
        "language": {
            "lengthMenu": "# _MENU_ por pagina",
            "zeroRecords": "No hay datos encontrados",
            "info": "Pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos encontrados",
        }
    });
    $('#tipo').select2();


    
    $('#btnBuscar').click(function () {
        getLote()
    });
    $('#btnCrear').click(function () {
        createLote()
    });
    $('#btnGuardarDetalle').click(function () {
        setDetalleLote()
    });
    $('#btnBitacora').click(function () {
        setPlantillaDetalleMetales()
    });

    
    
});
document.addEventListener("keydown", function(event) {
    if (event.altKey && event.code === "KeyA")
    {
        
    }
    if (event.altKey && event.code === "KeyB"){
        getLote()
    }
    if (event.altKey && event.code === "KeyC"){
        createLote()
    }
});
function createLote()
{
    let muestra = document.getElementsByName("std")
    let ids = new Array();
    let fechas = new Array();
    let horas = new Array();

    for(let i = 0; i < muestra.length;i++){
        if (muestra[i].checked) {
            ids.push(muestra[i].value);
            fechas.push($("#fecha"+muestra[i].value).val())
            horas.push($("#hora"+muestra[i].value).val())
        }
    }
   
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/metales/createLote",
        data: {
            ids:ids,
            fechas:fechas,
            horas:horas,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
           
        }
    });
    
}
function getLote()
{
    let tabla = document.getElementById('divLotes');
    let tab = '';
    let model = new Array()
    $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/metales/getLote",
        data: {
            fecha: $("#fecha").val(),
            tipo: $("#tipo").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            model = response.model
            tab += '<table id="tablaLote" class="table table-sm">';
            tab += '    <thead class="thead-dark">';
            tab += '        <tr>';
            tab += '          <th>Seleccionar</th>';
            tab += '          <th>Cerrado</th>';
            tab += '          <th>Id lote</th>';
            tab += '          <th>Fórmula</th>';
            tab += '          <th>Tipo fórmula</th>';
            tab += '          <th>Fecha lote</th> ';
            tab += '          <th>Hora</th> ';
            tab += '        </tr>';
            tab += '    </thead>';
            tab += '    <tbody>';
            for (let i = 0; i < model.length; i++) {
                tab += '<tr ondblclick="eventLote('+model[i][0]+',\''+model[0][2]+'\')">'; 
                if (model[i][0] == "N/A") {
                    tab += '    <td><input type="checkbox" name="std"  value="'+model[i][1]+'"></td>';
                } else {
                    tab += '    <td><input type="checkbox" name="std" checked value="'+model[i][1]+'"></td>';   
                }
                tab += '    <td></td>';
                tab += '    <td>'+model[i][0]+'</td>';
                tab += '    <td>('+model[i][1]+') '+model[i][2]+'</td>';
                tab += '    <td>'+model[i][3]+'</td>';
                tab += '    <td><input type="date" id="fecha'+model[i][1]+'" value="'+model[i][4]+'"></td>';
                tab += '    <td><input type="time" id="hora'+model[i][1]+'" value="'+model[i][5]+'"></td>';
                tab += '</tr>';
            }
            tab += '    </tbody>';
            tab += '</table>';
            tabla.innerHTML = tab;
        }
    });
}
var idSol = 0;
var idLote = 0;
function eventLote(id,parametro)
{  
    $("#modalDetalle").modal("show");
    $("#idLote").val(""+id+" "+parametro)
    idLote = id
    let summer = document.getElementById("divSummer");
        $.ajax({
        type: 'POST',
        url: base_url + "/admin/laboratorio/metales/getDetalleLote",
        data: {
            id:id,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            $("#tituloBit").val(response.plantilla[0].Titulo)
            $("#revBit").val(response.plantilla[0].Rev)
            summer.innerHTML = '<div id="summernote">'+response.plantilla[0].Texto+'</div>';
            $('#summernote').summernote({
                placeholder: '', 
                tabsize: 2,
                height: 300,         
            }); 

            if (response.model.length > 0) {
                let model = response.model[0]
                // Flama
                $("#fechaDigestion").val(model.Fecha_digestion)
                $("#fechaPreparacion").val(model.Fecha_preparacion),
                $("#longitudOnda").val(model.Longitud_onda)
                $("#noInventario").val(model.No_inventario), 
                $("#corriente").val(model.Corriente)
                $("#gas").val(model.Gas)
                $("#flujoGas").val(model.Flujo_gas)
                $("#noLampara").val(model.No_lampara)
                $("#energia").val(model.Energia)
                $("#aire").val(model.Aire)
                $("#equipo").val(model.Equipo)
                $("#slit").val(model.Slit)
                $("#conStd").val(model.Conc_std)
                $("#oxidoNitroso").val(model.Oxido_nitroso)
               //Blanco de curva
               $("#verificacionBlanco").val(model.Verificacion_blanco)
               $("#absTeoricaB").val(model.Abs_teoricoB)
               $("#abs1B").val(model.Abs1B)
               $("#abs2B").val(model.Abs2B)
               $("#abs3B").val(model.Abs3B)
               $("#abs4B").val(model.Abs4B)
               $("#abs5B").val(model.Abs5B) 
               $("#promedioB").val(model.PromedioB)
               $("#conclusionB").val(model.ConclusionB)
               //Verificacion de espectro
               $("#stdCalE").val(model.Std_calE)
               $("#absTeoricaE").val(model.Abs_teoricoE)
               $("#concE").val(model.ConcE)
               $("#abs1E").val(model.Abs1E)
               $("#abs2E").val(model.Abs2E)
               $("#abs3E").val(model.Abs3E)
               $("#abs4E").val(model.Abs4E)
               $("#abs5E").val(model.Abs5E)
               $("#promedioE").val(model.PromedioE)
               $("#masaE").val(model.MasaE)
               $("#conclusionE").val(model.ConclusionE)
               $("#concObtenidaE").val(model.Conc_obtenidaE)
               $("#recE").val(model.RecuperacionE)
               $("#cumpleE").val(model.CumpleE)
               //Estandar
               $("#concI").val(model.ConcI)
               $("#desvI").val(model.DesvI)
               $("#cumpleI").val(model.CumpleI)
               $("#abs1I").val(model.Abs1I)
               $("#abs2I").val(model.Abs2I)
               $("#abs3I").val(model.Abs3I)
               $("#abs4I").val(model.Abs4I)
               $("#abs5I").val(model.Abs5I)
               //Other
               $("#bitacora").val(model.Bitacora)
               $("#folio").val(model.Folio)
               $("#valor").val(model.Valor)

            }else{
                $("#fechaDigestion").val("")
                $("#fechaPreparacion").val(""),
                $("#longitudOnda").val(response.configuracion.Longitud_onda)
                $("#noInventario").val(response.configuracion.No_Inventario)
                $("#corriente").val(response.configuracion.Lampara)
                $("#gas").val(response.configuracion.Acetileno)
                $("#flujoGas").val(response.configuracion.Flujo_gas) 
                $("#noLampara").val(response.configuracion.No_lampara)
                $("#energia").val(response.configuracion.Energia)
                $("#aire").val(response.configuracion.Aire)
                $("#equipo").val(response.configuracion.Equipo)
                $("#slit").val(response.configuracion.Slit)
                $("#conStd").val(response.configuracion.Concentracion)
                $("#oxidoNitroso").val(response.configuracion.Oxido_nitroso)
                $("#verificacionBlanco").val("")
                $("#absTeoricaB").val("")
                $("#abs1B").val("")
                $("#abs2B").val("")
                $("#abs3B").val("")
                $("#abs4B").val("")
                $("#abs5B").val("")
                $("#promedioB").val("")
                $("#conclusionB").val("")
                $("#stdCalE").val("")
                $("#absTeoricaE").val("")
                $("#concE").val("")
                $("#abs1E").val("")
                $("#abs2E").val("")
                $("#abs3E").val("")
                $("#abs4E").val("") 
                $("#abs5E").val("")
                $("#promedioE").val("")
                $("#masaE").val("")
                $("#conclusionE").val("")
                $("#concObtenidaE").val("")
                $("#recE").val("")
                $("#cumpleE").val("")
                $("#concI").val("")
                $("#desvI").val("")
                $("#cumpleI").val("")
                $("#abs1I").val("")
                $("#abs2I").val("")
                $("#abs3I").val("")
                $("#abs4I").val("")
                $("#abs5I").val("")
                $("#bitacora").val(response.configuracion.Bitacora_curva)
                $("#folio").val("")
                $("#valor").val(response.configuracion.Hidruros)
            }
        }
    });
}
function setDetalleLote(){
    $.ajax({
    type: 'POST',
    url: base_url + "/admin/laboratorio/metales/setDetalleLote",
    data: {
        id:idLote,
        fechaDigestion:$("#fechaDigestion").val(),
        fechaPreparacion:$("#fechaPreparacion").val(),
        longitudOnda:$("#longitudOnda").val(),
        noInventario:$("#noInventario").val(), 
        corriente:$("#corriente").val(),
        gas:$("#gas").val(),
        flujoGas:$("#flujoGas").val(),
        noLampara:$("#noLampara").val(),
        energia:$("#energia").val(),
        aire:$("#aire").val(),
        equipo:$("#equipo").val(),
        slit:$("#slit").val(),
        conStd:$("#conStd").val(),
        oxidoNitroso:$("#oxidoNitroso").val(),
        verificacionBlanco:$("#verificacionBlanco").val(), 
        absTeoricaB:$("#absTeoricaB").val(),
        abs1B:$("#abs1B").val(),
        abs2B:$("#abs2B").val(),
        abs3B:$("#abs3B").val(),
        abs4B:$("#abs4B").val(),
        abs5B:$("#abs5B").val(),
        promedioB:$("#promedioB").val(),
        conclusionB:$("#conclusionB").val(),
        stdCalE:$("#stdCalE").val(),
        absTeoricaE:$("#absTeoricaE").val(),
        concE:$("#concE").val(),
        abs1E:$("#abs1E").val(),
        abs2E:$("#abs2E").val(),
        abs3E:$("#abs3E").val(),
        abs4E:$("#abs4E").val(),
        abs5E:$("#abs5E").val(),
        promedioE:$("#promedioE").val(),
        masaE:$("#masaE").val(),
        conclusionE:$("#conclusionE").val(),
        concObtenidaE:$("#concObtenidaE").val(),
        recE:$("#recE").val(),
        cumpleE:$("#cumpleE").val(),
        concI:$("#concI").val(),
        desvI:$("#desvI").val(),
        cumpleI:$("#cumpleI").val(),
        abs1I:$("#abs1I").val(),
        abs2I:$("#abs2I").val(),
        abs3I:$("#abs3I").val(),
        abs4I:$("#abs4I").val(),
        abs5I:$("#abs5I").val(),
        bitacora:$("#bitacora").val(),
        folio:$("#folio").val(),
        valor:$("#valor").val(),
        _token: $('input[name="_token"]').val(),
    },
    dataType: "json",
    async: false,
    success: function (response) {            
        console.log(response);
        alert("datos guardados")
    }
});
}

function setPlantillaDetalleMetales()
{
    $.ajax({ 
        type: "POST",
        url: base_url + "/admin/laboratorio/metales/setPlantillaDetalleMetales",
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
// function createLote()
// {
//     $.ajax({
//         type: 'POST',
//         url: base_url + "/admin/laboratorio/metales/createLote",
//         data: {
//             fecha: $("#fechaLote").val(),
//             tipo: $("#tipoFormula").val(),
//             _token: $('input[name="_token"]').val(),
//         },
//         dataType: "json",
//         async: false,
//         success: function (response) {            
//             console.log(response);
//             swal("Registro!", "Lote creado correctamente!", "success");
//             $('#modalCrearLote').modal('hide')
//         }
//     });
// }

// function buscarLote()
// {
//     let tabla = document.getElementById('divTable');
//     let tab = '';
//     $.ajax({
//         type: 'POST',
//         url: base_url + "/admin/laboratorio/metales/buscarLote",
//         data: {
//             tipo: $("#tipo").val(),
//             fecha: $("#fecha").val(),
//             _token: $('input[name="_token"]').val(), 
//         },
//         dataType: "json",
//         async: false,
//         success: function (response) {            
//             console.log(response);
//             tab += '<table id="tablaLote" class="table table-sm">';
//             tab += '    <thead class="thead-dark">';
//             tab += '        <tr>';
//             tab += '          <th>#</th>';
//             tab += '          <th>Tipo formula</th>';
//             tab += '          <th>Fecha lote</th> ';
//             tab += '          <th>Fecha creacion</th> ';
//             tab += '          <th>Opc</th> ';
//             tab += '        </tr>';
//             tab += '    </thead>';
//             tab += '    <tbody>';
//             $.each(response.model, function (key, item) {
//                 tab += '<tr>';
//                 tab += '<td>'+item.Id_lote+'</td>';
//                 tab += '<td>'+item.Parametro+'</td>';
//                 tab += '<td>'+item.Fecha+'</td>';
//                 tab += '<td>'+item.created_at+'</td>';
//                 tab += '<td><button type="button" id="btnAsignar" onclick="setAsignar('+item.Id_lote+')"  class="btn btn-primary">Agregar</button></td>';
//               tab += '</tr>';
//             });
//             tab += '    </tbody>';
//             tab += '</table>';
//             tabla.innerHTML = tab;
//         }
//     });
// }

// function setAsignar(id)
// {
//     window.location = base_url + "/admin/laboratorio/metales/asgnarMuestraLote/"+id;
// }

// function getDatalote()
// {
//     let tabla = document.getElementById('divTableFormulaGlobal');
//     let tab = '';
//     let summer = document.getElementById("divSummer");
//     $.ajax({
//         type: 'POST',
//         url: base_url + "/admin/laboratorio/metales/getDatalote",
//         data: {
//             idLote:$("#idLoteHeader").val(),
//             _token: $('input[name="_token"]').val(),
//         },
//         dataType: "json", 
//         async: false,
//         success: function (response) {
            
//             console.log(response);  
            
//             //console.log("Valor de idLote: " + response[8]);
            
//             if(response[8] == 0 || response[8] < 0 || !response[8]){
//                 tab += '<table id="tableFormulasGlobales" class="table table-sm">';
//                 tab += '<thead>'
//                 tab +=      '<tr>'
//                 tab +=          '<th scope="col">Fórmula</th>'
//                 tab +=          '<th scope="col">Resultado</th>'
//                 tab +=          '<th scope="col">Núm.Decimales</th>'
//                 tab +=      '</tr>'
//                 tab += '</thead>'
//                 tab += '<tbody>'
//                 tab +=      '<tr>'
//                 //tab +=          '<td></td>'
//                 //tab +=          '<td></td>'
//                 //tab +=          '<td></td>'
//                 tab +=      '</tr>'
//                 tab += '</tbody>'
//                 tab += '</table>';
//                 tabla.innerHTML = tab;
//             }else if(response[8] > 0){
//                 tab += '<table id="tableFormulasGlobales" class="table table-sm">';
//                 tab += '    <thead class="thead-dark">';
//                 tab += '        <tr>';
//                 tab += '          <th>Fórmula</th>';
//                 tab += '          <th>Resultado</th> ';
//                 tab += '          <th>Núm. Decimales</th> ';
//                 tab += '        </tr>';
//                 tab += '    </thead>';
//                 tab += '    <tbody>';
                
//                 if(response[0] !== null){
//                     tab +=          '<tr>';
//                     tab +=              '<td>B</td>';
//                     tab +=              '<td>'+response[0].B+'</td>';
//                     tab +=              '<td>3</td>';
//                     tab +=          '</tr>';

//                     tab +=          '<tr>';
//                     tab +=              '<td>M</td>';
//                     tab +=              '<td>'+response[0].M+'</td>';
//                     tab +=              '<td>3</td>';
//                     tab +=          '</tr>';

//                     tab +=          '<tr>';
//                     tab +=              '<td>R</td>';
//                     tab +=              '<td>'+response[0].R+'</td>';
//                     tab +=              '<td>3</td>';
//                     tab +=          '</tr>';
//                 }
                
//                 /* $.each(response[0], function (key, item) {
//                     tab += '<tr>';
//                     tab +=      '<td>'+item.Constante+'</td>';
//                     tab +=      '<td>'+item.Valor+'</td>';
//                     tab +=      '<td>3</td>';                
//                     tab += '</tr>';
//                 }); */ 

//                 tab += '    </tbody>';
//                 tab += '</table>';
//                 tabla.innerHTML = tab;
//             }

//             if((response[1] !== null) && (response[2] !== null) && (response[3] !== null) && (response[4] !== null) && (response[5] !== null) && (response[6] !== null)){
//                 //Formatea la fecha a un formato admitido por el input datetime
//                 let fecha = response[1].Fecha_hora_dig;
//                 let fechaIngresada = moment(fecha, 'YYYY-MM-DDTHH:mm:ss');
//                 let fechaFormateada = moment(fechaIngresada).format('yyyy-MM-DDThh:mm');                                

//                 $("#flama_loteId").val(response[1].Id_lote);
//                 $("#flama_fechaHoraDig").val(fechaFormateada);                
//                 $("#flama_longOnda").val(response[1].Longitud_onda);
//                 $("#flama_flujoGas").val(response[1].Flujo_gas);
//                 $("#flama_equipoForm").val(response[1].Equipo);
//                 $("#flama_numInventario").val(response[1].Num_inventario);
//                 $("#flama_numInvLamp").val(response[1].Num_invent_lamp);
//                 $("#flama_slit").val(response[1].Slit);
//                 $("#flama_corriente").val(response[1].Corriente);
//                 $("#flama_energia").val(response[1].Energia);
//                 $("#flama_concStd").val(response[1].Conc_std);
//                 $("#flama_gas").val(response[1].Gas);
//                 $("#flama_aire").val(response[1].Aire);
//                 $("#flama_oxidoN").val(response[1].Oxido_nitroso);
//                 $("#flama_fechaPrep").val(response[1].Fecha_preparacion);

//                 $('#blanco_verifBlanco').val(response[2].Verif_blanco);
//                 $('#blanco_absTeoBlanco').val(response[2].ABS_teor_blanco);
//                 $('#blanco_abs1').val(response[2].ABS1);
//                 $('#blanco_abs2').val(response[2].ABS2);
//                 $('#blanco_abs3').val(response[2].ABS3);
//                 $('#blanco_abs4').val(response[2].ABS4);
//                 $('#blanco_abs5').val(response[2].ABS5);
//                 $('#blanco_absProm').val(response[2].ABS_prom);
//                 $('#blanco_concBlanco').val(response[2].Concl_blanco);

//                 $('#verif_stdCal').val(response[4].STD_cal);
//                 $('#verif_absTeorica').val(response[4].ABS_teorica);
//                 $('#verif_concMgL').val(response[4].Conc_mgL);
//                 $('#verif_Abs1').val(response[4].ABS1);
//                 $('#verif_Abs2').val(response[4].ABS2);
//                 $('#verif_Abs3').val(response[4].ABS3);
//                 $('#verif_Abs4').val(response[4].ABS4);
//                 $('#verif_Abs5').val(response[4].ABS5);
//                 $('#verif_AbsProm').val(response[4].ABS_prom);
//                 $('#verif_masaCarac').val(response[4].Masa_caract);
//                 $('#verif_conclusion').val(response[4].Conclusion);
//                 $('#verif_conclusionObtenida').val(response[4].Conc_obtenida);
//                 $('#verif_rec').val(response[4].Porc_rec);
//                 $('#verif_cumple').val(response[4].Cumple);

//                 $('#std_conc').val(response[3].Conc_mgL);
//                 $('#std_desvStd').val(response[3].DESV_std);
//                 $('#std_cumple').val(response[3].Cumple);
//                 $('#std_abs1').val(response[3].ABS1);
//                 $('#std_abs2').val(response[3].ABS2);
//                 $('#std_abs3').val(response[3].ABS3);
//                 $('#std_abs4').val(response[3].ABS4);
//                 $('#std_abs5').val(response[3].ABS5);

//                 $('#curva_bitCurvaCal').val(response[5].Bitacora_curCal),
//                 $('#curva_folioCurvaCal').val(response[5].Folio_curCal),

//                 $('#gen_genHidruros').val(response[6].Generador_hidruros)
//             }else{                
//                 $("#flama_loteId").val('');
//                 $("#flama_fechaHoraDig").val('');
//                 $("#flama_longOnda").val('');
//                 $("#flama_flujoGas").val('');
//                 $("#flama_equipoForm").val('');
//                 $("#flama_numInventario").val('');
//                 $("#flama_numInvLamp").val('');
//                 $("#flama_slit").val('');
//                 $("#flama_corriente").val('');
//                 $("#flama_energia").val('');
//                 $("#flama_concStd").val('');
//                 $("#flama_gas").val('');
//                 $("#flama_aire").val('');
//                 $("#flama_oxidoN").val('');
//                 $("#flama_fechaPrep").val('');

//                 $('#blanco_verifBlanco').val('');
//                 $('#blanco_absTeoBlanco').val('');
//                 $('#blanco_abs1').val('');
//                 $('#blanco_abs2').val('');
//                 $('#blanco_abs3').val('');
//                 $('#blanco_abs4').val('');
//                 $('#blanco_abs5').val('');
//                 $('#blanco_absProm').val('');
//                 $('#blanco_concBlanco').val('');

//                 $('#verif_stdCal').val('');
//                 $('#verif_absTeorica').val('');
//                 $('#verif_concMgL').val('');
//                 $('#verif_Abs1').val('');
//                 $('#verif_Abs2').val('');
//                 $('#verif_Abs3').val('');
//                 $('#verif_Abs4').val('');
//                 $('#verif_Abs5').val('');
//                 $('#verif_AbsProm').val('');
//                 $('#verif_masaCarac').val('');
//                 $('#verif_conclusion').val('');
//                 $('#verif_conclusionObtenida').val('');
//                 $('#verif_rec').val('');
//                 $('#verif_cumple').val('');

//                 $('#std_conc').val('');
//                 $('#std_desvStd').val('');
//                 $('#std_cumple').val('');
//                 $('#std_abs1').val('');
//                 $('#std_abs2').val('');
//                 $('#std_abs3').val('');
//                 $('#std_abs4').val('');
//                 $('#std_abs5').val('');

//                 $('#curva_bitCurvaCal').val('');
//                 $('#curva_folioCurvaCal').val('');

//                 $('#gen_genHidruros').val('');
//             }                                                    

//             console.log("actualizado");
//             if(response[7] !== null){
//                 summer.innerHTML = '<div id="summernote">'+response[7].Texto+'</div>';
//                 $('#summernote').summernote({
//                     placeholder: '',
//                     tabsize: 2,
//                     height: 100,
            
//                 });
//             }else{
//                 $.ajax({
//                     type: "POST",
//                     url: base_url + "/admin/laboratorio/metales/getDataLote/plantillaPredeterminada",
//                     data: {
//                         idLote: 0,
//                         _token: $('input[name="_token"]').val(),
//                     },
//                     dataType: "json",
//                     async: false,
//                     success: function (response) {
//                         //console.log(response);                        
//                         summer.innerHTML = '<div id="summernote">'+response.Texto+'</div>';
//                         $('#summernote').summernote({
//                             placeholder: '',
//                             tabsize: 2,
//                             height: 100,
                    
//                         });
//                     }
//                 });
//             }
//         }
//     });
// }

// /* function getDatalote()
// {
//     let tabla = document.getElementById('divTableFormulaGlobal');
//     let tab = '';
//     let summer = document.getElementById("divSummer");
//     $.ajax({
//         type: 'POST',
//         url: base_url + "/admin/laboratorio/getDatalote",
//         data: {
//             idLote:$("#idLoteHeader").val(),
//             _token: $('input[name="_token"]').val(),
//         },
//         dataType: "json",
//         async: false,
//         success: function (response) {
            
//             console.log(response);                        

//             if((response.tecLotMet) && (response.blancCurvaMet) && (response.stdVerMet) && (response.verMet) && (response.curMet) && (response.genMet)){
//                 //Formatea la fecha a un formato admitido por el input datetime
//                 let fecha = response.tecLotMet.Fecha_hora_dig;
//                 let fechaIngresada = moment(fecha, 'YYYY-MM-DDTHH:mm:ss');
//                 let fechaFormateada = moment(fechaIngresada).format('yyyy-MM-DDThh:mm');                                

//                 $("#flama_loteId").val(response.tecLotMet.Id_lote);
//                 $("#flama_fechaHoraDig").val(fechaFormateada);                
//                 $("#flama_longOnda").val(response.tecLotMet.Longitud_onda);
//                 $("#flama_flujoGas").val(response.tecLotMet.Flujo_gas);
//                 $("#flama_equipoForm").val(response.tecLotMet.Equipo);
//                 $("#flama_numInventario").val(response.tecLotMet.Num_inventario);
//                 $("#flama_numInvLamp").val(response.tecLotMet.Num_invent_lamp);
//                 $("#flama_slit").val(response.tecLotMet.Slit);
//                 $("#flama_corriente").val(response.tecLotMet.Corriente);
//                 $("#flama_energia").val(response.tecLotMet.Energia);
//                 $("#flama_concStd").val(response.tecLotMet.Conc_std);
//                 $("#flama_gas").val(response.tecLotMet.Gas);
//                 $("#flama_aire").val(response.tecLotMet.Aire);
//                 $("#flama_oxidoN").val(response.tecLotMet.Oxido_nitroso);
//                 $("#flama_fechaPrep").val(response.tecLotMet.Fecha_preparacion);

//                 $('#blanco_verifBlanco').val(response.blancCurvaMet.Verif_blanco);
//                 $('#blanco_absTeoBlanco').val(response.blancCurvaMet.ABS_teor_blanco);
//                 $('#blanco_abs1').val(response.blancCurvaMet.ABS1);
//                 $('#blanco_abs2').val(response.blancCurvaMet.ABS2);
//                 $('#blanco_abs3').val(response.blancCurvaMet.ABS3);
//                 $('#blanco_abs4').val(response.blancCurvaMet.ABS4);
//                 $('#blanco_abs5').val(response.blancCurvaMet.ABS5);
//                 $('#blanco_absProm').val(response.blancCurvaMet.ABS_prom);
//                 $('#blanco_concBlanco').val(response.blancCurvaMet.Concl_blanco);

//                 $('#verif_stdCal').val(response.verMet.STD_cal);
//                 $('#verif_absTeorica').val(response.verMet.ABS_teorica);
//                 $('#verif_concMgL').val(response.verMet.Conc_mgL);
//                 $('#verif_Abs1').val(response.verMet.ABS1);
//                 $('#verif_Abs2').val(response.verMet.ABS2);
//                 $('#verif_Abs3').val(response.verMet.ABS3);
//                 $('#verif_Abs4').val(response.verMet.ABS4);
//                 $('#verif_Abs5').val(response.verMet.ABS5);
//                 $('#verif_AbsProm').val(response.verMet.ABS_prom);
//                 $('#verif_masaCarac').val(response.verMet.Masa_caract);
//                 $('#verif_conclusion').val(response.verMet.Conclusion);
//                 $('#verif_conclusionObtenida').val(response.verMet.Conc_obtenida);
//                 $('#verif_rec').val(response.verMet.Porc_rec);
//                 $('#verif_cumple').val(response.verMet.Cumple);

//                 $('#std_conc').val(response.stdVerMet.Conc_mgL);
//                 $('#std_desvStd').val(response.stdVerMet.DESV_std);
//                 $('#std_cumple').val(response.stdVerMet.Cumple);
//                 $('#std_abs1').val(response.stdVerMet.ABS1);
//                 $('#std_abs2').val(response.stdVerMet.ABS2);
//                 $('#std_abs3').val(response.stdVerMet.ABS3);
//                 $('#std_abs4').val(response.stdVerMet.ABS4);
//                 $('#std_abs5').val(response.stdVerMet.ABS5);

//                 $('#curva_bitCurvaCal').val(response.curMet.Bitacora_curCal),
//                 $('#curva_folioCurvaCal').val(response.curMet.Folio_curCal),

//                 $('#gen_genHidruros').val(response.genMet.Generador_hidruros)
//             }else{                
//                 $("#flama_loteId").val('');
//                 $("#flama_fechaHoraDig").val('');
//                 $("#flama_longOnda").val('');
//                 $("#flama_flujoGas").val('');
//                 $("#flama_equipoForm").val('');
//                 $("#flama_numInventario").val('');
//                 $("#flama_numInvLamp").val('');
//                 $("#flama_slit").val('');
//                 $("#flama_corriente").val('');
//                 $("#flama_energia").val('');
//                 $("#flama_concStd").val('');
//                 $("#flama_gas").val('');
//                 $("#flama_aire").val('');
//                 $("#flama_oxidoN").val('');
//                 $("#flama_fechaPrep").val('');

//                 $('#blanco_verifBlanco').val('');
//                 $('#blanco_absTeoBlanco').val('');
//                 $('#blanco_abs1').val('');
//                 $('#blanco_abs2').val('');
//                 $('#blanco_abs3').val('');
//                 $('#blanco_abs4').val('');
//                 $('#blanco_abs5').val('');
//                 $('#blanco_absProm').val('');
//                 $('#blanco_concBlanco').val('');

//                 $('#verif_stdCal').val('');
//                 $('#verif_absTeorica').val('');
//                 $('#verif_concMgL').val('');
//                 $('#verif_Abs1').val('');
//                 $('#verif_Abs2').val('');
//                 $('#verif_Abs3').val('');
//                 $('#verif_Abs4').val('');
//                 $('#verif_Abs5').val('');
//                 $('#verif_AbsProm').val('');
//                 $('#verif_masaCarac').val('');
//                 $('#verif_conclusion').val('');
//                 $('#verif_conclusionObtenida').val('');
//                 $('#verif_rec').val('');
//                 $('#verif_cumple').val('');

//                 $('#std_conc').val('');
//                 $('#std_desvStd').val('');
//                 $('#std_cumple').val('');
//                 $('#std_abs1').val('');
//                 $('#std_abs2').val('');
//                 $('#std_abs3').val('');
//                 $('#std_abs4').val('');
//                 $('#std_abs5').val('');

//                 $('#curva_bitCurvaCal').val('');
//                 $('#curva_folioCurvaCal').val('');

//                 $('#gen_genHidruros').val('');
//             }

//             console.log("Valor de idLote: " + response.idLote);
//             if(response.idLote == 0 || response.idLote < 0 || !response.idLote){
//                 tab += '<table id="tableFormulasGlobales" class="table table-sm">';
//                 tab += '<thead>'
//                 tab +=      '<tr>'
//                 tab +=          '<th scope="col">Fórmula</th>'
//                 tab +=          '<th scope="col">Resultado</th>'
//                 tab +=          '<th scope="col">Núm.Decimales</th>'
//                 tab +=      '</tr>'
//                 tab += '</thead>'
//                 tab += '<tbody>'
//                 tab +=      '<tr>'
//                 //tab +=          '<td></td>'
//                 //tab +=          '<td></td>'
//                 //tab +=          '<td></td>'
//                 tab +=      '</tr>'
//                 tab += '</tbody>'
//                 tab += '</table>';
//                 tabla.innerHTML = tab;
//             }else if(response.idLote > 0){
//                 tab += '<table id="tableFormulasGlobales" class="table table-sm">';
//                 tab += '    <thead class="thead-dark">';
//                 tab += '        <tr>';
//                 tab += '          <th>Fórmula</th>';
//                 tab += '          <th>Resultado</th> ';
//                 tab += '          <th>Núm. Decimales</th> ';
//                 tab += '        </tr>';
//                 tab += '    </thead>';
//                 tab += '    <tbody>';
//                 $.each(response.constantes, function (key, item) {
//                     tab += '<tr>';
//                     tab +=      '<td>'+item.Constante+'</td>';
//                     tab +=      '<td>'+item.Valor+'</td>';
//                     tab +=      '<td>3</td>';                
//                     tab += '</tr>';
//                 }); 
//                 tab += '    </tbody>';
//                 tab += '</table>';
//                 tabla.innerHTML = tab;
//             }                
            
//             summer.innerHTML = '<div id="summernote">'+response.reporte.Texto+'</div>';
//             $('#summernote').summernote({
//                 placeholder: '',
//                 tabsize: 2,
//                 height: 100,
        
//               });
        
//         }
//     });
// } */

// function isSelectedProcedimiento(procedimientoTab){
//     let valorProcedimientoTab = 'https://dev.sistemaacama.com.mx/admin/laboratorio/lote#procedimiento';
//     let pestañaProcedimiento = document.getElementById(procedimientoTab);
//     let btnActualizar = document.getElementById('btnRefresh');
//     let annex = '';
//     let evento = "(onclick='busquedaPlantilla('idLoteHeader');')";

//     if(pestañaProcedimiento == valorProcedimientoTab){        
//         annex+= '<button type="button" class="btn btn-primary" evento.value><i class="fas fa-sync-alt"></i></button>';
//     }else{        
//         annex = '';
//     }

//     btnActualizar.innerHTML = annex;
// }

// //Método que guarda el texto ingresado en el editor de texto Quill en la BD
// function guardarTexto(idLote){  
//     let lote = document.getElementById(idLote).value;
//     let texto = document.getElementById("summernote");    
//     let summer = document.getElementById("divSummer");        

//     console.log("Antes de ajax");
    
//     $.ajax({
//         type: 'POST',
//         url: base_url + "/admin/laboratorio/metales/lote/procedimiento",
//         data: {            
//             texto: $("#summernote").summernote('code'), 
//             lote: lote,
//             idArea: 2
//         },
//         dataType: "json",
//         async: false,
//         success: function (response) {            
//             console.log("REGISTRO EXITOSO");            
//             //console.log(response);
//             summer.innerHTML = '<div id="summernote">'+response.texto.Texto+'</div>';
//             $('#summernote').summernote({
//                 placeholder: '',
//                 tabsize: 2,
//                 height: 100,            
//             });
//         }
//     });
// }

// //Función que guarda todos los input de la vista Lote > Modal > Equipo
// $('#guardarTodo').click(function() {
//     //console.log("Valor de IDLote: " + $('#idLoteHeader').val());

//     $.ajax({
//         type: "POST",
//         url: base_url + "/admin/laboratorio/metales/lote/equipo/guardarDatosGenerales",
//         data: {
//             idLote: $('#idLoteHeader').val(),
//             flama_loteId: $('#flama_loteId').val(),
//             flama_fechaHoraDig: $('#flama_fechaHoraDig').val(),
//             flama_longOnda: $('#flama_longOnda').val(),
//             flama_flujoGas: $('#flama_flujoGas').val(),
//             flama_equipoForm: $('#flama_equipoForm').val(),
//             flama_numInventario: $('#flama_numInventario').val(),
//             flama_numInvLamp: $('#flama_numInvLamp').val(),
//             flama_slit: $('#flama_slit').val(),
//             flama_corriente: $('#flama_corriente').val(),
//             flama_energia: $('#flama_energia').val(),
//             flama_concStd: $('#flama_concStd').val(),
//             flama_gas: $('#flama_gas').val(),
//             flama_aire: $('#flama_aire').val(),
//             flama_oxidoN: $('#flama_oxidoN').val(),
//             flama_fechaPrep: $('#flama_fechaPrep').val(),

//             blanco_verifBlanco: $('#blanco_verifBlanco').val(),
//             blanco_absTeoBlanco: $('#blanco_absTeoBlanco').val(),
//             blanco_abs1: $('#blanco_abs1').val(),
//             blanco_abs2: $('#blanco_abs2').val(),
//             blanco_abs3: $('#blanco_abs3').val(),
//             blanco_abs4: $('#blanco_abs4').val(),
//             blanco_abs5: $('#blanco_abs5').val(),
//             blanco_absProm: $('#blanco_absProm').val(),
//             blanco_concBlanco: $('#blanco_concBlanco').val(),

//             verif_stdCal: $('#verif_stdCal').val(),
//             verif_absTeorica: $('#verif_absTeorica').val(),
//             verif_concMgL: $('#verif_concMgL').val(),
//             verif_Abs1: $('#verif_Abs1').val(),
//             verif_Abs2: $('#verif_Abs2').val(),
//             verif_Abs3: $('#verif_Abs3').val(),
//             verif_Abs4: $('#verif_Abs4').val(),
//             verif_Abs5: $('#verif_Abs5').val(),
//             verif_AbsProm: $('#verif_AbsProm').val(),
//             verif_masaCarac: $('#verif_masaCarac').val(),
//             verif_conclusion: $('#verif_conclusion').val(),
//             verif_conclusionObtenida: $('#verif_conclusionObtenida').val(),
//             verif_rec: $('#verif_rec').val(),
//             verif_cumple: $('#verif_cumple').val(),

//             std_conc: $('#std_conc').val(),
//             std_desvStd: $('#std_desvStd').val(),
//             std_cumple: $('#std_cumple').val(),
//             std_abs1: $('#std_abs1').val(),
//             std_abs2: $('#std_abs2').val(),
//             std_abs3: $('#std_abs3').val(),
//             std_abs4: $('#std_abs4').val(),
//             std_abs5: $('#std_abs5').val(),

//             curva_bitCurvaCal: $('#curva_bitCurvaCal').val(),
//             curva_folioCurvaCal: $('#curva_folioCurvaCal').val(),

//             gen_genHidruros: $('#gen_genHidruros').val(),

//             _token: $('input[name="_token"]').val()
//         },
//         dataType: "json",
//         async: false,
//         success: function (response) {
//             console.log(response);
//             //swal("Registro!", "Datos guardados correctamente!", "success"); 
//             alert("Datos guardados correctamente")           
//         }
//     });
// });