<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/informes/conComparacion/conComparacion.css')}}">
    <title>Informe Sin Comparación</title>
</head>

<body>
    <p id='header1'>
        INFORME DE RESULTADOS AGUA RESIDUAL
    </p>
<div id="contenedorTabla">
    <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
        <tbody>            
                <tr>
                    <td class="filasIzq bordesTabla anchoColumna7 bordeDer paddingTopBot">Empresa:</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzq" width="400.8px">{{$solModel1->Empresa}}</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzq" width="137.3px">&nbsp;</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzq" width="92.9px">&nbsp;</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzq">&nbsp;RFC: {{@$rfc->RFC}}</td>                    
                    <td class="filasIzq bordesTabla fontBold bordeFinal justificadoDer" ></td>                    
                </tr>

                <tr>                    
                    <td class="filasIzq bordesTabla bordeDerSinSup paddingTopBot">Dirección:</td>                    
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup"> {{$solModel1->Direccion}}</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup">&nbsp;</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup">&nbsp;</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup">&nbsp;Titulo de conseción: {{@$titulo->Titulo}}</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqSinSup justificadoDer">
                        @if (@$solModel1->Siralab == 1) 
                            TITULO DE CONCESIÓN: {{@$punto->Titulo}}
                        @else
                          
                        @endif
                    </td>
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
                            echo (@$gasto1->Resultado2 + @$gasto2->Resultado2) / 2;
                        @endphp
                    </td>
                    <td class="filasIzq bordesTabla bordeIzqSinSup fontSize6 fontBold bordeIzqDerSinSup justificadorCentr">L/s</td>                                        
                    <td class="filasIzq bordesTabla soloBordeDer fontBold fontSize12 justificadorCentr" rowspan="9" width="13.05%">
                        {{@$solModel1->Clave_norma}}
                        @if (@$tipo == 1)
                            LIMITES MAXIMOS 
                            PERMISIBLES
                        @endif
                        @if (@$tipoReporte->Id_categoria == 4 || @$tipoReporte->Id_categoria == 5 || @$tipoReporte->Id_categoria == 6)
                            (Suelo) {{@$tipoReporte->Categoria}}
                        @else
                            {{@$tipoReporte->Categoria}}
                        @endif
                    </td>
                </tr>
                    
                <tr>
                    <td class="filasIzq bordesTabla soloBordeSup paddingTopBotInter fontSize6">GASTO LPS</td>
                    <td class="filasIzq bordesTabla bordeDerSinSup paddingTopBotInter justificadorCentr fontSize6 fontBold">{{@$gasto1->Resultado2}}</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup paddingTopBotInter justificadorCentr fontSize6 fontBold">{{@$gasto2->Resultado2}}</td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla soloBordeSup paddingTopBotInter fontSize6">FECHA DE MUESTREO:</td>
                    <td class="filasIzq bordesTabla bordeDerSinSup paddingTopBotInter justificadorCentr fontSize6 fontBold">{{ \Carbon\Carbon::parse(@$solModel1->Fecha_muestreo)->format('d/m/Y')}}</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup paddingTopBotInter justificadorCentr fontSize6 fontBold">{{ \Carbon\Carbon::parse(@$solModel2->Fecha_muestreo)->format('d/m/Y')}}</td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla soloBordeSup paddingTopBotInter fontSize6">FECHA DE RECEPCION:</td>
                    <td class="filasIzq bordesTabla bordeDerSinSup paddingTopBotInter justificadorCentr fontSize6 fontBold">{{ \Carbon\Carbon::parse(@$proceso1->Hora_recepcion)->format('d/m/Y')}}</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup paddingTopBotInter justificadorCentr fontSize6 fontBold">{{ \Carbon\Carbon::parse(@$proceso2->Hora_recepcion)->format('d/m/Y')}}</td>
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
                    <td class="filasIzq bordesTabla bordeDerSinSup paddingTopBotInter justificadorCentr fontSize6 fontBold">DE {{\Carbon\Carbon::parse(@$proceso1->Hora_entrada)->format('d/m/Y')}} A {{\Carbon\Carbon::parse($proceso1->Hora_entrada)->addDays(7)->format('d/m/Y')}}</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup paddingTopBotInter justificadorCentr fontSize6 fontBold">DE {{\Carbon\Carbon::parse(@$proceso2->Hora_entrada)->format('d/m/Y')}} A {{\Carbon\Carbon::parse($proceso2->Hora_entrada)->addDays(7)->format('d/m/Y')}}
                    </td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla soloBordeSup paddingTopBotInter fontSize6">TIPO DE MUESTREO:</td>
                    <td class="filasIzq bordesTabla bordeDerSinSup paddingTopBotInter fontSize6 fontBold justificadorCentr">@if (@$solModel1->Id_muestra == 1)
                        INSTANTANEA
                    @else   
                        COMPUESTA
                    @endif</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup paddingTopBotInter fontSize6 fontBold justificadorCentr">@if (@$solModel2->Id_muestra == 1)
                        INSTANTANEA
                    @else   
                        COMPUESTA
                    @endif</td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla soloBordeSup paddingTopBotInter fontSize6">N° DE MUESTRA:</td>
                    <td class="filasIzq bordesTabla bordeDerSinSup paddingTopBotInter fontSize6 fontBold justificadorCentr">{{@$solModel1->Folio_servicio}}</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup paddingTopBotInter fontSize6 fontBold justificadorCentr">{{@$solModel2->Folio_servicio}}</td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla bordeSupDer paddingTopBotInter fontSize6">N° DE ORDEN:</td>
                    <td class="filasIzq bordesTabla soloBordeDer paddingTopBotInter fontSize6 fontBold justificadorCentr">{{@$numOrden1->Folio_servicio}}</td>
                    <td class="filasIzq bordesTabla soloBordeDer paddingTopBotInter fontSize6 fontBold justificadorCentr">{{@$numOrden2->Folio_servicio}}</td>
                </tr>                
        </tbody>         
    </table>  
</div>
    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0" cellspacing="0"
            border-color="#000000" width="100%">
            <thead>
                <tr>
                    @if (@$tipo == 1)
                        <td class="tableCabecera bordesTablaBody justificadoCentr" width="47%">PARAMETRO &nbsp;</td>
                    @else
                        <td class="tableCabecera bordesTablaBody justificadoCentr" width="40.9%">PARAMETRO &nbsp;</td>    
                    @endif 
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="7%">&nbsp;UNIDAD&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="14%">&nbsp;METODO DE
                        PRUEBA&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="9.45%">&nbsp;PROMEDIO DIARIO&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="9.45%">&nbsp;PROMEDIO DIARIO&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr">&nbsp;C.P.M&nbsp;&nbsp;</td>
                    @if (@$tipo == 1)
                        <td class="tableCabecera bordesTablaBody justificadoCentr" width="9%">&nbsp;DECLARACION DE LA CONFORMIDAD &nbsp;&nbsp;</td>
                    @endif
                </tr>
            </thead>

            <tbody>
                @php
                    $cont = 0; 
                @endphp
                @foreach ($model1 as $item)
                    <tr>
                        <td class="tableContentLeft bordesTablaBody">{{$item->Parametro}}<sup>{{$item->Simbologia}}</sup></td>
                        <td class="tableContent bordesTablaBody">{{$item->Unidad}}</td>
                        <td class="tableContent bordesTablaBody">{{$item->Clave_metodo}}</td>
                        <td class="tableContent bordesTablaBody">
                            {{@$limitesC1[$cont]}}
                        </td>                    
                        
                        <td class="tableContent bordesTablaBody">
                            {{@$limitesC2[$cont]}}
                        </td>

                        <td class="tableContent bordesTablaBody">
                            {{@$ponderado[$cont]}}
                        </td>
                        @if (@$tipo == 1)
                            <td class="tableContent bordesTablaBody">
                                {{@$limitesN[$cont]}} 
                            </td>
                        @endif
                    </tr>
                    @php $cont++; @endphp
                @endforeach
            </tbody>
        </table>
    </div>

    <br>

    <div autosize="1" class="" cellpadding="0" cellspacing="0" border-color="#000000">

        <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <tbody>            
                    <tr>
                        <td class="nombreHeader nom fontSize727 justificadorIzq">OBSERVACIONES: TEMPERATURA AMBIENTE PROMEDIO DE 25.90 Y 25.83 LAS MUESTRAS 
                            PRESENTAN OLOR Y COLOR TURBIO EL MUESTREO FUE REALIZADO DE ACUERDO A LO ESTABLECIDO EN LA NMX-AA-003-1980 Y DE ACUERTO A PROCEDIMIENTO PE-10-002-04
                            DÍA PARCIALMENTE NUBLADO , EQUIPO UTILIZADO INVLAB 650 E INVLAB 673, NO HUBO FLUJO EN EL NUMERO DE MUESTRA 4,5 Y 6 YAQUE NO HUBO DESCARGA.
                            DÍA PARCIALMENTE NUBLADO, EQUIPO UTILIZADO INVLAB650 E INVLAB 673,  NO HUBO FLUJO EN EL NUMERO DE MUESTRA 4,5 Y 6 YA QUE NO HUBO DESCARGA.

                            </td>
                    </tr>                
            </tbody>         
        </table> 

        <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <tbody>            
                    <tr>
                        <td class="nombreHeaders fontBold fontSize5 justificadorIzq" colspan="2">NOTA: INTERPRETAR EL PUNTO (.) COMO SIGNO DECIMAL SEGÚN NORMA NOM-008-SCFI-2002 <br>
                            LOS VALORES CON EL SIGNO MENOR (<) CORRESPONDEN AL VALOR MÍNIMO CUANTIFICADO POR EL MÉTODO. <br>
                            ESTE REPORTE NO DEBE REPRODUCIRSE SIN LA APROBACIÓN DEL LABORATORIO EMISOR. <br>
                            N.A INTERPRETAR COMO NO APLICA. <br>
                            N.N INTERPRETAR COMO NO NORMADO. <br>
                            NOTA 2: LOS DATOS EXPRESADOS AVALAN ÚNICAMENTE LOS RESULTADOS DE LA MUESTRA ANALIZADA. <br> <br>
                     
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
                    @foreach ($model1 as $item)
                        @for ($i = 0; $i < sizeof($temp); $i++)
                            @if ($temp[$i] == $item->Id_simbologia_info)
                                @php $sw = true; @endphp
                            @endif
                        @endfor
                        @if ($sw != true)
                            @switch($item->Id_parametro)
                                @case(97)
                                    <tr>
                                        <td class="nombreHeaders fontBold fontSize5 justificadorIzq">{{$item->Simbologia_inf}} @php print  $item->Descripcion2; @endphp</td>
                                    </tr>
                                    <tr>
                                        <td class="nombreHeaders fontBold fontSize5 justificadorIzq">*** LA DETERMINACIÓN DE LA TEMPERATURA DE LA MUESTRA COMPUESTA ES DE {{@$campoCompuesto->Temp_muestraComp}}°C Y EL PH COMPUESTO ES DE {{@$campoCompuesto->Ph_muestraComp}}</td>
                                    </tr>
                                    @php
                                        array_push($temp,$item->Id_simbologia_info);
                                    @endphp
                                    @break
                                @default
                                <tr>
                                    <td class="nombreHeaders fontBold fontSize5 justificadorIzq">{{$item->Simbologia_inf}} @php print  $item->Descripcion2; @endphp</td>
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
    <footer>    
        <div autosize="1" class="contenedorPadre12">
         
                
        <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <tbody>            
    
                    <tr>
                        <td class="justificadorCentr">
                            @php
                                /*$url = url()->current();*/
                                $url = "https://sistemaacama.com.mx/clientes/informeMensualSinComparacion/".@$solModel->Id_solicitud;
                                $qr_code = "data:image/png;base64," . \DNS2D::getBarcodePNG((string) $url, "QRCODE");
                            @endphp
                                                            
                            <br>
                            <img style="width: 11%; height: 11%;" src="{{@$qr_code}}" alt="qrcode" /> <br> <span class="fontSize9 fontBold"> {{@$solModel->Folio_servicio}}</span>
                        </td>         
                        <td>
                            <span><img style="width: auto; height: auto; max-width: 100px; max-height: 80px;" src="{{asset('public/storage/'.$firma1->firma)}}"> <br></span>
                            <span class="bodyStdMuestra fontSize5"> BIOL. GUADALUPE GARCÍA PÉREZ{{-- {{@$usuario->name}} --}}</span> <br>
                            <span class="cabeceraStdMuestra fontNormal fontSize5"> REVISÓ SIGNATARIO</span>
                        
                        </td>
                        <td>
                            
                            <span><img style="width: auto; height: auto; max-width: 100px; max-height: 80px;" src="{{asset('public/storage/'.$firma2->firma)}}"> <br></span>          
                            <br>
                            <span class="bodyStdMuestra fontSize5"> TSU. MARÍA IRENE REYES MORALES{{-- {{@$usuario->name}} --}} </span> <br>
                            <span class="cabeceraStdMuestra fontNormal fontSize5"> AUTORIZÓ SIGNATARIO</span> 
                        </td>
                    </tr>
            </tbody>         
        </table>                                                        
    </div>
            <div class="contenedorSubPadre12" cellpadding="0" cellspacing="0" border-color="#000000" style="text-align:right;">
          
                <br>
    
                <span class="revisiones">FO-13-001</span> <br> <span class="revisiones fontSize5">Revisión 5</span>
            </div>    
        </div>    
        <br> <br>
    </footer>
    
</body>


</html>