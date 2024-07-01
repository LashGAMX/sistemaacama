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
        
        {{$impresion[0]->Encabezado}}
        <br> MUESTRA
        @if (@$solModel->Id_muestra == 1 || @$solModel->Id_muestra == 0)
        INSTANTANEA
        @else
        COMPUESTA 
        @endif
    </p>
    <div style="width: 100%">
        <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <tbody>
                <tr>
                    <td class="filasIzq bordesTabla anchoColumna7 bordeDer paddingTopBot" style="font-size:10px">Empresa:</td>
                    <td class="filasIzq bordesTabla fontBold anchoColumna82 bordeIzq" style="font-size: 10px">
                        {{@$cliente->Empresa}}</td>
                    <td class="filasIzq bordesTabla fontBold anchoColumna11 bordeFinal justificadoDer">
                        @if (@$solModel->Siralab == 1)
                            RFC: {{@$rfc->RFC}}
                            @else
    
                            @endif
                    </td>
                </tr>
                <tr>
                    <td class="filasIzq bordesTabla anchoColumna7 bordeDerSinSup paddingTopBot" style="font-size: 10px;">Dirección:</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqSinSup" colspan="2" style="font-size: 10px;">{{@$direccion->Direccion}}</td>
                </tr>
                <tr>
                    <td class="filasIzq bordesTabla anchoColumna7 bordeDerSinSup" rowspan="6" style="font-size: 10px">Punto de muestreo:</td>
                    <td class="filasIzq bordesTabla fontBold anchoColumna60 bordeIzqDerSinSup" rowspan="6" style="font-size: 10px;">
                        @php
                            echo $puntoMuestreo->Punto;
                        @endphp
                    </td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">Fecha de
                        Muestreo: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="fontBold"> {{ \Carbon\Carbon::parse(@$solModel->Fecha_muestreo)->format('d/m/Y') }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">Hora de muestreo:
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="fontBold">
                            @if ($solModel->Id_servicio != 3)  
                                @switch($solModel->Id_norma)
                                    @case(30)
                                    @case(7)
                                        {{@$horaMuestreo}}
                                        @break
                                        @case(9)
                                    @if ($solModel->Num_tomas > 1)
                                        COMPUESTA
                                    @else
                                        {{@$horaMuestreo}}
                                    @endif
                                        @break
                                    @default
                                    @if ($solModel->Num_tomas > 1)
                                        COMPUESTA
                                    @else
                                        @switch($solModel->Id_norma)
                                        @case(1)
                                        @case(27)
                                        @case(2)
                                        @case(4)
                                        {{@$horaMuestreo}}   
                                                @break
                                            @default
                                            INSTANTANEA   
                                        @endswitch       
                                    @endif
                                @endswitch
                            @else
                                 @if ($solModel->Num_tomas > 1)
                                        COMPUESTA
                                    @else
                                     INSTANTANEA
                                    @endif
                                  
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
                            @if ($modelProcesoAnalisis->Emision_informe == NULL)
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
                            @else
                             {{\Carbon\Carbon::parse(@$modelProcesoAnalisis->Emision_informe)->format('d/m/Y')}}
                            @endif
                           
                           
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
                    <td class="filasIzq bordesTabla anchoColumna11 bordeDer" style="font-size: 10px">Periodo de análisis:</td>
                    <td class="filasIzq bordesTabla bordeSinIzqFinalSup anchoColumna28 fontBold" colspan="2" style="font-size: 10px">DE
                        {{\Carbon\Carbon::parse(@$modelProcesoAnalisis->Hora_recepcion)->format('d/m/Y')}}
                        A 

                        @if ($modelProcesoAnalisis->Emision_informe == NULL)
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
                            @else
                             {{\Carbon\Carbon::parse(@$modelProcesoAnalisis->Emision_informe)->format('d/m/Y')}}
                            @endif
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="fontBold"> 
                            @if (@$solModel->Atencion == null)
                                
                            @else
                                Atención a: {{@$solModel->Atencion}}
                            @endif
                        </span>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="fontBold"> @if (@$solModel->Siralab == 1)
                            TITULO DE CONCESIÓN: {{@$tituloConsecion}}
                            @else
    
                            @endif
                        </span>
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
                    <td class="nombreHeader fontBold nom justificadorCentr" style="font-size: 8px;">
                    @switch(@$solicitud->Id_norma)
                        @case(1)
                            DE ACUERDO A NOM-001-SEMARNAT-1996
                            @if (@$solModel->Id_muestra == 1)
                            INSTANTANEA
                            @else
                            COMPUESTA
                            @endif
                            @if (@$solModel->Id_reporte != 0)
                                TIPO "{{@$tipoReporte->Tipo}}", {{@$tipoReporte->Cuerpo}} - {{@$tipoReporte->Detalle}} 
                            @else
                                
                            @endif
                            QUE ESTABLECE LOS LIMITES MAXIMOS PERMISIBLES DE CONTAMINANTES EN LAS DESCARGAS DE AGUAS RESIDUALES EN AGUAS Y
                            BIENES NACIONALES.
                            @break
                            @case(27)
                            DE ACUERDO A LA NOM-001-SEMARNAT-2021       
                            @if (@$solModel->Id_muestra == 1)
                            INSTANTANEA
                            @else
                            COMPUESTA
                            @endif
                            QUE ESTABLECE LOS LIMITES PERMISIBLES DE CONTAMINANTES EN LAS DESCARGAS DE AGUAS RESIDUALES EN CUERPO RECEPTORES PROPIEDAD DE LA NACION
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
                            {{-- SALUD AMBIENTAL. AGUA PARA USO Y CONSUMO HUMANO. LÍMITES PERMISIBLES DE CALIDAD Y TR   ATAMIENTOS A QUE
                            DEBE <br> SOMETERSE EL AGUA PARA SU POTABILIZACION. --}}
                         @break
                            @default
                             DE ACUERDO A {{$norma->Norma}} PARA MUESTRA    
                             @if (@$solModel->Id_muestra == 1 || @$solModel->Id_muestra == 0) 
                                INSTANTANEA
                            @else
                                 COMPUESTA
                            @endif
                            <br>
                             {{$norma->Clave_norma}}
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
                            @switch(@$solModel->Id_promedio)
                                @case(1)
                                <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr" width="10.6%">&nbsp;LIMITE PERMISIBLE <br> INSTANTANEO&nbsp;&nbsp;</td>    
                                    @break
                                @case(2)
                                
                                    @break
                                @case(3)
                                <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr" width="10.6%">&nbsp;LIMITE PERMISIBLE <br> P.D&nbsp;&nbsp;</td>    
                                @break
                                @default
                                    
                            @endswitch

                            <!-- <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr" width="10.6%">&nbsp;DECLARACION DE <br> LA CONFORMIDAD&nbsp;&nbsp;</td>     -->
                    @endif             
                    <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr" width="10.6%">ANALISTA</td>
                </tr>
            </thead>
    
            <tbody>
                @php $i = 0; @endphp
                @foreach ($model as $item)
                    @if (@$item->Id_area != 9)
                        <tr> 
                            <!-- <td class="tableContent bordesTablaBody" style="font-size: 8px;" height="25">({{@$item->Id_parametro}}) {{@$item->Parametro}}<sup>{{$item->Simbologia}} </sup></td> -->
                            <td class="tableContent bordesTablaBody" style="font-size: 8px;" height="25">{{@$item->Parametro}}<sup>{{$item->Simbologia}} </sup></td>
                            {{-- <td class="tableContent bordesTablaBody" style="font-size: 8px;" height="25">{{@$item->Parametro}}<sup>{{$item->Simbologia}} </sup> | {{$item->Simbologia_inf}}</td> --}}
                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                {{$item->Clave_metodo}}
                            </td>
                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">{{@$item->Unidad}}</td>
                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                @if (@$item->Resultado2 != NULL)
                                    {{@$limitesC[$i]}}
                                @else
                                    -------
                                @endif
                     
                            </td>
                            @if ($tipo == 1)
                                <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                    @if (@$item->Resultado2 != NULL)
                                        @switch($item->Id_parametro)
                                            @case(64)
                                            {{ @$limitesCon[$i] }}
                                            @break
                                            @default
                                            {{ @$limitesN[$i] }}
                                        @endswitch
                                    @else
                                        -------
                                    @endif
                                  
                                </td>

                                <!-- <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                    @if (@$item->Resultado2 != NULL)
                                        @switch($item->Id_parametro)
                                            @case(64)
                                            {{ @$limitesCon[$i] }}
                                            @break
                                            @default
                                            {{ @$limitesCon[$i] }}
                                        @endswitch
                                    @else
                                        -------
                                    @endif
                                  
                                </td> -->


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
                                            OBSERVACIONES: TEMPERATURA AMBIENTE PROMEDIO DE {{@$tempAmbienteProm}}°C, 
                                            @php if(@$swOlor == true) {echo "LA MUESTRA PRESENTA OLOR";} else{ echo "LA MUESTRA NO PRESENTA OLOR";}@endphp
                                            Y COLOR DE LA MUESTRA {{$color}} ,
                                                EL MUESTREO FUE REALIZADO DE ACUERDO A LO ESTABLECIDO EN LA 
                                                @if (@$campoCompuesto->Proce_muestreo == 27)
                                                    PEA-10-002-01
                                                @else
                                                    NMX-AA-003-1980 
                                                @endif
                                                Y DE ACUERDO A PROCEDIMIENTO PE-10-002-{{$campoCompuesto->Proce_muestreo}} <br>
                                                {{@$obsCampo}}
                                                @break
                                            @case(5) 
                                            @case(7)  
                                            @case(30)
                                                @if ($solModel->Id_servicio != 3)
                                                    OBSERVACIONES: TEMPERATURA AMBIENTE PROMEDIO DE {{@$tempAmbienteProm}}°C, 
                                                    @php if(@$swOlor == true) {echo "LA MUESTRA PRESENTA OLOR";} else{ echo "LA MUESTRA NO PRESENTA OLOR";}@endphp
                                                    Y COLOR DE LA MUESTRA {{$color}} ,
                                                    EL MUESTREO FUE REALIZADO DE ACUERDO A LO ESTABLECIDO EN EL PROCEDIMIENTO INTERNO PEA-10-002-01 Y DE ACUERDO A PROCEDIMIENTO PE-10-002-{{@$campoCompuesto->Proce_muestreo}} <br>
                                                    {{@$obsCampo}}
                                                @else
                                                    OBSERVACIONES: MUESTRA REMITIDA AL LABORATORIO POR EL CLIENTE, LOS RESULTADOS SE APLICAN A LA MUESTRA COMO SE RECIBIÓ
                                                @endif
                                            @break 
                                            @default
                                            OBSERVACIONES: TEMPERATURA AMBIENTE PROMEDIO DE {{@$tempAmbienteProm}}°C, 
                                            @php if(@$swOlor == true) {echo "LA MUESTRA PRESENTA OLOR";} else{ echo "LA MUESTRA NO PRESENTA OLOR";}@endphp
                                            Y COLOR DE LA MUESTRA {{$color}} ,
                                                EL MUESTREO FUE REALIZADO DE ACUERDO A LO ESTABLECIDO EN LA 
                                                @if (@$campoCompuesto->Proce_muestreo == 27)
                                                    PEA-10-002-01
                                                @else
                                                    NMX-AA-003-1980  
                                                @endif

                                                Y DE ACUERDO A PROCEDIMIENTO PE-10-002-{{@$campoCompuesto->Proce_muestreo}} <br>
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
                                    @php
                                        echo $impresion[0]->Nota;
                                    @endphp
                                    @if (@$solModel->Id_norma == 27)
                                        @php
                                            echo @$impresion[0]->Nota_siralab;
                                        @endphp
                                    @endif
                                    @if ($solModel->Nota_4 == 1)
                                        4 PARAMETRO NO ACREDITADO
                                    @endif
                                    @if ($tipo == 1)
                                        A SOLICITUD DEL CLIENTE SE COMPARA EL INFORME DE RESULTADOS CON LOS LIMITES PERMISIBLES DE LA NORMA
                                    @endif
                                   
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
            
            @if (@$solModel->Num_tomas > 1)
                    
                <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%" >
                    <tbody>        
                        <tr><td></td></tr>
                            @foreach ($model as $item)
                                @for ($i = 0; $i < sizeof($temp); $i++)
                                    @if ($temp[$i] == $item->Id_simbologia_info)
                                        @php $sw = true; @endphp
                                    @endif
                                @endfor
                                @if ($sw != true) 
                                    @switch($item->Id_simbologia_info)
                                        @case(9)
                                            
                                            @break
                                        @case(11)
                                            <tr> 
                                                <td   style="font-size: 7px" class="fontBold justificadorIzq">1++ MEDIA GEOMETRICA DE LAS {{@$numTomas->count()}} MUESTRAS SIMPLES DE ESCHERICHIA COLI.</td>
                                            </tr>
                                            @php
                                                array_push($temp,$item->Id_simbologia_info);
                                            @endphp
                                        @break
                                        @case(5)
                                            <tr> 
                                                <td   style="font-size: 7px" class="fontBold justificadorIzq">1# PROMEDIO PONDERADO DE LAS {{@$numTomas->count()}} MUESTRAS SIMPLES DE GRASAS Y ACEITES</td>
                                            </tr>
                                            @php
                                                array_push($temp,$item->Id_simbologia_info);
                                            @endphp
                                        @break
                                        @case(4)
                                            <tr> 
                                                <td   style="font-size: 7px" class="fontBold justificadorIzq">1+ MEDIA GEOMETRICA DE LAS {{@$numTomas->count()}} MUESTRAS SIMPLES DE COLIFORMES. EL VALOR MINIMO CUANTIFICADO REPORTADO SERA DE 3, COMO CRITERIO CALCULADO PARA COLIFORMES EN SIRALAB Y EL
                                                    LABORATORIO.</td>
                                            </tr>
                                            @php
                                                array_push($temp,$item->Id_simbologia_info);
                                            @endphp
                                        @break
                                        @case(12)
                                            <tr> 
                                                <td   style="font-size: 7px" class="fontBold justificadorIzq">1+++ MEDIA GEOMETRICA DE LAS {{@$numTomas->count()}} MUESTRAS SIMPLES DE ENTEROCOCOS FECALES. </td>
                                            </tr>
                                            @php
                                                array_push($temp,$item->Id_simbologia_info);
                                            @endphp
                                        @break
                                        @default
                                        @switch($item->Id_parametro)
                                            @case(97)
                                                @if ($solicitud->Num_tomas > 1)
                                                    <tr> 
                                                        <td   style="font-size: 7px" class="fontBold justificadorIzq">{{$item->Simbologia_inf}} @php print  $item->Descripcion2; @endphp</td>
                                                    </tr>
                                                @else
                                                    
                                                @endif
                                                {{-- <tr>
                                                    <td   style="font-size: 7px" class="fontBold justificadorIzq">*** LA DETERMINACIÓN DE LA TEMPERATURA DE LA MUESTRA COMPUESTA ES DE {{@$campoCompuesto->Temp_muestraComp}}°C Y EL PH COMPUESTO ES DE {{@$campoCompuesto->Ph_muestraComp}}</td>
                                                </tr> --}}
                                                @php
                                                    array_push($temp,$item->Id_simbologia_info);
                                                @endphp
                                                @break
                                            @default

                                            <tr>
                                                <td   style="font-size: 7px" class="fontBold justificadorIzq">{{$item->Simbologia_inf}} @php echo  $item->Descripcion2; @endphp</td>
                                            </tr>
                                            @php
                                                array_push($temp,$item->Id_simbologia_info);
                                            @endphp
                                        @endswitch
                                    @endswitch
                                
                                @endif 
                                @php
                                    $sw = false;
                                @endphp
                            @endforeach 

                      
                    </tbody>         
                </table>  
            @else
            <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%" style="margin-top:-70px">
                <tbody>        
                    <tr><td></td></tr>
                        @foreach ($model as $item)
                            @for ($i = 0; $i < sizeof($temp); $i++)
                                @if ($temp[$i] == $item->Id_simbologia_info)
                                    @php $sw = true; @endphp
                                @endif
                            @endfor
                            @if ($sw != true) 
                                @switch($item->Id_simbologia_info)
                                    @case(9)
                                        
                                        @break
                                    @case(11)
                                       
                                        @php
                                            array_push($temp,$item->Id_simbologia_info);
                                        @endphp
                                    @break
                                    @case(5)
                                      
                                        @php
                                            array_push($temp,$item->Id_simbologia_info);
                                        @endphp
                                    @break
                                    @case(4)
                                     
                                        @php
                                            array_push($temp,$item->Id_simbologia_info);
                                        @endphp
                                    @break
                                    @case(12)
                                      
                                        @php
                                            array_push($temp,$item->Id_simbologia_info);
                                        @endphp
                                    @break
                                    @default
                                    @switch($item->Id_parametro)
                                        @case(97)
                                            @if ($solicitud->Num_tomas > 1)
                                              
                                            @else
                                                
                                            @endif
                                            @php
                                                array_push($temp,$item->Id_simbologia_info);
                                            @endphp
                                        @break
                                        @case(358)
                                        
                                            {{-- <tr>
                                                <td   style="font-size: 7px" class="fontBold justificadorIzq">{{$item->Simbologia_inf}} @php echo  $item->Descripcion2; @endphp</td>
                                            </tr> --}}
                                        @break
                                        @default

                                        @php
                                            array_push($temp,$item->Id_simbologia_info);
                                        @endphp
                                    @endswitch
                                @endswitch
                            
                            @endif 
                            @php
                                $sw = false;
                            @endphp
                        @endforeach 
                </tbody>         
            </table>  
            @endif
            
            </div>    

        </footer>
</body>
</html>