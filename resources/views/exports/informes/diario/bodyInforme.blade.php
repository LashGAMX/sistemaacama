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
    <div style="width: 100%">
        <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <tbody>
                <tr>
                    <td class="filasIzq bordesTabla anchoColumna7 bordeDer paddingTopBot">Empresa:</td>
                    <td class="filasIzq bordesTabla fontBold anchoColumna82 bordeIzq" style="font-size: 8px">
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
                    <td class="filasIzq bordesTabla fontBold bordeIzqSinSup" colspan="2" style="font-size: 8px;">{{@$direccion->Direccion}}</td>
                </tr>
                <tr>
                    <td class="filasIzq bordesTabla anchoColumna7 bordeDerSinSup" rowspan="6" style="font-size: 9px">Punto de muestreo:</td>
                    <td class="filasIzq bordesTabla fontBold anchoColumna60 bordeIzqDerSinSup" rowspan="6" style="font-size: 8px;">{{$puntoMuestreo->Punto}}</td>
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
                        <span class="fontBold">
                            @if ($solModel->Id_servicio != 3)  
                                {{@$horaMuestreo}}
                            @else
                                INSTANTANEA                                    
                            @endif
                        </span>
                    </td>
                </tr>
    
                <tr>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">Fecha de
                        Recepción: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span
                            class="fontBold">{{\Carbon\Carbon::parse(@$modelProcesoAnalisis->Hora_recepcion)->format('d/m/Y')}}</span>
                    </td>
                </tr>
                
                <tr>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">Fecha de Emisión:
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="fontBold">
                            @switch($solModel->Id_norma)
                            @case(1)
                            @case(27)  
                            {{\Carbon\Carbon::parse(@$modelProcesoAnalisis->Hora_recepcion)->addDays(11)->format('d/m/Y')}}
                                @break
                            @case(5)
                            @case(30)  
                            {{\Carbon\Carbon::parse(@$modelProcesoAnalisis->Hora_recepcion)->addDays(14)->format('d/m/Y')}}
                                @break
                            @default
                            {{\Carbon\Carbon::parse(@$modelProcesoAnalisis->Hora_recepcion)->addDays(11)->format('d/m/Y')}}
                        @endswitch
                        </span>
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
                    <td class="filasIzq bordesTabla bordeSinIzqFinalSup anchoColumna28 fontBold" colspan="2">DE
                        {{\Carbon\Carbon::parse(@$modelProcesoAnalisis->Hora_recepcion)->format('d/m/Y')}}
                        A 
                        @switch($solModel->Id_norma)
                        @case(1)
                        @case(27)  
                            {{\Carbon\Carbon::parse(@$modelProcesoAnalisis->Hora_recepcion)->addDays(11)->format('d/m/Y')}}
                            @break
                        @case(5)
                        @case(30)  
                            {{\Carbon\Carbon::parse(@$modelProcesoAnalisis->Hora_recepcion)->addDays(14)->format('d/m/Y')}}
                            @break
                        @default
                            {{\Carbon\Carbon::parse(@$modelProcesoAnalisis->Hora_recepcion)->addDays(11)->format('d/m/Y')}}
                        @endswitch
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
    <br>

    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0" cellspacing="0"
            border-color="#000000" width="100%">
            <tbody>
                <tr>
                    <td class="nombreHeader fontBold nom justificadorCentr" style="font-size: 10px;">
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
                        @case(27)
                            DE ACUERDO A NOM-001-SEMARNAT-2021
                            @if (@$solModel->Id_muestra == 1)
                              INSTANTANEA
                            @else
                             COMPUESTA
                            @endif
                                TIPO  QUE ESTABLECE
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
                        DE ACUERDO  A LA NORMA OFICIAL MEXICANA NOM-127-SSA1-2021, AGUA PARA USO Y CONSUMO HUMANO. LÍMITES PERMISIBLES DE LA CALIDAD DEL AGUA.
                        {{-- SALUD AMBIENTAL. AGUA PARA USO Y CONSUMO HUMANO. LÍMITES PERMISIBLES DE CALIDAD Y TRATAMIENTOS A QUE
                        DEBE <br> SOMETERSE EL AGUA PARA SU POTABILIZACION. --}}
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
                    <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr" height="30" width="20.6%">PARAMETRO &nbsp;</td>
                    <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr" width="20.6%">&nbsp;METODO DE PRUEBA&nbsp;&nbsp;</td>
                    <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr" width="10.6%">&nbsp;UNIDAD&nbsp;&nbsp;</td>
                    <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr" width="10.6%">&nbsp;CONCENTRACION <br> CUANTIFICADA&nbsp;&nbsp;</td>       
                    @if ($tipo == 1)
                        <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr">&nbsp;CONCENTRACION PERMISIBLE P.D&nbsp;&nbsp;</td>
                    @endif             
                    <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr" width="10.6%">ANALISTA</td>
                </tr>
            </thead>
    
            <tbody>
                @php $i = 0; @endphp
                @foreach ($model as $item)
                    @if (@$item->Id_area != 9)
                        <tr> 
                            <td class="tableContent bordesTablaBody" style="font-size: 8px;" height="25">{{@$item->Parametro}}<sup>{{$item->Simbologia}}</sup></td>
                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">{{@$item->Clave_metodo}}</td>
                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">{{@$item->Unidad}}</td>
                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                @if (@$item->Resultado2 != NULL)
                                    @switch($item->Id_parametro)
                                    @case(64)
                                        @if ($solicitud->Id_norma == 27)
                                            {{$campoCompuesto->Cloruros}}
                                        @else
                                            {{@$limitesC[$i]}}        
                                        @endif
                                        @break
                                    @default
                                    {{@$limitesC[$i]}}
                                   @endswitch
                                @else
                                    -------
                                @endif
                     
                            </td>
                            @if ($tipo == 1)
                                <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                    @if (@$item->Resultado2 != NULL)
                                        @switch($item->Id_parametro)
                                            @case(64)
                                                {{"N/A"}}
                                            @break
                                            @default
                                            {{ @$limitesN[$i] }}
                                        @endswitch
                                    @else
                                        -------
                                    @endif
                                  
                                </td>
                            @endif
                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                @if (@$item->Resultado2 != NULL)
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
                                <td class="nombreHeader nom fontSize11 justificadorIzq"  style="font-size: 8px;margin:2px">
                                    @if ($solModel->Id_servicio != 3)  
                                        @switch($solModel->Id_norma)
                                            @case(1)
                                            @case(27)
                                                OBSERVACIONES: TEMPERATURA AMBIENTE PROMEDIO DE {{@$tempAmbienteProm}}°C, @php if(@$swOlor == true) {echo "LA MUESTRA PRESENTA OLOR " .@$color;} else{ echo "LA MUESTRA PRESENTA COLOR ".@$color; }@endphp
                                                EL MUESTREO FUE REALIZADO DE ACUERDO A LO ESTABLECIDO EN LA NMX-AA-003-1980 Y DE ACUERDO A PROCEDIMIENTO PE-10-002-04 <br>
                                                {{@$obsCampo}}
                                                @break
                                            @case(5) 
                                            @case(30)
                                                @if ($solModel->Id_servicio != 3)
                                                    OBSERVACIONES: TEMPERATURA AMBIENTE PROMEDIO DE {{@$tempAmbienteProm}}°C, 
                                                    @php if(@$swOlor == true) {echo "LA MUESTRA PRESENTA OLOR";} else{ echo "LA MUESTRA NO PRESENTA OLOR";}@endphp
                                                    Y COLOR DE LA MUESTRA {{$color}} ,
                                                    EL MUESTREO FUE REALIZADO DE ACUERDO A LO ESTABLECIDO EN EL PROCEDIMIENTO INTERNO PEA-10-002-01 Y DE ACUERDO A PROCEDIMIENTO PE-10-002-27 <br>
                                                    {{@$obsCampo}}
                                                @else
                                                    OBSERVACIONES: MUESTRA REMITIDA AL LABORATORIO POR EL CLIENTE, LOS RESULTADOS SE APLICAN A LA MUESTRA COMO SE RECIBIÓ
                                                @endif
                                            @break 
                                            @default
                                                OBSERVACIONES: TEMPERATURA AMBIENTE PROMEDIO DE {{@$tempAmbienteProm}}°C, @php if(@$swOlor == true) {echo "LA MUESTRA PRESENTA OLOR Y COLOR " .@$color;} else{ echo "LA MUESTRA PRESENTA COLOR ".@$color; }@endphp
                                                EL MUESTREO FUE REALIZADO DE ACUERDO A LO ESTABLECIDO EN LA NMX-AA-003-1980 Y DE ACUERDO A PROCEDIMIENTO PE-10-002-04 <br>
                                                {{@$obsCampo}}
                                        @endswitch
                                    @else
                                    
                                    <p>OBSERVACIONES: MUESTRA REMITIDA AL LABORATORIO POR EL CLIENTE, LOS RESULTADOS SE APLICAN A LA MUESTRA COMO SE RECIBIÓ</p>
                                    @endif
                                </td>
                            </tr>                
                    </tbody>         
                </table>  
        </div>
        <div id="contenedorTabla">
            <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
                <tbody>            
                        <tr>
                            <td class="nombreHeader nom fontSize11 justificadorIzq"  style="font-size: 8px;margin:4px">
                                <br>
                                {{-- <center><span><img style="width: auto; height: auto; max-width: 90px; max-height: 40;" src="{{url('public/storage/'.$firma1->firma)}}"> <br></span></center> --}}
                                <br>
                                <br>
                                <br>
                                <br>
                            </td> 
                            <td class="nombreHeader nom fontSize11 justificadorIzq"  style="font-size: 8px;margin:4px">
                                {{-- <center><span><img style="width: auto; height: auto; max-width: 90px; max-height: 40px;" src="{{url('public/storage/'.$firma2->firma)}}"> <br></span></center> --}}
                            </td> 
                        </tr>                
                        <tr>
                            <td class="nombreHeader nom fontSize11 justificadorIzq"  style="font-size: 8px;margin:2px">
                                <center><span class="cabeceraStdMuestra"> REVISÓ SIGNATARIO <br> </span></center>
                                <center><span class="bodyStdMuestra"> {{$firma1->name}} {{-- {{@$usuario->name}} --}} </span></center>
                            </td>
                            <td class="nombreHeader nom fontSize11 justificadorIzq"  style="font-size: 8px;margin:2px">
                                <center><span class="cabeceraStdMuestra"> AUTORIZÓ SIGNATARIO <br> </span></center>
                                <center><span class="bodyStdMuestra"> {{$firma2->name}} {{-- {{@$usuario->name}} --}} </span></center>
                            </td>
                        </tr> 
                </tbody>         
            </table>  
    </div>

    
        
    
            <div id="contenedorTabla">
                <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
                    <tbody>           
                            <tr>
                                <td class="nombreHeaders fontBold justificadorIzq" style="font-size: 7px;width: 80%;" >
                                    {{-- @php
                                        echo $reportesInformes->Nota;
                                    @endphp --}}
                                    @switch($solModel->Id_norma)
                                        @case(5)
                                        @case(30)
                                                @if ($solModel->Id_servicio != 3)
                                                    <p>NOTA: INTERPRETAR EL PUNTO (.) COMO SIGNO DECIMAL SEG&Uacute;N NORMA NOM-008-SCFI-2002</p>
                                                    <p>NOTA 2: LOS DATOS EXPRESADOS AVALAN &Uacute;NICAMENTE LOS RESULTADOS DE LA MUESTRA ANALIZADA.</p>
                                                    <p>NOTA 3: LOS DATOS DE EMPRESA, DIRECCI&Oacute;N, PUNTO DE MUESTREO, SON PROPORCIONADOS POR EL CLIENTE.</p>
                                                    <p>LOS VALORES CON EL SIGNO MENOR (&lt;) CORRESPONDEN AL VALOR M&Iacute;NIMO CUANTIFICADO POR EL M&Eacute;TODO.</p>
                                                    <p>ESTE REPORTE NO DEBE REPRODUCIRSE SIN LA APROBACI&Oacute;N DEL LABORATORIO EMISOR.</p>
                                                    <p>PLAN DE MUESTREO RE-11-005 Y CADENA DE CUSTODIA INTERNA RE-11-003-1</p>
                                                    <p>N.A INTERPRETAR COMO NO APLICA.</p>
                                                    <p>N.N INTERPRETAR COMO NO NORMADO.</p>           
                                                    <p>1 REG. ACREDIT. ENTIDAD MEXICANA DE ACREDITACI&Oacute;N ema No. AG-057-025/12, CONTINUAR&Aacute; VIGENTE.</p>
                                                    <p>1 APROBACI&Oacute;N C.N.A. No CNA-GCA-2599, VIGENCIA A PARTIR DEL 17 DE FEBRERO DE 2023 HASTA 18 DE NOVIEMBRE DEL 2023</p>
                                                    <p>1A ACREDITAMIENTO EN ALIMENTOS: REG. ACREDIT. ENTIDAD MEXICANA DE ACREDITACI&Oacute;N EMA NO. A-0530-047/14, CONTINUAR&Aacute; VIGENTE.</p>
                                                @else
                                                    <p>NOTA: INTERPRETAR EL PUNTO (.) COMO SIGNO DECIMAL SEG&Uacute;N NORMA NOM-008-SCFI-2002</p>
                                                    <p>NOTA 2: LOS DATOS EXPRESADOS AVALAN &Uacute;NICAMENTE LOS RESULTADOS DE LA MUESTRA ANALIZADA.</p>
                                                    <p>NOTA 3: LOS DATOS DE EMPRESA, DIRECCI&Oacute;N, PUNTO DE MUESTREO, SON PROPORCIONADOS POR EL CLIENTE.</p>
                                                    <p>LOS VALORES CON EL SIGNO MENOR (&lt;) CORRESPONDEN AL VALOR M&Iacute;NIMO CUANTIFICADO POR EL M&Eacute;TODO.</p>
                                                    <p>ESTE REPORTE NO DEBE REPRODUCIRSE SIN LA APROBACI&Oacute;N DEL LABORATORIO EMISOR.</p>
                                                    {{-- <p>PLAN DE MUESTREO RE-11-005 Y CADENA DE CUSTODIA INTERNA RE-11-003-1</p> --}}
                                                    <p>N.A INTERPRETAR COMO NO APLICA.</p>
                                                    <p>N.N INTERPRETAR COMO NO NORMADO.</p>
                                                    <p>1 REG. ACREDIT. ENTIDAD MEXICANA DE ACREDITACI&Oacute;N ema No. AG-057-025/12, CONTINUAR&Aacute; VIGENTE.</p>
                                                    <p>1 APROBACI&Oacute;N C.N.A. No CNA-GCA-2599, VIGENCIA A PARTIR DEL 17 DE FEBRERO DE 2023 HASTA 18 DE NOVIEMBRE DEL 2023</p>
                                                    <p>1A ACREDITAMIENTO EN ALIMENTOS: REG. ACREDIT. ENTIDAD MEXICANA DE ACREDITACI&Oacute;N EMA NO. A-0530-047/14, CONTINUAR&Aacute; VIGENTE.</p>
                                                @endif
                                            @break

                                        @default
                                        @php
                                            echo $reportesInformes->Nota;
                                        @endphp
                                    @endswitch
                                </td>
                                <td style="width: 15%">
                                    @php
                                    $url = "https://sistemaacama.com.mx/clientes/informe-de-resultados-acama/".@$folioEncript;
                                    $qr_code = "data:image/png;base64," . \DNS2D::getBarcodePNG((string) $url, "QRCODE");
                                    @endphp
                                       
                                    <center><img style="width: 8%; height: 8%;" src="{{@$qr_code}}" alt="qrcode" /> <br> <span class="fontSize9 fontBold">&nbsp;&nbsp;&nbsp; {{@$solicitud->Folio_servicio}}</span></center>
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
                                @if ($item->Id_simbologia_info	!= 9)
                                    @switch($item->Id_parametro)
                                        @case(97)
                                            @if ($solicitud->Num_tomas > 1)
                                                <tr> 
                                                    <td   style="font-size: 7px" class="fontBold justificadorIzq">{{$item->Simbologia_inf}} @php print  $item->Descripcion2; @endphp</td>
                                                </tr>
                                            @else
                                                
                                            @endif
                                            <!-- <tr>
                                                 <td   style="font-size: 7px" class="fontBold justificadorIzq">*** LA DETERMINACIÓN DE LA TEMPERATURA DE LA MUESTRA COMPUESTA ES DE {{@$campoCompuesto->Temp_muestraComp}}°C Y EL PH COMPUESTO ES DE {{@$campoCompuesto->Ph_muestraComp}}</td>
                                            </tr> -->
                                            @php
                                                array_push($temp,$item->Id_simbologia_info);
                                            @endphp
                                            @break
                                        @default

                                        <tr>
                                            <td   style="font-size: 7px" class="fontBold justificadorIzq">{{$item->Simbologia_inf}} @php print  $item->Descripcion2; @endphp</td>
                                        </tr>
                                        @php
                                            array_push($temp,$item->Id_simbologia_info);
                                        @endphp
                                    @endswitch
                                @endif
                            
                            @endif 
                            @php
                                $sw = false;
                            @endphp
                        @endforeach 
                </tbody>         
            </table>  
            
            </div>    

        </footer>
</body>
</html>