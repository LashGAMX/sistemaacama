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
            <span class="bodyStdMuestra">{{@$hora}}</span>
        </div>

        <div class="subContenedor2">
            <span class="elementos"> ESPECTROFOTÓMETRO DE ABSORCIÓN ATÓMICA <span><br></span> PERKIN ELMER MODELO:
            </span>
            <span class="subElementos">@if (@$detalle->Equipo != "")
                {{@$detalle->Equipo}}
                @else
                N/A
                @endif &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;
            </span>
        </div>

        <div class="subContenedor2">
            <span class="elementos">CORRIENTE DE LA LÁMPARA: </span>
            <span class="subElementos">@if (@$detalle->Corriente != NULL) {{@$detalle->Corriente}} @else N/A
                @endif</span>
            <br>

        </div>
    </div>


    <div class="contenedorSecundario">

        <div class="subContenedor2">
            <span class="elementos">No. DE INV. LÁMPARA: </span>
            <span class="subElementos">@if (@$detalle->No_lampara) {{@$detalle->No_lampara}} @else N/A @endif</span>
        </div>

        <div class="subContenedor2">
            <span class="elementos">ENERGÍA DE LÁMPARA: </span>
            <span class="subElementos">@if (@$detalle->Energia != "") {{@$detalle->Energia}} @else N/A @endif</span>
        </div>
    </div>

    <div class="contenedorTerciario">
        <div class="subContenedor3">
            <span class="verifEspectro"> VERIFICACIÓN DE BLANCO: </span>
            <span class="subElementos">@if (@$detalle->Verificacion_blanco != "") {{@$detalle->Verificacion_blanco}}
                @else N/A @endif</span>
        </div>

        <div class="subContenedor3">
            <span class="elementos">As. Teorica: </span>
            <span class="subElementos">@if (@$detalle->Abs_teoricoB != "") {{@$detalle->Abs_teoricoB}} @else N/A
                @endif</span>
        </div>

        <div class="subContenedor3">
            <span class="elementos">Abs 1: </span>
            <span class="subElementos">@if (@$detalle->Abs1B != ""){{@$detalle->Abs1B}}@else N/A @endif</span>
        </div>

        <div class="subContenedor3">
            <span class="elementos">Abs 2: </span>
            <span class="subElementos">@if (@$detalle->Abs2B != ""){{@$detalle->Abs2B}}@else N/A @endif</span>
        </div>

        <div class="subContenedor3">
            <span class="elementos">Abs 3: </span>
            <span class="subElementos">@if (@$detalle->Abs3B != ""){{@$detalle->Abs3B}}@else N/A @endif</span>
        </div>

        <div class="subContenedor3">
            <span class="elementos">Abs 4: </span>
            <span class="subElementos">@if (@$detalle->Abs4B != ""){{@$detalle->Abs4B}}@else N/A @endif</span>
        </div>

        <div class="subContenedor3">
            <span class="elementos">Abs 5: </span>
            <span class="subElementos">@if (@$detalle->Abs5B != ""){{@$detalle->Abs5B}}@else N/A @endif</span>
        </div>

        <div class="subContenedor3">
            <span class="elementos">Abs Prom: </span>
            <span class="subElementos">@if (@$detalle->PromedioB != ""){{@$detalle->PromedioB}}@else N/A @endif</span>
        </div>
        <div class="subContenedor3">
            <span class="elementos">Conclusion: </span>
            <span class="subElementos">@if (@$detalle->ConclusionB != ""){{@$detalle->ConclusionB}}@else N/A
                @endif</span>
        </div>
    </div>
    <div class="contenedorTerciario">
        <div class="subContenedor3">
            <span class="elementos"> No. DE INVENTARIO: </span>
            <span class="subElementos">@if (@$detalle->No_inventario != "") {{@$detalle->No_inventario}} @else N/A
                @endif</span>
        </div>

        <div class="subContenedor3">
            <span class="elementos">LONGITUD DE ONDA: </span>
            <span class="subElementos">@if (@$detalle->Longitud_onda != "") {{@$detalle->Longitud_onda}} @else N/A
                @endif</span>
        </div>

        <div class="subContenedor3">
            <span class="elementos">SLIT: </span>
            <span class="subElementos">@if (@$detalle->Slit != ""){{@$detalle->Slit}}@else N/A @endif</span>
        </div>

        <div class="subContenedor3">
            <span class="elementos">ACETILENO: </span>
            <span class="subElementos">@if (@$detalle->Gas != "") @php echo @$detalle->Gas; @endphp @else N/A @endif
            </span>
        </div>

        <div class="subContenedor3">
            <span class="elementos">AIRE: {{@$detalle->Aire}}</span>
            <span class="subElementos"></span>
        </div>

        <div class="subContenedor3">
            <span class="elementos">ÓXIDO NITROSO: {{@$detalle->Oxido_nitroso}}</span>
            <span class="subElementos"></span>
        </div>
    </div>

    <div class="contenedorCuarto">
        <div class="subContenedor4">
            <span class="verifEspectro"> VERIFICACIÓN DEL <span><br></span> ESPECTROFOTÓMETRO</span>
        </div>
        <div class="subContenedor4">
            <span class="elementos">Masa caracteristica: @if (@$detalle->MasaE != ""){{@$detalle->MasaE}} @else N/A
                @endif</span>
        </div>
        <div class="subContenedor4">
            <span class="elementos">STD.CAL. @if (@$detalle->Std_calE != "") {{@$detalle->Std_calE}} @else N/A @endif
            </span>
        </div>

        <div class="subContenedor4">
            <span class="elementos">ABS. TEÓRICA: @if (@$detalle->Abs_teoricoE) {{@$detalle->Abs_teoricoE}} @else N/A
                @endif</span>
        </div>

        <div class="subContenedor4">
            <span class="elementos">ABS 1: @if (@$detalle->Abs1E != "") {{@$detalle->Abs1E}} @else N/A @endif</span>
        </div>

        <div class="subContenedor4">
            <span class="elementos">ABS 2: @if (@$detalle->Abs2E != "") {{@$detalle->Abs2E}} @else N/A @endif</span>
        </div>

        <div class="subContenedor4">
            <span class="elementos">ABS 3: @if (@$detalle->Abs3E != "") {{@$detalle->Abs3E}} @else N/A @endif</span>
        </div>

        <div class="subContenedor4">
            <span class="elementos">ABS 4: @if (@$detalle->Abs4E != "") {{@$detalle->Abs4E}} @else N/A @endif</span>
        </div>

        <div class="subContenedor4">
            <span class="elementos">ABS 5: @if (@$detalle->Abs5E != "") {{@$detalle->Abs5E}} @else N/A @endif</span>
        </div>
        <div class="subContenedor4">
            <span class="elementos">CONCLUSION: @if (@$detalle->ConclusionE != "") {{@$detalle->ConclusionE}} @else N/A
                @endif</span>
        </div>

    </div>


    <div class="contenedorQuinto">
        <div class="subContenedor5">
            <span class="verifEspectro">CURVA DE CALIBRACIÓN</span>
            <br>
            <span class="elementosCurvaCalib">Ver. {{@$detalle->Bitacora}} Folio: {{@$detalle->Folio}}. (Bitacora para
                la preparación de estándares y curvas de calibración)</span>

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
                        <th></span>&nbsp;&nbsp;</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td id="tableContent">
                            CONCENTRACIÓN EN μg/L
                        </td>

                        <td id="tableContent">
                            @php
                            echo number_format(@$estandares[0]->Concentracion, 4, ".", ",");
                            @endphp
                        </td>


                        @for ($i = 1; ($i < @$estandares->count()); $i++)
                            @if(@$estandares[$i]->Concentracion != null)
                            <td id="tableContent">
                                @php
                                echo number_format(@$estandares[$i]->Concentracion, 4, ".", ",");
                                @endphp
                            </td>
                            @endif
                            @endfor

                            <td id="tableContent"><span class="bmrTabla">b = </span></td>
                            <td id="tableContent">
                                @php
                                echo number_format(@$curva->B, 5, ".", ".");
                                @endphp
                                <br>
                            </td>

                    </tr>
                    <tr>
                        <td id="tableContent">ABSORBANCIA 1</td>

                        <td id="tableContent">
                            @php
                            echo number_format(@$estandares[0]->ABS1, 4, ".", ",");
                            @endphp
                        </td>

                        @for ($i = 1; ($i < @$estandares->count()); $i++)
                            @if(@$estandares[$i]->ABS1 != null)
                            <td id="tableContent">
                                @php
                                echo number_format(@$estandares[$i]->ABS1, 4, ".", ".");
                                @endphp
                            </td>
                            @endif
                            @endfor

                            <td id="tableContent"><span class="bmrTabla">m= </span></td>
                            <td id="tableContent">
                                @php
                                echo number_format(@$curva->M, 5, ".", ".");
                                @endphp
                                <br>
                            </td>

                    </tr>
                    <tr>
                        <td id="tableContent">ABSORBANCIA 2</td>

                        <td id="tableContent">
                            @php
                            echo number_format(@$estandares[0]->ABS2, 4, ".", ",");
                            @endphp
                        </td>

                        @for ($i = 1; ($i < @$estandares->count()); $i++)
                            @if(@$estandares[$i]->ABS2 != null)
                            <td id="tableContent">
                                @php
                                echo number_format(@$estandares[$i]->ABS2, 4, ".", ".");
                                @endphp
                            </td>
                            @endif
                            @endfor

                            <td id="tableContent"><span class="bmrTabla">r = </span></td>
                            <td id="tableContent">
                                @php
                                echo number_format(@$curva->R, 5, ".", ".");
                                @endphp
                            </td>

                    </tr>
                    <tr>
                        <td id="tableContent">ABSORBANCIA 3</td>

                        <td id="tableContent">
                            @php
                            echo number_format(@$estandares[0]->ABS3, 4, ".", ",");
                            @endphp
                        </td>

                        @for ($i = 1; ($i < @$estandares->count()); $i++)
                            @if(@$estandares[$i]->ABS3 != null)
                            <td id="tableContent">
                                @php
                                echo number_format(@$estandares[$i]->ABS3, 4, ".", ".");
                                @endphp
                            </td>
                            @endif

                            @endfor
                            <td id="tableContent"><span class="bmrTabla">Fecha de preparación = </span></td>
                            <td id="tableContent">{{@$fechaPreparacion->toDateString()}}</td>

                    </tr>
                    <tr>
                        <td id="tableContent">ABSORBANCIA PROM.</td>

                        <td id="tableContent">
                            @php
                            echo number_format(@$estandares[0]->Promedio, 4, ".", ",");
                            @endphp
                        </td>

                        @for ($i = 1; ($i < @$estandares->count()); $i++)
                            @if(@$estandares[$i]->Promedio != null)
                            <td id="tableContent">
                                @php
                                echo number_format(@$estandares[$i]->Promedio, 4, ".", ".");
                                @endphp
                            </td>
                            @endif
                            @endfor

                            <td id="tableContent"><span class="bmrTabla">Límite de cuantificación = </span></td>
                            <td id="tableContent">
                                < {{@$model[0]->Limite}}
                            </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos">
            <thead>
                <tr>
                    <td id="tableCabecera">No. de muestra &nbsp;</td>
                    <td id="tableCabecera">&nbsp;Volumen de muestra (mL)&nbsp;&nbsp;</td>
                    <td id="tableCabecera">&nbsp;Vol Final (mL)&nbsp;&nbsp;</td>
                    <td id="tableCabecera">&nbsp;Es pH<2&nbsp;&nbsp;< /td>
                    <td id="tableCabecera">&nbsp;Abs 1&nbsp;&nbsp;</td>
                    <td id="tableCabecera">&nbsp;Abs 2&nbsp;&nbsp;</td>
                    <td id="tableCabecera">&nbsp;Abs 3&nbsp;&nbsp;</td>
                    <td id="tableCabecera">&nbsp;Abs Promedio&nbsp;&nbsp;</td>
                    <td id="tableCabecera">&nbsp;Abs Muestra - Abs Blanco&nbsp;&nbsp;</td>
                    <td id="tableCabecera">&nbsp;[μg/L] Obtenida&nbsp;&nbsp;</td>
                    <td id="tableCabecera">&nbsp;F.D.&nbsp;&nbsp;</td>
                    <td id="tableCabecera">&nbsp;F.C.&nbsp;&nbsp;</td>

                    <td id="tableCabecera">&nbsp;[mg/L] Reportada&nbsp;&nbsp;</td>
                    <td id="tableCabecera">&nbsp;Observaciones&nbsp;</td>
                    <td id="tableCabecera"></td>
                    <td id="tableCabecera"></td>

                </tr>
            </thead>

            <tbody>

                <!-- Resultados -->

                @foreach ($model as $item)
                <tr>
                    <td id="tableContent">
                        @switch($item->Id_control)
                        @case(9)
                        @case(4)
                        @case(14)
                        @case(15)
                        @case(23)
                        {{@$item->Control}}
                        @break
                        @default
                        @if ($item->Id_control != 1)
                        {{@$item->Folio_servicio}}
                        {{@$item->Control}}
                        @else
                        {{@$item->Folio_servicio}}
                        @endif
                        @endswitch
                    </td>
                    <td id="tableContent">{{@$item->Vol_muestra}}</td>
                    <td id="tableContent">{{@$item->Vol_final}}</td>
                    <td id="tableContent">
                        < 2</td>
                    <td id="tableContent">{{number_format(@$item->Abs1,4)}}</td>
                    <td id="tableContent">{{number_format(@$item->Abs2,4)}}</td>
                    <td id="tableContent">{{number_format(@$item->Abs3,4)}}</td>
                    <td id="tableContent">
                        {{number_format(@$item->Abs_promedio, 4, ".", ".")}}
                    </td>
                    <td id="tableContent">
                        {{number_format(@$item->Abs_promedio, 4, ".", ".")}}
                    </td>
                    <td id="tableContent">
                        {{number_format(@$item->Resultado_microgramo, 3, ".", ".")}}
                    </td>
                    <td id="tableContent">{{number_format(@$item->Factor_dilucion, 6, ".", ".")}}</td>
                    <td id="tableContent">{{@$item->Factor_conversion}}</td>
                    <td id="tableContent">
                        @if (@$item->Vol_disolucion != null || @$item->Vol_disolucion == 0)
                        @if ($item->Vol_disolucion < $item->Limite)
                            < {{$item->Limite}}
                                @else
                                {{number_format(@$item->Vol_disolucion,3)}}
                                @endif
                                @else
                                -------
                                @endif
                    </td>
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
                        Criterio de aceptación para Std ctrl 95-105%. Fórmula; Recuperación(%) = (C1/C2)x100. Donde: C1
                        = Concentración leída. C2 = Concentración Real.
                    </td>
                    <td id="tableContent">
                        DPR (DIFERENCIAL PORCENTUAL RELATIVA) MUESTRA DUPLICADA: La DPR de cada analito obtenido entre
                        la muestra y la
                        duplicada debe ser: < 20%. Fórmula: DPR=((|C1-C2|)/((C1+C2)/2))*100. Donde: C1 - Concentración
                            de la primera muestra. C2 - Concentración de la segunda muestra (muestra duplicada).</td>
                    <td id="tableContent">
                        Criterio de Aceptación para MA 85 - 115%. Fórmula: Recuperación n =
                        ((Cs(v+v1)-(Cr*V1))/(Ca*v))*100. Donde: V = Volúmen del
                        estándar usado para la muestra adicionada. Ca = Concentración del estándar. V1 = Volúmen de la
                        muestra problema usada
                        para la muestra adicionada. Cr = Concentración de muestra problema. Cs = Concentración de la
                        muestra adicionada. Recuperación
                        n: porcentaje del analito adicionado que es medido.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <br>

</body>

</html>