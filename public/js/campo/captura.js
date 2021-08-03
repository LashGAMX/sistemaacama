var base_url = "https://dev.sistemaacama.com.mx";
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
                "phTLote1"
            );
        }                

        $("#phCalidad1")
            .val($("#phTrazable1").val())
            .attr("disabled", "disabled");
            //idPh, nombre, marca, lote
            setPhCalidad($("#phCalidad1").val(), "phCNombre1", "phCMarca1", "phCLote1");

            if ($("#phTrazable1").val() != "0" && $("#phTrazable2").val() != "0" && $("#phTrazable1").val() == $("#phTrazable2").val()) {
                //alert("Los valores de Ph trazable no pueden ser los mismos");
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
            setPhTrazable(
                $("#phTrazable2").val(),
                "phTNombre2",
                "phTMarca2",
                "phTLote2"
            );
        }        

        $("#phCalidad2")
            .val($("#phTrazable2").val())
            .attr("disabled", "disabled");
            setPhCalidad($("#phCalidad2").val(), "phCNombre2", "phCMarca2", "phCLote2");

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
        setPhCalidad(
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
    let temp1 = parseFloat($("#tempAmbiente").val());
    let temp2 = parseFloat($("#tempBuffer").val());

    let temp01 = document.getElementById(temperaturaAmbiente).value;
    let temp02 = document.getElementById(temperaturaBuffer).value;

    if(temp01.length > 5){
        alert("La temperatura ambiente no puede tener más de dos decimales");
    }

    /*if(temp02.length > 5){
        alert("La temperatura búffer no puede tener más de dos decimales");
    }*/

    if (temp1 - temp2 > 5 || temp1 - temp2 < -5) {
        inputBorderColor("tempAmbiente", "rojo");
        inputBorderColor("tempBuffer", "rojo");
        alert("La diferencia de temperatura ambiente y temperatura búffer es mayor a 5 grados");
    } else {
        inputBorderColor("tempAmbiente", "verde");
        inputBorderColor("tempBuffer", "verde");
    }
}

function valPhTrazable(lec1, lec2, lec3, estado, phTrazable) {
    var select = document.getElementById(phTrazable);  
    //var value = select.value;
    var text = select.options[select.selectedIndex].innerText;
    text = parseFloat(text);
    //console.log("Valor de phT: " + text);
        
    let sw = false;
    let sw1 = true;
    let sw2 = true;
    let sw3 = true;
    let std = document.getElementById(estado);
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

    // val if 0.003

    r1 = l1 - l2;
    r2 = l1 - l3;

    r3 = l2 - l1;
    r4 = l2 - l3;

    r5 = l3 - l1;
    r6 = l3 - l2;

    if (r1 <= -0.03 || r1 >= 0.03) {
        sw = false;
    } else {
        sw = true;
    }

    if (r2 <= -0.03 || r2 >= 0.03) {
        sw = false;
    } else {
        sw = true;
    }

    if (r3 <= -0.03 || r3 >= 0.03) {
        sw = false;
    } else {
        sw = true;
    }

    if (r4 <= -0.03 || r4 >= 0.03) {
        sw = false;
    } else {
        sw = true;
    }
    if (r5 <= -0.03 || r5 >= 0.03) {
        sw = false;
    } else {
        sw = true;
    }

    if (r6 <= -0.03 || r6 >= 0.03) {
        sw = false;
    } else {
        sw = true;
    }

    if(sw == true){
        console.log("Aceptado")
    }else{
        console.log("Rechazado")
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

function valPhTrazable2(lec1, lec2, lec3, estado, phTrazable) {
    var select = document.getElementById(phTrazable);  
    //var value = select.value;
    var text = select.options[select.selectedIndex].innerText;
    text = parseFloat(text);
    //console.log("Valor de phT: " + text);
        
    let sw = false;
    let sw1 = true;
    let sw2 = true;
    let sw3 = true;
    let std = document.getElementById(estado);
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

    // val if 0.003

    r1 = l1 - l2;
    r2 = l1 - l3;

    r3 = l2 - l1;
    r4 = l2 - l3;

    r5 = l3 - l1;
    r6 = l3 - l2;

    if (r1 <= -0.03 || r1 >= 0.03) {
        sw = false;
    } else {
        sw = true;
    }

    if (r2 <= -0.03 || r2 >= 0.03) {
        sw = false;
    } else {
        sw = true;
    }

    if (r3 <= -0.03 || r3 >= 0.03) {
        sw = false;
    } else {
        sw = true;
    }

    if (r4 <= -0.03 || r4 >= 0.03) {
        sw = false;
    } else {
        sw = true;
    }
    if (r5 <= -0.03 || r5 >= 0.03) {
        sw = false;
    } else {
        sw = true;
    }

    if (r6 <= -0.03 || r6 >= 0.03) {
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

    if(v1 < -0.5 || v1 > 0.5){
        sw1 = false;
    }else{
        sw1 = true;
    }

    if(v2 < -0.5 || v2 > 0.5){
        sw1 = false;
    }else{
        sw1 = true;
    }

    if(v3 < -0.5 || v3 > 0.5){
        sw2 = false;
    }else{
        sw2 = true;
    }

    if(v4 < -0.5 || v4 > 0.5){
        sw2 = false;
    }else{
        sw2 = true;
    }

    if(v5 < -0.5 || v5 > 0.5){
        sw3 = false;
    }else{
        sw3 = true;
    }

    if(v6 < -0.5 || v6 > 0.5){
        sw3 = false;
    }else{
        sw3 = true;
    }

    if(sw1 == true && sw2 == true && sw3 == true){
        sw = true;
    }else{
        sw = false;
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

function valPhCalidad(lec1, lec2, lec3, estado, prom) {
    let sw = false;
    let p = document.getElementById(prom);
    let std = document.getElementById(estado);
    let l1 = parseFloat(document.getElementById(lec1).value);
    let l2 = parseFloat(document.getElementById(lec2).value);
    let l3 = parseFloat(document.getElementById(lec3).value);
    let t = document.getElementById("phControlCalidad");

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

    if (r1 <= -0.03 || r1 >= 0.03) {
        sw = false;
    } else {
        sw = true;
    }

    if (r2 <= -0.03 || r2 >= 0.03) {
        sw = false;
    } else {
        sw = true;
    }

    if (r3 <= -0.03 || r3 >= 0.03) {
        sw = false;
    } else {
        sw = true;
    }

    if (r4 <= -0.03 || r4 >= 0.03) {
        sw = false;
    } else {
        sw = true;
    }
    if (r5 <= -0.03 || r5 >= 0.03) {
        sw = false;
    } else {
        sw = true;
    }

    if (r6 <= -0.03 || r6 >= 0.03) {
        sw = false;
    } else {
        sw = true;
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
    p.value = ((l1 + l2 + l3) / 3).toFixed(3);
}

function valConTrazable(lec1, lec2, lec3, estado) {
    let t = document.getElementById("tableConTrazable");
    let con = parseFloat($("#conTrazable option:selected").text());
    let porcentaje = (con * 5) / 100;

    let sw = true;
    let std = document.getElementById(estado);
    // let p = document.getElementById(prom);
    let l1 = parseFloat(document.getElementById(lec1).value);
    let l2 = parseFloat(document.getElementById(lec2).value);
    let l3 = parseFloat(document.getElementById(lec3).value);

    if (l1 - con <= porcentaje * -1 || l1 - con >= porcentaje) {
        sw = false;
    }
    if (l2 - con <= porcentaje * -1 || l2 - con >= porcentaje) {
        sw = false;
    }
    if (l3 - con <= porcentaje * -1 || l3 - con >= porcentaje) {
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

    let sw = true;
    let std = document.getElementById(estado);
    let p = document.getElementById(prom);
    let l1 = parseFloat(document.getElementById(lec1).value);
    let l2 = parseFloat(document.getElementById(lec2).value);
    let l3 = parseFloat(document.getElementById(lec3).value);

    if (l1 - con < porcentaje * -1 || l1 - con > porcentaje) {
        sw = false;
    }
    if (l2 - con < porcentaje * -1 || l2 - con > porcentaje) {
        sw = false;
    }
    if (l3 - con < porcentaje * -1 || l3 - con > porcentaje) {
        sw = false;
    }

    if (sw == true) {
        std.value = "Aceptado";
        t.rows[1].setAttribute("class", "bg-success");
    } else {
        std.value = "Rechazado";
        t.rows[1].setAttribute("class", "bg-danger");
    }

    p.value = ((l1 + l2 + l3) / 3).toFixed(3);
}

function valPendiente(valor, criterio) {
    let sw = true;
    let t = document.getElementById("tableCalPendiente");
    let v = parseFloat(document.getElementById(valor).value);
    let c = document.getElementById(criterio);
    //let c = parseFloat(document.getElementById(criterio).value);

    if (v < 95 || v > 105) {
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

    // Val if rango 4 - 9

    if (l1 > 4 && l1 < 9) {
        sw = true;
    } else {
        sw = false;        
    }

    if (l2 > 4 && l2 < 9) {
        sw = true;
    } else {
        sw = false;
    }

    if (l3 > 4 && l3 < 9) {
        sw = true;
    } else {
        sw = false;
    }

    // val if 0.003

    r1 = (l1 - l2).toFixed(3);
    r2 = (l1 - l3).toFixed(3);
    r3 = (l2 - l1).toFixed(3);
    r4 = (l2 - l3).toFixed(3);
    r5 = (l3 - l1).toFixed(3);
    r6 = (l3 - l2).toFixed(3);
    
    if (r1 < -0.03 || r1 > 0.03) {
        sw1 = false;        
    } else {
        if (r1 === 0.03 || r1 === -0.03 || r1 === 0.02999999999999936 || r1 === -0.02999999999999936) {
            sw1 = false;            
        } else {
            sw1 = true;
        }
    }

    if (r2 < -0.03 || r2 > 0.03) {
        sw2 = false;          
    } else {
        if (r2 === 0.03 || r2 === -0.03 || r2 === 0.02999999999999936 || r2 === -0.02999999999999936) {
            sw2 = false;            
        } else {
            sw2 = true;
        }
    }

    if (r3 < -0.03 || r3 > 0.03) {
        sw3 = false;        
    } else {
        if (r3 === 0.03 || r3 === -0.03 || r3 === 0.02999999999999936 || r3 === -0.02999999999999936) {
            sw3 = false;            
        } else {
            sw3 = true;
        }
    }

    if (r4 < -0.03 || r4 > 0.03) {
        sw4 = false;        
    } else {
        if (r4 === 0.03 || r4 === -0.03 || r4 === 0.02999999999999936 || r4 === -0.02999999999999936) {
            sw4 = false;            
        } else {
            sw4 = true;
        }
    }

    if (r5 < -0.03 || r5 > 0.03) {
        sw5 = false;        
    } else {
        if (r5 === 0.03 || r5 === -0.03 || r5 === 0.02999999999999936 || r5 === -0.02999999999999936) {
            sw5 = false;            
        } else {
            sw5 = true;
        }
    }

    if (r6 < -0.03 || r6 > 0.03) {
        sw6 = false;        
    } else {
        if (r6 === 0.03 || r6 === -0.03 || r6 === 0.02999999999999936 || r6 === -0.02999999999999936) {
            sw6 = false;            
        } else {
            sw6 = true;
        }
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
        p.value = parseFloat(((l1 + l2 + l3) / 3)).toFixed(3);
        p1.innerHTML = parseFloat(((l1 + l2 + l3) / 3)).toFixed(3);
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
    
                    $.each(response.model, function (key, item) {
                        item.Factor = parseInt(item.Factor);                        
                                                    
                            //LECTURA 1-----------------------------------------------
                            if((l1 >= 0 && l1 < 5)){
                                if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                                                                
                                    fac1.innerHTML = parseFloat((l1 + factores[0])).toFixed(3);
                                    l1 = parseFloat(l1 + factores[0]);                                    
                                }
                            }else if(l1 >= 5 && l1 < 10){
                                if((item.Factor >= 0.5 || item.Factor <= -0.5) ){
                                    fac1.innerHTML = parseFloat((l1 + factores[1])).toFixed(3);
                                    l1 = parseFloat(l1 + factores[1]);                                                                                                            
                                }
                            }else if((l1 >= 10 && l1 < 15)){
                                if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                
                                    fac1.innerHTML = parseFloat((l1 + factores[2])).toFixed(3);
                                    l1 = parseFloat(l1 + factores[2]);
                                }
                            }else if(l1 >= 15 && l1 < 20){
                                if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                        
                                    fac1.innerHTML = parseFloat((l1 + factores[3])).toFixed(3);
                                    l1 = parseFloat(l1 + factores[3]);
                                }
                            }else if(l1 >= 20 && l1 < 25){
                                if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                                     
                                    fac1.innerHTML = parseFloat((l1 + factores[4])).toFixed(3);
                                    l1 = parseFloat(l1 + factores[4]);
                                }
                            }else if(l1 >= 25 && l1 < 30){
                                if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                
                                    fac1.innerHTML = parseFloat((l1 + factores[5])).toFixed(3);
                                    l1 = parseFloat(l1 + factores[5]);
                                }
                            }else if(l1 >= 30 && l1 < 35){
                                if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                                     
                                    fac1.innerHTML = parseFloat((l1 + factores[6])).toFixed(3);
                                    l1 = parseFloat(l1 + factores[6]);
                                }
                            }else if(l1 >= 35 && l1 < 40){
                                if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                
                                    fac1.innerHTML = parseFloat((l1 + factores[7])).toFixed(3);
                                    l1 = parseFloat(l1 + factores[7]);
                                }
                            }                                                    
                            
                            //LECTURA 2---------------------------------------------
                            if((l2 >= 0 && l2 < 5)){
                                if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                                                                
                                    fac2.innerHTML = parseFloat((l2 + factores[0])).toFixed(3);
                                    l2 = parseFloat(l2 + factores[0]);
                                }
                            }else if(l2 >= 5 && l2 < 10){
                                if((item.Factor >= 0.5 || item.Factor <= -0.5) ){
                                    fac2.innerHTML = parseFloat((l2 + factores[1])).toFixed(3);
                                    l2 = parseFloat(l2 + factores[1]);
                                }
                            }else if((l2 >= 10 && l2 < 15)){
                                if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                
                                    fac2.innerHTML = parseFloat((l2 + factores[2])).toFixed(3);
                                    l2 = parseFloat(l2 + factores[2]);
                                }
                            }else if(l2 >= 15 && l2 < 20){
                                if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                        
                                    fac2.innerHTML = parseFloat((l2 + factores[3])).toFixed(3);
                                    l2 = parseFloat(l2 + factores[3]);
                                }
                            }else if(l2 >= 20 && l2 < 25){
                                if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                                     
                                    fac2.innerHTML = parseFloat((l2 + factores[4])).toFixed(3);
                                    l2 = parseFloat(l2 + factores[4]);
                                }
                            }else if(l2 >= 25 && l2 < 30){
                                if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                
                                    fac2.innerHTML = parseFloat((l2 + factores[5])).toFixed(3);
                                    l2 = parseFloat(l2 + factores[5]);
                                }
                            }else if(l2 >= 30 && l2 < 35){
                                if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                                     
                                    fac2.innerHTML = parseFloat((l2 + factores[6])).toFixed(3);
                                    l2 = parseFloat(l2 + factores[6]);
                                }
                            }else if(l2 >= 35 && l2 < 40){
                                if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                
                                    fac2.innerHTML = parseFloat((l2 + factores[7])).toFixed(3);
                                    l2 = parseFloat(l2 + factores[7]);
                                }
                            }                                                    
                            
                            //LECTURA 3---------------------------------------------    
                            if((l3 >= 0 && l3 < 5)){
                                if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                                                                
                                    fac3.innerHTML = parseFloat((l3 + factores[0])).toFixed(3);
                                    l3 = parseFloat(l3 + factores[0]);                                    
                                }
                            }else if(l3 >= 5 && l3 < 10){
                                if((item.Factor >= 0.5 || item.Factor <= -0.5) ){
                                    fac3.innerHTML = parseFloat((l3 + factores[1])).toFixed(3);
                                    l3 = parseFloat(l3 + factores[1]);
                                }
                            }else if((l3 >= 10 && l3 < 15)){
                                if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                
                                    fac3.innerHTML = parseFloat((l3 + factores[2])).toFixed(3);
                                    l3 = parseFloat(l3 + factores[2]);                        
                                }
                            }else if(l3 >= 15 && l3 < 20){
                                if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                        
                                    fac3.innerHTML = parseFloat((l3 + factores[3])).toFixed(3);
                                    l3 = parseFloat(l3 + factores[3]);
                                }
                            }else if(l3 >= 20 && l3 < 25){
                                if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                                     
                                    fac3.innerHTML = parseFloat((l3 + factores[4])).toFixed(3);
                                    l3 = parseFloat(l3 + factores[4]);
                                }
                            }else if(l3 >= 25 && l3 < 30){
                                if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                
                                    fac3.innerHTML = parseFloat((l3 + factores[5])).toFixed(3);
                                    l3 = parseFloat(l3 + factores[5]);
                                }
                            }else if(l3 >= 30 && l3 < 35){
                                if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                                     
                                    fac3.innerHTML = parseFloat((l3 + factores[6])).toFixed(3);
                                    l3 = parseFloat(l3 + factores[6]);
                                }
                            }else if(l3 >= 35 && l3 < 40){
                                if((item.Factor >= 0.5 || item.Factor <= -0.5) ){                                                
                                    fac3.innerHTML = parseFloat((l3 + factores[7])).toFixed(3);
                                    l3 = parseFloat(l3 + factores[7]);
                                }
                            }                        
                    });
                },
            });                                    
        
        p.value = parseFloat(((l1 + l2 + l3) / 3)).toFixed(3);
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

function valTempCalMuestra(lec1) {
    let sw = true;    
    let l1 = parseFloat(document.getElementById(lec1).value);
    let t = document.getElementById("tempCalidad");    

    if (sw == true) {
        //Aceptado
        if (lec1 == "tempCalidad10") {
            if(isNaN(l1)){
                t.rows[1].setAttribute("class", "bg-danger");                
            }else{
                t.rows[1].setAttribute("class", "bg-success");
            }                               
        } else if (lec1 == "tempCalidad11") {
            if(isNaN(l1)){
                t.rows[2].setAttribute("class", "bg-danger");                
            }else{
                t.rows[2].setAttribute("class", "bg-success");
            }                               
        } else if (lec1 == "tempCalidad12") {
            if(isNaN(l1)){
                t.rows[3].setAttribute("class", "bg-danger");                
            }else{
                t.rows[3].setAttribute("class", "bg-success");
            }                   
        } else if (lec1 == "tempCalidad13") {
            if(isNaN(l1)){
                t.rows[4].setAttribute("class", "bg-danger");                
            }else{
                t.rows[4].setAttribute("class", "bg-success");
            }                               
        } else if (lec1 == "tempCalidad14") {
            if(isNaN(l1)){
                t.rows[5].setAttribute("class", "bg-danger");                
            }else{
                t.rows[5].setAttribute("class", "bg-success");
            }                               
        } else if (lec1 == "tempCalidad15") {
            if(isNaN(l1)){
                t.rows[6].setAttribute("class", "bg-danger");                
            }else{
                t.rows[6].setAttribute("class", "bg-success");
            }                               
        }
    }   

    return sw;
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
        p.value = parseFloat(((l1 + l2 + l3) / 3)).toFixed(3);
        p1.innerHTML = parseFloat(((l1 + l2 + l3) / 3)).toFixed(3);
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
        p.value = parseFloat(((l1 + l2 + l3) / 3)).toFixed(3);        
        p1.innerHTML = parseFloat(((l1 + l2 + l3) / 3)).toFixed(3);        
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
//FUNCIÓN EN PROCESO
function valTempCompuesto(temp1, factTempAplicado){
    //Almacena el valor del ID del Input
    t = parseInt(document.getElementById(temp1).value);
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
            let cont = 1;
            
            console.log(response.model);               

            $.each(response.model, function (key, item) {                                
                item.Factor = parseInt(item.Factor);
                item.Factor_aplicado= parseInt(item.Factor_aplicado);   

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
                        sw9 = false
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
                    }
                }else{
                        sw9 = true;
                        sw1 = false;
                        sw2 = false;
                        sw3 = false;
                        sw4 = false;
                        sw5 = false;
                        sw6 = false;
                        sw7 = false;
                        sw8 = false;
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
                }else if(sw9 == true){
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
            nom.innerText = "";
            mar.innerText = "";
            lot.innerText = "";
        },
    });
}

function setPhCalidad(idPh, nombre, marca, lote) {    
    let nom = document.getElementById(nombre);
    let mar = document.getElementById(marca);
    let lot = document.getElementById(lote);
    $.ajax({
        url: base_url + "/admin/campo/captura/getPhCalidad", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            idPh: idPh,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);            
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
    $.ajax({
        url: base_url + "/admin/campo/captura/getPhCalidad", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            idPh: idPh,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {            
            console.log(response);
            nom.innerText = "";
            mar.innerText = "";
            lot.innerText = "";                                   
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
    let temperaturaCalidad = new Array();
    let conductividad = new Array();
    let gasto = new Array();
    let row = new Array();

    for (let i = 0; i < $("#numTomas").val(); i++) {
        row = new Array();
        row.push($("#materia" + i).val());
        row.push($("#olor" + i).val());
        row.push($("#color" + i).val());
        row.push($("#phl1" + i).val());
        row.push($("#phl2" + i).val());
        row.push($("#phl3" + i).val());
        row.push($("#phprom" + i).val());
        row.push($("#phf" + i).val());        
        ph.push(row);
    }

    for (let i = 0; i < $("#numTomas").val(); i++) {
        row = new Array();
        row.push($("#temp1" + i).val());
        row.push($("#temp2" + i).val());
        row.push($("#temp3" + i).val());
        row.push($("#tempprom" + i).val());        
        temperatura.push(row);
    }

    for (let i = 0; i < $("#numTomas").val(); i++) {
        row = new Array();
        row.push($("#tempCalidad1" + i).val());        
        temperaturaCalidad.push(row);
    }

    for (let i = 0; i < $("#numTomas").val(); i++) {
        row = new Array();
        row.push($("#con1" + i).val());
        row.push($("#con2" + i).val());
        row.push($("#con3" + i).val());
        row.push($("#conprom" + i).val());        
        conductividad.push(row);
    }

    for (let i = 0; i < $("#numTomas").val(); i++) {
        row = new Array();
        row.push($("#gas1" + i).val());
        row.push($("#gas2" + i).val());
        row.push($("#gas3" + i).val());
        row.push($("#gasprom" + i).val());
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
            temperaturaCalidad: temperaturaCalidad,
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

/*function setEvidencia(){        
    var parametros = new FormData($("#formulario-envia")[0]);

    $.ajax({
        url: base_url + "/admin/campo/captura/setDataMuestreo",
        type: "POST", //método de envio
        contentType: false,
        processData: false,        
        data: {
            idSolicitud: $("#idSolicitud").val(),            
            _token: $('input[name="_token"]').val(),
            parametros
        },
        beforeSend: function(){

        },
        success: function(response){
            alert(response);
        }
    });
}*/

function selectedOption() {
    //Obtiene el valor de la opción seleccionada
    let selectedOption = document.getElementById("phTrazable1").value;

    return selectedOption;
}

//Función para generar la tabla Qi, Qt, Qi/Qt, Vmc, Vmsi
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
}

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

//FUNCIÓN EN CONSTRUCCIÓN
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

    console.log("Tamaño de altitud: " + altitud.length);

    if(altitud.length > 10){
        alert("La altitud no puede tener más de 10 dígitos");
    }
}