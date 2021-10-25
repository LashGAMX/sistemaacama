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
        <span id="fecha">Fecha de análisis</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span></span>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <span id="hora">Hora</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span></span>
    </p>

    <p id="resMuestras">Resultado de las muestras</p>

    <table class="table table-borderless">
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
        </tbody>
    </table>  
    
    <br>
    
    <div id="prueba1">
        <div id="prueba2">
            <table class="table table-borderless">
                <thead>
                    <tr>
                        <td id="analizo">
                            ANALIZÓ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </td>                    

                        <td id="superviso">SUPERVISÓ</td>
                    </tr>
                </thead>            

                <tbody>
                    <tr>
                        <td><span>AQUÍ VA LA FIRMA</span></td>
                        <td><span>AQUÍ VA LA FIRMA</span></td>
                    </tr>
                    <tr>
                        <td id="nombreAnalizo">NOMBRE ANALIZÓ</td>
                        <td id="nombreSuperviso">NOMBRE SUPERVISÓ</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>