<p id='header1'>
    INFORME DE RESULTADOS AGUA RESIDUAL
</p>

<div id="contenedorTabla">
    <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
        <tbody>            
                <tr>
                    <td class="filasIzq bordesTabla anchoColumna7 bordeDer paddingTopBot">Empresa:</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzq" width="460.8px">{{@$cliente->Nombres}}</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzq" width="137.3px">&nbsp;</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzq" width="92.9px">&nbsp;</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzq">&nbsp;</td>                    
                    <td class="filasIzq bordesTabla fontBold bordeFinal justificadoDer">{{@$cliente->RFC}}</td>                    
                </tr>

                <tr>                    
                    <td class="filasIzq bordesTabla bordeDerSinSup paddingTopBot">Dirección:</td>                    
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup">{{@$direccion->Direccion}}</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup">&nbsp;</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup">&nbsp;</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup">&nbsp;</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqSinSup justificadoDer">TITULO DE CONCESIÓN: {{@$puntoMuestreo->Titulo_consecion}}</td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla bordeDerSinSup paddingTopBot">Punto de muestreo:</td>
                    <td class="filasIzq bordesTabla fontBold soloBordeInf">{{@$puntoMuestreo->Punto}}</td>
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
                            $gastoLPS = ($gastoLPS1 + $gastoLPS2) / 2;
                            echo number_format($gastoLPS, 2, ".", ",");
                        @endphp
                    </td>
                    <td class="filasIzq bordesTabla bordeIzqSinSup fontSize6 fontBold bordeIzqDerSinSup justificadorCentr">L/s</td>                                        
                    <td class="filasIzq bordesTabla soloBordeDer fontBold fontSize12 justificadorCentr" rowspan="9" width="13.05%">{{@$norma->Clave_norma}}</td>
                </tr>
                    
                <tr>
                    <td class="filasIzq bordesTabla soloBordeSup paddingTopBotInter fontSize6">GASTO LPS</td>
                    <td class="filasIzq bordesTabla bordeDerSinSup paddingTopBotInter justificadorCentr fontSize6 fontBold">{{number_format(@$gastoLPS1, 2, ".", ",")}}</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup paddingTopBotInter justificadorCentr fontSize6 fontBold">{{number_format(@$gastoLPS2, 2, ".", ",")}}</td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla soloBordeSup paddingTopBotInter fontSize6">FECHA DE MUESTREO:</td>
                    <td class="filasIzq bordesTabla bordeDerSinSup paddingTopBotInter justificadorCentr fontSize6 fontBold">{{ \Carbon\Carbon::parse($solModel->Fecha_muestreo)->format('d/m/Y')}}</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup paddingTopBotInter justificadorCentr fontSize6 fontBold">{{ \Carbon\Carbon::parse($solModel2[0]->Fecha_muestreo)->format('d/m/Y')}}                        
                    </td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla soloBordeSup paddingTopBotInter fontSize6">FECHA DE RECEPCION:</td>
                    <td class="filasIzq bordesTabla bordeDerSinSup paddingTopBotInter justificadorCentr fontSize6 fontBold">{{\Carbon\Carbon::parse(@$modelProcesoAnalisis1->Hora_entrada)->format('d/m/Y')}}</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup paddingTopBotInter justificadorCentr fontSize6 fontBold">{{\Carbon\Carbon::parse(@$modelProcesoAnalisis2->Hora_entrada)->format('d/m/Y')}}                                                                        
                    </td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla soloBordeSup paddingTopBotInter fontSize6">FECHA DE EMISIÓN:</td>
                    <td class="filasIzq bordesTabla bordeDerSinSup paddingTopBotInter justificadorCentr fontSize6 fontBold">
                        {{\Carbon\Carbon::parse($modelProcesoAnalisis1->Hora_entrada)->addDays(7)->format('d/m/Y')}}
                    </td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup paddingTopBotInter justificadorCentr fontSize6 fontBold">
                        {{\Carbon\Carbon::parse($modelProcesoAnalisis2->Hora_entrada)->addDays(7)->format('d/m/Y')}}
                    </td>
                </tr>
                
                <tr>
                    <td class="filasIzq bordesTabla soloBordeSup paddingTopBotInter fontSize6">PERIODO DE ANALISIS:</td>
                    <td class="filasIzq bordesTabla bordeDerSinSup paddingTopBotInter justificadorCentr fontSize6 fontBold">DE {{\Carbon\Carbon::parse($modelProcesoAnalisis1->Hora_entrada)->format('d/m/Y')}} A {{\Carbon\Carbon::parse($modelProcesoAnalisis1->Hora_entrada)->addDays(7)->format('d/m/Y')}}</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup paddingTopBotInter justificadorCentr fontSize6 fontBold">DE {{\Carbon\Carbon::parse($modelProcesoAnalisis2->Hora_entrada)->format('d/m/Y')}} A {{\Carbon\Carbon::parse($modelProcesoAnalisis2->Hora_entrada)->addDays(7)->format('d/m/Y')}}
                    </td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla soloBordeSup paddingTopBotInter fontSize6">TIPO DE MUESTREO:</td>
                    <td class="filasIzq bordesTabla bordeDerSinSup paddingTopBotInter fontSize6 fontBold justificadorCentr">{{$solModel->Id_muestra}}</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup paddingTopBotInter fontSize6 fontBold justificadorCentr">{{$solModel2[0]->Id_muestra}}</td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla soloBordeSup paddingTopBotInter fontSize6">N° DE MUESTRA:</td>
                    <td class="filasIzq bordesTabla bordeDerSinSup paddingTopBotInter fontSize6 fontBold justificadorCentr">{{$solModel->Folio_servicio}}</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup paddingTopBotInter fontSize6 fontBold justificadorCentr">{{$solModel2[0]->Folio_servicio}}</td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla bordeSupDer paddingTopBotInter fontSize6">N° DE ORDEN:</td>
                    <td class="filasIzq bordesTabla soloBordeDer paddingTopBotInter fontSize6 fontBold justificadorCentr">{{$numOrden->Folio_servicio}}</td>
                    <td class="filasIzq bordesTabla soloBordeDer paddingTopBotInter fontSize6 fontBold justificadorCentr">{{$numOrden2->Folio_servicio}}</td>
                </tr>                
        </tbody>         
    </table>  
</div>