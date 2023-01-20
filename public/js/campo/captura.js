
// var selectedRow = false;
$(document).ready(function () {        
    $("#datosGenerales-tab").click();
    //Selec con buscador
    $('#termometro').select2();
    $('#termometro2').select2();
});
function valPhTrazable(id) {
    let l1 = parseFloat($("#phT1"+id).val())
    let l2 = parseFloat($("#phT2"+id).val())
    let l3 = parseFloat($("#phT3"+id).val())
    let ph = parseFloat($("#phTrazable"+id+" option:selected").text())
    
    let sw = false;

    // Val if rango 4 - 9
    if (l1 > 4 && l1 < 9) { 
        sw = true;
    } else if (l2 > 4 && l2 < 9) {
        sw = true;
    } else if (l1 > 4 && l1 < 9) {
        sw = true;
    } else {
        sw = false;
    }    

    // Val entre ellos
    r1 = l1 - l2;
    console.log("Valor r1"+r1)
    r2 = l1 - l3;

    r3 = l2 - l1;
    r4 = l2 - l3;

    r5 = l3 - l1;
    r6 = l3 - l2;

    if (r1 < -0.05 || r1 > 0.05) {
        sw = false;
    } else {
        sw = true;
    }

    if (r2 < -0.05 || r2 > 0.05) {
        sw = false;
    } else {
        sw = true;
    }

    if (r3 < -0.05 || r3 > 0.05) {
        sw = false;
    } else {
        sw = true;
    }

    if (r4 < -0.05 || r4 > 0.05) {
        sw = false;
    } else {
        sw = true;
    }

    if (r5 < -0.05 || r5 >= 0.05) {
        sw = false;
    } else {
        sw = true;
    }

    if (r6 < -0.05 || r6 > 0.05) {
        sw = false;
    } else {
        sw = true;
    }

    v1 = ph - l1;
    console.log("Valor de v1: " + v1);
    v2 = l1 - ph;
    console.log("Valor de v2: " + v2);
    v3 = ph - l2;
    //console.log("Valor de v3: " + v3);
    v4 = l2 - ph;
    //console.log("Valor de v4: " + v4);
    v5 = ph - l3;
    //console.log("Valor de v5: " + v5);
    v6 = l3 - ph;
    //console.log("Valor de v6: " + v6);

    if(v1 < -0.05 || v1 > 0.05){
        sw1 = false;
    }else{
        sw1 = true;
    }

    if(v2 < -0.05 || v2 > 0.05){
        sw1 = false;
    }else{
        sw1 = true;
    }

    if(v3 < -0.05 || v3 > 0.05){
        sw2 = false;
    }else{
        sw2 = true;
    }

    if(v4 < -0.05 || v4 > 0.05){
        sw2 = false;
    }else{
        sw2 = true;
    }
 
    if(v5 < -0.05 || v5 > 0.05){
        sw3 = false;
    }else{
        sw3 = true;
    }

    if(v6 < -0.05 || v6 > 0.05){
        sw3 = false;
    }else{
        sw3 = true;
    }    

    if(sw1 == true && sw2 == true && sw3 == true){
        sw = true;
    }else{
        sw = false;
    }

    
    if(l1 == ""){
        $("#phT1"+id).attr("placeholder","Lectura Vacía")
        sw = false;
    }
    
    if(l2 == ""){
        $("#phT2"+id).attr("placeholder","Lectura Vacía")
        sw = false;
    }
    
    if(l3 == ""){
        $("#phT3"+id).attr("placeholder","Lectura Vacía")
        sw = false;
    }

    if (sw == true) {
        $("#phTEstado"+id).val("Aprobado")
        $("#trTrazable"+id).attr("class","bg-success")
    } else {
        $("#phTEstado"+id).val("Rechazado")
        $("#trTrazable"+id).attr("class","bg-danger")
    }
    return sw;
}

function getPhTrazable(id,idg) {
    let tab = document.getElementById("phTrazable")
    let id1 = tab.rows[1].children[0].children[0].value
    let id2 = tab.rows[2].children[0].children[0].value
    
    if (id1 == id2) {
        alert("No puedes seleccionar el mismo ph")
    } else {
        $.ajax({
            url: base_url + "/admin/campo/captura/getPhTrazable", //archivo que recibe la peticion
            type: "POST", //método de envio
            data: {
                id:$("#"+id).val(),
                _token: $('input[name="_token"]').val(),
            },
            dataType: "json",
            async: false,
            success: function (response) {
                console.log(response);
                $("#phTNombre"+idg).text(response.model.Ph) 
                $("#phTMarca"+idg).text(response.model.Marca) 
                $("#phTLote"+idg).text(response.model.Lote) 
            },
        });
    }
}

function setDataGeneral() {
    $.ajax({
        url: base_url + "/admin/campo/captura/setDataGeneral", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            idSolicitud: $("#idSolicitud").val(),
            Captura: "Sistema",
            equipo: $("#termometro").val(),
            equipo2: $("#termometro2").val(),
            temp1: $("#tempAmbiente").val(),
            temp2: $("#tempBuffer").val(),
            latitud: $("#latitud").val(),
            longitud: $("#longitud").val(),
            altitud: $("#altitud").val(),
            pendiente: $("#pendiente").val(),
            criterio: $("#criterioPendiente").val(),
            supervisor: $("#nombreSupervisor").val(),

            phTrazable1: $("#phTrazable1").val(),
            phTl11: $("#phTl11").val(),
            phT21: $("#phT21").val(),
            phTl31: $("#phTl31").val(),
            phTEstado1: $("#phTEstado1").val(),

            phTrazable2: $("#phTrazable2").val(),
            phTl12: $("#phTl12").val(),
            phT22: $("#phT22").val(),
            phTl32: $("#phTl32").val(),
            phTEstado2: $("#phTEstado2").val(),

            phCalidad1: $("#phCalidad1").val(),
            phC11: $("#phC11").val(),
            phC21: $("#phC21").val(),
            phC31: $("#phC31").val(),
            phCEstado1: $("#phCEstado1").val(),
            phCPromedio1: $("#phCPromedio1").val(),

            phCalidad2: $("#phCalidad2").val(),
            phC12: $("#phC12").val(),
            phC22: $("#phC22").val(),
            phC23: $("#phC23").val(),
            phCEstado2: $("#phCEstado2").val(),
            phCPromedio2: $("#phCPromedio2").val(),

            conTrazable: $("#conTrazable").val(),
            conT1: $("#conT1").val(),
            conT2: $("#conT2").val(),
            conT3: $("#conT3").val(),
            conTEstado: $("#conTEstado").val(),

            conCalidad: $("#conCalidad").val(),
            conCl1: $("#conCl1").val(),
            conCl2: $("#conCl2").val(),
            conCl3: $("#conCl3").val(),
            conCEstado: $("#conCEstado").val(),
            conCPromedio: $("#conCPromedio").val(),

            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            swal("Registro!", "Registro guardado correctamente!", "success");
        },
    });
}
