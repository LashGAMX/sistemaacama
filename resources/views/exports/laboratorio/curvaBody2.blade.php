<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/metales/curvaPDF2.css')}}">
    <title>Captura</title>
</head>
<body>        


    <div class="contenedorPrincipal">                                        
        <div class="subContenedor">                        
            <span class="cabeceraStdMuestra">FECHA DE ANÁLISIS: </span>
            <span class="bodyStdMuestra">{{$fechaConFormato}}</span>
        </div>

        <div class="subContenedor">
            <span class="cabeceraStdMuestra">HORA DE ANÁLISIS: </span>
            <span class="bodyStdMuestra">HORA DE ANÁLISIS</span>
        </div>

        <div class="subContenedor">
            <span class="cabeceraStdMuestra">FECHA DE DIGESTIÓN: </span>                    
            <span class="bodyStdMuestra">{{$soloFechaFormateada}}</span>
        </div>

        <div class="subContenedor">
            <span class="cabeceraStdMuestra">HORA DE DIGESTIÓN: </span>
            <span class="bodyStdMuestra">{{$soloHoraFormateada}}</span>
        </div>
    </div>

    <div class="contenedorSecundario">                        
        <div class="subContenedor2">            
            <span class="elementos"> ESPECTROFOTÓMETRO DE ABSORCIÓN ATÓMICA <span><br></span> PERKIN ELMER MODELO: </span>
            <span class="subElementos">{{$tecnicaMetales->Equipo}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;
            </span>
        </div>

        <div class="subContenedor2">
            <span class="elementos">CORRIENTE DE LA LÁMPARA: </span>
            <span class="subElementos">{{$tecnicaMetales->Corriente}}</span>
        </div>

        <div class="subContenedor2">
            <span class="elementos">No. DE INV. LÁMPARA: </span>                    
            <span class="subElementos">{{$tecnicaMetales->Num_invent_lamp}}</span>
        </div>

        <div class="subContenedor2">
            <span class="elementos">ENERGÍA DE LÁMPARA: </span>
            <span class="subElementos">{{$tecnicaMetales->Energia}}</span>
        </div>
    </div>

    <div class="contenedorTerciario">                        
        <div class="subContenedor3">            
            <span class="elementos"> No. DE INVENTARIO: </span>
            <span class="subElementos">{{$tecnicaMetales->Num_inventario}}</span>
        </div>

        <div class="subContenedor3">
            <span class="elementos">LONGITUD DE ONDA: </span>
            <span class="subElementos">{{$tecnicaMetales->Longitud_onda}}</span>
        </div>

        <div class="subContenedor3">
            <span class="elementos">SLIT: </span>                    
            <span class="subElementos">{{$tecnicaMetales->Slit}}</span>
        </div>

        <div class="subContenedor3">
            <span class="elementos">ACETILENO: </span>
            <span class="subElementos">{{$tecnicaMetales->Gas}}</span>
        </div>

        <div class="subContenedor3">
            <span class="elementos">AIRE: </span>
            <span class="subElementos">{{$tecnicaMetales->Aire}}</span>
        </div>

        <div class="subContenedor3">
            <span class="elementos">ÓXIDO NITROSO: </span>
            <span class="subElementos">{{$tecnicaMetales->Oxido_nitroso}}</span>
        </div>
    </div>

    <div class="contenedorCuarto">                        
        <div class="subContenedor4">            
            <span class="verifEspectro"> VERIFICACIÓN DEL <span><br></span> ESPECTROFOTÓMETRO</span>            
        </div>

        <div class="subContenedor4">
            <span class="elementos">STD.CAL. {{$verificacionMetales->STD_cal}} </span>            
        </div>

        <div class="subContenedor4">
            <span class="elementos">ABS. TEÓRICA: {{$verificacionMetales->ABS_teorica}}</span>
        </div>

        <div class="subContenedor4">
            <span class="elementos">ABS 1: {{$verificacionMetales->ABS1}}</span>
        </div>

        <div class="subContenedor4">
            <span class="elementos">ABS 2: {{$verificacionMetales->ABS2}}</span>
        </div>

        <div class="subContenedor4">
            <span class="elementos">ABS 3: {{$verificacionMetales->ABS3}}</span>
        </div>

        <div class="subContenedor4">
            <span class="elementos">ABS 4: {{$verificacionMetales->ABS4}}</span>
        </div>

        <div class="subContenedor4">
            <span class="elementos">ABS 5: {{$verificacionMetales->ABS5}}</span>
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
                        <th id="tableCabecera">&nbsp;Blanco&nbsp;&nbsp;</th>
                        <th id="tableCabecera">&nbsp;STD1&nbsp;&nbsp;</th>
                        <th id="tableCabecera">&nbsp;STD2&nbsp;&nbsp;</th>
                        <th id="tableCabecera">&nbsp;STD3&nbsp;&nbsp;</th>
                        <th id="tableCabecera">&nbsp;STD4&nbsp;&nbsp;</th>
                        <th id="tableCabecera">&nbsp;STD5&nbsp;&nbsp;</th>
                        <th id="tableCabecera">&nbsp;<span class="bmrTabla">b = </span>&nbsp;&nbsp;</th>
                        <th id="tableContent">&nbsp;<span class="bTabla">{{$bmr->B}}</span>&nbsp;&nbsp;</th>
                    </tr>
                </thead>
        
                <tbody>
                    <tr>
                        <td id="tableContent">CONCENTRACIÓN EN mg/L</td>

                        <td id="tableContent"></td>
                        
                        @for ($i = 1; ($i < $estandares->count()); $i++)                            
                            <td id="tableContent">{{$estandares[$i]->Concentracion}}</td>
                        @endfor
                        
                        <td id="tableContent"><span class="bmrTabla">m = </span></td>
                        <td id="tableContent">{{$bmr->M}}</td>
                    </tr>
                    <tr>
                        <td id="tableContent">ABSORBANCIA 1</td>
                        
                        <td id="tableContent">{{$estandares[0]->ABS1}}</td>

                        @for ($i = 1; ($i < $estandares->count()); $i++)                            
                            <td id="tableContent">{{$estandares[$i]->ABS1}}</td>
                        @endfor                                                
                        
                        <td id="tableContent"><span class="bmrTabla">r = </span></td>
                        <td id="tableContent">{{$bmr->R}}</td>
                    </tr>
                    <tr>
                        <td id="tableContent">ABSORBANCIA 2</td>

                        <td id="tableContent">{{$estandares[0]->ABS2}}</td>
                        
                        @for ($i = 1; ($i < $estandares->count()); $i++)                            
                            <td id="tableContent">{{$estandares[$i]->ABS2}}</td>
                        @endfor

                        <td id="tableContent"><span class="bmrTabla">Fecha de preparación = </span></td>
                        <td id="tableContent">{{$fechaPreparacion}}</td>
                    </tr>
                    <tr>
                        <td id="tableContent">ABSORBANCIA 3</td>

                        <td id="tableContent">{{$estandares[0]->ABS3}}</td>
                        
                        @for ($i = 1; ($i < $estandares->count()); $i++)                            
                            <td id="tableContent">{{$estandares[$i]->ABS3}}</td>
                        @endfor

                        <td id="tableContent"><span class="bmrTabla">Límite de cuantificación = </span></td>
                        <td id="tableContent">{{$limiteCuantificacion->Limite}}</td>
                    </tr>
                    <tr>
                        <td id="tableContent">ABSORBANCIA PROM.</td>

                        <td id="tableContent">{{$estandares[0]->Promedio}}</td>
                        
                        @for ($i = 1; ($i < $estandares->count()); $i++)                            
                            <td id="tableContent">{{$estandares[$i]->Promedio}}</td>
                        @endfor

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
        <span><br> Absorbancia B1: {{$estandares[0]->ABS1}}</span> <br>
        <span>Absorbancia B2: {{$estandares[0]->ABS2}}</span> <br>
        <span>Absorbancia B3: {{$estandares[0]->ABS3}}</span> <br>
        <span>RESULTADO BLANCO: {{$estandares[0]->Promedio}}</span>
    </div>
</body>
</html>