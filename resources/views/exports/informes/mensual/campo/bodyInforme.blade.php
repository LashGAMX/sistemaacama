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
        INFORME  DE DATOS DE CAMPO 
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
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup"> {{$dirReporte}}</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup">&nbsp;</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup">&nbsp;</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup">&nbsp;@if (@$solModel1->Siralab == 1) 
                        TITULO DE CONCESIÓN: {{@$punto->Titulo}}
                    @else
                      
                    @endif</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqSinSup justificadoDer">
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
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup bordeSinIzqFinalSup" colspan="2">&nbsp;
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
                  
        </tbody>         
    </table>  
</div>
    <div id="contenedorTabla" >
        <p id='header1' style="font-size: 10px">METODO DE PRUEBA NMX-AA-008-SCFI-2016 DETERMINACIÓN DE POTENCIAL DE HIDROGENO</p>
        <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <thead>
                <tr>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px">HORA DE TOMA </td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8PX">N. MUESTRA</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8PX"> UNIDADE DE pH</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8PX">GASTO L/s</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8PX">Promedio &nbsp;</td>
                    <td style="border:none">&nbsp;&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px">HORA DE TOMA</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px">N. MUESTRA</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px">CONCENTRACION <br>Unidades de pH</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px">GASTO L/s</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8PX">Promedio &nbsp;</td>
                    <td style="border:none">&nbsp;&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px;">PROMEDIO MENSUAL PONDERADO</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px;">DECLARACION DE LA CONFORMIDAD </td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px;">EVALUACIÓN DE LA CONFORMIDAD</td>
                </tr>
            </thead>

            <tbody>
                @php
                    $cont = 0;
                @endphp
                    @foreach ($ph1 as $item)
                        <tr>
                            <td class="tableContent bordesTablaBody">{{\Carbon\Carbon::parse(@$item->Fecha)}}</td>
                            @if ($cont == 0)
                                <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}">{{@$solModel1->Folio_servicio}}</td>
                            @endif
                            @if ($item->Activo == 1)
                                <td class="tableContent bordesTablaBody">{{round($item->Promedio,2)}}</td>
                                <td class="tableContent bordesTablaBody">{{round($gastoProm1[$cont],2)}}</td>
                                @if ($cont == 0)
                                <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}"> {{number_format(round($promPh1,2), 2, ".", ",")}}</td>
                                @endif
                            @else
                                <td class="tableContent bordesTablaBody">----</td>
                                <td class="tableContent bordesTablaBody">----</td>
                                @if ($cont == 0)
                                    <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}">----</td>
                                @endif
                            @endif
                            <td style="border:none"> </td>
                            <td class="tableContent bordesTablaBody">{{\Carbon\Carbon::parse(@$ph2[$cont]->Fecha)}}</td>
                            @if ($cont == 0)
                                <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}">{{@$solModel2->Folio_servicio}}</td>
                            @endif
                            @if ($item->Activo == 1)
                                <td class="tableContent bordesTablaBody">{{round($ph2[$cont]->Promedio,2)}}</td>
                                <td class="tableContent bordesTablaBody">{{round($gastoProm2[$cont],2)}}</td>
                                @if ($cont == 0)
                                    <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}"> {{number_format(round($promPh2,2), 2, ".", ",")}}</td>
                                @endif
                            @else
                                <td class="tableContent bordesTablaBody">----</td> 
                                <td class="tableContent bordesTablaBody">----</td>
                                @if ($cont == 0)
                                    <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}">----</td>
                                @endif
                            @endif
                            <td style="border:none"> </td>
                            @if ($cont == 0)
                                <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}">{{round(($promPh1 + $promPh2 ) / 2,2)}}</td>
                                <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}">{{@$limPh->Pm}}</td>
                                @php
                                    $lim = explode("-",@$limPh->Pm);
                                @endphp
                            <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}">@if (round(($promPh1 + $promPh2 ) / 2,2) >= $lim[0] && round(($promPh1 + $promPh2 ) / 2,2) <= $lim[1])  CUMPLE @else NO CUMPLE @endif</td>
                            @endif
                        </tr>
                        @php
                            $cont++;
                        @endphp
                    @endforeach
            </tbody> 
        </table>

    </div>


    <div id="contenedorTabla" >
        <p id='header1' style="font-size: 10px">METODO DE PRUEBA NMX-AA-007-SCFI-2013 DETERMINACIÓN DE TEMPERATURA</p>
        <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <thead>
                <tr>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px">HORA DE TOMA </td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8PX">N. MUESTRA</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8PX">TEMPERATURA <br> DEL AGUA °C</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8PX">GASTO L/s &nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8PX">Promedio &nbsp;</td>
                    <td style="border:none">&nbsp;&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px">HORA DE TOMA</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px">N. MUESTRA</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px">TEMPERATURA <br> DEL AGUA °C</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px">GASTO L/s</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8PX">Promedio &nbsp;</td>
                    <td style="border:none">&nbsp;&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px;">PROMEDIO MENSUAL PONDERADO</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px;">DECLARACION DE LA CONFORMIDAD </td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px;">EVALUACIÓN DE LA CONFORMIDAD</td>
                </tr>
            </thead>

            <tbody>
                @php
                    $cont = 0;
                @endphp
                    @foreach ($tempModel1 as $item)
                        <tr>
                            <td class="tableContent bordesTablaBody">{{\Carbon\Carbon::parse(@$item->Fecha)}}</td>
                            @if ($cont == 0)
                                <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}">{{@$solModel1->Folio_servicio}}</td>
                            @endif
                            @if ($item->Activo == 1)
                                <td class="tableContent bordesTablaBody">{{round($item->Promedio,2)}}</td>
                                <td class="tableContent bordesTablaBody">{{round($gastoProm1[$cont],2)}}</td>
                                @if ($cont == 0)
                                    <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}"> {{round($promTemp1)}}</td>
                                @endif
                            @else
                                <td class="tableContent bordesTablaBody">----</td>
                                <td class="tableContent bordesTablaBody">----</td>
                                @if ($cont == 0)
                                <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}">----</td>
                            @endif
                            @endif
                            <td style="border:none"> </td>
                            <td class="tableContent bordesTablaBody">{{\Carbon\Carbon::parse(@$ph2[$cont]->Fecha)}}</td>
                            @if ($cont == 0)
                                <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}">{{@$solModel2->Folio_servicio}}</td>
                            @endif
                            @if ($item->Activo == 1)
                                <td class="tableContent bordesTablaBody">{{round($tempModel2[$cont]->Promedio,2)}}</td>
                                <td class="tableContent bordesTablaBody">{{round($gastoProm2[$cont],2)}}</td>
                                @if ($cont == 0)
                                <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}"> {{round($promTemp2)}}</td>
                                @endif
                            @else
                                <td class="tableContent bordesTablaBody">----</td>
                                <td class="tableContent bordesTablaBody">----</td>.
                                @if ($cont == 0)
                                    <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}">----</td>
                                @endif
                            @endif
                            <td style="border:none"> </td>
                            @if ($cont == 0)
                            <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}">{{round(($promTemp1 + $promTemp2 ) / 2)}}</td>
                            <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}">{{@$limTemp->Pm}}</td>
                            <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}">@if (round(($promTemp1 + $promTemp2 ) / 2) <= $limTemp->Pm ) CUMPLE @else NO CUMPLE @endif</td>
                            @endif
                        </tr>
                        @php
                            $cont++;
                        @endphp
                    @endforeach
            </tbody> 
        </table>

    </div>

    <div id="contenedorTabla" >
        <p id='header1' style="font-size: 10px">METODO DE PRUEBA NMX-AA-005-SCFI-2013 DETERMINACIÓN DE GRASAS Y ACEITES</p>
        <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <thead>
                <tr>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px">HORA DE TOMA </td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8PX">N. MUESTRA</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8PX">CONCENTRACION <br> mg/L</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8PX">GASTO L/s &nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8PX">Promedio &nbsp;</td>
                    <td style="border:none">&nbsp;&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px">HORA DE TOMA</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px">N. MUESTRA</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px">CONCENTRACION <br>mg/L</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px">GASTO L/s</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8PX">Promedio &nbsp;</td>
                    <td style="border:none">&nbsp;&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px;">PROMEDIO MENSUAL PONDERADO</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px;">DECLARACION DE LA CONFORMIDAD </td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px;">EVALUACIÓN DE LA CONFORMIDAD</td>
                </tr>
            </thead>

            <tbody>
                @php
                    $cont = 0;
                    $aux = 0;
                @endphp
                    @foreach ($ph1 as $item)
                        <tr>
                            <td class="tableContent bordesTablaBody">{{\Carbon\Carbon::parse(@$item->Fecha)}}</td>
                            @if ($cont == 0)
                                <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}">{{@$solModel1->Folio_servicio}}</td>
                            @endif
                            @if ($item->Activo == 1)
                                <td class="tableContent bordesTablaBody">{{number_format($grasasModel1[$aux]->Resultado, 2, ".", ",")}}</td>
                                <td class="tableContent bordesTablaBody">{{round($gastoProm1[$cont],2)}}</td>
                                @if ($cont == 0)
                                    <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}">{{number_format(round($promGa1,2), 2, ".", ",")}}</td>
                                @endif
                            @else
                                <td class="tableContent bordesTablaBody">----</td>
                                <td class="tableContent bordesTablaBody">----</td>
                                @if ($cont == 0)
                                <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}">----</td>
                                @endif
                            @endif
                            <td style="border:none"> </td>
                            <td class="tableContent bordesTablaBody">{{\Carbon\Carbon::parse(@$ph2[$cont]->Fecha)}}</td>
                            @if ($cont == 0)
                                <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}">{{@$solModel2->Folio_servicio}}</td>
                            @endif
                            @if ($item->Activo == 1)
                                <td class="tableContent bordesTablaBody">{{number_format($grasasModel2[$aux]->Resultado, 2, ".", ",")}}</td>
                                <td class="tableContent bordesTablaBody">{{round($gastoProm2[$cont],2)}}</td>
                                @if ($cont == 0)
                                 <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}">{{number_format(round($promGa2,2), 2, ".", ",")}}</td>
                                @endif
                            @else
                                <td class="tableContent bordesTablaBody">----</td>
                                <td class="tableContent bordesTablaBody">----</td>
                                @if ($cont == 0)
                                  <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}">----</td>
                                @endif
                            @endif
                            <td style="border:none"> </td>
                            @if ($cont == 0)
                            <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}">{{number_format(($promGa1 + $promGa2 ) / 2, 2, ".", ",")}}</td>
                            <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}">{{@$limGa->Pm}}</td>
                            <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}">@if (round(($promGa1 + $promGa2 ) / 2,2) <= $limGa->Pm ) CUMPLE @else NO CUMPLE @endif</td>
                            @endif
                        </tr>
                        @php
                            $cont++;
                            $aux++;
                        @endphp
                    @endforeach
            </tbody> 
        </table>

    </div>
    
@if ($colModel1->count())
<div id="contenedorTabla" >
    <p id='header1' style="font-size: 10px">METODO DE PRUEBA NMX-AA-042-SCFI-2015 / NMX-AA-167-SCFI-2017 DETERMINACIÓN DE E. COLI / ENTEROCOCOS </p>
    <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
        <thead>
            <tr>
                <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px">HORA DE TOMA </td>
                <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8PX">N. MUESTRA</td>
                <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8PX">NMP/100 mL</td>
                <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8PX">GASTO L/s</td>
                <td style="border:none">&nbsp;&nbsp;&nbsp;</td>
                <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px">HORA DE TOMA</td>
                <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px">N. MUESTRA</td>
                <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px">CONCENTRACION <br>Unidades de pH</td>
                <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px">GASTO L/s</td>
                <td style="border:none">&nbsp;&nbsp;&nbsp;</td>
                <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px;">PROMEDIO MENSUAL PONDERADO</td>
                <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px;">DECLARACION DE LA CONFORMIDAD </td>
                <td class="tableCabecera bordesTablaBody justificadoCentr" style="font-size: 8px;">EVALUACIÓN DE LA CONFORMIDAD</td>
            </tr>
        </thead>

        <tbody>
            @php
                $cont = 0;
                $aux = 0;
            @endphp
                @foreach ($ph1 as $item)
                    <tr>
                        <td class="tableContent bordesTablaBody">{{\Carbon\Carbon::parse(@$item->Fecha)}}</td>
                        @if ($cont == 0)
                            <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}">{{@$solModel1->Folio_servicio}}</td>
                        @endif
                        @if ($item->Activo == 1)
                            <td class="tableContent bordesTablaBody">{{round($colModel1[$aux]->Resultado,2)}}</td>
                            <td class="tableContent bordesTablaBody">{{round($gastoProm1[$cont],2)}}</td>
                        @else
                            <td class="tableContent bordesTablaBody">----</td>
                            <td class="tableContent bordesTablaBody">----</td>
                        @endif
                        <td style="border:none"> </td>
                        <td class="tableContent bordesTablaBody">{{\Carbon\Carbon::parse(@$ph2[$cont]->Fecha)}}</td>
                        @if ($cont == 0)
                            <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}">{{@$solModel2->Folio_servicio}}</td>
                        @endif
                        @if ($item->Activo == 1)
                        <td class="tableContent bordesTablaBody">{{round($colModel2[$aux]->Resultado,2)}}</td>
                            <td class="tableContent bordesTablaBody">{{round($gastoProm2[$cont],2)}}</td>
                        @else
                            <td class="tableContent bordesTablaBody">----</td>
                            <td class="tableContent bordesTablaBody">----</td>
                        @endif
                        <td style="border:none"> </td>
                        @if ($cont == 0)
                        <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}">{{round(($promCol1 + $promCol2 ) / 2)}}</td>
                        <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}">{{@$limCol->Pm}}</td>
                            <td class="tableContent bordesTablaBody" rowspan="{{$ph1->count()}}">{{@$solModel1->Folio_servicio}}</td>
                        @endif
                    </tr>
                    @php
                        $cont++;
                        $aux++;
                    @endphp
                @endforeach
        </tbody> 
    </table>

</div>
@endif
{{-- 
    <div autosize="1" class="" cellpadding="0" cellspacing="0" border-color="#000000" style="position: relative;margin-top: auto">

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
    </div> --}}
{{--     
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
    </div>     --}}
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
        


    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <thead>
                <tr>                    
                <td></td>                                                                   
                </tr>

                <tr>
                    <td style="text-align: right;"><span class="revisiones">FO-13-001</span> <br> <span class="revisiones">Revisión 5</span></td>
                </tr>
            </thead>                        
        </table>  
    </div> 

    
    </footer>
    
</body>


</html>