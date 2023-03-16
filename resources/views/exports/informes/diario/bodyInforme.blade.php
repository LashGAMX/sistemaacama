<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/informes/sinComparacion/sinComparacion.css')}}">
    <title>Informe @if ($tipo == 1)
        Con
    @else
        Sin
    @endif Comparación</title>
</head>
<body>
    <p id='header1'>
        {{-- INFORME DE RESULTADOS AGUA RESIDUAL  --}}
        {{$reportesInformes->Encabezado}}
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
        <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <thead>
                <tr>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" height="30">PARAMETRO &nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="16.6%">&nbsp;METODO DE PRUEBA&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="10.6%">&nbsp;UNIDAD&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr">&nbsp;CONCENTRACION CUANTIFICADA&nbsp;&nbsp;</td>       
                    @if ($tipo == 1)
                        <td class="tableCabecera bordesTablaBody justificadoCentr">&nbsp;CONCENTRACION PERMISIBLE P.D&nbsp;&nbsp;</td>
                    @endif             
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="25.6%">ANALISTA</td>
                </tr>
            </thead>
    
            <tbody>
                @php $i = 0; @endphp
                @foreach ($model as $item)
                    @if (@$item->Id_area != 9)
                        <tr> 
                            <td class="tableContent bordesTablaBody" height="25">{{@$item->Parametro}}<sup>{{$item->Simbologia}}</sup></td>
                            <td class="tableContent bordesTablaBody">{{@$item->Clave_metodo}}</td>
                            <td class="tableContent bordesTablaBody">{{@$item->Unidad}}</td>
                            <td class="tableContent bordesTablaBody">
                                {{@$limitesC[$i]}}
                            </td>
                            @if ($tipo == 1)
                                <td class="tableContent bordesTablaBody">
                                    {{ @$limitesN[$i] }}
                                </td>
                            @endif
                            <td class="tableContent bordesTablaBody">
                                @if (@$item->Analizo != 1)
                                    {{@$item->iniciales}}
                                @else
                                    -------
                                @endif
                            </td>
                        </tr>   
                        @php $i++; @endphp
                    @endif
                @endforeach

            </tbody>        
        </table>  
    </div> 
    <footer>
        <div id="contenedorTabla">
            <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
                <tbody>            
                        <tr>
                            <td class="nombreHeader nom fontSize11 justificadorIzq" height="57">
                                OBSERVACIONES: TEMPERATURA AMBIENTE PROMEDIO DE {{@$tempAmbienteProm->Resultado2}}°C, @php if(@swOlor == true) {echo "LA MUESTRA PRESENTA OLOR Y COLOR " .@$color;} else{ echo "LA MUESTRA PRESENTA COLOR ".@$color; }@endphp
                                EL MUESTREO FUE REALIZADO DE ACUERDO A LO ESTABLECIDO EN LA NMX-AA-003-1980 Y DE ACUERDO A PROCEDIMIENTO PE-10-002-04 <br>
                                {{@$obsCampo}}
                            </td>
                        </tr>                
                </tbody>         
            </table>  
        </div>
        
            <div autosize="1" class="contenedorPadre12 paddingTop" cellpadding="0" cellspacing="0" border-color="#000000">
                <div class="contenedorHijo12 bordesTablaFirmasSupIzq">
                    <span><img style="width: auto; height: auto; max-width: 90px; max-height: 70px;" src="{{url('public/storage/'.$firma1->firma)}}"> <br></span>            
                </div>
        
                <div class="contenedorHijo12 bordesTablaFirmasSupDer">            
                    <span><img style="width: auto; height: auto; max-width: 90px; max-height: 70px;" src="{{url('public/storage/'.$firma2->firma)}}"> <br></span>            
                </div>  
        
                <div class="contenedorHijo12 bordesTablaFirmasInfIzq">            
                    <span class="cabeceraStdMuestra"> REVISÓ SIGNATARIO <br> </span>            
                    <span class="bodyStdMuestra"> {{$reportesInformes->Analizo}} {{-- {{@$usuario->name}} --}} </span>
                </div>         
                
                <div class="contenedorHijo12 bordesTablaFirmasInfDer">            
                    <span class="cabeceraStdMuestra"> AUTORIZÓ SIGNATARIO <br> </span>
                    <span class="bodyStdMuestra"> {{$reportesInformes->Reviso}} {{-- {{@$usuario->name}} --}} </span>
                </div>
            </div>
        
            <div id="contenedorTabla">
                <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
                    <tbody>            
                            <tr>
                                <td class="nombreHeaders fontBold fontSize9 justificadorIzq">{{$reportesInformes->Nota}}
        
                                </td>
                            </tr>                
                    </tbody>         
                </table>  
            </div>    
                
            <div id="contenedorTabla">
                @php
                    $temp = array();
                    $sw = false;
                @endphp
                <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
                    <tbody>            
                            @foreach ($model as $item)
                                @for ($i = 0; $i < sizeof($temp); $i++)
                                    @if ($temp[$i] == $item->Id_simbologia_info)
                                        @php $sw = true; @endphp
                                    @endif
                                @endfor
                                @if ($sw != true)
                                    @switch($item->Id_parametro)
                                        @case(97)
                                            <tr>
                                                <td class="nombreHeaders fontBold fontSize9 justificadorIzq">{{$item->Simbologia_inf}} @php print  $item->Descripcion2; @endphp</td>
                                            </tr>
                                            <tr>
                                                <td class="nombreHeaders fontBold fontSize9 justificadorIzq">*** LA DETERMINACIÓN DE LA TEMPERATURA DE LA MUESTRA COMPUESTA ES DE {{@$campoCompuesto->Temp_muestraComp}}°C Y EL PH COMPUESTO ES DE {{@$campoCompuesto->Ph_muestraComp}}</td>
                                            </tr>
                                            @php
                                                array_push($temp,$item->Id_simbologia_info);
                                            @endphp
                                            @break
                                        @default
                                        <tr>
                                            <td class="nombreHeaders fontBold fontSize9 justificadorIzq">{{$item->Simbologia_inf}} @php print  $item->Descripcion2; @endphp</td>
                                        </tr>
                                        @php
                                            array_push($temp,$item->Id_simbologia_info);
                                        @endphp
                                    @endswitch
                                   
                                @endif
                                @php
                                    $sw = false;
                                @endphp
                            @endforeach
                    </tbody>         
                </table>  
            </div>    
        
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
        
                        {{-- <tr>
                            <td style="text-align: right;"><span class="revisiones">{{$reportesInformes->Clave}}</span> <br> <span class="revisiones">Revisión {{$reportesInformes->Num_rev}}</span></td>
                        </tr> --}}
                    </thead>                        
                </table>  
            </div> 
            
            <br>
        </footer>
</body>
</html>