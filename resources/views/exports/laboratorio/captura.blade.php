<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/laboratorio/capturaPDF.css')}}">
    <title>Captura PDF</title>
</head>
<body>
        
    <p id="header1">NMX-AA-051-SCFI-2016 PROTOCOLO DE TRABAJO: PE-10-002-28 MEDICIÓN DE METALES POR ABSORCIÓN ATÓMICA EN AGUAS NATURALES, POTABLES, RESIDUALES Y RESIDUALES TRATADAS</p>    
    
    <p id="header2">
        METALES TOTALES CON FLAMA 
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        ELEMENTO: {{$formulaSelected}}
    </p>
       
    <p>
        <span id="fecha">Fecha de análisis</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span>AQUÍ VA LA FECHA</span>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <span id="hora">Hora</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span> AQUÍ VA LA HORA</span>
    </p>
        
    <p id="resMuestras">Resultado de las muestras</p>    
    
    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos">
            <thead>
                <tr>
                    <td id="tableCabecera">No. de muestra &nbsp;</td>
                    <td id="tableCabecera">&nbsp;Volumen de muestra (mL)&nbsp;&nbsp;</td>
                    <td id="tableCabecera">&nbsp;Es pH < 2&nbsp;&nbsp;</td>
                    <td id="tableCabecera">&nbsp;Abs 1&nbsp;&nbsp;</td>
                    <td id="tableCabecera">&nbsp;Abs 2&nbsp;&nbsp;</td>
                    <td id="tableCabecera">&nbsp;Abs 3&nbsp;&nbsp;</td>
                    <td id="tableCabecera">&nbsp;Absorbancia Promedio&nbsp;&nbsp;</td>
                    <td id="tableCabecera">&nbsp;Abs Muestra - Abs Blanco&nbsp;&nbsp;</td>
                    <td id="tableCabecera">&nbsp;[mg/L] Obtenida&nbsp;&nbsp;</td>
                    <td id="tableCabecera">&nbsp;F.D.&nbsp;&nbsp;</td>
                    <td id="tableCabecera">&nbsp;[mg/L] Reportada&nbsp;&nbsp;</td>
                    <td id="tableCabecera">&nbsp;Observaciones&nbsp;</td>
                    <td></td>
                </tr>
            </thead>
    
            <tbody>
                @for ($i = 0; $i < 100; $i++)
                    <tr>
                        <td id="tableContent">PRUEBA</td>
                        <td id="tableContent">PRUEBA</td>
                        <td id="tableContent">PRUEBA</td>
                        <td id="tableContent">PRUEBA</td>
                        <td id="tableContent">PRUEBA</td>
                        <td id="tableContent">PRUEBA</td>
                        <td id="tableContent">PRUEBA</td>
                        <td id="tableContent">PRUEBA</td>
                        <td id="tableContent">PRUEBA</td>
                        <td id="tableContent">PRUEBA</td>
                        <td id="tableContent">PRUEBA</td>
                        <td id="tableContent">PRUEBA</td>
                        <td id="tableContent">PRUEBA</td>                
                    </tr>                
                @endfor                        
            </tbody>        
        </table>  
    </div>    

    <br>

    <div class="contenedorPadre">
        <div class="contenedorHijo1">            
            <span class="cabeceraStdMuestra"> ESTÁNDAR CONTROL <br> </span>
                <span class="bodyStdMuestra">Criterio de aceptación para Std ctrl 95-105%. Fórmula; Recuperación(%) = C1/C2x100. Donde: C1 = Concentración leída.
                    C2 = Concentración Real.
                </span>
        </div>

        <div class="contenedorHijo1">
            <span class="cabeceraStdMuestra">MUESTRA DUPLICADA <br> </span>                                    
            <span class="bodyStdMuestra">DPR (DIFERENCIAL PORCENTUAL RELATIVA) MUESTRA DUPLICADA: La DPR de cada analito obtenido entre la muestra y la
                duplicada debe ser: < 20%. Fórmula: DPR = (|C1-C2|)/[(C1+C2)*100]. Donde: C1 - Concentración de la primera muestra.
                C2 - Concentración de la segunda muestra (muestra duplicada).
            </span>                   
        </div>

        <div class="contenedorHijo1">
            <span class="cabeceraStdMuestra">MUESTRA ADICIONADA <br> </span>                    
            <span class="bodyStdMuestra">Criterio de Aceptación para MA  85 - 115%. Fórmula: Recuperación n = (Cr*V1)/Ca*V 100%. Donde: V = Volúmen del
                estándar usado para la muestra adicionada. Ca = Concentración del estándar. V1 = Volúmen de la muestra usada
                para la muestra adicionada. Cr = Concentración de muestra probable. Concentración de la muestra adicionada. Recuperación
                n: porcentaje adicionado que es medido.
            </span>                   
        </div>
    </div>        

    <br>        
</body>
</html>