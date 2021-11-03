<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/laboratorio/curvaPDF2.css')}}">
    <title>Captura</title>
</head>
<body>
    <div id="procedimientoBody2">
        <span>DPR = ((|C1-C2|)/((C1+C2)/2))*100</span>
        <br>
        <span>Dónde:</span>
        <br>
        <span>•C1 = Concentración de la muestra</span>
        <br>
        <span>•C2 = Concentración de la muestra duplicada</span>
        <br>
        <span>Criterio < 20%</span>        
    </div>    

    <div class="contenedorPrincipal">                                        
        <div class="subContenedor">                        
            <span class="cabeceraStdMuestra">FECHA DE ANÁLISIS: </span>
            <span class="bodyStdMuestra">FECHA DE ANÁLISIS</span>
        </div>

        <div class="subContenedor">
            <span class="cabeceraStdMuestra">HORA DE ANÁLISIS: </span>
            <span class="bodyStdMuestra">HORA DE ANÁLISIS</span>
        </div>

        <div class="subContenedor">
            <span class="cabeceraStdMuestra">FECHA DE DIGESTIÓN: </span>                    
            <span class="bodyStdMuestra">FECHA DE DIGESTIÓN</span>
        </div>

        <div class="subContenedor">
            <span class="cabeceraStdMuestra">HORA DE DIGESTIÓN: </span>
            <span class="bodyStdMuestra">HORA DE DIGESTIÓN</span>
        </div>
    </div>

    <div class="contenedorSecundario">                        
        <div class="subContenedor2">            
            <span class="elementos"> ESPECTROFOTÓMETRO DE ABSORCIÓN ATÓMICA <span><br></span> PERKIN ELMER MODELO: </span>
            <span class="subElementos">AANALYST 200 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;
            </span>
        </div>

        <div class="subContenedor2">
            <span class="elementos">CORRIENTE DE LA LÁMPARA: </span>
            <span class="subElementos">12.0 mA</span>
        </div>

        <div class="subContenedor2">
            <span class="elementos">No. DE INV. LÁMPARA: </span>                    
            <span class="subElementos">INVLAB 46</span>
        </div>

        <div class="subContenedor2">
            <span class="elementos">ENERGÍA DE LÁMPARA: </span>
            <span class="subElementos">72</span>
        </div>
    </div>

    <div class="contenedorTerciario">                        
        <div class="subContenedor3">            
            <span class="elementos"> No. DE INVENTARIO: </span>
            <span class="subElementos">INVLAB-88-1</span>
        </div>

        <div class="subContenedor3">
            <span class="elementos">LONGITUD DE ONDA: </span>
            <span class="subElementos">324.75 nm</span>
        </div>

        <div class="subContenedor3">
            <span class="elementos">SLIT: </span>                    
            <span class="subElementos">2.7/0.80 mm</span>
        </div>

        <div class="subContenedor3">
            <span class="elementos">ACETILENO: </span>
            <span class="subElementos">2.50 L/min</span>
        </div>

        <div class="subContenedor3">
            <span class="elementos">AIRE: </span>
            <span class="subElementos">10.00 L/min</span>
        </div>

        <div class="subContenedor3">
            <span class="elementos">ÓXIDO NITROSO: </span>
            <span class="subElementos">NA</span>
        </div>
    </div>

    <div class="contenedorCuarto">                        
        <div class="subContenedor4">            
            <span class="verifEspectro"> VERIFICACIÓN DEL <span><br></span> ESPECTROFOTÓMETRO</span>            
        </div>

        <div class="subContenedor4">
            <span class="elementos">STD.CAL.2 </span>            
        </div>

        <div class="subContenedor4">
            <span class="elementos">ABS. TEÓRICA: .352</span>
        </div>

        <div class="subContenedor4">
            <span class="elementos">ABS 1: .342</span>            
        </div>

        <div class="subContenedor4">
            <span class="elementos">ABS 2: .342</span>            
        </div>

        <div class="subContenedor4">
            <span class="elementos">ABS 3: .34 </span>            
        </div>

        <div class="subContenedor4">
            <span class="elementos">ABS 4: N.A. </span>            
        </div>

        <div class="subContenedor4">
            <span class="elementos">ABS 5: N.A. </span>            
        </div>
    </div>

    <div class="contenedorQuinto">                        
        <div class="subContenedor5">            
            <span class="verifEspectro">CURVA DE CALIBRACIÓN</span>
            <br>
            <span class="elementosCurvaCalib">Ver. RE-12-001-24-1 Bit.3 Folio: 378. (Bitacora para la preparación de estándares y curvas de calibración)</span>

            <table autosize="1" class="table table-borderless" id="tablaDatos">
                <thead>
                    <tr>
                        <th id="tableCabecera">&nbsp;</th>
                        <th id="tableCabecera">&nbsp;STD1&nbsp;&nbsp;</th>
                        <th id="tableCabecera">&nbsp;STD2&nbsp;&nbsp;</th>
                        <th id="tableCabecera">&nbsp;STD3&nbsp;&nbsp;</th>
                        <th id="tableCabecera">&nbsp;STD4&nbsp;&nbsp;</th>
                        <th id="tableCabecera">&nbsp;STD5&nbsp;&nbsp;</th>
                        <th id="tableCabecera">&nbsp;<span class="bmrTabla">b = </span>&nbsp;&nbsp;</th>
                        <th id="tableCabecera">&nbsp;Valor de b&nbsp;&nbsp;</th>
                    </tr>
                </thead>
        
                <tbody>
                    <tr>
                        <td id="tableContent">CONCENTRACIÓN EN mg/L</td>
                        <td id="tableContent">VAL</td>
                        <td id="tableContent">VAL</td>
                        <td id="tableContent">VAL</td>
                        <td id="tableContent">VAL</td>
                        <td id="tableContent">VAL</td>
                        <td id="tableContent"><span class="bmrTabla">m = </span></td>
                        <td id="tableContent">VAL</td>
                    </tr>
                    <tr>
                        <td id="tableContent">ABSORBANCIA 1</td>
                        <td id="tableContent">VAL</td>
                        <td id="tableContent">VAL</td>
                        <td id="tableContent">VAL</td>
                        <td id="tableContent">VAL</td>
                        <td id="tableContent">VAL</td>
                        <td id="tableContent"><span class="bmrTabla">r = </span></td>
                        <td id="tableContent">VAL</td>
                    </tr>
                    <tr>
                        <td id="tableContent">ABSORBANCIA 2</td>
                        <td id="tableContent">VAL</td>
                        <td id="tableContent">VAL</td>
                        <td id="tableContent">VAL</td>
                        <td id="tableContent">VAL</td>
                        <td id="tableContent">VAL</td>
                        <td id="tableContent"><span class="bmrTabla">Fecha de preparación = </span></td>
                        <td id="tableContent">FECHA</td>
                    </tr>
                    <tr>
                        <td id="tableContent">ABSORBANCIA 3</td>
                        <td id="tableContent">VAL</td>
                        <td id="tableContent">VAL</td>
                        <td id="tableContent">VAL</td>
                        <td id="tableContent">VAL</td>
                        <td id="tableContent">VAL</td>
                        <td id="tableContent"><span class="bmrTabla">Límite de cuantificación = </span></td>
                        <td id="tableContent">LÍM</td>
                    </tr>
                    <tr>
                        <td id="tableContent">ABSORBANCIA PROM.</td>
                        <td id="tableContent">VAL</td>
                        <td id="tableContent">VAL</td>
                        <td id="tableContent">VAL</td>
                        <td id="tableContent">VAL</td>
                        <td id="tableContent">VAL</td>
                        <td id="tableContent"></td>
                        <td id="tableContent"></td>
                    </tr>
                </tbody>        
            </table>
        </div>

        <div class="subContenedor6">
            <span class="elementos">
                NOTA: Ver condición para el cálculo del porcentaje de recuperación de la muestra 
                adicionada (ma) en procedimiento PG-05-014, en apartado de "ACTIVIDADES DIARIAS, MENSUALES O 
                SEMESTRALES" punto número 12.</span>
        </div>                 
    </div>

    <div class="contenedorSexto">                
        <span><br> Absorbancia B1: 0</span> <br>
        <span>Absorbancia B2: 0</span> <br>
        <span>Absorbancia B3: 0</span> <br>
        <span>RESULTADO BLANCO: 0</span>
    </div>
</body>
</html>