<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/informes/sinComparacion/sinComparacion.css')}}">
    
</head>
<body>

    <p id='header1'>
        {{-- INFORME DE RESULTADOS AGUA RESIDUAL  --}}
        INFORME  DE DATOS DE CAMPO 
        <br> MUESTRA
        @if (@$solModel->Id_muestra == 1)
        INSTANTANEA
        @else
        COMPUESTA
        @endif 
    </p>
    
    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0"
            border-color="#000000" width="100%">
            <tbody>
                <tr>
                    <td class="filasIzq bordesTabla anchoColumna7 bordeDer paddingTopBot">Empresa:</td>
                    <td class="filasIzq bordesTabla fontBold anchoColumna82 bordeIzq" style="font-size: 10px">
                        {{@$cliente->Nombres}}</td>
                    <td class="filasIzq bordesTabla fontBold anchoColumna11 bordeFinal justificadoDer">
                        @if (@$solModel->Siralab == 1)
                            RFC: {{@$rfc->RFC}}
                            @else
    
                            @endif
                    </td>
                </tr>
    
                <tr>
                    <td class="filasIzq bordesTabla anchoColumna7 bordeDerSinSup paddingTopBot">Dirección:</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqSinSup" colspan="2">{{@$direccion->Direccion}}</td>
                </tr>
    
                <tr>
                    <td class="filasIzq bordesTabla anchoColumna7 bordeDerSinSup" rowspan="6" style="font-size: 9px">Punto de muestreo:</td>
                    <td class="filasIzq bordesTabla fontBold anchoColumna60 bordeIzqDerSinSup" rowspan="6" style="font-size: 10px;">@if (@$solModel->Siralab == 1)
                        {{@$puntoMuestreo->Punto}}
                        @else
                        {{@$puntoMuestreo->Punto_muestreo}}
                        @endif</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">Fecha de
                        Muestreo: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="fontBold"> {{
                            \Carbon\Carbon::parse(@$solModel->Fecha_muestreo)->format('d/m/Y')}}</span>
                    </td>
                </tr>
    
                <tr>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">Hora de muestreo:
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="fontBold">{{@$horaMuestreo}}</span>
                    </td>
                </tr>
    
                <tr>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">Fecha de
                        Recepción: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span
                            class="fontBold">{{\Carbon\Carbon::parse(@$modelProcesoAnalisis->Hora_entrada)->format('d/m/Y')}}</span>
                    </td>
                </tr>
    
                <tr>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">Fecha de Emisión:
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="fontBold">{{
                            \Carbon\Carbon::parse(@$modelProcesoAnalisis->Hora_entrada)->addDays(7)->format('d/m/Y')}}</span>
                    </td>
                </tr>
    
                <tr>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">N° de Muestra:
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;
                        <span class="fontBold">{{@$solModel->Folio_servicio}}</span>
                    </td>
                </tr>
    
                <tr>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">N° de Orden:
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="fontBold">{{@$numOrden->Folio_servicio}}</span>
                    </td>
                </tr>
                {{-- <tr>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">Cloruros:
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="fontBold">{{@$compuesto->Cloruros}}</span>
                    </td>
                </tr> --}}
    
                <tr>
                    <td class="filasIzq bordesTabla anchoColumna11 bordeDer">Periodo de análisis:</td>
                    <td class="filasIzq bordesTabla bordeSinIzqFinalSup anchoColumna28 fontBold" colspan="3">DE
                        {{\Carbon\Carbon::parse(@$modelProcesoAnalisis->Hora_entrada)->format('d/m/Y')}}
                        A {{ \Carbon\Carbon::parse(@$modelProcesoAnalisis->Hora_entrada)->addDays(7)->format('d/m/Y')}}
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="fontBold"> @if (@$solModel->Siralab == 1)
                            TITULO DE CONCESIÓN: {{@$puntoMuestreo->Titulo}}
                            @else
    
                            @endif</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <p style="font-size: 8px; font-weight: 100">Cloruros (mg/L): {{$compuesto->Cloruros}} &nbsp;&nbsp;&nbsp;&nbsp; Conductividad Promedio (µS/cm): {{$conducCampo->Resultado2}}</p>

    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0" cellspacing="0"
            border-color="#000000" width="100%">
            <tbody>
                <tr>
                    <td class="nombreHeader fontBold nom fontSize11 justificadorCentr">
                        @switch(@$solicitud->Id_norma)
                        @case(1)
                        DE ACUERDO A NOM-001-SEMARNAT-1996
                        @if (@$solModel->Id_muestra == 1)
                        INSTANTANEA
                        @else
                        COMPUESTA
                        @endif
                        TIPO "{{@$tipoReporte->Tipo}}", {{@$tipoReporte->Cuerpo}} - {{$tipoReporte->Detalle}} QUE ESTABLECE
                        LOS LIMITES MAXIMOS PERMISIBLES DE CONTAMINANTES EN LAS DESCARGAS DE AGUAS RESIDUALES EN AGUAS Y
                        <br> BIENES NACIONALES.
                        @break
                        @case(2)
                        DE ACUERDO A NOM-002-SEMARNAT-1996 PARA MUESTRA
                        @if (@$solModel->Id_muestra == 1)
                        INSTANTANEA
                        @else
                        COMPUESTA
                        @endif
                        <br> QUE ESTABLECE LOS LIMITES MAXIMOS PERMISIBLES DE CONTAMINANTES EN LAS DESCARGAS DE AGUAS
                        RESIDUALES A LOS <br> SISTEMAS DE ALCANTARILLADO URBANO O MUNICIPAL.
                        @break
                        @case(4)
                        DE ACUERDO A NOM-003-SEMARNAT-1997 PARA MUESTRA
                        @if (@$solModel->Id_muestra == 1)
                        INSTANTANEA
                        @else
                        COMPUESTA
                        @endif
                        <br> QUE ESTABLECE LOS LIMITES MAXIMOS PERMISIBLES <br> DE CONTAMINANTES PARA LAS AGUAS RESIDUALES
                        <br> TRATADAS QUE SE REUSEN EN SERVICIOS AL PÚBLICO
                        @break
                        @case(5)
                        DE ACUERDO A MODIFICACIÓN A LA NORMA OFICIAL MEXICANA NOM-127-SSA1-1994, PARA MUESTRA
                        @if (@$solModel->Id_muestra == 1)
                        INSTANTANEA
                        @else
                        COMPUESTA
                        @endif
                        SALUD AMBIENTAL. AGUA PARA USO Y CONSUMO HUMANO. LÍMITES PERMISIBLES DE CALIDAD Y TRATAMIENTOS A QUE
                        DEBE <br> SOMETERSE EL AGUA PARA SU POTABILIZACION.
                        @break
                        @case(7)
                        DE ACUERDO A NORMA OFICIAL MEXICANA NOM-201-SSA1-2015 PARA MUESTRA
                        @if (@$solModel->Id_muestra == 1)
                        INSTANTANEA
                        @else
                        COMPUESTA
                        @endif
                        PRODUCTOS Y SERVICIOS. AGUA Y HIELO PARA CONSUMO HUMANO, ENVASADOS A GRANEL. ESPECIFICACIONES
                        SANITARIAS.
                        @break
                        @case(30)
                        DE ACUERDO A MODIFICACIÓN A LA NORMA OFICIAL MEXICANA NOM-127-SSA1-1994, PARA MUESTRA
                        @if (@$solModel->Id_muestra == 1)
                        INSTANTANEA
                        @else
                        COMPUESTA
                        @endif
                        SALUD AMBIENTAL. AGUA PARA USO Y CONSUMO HUMANO. LÍMITES PERMISIBLES DE CALIDAD Y TRATAMIENTOS A QUE
                        DEBE <br> SOMETERSE EL AGUA PARA SU POTABILIZACION.
                        @break
                        @endswitch
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div id="contenedorTabla">
        <p id='header1' style="font-size: 10px">METODO DE PRUEBA NMX-AA-008-SCFI-2016 DETERMINACIÓN DE POTENCIAL DE HIDROGENO </p>
        <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <thead>
                <tr>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" height="30">HORA DE TOMA &nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="16.6%">&nbsp;N. MUESTRA&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="16.6%">&nbsp;UNIDADES DE pH&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="10.6%">&nbsp;GASTO L/s&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr">&nbsp;PROMEDIO DIARIO &nbsp;</td>       
                    <td class="tableCabecera bordesTablaBody justificadoCentr"  width="10.6%">&nbsp;DECLARACION DE LA CONFORMIDAD  &nbsp;&nbsp;</td>       
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="25.6%">EVALUACIÓN DE LA CONFORMIDAD</td>
                </tr>
            </thead>
    
            <tbody>
                @php
                    $aux = 0;
                @endphp
               @foreach ($phMuestra as $item)
                   <tr>
                        <td class="tableContent bordesTablaBody">{{\Carbon\Carbon::parse(@$item->Fecha)}}</td>
                        <td class="tableContent bordesTablaBody">{{$item->Num_toma}}</td>
                        @if ($item->Activo == 1)
                            <td class="tableContent bordesTablaBody">{{$item->Promedio}}</td>
                            <td class="tableContent bordesTablaBody">{{$gasto[0]->Promedio}}</td>
                        @else
                            <td class="tableContent bordesTablaBody">----</td>
                            <td class="tableContent bordesTablaBody">----</td>
                        @endif
                        @if ($aux == 0)
                            <td class="tableContent bordesTablaBody" rowspan="{{$phMuestra->count()}}">{{$promPh}}</td>
                            <td class="tableContent bordesTablaBody" rowspan="{{$phMuestra->count()}}">{{$limPh->Pm }}</td>
                            @php
                                $auxPh = explode('-',$limPh->Pm);
                            @endphp
                            <td class="tableContent bordesTablaBody" rowspan="{{$phMuestra->count()}}">@if (round($promPh,2) >= $auxPh[0] && round($promPh,2) <= $auxPh[1] ) CUMPLE @else NO CUMPLE @endif</td>
                        @endif
                   </tr>
                   @php
                       $aux++;
                   @endphp
               @endforeach
            </tbody>        
        </table>  
    </div> 

    <div id="contenedorTabla">
        <p id='header1' style="font-size: 10px">METODO DE PRUEBA NMX-AA-007-SCFI-2013 DETERMINACIÓN DE TEMPERATURA  </p>
        <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <thead>
                <tr>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" height="30">HORA DE TOMA &nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="16.6%">&nbsp;N. MUESTRA&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="16.6%">&nbsp;TEMPERATURA <br> DEL AGUA °C&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="10.6%">&nbsp;GASTO L/s&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr">&nbsp;PROMEDIO DIARIO &nbsp;</td>       
                    <td class="tableCabecera bordesTablaBody justificadoCentr"  width="10.6%">&nbsp;DECLARACION DE LA CONFORMIDAD  &nbsp;&nbsp;</td>       
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="25.6%">EVALUACIÓN DE LA CONFORMIDAD</td>
                </tr>
            </thead>
    
            <tbody>
                @php
                    $aux = 0;
                @endphp
               @foreach ($phMuestra as $item)
                   <tr>
                    <td class="tableContent bordesTablaBody">{{\Carbon\Carbon::parse(@$item->Fecha)}}</td>
                        <td class="tableContent bordesTablaBody">{{$item->Num_toma}}</td>
                        @if ($item->Activo == 1)
                            <td class="tableContent bordesTablaBody">{{$tempMuestra[$aux]->Promedio}}</td>
                            <td class="tableContent bordesTablaBody">{{$gasto[$aux]->Promedio}}</td>
                        @else
                            <td class="tableContent bordesTablaBody">----</td>
                            <td class="tableContent bordesTablaBody">----</td>
                        @endif
                        @if ($aux == 0)
                            <td class="tableContent bordesTablaBody" rowspan="{{$phMuestra->count()}}">{{$promTemp}}</td>
                            <td class="tableContent bordesTablaBody" rowspan="{{$phMuestra->count()}}">{{$limTemp->Pm}}</td>
                          
                            <td class="tableContent bordesTablaBody" rowspan="{{$phMuestra->count()}}">@if (round($promTemp,2) <= $limTemp->Pm) CUMPLE @else NO CUMPLE @endif</td>
                        @endif
                   </tr>
                   @php
                       $aux++;
                   @endphp
               @endforeach
            </tbody>        
        </table>  
    </div> 
    
    <div id="contenedorTabla">
        <p id='header1' style="font-size: 10px">METODO DE PRUEBA NMX-AA-005-SCFI-2013 DETERMINACIÓN DE GRASAS Y ACEITES </p>
        <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <thead>
                <tr>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" height="30">HORA DE TOMA &nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="16.6%">&nbsp;N. MUESTRA&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="16.6%">&nbsp;CONCENTRACION mg/L&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="10.6%">&nbsp;GASTO L/s&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr">&nbsp;PROMEDIO DIARIO &nbsp;</td>       
                    <td class="tableCabecera bordesTablaBody justificadoCentr"  width="10.6%">&nbsp;DECLARACION DE LA CONFORMIDAD  &nbsp;&nbsp;</td>       
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="25.6%">EVALUACIÓN DE LA CONFORMIDAD</td>
                </tr>
            </thead>
    
            <tbody>
                @php 
                    $aux = 0;
                @endphp
               @foreach ($phMuestra as $item)
                   <tr>
                    <td class="tableContent bordesTablaBody">{{\Carbon\Carbon::parse(@$item->Fecha)}}</td>
                        <td class="tableContent bordesTablaBody">{{$item->Num_toma}}</td>
                        @if ($item->Activo == 1)
                            <td class="tableContent bordesTablaBody">{{$grasasModel[$aux]->Resultado}}</td>
                            <td class="tableContent bordesTablaBody">{{$gasto[$aux]->Promedio}}</td>
                        @else 
                            <td class="tableContent bordesTablaBody">----</td>
                            <td class="tableContent bordesTablaBody">----</td>
                        @endif
                        @if ($aux == 0)
                            <td class="tableContent bordesTablaBody" rowspan="{{$phMuestra->count()}}">{{round($promGa,2)}}</td>
                            <td class="tableContent bordesTablaBody" rowspan="{{$phMuestra->count()}}">{{$limGa->Pm}}</td>
                            <td class="tableContent bordesTablaBody" rowspan="{{$phMuestra->count()}}">@if (round($promGa,2) <= $limGa->Pm ) CUMPLE @else NO CUMPLE @endif</td>
                        @endif
                   </tr>
                   @php
                       $aux++;
                   @endphp
               @endforeach
            </tbody>        
        </table>  
    </div>
    
   @if ($ecoliModel->count())
    <div id="contenedorTabla">
        <p id='header1' style="font-size: 10px">METODO DE PRUEBA NMX-AA-042-SCFI-2015 / NMX-AA-167-SCFI-2017 DETERMINACIÓN DE E. COLI / ENTEROCOCOS </p>
        <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <thead>
                <tr>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" height="30">HORA DE TOMA &nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="16.6%">&nbsp;N. MUESTRA&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="16.6%">&nbsp;NMP/100 mL&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="10.6%">&nbsp;GASTO L/s&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr">&nbsp;PROMEDIO DIARIO &nbsp;</td>       
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="10.6%">&nbsp;DECLARACION DE LA CONFORMIDAD  &nbsp;&nbsp;</td>       
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="25.6%">EVALUACIÓN DE LA CONFORMIDAD</td>
                </tr>
            </thead>

            <tbody>
                @php 
                    $aux = 0;
                @endphp
            @foreach ($phMuestra as $item)
                <tr>
                    <td class="tableContent bordesTablaBody">{{\Carbon\Carbon::parse(@$item->Fecha)}}</td>
                        <td class="tableContent bordesTablaBody">{{$item->Num_toma}}</td>
                        @if ($item->Activo == 1)
                            <td class="tableContent bordesTablaBody">{{@$ecoliModel[$aux]->Resultado}}</td>
                            <td class="tableContent bordesTablaBody">{{$gasto[$aux]->Promedio}}</td>
                        @else 
                            <td class="tableContent bordesTablaBody">----</td>
                            <td class="tableContent bordesTablaBody">----</td>
                        @endif
                        @if ($aux == 0)
                            <td class="tableContent bordesTablaBody" rowspan="{{$phMuestra->count()}}">{{@$promEcoli}}</td>
                            <td class="tableContent bordesTablaBody" rowspan="{{$phMuestra->count()}}">{{$limCol->Pm}}</td>
                            <td class="tableContent bordesTablaBody" rowspan="{{$phMuestra->count()}}">@if (round($promEcoli,2) <= $limCol->Pm ) CUMPLE @else NO CUMPLE @endif</td>
                        @endif
                </tr>
                @php
                    $aux++;
                @endphp
            @endforeach
            </tbody>        
        </table>  
    </div> 
   @endif


   <footer> 
    
    
    </div>

    {{-- <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <tbody>            
                    <tr>
                        <td class="nombreHeaders fontBold fontSize9 justificadorIzq">{{$reportesInformes->Nota}}

                        </td>
                    </tr>                
            </tbody>         
        </table>  
    </div>     --}}
        
   
<br>
<br>
    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <thead>
                <tr>                    
                    <td>
                        @php
                            /* $url = url()->current(); */
                            $url = "https://sistemaacama.com.mx/clientes/exportPdfSinComparacion/".@$solicitud->Id_solicitud;
                            $qr_code = "data:image/png;base64," . \DNS2D::getBarcodePNG((string) $url, "QRCODE");
                        @endphp
                                                        
                        <img style="width: 8%; height: 8%;" src="{{@$qr_code}}" alt="qrcode" /> <br> <span class="fontSize9 fontBold">&nbsp;&nbsp;&nbsp; {{@$solicitud->Folio_servicio}}</span>
                    </td>                                                                        
                </tr>

                <tr>
                    <td style="text-align: right;"><span class="revisiones">{{$reportesInformes->Clave}}</span> <br> <span class="revisiones">Revisión {{$reportesInformes->Num_rev}}</span></td>
                </tr>
            </thead>                        
        </table>  
    </div> 
    
    <br>
</footer>

</body>
</html>