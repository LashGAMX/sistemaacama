
// var selectedRow = false;
$(document).ready(function () {        
    $("#datosGenerales-tab").click();
    datosGenerales();
    datosMuestreo();

    //Llamada a función añadida
    valoresPhTrazables();    
  
});


function datosGenerales() {            
    table = $("#materialUsado").DataTable({
        ordering: false,
        language: {
            lengthMenu: "# _MENU_ por pagina",
            zeroRecords: "No hay datos encontrados",
            info: "Pagina _PAGE_ de _PAGES_",
            infoEmpty: "No hay datos encontrados",
        },
        scrollY: "15vh",
        scrollCollapse: true,
        paging: false,
    });

    $("#termometro").click(function () {
        getFactorCorreccion();
    });

    //phTrazable1
    $("#phTrazable1").click(function () {                             
        if($("#phTrazable1").val() == "0"){
            setPhTrazable2(
                $("#phTrazable1").val(),
                "phTNombre1",
                "phTMarca1",
                "phTLote1"
            );    
        }else{            
            setPhTrazable(
                $("#phTrazable1").val(),
                "phTNombre1",
                "phTMarca1",
                "phTLote1",
            );
        }                
        
        $("#phCalidad1").val($("#phTrazable1").val()).attr("disabled", "disabled");
            //idPh, nombre, marca, lote
            setPhCalidad($("#phCalidad1").val(), "phCNombre1", "phCMarca1", "phCLote1");

            if ($("#phTrazable1").val() != "0" && $("#phTrazable2").val() != "0" && $("#phTrazable1").val() == $("#phTrazable2").val()) {                
                inputBorderColor("phTrazable1", "rojo");
                inputBorderColor("phTrazable2", "rojo");
            } else if ($("#phTrazable1").val() == "0" || $("#phTrazable2").val() == "0") {                        
                if($("#phTrazable1").val() == "0"){
                    inputBorderColor("phTrazable1", "original");
                    setPhCalidad2($("#phCalidad1").val(), "phCNombre1", "phCMarca1", "phCLote1");
                }                
                //inputBorderColor("phTrazable2", "original");                                       
            } else {
                inputBorderColor("phTrazable2", "verde");
                inputBorderColor("phTrazable1", "verde");
            }

            //valPhTrazable($("#phTl11").val(), $("#phT21").val(), $("#phTl31").val(), $("#phTEstado1").val(), $("#phTrazable1"));
    });

    //phTrazable2
    $("#phTrazable2").click(function () {

        if($("#phTrazable2").val() == "0"){
            setPhTrazable2(
                $("#phTrazable2").val(),
                "phTNombre2",
                "phTMarca2",
                "phTLote2"
            );    
        }else{
            setPhTrazable2(
                $("#phTrazable2").val(),
                "phTNombre2",
                "phTMarca2",
                "phTLote2"
            );
        }        

        $("#phCalidad2").val($("#phTrazable2").val()).attr("disabled", "disabled");
        setPhCalidad2($("#phCalidad2").val(), "phCNombre2", "phCMarca2", "phCLote2");

        if ($("#phTrazable1").val() != "0" && $("#phTrazable2").val() != "0" && $("#phTrazable1").val() == $("#phTrazable2").val()) {
            //alert("Los valores de Ph trazable no pueden ser los mismos");
            inputBorderColor("phTrazable1", "rojo");
            inputBorderColor("phTrazable2", "rojo");
        } else if ($("#phTrazable1").val() == "0" || $("#phTrazable2").val() == "0") {                        
            if($("#phTrazable2").val() == "0"){
                inputBorderColor("phTrazable2", "original");
                setPhCalidad2($("#phCalidad2").val(), "phCNombre2", "phCMarca2", "phCLote2");
            }
            
            //inputBorderColor("phTrazable1", "original");
                                   
        } else {
            inputBorderColor("phTrazable2", "verde");
            inputBorderColor("phTrazable1", "verde");
        }
    });

    //phCalidad1
    $("#phCalidad1").click(function () {
        setPhCalidad(
            $("#phCalidad1").val(),
            "phCNombre1",
            "phCMarca1",
            "phCLote1"
        );
        if (
            $("#phCalidad1").val() == $("#phTrazable1").val() ||
            $("#phCalidad1").val() == $("#phTrazable2").val()
        ) {
            inputBorderColor("phCalidad1", "verde");
        } else {
            inputBorderColor("phCalidad1", "rojo");
        }
    });

    //phCalidad2
    $("#phCalidad2").click(function () {
        setPhCalidad2(
            $("#phCalidad2").val(),
            "phCNombre2",
            "phCMarca2",
            "phCLote2"
        );

        if (
            $("#phCalidad2").val() == $("#phTrazable1").val() ||
            $("#phCalidad2").val() == $("#phTrazable2").val()
        ) {
            if ($("#phCalidad1").val() == $("#phCalidad2").val()) {
               // alert("Los valores de Ph calidad no pueden ser los mismos");
                inputBorderColor("phCalidad2", "rojo");
            } else {
                inputBorderColor("phCalidad2", "verde");
                inputBorderColor("phCalidad1", "verde");
            }
        } else {
            inputBorderColor("phCalidad2", "rojo");
        }
    });

    //Conductividad trazable
    $("#conTrazable").click(function () {
        setConTrazable(
            $("#conTrazable").val(),
            "conNombre",
            "conMarca",
            "conLote"
        );
    });

    //Conductividad control calidad
    $("#conCalidad").click(function () {
        setConCalidad(
            $("#conCalidad").val(),
            "conCNombre",
            "conCMarca",
            "conCLote"
        );
    });    
}

function datosMuestreo() {}

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

let semaforoA = false;
let semaforoB = false;

function diferenciaTemperaturas(temperaturaA, temperaturaB, entrada){
    let temp01 = document.getElementById(temperaturaA).value;
    let temp02 = document.getElementById(temperaturaB).value;
    let tempRes = temp01 - temp02;

    if(entrada == "tempAmbiente"){
        if($('#tempAmbiente').val().length === 0){
            inputBorderColor("tempAmbiente", "rojo");
            alert("Temperatura ambiente vacía");
            
            if($('#tempBuffer').val().length === 0){
                inputBorderColor("tempAmbiente", "rojo");
                inputBorderColor("tempBuffer", "rojo");
            }else{
                inputBorderColor("tempAmbiente", "rojo");
                inputBorderColor("tempBuffer", "original");
            }            
        }else{                                
            if($('#tempBuffer').val().length !== 0 && (tempRes > 5 || tempRes < -5)){
                if(semaforoB === false){
                    alert("Diferencia de T.Ambiente y T.Búffer mayor a 5 grados");
                    semaforoA = false;
                    semaforoB = true;
                }
            }            
        }
    }else if(entrada == "tempBuffer"){
        if($('#tempBuffer').val().length === 0){            
            alert("Temperatura búffer vacía");
            
            if($('#tempAmbiente').val().length === 0){
                inputBorderColor("tempAmbiente", "rojo");
                inputBorderColor("tempBuffer", "rojo");
            }else{
                inputBorderColor("tempAmbiente", "original");
                inputBorderColor("tempBuffer", "rojo");
            }
        }else{            
            if($('#tempAmbiente').val().length !== 0 && (tempRes > 5 || tempRes < -5)){                
                if(semaforoA === false){
                    alert("Diferencia T.Ambiente y T.Búffer mayor a 5 grados");
                    semaforoB = false;
                    semaforoA = true;
                }
            }
        }
    }
}

let prueba;

function setPhTrazable(phTrazable){
    prueba = document.getElementById(phTrazable);
    console.log("Entraste a SET");
}

function getPhTrazable(){
    return prueba;
}

function valPhTrazable(lec1, lec2, lec3, estado, phTrazable) {
    //console.log("Valor lec1 al inicio del método: " + document.getElementById(lec1).value);
    //console.log("Valor lec2 al inicio del método: " + document.getElementById(lec2).value);
    //console.log("Valor lec3 al inicio del método: " + document.getElementById(lec3).value);

    let select = document.getElementById(phTrazable);
    let text = select.options[select.selectedIndex].innerText;
    text = parseFloat(text);
    console.log("Valor de text: " + text);
        
    let sw = false;
    let sw1 = true;
    let sw2 = true;
    let sw3 = true;
    let std = document.getElementById(estado);
    let inLec1 = document.getElementById(lec1);
    let inLec2 = document.getElementById(lec2);
    let inLec3 = document.getElementById(lec3);
    let l1 = parseFloat(document.getElementById(lec1).value);
    let l2 = parseFloat(document.getElementById(lec2).value);
    let l3 = parseFloat(document.getElementById(lec3).value);
    let t = document.getElementById("phTrazable");

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

    r1 = l1 - l2;
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

    /*if(sw == true){
        console.log("Aceptado")
    }else{
        console.log("Rechazado")
    }*/

    v1 = text - l1;
    //console.log("Valor de v1: " + v1);
    v2 = l1 - text;
    //console.log("Valor de v2: " + v2);
    v3 = text - l2;
    //console.log("Valor de v3: " + v3);
    v4 = l2 - text;
    //console.log("Valor de v4: " + v4);
    v5 = text - l3;
    //console.log("Valor de v5: " + v5);
    v6 = l3 - text;
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

    if(isNaN(l1)){
        inLec1.setAttribute("placeholder", "Lectura Vacía");
        sw = false;
    }
    
    if(isNaN(l2)){
        inLec2.setAttribute("placeholder", "Lectura Vacía");
        sw = false;
    }
    
    if(isNaN(l3)){
        inLec3.setAttribute("placeholder", "Lectura Vacía");
        sw = false;
    }

    if(isNaN(text)){
        sw = false;
        alert("No se ha seleccionado un PH Trazable");
    }        

    if (sw == true) {
        std.value = "Aprobado";
        if (lec1 == "phTl11") {
            t.rows[1].setAttribute("class", "bg-success");
        } else {
            t.rows[2].setAttribute("class", "bg-success");
        }
    } else {
        std.value = "Rechazado";
        if (lec1 == "phTl11") {
            t.rows[1].setAttribute("class", "bg-danger");
        } else {
            t.rows[2].setAttribute("class", "bg-danger");
        }
        // if()
    }

    return sw;
}

function validacionPhTrazable1(lec1, lec2, lec3, activador, phTrazable){    
    let l1 = parseFloat(document.getElementById(lec1).value);
    let l2 = parseFloat(document.getElementById(lec2).value);
    let l3 = parseFloat(document.getElementById(lec3).value);    
    var select = document.getElementById(phTrazable);      
    var text = select.options[select.selectedIndex].innerText;
    text = parseFloat(text);
    //console.log("Valor de phT: " + text);

    if(activador == "phTl11"){       
        v1 = parseFloat((text - l1).toFixed(2));        
        v2 = parseFloat((l1 - text).toFixed(2));

        if((v1 < -0.05 || v1 > 0.05) || (v2 < -0.05 || v2 > 0.05)){
            alert("Diferencia de (+/-) 0.05 de lectura 1 contra el valor de PH Trazable");
        }
                
    }else if(activador == "phT21"){                
        v3 = parseFloat((text - l2).toFixed(2));        
        v4 = parseFloat((l2 - text).toFixed(2));

        if((v3 < -0.05 || v3 > 0.05) || (v4 < -0.05 || v4 > 0.05)){
            alert("Diferencia de (+/-) 0.05 de lectura 2 contra el valor de PH Trazable");
        }

    }else if(activador == "phTl31"){        
        v5 = parseFloat((text - l3).toFixed(2));
        v6 = parseFloat((l3 - text).toFixed(2));

        if((v5 < -0.05 || v5 > 0.05) || (v6 < -0.05 || v6 > 0.05)){
            alert("Diferencia de (+/-) 0.05 de lectura 3 contra el valor de PH Trazable");
        }
    }
}

function valPhTrazable2(lec1, lec2, lec3, estado, phTrazable) {
    var select = document.getElementById(phTrazable);      
    var text = select.options[select.selectedIndex].innerText;
    text = parseFloat(text);
    console.log("Valor de phT: " + text);
        
    let sw = false;
    let sw1 = true;
    let sw2 = true;
    let sw3 = true;
    let std = document.getElementById(estado);
    let l1 = parseFloat(document.getElementById(lec1).value);
    let l2 = parseFloat(document.getElementById(lec2).value);
    let l3 = parseFloat(document.getElementById(lec3).value);
    let inLec1 = document.getElementById(lec1);
    let inLec2 = document.getElementById(lec2);
    let inLec3 = document.getElementById(lec3);
    let t = document.getElementById("phTrazable");        

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

    // val if 0.003

    r1 = l1 - l2;
    r2 = l1 - l3;

    r3 = l2 - l1;
    r4 = l2 - l3;

    r5 = l3 - l1;
    r6 = l3 - l2;

    if (r1 <= -0.05 || r1 >= 0.05) {
        sw = false;
    } else {
        sw = true;
    }

    if (r2 <= -0.05 || r2 >= 0.05) {
        sw = false;
    } else {
        sw = true;
    }

    if (r3 <= -0.05 || r3 >= 0.05) {
        sw = false;
    } else {
        sw = true;
    }

    if (r4 <= -0.05 || r4 >= 0.05) {
        sw = false;
    } else {
        sw = true;
    }
    if (r5 <= -0.05 || r5 >= 0.05) {
        sw = false;
    } else {
        sw = true;
    }

    if (r6 <= -0.05 || r6 >= 0.05) {
        sw = false;
    } else {
        sw = true;
    }    

    v1 = text - l1;
    //console.log("Valor de v1: " + v1);
    v2 = l1 - text;
    //console.log("Valor de v2: " + v2);
    v3 = text - l2;
    //console.log("Valor de v3: " + v3);
    v4 = l2 - text;
    //console.log("Valor de v4: " + v4);
    v5 = text - l3;
    //console.log("Valor de v5: " + v5);
    v6 = l3 - text;
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

    if(isNaN(l1)){
        inLec1.setAttribute("placeholder", "Lectura Vacía");
        sw = false;
    }
    
    if(isNaN(l2)){
        inLec2.setAttribute("placeholder", "Lectura Vacía");
        sw = false;
    }
    
    if(isNaN(l3)){
        inLec3.setAttribute("placeholder", "Lectura Vacía");
        sw = false;
    }
    
    if(isNaN(text)){
        sw = false;
        alert("No se ha seleccionado un PH Trazable");
    }    

    if (sw == true) {
        std.value = "Aprobado";
        if (lec1 == "phTl11") {
            t.rows[1].setAttribute("class", "bg-success");
        } else {
            t.rows[2].setAttribute("class", "bg-success");
        }
    } else {
        std.value = "Rechazado";
        if (lec1 == "phTl11") {
            t.rows[1].setAttribute("class", "bg-danger");
        } else {
            t.rows[2].setAttribute("class", "bg-danger");
        }        
    }
    return sw;
}

function validacionPhTrazable2(lec1, lec2, lec3, activador, phTrazable){    
    let l1 = parseFloat(document.getElementById(lec1).value);
    let l2 = parseFloat(document.getElementById(lec2).value);
    let l3 = parseFloat(document.getElementById(lec3).value);     
    var select = document.getElementById(phTrazable);      
    var text = select.options[select.selectedIndex].innerText;
    text = parseFloat(text);
    //console.log("Valor de phT: " + text);

    if(activador == "phTl12"){                
        v1 = parseFloat((text - l1).toFixed(2));        
        v2 = parseFloat((l1 - text).toFixed(2));

        if((v1 < -0.05 || v1 > 0.05) || (v2 < -0.05 || v2 > 0.05)){
            alert("Diferencia de (+/-) 0.05 unidades de lectura 1 contra el valor de PH Trazable");
        }
                
    }else if(activador == "phTl22"){        
        v3 = parseFloat((text - l2).toFixed(2));        
        v4 = parseFloat((l2 - text).toFixed(2));

        if((v3 < -0.05 || v3 > 0.05) || (v4 < -0.05 || v4 > 0.05)){
            alert("Diferencia de (+/-) 0.05 unidades de lectura 2 contra el valor de PH Trazable");
        }

    }else if(activador == "phTl32"){
        v5 = parseFloat((text - l3).toFixed(2));
        v6 = parseFloat((l3 - text).toFixed(2));

        if((v5 < -0.05 || v5 > 0.05) || (v6 < -0.05 || v6 > 0.05)){
            alert("Diferencia de (+/-) 0.05 unidades de lectura 3 contra el valor de PH Trazable");
        }
    }
}

function valPhCalidad(lec1, lec2, lec3, estado, prom, phCalidad) {
    let sw = false;
    let p = document.getElementById(prom);
    let std = document.getElementById(estado);
    let inLec1 = document.getElementById(lec1);
    let inLec2 = document.getElementById(lec2);
    let inLec3 = document.getElementById(lec3);
    let l1 = parseFloat(document.getElementById(lec1).value);
    let l2 = parseFloat(document.getElementById(lec2).value);
    let l3 = parseFloat(document.getElementById(lec3).value);
    let t = document.getElementById("phControlCalidad");
    var select = document.getElementById(phCalidad);      
    var text = select.options[select.selectedIndex].innerText;
    text = parseFloat(text);

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
    let cal;
    
    if(lec1 == "phC11"){
        cal = parseFloat($("#phCalidad1 option:selected").text());
    }else{
        cal = parseFloat($("#phCalidad2 option:selected").text());
    }

    let porcentaje = (cal * 2) / 100;
    let porcentaje2 = parseFloat(porcentaje.toFixed(2));    
    
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
    if (parseFloat((l1 - cal).toFixed(2)) < porcentaje2 * -1 || parseFloat((l1 - cal).toFixed(2)) > porcentaje2) {
        sw7 = false;
    }    

    if (parseFloat((l2 - cal).toFixed(2)) < porcentaje2 * -1 || parseFloat((l2 - cal).toFixed(2)) > porcentaje2) {
        sw8 = false;
    }    

    if (parseFloat((l3 - cal).toFixed(2)) < porcentaje2 * -1 || parseFloat((l3 - cal).toFixed(2)) > porcentaje2) {
        sw9 = false;
    }
    //----------------------------------------------------------------------------------------------------------------    

    if(sw1 == true && sw2 == true && sw3 == true && sw4 == true && sw5 == true && sw6 == true && sw7 == true && sw8 == true && sw9 == true){
        sw = true;
    }else{
        sw = false;
    }        

    if(isNaN(l1)){
        inLec1.setAttribute("placeholder", "Lectura Vacía");
        sw = false;
    }
    
    if(isNaN(l2)){
        inLec2.setAttribute("placeholder", "Lectura Vacía");
        sw = false;
    }
    
    if(isNaN(l3)){
        inLec3.setAttribute("placeholder", "Lectura Vacía");
        sw = false;
    }

    if(isNaN(text)){
        sw = false;
        alert("No se ha seleccionado un PH Calidad");
    }

    if (sw == true) {
        std.value = "Aprobado";
        if (lec1 == "phC11") {
            t.rows[1].setAttribute("class", "bg-success");
        } else {
            t.rows[2].setAttribute("class", "bg-success");
        }
    } else {
        std.value = "Rechazado";
        if (lec1 == "phC11") {
            t.rows[1].setAttribute("class", "bg-danger");
        } else {
            t.rows[2].setAttribute("class", "bg-danger");
        }
    }
    p.value = ((l1 + l2 + l3) / 3).toFixed(2);
}

function validacionPhCalidad(lec1, lec2, lec3, activador, phControlCalidad){    
    let l1 = parseFloat(document.getElementById(lec1).value);        
    let l2 = parseFloat(document.getElementById(lec2).value);
    let l3 = parseFloat(document.getElementById(lec3).value);    
    var select = document.getElementById(phControlCalidad);
    var text = select.options[select.selectedIndex].innerText;    
    let cal = parseFloat($("#phCalidad1 option:selected").text());
    let porcentaje = (cal * 2) / 100;
    let porcentaje2 = parseFloat(porcentaje.toFixed(2));  
    
    let sw1;
    let sw2;
    let sw3;    

    text = parseFloat(text);
    //console.log("Valor de phT: " + text);

    r1 = parseFloat((l1 - l2).toFixed(2));
    r2 = parseFloat((l1 - l3).toFixed(2));

    r3 = parseFloat((l2 - l1).toFixed(2));
    r4 = parseFloat((l2 - l3).toFixed(2));

    r5 = parseFloat((l3 - l1).toFixed(2));
    r6 = parseFloat((l3 - l2).toFixed(2));    

    if((r1 < -0.03 || r1 > 0.03) || (r3 < -0.03 || r3 > 0.03)){
        sw1 = true;
    }else{
        sw1 = false;
    }

    if((r2 < -0.03 || r2 > 0.03) || (r5 < -0.03 || r5 > 0.03)){
        sw2 = true;
    }else{
        sw2 = false;
    }

    if((r4 < -0.03 || r4 > 0.03) || (r6 < -0.03 || r6 > 0.03)){
        sw3 = true;
    }else{
        sw3 = false;
    }

    //VERIFICAR BLOQUEO DE ALERTS
    if(sw1 == true || sw2 == true || sw3 == true){
        alert("Diferencia (+/-) 0.03 unidades entre las lecturas");
    }

    if(activador == "phC11"){
        if (parseFloat((l1 - cal).toFixed(2)) < porcentaje2 * -1 || parseFloat((l1 - cal).toFixed(2)) > porcentaje2) {
            alert("Diferencia (+/-) 2% de lectura 1 contra el valor de PH Calidad");
        }
    }else if(activador == "phC21"){
        if (parseFloat((l2 - cal).toFixed(2)) < porcentaje2 * -1 || parseFloat((l2 - cal).toFixed(2)) > porcentaje2) {
            alert("Diferencia (+/-) 2% de lectura 2 contra el valor de PH Calidad");
        }
    }else if(activador == "phC31"){
        if (parseFloat((l3 - cal).toFixed(2)) < porcentaje2 * -1 || parseFloat((l3 - cal).toFixed(2)) > porcentaje2) {
            alert("Diferencia (+/-) 2% de lectura 3 contra el valor de PH Calidad");
        }        
    }
}

function valPhCalidad2(lec1, lec2, lec3, estado, prom, phCalidad) {
    let sw = false;
    let p = document.getElementById(prom);
    let std = document.getElementById(estado);
    let inLec1 = document.getElementById(lec1);
    let inLec2 = document.getElementById(lec2);
    let inLec3 = document.getElementById(lec3);
    let l1 = parseFloat(document.getElementById(lec1).value);
    let l2 = parseFloat(document.getElementById(lec2).value);
    let l3 = parseFloat(document.getElementById(lec3).value);
    let t = document.getElementById("phControlCalidad");
    var select = document.getElementById(phCalidad);      
    var text = select.options[select.selectedIndex].innerText;
    text = parseFloat(text);

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
    let cal;
    
    if(lec1 == "phC11"){
        cal = parseFloat($("#phCalidad1 option:selected").text());
    }else{
        cal = parseFloat($("#phCalidad2 option:selected").text());
    }

    let porcentaje = (cal * 2) / 100;
    let porcentaje2 = parseFloat(porcentaje.toFixed(2));    
    
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
    if (parseFloat((l1 - cal).toFixed(2)) < porcentaje2 * -1 || parseFloat((l1 - cal).toFixed(2)) > porcentaje2) {
        sw7 = false;
    }    

    if (parseFloat((l2 - cal).toFixed(2)) < porcentaje2 * -1 || parseFloat((l2 - cal).toFixed(2)) > porcentaje2) {
        sw8 = false;
    }    

    if (parseFloat((l3 - cal).toFixed(2)) < porcentaje2 * -1 || parseFloat((l3 - cal).toFixed(2)) > porcentaje2) {
        sw9 = false;
    }
    //----------------------------------------------------------------------------------------------------------------    

    if(sw1 == true && sw2 == true && sw3 == true && sw4 == true && sw5 == true && sw6 == true && sw7 == true && sw8 == true && sw9 == true){
        sw = true;
    }else{
        sw = false;
    }        

    if(isNaN(l1)){
        inLec1.setAttribute("placeholder", "Lectura Vacía");
        sw = false;
    }
    
    if(isNaN(l2)){
        inLec2.setAttribute("placeholder", "Lectura Vacía");
        sw = false;
    }
    
    if(isNaN(l3)){
        inLec3.setAttribute("placeholder", "Lectura Vacía");
        sw = false;
    }

    if(isNaN(text)){
        sw = false;
        alert("No se ha seleccionado un PH Calidad");
    }

    if (sw == true) {
        std.value = "Aprobado";
        if (lec1 == "phC12") {
            t.rows[2].setAttribute("class", "bg-success");
        }
    } else {
        std.value = "Rechazado";
        if (lec1 == "phC12") {
            t.rows[2].setAttribute("class", "bg-danger");
        }
    }
    p.value = ((l1 + l2 + l3) / 3).toFixed(2);
}

function validacionPhCalidad2(lec1, lec2, lec3, activador, phControlCalidad){    
    let l1 = parseFloat(document.getElementById(lec1).value);        
    let l2 = parseFloat(document.getElementById(lec2).value);
    let l3 = parseFloat(document.getElementById(lec3).value);    
    var select = document.getElementById(phControlCalidad);
    var text = select.options[select.selectedIndex].innerText;    
    let cal = parseFloat($("#phCalidad2 option:selected").text());
    let porcentaje = (cal * 2) / 100;
    let porcentaje2 = parseFloat(porcentaje.toFixed(2));  
    
    let sw1;
    let sw2;
    let sw3;    

    text = parseFloat(text);
    //console.log("Valor de phT: " + text);

    r1 = parseFloat((l1 - l2).toFixed(2));
    r2 = parseFloat((l1 - l3).toFixed(2));

    r3 = parseFloat((l2 - l1).toFixed(2));
    r4 = parseFloat((l2 - l3).toFixed(2));

    r5 = parseFloat((l3 - l1).toFixed(2));
    r6 = parseFloat((l3 - l2).toFixed(2));    

    if((r1 < -0.03 || r1 > 0.03) || (r3 < -0.03 || r3 > 0.03)){
        sw1 = true;
    }else{
        sw1 = false;
    }

    if((r2 < -0.03 || r2 > 0.03) || (r5 < -0.03 || r5 > 0.03)){
        sw2 = true;
    }else{
        sw2 = false;
    }

    if((r4 < -0.03 || r4 > 0.03) || (r6 < -0.03 || r6 > 0.03)){
        sw3 = true;
    }else{
        sw3 = false;
    }

    //VERIFICAR BLOQUEO DE ALERTS
    if(sw1 == true || sw2 == true || sw3 == true){
        alert("Diferencia (+/-) 0.03 unidades entre las lecturas");
    }

    if(activador == "phC12"){
        if (parseFloat((l1 - cal).toFixed(2)) < porcentaje2 * -1 || parseFloat((l1 - cal).toFixed(2)) > porcentaje2) {
            alert("Diferencia (+/-) 2% de lectura 1 contra el valor de PH Calidad");
        }
    }else if(activador == "phC22"){
        if (parseFloat((l2 - cal).toFixed(2)) < porcentaje2 * -1 || parseFloat((l2 - cal).toFixed(2)) > porcentaje2) {
            alert("Diferencia (+/-) 2% de lectura 2 contra el valor de PH Calidad");
        }
    }else if(activador == "phC23"){
        if (parseFloat((l3 - cal).toFixed(2)) < porcentaje2 * -1 || parseFloat((l3 - cal).toFixed(2)) > porcentaje2) {
            alert("Diferencia (+/-) 2% de lectura 3 contra el valor de PH Calidad");
        }        
    }
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

function validacionConTrazable(lec1, lec2, lec3, activador){
    let inLec1 = document.getElementById(lec1);
    let inLec2 = document.getElementById(lec2);
    let inLec3 = document.getElementById(lec3);    
    let l1 = parseFloat(document.getElementById(lec1).value);
    let l2 = parseFloat(document.getElementById(lec2).value);
    let l3 = parseFloat(document.getElementById(lec3).value);
    let t = document.getElementById("tableConTrazable");

    let con = parseFloat($("#conTrazable option:selected").text());
    let porcentaje = (con * 5) / 100;
    let porcentaje2 = Math.ceil(parseFloat(porcentaje.toFixed(2)));    

    if(activador == "conT1"){
        if(isNaN(l1)){
            t.rows[1].setAttribute("class", "bg-danger");
            inLec1.setAttribute("placeholder", "Lectura Vacía");
        }

        if (l1 - con < porcentaje2 * -1 || l1 - con > porcentaje2) {
            alert("Diferencia (+/-) 5% de lectura 1 contra el valor de conductividad");
        }
    }else if(activador == "conT2"){
        if(isNaN(l2)){
            t.rows[1].setAttribute("class", "bg-danger");
            inLec2.setAttribute("placeholder", "Lectura Vacía");
        }
        
        if (l2 - con < porcentaje2 * -1 || l2 - con > porcentaje2) {
            alert("Diferencia (+/-) 5% de lectura 2 contra el valor de conductividad");
        }
    }else if(activador == "conT3"){
        if(isNaN(l3)){
            t.rows[1].setAttribute("class", "bg-danger");
            inLec3.setAttribute("placeholder", "Lectura Vacía");
        }
        
        if (l3 - con < porcentaje2 * -1 || l3 - con > porcentaje2) {
            alert("Diferencia (+/-) 5% de lectura 3 contra el valor de conductividad");
        }
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

    p.value = ((l1 + l2 + l3) / 3).toFixed(2);
}

function validacionConCalidad(lec1, lec2, lec3, activador){
    let t = document.getElementById("tableConCalidad");
    let inLec1 = document.getElementById(lec1);
    let inLec2 = document.getElementById(lec2);
    let inLec3 = document.getElementById(lec3);
    let l1 = parseFloat(document.getElementById(lec1).value);
    let l2 = parseFloat(document.getElementById(lec2).value);
    let l3 = parseFloat(document.getElementById(lec3).value);    
    let con = parseFloat($("#conCalidad option:selected").text());
    let porcentaje = (con * 5) / 100;
    let porcentaje2 = Math.ceil(parseFloat(porcentaje.toFixed(2)));

    if(activador == "conCl1"){
        if(isNaN(l1)){
            t.rows[1].setAttribute("class", "bg-danger");            
            inLec1.setAttribute("placeholder", "Lectura Vacía");
        }
        
        if (l1 - con < porcentaje2 * -1 || l1 - con > porcentaje2) {
            alert("Diferencia (+/-) 5% de lectura 1 contra el valor de conductividad");
        }
    }else if(activador == "conCl2"){
        if(isNaN(l2)){
            t.rows[1].setAttribute("class", "bg-danger");            
            inLec2.setAttribute("placeholder", "Lectura Vacía");
        }
        
        if (l2 - con < porcentaje2 * -1 || l2 - con > porcentaje2) {
            alert("Diferencia (+/-) 5% de lectura 2 contra el valor de conductividad");
        }
    }else if(activador == "conCl3"){                
        if(isNaN(l3)){
            t.rows[1].setAttribute("class", "bg-danger");
            inLec3.setAttribute("placeholder", "Lectura Vacía");
        }
        
        if (l3 - con < porcentaje2 * -1 || l3 - con > porcentaje2) {
            alert("Diferencia (+/-) 5% de lectura 3 contra el valor de conductividad");
        }
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

function validacionValPendiente(valor){    
    let v = parseFloat(document.getElementById(valor).value);

    if(v < 95 || v > 105){
        alert("Valor de la pendiente fuera de rango (95-105)");
    }
}

function valPhMuestra(lec1, lec2, lec3, prom, prom1) {
    let sw = false;
    let sw1 = false;
    let sw2 = false;
    let sw3 = false;
    let sw4 = false;
    let sw5 = false;
    let sw6 = false;        
    let p = document.getElementById(prom);    
    let p1 = document.getElementById(prom1);
    let l1 = parseFloat(document.getElementById(lec1).value);
    let l2 = parseFloat(document.getElementById(lec2).value);
    let l3 = parseFloat(document.getElementById(lec3).value);
    let t = document.getElementById("phMuestra");

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

    r1 = (l1 - l2).toFixed(2);
    r2 = (l1 - l3).toFixed(2);
    r3 = (l2 - l1).toFixed(2);
    r4 = (l2 - l3).toFixed(2);
    r5 = (l3 - l1).toFixed(2);
    r6 = (l3 - l2).toFixed(2);
    
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
            if (lec1 == "phl10") {
                if((isNaN(l1)|| isNaN(l2) || isNaN(l3))){
                    t.rows[1].setAttribute("class", "bg-danger");
                }else{
                    t.rows[1].setAttribute("class", "bg-success");
                }                
            } else if (lec1 == "phl11") {
                if((isNaN(l1)|| isNaN(l2) || isNaN(l3))){
                    t.rows[2].setAttribute("class", "bg-danger");                    
                }else{
                    t.rows[2].setAttribute("class", "bg-success");
                }                                
            } else if (lec1 == "phl12") {
                if((isNaN(l1)|| isNaN(l2) || isNaN(l3))){
                    t.rows[3].setAttribute("class", "bg-danger");                    
                }else{
                    t.rows[3].setAttribute("class", "bg-success");
                }                                
            } else if (lec1 == "phl13") {
                if((isNaN(l1)|| isNaN(l2) || isNaN(l3))){
                    t.rows[4].setAttribute("class", "bg-danger");                    
                }else{
                    t.rows[4].setAttribute("class", "bg-success");
                }                
            } else if (lec1 == "phl14") {
                if((isNaN(l1)|| isNaN(l2) || isNaN(l3))){
                    t.rows[5].setAttribute("class", "bg-danger");                    
                }else{
                    t.rows[5].setAttribute("class", "bg-success");
                }                
            } else if (lec1 == "phl15") {
                if((isNaN(l1)|| isNaN(l2) || isNaN(l3))){
                    t.rows[6].setAttribute("class", "bg-danger");                    
                }else{
                    t.rows[6].setAttribute("class", "bg-success");
                }                
            }
        } else {
            //Rechazado
            if (lec1 == "phl10") {
                t.rows[1].setAttribute("class", "bg-danger");
            } else if (lec1 == "phl11") {
                t.rows[2].setAttribute("class", "bg-danger");
            } else if (lec1 == "phl12") {
                t.rows[3].setAttribute("class", "bg-danger");
            } else if (lec1 == "phl13") {
                t.rows[4].setAttribute("class", "bg-danger");
            } else if (lec1 == "phl14") {
                t.rows[5].setAttribute("class", "bg-danger");
            } else if (lec1 == "phl15") {
                t.rows[6].setAttribute("class", "bg-danger");
            }
        }
    } else {
        //Rechazado
        if (lec1 == "phl10") {
            t.rows[1].setAttribute("class", "bg-danger");
        } else if (lec1 == "phl11") {
            t.rows[2].setAttribute("class", "bg-danger");
        } else if (lec1 == "phl12") {
            t.rows[3].setAttribute("class", "bg-danger");
        } else if (lec1 == "phl13") {
            t.rows[4].setAttribute("class", "bg-danger");
        } else if (lec1 == "phl14") {
            t.rows[5].setAttribute("class", "bg-danger");
        } else if (lec1 == "phl15") {
            t.rows[6].setAttribute("class", "bg-danger");
        }
    }

    if(sw == true && sw1 == true && sw2 == true && sw3 == true && sw4 == true && sw5 == true && sw6 == true){                
        p.value = parseFloat(((l1 + l2 + l3) / 3)).toFixed(2);
        p1.innerHTML = parseFloat(((l1 + l2 + l3) / 3)).toFixed(2);
    }else{                
        if(!isNaN(l1) && !isNaN(l2) && !isNaN(l3)){            
            p1.innerHTML = "Error lecturas";
        }else{
            p1.innerHTML = "";
        }        
    }
    return sw;
}

function valTempMuestra(lec1, lec2, lec3, prom, f1, f2, f3, prom1) {
    Number.prototype.toFixedDown = function (digits) {
        var re = new RegExp("(\\d+\\.\\d{" + digits + "})(\\d)"),
            m = this.toString().match(re);
        return m ? parseFloat(m[1]) : this.valueOf();
    };        

    let fac1 = document.getElementById(f1);    
    let fac2 = document.getElementById(f2);
    let fac3 = document.getElementById(f3);
    let sw = true;
    let sw1 = false;
    let sw2 = false;
    let sw3 = false;
    let p = document.getElementById(prom);
    let p1 = document.getElementById(prom1);
    let t = document.getElementById("tempAgua");

    //Lectura 1------------------------------------------------
    let l1 = parseFloat(document.getElementById(lec1).value);    
    
        if(lec1 == "temp10"){            
            if ((l1 < 1 || l1 > 40) || isNaN(l1)) {
                fac1.innerHTML = "";
                sw1 = false;                
            }else{
                sw1 = true;                
            }            
        }
        
        if(lec1 == "temp11"){
            if ((l1 < 1 || l1 > 40) || isNaN(l1)) {
                fac1.innerHTML = "";
                sw1 = false;                
            }else{
                sw1 = true;                
            }
        }
        
        if(lec1 == "temp12"){
            if ((l1 < 1 || l1 > 40) || isNaN(l1)) {
                fac1.innerHTML = "";
                sw1 = false;                
            }else{
                sw1 = true;                
            }
        }
        
        if(lec1 == "temp13"){
            if ((l1 < 1 || l1 > 40) || isNaN(l1)) {
                fac1.innerHTML = "";
                sw1 = false;                
            }else{
                sw1 = true;                
            }
        }

        if(lec1 == "temp14"){
            if ((l1 < 1 || l1 > 40) || isNaN(l1)) {
                fac1.innerHTML = "";
                sw1 = false;                
            }else{
                sw1 = true;                
            }
        }

        if(lec1 == "temp15"){
            if ((l1 < 1 || l1 > 40) || isNaN(l1)) {
                fac1.innerHTML = "";
                sw1 = false;                
            }else{
                sw1 = true;                
            }
        }

    //Lectura 2-----------------------------------------------
    let l2 = parseFloat(document.getElementById(lec2).value);        
    
        if(lec2 == "temp20"){
            if ((l2 < 1 || l2 > 40) || isNaN(l2)) {
                fac2.innerHTML = "";
                sw2 = false;                
            }else{
                sw2 = true;
            }
        }
        
        if(lec2 == "temp21"){
            if ((l2 < 1 || l2 > 40) || isNaN(l2)) {
                fac2.innerHTML = "";
                sw2 = false;                
            }else{
                sw2 = true;
            }
        }
        
        if(lec2 == "temp22"){
            if ((l2 < 1 || l2 > 40) || isNaN(l2)) {
                fac2.innerHTML = "";
                sw2 = false;                
            }else{
                sw2 = true;
            }
        }
        
        if(lec2 == "temp23"){
            if ((l2 < 1 || l2 > 40) || isNaN(l2)) {
                fac2.innerHTML = "";
                sw2 = false;                
            }else{
                sw2 = true;
            }
        }  
        
        if(lec2 == "temp24"){
            if ((l2 < 1 || l2 > 40) || isNaN(l2)) {
                fac2.innerHTML = "";
                sw2 = false;                
            }else{
                sw2 = true;
            }
        }

        if(lec2 == "temp25"){
            if ((l2 < 1 || l2 > 40) || isNaN(l2)) {
                fac2.innerHTML = "";
                sw2 = false;                
            }else{
                sw2 = true;
            }
        }

    //Lectura 3-----------------------------------------------
    let l3 = parseFloat(document.getElementById(lec3).value);                
        
        if(lec3 == "temp30"){
            if ((l3 < 1 || l3 > 40) || isNaN(l3)) {
                fac3.innerHTML = "";
                sw3 = false;  
            }else{
                sw3 = true;
            }          
        }
        
        if(lec3 == "temp31"){
            if ((l3 < 1 || l3 > 40) || isNaN(l3)) {
                fac3.innerHTML = "";
                sw3 = false;                                 
            }else{
                sw3 = true;
            }
        }
        
        if(lec3 == "temp32"){
            if ((l3 < 1 || l3 > 40) || isNaN(l3)) {
                fac3.innerHTML = "";
                sw3 = false;             
            }else{
                sw3 = true;
            }
        }
        
        if(lec3 == "temp33"){
            if ((l3 < 1 || l3 > 40) || isNaN(l3)) {
                fac3.innerHTML = "";
                sw3 = false;                
            }else{
                sw3 = true;
            }
        }  
        
        if(lec3 == "temp34"){
            if ((l3 < 1 || l3 > 40) || isNaN(l3)) {
                fac3.innerHTML = "";
                sw3 = false;  
            }else{
                sw3 = true;
            }          
        }

        if(lec3 == "temp35"){
            if ((l3 < 1 || l3 > 40) || isNaN(l3)) {
                fac3.innerHTML = "";
                sw3 = false;  
            }else{
                sw3 = true;
            }          
        }

    //Comprueba que la diferencia entre valores no sea mayor que 1 unidad------------------------------
    r1 = (l1 - l2).toFixedDown(2);
    r2 = (l1 - l3).toFixedDown(2);
    r3 = (l2 - l1).toFixedDown(2);
    r4 = (l2 - l3).toFixedDown(2);
    r5 = (l3 - l1).toFixedDown(2);
    r6 = (l3 - l2).toFixedDown(2);    

    if (r1 > 1 || r1 < -1) {
        sw = false;
    }

    if (r2 > 1.0 || r2 < -1.0) {
        sw = false;
    }

    if (r3 > 1.0 || r3 < -1.0) {
        sw = false;
    }

    if (r4 > 1.0 || r4 < -1.0) {
        sw = false;
    }

    if (r5 > 1.0 || r5 < -1) {
        sw = false;
    }

    if (r6 > 1.0 || r6 < -1) {
        sw = false;
    }

//-----------------------------------------------------------------------------------------

    if (sw == true) {
        //Aceptado
        if (lec1 == "temp10") {                        
            if((isNaN(l2) || isNaN(l3)) || (sw1 == false || sw2 == false || sw3 == false)){
                t.rows[1].setAttribute("class", "bg-danger");                
            }else{
                t.rows[1].setAttribute("class", "bg-success");
            }         

        } else if (lec1 == "temp11") {            
            if((isNaN(l2) || isNaN(l3)) || (sw1 == false || sw2 == false || sw3 == false)){
                t.rows[2].setAttribute("class", "bg-danger");                
            }else{
                t.rows[2].setAttribute("class", "bg-success");
            }            

        } else if (lec1 == "temp12") {
            if((isNaN(l2) || isNaN(l3)) || (sw1 == false || sw2 == false || sw3 == false)){
                t.rows[3].setAttribute("class", "bg-danger");                
            }else{
                t.rows[3].setAttribute("class", "bg-success");
            }
            
        } else if (lec1 == "temp13") {            
            if((isNaN(l2) || isNaN(l3)) || (sw1 == false || sw2 == false || sw3 == false)){
                t.rows[4].setAttribute("class", "bg-danger");                
            }else{
                t.rows[4].setAttribute("class", "bg-success");
            } 

        } else if (lec1 == "temp14") {            
            if((isNaN(l2) || isNaN(l3)) || (sw1 == false || sw2 == false || sw3 == false)){
                t.rows[5].setAttribute("class", "bg-danger");                
            }else{
                t.rows[5].setAttribute("class", "bg-success");
            } 

        } else if (lec1 == "temp15") {            
            if((isNaN(l2) || isNaN(l3)) || (sw1 == false || sw2 == false || sw3 == false)){
                t.rows[6].setAttribute("class", "bg-danger");                
            }else{
                t.rows[6].setAttribute("class", "bg-success");
            }

        }
    } else {
        //Rechazado
        if (lec1 == "temp10") {            
            fac1.innerHTML = "";
            fac2.innerHTML = "";
            fac3.innerHTML = "";
            t.rows[1].setAttribute("class", "bg-danger");
        } else if (lec1 == "temp11") {
            fac1.innerHTML = "";
            fac2.innerHTML = "";
            fac3.innerHTML = "";
            t.rows[2].setAttribute("class", "bg-danger");
        } else if (lec1 == "temp12") {
            fac1.innerHTML = "";
            fac2.innerHTML = "";
            fac3.innerHTML = "";
            t.rows[3].setAttribute("class", "bg-danger");
        } else if (lec1 == "temp13") {
            fac1.innerHTML = "";
            fac2.innerHTML = "";
            fac3.innerHTML = "";
            t.rows[4].setAttribute("class", "bg-danger");
        } else if (lec1 == "temp14") {
            fac1.innerHTML = "";
            fac2.innerHTML = "";
            fac3.innerHTML = "";
            t.rows[5].setAttribute("class", "bg-danger");
        } else if (lec1 == "temp15") {
            fac1.innerHTML = "";
            fac2.innerHTML = "";
            fac3.innerHTML = "";
            t.rows[6].setAttribute("class", "bg-danger");
        }
    }
    
    if(sw == true && sw1 == true && sw2 == true && sw3 == true){        
        
        $.ajax({
                url: base_url + "/admin/campo/captura/getFactorAplicado",
                type: "POST",
                data: {
                    idFactor: $("#termometro").val(),
                    _token: $('input[name="_token"]').val(),
                },
                dataType: "json",
                async: false,
                success: function (response) {                                                                                          
                    let factores = new Array(8);
                    let i = 0;                    

                    //console.log(response.model);
                    
                    $.each(response.model, function (key, item) { 
                        factores[i] = parseInt(item.Factor_aplicado);
                        i++;
                    });                                        
    
                    //$.each(response.model, function (key, item) {
                        //item.Factor = parseInt(item.Factor);                        
                                                    
                            //LECTURA 1-----------------------------------------------
                            if((l1 >= 0 && l1 < 5)){
                                //if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                                                                
                                    fac1.innerHTML = parseFloat((l1 + factores[0])).toFixed(2);
                                    l1 = parseFloat(l1 + factores[0]);                                    
                                //}
                            }else if(l1 >= 5 && l1 < 10){
                                //if((item.Factor >= 0.5 || item.Factor <= -0.5) ){
                                    fac1.innerHTML = parseFloat((l1 + factores[1])).toFixed(2);
                                    l1 = parseFloat(l1 + factores[1]);                                                                                                            
                                //}
                            }else if((l1 >= 10 && l1 < 15)){
                                //if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                
                                    fac1.innerHTML = parseFloat((l1 + factores[2])).toFixed(2);
                                    l1 = parseFloat(l1 + factores[2]);
                                //}
                            }else if(l1 >= 15 && l1 < 20){
                                //if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                        
                                    fac1.innerHTML = parseFloat((l1 + factores[3])).toFixed(2);
                                    l1 = parseFloat(l1 + factores[3]);
                                //}
                            }else if(l1 >= 20 && l1 < 25){
                                //if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                                     
                                    fac1.innerHTML = parseFloat((l1 + factores[4])).toFixed(2);
                                    l1 = parseFloat(l1 + factores[4]);
                                //}
                            }else if(l1 >= 25 && l1 < 30){
                                //if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                
                                    fac1.innerHTML = parseFloat((l1 + factores[5])).toFixed(2);
                                    l1 = parseFloat(l1 + factores[5]);
                                //}
                            }else if(l1 >= 30 && l1 < 35){
                                //if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                                     
                                    fac1.innerHTML = parseFloat((l1 + factores[6])).toFixed(2);
                                    l1 = parseFloat(l1 + factores[6]);
                                //}
                            }else if(l1 >= 35 && l1 < 40){
                                //if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                
                                    fac1.innerHTML = parseFloat((l1 + factores[7])).toFixed(2);
                                    l1 = parseFloat(l1 + factores[7]);
                                //}
                            }else if(l1 >= 40 && l1 < 45){
                                fac1.innerHTML = parseFloat((l1 + factores[8])).toFixed(2);
                                l1 = parseFloat(l1 + factores[8]);
                            }else if(l1 >= 45 && l1 <= 50){
                                fac1.innerHTML = parseFloat((l1 + factores[9])).toFixed(2);
                                l1 = parseFloat(l1 + factores[9]);
                            }
                            
                            //LECTURA 2---------------------------------------------
                            if((l2 >= 0 && l2 < 5)){
                                //if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                                                                
                                    fac2.innerHTML = parseFloat((l2 + factores[0])).toFixed(2);
                                    l2 = parseFloat(l2 + factores[0]);
                                //}
                            }else if(l2 >= 5 && l2 < 10){
                                //if((item.Factor >= 0.5 || item.Factor <= -0.5) ){
                                    fac2.innerHTML = parseFloat((l2 + factores[1])).toFixed(2);
                                    l2 = parseFloat(l2 + factores[1]);
                                //}
                            }else if((l2 >= 10 && l2 < 15)){
                                //if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                
                                    fac2.innerHTML = parseFloat((l2 + factores[2])).toFixed(2);
                                    l2 = parseFloat(l2 + factores[2]);
                                //}
                            }else if(l2 >= 15 && l2 < 20){
                                //if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                        
                                    fac2.innerHTML = parseFloat((l2 + factores[3])).toFixed(2);
                                    l2 = parseFloat(l2 + factores[3]);
                                //}
                            }else if(l2 >= 20 && l2 < 25){
                                //if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                                     
                                    fac2.innerHTML = parseFloat((l2 + factores[4])).toFixed(2);
                                    l2 = parseFloat(l2 + factores[4]);
                                //}
                            }else if(l2 >= 25 && l2 < 30){
                                //if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                
                                    fac2.innerHTML = parseFloat((l2 + factores[5])).toFixed(2);
                                    l2 = parseFloat(l2 + factores[5]);
                                //}
                            }else if(l2 >= 30 && l2 < 35){
                                //if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                                     
                                    fac2.innerHTML = parseFloat((l2 + factores[6])).toFixed(2);
                                    l2 = parseFloat(l2 + factores[6]);
                                //}
                            }else if(l2 >= 35 && l2 < 40){
                                //if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                
                                    fac2.innerHTML = parseFloat((l2 + factores[7])).toFixed(2);
                                    l2 = parseFloat(l2 + factores[7]);
                                //}
                            }else if(l2 >= 40 && l2 < 45){
                                fac2.innerHTML = parseFloat((l2 + factores[8])).toFixed(2);
                                l2 = parseFloat(l2 + factores[8]);
                            }else if(l2 >= 45 && l2 <= 50){
                                fac2.innerHTML = parseFloat((l2 + factores[9])).toFixed(2);
                                l2 = parseFloat(l2 + factores[9]);
                            }
                            
                            //LECTURA 3---------------------------------------------    
                            if((l3 >= 0 && l3 < 5)){
                                //if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                                                                
                                    fac3.innerHTML = parseFloat((l3 + factores[0])).toFixed(2);
                                    l3 = parseFloat(l3 + factores[0]);                                    
                                //}
                            }else if(l3 >= 5 && l3 < 10){
                                //if((item.Factor >= 0.5 || item.Factor <= -0.5) ){
                                    fac3.innerHTML = parseFloat((l3 + factores[1])).toFixed(2);
                                    l3 = parseFloat(l3 + factores[1]);
                                //}
                            }else if((l3 >= 10 && l3 < 15)){
                                //if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                
                                    fac3.innerHTML = parseFloat((l3 + factores[2])).toFixed(2);
                                    l3 = parseFloat(l3 + factores[2]);                        
                                //}
                            }else if(l3 >= 15 && l3 < 20){
                                //if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                        
                                    fac3.innerHTML = parseFloat((l3 + factores[3])).toFixed(2);
                                    l3 = parseFloat(l3 + factores[3]);
                                //}
                            }else if(l3 >= 20 && l3 < 25){
                                //if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                                     
                                    fac3.innerHTML = parseFloat((l3 + factores[4])).toFixed(2);
                                    l3 = parseFloat(l3 + factores[4]);
                                //}
                            }else if(l3 >= 25 && l3 < 30){
                                //if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                
                                    fac3.innerHTML = parseFloat((l3 + factores[5])).toFixed(2);
                                    l3 = parseFloat(l3 + factores[5]);
                                //}
                            }else if(l3 >= 30 && l3 < 35){
                                //if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                                     
                                    fac3.innerHTML = parseFloat((l3 + factores[6])).toFixed(2);
                                    l3 = parseFloat(l3 + factores[6]);
                                //}
                            }else if(l3 >= 35 && l3 < 40){
                                //if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                
                                    fac3.innerHTML = parseFloat((l3 + factores[7])).toFixed(2);
                                    l3 = parseFloat(l3 + factores[7]);
                                //}
                            }else if(l3 >= 40 && l3 < 45){
                                fac3.innerHTML = parseFloat((l3 + factores[8])).toFixed(2);
                                l3 = parseFloat(l3 + factores[8]);
                            }else if(l3 >= 45 && l3 <= 50){
                                fac3.innerHTML = parseFloat((l3 + factores[9])).toFixed(2);
                                l3 = parseFloat(l3 + factores[9]);
                            }
                    //});
                },
            });                                    
        
        p.value = parseFloat(((l1 + l2 + l3) / 3)).toFixed(2);
        p1.innerHTML = parseFloat(((l1 + l2 + l3) / 3)).toFixed(0);
    }else{        
        if(!isNaN(l1) && !isNaN(l2) && !isNaN(l3)){            
            p1.innerHTML = "Error lecturas";
        }else{
            p1.innerHTML = "";
        }   
    }

    return sw;
}





function valPhCalidadMuestra(lec1, lec2, lec3, estado, prom, phCalidadMuestra) {
    let sw = false;
    let p = document.getElementById(prom);
    let std = document.getElementById(estado);
    let inLec1 = document.getElementById(lec1);
    let inLec2 = document.getElementById(lec2);
    let inLec3 = document.getElementById(lec3);
    let l1 = parseFloat(document.getElementById(lec1).value);
    let l2 = parseFloat(document.getElementById(lec2).value);
    let l3 = parseFloat(document.getElementById(lec3).value);
    let t = document.getElementById("phControlCalidadMuestra");
    var select = document.getElementById(phCalidadMuestra);      
    var text = select.options[select.selectedIndex].innerText;
    text = parseFloat(text);

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
    let cal;
    
    if(lec1 == "phCM11"){
        cal = parseFloat($("#phControlCalidadMuestra1 option:selected").text());
    }else if(lec1 == "phCM12"){
        cal = parseFloat($("#phControlCalidadMuestra2 option:selected").text());
    }else if(lec1 == "phCM13"){
        cal = parseFloat($("#phControlCalidadMuestra3 option:selected").text());
    }else if(lec1 == "phCM14"){
        cal = parseFloat($("#phControlCalidadMuestra4 option:selected").text());
    }else if(lec1 == "phCM15"){
        cal = parseFloat($("#phControlCalidadMuestra5 option:selected").text());
    }else if(lec1 == "phCM16"){
        cal = parseFloat($("#phControlCalidadMuestra6 option:selected").text());
    }    

    let porcentaje = (cal * 2) / 100;
    let porcentaje2 = parseFloat(porcentaje.toFixed(2));    
    
    if (l1 > 4 && l1 < 9) {
        sw = true;
    } else if (l2 > 4 && l2 < 9) {
        sw = true;
    } else if (l3 > 4 && l3 < 9) {
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
    if (parseFloat((l1 - cal).toFixed(2)) < porcentaje2 * -1 || parseFloat((l1 - cal).toFixed(2)) > porcentaje2) {
        sw7 = false;
    }    

    if (parseFloat((l2 - cal).toFixed(2)) < porcentaje2 * -1 || parseFloat((l2 - cal).toFixed(2)) > porcentaje2) {
        sw8 = false;
    }    

    if (parseFloat((l3 - cal).toFixed(2)) < porcentaje2 * -1 || parseFloat((l3 - cal).toFixed(2)) > porcentaje2) {
        sw9 = false;
    }
    //----------------------------------------------------------------------------------------------------------------    

    if(sw1 == true && sw2 == true && sw3 == true && sw4 == true && sw5 == true && sw6 == true && sw7 == true && sw8 == true && sw9 == true){
        sw = true;
    }else{
        sw = false;
    }        

    if(isNaN(l1)){
        inLec1.setAttribute("placeholder", "Lectura Vacía");
        sw = false;
    }
    
    if(isNaN(l2)){
        inLec2.setAttribute("placeholder", "Lectura Vacía");
        sw = false;
    }
    
    if(isNaN(l3)){
        inLec3.setAttribute("placeholder", "Lectura Vacía");
        sw = false;
    }

    if(isNaN(text)){
        sw = false;
        alert("No se ha seleccionado un PH Calidad");
    }

    if (sw == true) {
        std.value = "Aprobado";
        if(lec1 == "phCM11") {
            t.rows[1].setAttribute("class", "bg-success");
        }else if(lec1 == "phCM12"){
            t.rows[2].setAttribute("class", "bg-success");
        }else if(lec1 == "phCM13"){
            t.rows[3].setAttribute("class", "bg-success");
        }else if(lec1 == "phCM14"){
            t.rows[4].setAttribute("class", "bg-success");
        }else if(lec1 == "phCM15"){
            t.rows[5].setAttribute("class", "bg-success");
        }else if(lec1 == "phCM16"){
            t.rows[6].setAttribute("class", "bg-success");
        }
        p.value = ((l1 + l2 + l3) / 3).toFixed(2);
    } else {
        std.value = "Rechazado";
        if(lec1 == "phCM11"){
            t.rows[1].setAttribute("class", "bg-danger");
        }else if(lec1 == "phCM12"){
            t.rows[2].setAttribute("class", "bg-danger");
        }else if(lec1 == "phCM13"){
            t.rows[3].setAttribute("class", "bg-danger");
        }else if(lec1 == "phCM14"){
            t.rows[4].setAttribute("class", "bg-danger");
        }else if(lec1 == "phCM15"){
            t.rows[5].setAttribute("class", "bg-danger");
        }else if(lec1 == "phCM16"){
            t.rows[6].setAttribute("class", "bg-danger");
        }

        p.value = null;
    }
    
    //p.value = ((l1 + l2 + l3) / 3).toFixed(2);
}

function validacionPhCalidadMuestra(lec1, lec2, lec3, activador, phControlCalidadMuestra){
    let l1 = parseFloat(document.getElementById(lec1).value);        
    let l2 = parseFloat(document.getElementById(lec2).value);
    let l3 = parseFloat(document.getElementById(lec3).value);
    var select = document.getElementById(phControlCalidadMuestra);
    var text = select.options[select.selectedIndex].innerText;
    
    if(lec1 == "phCM11"){
        cal = parseFloat($("#phControlCalidadMuestra1 option:selected").text());
    }else if(lec1 == "phCM12"){
        cal = parseFloat($("#phControlCalidadMuestra2 option:selected").text());
    }else if(lec1 == "phCM13"){
        cal = parseFloat($("#phControlCalidadMuestra3 option:selected").text());
    }else if(lec1 == "phCM14"){
        cal = parseFloat($("#phControlCalidadMuestra4 option:selected").text());
    }else if(lec1 == "phCM15"){
        cal = parseFloat($("#phControlCalidadMuestra5 option:selected").text());
    }else if(lec1 == "phCM16"){
        cal = parseFloat($("#phControlCalidadMuestra6 option:selected").text());
    }

    let porcentaje = (cal * 2) / 100;
    let porcentaje2 = parseFloat(porcentaje.toFixed(2));      
    
    let sw1;
    let sw2;
    let sw3;    

    text = parseFloat(text);
    //console.log("Valor de phT: " + text);

    r1 = parseFloat((l1 - l2).toFixed(2));
    r2 = parseFloat((l1 - l3).toFixed(2));

    r3 = parseFloat((l2 - l1).toFixed(2));
    r4 = parseFloat((l2 - l3).toFixed(2));

    r5 = parseFloat((l3 - l1).toFixed(2));
    r6 = parseFloat((l3 - l2).toFixed(2));    

    if((r1 < -0.03 || r1 > 0.03) || (r3 < -0.03 || r3 > 0.03)){
        sw1 = true;
    }else{
        sw1 = false;
    }

    if((r2 < -0.03 || r2 > 0.03) || (r5 < -0.03 || r5 > 0.03)){
        sw2 = true;
    }else{
        sw2 = false;
    }

    if((r4 < -0.03 || r4 > 0.03) || (r6 < -0.03 || r6 > 0.03)){
        sw3 = true;
    }else{
        sw3 = false;
    }

    //VERIFICAR BLOQUEO DE ALERTS
    if(sw1 == true || sw2 == true || sw3 == true){
        alert("Diferencia (+/-) 0.03 unidades entre las lecturas");
    }

    if(activador == "phCM11" || activador == "phCM12" || activador == "phCM13" || activador == "phCM14" || activador == "phCM15" || activador == "phCM16"){
        if (parseFloat((l1 - cal).toFixed(2)) < porcentaje2 * -1 || parseFloat((l1 - cal).toFixed(2)) > porcentaje2) {
            alert("Diferencia (+/-) 2% de lectura 1 contra el valor de PH Calidad");
        }
    }else if(activador == "phCM21" || activador == "phCM22" ||activador == "phCM23" ||activador == "phCM24" ||activador == "phCM25" ||activador == "phCM26"){
        if (parseFloat((l2 - cal).toFixed(2)) < porcentaje2 * -1 || parseFloat((l2 - cal).toFixed(2)) > porcentaje2) {
            alert("Diferencia (+/-) 2% de lectura 2 contra el valor de PH Calidad");
        }
    }else if(activador == "phCM31" || activador == "phCM32" || activador == "phCM33" || activador == "phCM34" || activador == "phCM35" || activador == "phCM36"){
        if (parseFloat((l3 - cal).toFixed(2)) < porcentaje2 * -1 || parseFloat((l3 - cal).toFixed(2)) > porcentaje2) {
            alert("Diferencia (+/-) 2% de lectura 3 contra el valor de PH Calidad");
        }        
    }
}

function valConMuestra(lec1, lec2, lec3, prom, prom1) {
    let sw = true;
    let p = document.getElementById(prom);
    let p1 = document.getElementById(prom1);
    let l1 = parseFloat(document.getElementById(lec1).value);
    let l2 = parseFloat(document.getElementById(lec2).value);
    let l3 = parseFloat(document.getElementById(lec3).value);
    let t = document.getElementById("conductividad");

    //El valor entre ellos no debe diferir de 5 unidades de conductividad
    if (l1 - l2 > 5 || l1 - l2 < -5) {
        sw = false;
    }
    if (l1 - l3 > 5 || l1 - l3 < -5) {
        sw = false;
    }
    if (l2 - l1 > 5 || l2 - l1 < -5) {
        sw = false;
    }
    if (l2 - l3 > 5 || l2 - l3 < -5) {
        sw = false;
    }
    if (l3 - l1 > 5 || l3 - l1 < -5) {
        sw = false;
    }
    if (l3 - l2 > 5 || l3 - l2 < -5) {
        sw = false;
    }

    if (sw == true) {
        //Aceptado
        if (lec1 == "con10") {
            if(isNaN(l1)|| isNaN(l2) || isNaN(l3)){
                t.rows[1].setAttribute("class", "bg-danger");                
            }else{
                t.rows[1].setAttribute("class", "bg-success");
            }                                     
        } else if (lec1 == "con11") {
            if(isNaN(l1)|| isNaN(l2) || isNaN(l3)){
                t.rows[2].setAttribute("class", "bg-danger");                
            }else{
                t.rows[2].setAttribute("class", "bg-success");
            }                   
        } else if (lec1 == "con12") {
            if(isNaN(l1)|| isNaN(l2) || isNaN(l3)){
                t.rows[3].setAttribute("class", "bg-danger");                
            }else{
                t.rows[3].setAttribute("class", "bg-success");
            }                               
        } else if (lec1 == "con13") {
            if(isNaN(l1)|| isNaN(l2) || isNaN(l3)){
                t.rows[4].setAttribute("class", "bg-danger");                
            }else{
                t.rows[4].setAttribute("class", "bg-success");
            }                               
        } else if (lec1 == "con14") {
            if(isNaN(l1)|| isNaN(l2) || isNaN(l3)){
                t.rows[5].setAttribute("class", "bg-danger");                
            }else{
                t.rows[5].setAttribute("class", "bg-success");
            }                   
        } else if (lec1 == "con15") {
            if(isNaN(l1)|| isNaN(l2) || isNaN(l3)){
                t.rows[6].setAttribute("class", "bg-danger");                
            }else{
                t.rows[6].setAttribute("class", "bg-success");
            }                   
        }
    } else {
        //Rechazado
        if (lec1 == "con10") {
            t.rows[1].setAttribute("class", "bg-danger");
        } else if (lec1 == "con11") {
            t.rows[2].setAttribute("class", "bg-danger");
        } else if (lec1 == "con12") {
            t.rows[3].setAttribute("class", "bg-danger");
        } else if (lec1 == "con13") {
            t.rows[4].setAttribute("class", "bg-danger");
        } else if (lec1 == "con14") {
            t.rows[5].setAttribute("class", "bg-danger");
        } else if (lec1 == "con15") {
            t.rows[6].setAttribute("class", "bg-danger");
        }
    }    

    if((sw == true) && (!isNaN(l1) && !isNaN(l2) && !isNaN(l3))){        
        p.value = parseFloat(((l1 + l2 + l3) / 3)).toFixed(0);
        p1.innerHTML = parseFloat(((l1 + l2 + l3) / 3)).toFixed(0);
    }else{        
        if(!isNaN(l1) && !isNaN(l2) && !isNaN(l3)){            
            p1.innerHTML = "Error lecturas";
        }else{
            p1.innerHTML = "";
        }
    }

    return sw;
}

let gasprom0 = null;
let gasprom1 = null;
let gasprom2 = null;
let gasprom3 = null;
let gasprom4 = null;
let gasprom5 = null;

let temp1;
let temp2;

function valGastoMuestra(lec1, lec2, lec3, prom, prom1) {
    let sw = true;
    let p = document.getElementById(prom);    
    //console.log("Valor de prom: " + prom);
    let p1 = document.getElementById(prom1);
    let l1 = parseFloat(document.getElementById(lec1).value);
    let l2 = parseFloat(document.getElementById(lec2).value);
    let l3 = parseFloat(document.getElementById(lec3).value);    
    let t = document.getElementById("gasto");

    if (l1 - l2 > 1 || l1 - l2 < -1) {
        sw = false;
    }
    if (l1 - l3 > 1 || l1 - l3 < -1) {
        sw = false;
    }
    if (l2 - l1 > 1 || l2 - l1 < -1) {
        sw = false;
    }
    if (l2 - l3 > 1 || l2 - l3 < -1) {
        sw = false;
    }
    if (l3 - l1 > 1 || l3 - l1 < -1) {
        sw = false;
    }
    if (l3 - l2 > 1 || l3 - l2 < -1) {
        sw = false;
    }

    if (sw == true) {
        //Aceptado
        if (lec1 == "gas10") {
            if(isNaN(l1)|| isNaN(l2) || isNaN(l3)){
                t.rows[1].setAttribute("class", "bg-danger");                
            }else{
                t.rows[1].setAttribute("class", "bg-success");
            }                               
        } else if (lec1 == "gas11") {
            if(isNaN(l1)|| isNaN(l2) || isNaN(l3)){
                t.rows[2].setAttribute("class", "bg-danger");                
            }else{
                t.rows[2].setAttribute("class", "bg-success");
            }                               
        } else if (lec1 == "gas12") {
            if(isNaN(l1)|| isNaN(l2) || isNaN(l3)){
                t.rows[3].setAttribute("class", "bg-danger");                
            }else{
                t.rows[3].setAttribute("class", "bg-success");
            }                   
        } else if (lec1 == "gas13") {
            if(isNaN(l1)|| isNaN(l2) || isNaN(l3)){
                t.rows[4].setAttribute("class", "bg-danger");                
            }else{
                t.rows[4].setAttribute("class", "bg-success");
            }                               
        } else if (lec1 == "gas14") {
            if(isNaN(l1)|| isNaN(l2) || isNaN(l3)){
                t.rows[5].setAttribute("class", "bg-danger");                
            }else{
                t.rows[5].setAttribute("class", "bg-success");
            }                               
        } else if (lec1 == "gas15") {
            if(isNaN(l1)|| isNaN(l2) || isNaN(l3)){
                t.rows[6].setAttribute("class", "bg-danger");                
            }else{
                t.rows[6].setAttribute("class", "bg-success");
            }                               
        }
    } else {
        //Rechazado
        if (lec1 == "gas10") {
            t.rows[1].setAttribute("class", "bg-danger");
        } else if (lec1 == "gas11") {
            t.rows[2].setAttribute("class", "bg-danger");
        } else if (lec1 == "gas12") {
            t.rows[3].setAttribute("class", "bg-danger");
        } else if (lec1 == "gas13") {
            t.rows[4].setAttribute("class", "bg-danger");
        } else if (lec1 == "gas14") {
            t.rows[5].setAttribute("class", "bg-danger");
        } else if (lec1 == "gas15") {
            t.rows[6].setAttribute("class", "bg-danger");
        }
    }

    if((sw == true) && (!isNaN(l1) && !isNaN(l2) && !isNaN(l3))){
        p.value = parseFloat(((l1 + l2 + l3) / 3)).toFixed(2);
        p1.innerHTML = parseFloat(((l1 + l2 + l3) / 3)).toFixed(2);
    }else{        
        if(!isNaN(l1) && !isNaN(l2) && !isNaN(l3)){            
            p1.innerHTML = "Error lecturas";
        }else{
            p1.innerHTML = "";
        }
    }

    if(prom === 'gasprom0'){        
        gasprom0 = parseFloat(p.value);
        //console.log("Valor de gasprom0: " + gasprom0);
    }else if(prom === 'gasprom1'){
        gasprom1 = parseFloat(p.value);
        //console.log("Valor de gasprom1: " + gasprom1);
    }else if(prom === 'gasprom2'){
        gasprom2 = parseFloat(p.value);
        //console.log("Valor de gasprom2: " + gasprom2);
    }else if(prom === 'gasprom3'){
        gasprom3 = parseFloat(p.value);
        //console.log("Valor de gasprom3: " + gasprom3);
    }else if(prom === 'gasprom4'){
        gasprom4 = parseFloat(p.value);
        //console.log("Valor de gasprom4: " + gasprom4);
        
        if(!gasprom4 === null){                        
            temp1 = gasprom4;                           
        }                  
    }else if(prom === 'gasprom5'){
        gasprom5 = parseFloat(p.value);
        //console.log("Valor de gasprom5: " + gasprom5);
        
        if(!gasprom5 === null){                        
            temp1 = gasprom5;                           
        }        
    }

    return sw;
}

let t;

function valTempCompuesto(temp1, factTempAplicado){
    //Almacena el valor del ID del Input
    t = parseFloat(document.getElementById(temp1).value);
    let factAplicado = document.getElementById(factTempAplicado);
    //console.log("Valor de temperatura compuesta: " + t);
    
    $.ajax({
        url: base_url + "/admin/campo/captura/getFactorCorreccion", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            idFactor: $("#termometro").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {                                                                      
            let sw1;
            let sw2;
            let sw3;
            let sw4;
            let sw5;
            let sw6;
            let sw7;
            let sw8;
            let sw9;
            let sw10;
            let sw11;
            let cont = 1;
            
            console.log(response.model);               

            $.each(response.model, function (key, item) {                                
                item.Factor = parseFloat(item.Factor);
                item.Factor_aplicado= parseFloat(item.Factor_aplicado);   

                /*console.log("Valor de Id_termometro: " + item.Id_termometro);
                console.log("Valor De_c: " + item.De_c);
                console.log("Valor A_c: " + item.A_c);
                console.log("Valor de factor de corrección: " + item.Factor);
                console.log("Valor de factor de corrección aplicado: " + item.Factor_aplicado);
                console.log('\n');
                console.log('---------------------------');*/
                
                if(t >= 0 && t < 5){
                    if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                                        
                        //factAplicado.innerHTML = t + item.Factor_aplicado;
                        //t = t + item.Factor_aplicado;
                        //console.log("Temp 0-5");
                        //console.log("Valor final: " + t);
                        sw1 = true;
                        sw2 = false;
                        sw3 = false;
                        sw4 = false;
                        sw5 = false;
                        sw6 = false;
                        sw7 = false;
                        sw8 = false;
                        sw9 = false;
                        sw10 = false;
                        sw11 = false;
                    }
                }else if(t >= 5 && t < 10){
                    if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                                                                
                        //factAplicado.innerHTML = t + item.Factor_aplicado;
                        //t = t + factorAplicado;
                        //console.log("Temp 5-10");
                        //console.log("Valor final: " + t);
                        sw2 = true;
                        sw1 = false;
                        sw3 = false;
                        sw4 = false;
                        sw5 = false;
                        sw6 = false;
                        sw7 = false;
                        sw8 = false;
                        sw9 = false;
                        sw10 = false;
                        sw11 = false;
                    }
                }else if(t >= 10 && t < 15){
                    if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                        
                        //factAplicado.innerHTML = t + item.Factor_aplicado;
                        //t = t + item.Factor_aplicado;
                        //console.log("Temp 10-15");
                        //console.log("Valor final: " + t);
                        sw3 = true;
                        sw2 = false;
                        sw1 = false;
                        sw4 = false;
                        sw5 = false;
                        sw6 = false;
                        sw7 = false;
                        sw8 = false;
                        sw9 = false; 
                        sw10 = false;                       
                        sw11 = false;
                    }
                }else if(t >= 15 && t < 20){
                    if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                        
                        //factAplicado.innerHTML = t + item.Factor_aplicado;
                        //t = t + item.Factor_aplicado;
                        //console.log("Temp 15-20");
                        //console.log("Valor final: " + t);
                        sw4 = true;
                        sw2 = false;
                        sw3 = false;
                        sw1 = false;
                        sw5 = false;
                        sw6 = false;
                        sw7 = false;
                        sw8 = false;
                        sw9 = false;
                        sw10 = false;                       
                        sw11 = false;
                    }
                }else if(t >= 20 && t < 25){
                    if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                        
                        //factAplicado.innerHTML = t + item.Factor_aplicado;
                        //t = t + item.Factor_aplicado;
                        //console.log("Temp 20-25");
                        //console.log("Valor final: " + t);                        
                        sw5 = true;
                        sw2 = false;
                        sw3 = false;
                        sw4 = false;
                        sw1 = false;
                        sw6 = false;
                        sw7 = false;
                        sw8 = false;
                        sw9 = false;
                        sw10 = false;                       
                        sw11 = false;
                    }
                }else if(t >= 25 && t < 30){
                    if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                        
                        //factAplicado.innerHTML = t + item.Factor_aplicado;
                        //t = t + item.Factor_aplicado;
                        //console.log("Temp 25-30");
                        //console.log("Valor final: " + t);
                        sw6 = true;
                        sw2 = false;
                        sw3 = false;
                        sw4 = false;
                        sw5 = false;
                        sw1 = false;
                        sw7 = false;
                        sw8 = false;
                        sw9 = false;
                        sw10 = false;                       
                        sw11 = false;
                    }
                }else if(t >= 30 && t < 35){
                    if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                        
                        //factAplicado.innerHTML = t + item.Factor_aplicado;
                        //t = t + item.Factor_aplicado;
                        //console.log("Temp 30-35");
                        //console.log("Valor final: " + t);                        
                        sw7 = true;
                        sw2 = false;
                        sw3 = false;
                        sw4 = false;
                        sw5 = false;
                        sw6 = false;
                        sw1 = false;
                        sw8 = false;
                        sw9 = false;
                        sw10 = false;                       
                        sw11 = false;
                    }
                }else if(t >= 35 && t < 40){
                    if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                        
                        //factAplicado.innerHTML = t + item.Factor_aplicado;
                        //t = t + item.Factor_aplicado;
                        //console.log("Temp 35-40");
                        //console.log("Valor final: " + t);                        
                        sw8 = true;
                        sw2 = false;
                        sw3 = false;
                        sw4 = false;
                        sw5 = false;
                        sw6 = false;
                        sw7 = false;
                        sw1 = false;
                        sw9 = false;
                        sw10 = false;                       
                        sw11 = false;
                    }
                }else if(t >= 40 && t < 45){
                    if((item.Factor >= 0.5 || item.Factor <= -0.5) ){ 
                        sw9 = true;
                        sw1 = false;
                        sw2 = false;
                        sw3 = false;
                        sw4 = false;
                        sw5 = false;
                        sw6 = false;
                        sw7 = false;
                        sw8 = false;
                        sw10 = false;                       
                        sw11 = false;
                    }
                }else if(t >= 45 && t <= 50){
                    if((item.Factor >= 0.5 || item.Factor <= -0.5) ){
                        sw10 = true;
                        sw9 = false;
                        sw1 = false;
                        sw2 = false;
                        sw3 = false;
                        sw4 = false;
                        sw5 = false;
                        sw6 = false;
                        sw7 = false;
                        sw8 = false;                                           
                        sw11 = false;
                    }
                }else{
                    sw11 = true;
                    sw9 = false;
                    sw1 = false;
                    sw2 = false;
                    sw3 = false;
                    sw4 = false;
                    sw5 = false;
                    sw6 = false;
                    sw7 = false;
                    sw8 = false;
                    sw10 = false;                                           
                }

                if(sw1 == true && cont == 1){
                    factAplicado.innerHTML = t + item.Factor_aplicado;
                    t = t + item.Factor_aplicado;
                    return false;
                }else if(sw2 == true && cont == 2){
                    factAplicado.innerHTML = t + item.Factor_aplicado;
                    t = t + item.Factor_aplicado;
                    return false;
                }else if(sw3 == true && cont == 3){
                    factAplicado.innerHTML = t + item.Factor_aplicado;
                    t = t + item.Factor_aplicado;
                    return false;
                }else if(sw4 == true && cont == 4){
                    factAplicado.innerHTML = t + item.Factor_aplicado;
                    t = t + item.Factor_aplicado;
                    return false;
                }else if(sw5 == true && cont == 5){
                    factAplicado.innerHTML = t + item.Factor_aplicado;
                    t = t + item.Factor_aplicado;
                    return false;
                }else if(sw6 == true && cont == 6){
                    factAplicado.innerHTML = t + item.Factor_aplicado;
                    t = t + item.Factor_aplicado;
                    return false;
                }else if(sw7 == true && cont == 7){
                    factAplicado.innerHTML = t + item.Factor_aplicado;
                    t = t + item.Factor_aplicado;
                    return false;
                }else if(sw8 == true && cont == 8){
                    factAplicado.innerHTML = t + item.Factor_aplicado;
                    t = t + item.Factor_aplicado;
                    return false;
                }else if(sw9 == true && cont == 9){
                    factAplicado.innerHTML = t + item.Factor_aplicado;
                    t = t + item.Factor_aplicado;
                    return false;
                }else if(sw10 == true && cont == 10){
                    factAplicado.innerHTML = t + item.Factor_aplicado;
                    t = t + item.Factor_aplicado;
                    return false;
                }else if(sw11 == true){
                    factAplicado.innerHTML = t;
                    cont--;
                    return false;                    
                }                

                cont++;
            }); 
            
            if(isNaN(t)){
                factAplicado.innerHTML = "Temperatura muestra vacía";
            }
        },
    });
}

function promedioPh(ph1, ph2, ph3, res) {
    let p1 = document.getElementById(ph1).value;
    let p2 = document.getElementById(ph2).value;
    let p3 = document.getElementById(ph3).value;
    let r = document.getElementById(res);
    let prom = (parseFloat(p1) + parseFloat(p2) + parseFloat(p3)) / 3;
    r.value = prom.toFixed(0);
}
function calPromedios(ph1, ph2, ph3, res, dec) {
    let p1 = document.getElementById(ph1).value;
    let p2 = document.getElementById(ph2).value;
    let p3 = document.getElementById(ph3).value;
    let r = document.getElementById(res);
    let prom = (parseFloat(p1) + parseFloat(p2) + parseFloat(p3)) / 3;
    r.value = prom.toFixed(dec);
}
function calPromedioGasto(ph1, ph2, ph3, res) {
    let p1 = document.getElementById(ph1).value;
    let p2 = document.getElementById(ph2).value;
    let p3 = document.getElementById(ph3).value;
    let r = document.getElementById(res);
    let prom = (parseFloat(p1) + parseFloat(p2) + parseFloat(p3)) / 3;
    r.value = prom / 0.012;
}
function getFactorCorreccion() {
    let table = document.getElementById("factorDeConversion");
    let tab = "";
    $.ajax({
        url: base_url + "/admin/campo/captura/getFactorCorreccion", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            idFactor: $("#termometro").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            myArray = response.model;
            console.log(myArray);
            tab +=
                '<table id="tablaFactorCorreccion" class="table table-sm  table-striped table-bordered">';
            tab += '    <thead class="thead-dark">';
            tab += "        <tr>";
            tab += "              <th>De °C</th>";
            tab += "              <th>a °C</th>";
            tab += "              <th>Factor corrección</th>";
            tab += "              <th>Factor de corrección aplicada</th>";
            tab += "        </tr>";
            tab += "    </thead>";
            tab += "    <tbody>";
            $.each(response.model, function (key, item) {
                tab += "<tr>";
                tab += "<td>" + item.De_c + "</td>";
                tab += "<td>" + item.A_c + "</td>";
                tab += "<td>" + item.Factor + "</td>";
                tab += "<td>" + item.Factor_aplicado + "</td>";
                tab += "</tr>";
            });
            tab += "    </tbody>";
            tab += "</table>";
            table.innerHTML = tab;
        },
    });
}

function setPhTrazable(idPh, nombre, marca, lote) {
    //let valor = $("#phTrazable1").val();    
    
    let nom = document.getElementById(nombre);
    let mar = document.getElementById(marca);
    let lot = document.getElementById(lote);
    $.ajax({
        url: base_url + "/admin/campo/captura/getPhTrazable", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            idPh: idPh,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            //console.log("HOLAA")

            //$('select[name="phTrazable1n"] option:selected').val(response.model.Id_ph);
            nom.innerText = response.model.Ph;
            mar.innerText = response.model.Marca;
            lot.innerText = response.model.Lote;
        },
    });
}

function setPhTrazable2(idPh, nombre, marca, lote) {
    let nom = document.getElementById(nombre);
    let mar = document.getElementById(marca);
    let lot = document.getElementById(lote);

    $.ajax({
        url: base_url + "/admin/campo/captura/getPhTrazable", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            idPh: idPh,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            //console.log("holaaa");

            nom.innerText = response.model.Ph;
            mar.innerText = response.model.Marca;
            lot.innerText = response.model.Lote;

            /* nom.innerText = "";
            mar.innerText = "";
            lot.innerText = ""; */
        },
    });
}

function setPhCalidad(idPh, nombre, marca, lote) {    
    let nom = document.getElementById(nombre);
    let mar = document.getElementById(marca);
    let lot = document.getElementById(lote);

    let idph = $('select[name="phTrazable1"] option:selected').text();    
    let trazable = $('select[name="phTrazable1n"] option:selected').text();    

    $.ajax({
        url: base_url + "/admin/campo/captura/getPhCalidad", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            idPh: idPh,
            trazable: trazable,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);            

            $("#phCalidad1").val(response.model.Id_ph);
            //$('select[name="phCalidad1n"] option:selected').val(response.model.Id_ph);
            //$('select[name="phCalidad1n"] option:selected').text(idPh);
            nom.innerText = response.model.Ph_calidad;
            mar.innerText = response.model.Marca;
            lot.innerText = response.model.Lote;
        },
    });
}

function setPhCalidad2(idPh, nombre, marca, lote) {
    let nom = document.getElementById(nombre);
    let mar = document.getElementById(marca);
    let lot = document.getElementById(lote);

    let idph = $('select[name="phTrazable1"] option:selected').text(); 
    let trazable = $('select[name="phTrazable2n"] option:selected').text();

    $.ajax({
        url: base_url + "/admin/campo/captura/getPhCalidad", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            idPh: idPh,
            trazable: trazable,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {                       
            console.log(response);
            
            $("#phCalidad2").val(response.model.Id_ph);
            nom.innerText = response.model.Ph_calidad;
            mar.innerText = response.model.Marca;
            lot.innerText = response.model.Lote;
            /* nom.innerText = ""; 
            mar.innerText = "";
            lot.innerText = ""; */
        },
    });
}

function setConTrazable(idCon, nombre, marca, lote) {
    let nom = document.getElementById(nombre);
    let mar = document.getElementById(marca);
    let lot = document.getElementById(lote);
    $.ajax({
        url: base_url + "/admin/campo/captura/getConTrazable", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            idCon: idCon,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            nom.innerText = response.model.Conductividad;
            mar.innerText = response.model.Marca;
            lot.innerText = response.model.Lote;
        },
    });
}
function setConCalidad(idCon, nombre, marca, lote) {
    let nom = document.getElementById(nombre);
    let mar = document.getElementById(marca);
    let lot = document.getElementById(lote);
    $.ajax({
        url: base_url + "/admin/campo/captura/getConCalidad", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            idCon: idCon,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            nom.innerText = response.model.Conductividad;
            mar.innerText = response.model.Marca;
            lot.innerText = response.model.Lote;
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

// Guardar datos generales

function setDataGeneral() {
    $.ajax({
        url: base_url + "/admin/campo/captura/setDataGeneral", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            idSolicitud: $("#idSolicitud").val(),
            Captura: "Sistema",
            equipo: $("#termometro").val(),
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

function setDataMuestreo() {
    let ph = new Array();
    let temperatura = new Array();
    let phCalidad = new Array();
    let conductividad = new Array();
    let gasto = new Array();
    let row = new Array();

    //PH muestra
    for (let i = 0; i < $("#numTomas").val(); i++) {
        row = new Array();

        if($("#materia" + i + " option:selected").val() == 0){
            row.push($("#materia" + i).val(null));
        }else{
            row.push($("#materia" + i + " option:selected").text());
        }

        if($("#olor" + i + " option:selected").val() == 0){
            row.push($("#olor" + i).val(null));
        }else{
            row.push($("#olor" + i + " option:selected").text());
        }

        if($("#color" + i + " option:selected").val() == 0){
            row.push($("#color" + i).val(null));
        }else{
            row.push($("#color" + i + " option:selected").text());
        }
        
        row.push($("#phl1" + i).val());
        row.push($("#phl2" + i).val());
        row.push($("#phl3" + i).val());
        row.push($("#phprom" + i).val());
        row.push($("#phf" + i).val());
        row.push($("#phStatus1" + i).val());
        row.push($("#numTomas").val());
        ph.push(row);
    }

    //Temperatura muestra
    for (let i = 0; i < $("#numTomas").val(); i++) {
        row = new Array();
        row.push($("#temp1" + i).val());
        row.push($("#temp2" + i).val());
        row.push($("#temp3" + i).val());
        row.push($("#tempprom" + i).val());
        row.push($("#tempStatus1" + (i+1)).val());
        temperatura.push(row);
    }

    //Ph calidad muestra
    for (let i = 0; i < $("#numTomas").val(); i++) {
        row = new Array();

        if($("#phControlCalidadMuestra" + (i+1) + " option:selected").val() == 0){
            row.push($("#phControlCalidadMuestra" + (i+1)).val(null));
        }else{
            row.push($("#phControlCalidadMuestra" + (i+1) + " option:selected").text());
        }

        row.push($("#phCM1" + (i+1)).val());
        row.push($("#phCM2" + (i+1)).val());
        row.push($("#phCM3" + (i+1)).val());
        row.push($("#phCMEstado1" + (i+1)).val());
        row.push($("#phCMPromedio1" + (i+1)).val());
        row.push($("#phCMStatus1" + (i+1)).val());
        phCalidad.push(row);
    }    

    //Conductividad muestra
    for (let i = 0; i < $("#numTomas").val(); i++) {
        row = new Array();
        row.push($("#con1" + i).val());
        row.push($("#con2" + i).val());
        row.push($("#con3" + i).val());
        row.push($("#conprom" + i).val()); 
        row.push($("#condStatus1" + (i+1)).val());       
        conductividad.push(row);
    }

    //Gasto muestra
    for (let i = 0; i < $("#numTomas").val(); i++) {
        row = new Array();
        row.push($("#gas1" + i).val());
        row.push($("#gas2" + i).val());
        row.push($("#gas3" + i).val());
        row.push($("#gasprom" + i).val());
        row.push($("#gastoStatus1" + (i+1)).val());
        gasto.push(row);
    }

    $.ajax({
        url: base_url + "/admin/campo/captura/setDataMuestreo", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            idSolicitud: $("#idSolicitud").val(),
            numTomas: $("#numTomas").val(),
            ph: ph,
            temperatura: temperatura,
            phCalidad: phCalidad,
            conductividad: conductividad,
            gasto: gasto,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            swal("Registro!", "Registro guardado correctamente!", "success");
        },
    });
} // return data;

function cancelaMuestra(){
    let resultado = window.confirm("¿Realmente desea cancelar este núm. muestra?");
    if(resultado === true){        
        let valor = $("#selectCancelMuestra").val();        

        if(valor == 0){
            alert("No se seleccionó ningún número de muestra");
        }else if(valor == 1){
            //PH
            $("#materia0").attr("disabled", true).val(null);
            $("#olor0").attr("disabled", true).val(null);
            $("#color0").attr("disabled", true).val(null);
            $("#phl10").attr("disabled", true).val(null);
            $("#phl20").attr("disabled", true).val(null);
            $("#phl30").attr("disabled", true).val(null);
            $("#phf0").attr("disabled", true)/* .val(null) */;
            $("#phprom0").val(null);
            $("#phStatus10").val(0);

            //Temperatura del agua
            $("#temp10").attr("disabled", true).val(null);
            $("#temp20").attr("disabled", true).val(null);
            $("#temp30").attr("disabled", true).val(null);
            $("#tempStatus11").val(0);

            //PH control calidad
            $("#phControlCalidadMuestra1").attr("disabled", true).val(null);
            $("#phCM11").attr("disabled", true).val(null);
            $("#phCM21").attr("disabled", true).val(null);
            $("#phCM31").attr("disabled", true).val(null);
            $("#phCMStatus11").val(0);

            //Conductividad
            $("#con10").attr("disabled", true).val(null);
            $("#con20").attr("disabled", true).val(null);
            $("#con30").attr("disabled", true).val(null);
            $("#condStatus11").val(0);

            //Gasto
            $("#gas10").attr("disabled", true).val(null);
            $("#gas20").attr("disabled", true).val(null);
            $("#gas30").attr("disabled", true).val(null);
            $("#gastoStatus11").val(0);

        }else if(valor == 2){
            //PH
            $("#materia1").attr("disabled", true).val(null);
            $("#olor1").attr("disabled", true).val(null);
            $("#color1").attr("disabled", true).val(null);
            $("#phl11").attr("disabled", true).val(null);
            $("#phl21").attr("disabled", true).val(null);
            $("#phl31").attr("disabled", true).val(null);
            $("#phf1").attr("disabled", true)/* .val(null) */;
            $("#phprom1").val(null);
            $("#phStatus11").val(0);

            //Temperatura del agua
            $("#temp11").attr("disabled", true).val(null);
            $("#temp21").attr("disabled", true).val(null);
            $("#temp31").attr("disabled", true).val(null);
            $("#tempStatus12").val(0);

            //PH control calidad
            $("#phControlCalidadMuestra2").attr("disabled", true).val(null);
            $("#phCM12").attr("disabled", true).val(null);
            $("#phCM22").attr("disabled", true).val(null);
            $("#phCM32").attr("disabled", true).val(null);
            $("#phCMStatus12").val(0);

            //Conductividad
            $("#con11").attr("disabled", true).val(null);
            $("#con21").attr("disabled", true).val(null);
            $("#con31").attr("disabled", true).val(null);
            $("#condStatus12").val(0);

            //Gasto
            $("#gas11").attr("disabled", true).val(null);
            $("#gas21").attr("disabled", true).val(null);
            $("#gas31").attr("disabled", true).val(null);
            $("#gastoStatus12").val(0);

        }else if(valor == 3){
            //PH
            $("#materia2").attr("disabled", true).val(null);
            $("#olor2").attr("disabled", true).val(null);
            $("#color2").attr("disabled", true).val(null);
            $("#phl12").attr("disabled", true).val(null);
            $("#phl22").attr("disabled", true).val(null);
            $("#phl32").attr("disabled", true).val(null);
            $("#phf2").attr("disabled", true)/* .val(null) */;
            $("#phprom2").val(null);
            $("#phStatus12").val(0);

            //Temperatura del agua
            $("#temp12").attr("disabled", true).val(null);
            $("#temp22").attr("disabled", true).val(null);
            $("#temp32").attr("disabled", true).val(null);
            $("#tempStatus13").val(0);

            //PH control calidad
            $("#phControlCalidadMuestra3").attr("disabled", true).val(null);
            $("#phCM13").attr("disabled", true).val(null);
            $("#phCM23").attr("disabled", true).val(null);
            $("#phCM33").attr("disabled", true).val(null);
            $("#phCMStatus13").val(0);

            //Conductividad
            $("#con12").attr("disabled", true).val(null);
            $("#con22").attr("disabled", true).val(null);
            $("#con32").attr("disabled", true).val(null);
            $("#condStatus13").val(0);

            //Gasto
            $("#gas12").attr("disabled", true).val(null);
            $("#gas22").attr("disabled", true).val(null);
            $("#gas32").attr("disabled", true).val(null);
            $("#gastoStatus13").val(0);

        }else if(valor == 4){
            //PH
            $("#materia3").attr("disabled", true).val(null);
            $("#olor3").attr("disabled", true).val(null);
            $("#color3").attr("disabled", true).val(null);
            $("#phl13").attr("disabled", true).val(null);
            $("#phl23").attr("disabled", true).val(null);
            $("#phl33").attr("disabled", true).val(null);
            $("#phf3").attr("disabled", true)/* .val(null) */;
            $("#phprom3").val(null);
            $("#phStatus13").val(0);

            //Temperatura del agua
            $("#temp13").attr("disabled", true).val(null);
            $("#temp23").attr("disabled", true).val(null);
            $("#temp33").attr("disabled", true).val(null);
            $("#tempStatus14").val(0);

            //PH control calidad
            $("#phControlCalidadMuestra4").attr("disabled", true).val(null);
            $("#phCM14").attr("disabled", true).val(null);
            $("#phCM24").attr("disabled", true).val(null);
            $("#phCM34").attr("disabled", true).val(null);
            $("#phCMStatus14").val(0);

            //Conductividad
            $("#con13").attr("disabled", true).val(null);
            $("#con23").attr("disabled", true).val(null);
            $("#con33").attr("disabled", true).val(null);
            $("#condStatus14").val(0);

            //Gasto
            $("#gas13").attr("disabled", true).val(null);
            $("#gas23").attr("disabled", true).val(null);
            $("#gas33").attr("disabled", true).val(null);
            $("#gastoStatus14").val(0);

        }else if(valor == 5){
            //PH
            $("#materia4").attr("disabled", true).val(null);
            $("#olor4").attr("disabled", true).val(null);
            $("#color4").attr("disabled", true).val(null);
            $("#phl14").attr("disabled", true).val(null);
            $("#phl24").attr("disabled", true).val(null);
            $("#phl34").attr("disabled", true).val(null);
            $("#phf4").attr("disabled", true)/* .val(null) */;
            $("#phprom4").val(null);
            $("#phStatus14").val(0);

            //Temperatura del agua
            $("#temp14").attr("disabled", true).val(null);
            $("#temp24").attr("disabled", true).val(null);
            $("#temp34").attr("disabled", true).val(null);
            $("#tempStatus15").val(0);

            //PH control calidad
            $("#phControlCalidadMuestra5").attr("disabled", true).val(null);
            $("#phCM15").attr("disabled", true).val(null);
            $("#phCM25").attr("disabled", true).val(null);
            $("#phCM35").attr("disabled", true).val(null);
            $("#phCMStatus15").val(0);

            //Conductividad
            $("#con14").attr("disabled", true).val(null);
            $("#con24").attr("disabled", true).val(null);
            $("#con34").attr("disabled", true).val(null);
            $("#condStatus15").val(0);

            //Gasto
            $("#gas14").attr("disabled", true).val(null);
            $("#gas24").attr("disabled", true).val(null);
            $("#gas34").attr("disabled", true).val(null);
            $("#gastoStatus15").val(0);

        }else if(valor == 6){
            //PH
            $("#materia5").attr("disabled", true).val(null);
            $("#olor5").attr("disabled", true).val(null);
            $("#color5").attr("disabled", true).val(null);
            $("#phl15").attr("disabled", true).val(null);
            $("#phl25").attr("disabled", true).val(null);
            $("#phl35").attr("disabled", true).val(null);
            $("#phf5").attr("disabled", true)/* .val(null) */;
            $("#phprom5").val(null);
            $("#phStatus15").val(0);

            //Temperatura del agua
            $("#temp15").attr("disabled", true).val(null);
            $("#temp25").attr("disabled", true).val(null);
            $("#temp35").attr("disabled", true).val(null);
            $("#tempStatus16").val(0);

            //PH control calidad
            $("#phControlCalidadMuestra6").attr("disabled", true).val(null);
            $("#phCM16").attr("disabled", true).val(null);
            $("#phCM26").attr("disabled", true).val(null);
            $("#phCM36").attr("disabled", true).val(null);
            $("#phCMStatus16").val(0);

            //Conductividad
            $("#con15").attr("disabled", true).val(null);
            $("#con25").attr("disabled", true).val(null);
            $("#con35").attr("disabled", true).val(null);
            $("#condStatus16").val(0);

            //Gasto
            $("#gas15").attr("disabled", true).val(null);
            $("#gas25").attr("disabled", true).val(null);
            $("#gas35").attr("disabled", true).val(null);
            $("#gastoStatus16").val(0);
        }
    }
}

function revierteMuestra(){
    let resultado = window.confirm("¿Realmente desea revertir este núm. muestra?");
    if(resultado === true){        
        let valor = $("#selectRevierteMuestra").val();        

        if(valor == 0){
            alert("No se seleccionó ningún número de muestra");
        }else if(valor == 1){
            //PH
            $("#materia0").attr("disabled", false).val(0);
            $("#olor0").attr("disabled", false).val(0);
            $("#color0").attr("disabled", false).val(0);
            $("#phl10").attr("disabled", false).val(null);
            $("#phl20").attr("disabled", false).val(null);
            $("#phl30").attr("disabled", false).val(null);
            $("#phf0").attr("disabled", false)/* .val(null) */;
            $("#phprom0").val(null);
            $("#phStatus10").val(1);

            //Temperatura del agua
            $("#temp10").attr("disabled", false).val(null);
            $("#temp20").attr("disabled", false).val(null);
            $("#temp30").attr("disabled", false).val(null);
            $("#tempStatus11").val(1);

            //PH control calidad
            $("#phControlCalidadMuestra1").attr("disabled", false).val(0);
            $("#phCM11").attr("disabled", false).val(null);
            $("#phCM21").attr("disabled", false).val(null);
            $("#phCM31").attr("disabled", false).val(null);
            $("#phCMStatus11").val(1);

            //Conductividad
            $("#con10").attr("disabled", false).val(null);
            $("#con20").attr("disabled", false).val(null);
            $("#con30").attr("disabled", false).val(null);
            $("#condStatus11").val(1);

            //Gasto
            $("#gas10").attr("disabled", false).val(null);
            $("#gas20").attr("disabled", false).val(null);
            $("#gas30").attr("disabled", false).val(null);
            $("#gastoStatus11").val(1);

        }else if(valor == 2){
            //PH
            $("#materia1").attr("disabled", false).val(0);
            $("#olor1").attr("disabled", false).val(0);
            $("#color1").attr("disabled", false).val(0);
            $("#phl11").attr("disabled", false).val(null);
            $("#phl21").attr("disabled", false).val(null);
            $("#phl31").attr("disabled", false).val(null);
            $("#phf1").attr("disabled", false)/* .val(null) */;
            $("#phprom1").val(null);
            $("#phStatus11").val(1);

            //Temperatura del agua
            $("#temp11").attr("disabled", false).val(null);
            $("#temp21").attr("disabled", false).val(null);
            $("#temp31").attr("disabled", false).val(null);
            $("#tempStatus12").val(1);

            //PH control calidad
            $("#phControlCalidadMuestra2").attr("disabled", false).val(0);
            $("#phCM12").attr("disabled", false).val(null);
            $("#phCM22").attr("disabled", false).val(null);
            $("#phCM32").attr("disabled", false).val(null);
            $("#phCMStatus12").val(1);

            //Conductividad
            $("#con11").attr("disabled", false).val(null);
            $("#con21").attr("disabled", false).val(null);
            $("#con31").attr("disabled", false).val(null);
            $("#condStatus12").val(1);

            //Gasto
            $("#gas11").attr("disabled", false).val(null);
            $("#gas21").attr("disabled", false).val(null);
            $("#gas31").attr("disabled", false).val(null);
            $("#gastoStatus12").val(1);

        }else if(valor == 3){
            //PH
            $("#materia2").attr("disabled", false).val(0);
            $("#olor2").attr("disabled", false).val(0);
            $("#color2").attr("disabled", false).val(0);
            $("#phl12").attr("disabled", false).val(null);
            $("#phl22").attr("disabled", false).val(null);
            $("#phl32").attr("disabled", false).val(null);
            $("#phf2").attr("disabled", false)/* .val(null) */;
            $("#phprom2").val(null);
            $("#phStatus12").val(1);

            //Temperatura del agua
            $("#temp12").attr("disabled", false).val(null);
            $("#temp22").attr("disabled", false).val(null);
            $("#temp32").attr("disabled", false).val(null);
            $("#tempStatus13").val(1);

            //PH control calidad
            $("#phControlCalidadMuestra3").attr("disabled", false).val(0);
            $("#phCM13").attr("disabled", false).val(null);
            $("#phCM23").attr("disabled", false).val(null);
            $("#phCM33").attr("disabled", false).val(null);
            $("#phCMStatus13").val(1);

            //Conductividad
            $("#con12").attr("disabled", false).val(null);
            $("#con22").attr("disabled", false).val(null);
            $("#con32").attr("disabled", false).val(null);
            $("#condStatus13").val(1);

            //Gasto
            $("#gas12").attr("disabled", false).val(null);
            $("#gas22").attr("disabled", false).val(null);
            $("#gas32").attr("disabled", false).val(null);
            $("#gastoStatus13").val(1);

        }else if(valor == 4){
            //PH
            $("#materia3").attr("disabled", false).val(0);
            $("#olor3").attr("disabled", false).val(0);
            $("#color3").attr("disabled", false).val(0);
            $("#phl13").attr("disabled", false).val(null);
            $("#phl23").attr("disabled", false).val(null);
            $("#phl33").attr("disabled", false).val(null);
            $("#phf3").attr("disabled", false)/* .val(null) */;
            $("#phprom3").val(null);
            $("#phStatus13").val(1);

            //Temperatura del agua
            $("#temp13").attr("disabled", false).val(null);
            $("#temp23").attr("disabled", false).val(null);
            $("#temp33").attr("disabled", false).val(null);
            $("#tempStatus14").val(1);

            //PH control calidad
            $("#phControlCalidadMuestra4").attr("disabled", false).val(0);
            $("#phCM14").attr("disabled", false).val(null);
            $("#phCM24").attr("disabled", false).val(null);
            $("#phCM34").attr("disabled", false).val(null);
            $("#phCMStatus14").val(1);

            //Conductividad
            $("#con13").attr("disabled", false).val(null);
            $("#con23").attr("disabled", false).val(null);
            $("#con33").attr("disabled", false).val(null);
            $("#condStatus14").val(1);

            //Gasto
            $("#gas13").attr("disabled", false).val(null);
            $("#gas23").attr("disabled", false).val(null);
            $("#gas33").attr("disabled", false).val(null);
            $("#gastoStatus14").val(1);

        }else if(valor == 5){
            //PH
            $("#materia4").attr("disabled", false).val(0);
            $("#olor4").attr("disabled", false).val(0);
            $("#color4").attr("disabled", false).val(0);
            $("#phl14").attr("disabled", false).val(null);
            $("#phl24").attr("disabled", false).val(null);
            $("#phl34").attr("disabled", false).val(null);
            $("#phf4").attr("disabled", false)/* .val(null) */;
            $("#phprom4").val(null);
            $("#phStatus14").val(1);

            //Temperatura del agua
            $("#temp14").attr("disabled", false).val(null);
            $("#temp24").attr("disabled", false).val(null);
            $("#temp34").attr("disabled", false).val(null);
            $("#tempStatus15").val(1);

            //PH control calidad
            $("#phControlCalidadMuestra5").attr("disabled", false).val(0);
            $("#phCM15").attr("disabled", false).val(null);
            $("#phCM25").attr("disabled", false).val(null);
            $("#phCM35").attr("disabled", false).val(null);
            $("#phCMStatus15").val(1);

            //Conductividad
            $("#con14").attr("disabled", false).val(null);
            $("#con24").attr("disabled", false).val(null);
            $("#con34").attr("disabled", false).val(null);
            $("#condStatus15").val(1);

            //Gasto
            $("#gas14").attr("disabled", false).val(null);
            $("#gas24").attr("disabled", false).val(null);
            $("#gas34").attr("disabled", false).val(null);
            $("#gastoStatus15").val(1);
            
        }else if(valor == 6){
            //PH
            $("#materia5").attr("disabled", false).val(0);
            $("#olor5").attr("disabled", false).val(0);
            $("#color5").attr("disabled", false).val(0);
            $("#phl15").attr("disabled", false).val(null);
            $("#phl25").attr("disabled", false).val(null);
            $("#phl35").attr("disabled", false).val(null);
            $("#phf5").attr("disabled", false)/* .val(null) */;
            $("#phprom5").val(null);
            $("#phStatus15").val(1);

            //Temperatura del agua
            $("#temp15").attr("disabled", false).val(null);
            $("#temp25").attr("disabled", false).val(null);
            $("#temp35").attr("disabled", false).val(null);
            $("#tempStatus16").val(1);

            //PH control calidad
            $("#phControlCalidadMuestra6").attr("disabled", false).val(0);
            $("#phCM16").attr("disabled", false).val(null);
            $("#phCM26").attr("disabled", false).val(null);
            $("#phCM36").attr("disabled", false).val(null);
            $("#phCMStatus16").val(1);

            //Conductividad
            $("#con15").attr("disabled", false).val(null);
            $("#con25").attr("disabled", false).val(null);
            $("#con35").attr("disabled", false).val(null);
            $("#condStatus16").val(1);

            //Gasto
            $("#gas15").attr("disabled", false).val(null);
            $("#gas25").attr("disabled", false).val(null);
            $("#gas35").attr("disabled", false).val(null);
            $("#gastoStatus16").val(1);
        }
    }
}

function setDataCompuesto(){    
    $.ajax({
        url: base_url + "/admin/campo/captura/setDataCompuesto", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            idSolicitud: $("#idSolicitud").val(),
            aforoCompuesto: $("#aforoCompuesto").val(),
            conTratamientoCompuesto: $("#conTratamientoCompuesto").val(),
            tipoTratamientoCompuesto: $("#tipoTratamientoCompuesto").val(),
            procedimientoCompuesto: $("#procedimientoCompuesto").val(),
            obsCompuesto: $("#observacionCompuesto").val(),
            //obs_sol: $("#").val(),
            volCalculadoComp: $("#volCalculado").val(),
            phMuestraCompuesto: $("#phMuestraCompuesto").val(),
            valTempCompuesto: $("#valTemp").val(),
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

function selectedOption() {
    //Obtiene el valor de la opción seleccionada
    let selectedOption = document.getElementById("phTrazable1").value;

    return selectedOption;
}

//Función para generar la tabla Qi, Qt, Qi/Qt, Vmc, Vmsi
function btnGenerar() {        
    Number.prototype.toFixedDown = function (digits) {
        var re = new RegExp("(\\d+\\.\\d{" + digits + "})(\\d)"),
            m = this.toString().match(re);
        return m ? parseFloat(m[1]) : this.valueOf();
    };  
    
    let tabla = document.getElementById('muestrasQi');
    let tab = '';        
    let qt1; 
    let qt2;
    let qt3;
    let qt4;
    let qt5;
    let qt6;   
    let qi_qt1;
    let qi_qt2;
    let qi_qt3;
    let qi_qt4;
    let qi_qt5;
    let qi_qt6;
    let vmc1;
    let vmc2;
    let vmc3;
    let vmc4;
    let vmc5;
    let vmc6;
    let vmsi1;
    let vmsi2;
    let vmsi3;
    let vmsi4;
    let vmsi5;
    let vmsi6;
    let volCalculado = parseFloat(document.getElementById('volCalculado').value);
    //console.log("Valor de volCalculado: " + volCalculado);

    tab += '<table class="table" id="muestrasQi">';
        tab += '    <thead class="thead-dark">';
        tab += '        <tr>';
        tab += '            <th>Núm muestra</th>';
        tab += '            <th>Qi</th>';
        tab += '            <th>Qt</th>';
        tab += '            <th>Qi/Qt</th>';
        tab += '            <th>Vmc</th>';
        tab += '            <th>Vmsi</th>';
        tab += '        </tr>';
        tab += '    </thead>';
        tab += '    <tbody>';    

    /* if(!isNaN(gasprom0) && !isNaN(gasprom1) && !isNaN(gasprom2) && !isNaN(gasprom3)){ */
        
        if((gasprom0 === null) || (gasprom0 == 0)){
            gasprom0 = 0;
            qt1 = 0;
            qi_qt1 = 0;
            vmc1 = 0;
            vmsi1 = 0;
        }else{
            qt1 = gasprom0 + gasprom1 + gasprom2 + gasprom3 + gasprom4 + gasprom5;
            qi_qt1 = gasprom0 / qt1;            
            vmc1 = volCalculado;            
            vmsi1 = qi_qt1 * volCalculado;
        }        

        if((gasprom1 === null) || (gasprom1 == 0)){
            gasprom1 = 0;
            qt2 = 0;
            qi_qt2 = 0;
            vmc2 = 0;
            vmsi2 = 0;
        }else{
            qt2 = gasprom0 + gasprom1 + gasprom2 + gasprom3 + gasprom4 + gasprom5;
            qi_qt2 = gasprom1 / qt2;
            vmc2 = volCalculado;
            vmsi2 = qi_qt2 * volCalculado;
        }        
        
        if((gasprom2 === null) || (gasprom2 == 0)){
            gasprom2 = 0;
            qt3 = 0;
            qi_qt3 = 0;
            vmc3 = 0;
            vmsi3 = 0;
        }else{
            qt3 = gasprom0 + gasprom1 + gasprom2 + gasprom3 + gasprom4 + gasprom5;
            qi_qt3 = gasprom2 / qt3;
            vmc3 = volCalculado;
            vmsi3 = qi_qt3 * volCalculado;
        }

        if((gasprom3 === null) || (gasprom3 == 0)){
            gasprom3 = 0;
            qt4 = 0;
            qi_qt4 = 0;
            vmc4 = 0;
            vmsi4 = 0;
            
        }else{
            qt4 = gasprom0 + gasprom1 + gasprom2 + gasprom3 + gasprom4 + gasprom5;
            qi_qt4 = gasprom3 / qt4;
            vmc4 = volCalculado
            vmsi4 = qi_qt4 * volCalculado;
        }        
            
        //FILA 1
        if($("#numTomas").val() >= 1){
            tab += '<tr>';
            tab += '    <td>'+1+'</td>';
            tab += '    <td>'+gasprom0+'</td>';
            tab += '    <td>'+qt1.toFixedDown(3)+'</td>';
            tab += '    <td>'+qi_qt1.toFixedDown(3)+'</td>';
            tab += '    <td>'+vmc1+'</td>';  
            tab += '    <td>'+vmsi1.toFixedDown(3)+'</td>';            
            tab += '</tr>';
        }

        //FILA 2        
        if($("#numTomas").val() >= 2){
            tab += '<tr>';
            tab += '    <td>'+2+'</td>';
            tab += '    <td>'+gasprom1+'</td>';
            tab += '    <td>'+qt2.toFixedDown(3)+'</td>';
            tab += '    <td>'+qi_qt2.toFixedDown(3)+'</td>';
            tab += '    <td>'+vmc2+'</td>';  
            tab += '    <td>'+vmsi2.toFixedDown(3)+'</td>';            
            tab += '</tr>';
        }

        if($("#numTomas").val() >= 4){
            //FILA 3
            tab += '<tr>';
            tab += '    <td>'+3+'</td>';
            tab += '    <td>'+gasprom2+'</td>';
            tab += '    <td>'+qt3.toFixedDown(3)+'</td>';
            tab += '    <td>'+qi_qt3.toFixedDown(3)+'</td>';
            tab += '    <td>'+vmc3+'</td>';
            tab += '    <td>'+vmsi3.toFixedDown(3)+'</td>';  
            tab += '</tr>';

            //FILA 4
            tab += '<tr>';
            tab += '    <td>'+4+'</td>';
            tab += '    <td>'+gasprom3+'</td>';
            tab += '    <td>'+qt4.toFixedDown(3)+'</td>';
            tab += '    <td>'+qi_qt4.toFixedDown(3)+'</td>';
            tab += '    <td>'+vmc4+'</td>';
            tab += '    <td>'+vmsi4.toFixedDown(3)+'</td>';  
            tab += '</tr>';
        }
    /* } */
    
    //FILA 5
    if($("#numTomas").val() >= 5){
        if((gasprom4 === null) || (gasprom4 == 0)){
            gasprom4 = 0;
            qt5 = 0;
            qi_qt5 = 0;
            vmc5 = 0;
            vmsi5 = 0;
        }else{
            qt5 = gasprom0 + gasprom1 + gasprom2 + gasprom3 + gasprom4 + gasprom5;
            qi_qt5 = gasprom4 / qt5;
            vmc5 = volCalculado;
            vmsi5 = qi_qt5 * volCalculado;
        }        
        
        tab += '<tr>';
        tab += '    <td>'+5+'</td>';
        tab += '    <td>'+gasprom4+'</td>';
        tab += '    <td>'+qt5.toFixedDown(3)+'</td>';
        tab += '    <td>'+qi_qt5.toFixedDown(3)+'</td>';
        tab += '    <td>'+vmc5+'</td>';
        tab += '    <td>'+vmsi5.toFixedDown(3)+'</td>';  
        tab += '</tr>';
    }

    //FILA 6
    if($("#numTomas").val() == 6){
        if((gasprom5 === null) || (gasprom5 == 0)){
            gasprom5 = 0;
            qt6 = 0;
            qi_qt6 = 0;
            vmc6 = 0;
            vmsi6 = 0;
        }else{
            qt6 = gasprom0 + gasprom1 + gasprom2 + gasprom3 + gasprom4 + gasprom5;
            qi_qt6 = gasprom5 / qt6;
            vmc6 = volCalculado;
            vmsi6 = qi_qt6 * volCalculado;
        }        
        
        tab += '<tr>';
        tab += '    <td>'+6+'</td>';
        tab += '    <td>'+gasprom5+'</td>';
        tab += '    <td>'+qt6.toFixedDown(3)+'</td>';
        tab += '    <td>'+qi_qt6.toFixedDown(3)+'</td>';
        tab += '    <td>'+vmc6+'</td>';
        tab += '    <td>'+vmsi6.toFixedDown(3)+'</td>';  
        tab += '</tr>';
    }

    tab += '    </tbody>';
    tab += '</table>';
    tabla.innerHTML = tab;    
}

/* //Función para generar la tabla Qi, Qt, Qi/Qt, Vmc, Vmsi
function btnGenerar() 
{
    Number.prototype.toFixedDown = function (digits) {
        var re = new RegExp("(\\d+\\.\\d{" + digits + "})(\\d)"),
            m = this.toString().match(re);
        return m ? parseFloat(m[1]) : this.valueOf();
    };  
    
    let tabla = document.getElementById('muestrasQi');
    let tab = '';        
    let qt;    
    let qi_qt1;
    let qi_qt2;
    let qi_qt3;
    let qi_qt4;
    let qi_qt5;
    let qi_qt6;
    let vmsi1;
    let vmsi2;
    let vmsi3;
    let vmsi4;
    let vmsi5;
    let vmsi6;
    let volCalculado = parseFloat(document.getElementById('volCalculado').value);
    //console.log("Valor de volCalculado: " + volCalculado);

    tab += '<table class="table" id="muestrasQi">';
          tab += '    <thead class="thead-dark">';
          tab += '        <tr>';
          tab += '            <th>Núm muestra</th>';
          tab += '            <th>Qi</th>';
          tab += '            <th>Qt</th>';
          tab += '            <th>Qi/Qt</th>';
          tab += '            <th>Vmc</th>';
          tab += '            <th>Vmsi</th>';
          tab += '        </tr>';
          tab += '    </thead>';
          tab += '    <tbody>';

    qt = gasprom0 + gasprom1 + gasprom2 + gasprom3 + gasprom4 + gasprom5;

    if(!isNaN(gasprom0) && !isNaN(gasprom1) && !isNaN(gasprom2) && !isNaN(gasprom3)){
        
        qi_qt1 = gasprom0 / qt;
        vmsi1 = qi_qt1 * volCalculado;

        qi_qt2 = gasprom1 / qt;
        qi_qt3 = gasprom2 / qt;
        qi_qt4 = gasprom3 / qt;

        vmsi2 = qi_qt2 * volCalculado;
        vmsi3 = qi_qt3 * volCalculado;
        vmsi4 = qi_qt4 * volCalculado;
            
        //FILA 1
        tab += '<tr>';
        tab += '    <td>'+1+'</td>';
        tab += '    <td>'+gasprom0+'</td>';
        tab += '    <td>'+qt.toFixedDown(3)+'</td>';
        tab += '    <td>'+qi_qt1.toFixedDown(3)+'</td>';
        tab += '    <td>'+volCalculado+'</td>';  
        tab += '    <td>'+vmsi1.toFixedDown(3)+'</td>';            
        tab += '</tr>';

        //FILA 2        
        tab += '<tr>';
        tab += '    <td>'+2+'</td>';
        tab += '    <td>'+gasprom1+'</td>';
        tab += '    <td>'+qt.toFixedDown(3)+'</td>';
        tab += '    <td>'+qi_qt2.toFixedDown(3)+'</td>';
        tab += '    <td>'+volCalculado+'</td>';  
        tab += '    <td>'+vmsi2.toFixedDown(3)+'</td>';            
        tab += '</tr>';

        //FILA 3
        tab += '<tr>';
        tab += '    <td>'+3+'</td>';
        tab += '    <td>'+gasprom2+'</td>';
        tab += '    <td>'+qt.toFixedDown(3)+'</td>';
        tab += '    <td>'+qi_qt3.toFixedDown(3)+'</td>';
        tab += '    <td>'+volCalculado+'</td>';
        tab += '    <td>'+vmsi3.toFixedDown(3)+'</td>';  
        tab += '</tr>';

        //FILA 4
        tab += '<tr>';
        tab += '    <td>'+4+'</td>';
        tab += '    <td>'+gasprom3+'</td>';
        tab += '    <td>'+qt.toFixedDown(3)+'</td>';
        tab += '    <td>'+qi_qt4.toFixedDown(3)+'</td>';
        tab += '    <td>'+volCalculado+'</td>';
        tab += '    <td>'+vmsi4.toFixedDown(3)+'</td>';  
        tab += '</tr>';
    }

    //console.log("valor de gasprom4: " + gasprom4);
    //console.log("valor de gasprom5: " + gasprom5);
    
    //FILA 5
    if(temp1 >= 0){
        qi_qt5 = gasprom4 / qt;
        vmsi5 = qi_qt5 * volCalculado;
        
        tab += '<tr>';
        tab += '    <td>'+5+'</td>';
        tab += '    <td>'+gasprom4+'</td>';
        tab += '    <td>'+qt.toFixedDown(3)+'</td>';
        tab += '    <td>'+qi_qt5.toFixedDown(3)+'</td>';
        tab += '    <td>'+volCalculado+'</td>';
        tab += '    <td>'+vmsi5.toFixedDown(3)+'</td>';  
        tab += '</tr>';
    }

    //FILA 6
    if(temp2 >=0){
        qi_qt6 = gasprom5 / qt;
        vmsi6 = qi_qt6 * volCalculado;
        
        tab += '<tr>';
        tab += '    <td>'+6+'</td>';
        tab += '    <td>'+gasprom5+'</td>';
        tab += '    <td>'+qt.toFixedDown(3)+'</td>';
        tab += '    <td>'+qi_qt6.toFixedDown(3)+'</td>';
        tab += '    <td>'+volCalculado+'</td>';
        tab += '    <td>'+vmsi6.toFixedDown(3)+'</td>';  
        tab += '</tr>';
    }

    tab += '    </tbody>';
    tab += '</table>';
    tabla.innerHTML = tab;        
} */

//-----------------------------------------------------------------------------------------------------------------------------

//Arreglo que almacena únicamente las fechas de los inputs en PH Muestra
let fechas = moment(new Array(6));

//Arreglo que almacena únicamente las horas de los inputs en PH Muestra
let horas = new Array(6);

//FUNCIÓN EN PROCESO
function validacionFechaMuestreo(fechaLec){
    let t = document.getElementById("phMuestra");
    
    //Obtiene el valor del input de fecha
    let fecha = document.getElementById(fechaLec).value;        

    let fechaIngresada = moment(fecha, 'YYYY-MM-DDTHH:mm:ss');
    //console.log("Objeto moment fechaIngresada: " + moment.isMoment(fechaIngresada));
    //console.log("Valor de fechaIngresada: " + fechaIngresada);

    //let fechaIngresada = moment(fecha).format('YYYY-MM-DDTHH:mm:ss');
    let soloFecha = moment(fechaIngresada).format('YYYY-MM-DD');
    let soloHora = moment(fechaIngresada).format('HH:mm:ss');
    
    console.log("Valor de fechaLec: " + fechaLec);

    //Llena los arreglos fechas y horas con los valores introducidos en los inputs date-local    
    if(fechaLec == "phf0"){
        fechas[0] = soloFecha;
        horas[0] = soloHora;

    }else if(fechaLec == "phf1"){
        //fechas[1] = soloFecha;
        //horas[1] = soloHora;
        
        //let fecha1 = moment(fechas[0]);
        //let fecha2 = moment(fechas[1]);

        //console.log("Objeto moment: " + moment.isMoment(fechas[1]));

        //console.log("Valor de arreglo fechas[0]: " + fecha1);
        //console.log("Valor de arreglo fechas[1]: " + fechas[1]);

        /*if(moment(fecha1).isBefore(fechas[1])){
            console.log("Lo conseguiste");
            t.rows[1].setAttribute("class", "bg-success");
        }*/

    }else if(fechaLec == "phf2"){
        //fechas[2] = soloFecha;
        //horas[2] = soloHora;

    }else if(fechaLec == "phf3"){
        //fechas[3] = soloFecha;
        //horas[3] = soloHora;

    }else if(fechaLec == "phf4"){
        //fechas[4] = soloFecha;
        //horas[4] = soloHora;

    }else if(fechaLec == "phf5"){
        //fechas[5] = soloFecha;
        //horas[5] = soloHora;
    }
    //----------------------------------------------------------------------------------------------

    //console.log(soloFecha);
    //console.log(soloHora);
    //t.rows[1].setAttribute("class", "bg-danger");    
}

//------------------------------------------------------------------------------------------------------------------------------

//FUNCIÓN EN CONSTRUCCIÓN; DATOS GENERALES.-REFLEJADO DE VALIDACIÓN AL CAMBIAR DE SELECT
function valoresPhTrazables(){    
    
    //console.log("Estás dentro de valoresPhTrazables");
    var ddl = document.getElementById("phTrazable1");    
    var selectedValue = ddl.options[ddl.selectedIndex].value;    
    //console.log("Valor seleccionado: " + selectedValue);
    var texto = selectedValue.text;
    //console.log("Valor texto: " + texto);
}

function validacionLatitud(latitudCaptura){
    latitud = document.getElementById(latitudCaptura).value;    

    if(latitud.length > 10){
        alert("La latitud no puede tener más de 10 dígitos");
    }
}

function validacionLongitud(longitudCaptura){
    longitud = document.getElementById(longitudCaptura).value;    

    if(longitud.length > 10){
        alert("La longitud no puede tener más de 10 dígitos");
    }
}

function validacionAltitud(altitudCaptura){
    altitud = document.getElementById(altitudCaptura).value;    

    if(altitud.length > 10){
        alert("La altitud no puede tener más de 10 dígitos");
    }
}
