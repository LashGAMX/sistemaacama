var base_url = "https://dev.sistemaacama.com.mx";

// var selectedRow = false;
$(document).ready(function () {
    $("#datosGenerales-tab").click();
    datosGenerales();
    datosMuestreo();
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
                alert("Los valores de Ph trazable no pueden ser los mismos");
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
            alert("Los valores de Ph trazable no pueden ser los mismos");
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
                alert("Los valores de Ph calidad no pueden ser los mismos");
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

function valTempAmbiente() {
    let temp1 = parseFloat($("#tempAmbiente").val());
    let temp2 = parseFloat($("#tempBuffer").val());
    if (temp1 - temp2 > 5 || temp1 - temp2 < -5) {
        inputBorderColor("tempAmbiente", "rojo");
        inputBorderColor("tempBuffer", "rojo");
    } else {
        inputBorderColor("tempAmbiente", "verde");
        inputBorderColor("tempBuffer", "verde");
    }
}

function valPhTrazable(lec1, lec2, lec3, estado) {
    let sw = false;
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

function valPhMuestra(lec1, lec2, lec3, prom) {
    let sw = false;
    let sw1 = false;
    let sw2 = false;
    let sw3 = false;
    let sw4 = false;
    let sw5 = false;
    let sw6 = false;
    let p = document.getElementById(prom);
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
        if (sw1 == true && sw2 == true && sw3 == true && sw4 == true && sw5 == true &&sw6 == true) {
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
        }
    }

    if(sw == true && sw1 == true && sw2 == true && sw3 == true && sw4 == true && sw5 == true && sw6 == true){        
        p.value = parseFloat(((l1 + l2 + l3) / 3)).toFixed(3);
    }else{        
        p.value = "";
    }

    return sw;
}

function valTempMuestra(lec1, lec2, lec3, prom, f1, f2, f3) {
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

            if (l1 >= 0 && l1 <= 5) {                
                fac1.innerHTML = (l1 - 1).toFixed(3);
            } else if (l1 >= 16 && l1 < 20) {
                fac1.innerHTML = (l1 + 1).toFixed(3);
            } else {
                fac1.innerHTML = l1.toFixed(3);
            }

            if (l2 >= 0 && l2 <= 5) {
                fac2.innerHTML = (l2 - 1).toFixed(3);
            } else if (l2 >= 16 && l2 < 20) {
                fac2.innerHTML = (l2 + 1).toFixed(3);
            } else {
                fac2.innerHTML = l2.toFixed(3);
            }

            if (l3 >= 0 && l3 <= 5) {
                fac3.innerHTML = (l3 - 1).toFixed(3);
            } else if (l1 >= 16 && l1 < 20) {
                fac3.innerHTML = (l3 + 1).toFixed(3);
            } else {
                fac3.innerHTML = l3.toFixed(3);
            }

            if((isNaN(l2) || isNaN(l3)) || (sw1 == false || sw2 == false || sw3 == false)){
                t.rows[1].setAttribute("class", "bg-danger");
                fac1.innerHTML = "";
                fac2.innerHTML = "";
                fac3.innerHTML = "";
            }else{
                t.rows[1].setAttribute("class", "bg-success");
            }         

        } else if (lec1 == "temp11") {
            if (l1 >= 0 && l1 <= 5) {
                fac1.innerHTML = (l1 - 1).toFixed(3);
            } else if (l1 >= 16 && l1 < 20) {
                fac1.innerHTML = (l1 + 1).toFixed(3);
            } else {
                fac1.innerHTML = l1.toFixed(3);
            }

            if (l2 >= 0 && l2 <= 5) {
                fac2.innerHTML = (l2 - 1).toFixed(3);
            } else if (l2 >= 16 && l2 < 20) {
                fac2.innerHTML = (l2 + 1).toFixed(3);
            } else {
                fac2.innerHTML = l2.toFixed(3);
            }

            if (l3 >= 0 && l3 <= 5) {
                fac3.innerHTML = (l3 - 1).toFixed(3);
            } else if (l1 >= 16 && l1 < 20) {
                fac3.innerHTML = (l3 + 1).toFixed(3);
            } else {
                fac3.innerHTML = l3.toFixed(3);
            }

            if((isNaN(l2) || isNaN(l3)) || (sw1 == false || sw2 == false || sw3 == false)){
                t.rows[2].setAttribute("class", "bg-danger");
                fac1.innerHTML = "";
                fac2.innerHTML = "";
                fac3.innerHTML = "";
            }else{
                t.rows[2].setAttribute("class", "bg-success");
            }            

        } else if (lec1 == "temp12") {
            if (l1 >= 0 && l1 <= 5) {
                fac1.innerHTML = (l1 - 1).toFixed(3);
            } else if (l1 >= 16 && l1 < 20) {
                fac1.innerHTML = (l1 + 1).toFixed(3);
            } else {
                fac1.innerHTML = l1.toFixed(3);
            }

            if (l2 >= 0 && l2 <= 5) {
                fac2.innerHTML = (l2 - 1).toFixed(3);
            } else if (l2 >= 16 && l2 < 20) {
                fac2.innerHTML = (l2 + 1).toFixed(3);
            } else {
                fac2.innerHTML = l2.toFixed(3);
            }

            if (l3 >= 0 && l3 <= 5) {
                fac3.innerHTML = (l3 - 1).toFixed(3);
            } else if (l1 >= 16 && l1 < 20) {
                fac3.innerHTML = (l3 + 1).toFixed(3);
            } else {
                fac3.innerHTML = l3.toFixed(3);
            }

            if((isNaN(l2) || isNaN(l3)) || (sw1 == false || sw2 == false || sw3 == false)){
                t.rows[3].setAttribute("class", "bg-danger");
                fac1.innerHTML = "";
                fac2.innerHTML = "";
                fac3.innerHTML = "";
            }else{
                t.rows[3].setAttribute("class", "bg-success");
            }
            
        } else if (lec1 == "temp13") {
            if (l1 >= 0 && l1 <= 5) {
                fac1.innerHTML = (l1 - 1).toFixed(3);
            } else if (l1 >= 16 && l1 < 20) {
                fac1.innerHTML = (l1 + 1).toFixed(3);
            } else {
                fac1.innerHTML = l1.toFixed(3);
            }

            if (l2 >= 0 && l2 <= 5) {
                fac2.innerHTML = (l2 - 1).toFixed(3);
            } else if (l2 >= 16 && l2 < 20) {
                fac2.innerHTML = (l2 + 1).toFixed(3);
            } else {
                fac2.innerHTML = l2.toFixed(3);
            }

            if (l3 >= 0 && l3 <= 5) {
                fac3.innerHTML = (l3 - 1).toFixed(3);
            } else if (l1 >= 16 && l1 < 20) {
                fac3.innerHTML = (l3 + 1).toFixed(3);
            } else {
                fac3.innerHTML = l3.toFixed(3);
            }

            if((isNaN(l2) || isNaN(l3)) || (sw1 == false || sw2 == false || sw3 == false)){
                t.rows[4].setAttribute("class", "bg-danger");
                fac1.innerHTML = "";
                fac2.innerHTML = "";
                fac3.innerHTML = "";
            }else{
                t.rows[4].setAttribute("class", "bg-success");
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
        }
    }               
    
    if(sw == true && sw1 == true && sw2 == true && sw3 == true){        
        p.value = parseFloat(((l1 + l2 + l3) / 3)).toFixed(3);
    }else{        
        p.value = "";
    }

    return sw;
}

function valConMuestra(lec1, lec2, lec3, prom) {
    let sw = true;
    let p = document.getElementById(prom);
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
        }
    }    

    if((sw == true) && (!isNaN(l1) && !isNaN(l2) && !isNaN(l3))){        
        p.value = parseFloat(((l1 + l2 + l3) / 3)).toFixed(3);
    }else{        
        p.value = "";
    }

    return sw;
}

function valGastoMuestra(lec1, lec2, lec3, prom) {
    let sw = true;
    let p = document.getElementById(prom);
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
        }
    }

    if((sw == true) && (!isNaN(l1) && !isNaN(l2) && !isNaN(l3))){        
        p.value = parseFloat(((l1 + l2 + l3) / 3)).toFixed(3);
    }else{        
        p.value = "";
    }

    return sw;
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
            console.log(response.model);
            tab +=
                '<table id="tablaFactorCorreccion" class="table table-sm  table-striped table-bordered">';
            tab += '    <thead class="thead-dark">';
            tab += "        <tr>";
            tab += "              <th>De °C</th>";
            tab += "              <th>a °C</th>";
            tab += "              <th>Factor corección</th>";
            tab += "              <th>Factor de corección aplicada</th>";
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

function selectedOption() {
    //Obtiene el valor de la opción seleccionada
    let selectedOption = document.getElementById("phTrazable1").value;

    return selectedOption;
}
