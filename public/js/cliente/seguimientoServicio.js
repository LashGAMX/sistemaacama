$(document).ready(function(){
    // $("#barra-progreso").css("width", "40%");
    // $("#contenedor-iconos").html(templateIconos2);


    $("#buscar-numero-seguimiento").click(function(){
        let numeroSeguimiento = $("#numero-seguimiento").val();
        $("#contenido").html(templateContenido);
        buscarNumeroSeguimiento(numeroSeguimiento);
    });
});

// 1 analisis y muestreo, 2 muestreo 3 analisis

var templateContenido = `
    <div class="row justify-content-center mb-5">
        <div class="col-12 col-md-10">
            <div class="position-relative">
                <div class="progress" role="progressbar" aria-label="Success striped example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: 0%" id="barra-progreso"></div>
                </div>
                <div id="contenedor-iconos">
                    
                </div>
            </div>
        </div>
    </div>
    <div class="row pt-md-5 pb-3">
        <div class="col">
            <details>
                <summary class="deshabilitado" id="orden-servicio">Orden de servicio</summary>
                <div>
                    <p>Cliente: <span id="cliente"></span></p> <!-- SISTEMA OPERADOR DE LOS SERVICIOS DE AGUA POTABLE Y ALCANTARILLADO DEL MUNICIPIO DE PUEBLA (SOAPAP) -->
                    <p>Norma : <span id="norma"></span></p> <!-- NOM-002-SEMARNAT-1996 -->
                    <p>Servicio : <span id="servicio"></span></p> <!-- Análisis y muestreo -->
                    <p>Siralab: <span id="siralab"></span></p> <!-- No -->
                    <p>Folio : <span id="folio"></span></p> <!-- 152-23/24 -->
                </div>
            </details>
        </div>
    </div>
    <div class="row pb-3">
        <div class="col">
            <details>
                <summary class="deshabilitado" id="muestreo">Muestreo</summary>
                <div>
                    <p id="punto"></p> <!-- //Capturando puntos de muestreo - Puntos de muestreo capturados -->
                </div>
            </details>
        </div>
    </div>
    <div class="row pb-3">
        <div class="col">
            <details>
                <summary class="deshabilitado" id="recepcion">Recepción</summary>
                <div>
                    <p id="texto-recepcion"></p> <!-- 2024-05-31 18:00:00 -->
                </div>
            </details>
        </div>
    </div>
    <div class="row pb-3">
        <div class="col">
            <details>
                <summary class="deshabilitado" id="ingreso-lab">Ingreso al lab</summary>
                <div>
                    <p id="estado-muestra"></p> <!-- Muestras ingresadas - Analizando muestras - Muestras analizadas -->
                </div>
            </details>
        </div>
    </div>
    <div class="row pb-3">
        <div class="col">
            <details>
                <summary class="deshabilitado" id="impresion">Impresión</summary>
                <div>
                    <p id="estado-impresion"></p> <!-- Informe pendiente por impresion - Informe impreso -->
                </div>
            </details>
        </div>
    </div>
`;

var templateIconos1 = `
    <div class="icono1">
        <img src="http://sistemasofia.ddns.net:85/sofiadev/public/storage/Icono-orden-servicio.png" alt="Logo Acama" width="35px" height="35px">
        <p class="etiqueta-icono d-none d-md-block">Orden de servicio</p>
    </div>
    <div class="icono2">
        <img src="http://sistemasofia.ddns.net:85/sofiadev/public/storage/Icono-muestreo.png" alt="Logo Acama" width="35px" height="35px">
        <p class="etiqueta-icono d-none d-md-block">Muestreo</p>
    </div>
    <div class="icono3">
        <img src="http://sistemasofia.ddns.net:85/sofiadev/public/storage/icono-recepcion.png" alt="Logo Acama" width="35px" height="35px">
        <p class="etiqueta-icono d-none d-md-block">Recepción</p>
    </div>
    <div class="icono4">
        <img src="http://sistemasofia.ddns.net:85/sofiadev/public/storage/Icono-ingreso-al-lab.png" alt="Logo Acama" width="35px" height="35px">
        <p class="etiqueta-icono d-none d-md-block">Ingreso al lab</p>
    </div>
    <div class="icono5">
        <img src="http://sistemasofia.ddns.net:85/sofiadev/public/storage/Icono-impresion.png" alt="Logo Acama" width="35px" height="35px">
        <p class="etiqueta-icono d-none d-md-block">Impresión</p>
    </div>
`;

var templateIconos2 = `
    <div class="icono1-v2">
        <img src="http://sistemasofia.ddns.net:85/sofiadev/public/storage/Icono-orden-servicio.png" alt="Logo Acama" width="35px" height="35px">
        <p class="etiqueta-icono d-none d-md-block">Orden de servicio</p>
    </div>
    <div class="icono2-v2">
        <img src="http://sistemasofia.ddns.net:85/sofiadev/public/storage/icono-recepcion.png" alt="Logo Acama" width="35px" height="35px">
        <p class="etiqueta-icono d-none d-md-block">Recepción</p>
    </div>
    <div class="icono3-v2">
        <img src="http://sistemasofia.ddns.net:85/sofiadev/public/storage/Icono-ingreso-al-lab.png" alt="Logo Acama" width="35px" height="35px">
        <p class="etiqueta-icono d-none d-md-block">Ingreso al lab</p>
    </div>
    <div class="icono4-v2">
        <img src="http://sistemasofia.ddns.net:85/sofiadev/public/storage/Icono-impresion.png" alt="Logo Acama" width="35px" height="35px">
        <p class="etiqueta-icono d-none d-md-block">Impresión</p>
    </div>
`;

function buscarNumeroSeguimiento(numeroSeguimiento){
    $.ajax({
        type: 'POST',
        data: {
            numero_seguimiento: numeroSeguimiento,
            "_token": $("meta[name='csrf-token']").attr("content"),
        },
        dataType: 'json',
        url: 'http://sistemasofia.ddns.net:85/sofiadev/clientes/getFolioServicio',
        success: function(response){
            if(response.error != null){
                $("#error-numero-seguimiento").html(response.error);
            }
            else{
                $("#error-numero-seguimiento").html('');
                if(response.estado_muestreo == 1){
                    $("#contenedor-iconos").html(templateIconos1);
                }
                else{
                    $("#contenedor-iconos").html(templateIconos2);
                }
                $("#barra-progreso").css("width", response.porcentaje + "%");

                console.log(response);
                if(response.orden_servicio.cliente == '' && response.orden_servicio.norma == '' && response.orden_servicio.servicio == '' && response.orden_servicio.folio == '' && response.orden_servicio.siralab == ''){
                    $("#orden-servicio").addClass("deshabilitado");
                }
                else{
                    $("#orden-servicio").removeClass("deshabilitado");
                    $("#cliente").html(response.orden_servicio.cliente);
                    $("#norma").html(response.orden_servicio.norma);
                    $("#servicio").html(response.orden_servicio.servicio);
                    $("#siralab").html(response.orden_servicio.siralab);
                    $("#folio").html(response.orden_servicio.folio);
                }
                habilitarFila(response.muestreo, "#muestreo");
                if(response.muestreo != ''){
                    $("#punto").html(response.muestreo);
                }
                habilitarFila(response.recepcion, "#recepcion");
                if(response.recepcion != ''){
                    $("#texto-recepcion").html(response.recepcion);
                }
                habilitarFila(response.ingreso_lab, "#ingreso-lab");
                if(response.ingreso_lab != ''){
                    $("#estado-muestra").html(response.ingreso_lab);
                }
                habilitarFila(response.impresion, "#impresion");
                if(response.impresion != ''){
                    $("#estado-impresion").html(response.impresion);
                }
                if(response.porcentaje == '100'){
                    $("#barra-progreso").removeClass("progress-bar-striped");
                }
                else{
                    $("#barra-progreso").addClass("progress-bar-striped");
                }
            }
        }
    });
}

function habilitarFila(valor, campo){
    if(valor != ''){
        $(campo).removeClass("deshabilitado");
    }
    else{
        $(campo).addClass("deshabilitado");
    }
}