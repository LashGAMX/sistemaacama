<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/metales/capturaPDF.css')}}">
    <title>Bitácora Metales</title>
</head>
<body>    

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
                @for ($i = 0; $i < @$datosLength ; $i++)
                    <tr>
                        @if (@$tecnicaUsada->Id_tecnica == 22)
                            <td id="tableContent">
                                @if (@$datos[$i]->Control == 'Muestra Adicionada' || @$datos[$i]->Control == 'Duplicado' || @$datos[$i]->Control == 'Resultado')
                                    {{@$datos[$i]->Folio_servicio}}                            
                                @else
                                    {{@$datos[$i]->Control}}    
                                @endif 
                            </td>
                            <td id="tableContent">{{@$datos[$i]->Vol_muestra}}</td>
                            <td id="tableContent">{{@$loteModelPh[$i]}}</td>
                            <td id="tableContent">                                
                                @php
                                    echo number_format(@$datos[$i]->Abs1, 4, ".", ".");
                                @endphp
                            </td>
                            <td id="tableContent">                                
                                @php
                                    echo number_format(@$datos[$i]->Abs2, 4, ".", ".");
                                @endphp
                            </td>
                            <td id="tableContent">
                                @php
                                    echo number_format(@$datos[$i]->Abs3, 4, ".", ".");
                                @endphp
                            </td>
                            <td id="tableContent">                            
                                @php
                                    echo number_format(@$datos[$i]->Abs_promedio, 4, ".", ".");
                                @endphp
                            </td>                            
                            <td id="tableContent">
                                @php
                                    echo number_format(@$datos[$i]->Vol_disolucion / @$datos[$i]->Factor_dilucion, 3, ".", ".");
                                @endphp
                            </td>
                            <td id="tableContent">{{@$datos[$i]->Factor_dilucion}}</td>
                            <td id="tableContent">{{@$datos[$i]->Factor_conversion}}</td>
                            <!-- <td id="tableContent">PRUEBA</td> -->
                            <td id="tableContent">{{@$limites[$i]}}</td>
                            <td id="tableContent">{{@$loteModel[$i]}}</td>                        
                            <td id="tableContent">
                                @if (@$datos[$i]->Liberado == 1)
                                    Liberado
                                @elseif(@$datos[$i]->Liberado == 0)
                                    No liberado
                                @endif
                            </td>
                            <td id="tableContent">{{@$datos[$i]->Control}}</td>    
                        @else
                            <td id="tableContent">
                                @if (@$datos[$i]->Control == 'Muestra Adicionada' || @$datos[$i]->Control == 'Duplicado' || @$datos[$i]->Control == 'Resultado')
                                    {{@$datos[$i]->Folio_servicio}}                            
                                @else
                                    {{@$datos[$i]->Control}}    
                                @endif 
                            </td>
                            <td id="tableContent">{{@$datos[$i]->Vol_muestra}}</td>
                            <td id="tableContent">{{@$loteModelPh[$i]}}</td>
                            <td id="tableContent">{{@$datos[$i]->Abs1}}</td>
                            <td id="tableContent">{{@$datos[$i]->Abs2}}</td>
                            <td id="tableContent">{{@$datos[$i]->Abs3}}</td>
                            <td id="tableContent">                            
                                @php
                                    echo number_format(@$datos[$i]->Abs_promedio, 3, ".", ".");                                
                                @endphp
                            </td>
                            <td id="tableContent">
                                @php
                                    echo number_format(@$datos[$i]->Abs_promedio, 3, ".", ".");                                
                                @endphp
                            </td>
                            <td id="tableContent">
                                @php
                                    echo round(@$datos[$i]->Vol_disolucion / @$datos[$i]->Factor_dilucion, 4);
                                @endphp
                            </td>
                            <td id="tableContent">{{@$datos[$i]->Factor_dilucion}}</td>
                            <!-- <td id="tableContent">PRUEBA</td> -->
                            <td id="tableContent">{{@$limites[$i]}}</td>
                            <td id="tableContent">{{@$loteModel[$i]}}</td>                        
                            <td id="tableContent">
                                @if (@$datos[$i]->Liberado == 1)
                                    Liberado
                                @elseif(@$datos[$i]->Liberado == 0)
                                    No liberado
                                @endif
                            </td>
                            <td id="tableContent">{{@$datos[$i]->Control}}</td>
                        @endif                                                
                    </tr>                
                @endfor                        
            </tbody>        
        </table>  
    </div>    

    <br>

    <div class="contenedorPadre">
        <div class="contenedorHijo1">            
            <span class="cabeceraStdMuestra"> ESTÁNDAR CONTROL <br> </span>
                <span class="bodyStdMuestra">Criterio de aceptación para Std ctrl 95-105%. Fórmula; Recuperación(%) = (C1/C2)x100. Donde: C1 = Concentración leída.
                    C2 = Concentración Real.
                </span>
        </div>

        <div class="contenedorHijo1">
            <span class="cabeceraStdMuestra">MUESTRA DUPLICADA <br> </span>                                    
            <span class="bodyStdMuestra">DPR (DIFERENCIAL PORCENTUAL RELATIVA) MUESTRA DUPLICADA: La DPR de cada analito obtenido entre la muestra y la
                duplicada debe ser: < 20%. Fórmula: DPR = ((|C1-C2|)/(C1+C2)/2)*100. Donde: C1 - Concentración de la primera muestra.
                C2 - Concentración de la segunda muestra (muestra duplicada).
            </span>                   
        </div>

        <div class="contenedorHijo1">
            <span class="cabeceraStdMuestra">MUESTRA ADICIONADA <br> </span>                    
            <span class="bodyStdMuestra">Criterio de Aceptación para MA  85 - 115%. Fórmula: Recuperación n = ((Cs(V+V1)-Cr*V1/Ca*V))*100. Donde: V = Volúmen del
                estándar usado para la muestra adicionada. Ca = Concentración del estándar. V1 = Volúmen de la muestra problema usada 
                para la muestra adicionada. Cr = Concentración de muestra problema. Cs = Concentración de la muestra adicionada. Recuperación
                n: porcentaje del analito adicionado que es medido.
            </span>                   
        </div>
    </div>        

    <br>        
</body>
</html>