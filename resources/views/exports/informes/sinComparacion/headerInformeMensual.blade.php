<p id='header1'>
    INFORME DE RESULTADOS AGUA RESIDUAL
</p>

<div id="contenedorTabla">
    <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
        <tbody>            
                <tr>
                    <td class="filasIzq bordesTabla anchoColumna7 bordeDer paddingTopBot">Empresa:</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzq" width="460.8px">{{$solModel1->Empresa}}</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzq" width="137.3px">&nbsp;</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzq" width="92.9px">&nbsp;</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzq">&nbsp;</td>                    
                    <td class="filasIzq bordesTabla fontBold bordeFinal justificadoDer"></td>                    
                </tr>

                <tr>                    
                    <td class="filasIzq bordesTabla bordeDerSinSup paddingTopBot">Dirección:</td>                    
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup"> {{$solModel1->Direccion}}</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup">&nbsp;</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup">&nbsp;</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup">&nbsp;</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqSinSup justificadoDer">TITULO DE CONCESIÓN: </td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla bordeDerSinSup paddingTopBot">Punto de muestreo:</td>
                    <td class="filasIzq bordesTabla fontBold soloBordeInf">@if ($solModel1->Siralab == 1)
                        {{$punto->Punto}}
                    @else
                        {{$punto->Punto_muestreo}}
                    @endif</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup">&nbsp;</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup">&nbsp;</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup">&nbsp;</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup bordeSinIzqFinalSup">&nbsp;</td>
                </tr>
                
                <tr>                    
                    <td class="filasIzq bordesTabla soloBordeDer" colspan="2" rowspan="9">&nbsp;</td>
                    <td class="filasIzq bordesTabla soloBordeDer paddingTopBotInter fontSize6">GASTO PROMEDIO</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup justificadorCentr fontSize6">
                        @php
                            echo ($gasto1->Resultado2 + $gasto2->Resultado2) / 2;
                        @endphp
                    </td>
                    <td class="filasIzq bordesTabla bordeIzqSinSup fontSize6 fontBold bordeIzqDerSinSup justificadorCentr">L/s</td>                                        
                    <td class="filasIzq bordesTabla soloBordeDer fontBold fontSize12 justificadorCentr" rowspan="9" width="13.05%"></td>
                </tr>
                    
                <tr>
                    <td class="filasIzq bordesTabla soloBordeSup paddingTopBotInter fontSize6">GASTO LPS</td>
                    <td class="filasIzq bordesTabla bordeDerSinSup paddingTopBotInter justificadorCentr fontSize6 fontBold">{{$gasto1->Resultado2}}</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup paddingTopBotInter justificadorCentr fontSize6 fontBold">{{$gasto2->Resultado2}}</td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla soloBordeSup paddingTopBotInter fontSize6">FECHA DE MUESTREO:</td>
                    <td class="filasIzq bordesTabla bordeDerSinSup paddingTopBotInter justificadorCentr fontSize6 fontBold">{{ \Carbon\Carbon::parse($solModel1->Fecha_muestreo)->format('d/m/Y')}}</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup paddingTopBotInter justificadorCentr fontSize6 fontBold">{{ \Carbon\Carbon::parse($solModel2->Fecha_muestreo)->format('d/m/Y')}}</td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla soloBordeSup paddingTopBotInter fontSize6">FECHA DE RECEPCION:</td>
                    <td class="filasIzq bordesTabla bordeDerSinSup paddingTopBotInter justificadorCentr fontSize6 fontBold">{{ \Carbon\Carbon::parse($proceso1->Hora_recepcion)->format('d/m/Y')}}</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup paddingTopBotInter justificadorCentr fontSize6 fontBold">{{ \Carbon\Carbon::parse($proceso2->Hora_recepcion)->format('d/m/Y')}}</td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla soloBordeSup paddingTopBotInter fontSize6">FECHA DE EMISIÓN:</td>
                    <td class="filasIzq bordesTabla bordeDerSinSup paddingTopBotInter justificadorCentr fontSize6 fontBold">
                        {{\Carbon\Carbon::parse($proceso1->Hora_entrada)->addDays(7)->format('d/m/Y')}}                        
                    </td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup paddingTopBotInter justificadorCentr fontSize6 fontBold">
                        {{\Carbon\Carbon::parse($proceso2->Hora_entrada)->addDays(7)->format('d/m/Y')}}
                    </td>
                </tr>
                
                <tr>
                    <td class="filasIzq bordesTabla soloBordeSup paddingTopBotInter fontSize6">PERIODO DE ANALISIS:</td>
                    <td class="filasIzq bordesTabla bordeDerSinSup paddingTopBotInter justificadorCentr fontSize6 fontBold">DE {{\Carbon\Carbon::parse($proceso1->Hora_entrada)->format('d/m/Y')}} A {{\Carbon\Carbon::parse($proceso1->Hora_entrada)->addDays(7)->format('d/m/Y')}}</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup paddingTopBotInter justificadorCentr fontSize6 fontBold">DE {{\Carbon\Carbon::parse($proceso2->Hora_entrada)->format('d/m/Y')}} A {{\Carbon\Carbon::parse($proceso2->Hora_entrada)->addDays(7)->format('d/m/Y')}}
                    </td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla soloBordeSup paddingTopBotInter fontSize6">TIPO DE MUESTREO:</td>
                    <td class="filasIzq bordesTabla bordeDerSinSup paddingTopBotInter fontSize6 fontBold justificadorCentr">@if ($solModel1->Id_muestra == 1)
                        INSTANTANEA
                    @else   
                        COMPUESTA
                    @endif</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup paddingTopBotInter fontSize6 fontBold justificadorCentr">@if ($solModel2->Id_muestra == 1)
                        INSTANTANEA
                    @else   
                        COMPUESTA
                    @endif</td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla soloBordeSup paddingTopBotInter fontSize6">N° DE MUESTRA:</td>
                    <td class="filasIzq bordesTabla bordeDerSinSup paddingTopBotInter fontSize6 fontBold justificadorCentr">{{$solModel1->Folio_servicio}}</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup paddingTopBotInter fontSize6 fontBold justificadorCentr">{{$solModel2->Folio_servicio}}</td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla bordeSupDer paddingTopBotInter fontSize6">N° DE ORDEN:</td>
                    <td class="filasIzq bordesTabla soloBordeDer paddingTopBotInter fontSize6 fontBold justificadorCentr">{{$numOrden1->Folio_servicio}}</td>
                    <td class="filasIzq bordesTabla soloBordeDer paddingTopBotInter fontSize6 fontBold justificadorCentr">{{$numOrden2->Folio_servicio}}</td>
                </tr>                
        </tbody>         
    </table>  
</div>