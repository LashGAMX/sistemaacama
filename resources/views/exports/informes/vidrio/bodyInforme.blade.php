<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/informes/sinComparacion/sinComparacion.css')}}">
    <title>Informe Vibrio</title>
    <style>
        #conagua {
            display: none;
        }
    </style>
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
        <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <thead>
                <tr>
                    <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr" height="30" width="20.6%">PARAMETRO &nbsp;</td>
                    <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr" height="30" width="10%"># DE TOMA &nbsp;</td>
                    <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr" width="20.6%">&nbsp;METODO DE PRUEBA&nbsp;&nbsp;</td>
                    <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr" width="10.6%">&nbsp;UNIDAD&nbsp;&nbsp;</td>
                    <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr" width="10.6%" colspan="2">&nbsp;CONCENTRACION <br> CUANTIFICADA&nbsp;&nbsp;</td>       
                    @if($incerAux->count())
                        <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr" width="10.6%">
                        &nbsp;INCERTIDUMBRE&nbsp;&nbsp;</td>
                    @endif
                    <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr" width="10.6%">ANALISTA</td>
                </tr>
            </thead>
    
            <tbody>
                @php $i = 0; @endphp
                @foreach ($model as $item)
                  @if($incerAux->count())
                         @php 
                         $incer = explode(',',$item->Incertidumbre);
                         @endphp
                    @endif
                 
                    <tr> 
                            <td class="tableContent bordesTablaBody" style="font-size: 11px;" height="25" rowspan="6">Toxicidad aguda ( <i>Vibrio fischeri</i> ) <sup>{{$item->Simbologia}} </sup></td>
                            <td class="tableContent bordesTablaBody" style="font-size: 11px;" height="25" rowspan="6">{{$item->Num_muestra}}</td>
                            <td class="tableContent bordesTablaBody" style="font-size: 11px;" rowspan="6">
                                {{$item->Clave_metodo}}
                            </td>
                            <td class="tableContent bordesTablaBody" style="font-size: 11px;" rowspan="3">CE50 %</td>
                            <td class="tableContent bordesTablaBody" style="font-size: 11px;"> 5 Min </td>
                            <td class="tableContent bordesTablaBody" style="font-size: 11px;">{{$item->Resultado}}</td>
                             @if($incerAux->count())
                                <td class="tableContent bordesTablaBody" style="font-size: 8px;"> {{@$incer[0]}}</td>
                            @endif
                            <td class="tableContent bordesTablaBody" style="font-size: 11px;" rowspan="6">
                                @if (@$item->Resultado2 != NULL)
                                    {{@$item->iniciales}}
                                @else
                                    -------
                                @endif
                            </td>
                    </tr>   
                    <tr>
                        <td class="tableContent bordesTablaBody" style="font-size: 11px;">15 Min</td>
                        <td class="tableContent bordesTablaBody" style="font-size: 11px;">{{$item->Resultado2}}</td>
                         @if($incerAux->count())
                          <td class="tableContent bordesTablaBody" style="font-size: 8px;"> {{@$incer[1]}}</td>
                    @endif
                    </tr>  
                    <tr>
                        <td class="tableContent bordesTablaBody" style="font-size: 11px;">30 Min</td>
                        <td class="tableContent bordesTablaBody" style="font-size: 11px;">{{$item->Resultado_aux}}</td>
                         @if($incerAux->count())
                          <td class="tableContent bordesTablaBody" style="font-size: 8px;"> {{@$incer[2]}}</td>
                    @endif
                    </tr>    
                    <tr>
                        <td class="tableContent bordesTablaBody" style="font-size: 11px;" rowspan="3">@if ($item->Ph_muestra == "1")
                        %E
                        @else
                            {{@$item->Unidad}}
                        @endif
                        </td>
                        <td class="tableContent bordesTablaBody" style="font-size: 11px;">5 Min</td>
                        <td class="tableContent bordesTablaBody" style="font-size: 11px;"> {{$item->Resultado_aux2}}</td>
                         @if($incerAux->count())
                          <td class="tableContent bordesTablaBody" style="font-size: 8px;"> {{@$incer[3]}}</td>
                    @endif
                    </tr>    
                    <tr>
                        <td class="tableContent bordesTablaBody" style="font-size: 11px;">15 Min</td>
                        <td class="tableContent bordesTablaBody" style="font-size: 11px;">{{$item->Resultado_aux3}}</td>
                         @if($incerAux->count())
                          <td class="tableContent bordesTablaBody" style="font-size: 8px;"> {{@$incer[4]}}</td>
                    @endif
                    </tr>    
                    <tr>
                        <td class="tableContent bordesTablaBody" style="font-size: 11px;">30 Min</td>
                        <td class="tableContent bordesTablaBody" style="font-size: 11px;">{{$item->Resultado_aux4}}</td>
                         @if($incerAux->count())
                          <td class="tableContent bordesTablaBody" style="font-size: 8px;"> {{@$incer[5]}}</td>
                    @endif
                    </tr>    
                        @php
                            $i++;
                        @endphp
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
                                                    NMX-AA-003-1980 / NMX-AA-014-1980
                                                @endif
                                                Y DE ACUERDO A PROCEDIMIENTO PE-10-002-{{str_pad($campoCompuesto->Proce_muestreo, 2, "0", STR_PAD_LEFT)}} <br>
                                                {{@$obsCampo}}
                                                @break
                                            @case(5) 
                                            @case(7)  
                                            @case(30)
                                                @if ($solModel->Id_servicio != 3)
                                                    OBSERVACIONES: TEMPERATURA AMBIENTE PROMEDIO DE {{@$tempAmbienteProm}}°C, 
                                                    @php if(@$swOlor == true) {echo "LA MUESTRA PRESENTA OLOR";} else{ echo "LA MUESTRA NO PRESENTA OLOR";}@endphp
                                                    Y COLOR DE LA MUESTRA {{$color}} ,
                                                    EL MUESTREO FUE REALIZADO DE ACUERDO A LO ESTABLECIDO EN EL PROCEDIMIENTO INTERNO PEA-10-002-01 Y DE ACUERDO A PROCEDIMIENTO PE-10-002-{{str_pad($campoCompuesto->Proce_muestreo, 2, "0", STR_PAD_LEFT)}} <br>
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
                                                    NMX-AA-003-1980  / NMX-AA-014-1980
                                                @endif

                                                Y DE ACUERDO A PROCEDIMIENTO PE-10-002-{{str_pad($campoCompuesto->Proce_muestreo, 2, "0", STR_PAD_LEFT)}} <br>
                                                {{@$obsCampo}}
                                        @endswitch
                                    @else 
                                    
                                    <p>OBSERVACIONES: MUESTRA REMITIDA AL LABORATORIO POR EL CLIENTE, LOS RESULTADOS SE APLICAN A LA MUESTRA COMO SE RECIBIÓ. {{@$modelProcesoAnalisis->Obs_proceso}}</p>
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
                            <td class="nombreHeader nom fontSize11 justificadorIzq"  style="font-size: 8px;margin:4px;width: 50%">
                                <br>
                                    @if ($firmaEncript2 != "")
                                        <center><p style="font-size: 8px">&nbsp;{{$firmaEncript1}}&nbsp;</p></center>
                                    @endif
                                <br>
                                <br>
                                <br>
                            </td> 
                            <td class="nombreHeader nom fontSize11 justificadorIzq"  style="font-size: 8px;margin:4px;width: 50%">
                                <br>
                                    <center><p style="font-size: 8px">&nbsp;{{$firmaEncript2}}&nbsp;</p></center>
                                <br>
                                <br>
                                <br>
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
                                    <p>
                                        @php
                                            echo $impresion[0]->Nota;
                                        @endphp
                                    </p>
                                    
                                    
                                    @switch(@$solModel->Id_norma)
                                        @case(27)
                                            @php
                                                echo @$impresion[0]->Nota_siralab;
                                            @endphp 
                                            @break
                                        @case(7)
                                            <p>
                                                @php
                                                    echo "LOS PARÁMETROS DE NITRATOS, SAAM, Y CIANUROS SE ANALIZARÁN BAJO ESPECIFICACIÓN PARA AGUA DE USO Y CONSUMO HUMANO.";
                                                @endphp
                                            </p>
                                            @break
                                        @default
                                            
                                    @endswitch
                                    @if ($solModel->Nota_4 == 1)
                                        4 PARAMETRO NO ACREDITADO
                                    @endif
                                    @if (@$tipo == 1)
                                        A SOLICITUD DEL CLIENTE SE COMPARA EL INFORME DE RESULTADOS CON LOS LIMITES PERMISIBLES DE LA NORMA
                                    @endif
                                   
                                </td>
                                <td style="width: 15%">
                                    @php
                                    $url = "http://sistemasofia.ddns.net:86/sofia/clientes/informe-de-resultados-acama/".urlencode(@$folioEncript);
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