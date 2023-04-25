<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/metales/curvaPDF.css')}}">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/metales/curvaPDF2.css')}}">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/metales/capturaPDF.css')}}">
    <title>Captura PDF</title>
</head>
<body>
    
    <div id="contenidoCurva">
        @php
            echo $plantilla[0]->Texto;
        @endphp
    </div>    

    <div class="contenedorPrincipal">                                        

        <div class="subContenedor">
            <span class="cabeceraStdMuestra">FECHA DE DIGESTIÓN: </span>                    
            <span class="bodyStdMuestra">{{@$fechaHora->toDateString()}}</span>
        </div>

        <div class="subContenedor">
            <span class="cabeceraStdMuestra">HORA DE DIGESTIÓN: </span>
            <span class="bodyStdMuestra">{{@$fechaHora->toDateString()}}</span>
        </div>

        <div class="subContenedor2">            
            <span class="elementos"> ESPECTROFOTÓMETRO DE ABSORCIÓN ATÓMICA <span><br></span> PERKIN ELMER MODELO: </span>
            <span class="subElementos">{{@$tecnicaMetales->Equipo}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;
            </span>
        </div>

        <div class="subContenedor2">
            <span class="elementos">CORRIENTE DE LA LÁMPARA: </span>
            <span class="subElementos">{{@$tecnicaMetales->Corriente}}</span>
        </div>
    </div>

    
    <div class="contenedorSecundario">                                

        <div class="subContenedor2">
            <span class="elementos">No. DE INV. LÁMPARA: </span>                    
            <span class="subElementos">{{@$tecnicaMetales->Num_invent_lamp}}</span>
        </div>

        <div class="subContenedor2">
            <span class="elementos">ENERGÍA DE LÁMPARA: </span>
            <span class="subElementos">{{@$tecnicaMetales->Energia}}</span>
        </div>
    </div>
    <div class="contenedorTerciario">                        
        <div class="subContenedor3">            
            <span class="elementos"> No. DE INVENTARIO: </span>
            <span class="subElementos">{{@$tecnicaMetales->Num_inventario}}</span>
        </div>

        <div class="subContenedor3">
            <span class="elementos">LONGITUD DE ONDA: </span>
            <span class="subElementos">{{@$tecnicaMetales->Longitud_onda}}</span>
        </div>

        <div class="subContenedor3">
            <span class="elementos">SLIT: </span>                    
            <span class="subElementos">{{@$tecnicaMetales->Slit}} mm</span>
        </div>

        <div class="subContenedor3">
            <span class="elementos">ACETILENO: </span>
            <span class="subElementos">{{@$tecnicaMetales->Gas}} L/min</span>
        </div>

        <div class="subContenedor3">
            <span class="elementos">AIRE: </span>
            <span class="subElementos">{{@$tecnicaMetales->Aire}} L/min</span>
        </div>

        <div class="subContenedor3">
            <span class="elementos">ÓXIDO NITROSO: </span>
            <span class="subElementos">{{@$tecnicaMetales->Oxido_nitroso}}</span>
        </div>
    </div>

    <div class="contenedorCuarto">                        
        <div class="subContenedor4">            
            <span class="verifEspectro"> VERIFICACIÓN DEL <span><br></span> ESPECTROFOTÓMETRO</span>            
        </div>

        <div class="subContenedor4">
            <span class="elementos">STD.CAL. {{@$verificacionMetales->STD_cal}} </span>            
        </div>

        <div class="subContenedor4">
            <span class="elementos">ABS. TEÓRICA: {{@$verificacionMetales->ABS_teorica}}</span>
        </div>

        <div class="subContenedor4">
            <span class="elementos">ABS 1: {{@$verificacionMetales->ABS1}}</span>
        </div>

        <div class="subContenedor4">
            <span class="elementos">ABS 2: {{@$verificacionMetales->ABS2}}</span>
        </div>

        <div class="subContenedor4">
            <span class="elementos">ABS 3: {{@$verificacionMetales->ABS3}}</span>
        </div>

        <div class="subContenedor4">
            <span class="elementos">ABS 4: {{@$verificacionMetales->ABS4}}</span>
        </div>

        <div class="subContenedor4">
            <span class="elementos">ABS 5: {{@$verificacionMetales->ABS5}}</span>
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
                        </span>&nbsp;&nbsp;</th>
                    </tr>                           
                </thead>
        
                <tbody>
                    <tr>
                        <td id="tableContent">CONCENTRACIÓN EN mg/L</td>
                        <td id="tableContent"></td>
                        <td id="tableContent"></td>
                        <td id="tableContent"></td>
                        <td id="tableContent"></td>
                        <td id="tableContent"></td>
                        <td id="tableContent"></td>
                        @if (@$tecnicaUsada->Id_tecnica == 22)
                            <td id="tableContent">CONCENTRACIÓN EN μg/L</td>
                        @else
                            <td id="tableContent">CONCENTRACIÓN EN mg/L</td>
                        @endif                        

                        <td id="tableContent">                            
                            @php
                                echo number_format(@$estandares[0]->Concentracion, 3, ".", ",");
                            @endphp                            
                        </td>
                        
                        @for ($i = 1; ($i < @$estandares->count()); $i++)                            
                            @if(@$estandares[$i]->Concentracion != null)
                                <td id="tableContent">
                                    @php
                                        echo number_format(@$estandares[$i]->Concentracion, 3, ".", ",");
                                    @endphp                                      
                                </td>
                            @endif                            
                        @endfor
                        
                        <td id="tableContent"><span class="bmrTabla">m = </span></td>
                        <td id="tableContent">
                            @php
                                echo number_format(@$bmr->M, 5, ".", ".");
                            @endphp
                        </td>
                    </tr>
                    <tr>
                        <td id="tableContent">ABSORBANCIA 1</td>
                        
                        <td id="tableContent">                            
                            @php
                                echo number_format(@$estandares[0]->ABS1, 3, ".", ",");
                            @endphp
                        </td>

                        @for ($i = 1; ($i < @$estandares->count()); $i++) 
                            @if(@$estandares[$i]->ABS1 != null)                           
                                <td id="tableContent">
                                    @php
                                        echo number_format(@$estandares[$i]->ABS1, 3, ".", ".");
                                    @endphp                                    
                                </td>
                            @endif
                        @endfor                                                
                        
                        <td id="tableContent"><span class="bmrTabla">r = </span></td>
                        <td id="tableContent">
                            @php
                                echo number_format(@$bmr->R, 5, ".", ".");
                            @endphp
                        </td>
                    </tr>
                    <tr>
                        <td id="tableContent">ABSORBANCIA 2</td>

                        <td id="tableContent">                            
                            @php
                                echo number_format(@$estandares[0]->ABS2, 3, ".", ",");
                            @endphp
                        </td>
                        
                        @for ($i = 1; ($i < @$estandares->count()); $i++)
                            @if(@$estandares[$i]->ABS2 != null)
                                <td id="tableContent">
                                    @php
                                        echo number_format(@$estandares[$i]->ABS2, 3, ".", ".");
                                    @endphp                                      
                                </td>
                            @endif
                        @endfor

                        <td id="tableContent"><span class="bmrTabla">Fecha de preparación = </span></td>
                        <td id="tableContent">{{@$fechaPreparacion}}</td>
                    </tr>
                    <tr>
                        <td id="tableContent">ABSORBANCIA 3</td>

                        <td id="tableContent">                            
                            @php
                                echo number_format(@$estandares[0]->ABS3, 3, ".", ",");
                            @endphp
                        </td>
                        
                        @for ($i = 1; ($i < @$estandares->count()); $i++)
                            @if(@$estandares[$i]->ABS3 != null)
                                <td id="tableContent">
                                    @php
                                        echo number_format(@$estandares[$i]->ABS3, 3, ".", ".");
                                    @endphp  
                                </td>
                            @endif
                        @endfor

                        <td id="tableContent"><span class="bmrTabla">Límite de cuantificación = </span></td>
                        <td id="tableContent">< {{@$model[0]->Limite}}</td>
                    </tr>
                    <tr>
                        <td id="tableContent">ABSORBANCIA PROM.</td>

                        <td id="tableContent">                            
                            @php
                                echo number_format(@$estandares[0]->Promedio, 3, ".", ",");
                            @endphp
                        </td>
                        
                        @for ($i = 1; ($i < @$estandares->count()); $i++)                            
                            @if(@$estandares[$i]->Promedio != null)
                                <td id="tableContent">
                                    @php
                                        echo number_format(@$estandares[$i]->Promedio, 3, ".", ".");
                                    @endphp
                                </td>
                            @endif
                        @endfor

                        <td id="tableContent"></td>
                        <td id="tableContent"></td>
                    </tr>
                </tbody>        
            </table>
        </div>                       
    </div>

    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos">
            <thead>
                <tr>
                    @if (@$tecnicaUsada->Id_tecnica == 22)
                        <td id="tableCabecera">No. de muestra &nbsp;</td>
                        <td id="tableCabecera">&nbsp;Volumen de muestra (mL)&nbsp;&nbsp;</td>
                        <td id="tableCabecera">&nbsp;Es pH<2&nbsp;&nbsp;</td>
                        <td id="tableCabecera">&nbsp;Abs 1&nbsp;&nbsp;</td>
                        <td id="tableCabecera">&nbsp;Abs 2&nbsp;&nbsp;</td>
                        <td id="tableCabecera">&nbsp;Abs 3&nbsp;&nbsp;</td>
                        <td id="tableCabecera">&nbsp;Abs Promedio&nbsp;&nbsp;</td>                        
                        <td id="tableCabecera">&nbsp;[μg/L] Obtenida&nbsp;&nbsp;</td>
                        <td id="tableCabecera">&nbsp;F.D.&nbsp;&nbsp;</td>
                        <td id="tableCabecera">&nbsp;F.C.&nbsp;&nbsp;</td>
                        <td id="tableCabecera">&nbsp;[mg/L] Reportada&nbsp;&nbsp;</td>
                        <td id="tableCabecera">&nbsp;Observaciones&nbsp;</td>
                        <td id="tableCabecera"></td>
                        <td id="tableCabecera"></td>    
                    @else
                        <td id="tableCabecera">No. de muestra &nbsp;</td>
                        <td id="tableCabecera">&nbsp;Volumen de muestra (mL)&nbsp;&nbsp;</td>
                        <td id="tableCabecera">&nbsp;Es pH<2&nbsp;&nbsp;</td>
                        <td id="tableCabecera">&nbsp;Abs 1&nbsp;&nbsp;</td>
                        <td id="tableCabecera">&nbsp;Abs 2&nbsp;&nbsp;</td>
                        <td id="tableCabecera">&nbsp;Abs 3&nbsp;&nbsp;</td>
                        <td id="tableCabecera">&nbsp;Abs Promedio&nbsp;&nbsp;</td>
                        <td id="tableCabecera">&nbsp;Abs Muestra - Abs Blanco&nbsp;&nbsp;</td>
                        <td id="tableCabecera">&nbsp;[mg/L] Obtenida&nbsp;&nbsp;</td>
                        <td id="tableCabecera">&nbsp;F.D.&nbsp;&nbsp;</td>
                        <!-- <td id="tableCabecera">&nbsp;Resultado c/factor aplicado&nbsp;&nbsp;</td> -->
                        <td id="tableCabecera">&nbsp;[mg/L] Reportada&nbsp;&nbsp;</td>
                        <td id="tableCabecera">&nbsp;Observaciones&nbsp;</td>
                        <td id="tableCabecera"></td>
                        <td id="tableCabecera"></td>
                    @endif                                        
                </tr>
            </thead>
    
            <tbody>
                @foreach ($model as $item)
                    <tr>
                        <td id="tableContent">
                            @if (@$item->Control == 'Muestra Adicionada' || @$item->Control == 'Duplicado' || @$item->Control == 'Resultado')
                                {{@$item->Folio_servicio}}                            
                            @else
                                {{@$item->Control}}    
                            @endif 
                        </td>
                        <td id="tableContent">{{@$item->Vol_muestra}}</td>
                        <td id="tableContent">< 2</td>
                        <td id="tableContent">{{@$item->Abs1}}</td>
                        <td id="tableContent">{{@$item->Abs2}}</td>
                        <td id="tableContent">{{@$item->Abs3}}</td>
                        <td id="tableContent">                            
                            @php
                                echo number_format(@$item->Abs_promedio, 3, ".", ".");                                
                            @endphp
                        </td>
                        <td id="tableContent">
                            @php
                                echo number_format(@$item->Abs_promedio, 3, ".", ".");                                
                            @endphp
                        </td>
                        <td id="tableContent">
                            @php
                                echo round(@$item->Vol_disolucion / @$item->Factor_dilucion, 3);
                            @endphp
                        </td>
                        <td id="tableContent">{{@$item->Factor_dilucion}}</td>
                        <!-- <td id="tableContent">PRUEBA</td> -->
                        <td id="tableContent">{{@$limites[$i]}}</td>
                        <td id="tableContent">{{@$item->Observacion}}</td>                        
                        <td id="tableContent">
                            @if (@$item->Liberado == 1)
                                Liberado
                            @elseif(@$item->Liberado == 0)
                                No liberado
                            @endif
                        </td>
                        <td id="tableContent">{{@$item->Control}}</td>
                    </tr>
                @endforeach                                         
            </tbody>        
        </table>  
    </div>    
    <br>

    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos">
            <thead>
                <tr>
                    <td id="tableCabecera"> ESTÁNDAR CONTROL</td>    
                    <td id="tableCabecera"> MUESTRA DUPLICADA</td>    
                    <td id="tableCabecera"> MUESTRA ADICIONADA</td>    
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td id="tableContent">
                        Criterio de aceptación para Std ctrl 95-105%. Fórmula; Recuperación(%) = (C1/C2)x100. Donde: C1 = Concentración leída. C2 = Concentración Real.
                    </td>
                    <td id="tableContent">
                        DPR (DIFERENCIAL PORCENTUAL RELATIVA) MUESTRA DUPLICADA: La DPR de cada analito obtenido entre la muestra y la
                        duplicada debe ser: < 20%. Fórmula: DPR = ((|C1-C2|)/((C1+C2)/2))*100. Donde: C1 - Concentración de la primera muestra.
                        C2 - Concentración de la segunda muestra (muestra duplicada).</td>
                    <td id="tableContent">
                        Criterio de Aceptación para MA  85 - 115%. Fórmula: Recuperación n = ((Cs(v+v1)-(Cr*V1))/(Ca*v))*100. Donde: V = Volúmen del
                        estándar usado para la muestra adicionada. Ca = Concentración del estándar. V1 = Volúmen de la muestra problema usada 
                        para la muestra adicionada. Cr = Concentración de muestra problema. Cs = Concentración de la muestra adicionada. Recuperación
                        n: porcentaje del analito adicionado que es medido.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <br>

</body>
</html> 