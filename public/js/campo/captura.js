
// var selectedRow = false;
$(document).ready(function () {        
    $("#datosGenerales-tab").click();
    //Selec con buscador
    $('#termometro').select2();
    $('#termometro2').select2();
    validacionInicio()
});

function valPhMuestra(id) {
    console.log("valPhMuestra")
    let sw = false;
    let sw1 = false;
    let sw2 = false;
    let sw3 = false;
    let sw4 = false;
    let sw5 = false;
    let sw6 = false;        
    
    let l1 = parseFloat($("#phl"+id).val()); 
    let l2 = parseFloat($("#ph2"+id).val());
    let l3 = parseFloat($("#ph3"+id).val());
 
    // Val if rango 0 - 14

    if (l1 >= 0 && l1 <= 14) {
        sw = true;
    } else {
        sw = false;
    }

    if (l2 >= 0 && l2 <= 14) {
        sw = true;
    } else {
        sw = false;
    }

    if (l3 >= 0 && l3 <= 14) {
        sw = true;
    } else {
        sw = false;
    }

    // val if 0.003

    let r1 = (l1 - l2).toFixed(2); 
    let r2 = (l1 - l3).toFixed(2);
    let r3 = (l2 - l1).toFixed(2);
    let r4 = (l2 - l3).toFixed(2);
    let r5 = (l3 - l1).toFixed(2);
    let r6 = (l3 - l2).toFixed(2);
    
    //Cambio de 0.03 a 0.05 ; 0.02999999999999936
    if (r1 < -0.05 || r1 > 0.05) {
        sw1 = false;        
    } else {        
        sw1 = true;        
    }

    if (r2 < -0.05 || r2 > 0.05) {
        sw2 = false;          
    } else {        
        sw2 = true;        
    }

    if (r3 < -0.05 || r3 > 0.05) {
        sw3 = false;                    
    }else{
        sw3 = true;
    }

    if (r4 < -0.05 || r4 > 0.05) {
        sw4 = false;        
    } else {        
        sw4 = true;
    }

    if (r5 < -0.05 || r5 > 0.05) {
        sw5 = false;        
    } else {        
        sw5 = true;        
    }

    if (r6 < -0.05 || r6 > 0.05) {
        sw6 = false;        
    } else {        
        sw6 = true;
    }    

    if (sw == true) {
        if (sw1 == true && sw2 == true && sw3 == true && sw4 == true && sw5 == true && sw6 == true) {
            //Aceptado
            if(l1 == "" || l2 == "" || l3 == ""){
                $("#trPh"+id).attr('class',"bg-danger")
            }else{
                $("#trPh"+id).attr('class',"bg-success")
            }     
        } else {
            $("#trPh"+id).attr('class',"bg-danger")
            //Rechazado
            $("#trPh"+id).attr('class',"bg-danger")
        }
    } else {
        //Rechazado
        $("#trPh"+id).attr('class',"bg-danger")
    }

    if(sw == true && sw1 == true && sw2 == true && sw3 == true && sw4 == true && sw5 == true && sw6 == true){                
        
        $("#phProm"+id).val(parseFloat(((l1 + l2 + l3) / 3)).toFixed(2))
    }else{                
        if(l1 == "" && l2 == "" && l3 == ""){
            $("#phProm"+id).val("Error de lectura")
        }else{
            $("#phProm"+id).val("")
        }        
    }
    return sw;
}

let numTomas = $('#numTomas').val();
let FactorCorrecion = new Array();

function getFactorCorreccion() {
    $.ajax({
        url: base_url + "/admin/campo/captura/getFactorCorreccion", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            idFactor: $("#termmometro").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            FactorCorrecion.push(response);
        },
    }); 
}
function GuardarTempAgua() {

    let array1 = new Array();
    let array2 = new Array();
    let array3 = new Array();

    for (let i = 0; i <numTomas ; i++) {
        let temp1 = $("#temp1"+i).val();
        let temp2 = $("#temp2"+i).val();
        let temp3 = $("#temp3"+i).val();
        array1.push(temp1);
        array2.push(temp2);
        array3.push(temp3);
    }
    
    $.ajax({
        url: base_url + "/admin/campo/captura/GuardarTempAgua", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            idSolicitud:$("#idSolicitud").val(),
            array1: array1,
            array2: array2,
            array3: array3,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
        },
    }); 
}
function validacionInicio()
{
    valTempAmbiente("tempAmbiente","tempBuffer")
}
function valTempAmbiente(temperaturaAmbiente, temperaturaBuffer) {
    let temp01 = document.getElementById(temperaturaAmbiente).value;
    let temp02 = document.getElementById(temperaturaBuffer).value;
    let tempRes = temp01 - temp02;    

    if (tempRes > 5 || tempRes < -5) {
        inputBorderColor("tempAmbiente", "rojo");
        inputBorderColor("tempBuffer", "rojo");        
    } else {        
        inputBorderColor("tempAmbiente", "verde");
        inputBorderColor("tempBuffer", "verde");        
    }    
}

function validacionValPendiente(valor){    
    let v = parseFloat(document.getElementById(valor).value);

    if(v < 95 || v > 105){
        alert("Valor de la pendiente fuera de rango (95-105)");
    }
}
function valPendiente(valor, criterio) {
    let sw = true;
    let t = document.getElementById("tableCalPendiente");
    let v = parseFloat(document.getElementById(valor).value);
    let c = document.getElementById(criterio);
    let valPendiente = document.getElementById(valor);
    //let c = parseFloat(document.getElementById(criterio).value);

    if(isNaN(v)){
        sw = false;
        valPendiente.setAttribute("placeholder", "Lectura Vacía");
    }else if (v < 95 || v > 105) {
        sw = false;
    }    

    if (sw == true) {
        c.value = "Aceptado";
        t.rows[1].setAttribute("class", "bg-success");
    } else {
        c.value = "Rechazado";
        t.rows[1].setAttribute("class", "bg-danger");
    }
}
function getConCalidad(id) {
    $.ajax({
        url: base_url + "/admin/campo/captura/getConCalidad", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            idCon: $("#"+id).val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            $("#conCNombre").text(response.model.Conductividad) 
            $("#conCMarca").text(response.model.Marca) 
            $("#conCLote").text(response.model.Lote) 
        },
    }); 
}
function valConTrazable(lec1, lec2, lec3, estado) {
    let t = document.getElementById("tableConTrazable");
    let con = parseFloat($("#conTrazable option:selected").text());
    let porcentaje = (con * 5) / 100;
    let porcentaje2 = Math.ceil(parseFloat(porcentaje.toFixed(2)));

    let sw = true;
    let std = document.getElementById(estado);
    // let p = document.getElementById(prom);
    let l1 = parseFloat(document.getElementById(lec1).value);
    let l2 = parseFloat(document.getElementById(lec2).value);
    let l3 = parseFloat(document.getElementById(lec3).value);    

    if (l1 - con < porcentaje2 * -1 || l1 - con > porcentaje2) {
        sw = false;
    }
    if (l2 - con < porcentaje2 * -1 || l2 - con > porcentaje2) {
        sw = false;
    }
    if (l3 - con < porcentaje2 * -1 || l3 - con > porcentaje2) { 
        sw = false;
    }

    if($("#conTrazable option:selected").text() == "Sin seleccionar"){
        sw = false;
        alert("No se ha seleccionado una conductividad trazable");
    }

    if(isNaN(l1) || isNaN(l2) || isNaN(l3)){
        sw = false;
    }

    if (sw == true) {
        std.value = "Aceptado";
        t.rows[1].setAttribute("class", "bg-success");
    } else {
        std.value = "Rechazado";
        t.rows[1].setAttribute("class", "bg-danger");
    }
}
function valConCalidad(lec1, lec2, lec3, estado, prom) {
    let t = document.getElementById("tableConCalidad");
    let con = parseFloat($("#conCalidad option:selected").text());
    let porcentaje = (con * 5) / 100;
    let porcentaje2 = Math.ceil(parseFloat(porcentaje.toFixed(2)));

    let sw = true;
    let std = document.getElementById(estado);
    let p = document.getElementById(prom);
    let l1 = parseFloat(document.getElementById(lec1).value);
    let l2 = parseFloat(document.getElementById(lec2).value);
    let l3 = parseFloat(document.getElementById(lec3).value);

    if (l1 - con < porcentaje2 * -1 || l1 - con > porcentaje2) {
        sw = false;
    }
    if (l2 - con < porcentaje2 * -1 || l2 - con > porcentaje2) {
        sw = false;
    }
    if (l3 - con < porcentaje2 * -1 || l3 - con > porcentaje2) {
        sw = false;
    }

    if(isNaN(l1) || isNaN(l2) || isNaN(l3)){
        sw = false;
    }

    if($("#conCalidad option:selected").text() == "Sin seleccionar"){
        sw = false;
        alert("No se ha seleccionado una conductividad calidad");
    }

    if (sw == true) {
        std.value = "Aceptado";
        t.rows[1].setAttribute("class", "bg-success");
    } else {
        std.value = "Rechazado";
        t.rows[1].setAttribute("class", "bg-danger");
    }

    p.value = ((l1 + l2 + l3) / 3).toFixed();
}
function getConTrazable(id) {
    $.ajax({
        url: base_url + "/admin/campo/captura/getConTrazable", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            idCon: $("#"+id).val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            $("#conTNombre").text(response.model.Conductividad) 
            $("#conTMarca").text(response.model.Marca) 
            $("#conTLote").text(response.model.Lote) 
        },
    });
}
function valPhCalidad(id) {

    let l1 = parseFloat($("#phC1"+id).val())
    let l2 = parseFloat($("#phC2"+id).val())
    let l3 = parseFloat($("#phC3"+id).val())
    let ph = parseFloat($("#phCalidad"+id+" option:selected").text())
    let porcentaje = 0
    let temp = 0


    let sw = false;
    //nuevas variables
    let sw1;
    let sw2;
    let sw3;
    let sw4;
    let sw5;
    let sw6;
    let sw7 = true;
    let sw8 = true;
    let sw9 = true;
    
    temp = (ph * 2) / 100
    porcentaje = temp.toFixed(2)
    
    if (l1 > 4 && l1 < 9) {
        sw = true;
    } else if (l2 > 4 && l2 < 9) {
        sw = true;
    } else if (l1 > 4 && l1 < 9) {
        sw = true;
    } else {
        sw = false;
    }

    // val if 0.03

    r1 = parseFloat((l1 - l2).toFixed(2));
    r2 = parseFloat((l1 - l3).toFixed(2));

    r3 = parseFloat((l2 - l1).toFixed(2));
    r4 = parseFloat((l2 - l3).toFixed(2));

    r5 = parseFloat((l3 - l1).toFixed(2));
    r6 = parseFloat((l3 - l2).toFixed(2));

    if (r1 < -0.03 || r1 > 0.03) {
        sw1 = false;
    } else {
        sw1 = true;
    }

    if (r2 < -0.03 || r2 > 0.03) {
        sw2 = false;
    } else {
        sw2 = true;
    }

    if (r3 < -0.03 || r3 > 0.03) {
        sw3 = false;
    } else {
        sw3 = true;
    }

    if (r4 < -0.03 || r4 > 0.03) {
        sw4 = false;
    } else {
        sw4 = true;
    }

    if (r5 < -0.03 || r5 > 0.03) {
        sw5 = false;
    } else {
        sw5 = true;
    }

    if (r6 < -0.03 || r6 > 0.03) {
        sw6 = false;
    } else {
        sw6 = true;
    }

    //COMPROBACIÓN DE +/- 2%-----------------------------------------------------------------------------------------
    if (parseFloat((l1 - ph).toFixed(2)) < porcentaje * -1 || parseFloat((l1 - ph).toFixed(2)) > porcentaje) {
        sw7 = false;
    }    

    if (parseFloat((l2 - ph).toFixed(2)) < porcentaje * -1 || parseFloat((l2 - ph).toFixed(2)) > porcentaje) {
        sw8 = false;
    }    

    if (parseFloat((l3 - ph).toFixed(2)) < porcentaje * -1 || parseFloat((l3 - ph).toFixed(2)) > porcentaje) {
        sw9 = false;
    }
    //----------------------------------------------------------------------------------------------------------------    

    if(sw1 == true && sw2 == true && sw3 == true && sw4 == true && sw5 == true && sw6 == true && sw7 == true && sw8 == true && sw9 == true){
        sw = true;
    }else{
        sw = false;
    }        

   

    if(l1 == ""){
        $("#phC1"+id).attr("placeholder","Lectura Vacía")
        sw = false;
    }
    
    if(l2 == ""){
        $("#phC2"+id).attr("placeholder","Lectura Vacía")
        sw = false;
    }
    
    if(l3 == ""){
        $("#phC3"+id).attr("placeholder","Lectura Vacía")
        sw = false;
    }

    
    if (sw == true) {
        $("#phCEstado"+id).val("Aprobado")
        $("#trCalidad"+id).attr("class","bg-success")
    } else {
        $("#phCEstado"+id).val("Rechazado")
        $("#trCalidad"+id).attr("class","bg-danger")
    }

    $("#phCPromedio"+id).val(((l1 + l2 + l3) / 3).toFixed(2))
}
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

                $("#phCalidad"+idg).val(response.model2.Ph_calidad) 
                $("#phCNombre"+idg).text(response.model2.Ph_calidad) 
                $("#phCMarca"+idg).text(response.model2.Marca) 
                $("#phCLote"+idg).text(response.model2.Lote) 
            },
        });
    }
}


function setDataGeneral() {

    let tabPhT = document.getElementById("phTrazable")
    let tabPhC = document.getElementById("phControlCalidad")
    let tabConT = document.getElementById("tableConTrazable")
    let tabConC = document.getElementById("tableConCalidad")

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
            firmaSupervisor: $("#firmaSupervisor").val(),

            phTrazable1: tabPhT.rows[1].children[0].children[0].value,
            phTl11: tabPhT.rows[1].children[4].children[0].value,
            phT21: tabPhT.rows[1].children[5].children[0].value,
            phTl31: tabPhT.rows[1].children[6].children[0].value,
            phTEstado1: tabPhT.rows[1].children[7].children[0].value,

            phTrazable2:tabPhT.rows[2].children[0].children[0].value,
            phTl12: tabPhT.rows[2].children[4].children[0].value,
            phT22:tabPhT.rows[2].children[5].children[0].value,
            phTl32: tabPhT.rows[2].children[6].children[0].value,
            phTEstado2:tabPhT.rows[2].children[7].children[0].value,

            phCalidad1:tabPhC.rows[1].children[0].children[0].value,
            phC11:tabPhC.rows[1].children[4].children[0].value,
            phC21:tabPhC.rows[1].children[5].children[0].value,
            phC31:tabPhC.rows[1].children[6].children[0].value,
            phCEstado1:tabPhC.rows[1].children[7].children[0].value,
            phCPromedio1:tabPhC.rows[1].children[8].children[0].value,

            phCalidad2:tabPhC.rows[2].children[0].children[0].value,
            phC12:tabPhC.rows[2].children[4].children[0].value,
            phC22:tabPhC.rows[2].children[5].children[0].value,
            phC23:tabPhC.rows[2].children[6].children[0].value,
            phCEstado2:tabPhC.rows[2].children[7].children[0].value,
            phCPromedio2:tabPhC.rows[2].children[8].children[0].value,

            conTrazable:tabConT.rows[1].children[0].children[0].value,
            conT1:tabConT.rows[1].children[4].children[0].value,
            conT2:tabConT.rows[1].children[5].children[0].value,
            conT3:tabConT.rows[1].children[6].children[0].value,
            conTEstado:tabConT.rows[1].children[7].children[0].value,

            conCalidad:tabConC.rows[1].children[0].children[0].value,
            conCl1:tabConC.rows[1].children[4].children[0].value,
            conCl2:tabConC.rows[1].children[5].children[0].value,
            conCl3:tabConC.rows[1].children[6].children[0].value,
            conCEstado:tabConC.rows[1].children[7].children[0].value,
            conCPromedio:tabConC.rows[1].children[8].children[0].value,

            
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
function inputBorderColor(id, color) {
    let cont = document.getElementById(id);

    switch (color) {        
        case "rojo":
            cont.setAttribute("style", "border-color:#dc3545");
            break;
        case "verde":
            cont.setAttribute("style", "border-color:#28a745");
            break;
        case "original":
            cont.setAttribute("style", "border-color:#ccc");
            break;
        default:
            break;
    }
}
