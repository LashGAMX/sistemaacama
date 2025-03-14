
// var selectedRow = false;
$(document).ready(function () {        
    $("#datosGenerales-tab").click();
    //Selec con buscador
    $('#termometro').select2();
    $('#termometro2').select2(); 
    validacionInicio()
    $("#setPhMuestra").click(function () { 
    guardarPhMuestra()
    });
    $(".btnSubir").click(function () {
        $('body, html').animate({
            scrollTop: '0px'
        }, 300);
    });
    $("#btnCancelarPunto").click(function () {
        CancelarPunto()
    });
    $("#btnGuardarTodo").click(function () {
        guardarPhMuestra()
        GuardarConductividad()
        GuardarGasto()
        GuardarVidrio()
        GuardarTempAgua()
        GuardarPhControlCalidad()
        GuardarTempAmb()
        alert("Datos Guardados")
    });
    getFactorCorreccion(1,'termometro')
    getFactorCorreccion(2,'termometro2')
});
function validacionFechaMuestreo(f1,f2,sw){

    if (sw == 1) { 
        let d1 = new Date($("#"+f1).val()); // Fecha original
        let d2 = new Date($("#"+f2).val()); // Fecha comprobacion
        if(d1 > d2){
            inputBorderColor(f1, "verde");
            console.log("Fecha mayor");
        }else{
            inputBorderColor(f1, "rojo");
            console.log("Fecha menor");
        }
        let aux = new Date()
        aux = d2.getDate() + 1
        console.log(aux)
        console.log(d1)
        if (d1.getDate() > aux) {
            alert("Estas seguro de agregar esa fecha?")
        }
    } else {
        let d1 = new Date($("#"+f1).val()); // Fecha original
        let d2 = new Date($("#"+f2).val()); // Fecha comprobacion
        let temp = new Date();
        if(d1 > d2){
            temp = d2.setHours(d2.getHours() + 4,d2.getMinutes() + 10);
            if(temp > d1){
                inputBorderColor(f1, "verde");
                console.log("Fecha mayor");
            }else{
                console.log("Supera el limite");
                inputBorderColor(f1, "rojo");
            }
        }else{
            inputBorderColor(f1, "rojo");
            console.log("Fecha menor");
        }
    }
    
}
function generarVmsi()
{
    let table = document.getElementById("muestrasQi")
    let tab = '';
    let prom = 0
    $.ajax({
        url: base_url + "/admin/campo/captura/generarVmsi", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            idSolicitud:$("#idSolicitud").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            tab += '<thead>';
            tab += '    <tr>';
            tab += '        <th>Núm muestra</th>';
            tab += '        <th>Qi</th>';
            tab += '        <th>Qt</th>';
            tab += '        <th>Qi / Qt</th>';
            tab += '        <th>Vmc</th>';
            tab += '        <th>Vmsi</th>';
            tab += '    </tr>';
            tab += '</thead>';
            $.each(response.model, function (key, item) { 
                if(item.Activo != 0){
                    prom = prom + parseFloat(item.Promedio)
                }
            });  
            $.each(response.model, function (key, item) { 
                tab += '<tr>'
                tab += '    <td>'+item.Num_toma+'</td>'
                if (item.Activo == 1) {
                    tab += '    <td>'+item.Promedio+'</td>'
                    tab += '    <td>'+prom.toFixed(2)+'</td>'
                    tab += '    <td>'+item.Promedio+' / '+prom.toFixed(2)+'</td>'
                    tab += '    <td>'+((parseFloat(item.Promedio) / prom).toFixed(2))+'</td>'
                    tab += '    <td>'+((parseFloat(item.Promedio) / prom) * parseFloat($("#volCalculado").val())).toFixed(2)+'</td>'   
                } else {
                    tab += '    <td>------</td>'
                    tab += '    <td>------</td>'
                    tab += '    <td>------</td>'
                    tab += '    <td>------</td>'
                    tab += '    <td>------</td>'
                }
                tab += '</tr>'
            });     
            table.innerHTML = tab;
        },
    }); 
}
function valGastoMuestra(id) {
    console.log("este es el id "+id);
    let sw = true;
    let l1 = parseFloat(document.getElementById("gas1" + id).value);
    let l2 = parseFloat(document.getElementById("gas2" + id).value);
    let l3 = parseFloat(document.getElementById("gas3" + id).value);

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

    if (l1 == "") {
        $("#gas1" + id).attr("placeholder", "Lectura Vacía");
        sw = false;
    }

    if (l2 == "") {
        $("#gas2" + id).attr("placeholder", "Lectura Vacía");
        sw = false;
    }

    if (l3 == "") {
        $("#gas3" + id).attr("placeholder", "Lectura Vacía");
        sw = false;
    }

    if (sw == true) {
        $("#trGastoMuestra" + id).attr("class", "bg-success");
    } else {
        $("#trGastoMuestra" + id).attr("class", "bg-danger");
    }
 
    $("#gasprom" + id).val(((l1 + l2 + l3) / 3).toFixed(2));

    return sw;
}

function valVidrio(id) {
    let sw = true;

    // Obtener los valores de los inputs y selects
    let oxigenoD = parseFloat(document.getElementById("Vidrio1" + id).value);
    let burbujas = document.getElementById("selectVidrio" + id).value;

    // Validar que el campo de Oxigeno D no esté vacío y que sea un número
    if (isNaN(oxigenoD) || oxigenoD < 0) {
        document.getElementById("Vidrio1" + id).setAttribute("placeholder", "Valor no válido");
        sw = false;
    }

    // Validar que se haya seleccionado una opción en el select
    if (burbujas === "") {
        sw = false;
    }

    // Cambiar color de la fila según la validación
    if (sw) {
        document.getElementById("trVidrio" + id).classList.remove("bg-danger");
        document.getElementById("trVidrio" + id).classList.add("bg-success");
    } else {
        document.getElementById("trVidrio" + id).classList.remove("bg-success");
        document.getElementById("trVidrio" + id).classList.add("bg-danger");
    }

    return sw;
}


function valConMuestra(id) {
    let sw = true;
    
    let l1 = parseFloat(document.getElementById("con1"+id).value);
    let l2 = parseFloat(document.getElementById("con2"+id).value);
    let l3 = parseFloat(document.getElementById("con3"+id).value);

    switch (parseInt($("#idNorma").val())) {
        case 1:
        case 27:
            if (l1 > 3500 || l2 > 3500 || l3 > 3500) {
                alert("La conductividad no puede sobrepasar mas de 3500")
            } 
            break;
        default:  
            break;
    }
    
    

    //El valor entre ellos no debe diferir de 5 unidades de conductividad
    if (l1 - l2 > 100 || l1 - l2 < -100) {
        sw = false;
    }
    if (l1 - l3 > 100 || l1 - l3 < -100) {
        sw = false;
    }
    if (l2 - l1 > 100 || l2 - l1 < -100) {
        sw = false;
    }
    if (l2 - l3 > 100 || l2 - l3 < -100) {
        sw = false;
    }
    if (l3 - l1 > 100 || l3 - l1 < -100) {
        sw = false;
    }
    if (l3 - l2 > 100 || l3 - l2 < -100) {
        sw = false;
    }

    if(l1 == ""){
        $("#con1"+id).attr("placeholder","Lectura Vacía")
        sw = false;
    }
    
    if(l2 == ""){
        $("#con2"+id).attr("placeholder","Lectura Vacía")
        sw = false;
    }
    
    if(l3 == ""){
        $("#con3"+id).attr("placeholder","Lectura Vacía")
        sw = false;
    }


    if (sw == true) {
        $("#trConducMuestra"+id).attr("class","bg-success")
    } else {
        $("#trConducMuestra"+id).attr("class","bg-danger")
    }
    
    $("#conProm"+id).val(((l1 + l2 + l3) / 3).toFixed(0))

    return sw;
}
function valPhCalidadMuestra(id) {
    console.log("valPhCalidadMuestra")
    let l1 = parseFloat($("#phCM1"+id).val())
    let l2 = parseFloat($("#phCM2"+id).val())
    let l3 = parseFloat($("#phCM3"+id).val())
    let ph = parseFloat($("#phControlMuestra"+id+" option:selected").text())
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
        $("#phCM1"+id).attr("placeholder","Lectura Vacía")
        sw = false;
    }
    
    if(l2 == ""){
        $("#phCM2"+id).attr("placeholder","Lectura Vacía")
        sw = false;
    }
    
    if(l3 == ""){
        $("#phCM3"+id).attr("placeholder","Lectura Vacía")
        sw = false;
    }

    
    if (sw == true) {
        $("#phCMEstado"+id).val("Aprobado")
        $("#trCalidadMuestra"+id).attr("class","bg-success")
    } else {
        $("#phCMEstado"+id).val("Rechazado")
        $("#trCalidadMuestra"+id).attr("class","bg-danger")
    }
 
    $("#phCMPromedio"+id).val(((l1 + l2 + l3) / 3).toFixed(2))
}
function valTemperaturaAgua(id)
{
    console.log("valTemperaturaAgua")
    let l1 = parseFloat($("#temp1"+id).val())
    let l2 = parseFloat($("#temp2"+id).val())
    let l3 = parseFloat($("#temp3"+id).val())
    let sw = true
    r1 = (l1 - l2).toFixed(2);
    r2 = (l1 - l3).toFixed(2);
    r3 = (l2 - l1).toFixed(2);
    r4 = (l2 - l3).toFixed(2);
    r5 = (l3 - l1).toFixed(2);
    r6 = (l3 - l2).toFixed(2);    

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
    if (sw == true) {
        inputBorderColor("temp1"+id,'verde')
        inputBorderColor("temp2"+id,'verde')
        inputBorderColor("temp3"+id,'verde')
    } else {
        inputBorderColor("temp1"+id,'rojo')
        inputBorderColor("temp2"+id,'rojo')
        inputBorderColor("temp3"+id,'rojo')
    }
    let temp = 0;
    $.each(factorCorrecion1, function (key, item) {    
        if(l1 >= parseFloat(item.De_c) && l1 < parseFloat(item.A_c)){
            $("#tempSin1"+id).val(l1 + parseFloat(item.Factor_aplicado))    
            temp = temp + (l1 + parseFloat(item.Factor_aplicado))
        }

        if(l2 >= parseFloat(item.De_c) && l2 < parseFloat(item.A_c)){
            $("#tempSin2"+id).val(l2 + parseFloat(item.Factor_aplicado))
            temp = temp + (l2 + parseFloat(item.Factor_aplicado))
        }

        if(l3 >= parseFloat(item.De_c) && l3 < parseFloat(item.A_c)){
            $("#tempSin3"+id).val(l3 + parseFloat(item.Factor_aplicado))
            temp = temp + (l3 + parseFloat(item.Factor_aplicado))
        }
      });
      if(l1 > 50){
        $("#tempSin1"+id).val(l1 + parseFloat(factorCorrecion1[9].Factor_aplicado))    
        temp = temp + (l1 + parseFloat(factorCorrecion1[9].Factor_aplicado))
        }
        if(l3 > 50){
            $("#tempSin3"+id).val(l3 + parseFloat(factorCorrecion1[9].Factor_aplicado))    
            temp = temp + (l3 + parseFloat(factorCorrecion1[9].Factor_aplicado))
        }
        if(l2 > 50){
            $("#tempSin2"+id).val(l2 + parseFloat(factorCorrecion1[9].Factor_aplicado))    
            temp = temp + (l2 + parseFloat(factorCorrecion1[9].Factor_aplicado))
        }
    $("#tempprom"+id).val((temp / 3).toFixed())
}
function valTemperaturaAmbiente(id)
{
    console.log("valTemperaturaAmbiente")
    let l1 = parseFloat($("#tempa1"+id).val())

    $.each(factorCorrecion2, function (key, item) {    
        if(l1 >= parseFloat(item.De_c) && l1 < parseFloat(item.A_c)){
            $("#tempaSin1"+id).val((l1 + parseFloat(item.Factor_aplicado)).toFixed(1))    
            $("#tempaApl1"+id).val(parseFloat(item.Factor_aplicado))    
        }
      });
}
function guardarPhMuestra()
{
    let tab = document.getElementById("phMuestra")
    let materia = new Array()
    let olor = new Array()
    let color = new Array()
    let ph1 = new Array()
    let ph2 = new Array()
    let ph3 = new Array()
    let promedio = new Array() 
    let fecha = new Array()
    let activo = new Array()

    for (let i = 0; i < parseInt($("#numTomas").val()); i++) {
        materia.push(tab.rows[i + 1].children[1].children[0].value)
        olor.push(tab.rows[i + 1].children[2].children[0].value)
        color.push(tab.rows[i + 1].children[3].children[0].value)
        ph1.push(tab.rows[i + 1].children[4].children[0].value)
        ph2.push(tab.rows[i + 1].children[5].children[0].value)
        ph3.push(tab.rows[i + 1].children[6].children[0].value)
        promedio.push(tab.rows[i + 1].children[7].children[0].value)
        fecha.push(tab.rows[i + 1].children[8].children[0].value)
        activo.push(tab.rows[i + 1].children[9].children[0].value)
    }

    $.ajax({
        url: base_url + "/admin/campo/captura/GuardarPhMuestra", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            id:$("#idSolicitud").val(),     
            materia:materia,
            olor:olor,
            color:color,
            ph1:ph1,
            ph2:ph2,
            ph3:ph3,
            promedio:promedio,
            fecha:fecha,
            numTomas:numTomas,
            activo:activo,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            alert("Ph guardado")
        },
    });
}
function valPhMuestra(id) {
    let sw = false;
    let sw1 = false;
    let sw2 = false;
    let sw3 = false;
    let sw4 = false;
    let sw5 = false;
    let sw6 = false;        
    
    let l1 = parseFloat($("#ph1"+id).val()); 
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
        $("#phProm"+id).val(((parseFloat(l1) + parseFloat(l2) + parseFloat(l3)) / 3).toFixed(2))
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
let factorCorrecion1 = new Array()
let factorCorrecion2 = new Array()
function getFactorCorreccion(sw,id) {
    $.ajax({
        url: base_url + "/admin/campo/captura/getFactorCorreccion", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            idFactor: $("#"+id).val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
          if (sw == 1) {
            factorCorrecion1 = response.model
          } else {
            factorCorrecion2 = response.model
          }  
        },
    }); 
}
function GuardarTempAgua() {

    let array1 = new Array();
    let array2 = new Array();
    let array3 = new Array();
    let arrayB1 =new Array();
    let arrayB2 =new Array();
    let arrayB3 =new Array();
    let promedio = new Array();
    let estado = new Array();
    let tab = document.getElementById('tempAgua');
    
    for (let i = 0; i < numTomas ; i++) {
        array1.push(tab.rows[i+1].children[1].children[0].value);
        arrayB1.push(tab.rows[i+1].children[2].children[0].value); 
        array2.push(tab.rows[i+1].children[3].children[0].value);
        arrayB2.push(tab.rows[i+1].children[4].children[0].value);
        array3.push(tab.rows[i+1].children[5].children[0].value);
        arrayB3.push(tab.rows[i+1].children[6].children[0].value);
        promedio.push(tab.rows[i+1].children[7].children[0].value);
        estado.push(tab.rows[i+1].children[8].children[0].value);
        }
    
    $.ajax({
        url: base_url + "/admin/campo/captura/GuardarTempAgua", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            idSolicitud:$("#idSolicitud").val(),
            array1 :array1,
            array2 :array2,
            array3 :array3,
            arrayB1:arrayB1,
            arrayB2:arrayB2,
            arrayB3:arrayB3,
            promedio :promedio,
            estado :estado ,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
        },
    }); 
}
function GuardarTempAmb() {

    let array1 = new Array();
    let array2 = new Array();
    let factor = new Array();
    let activo = new Array();
    let tab = document.getElementById('tabTempAmbiente');
    
    for (let i = 0; i <numTomas ; i++) {
        array1.push(tab.rows[i+1].children[1].children[0].value);
        factor.push(tab.rows[i+1].children[2].children[0].value); 
        array2.push(tab.rows[i+1].children[3].children[0].value);
        activo.push(tab.rows[i+1].children[4].children[0].value); 
        }
    $.ajax({
        url: base_url + "/admin/campo/captura/GuardarTempAmb", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            idSolicitud:$("#idSolicitud").val(),
            array1 :array1,
            array2 :array2, 
            factor:factor,
            activo:activo,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
        },
    }); 
}
function GuardarPhControlCalidad() {

    let array1 = new Array();
    let array2 = new Array();
    let array3 = new Array();
    let array4 = new Array();
    let promedio = new Array();
    let estado = new Array();
    let activo = new Array();
    let tab = document.getElementById('phControlCalidadMuestra');
    
    for (let i = 0; i <numTomas ; i++) {
        array1.push(tab.rows[i+1].children[1].children[0].value); 
        array2.push(tab.rows[i+1].children[2].children[0].value);
        array3.push(tab.rows[i+1].children[3].children[0].value); 
        array4.push(tab.rows[i+1].children[4].children[0].value);
        estado.push(tab.rows[i+1].children[5].children[0].value);
        promedio.push(tab.rows[i+1].children[6].children[0].value);
        activo.push(tab.rows[i+1].children[7].children[0].value);
        }
    $.ajax({
        url: base_url + "/admin/campo/captura/GuardarPhControlCalidad", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            idSolicitud:$("#idSolicitud").val(),
            array1 :array1 ,
            array2 :array2 ,
            array3 :array3 ,
            array4 :array4 ,
            promedio:promedio,
            estado :estado ,
            activo:activo,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
        },
    }); 
}
function GuardarConductividad() {
    let array1 = new Array();
    let array2 = new Array();
    let array3 = new Array();
    let promedio = new Array();
    let activo = new Array();
    let tab = document.getElementById('conductividad');
    
    for (let i = 0; i <numTomas ; i++) {
        array1.push(tab.rows[i+1].children[1].children[0].value);
        array2.push(tab.rows[i+1].children[2].children[0].value);
        array3.push(tab.rows[i+1].children[3].children[0].value);
        promedio.push(tab.rows[i+1].children[4].children[0].value);
        activo.push(tab.rows[i+1].children[5].children[0].value);
        }
    $.ajax({
        url: base_url + "/admin/campo/captura/GuardarConductividad", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            idSolicitud:$("#idSolicitud").val(),
            array1 :array1,
            array2 :array2,
            array3 :array3,
            promedio :promedio,
            activo :activo,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
        },
    }); 
}
function GuardarGasto() {
    let array1 = new Array();
    let array2 = new Array();
    let array3 = new Array();
    let promedio = new Array();
    let estado = new Array();
    let tab = document.getElementById('gasto');
    
    for (let i = 0; i <numTomas ; i++) {
        array1.push(tab.rows[i+1].children[1].children[0].value);
        array2.push(tab.rows[i+1].children[2].children[0].value);
        array3.push(tab.rows[i+1].children[3].children[0].value);
        promedio.push(tab.rows[i+1].children[4].children[0].value);
        estado.push(tab.rows[i+1].children[5].children[0].value);
        }
    $.ajax({
        url: base_url + "/admin/campo/captura/GuardarGasto", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            idSolicitud:$("#idSolicitud").val(),
            array1 :array1,
            array2 :array2,
            array3 :array3,
            promedio :promedio,
            estado :estado,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
        },
    }); 
}
function GuardarVidrio() {
    let oxigeno = [];
    let burbuja = [];
    let tab = document.getElementById('vidrio');
    let numTomas = tab.rows.length - 1; // Resta 1 porque la primera fila es el encabezado

    for (let i = 1; i <= numTomas; i++) { // Comienza en 1 para omitir la fila de encabezado
        let oxigenoValor = parseFloat(tab.rows[i].cells[1].children[0].value).toFixed(1); // Obtiene el valor y redondea a 1 decimal
        oxigeno.push(oxigenoValor);

        let burbujaValor = tab.rows[i].cells[2].children[0].value === "si" ? 1 : 0;
        burbuja.push(burbujaValor);
    }

    $.ajax({
        url: base_url + "/admin/campo/captura/GuardarVidrio",
        type: "POST",
        data: {
            idSolicitud: $("#idSolicitud").val(),
            oxigeno: oxigeno,
            burbuja: burbuja,
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        }
    });
}

function SetDatosCompuestos(){
    $.ajax({
        url: base_url + "/admin/campo/captura/SetDatosCompuestos", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            idSolicitud:$("#idSolicitud").val(),
            metodoAforo:$("#aforoCompuesto").val(),
            ConTratamiento:$("#conTratamientoCompuesto").val(),
            TipoTratamiento:$("#tipoTratamientoCompuesto").val(),
            ProcedimientoMuestreo:$("#procedimientoCompuesto").val(),
            ProcedimientoMuestreo2:$("#procedimientoCompuesto2").val(),
            observacion:$("#observacionCompuesto").val(),
            volumenCalculado:$("#volCalculado").val(),
           // observacionSolicitud:$("#observacionSolicitud").val(),
            phMuestraCompuesta:$("#phMuestraCompuesto").val(),
            tempMuestraCompuesta:$("#valTemp").val(),
            cloruros:$('#valCloruros').val(),
            cloroMuestra:$("#cloroMuestra").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            
            alert("Datos de Campo Guardados!","success");
            window.location.href = base_url + "/admin/campo/capturar";
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
        c.value = "Aprobado";
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
        std.value = "Aprobado";
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
        std.value = "Aprobado";
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

function getPhTrazable(id,idg,sw) {
    let tab = document.getElementById("phTrazable")     
    let tab2 = document.getElementById("phControlCalidad")     
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

                if (sw == 1) {
                    tab2.rows[1].children[0].children[0].value = response.model2.Ph_calidad
                    tab2.rows[1].children[1].children[0].innerText = response.model2.Ph_calidad
                    tab2.rows[1].children[2].children[0].innerText = response.model2.Marca
                    tab2.rows[1].children[3].children[0].innerText = response.model2.Lote
                } else {
                    tab2.rows[2].children[0].children[0].value = response.model2.Ph_calidad
                    tab2.rows[2].children[1].children[0].innerText = response.model2.Ph_calidad
                    tab2.rows[2].children[2].children[0].innerText = response.model2.Marca
                    tab2.rows[2].children[3].children[0].innerText = response.model2.Lote
                }
                // $("#phCalidad"+idg).val(response.model2.Ph_calidad) 
                // $("#phCNombre"+idg).text(response.model2.Ph_calidad) 
                // $("#phCMarca"+idg).text(response.model2.Marca) 
                // $("#phCLote"+idg).text(response.model2.Marca) 
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

            puntoMuestreo: $("#puntoMuestreo").val(),

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
function muestraCancelada(num,std)
{
    let ph = document.getElementById("phMuestra")
    let tempAg = document.getElementById("tempAgua")
    let phC = document.getElementById("phControlCalidadMuestra")
    let con = document.getElementById("conductividad")
    let gas = document.getElementById("gasto")

    //Ph
    ph.rows[num].children[1].children[0].disabled = std;
    ph.rows[num].children[2].children[0].disabled = std;
    ph.rows[num].children[3].children[0].disabled = std;
    ph.rows[num].children[4].children[0].disabled = std;
    ph.rows[num].children[5].children[0].disabled = std;
    ph.rows[num].children[6].children[0].disabled = std;

    //Temp agua
    tempAg.rows[num].children[1].children[0].disabled = std;
    tempAg.rows[num].children[3].children[0].disabled = std;
    tempAg.rows[num].children[5].children[0].disabled = std;

    //phCalidad
    phC.rows[num].children[1].children[0].disabled = std;
    phC.rows[num].children[2].children[0].disabled = std;
    phC.rows[num].children[3].children[0].disabled = std;
    phC.rows[num].children[4].children[0].disabled = std;

    //Conductividad
    con.rows[num].children[1].children[0].disabled = std;
    con.rows[num].children[2].children[0].disabled = std;
    con.rows[num].children[3].children[0].disabled = std;

    //Gasto
    gas.rows[num].children[1].children[0].disabled = std;
    gas.rows[num].children[2].children[0].disabled = std;
    gas.rows[num].children[3].children[0].disabled = std;
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
function CancelarMuestra(){
    $.ajax({
        url: base_url + "/admin/campo/captura/CancelarMuestra", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            idSolicitud:$("#idSolicitud").val(),
            muestra: $("#selectCancelMuestra").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            muestraCancelada($("#selectCancelMuestra").val(),response.std)
            alert("Muestra cancelada");
        }
        
    });
}
function CancelarPunto(){
    $.ajax({
        url: base_url + "/admin/campo/captura/CancelarPunto", //archivo que recibe la peticion
        type: "POST", //método de envio
        data: {
            idSolicitud:$("#idSolicitud").val(),
            muestra: $("#selectCancelMuestra").val(),
            _token: $('input[name="_token"]').val(),
        },
        dataType: "json",
        async: false,
        success: function (response) {
            console.log(response);
            if(response.sw == true){
                alert("Punto cancelada");
            } else {
                alert("Error al cancelar. Es posible que ya existan Parametros para este punto!");
            }
           
        }
        
    });
}

