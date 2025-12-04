<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('/public/css/informes/sinComparacion/sinComparacion.css') }}">
    <title>Informe @if ($tipo == 1)
            Con
        @else
            Sin
        @endif Comparación</title>

</head>

<body>
    <p id='header1'>
        INFORME DE RESULTADOS
        <br> MUESTRA
        @if (@$datos->Id_muestra == 1 || @$datos->Id_muestra == 0)
            INSTANTANEA
        @else
            COMPUESTA
        @endif
    </p>
    <div style="width: 100%">
        <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0"
            border-color="#000000" width="100%">
            <tbody>
                <tr>
                    <td class="filasIzq bordesTabla anchoColumna7 bordeDer paddingTopBot" style="font-size:10px">
                        Empresa:</td>
                    <td class="filasIzq bordesTabla fontBold anchoColumna82 bordeIzq" style="font-size: 10px">
                        {{ @$datos->Sucursal }}</td>
                    <td class="filasIzq bordesTabla fontBold anchoColumna11 bordeFinal justificadoDer">
                        @if (@$datos->Siralab == 1)
                            RFC: {{ @$datos->RFC }}
                        @else
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="filasIzq bordesTabla anchoColumna7 bordeDerSinSup paddingTopBot"
                        style="font-size: 10px;">Dirección:</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqSinSup" colspan="2" style="font-size: 10px;">
                        {{ @$datos->Direccion }}</td>
                </tr>
                <tr>
                    <td class="filasIzq bordesTabla anchoColumna7 bordeDerSinSup" rowspan="6"
                        style="font-size: 10px">
                        Punto de muestreo:</td>
                    <td class="filasIzq bordesTabla fontBold anchoColumna60 bordeIzqDerSinSup" rowspan="6"
                        style="font-size: 10px;">
                        @php
                            echo $datos->Punto;
                        @endphp
                    </td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">Fecha de
                        Muestreo: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="fontBold">
                            {{ \Carbon\Carbon::parse(@$datos->Fecha_muestreo)->format('d/m/Y') }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">Hora de
                        muestreo:
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="fontBold">
                            @if ($datos->Id_servicio != 3)
                                @switch($datos->Id_norma)
                                    @case(30)
                                    @case(7)
                                        {{ @$datos->Hora }}
                                    @break

                                    @case(9)
                                        @if ($datos->Num_tomas > 1)
                                            COMPUESTA
                                        @else
                                            {{ @$datos->Hora }}
                                        @endif
                                    @break

                                    @default
                                        @if ($datos->Num_tomas > 1)
                                            COMPUESTA
                                        @else
                                            @switch($datos->Id_norma)
                                                @case(1)
                                                @case(27)

                                                @case(2)
                                                @case(4)
                                                    {{ @$datos->Hora }}
                                                @break

                                                @default
                                                    INSTANTANEA
                                            @endswitch
                                        @endif
                                    @endswitch
                                @else
                                    @if ($datos->Num_tomas > 1)
                                        COMPUESTA
                                    @else
                                        @if ($datos->Id_muestra == 2)
                                            COMPUESTA
                                        @else
                                            INSTANTANEA
                                        @endif
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
                                class="fontBold">{{ \Carbon\Carbon::parse(@$datos->Hora_recepcion)->format('d/m/Y') }}</span>
                        </td>
                    </tr>

                    <tr>
                        <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">Fecha de
                            Emisión:
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="fontBold">
                                @if ($datos->Emision_informe == null)
                                    @switch($datos->Id_norma)
                                        @case(1)
                                        @case(27)
                                            {{ \Carbon\Carbon::parse(@$datos->Hora_recepcion)->addDays(11)->format('d/m/Y') }}
                                        @break

                                        @case(5)
                                        @case(30)
                                            {{ \Carbon\Carbon::parse(@$datos->Hora_recepcion)->addDays(14)->format('d/m/Y') }}
                                        @break

                                        @default
                                            {{ \Carbon\Carbon::parse(@$datos->Hora_recepcion)->addDays(11)->format('d/m/Y') }}
                                    @endswitch
                                @else
                                    {{ \Carbon\Carbon::parse(@$datos->Emision_informe)->format('d/m/Y') }}
                                @endif


                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">N° de
                            Muestra:
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;
                            <span class="fontBold">{{ @$datos->Folio_servicio }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">N° de Orden:
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            @php
                                $folio = $datos->Folio_servicio ?? '';
                                $parts = explode('/', $folio);
                                $firstPart = $parts[0] ?? '';
                                $secondPart = isset($parts[1]) ? explode('-', $parts[1])[0] : '';
                            @endphp
                            <span class="fontBold">{{ $firstPart }}/{{ $secondPart }}</span>

                        </td>
                    </tr>
                    <tr>
                        <td class="filasIzq bordesTabla anchoColumna11 bordeDer" style="font-size: 10px">Periodo de
                            análisis:</td>
                        <td class="filasIzq bordesTabla bordeSinIzqFinalSup anchoColumna28 fontBold" colspan="2"
                            style="font-size: 10px">DE
                            {{ \Carbon\Carbon::parse(@$datos->Hora_recepcion)->format('d/m/Y') }}
                            A

                            @if ($datos->Emision_informe == null)
                                @switch($datos->Id_norma)
                                    @case(1)
                                    @case(27)
                                        {{ \Carbon\Carbon::parse(@$datos->Hora_recepcion)->addDays(11)->format('d/m/Y') }}
                                    @break

                                    @case(5)
                                    @case(30)
                                        {{ \Carbon\Carbon::parse(@$datos->Hora_recepcion)->addDays(14)->format('d/m/Y') }}
                                    @break

                                    @default
                                        {{ \Carbon\Carbon::parse(@$datos->Hora_recepcion)->addDays(11)->format('d/m/Y') }}
                                @endswitch
                            @else
                                {{ \Carbon\Carbon::parse(@$datos->Emision_informe)->format('d/m/Y') }}
                            @endif
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="fontBold">
                                @if (@$datos->Atencion == null)
                                @else
                                    Atención a: {{ @$datos->Atencion }}
                                @endif
                            </span>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="fontBold">
                                @if (@$datos->Siralab == 1)
                                    TITULO DE CONCESIÓN: {{ @$datos->Titulo }}
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

                            @if (!empty($newTitulo))
                                CPD
                            @elseif (@$datos->Id_solicitud == 33553 || @$datos->Id_solicitud == 32953)
                                <p>CONDICIONES PARTICULARES DE DESCARGA </p>
                            @else
                                @switch(@$datos->Id_norma)
                                    @case(1)
                                        DE ACUERDO A NOM-001-SEMARNAT-1996
                                        @if (@$solModel->Id_muestra == 1)
                                            INSTANTANEA
                                        @else
                                            COMPUESTA
                                        @endif
                                        @if (@$solModel->Id_reporte != 0)
                                            TIPO "{{ @$tipoReporte->Tipo }}", {{ @$tipoReporte->Cuerpo }} -
                                            {{ @$tipoReporte->Detalle }}
                                        @else
                                        @endif
                                        QUE ESTABLECE LOS LIMITES MAXIMOS PERMISIBLES DE CONTAMINANTES EN LAS DESCARGAS DE AGUAS
                                        RESIDUALES EN AGUAS Y
                                        BIENES NACIONALES.
                                    @break

                                    @case(27)
                                        DE ACUERDO A LA NOM-001-SEMARNAT-2021
                                        @if (@$solModel->Id_muestra == 1)
                                            INSTANTANEA
                                        @else
                                            COMPUESTA
                                        @endif
                                        QUE ESTABLECE LOS LIMITES PERMISIBLES DE CONTAMINANTES EN LAS DESCARGAS DE AGUAS RESIDUALES
                                        EN
                                        CUERPO RECEPTORES PROPIEDAD DE LA NACION
                                    @break

                                    @case(2)
                                        DE ACUERDO A NOM-002-SEMARNAT-1996 PARA MUESTRA
                                        @if (@$solModel->Id_muestra == 1)
                                            INSTANTANEA
                                        @else
                                            COMPUESTA
                                        @endif
                                        <br> QUE ESTABLECE LOS LIMITES MAXIMOS PERMISIBLES DE CONTAMINANTES EN LAS DESCARGAS DE
                                        AGUAS
                                        RESIDUALES A LOS <br> SISTEMAS DE ALCANTARILLADO URBANO O MUNICIPAL.
                                    @break

                                    @case(4)
                                        DE ACUERDO A NOM-003-SEMARNAT-1997 PARA MUESTRA
                                        @if (@$solModel->Id_muestra == 1)
                                            INSTANTANEA
                                        @else
                                            COMPUESTA
                                        @endif
                                        <br> QUE ESTABLECE LOS LIMITES MAXIMOS PERMISIBLES <br> DE CONTAMINANTES PARA LAS AGUAS
                                        RESIDUALES
                                        <br> TRATADAS QUE SE REUSEN EN SERVICIOS AL PÚBLICO
                                    @break

                                    @case(5)
                                        DE ACUERDO A MODIFICACIÓN A LA NORMA OFICIAL MEXICANA NOM-127-SSA1-1994, PARA MUESTRA
                                        @if (@$solModel->Id_muestra == 1)
                                            INSTANTANEA
                                        @else
                                            COMPUESTA
                                        @endif
                                        SALUD AMBIENTAL. AGUA PARA USO Y CONSUMO HUMANO. LÍMITES PERMISIBLES DE CALIDAD Y
                                        TRATAMIENTOS A
                                        QUE
                                        DEBE <br> SOMETERSE EL AGUA PARA SU POTABILIZACION.
                                    @break

                                    @case(7)
                                        DE ACUERDO A NORMA OFICIAL MEXICANA NOM-201-SSA1-2015 PARA MUESTRA
                                        @if (@$solModel->Id_muestra == 1)
                                            INSTANTANEA
                                        @else
                                            COMPUESTA
                                        @endif
                                        PRODUCTOS Y SERVICIOS. AGUA Y HIELO PARA CONSUMO HUMANO, ENVASADOS A GRANEL.
                                        ESPECIFICACIONES
                                        SANITARIAS.
                                    @break

                                    @case(30)
                                        DE ACUERDO A LA NORMA OFICIAL MEXICANA NOM-127-SSA1-2021, AGUA PARA USO Y CONSUMO HUMANO.
                                        LÍMITES PERMISIBLES DE LA CALIDAD DEL AGUA.
                                        {{-- SALUD AMBIENTAL. AGUA PARA USO Y CONSUMO HUMANO. LÍMITES PERMISIBLES DE CALIDAD Y TR
                        ATAMIENTOS A QUE
                        DEBE <br> SOMETERSE EL AGUA PARA SU POTABILIZACION. --}}
                                    @break

                                    @default
                                        DE ACUERDO A {{ $norma->Norma }} PARA MUESTRA
                                        @if (@$solModel->Id_muestra == 1 || @$solModel->Id_muestra == 0)
                                            INSTANTANEA
                                        @else
                                            COMPUESTA
                                        @endif
                                        <br>
                                        {{ $norma->Clave_norma }}
                                    @break
                                @endswitch
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        @if ($datos2->count() > 0)
            <div id="contenedorTabla">
                <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0"
                    border-color="#000000" width="100%">
                    <thead>
                        <tr>
                            <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr"
                                height="30" width="20.6%">PARAMETRO &nbsp;</td>
                            <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr"
                                width="20.6%">
                                &nbsp;METODO DE PRUEBA&nbsp;&nbsp;
                            </td>
                            <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr"
                                width="10.6%">
                                &nbsp;UNIDAD&nbsp;&nbsp;
                            </td>
                            <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr"
                                width="10.6%">
                                &nbsp;CONCENTRACION <br> CUANTIFICADA&nbsp;&nbsp;
                            </td>

                            @if ($tipo == 1)
                                @switch(optional($datos2->first())->Id_promedio)
                                    @case(1)
                                        <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr"
                                            width="10.6%">
                                            &nbsp;LIMITE PERMISIBLE <br> INSTANTANEO&nbsp;&nbsp;
                                        </td>
                                    @break

                                    @case(2)
                                        <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr"
                                            width="10.6%">
                                        </td>
                                        {{-- Aquí podrías agregar otra columna si aplica --}}
                                    @break

                                    @case(3)
                                        <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr"
                                            width="10.6%">
                                            &nbsp;LIMITE PERMISIBLE <br> P.D&nbsp;&nbsp;
                                        </td>
                                    @break

                                    @default
                                        <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr"
                                            width="10.6%">
                                        </td>
                                        <!-- <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr" width="10.6%">
                                         &nbsp;LIMITE PERMISIBLE <br> P.D&nbsp;&nbsp;
                                        </td> -->
                                @endswitch
                            @endif
                            {{-- Columna de incertidumbre si existe --}}
                            @if ($incerAux > 0)
                                <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr"
                                    width="10.6%">
                                    &nbsp;INCERTIDUMBRE&nbsp;&nbsp;
                                </td>
                            @endif
                            <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr"
                                width="10.6%">
                                ANALISTA
                            </td>
                        </tr>
                    </thead>

                    <tbody>
                        @php  $i = 0; @endphp
                        @foreach ($datos2 as $item)
                            @if (@$item->Id_area != 9)
                                @switch($item->Id_parametro)
                                    @case(365)
                                        @php
                                            $cod = DB::table('codigo_parametro')
                                                ->where('Id_codigo', $item->Id_codigo)
                                                ->first();
                                        @endphp

                                        <tr>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;" height="25">
                                                {{ @$item->Parametro }}<sup>{{ $item->Simbologia }}</sup>
                                            </td>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                {{ $item->Metodo }}
                                            </td>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">{{ @$item->Unidad }}
                                            </td>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                @if (@$item->Resultado2 != null)
                                                    @if ($item->Resultado2 <= $item->Limite)
                                                    < {{ $item->Limite }} @else {{ @$item->Resultado2 }} @endif
                                                        @else
                                                            -------
                                                    @endif

                                            </td>
                                            @if ($tipo == 1)
                                                <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                    @if (@$item->Resultado2 != null)
                                                        {{ $item->Resultado2 }}
                                                    @else
                                                        -------
                                                    @endif

                                                </td>
                                            @endif
                                            @if ($incerAux > 0)
                                                <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                    {{ $item->Incertidumbre }}</td>
                                            @endif
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                @if (@$item->Resultado2 != null)
                                                    {{ @$item->AnalizoInicial }}
                                                @else
                                                    -------
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;" height="25">
                                                pH muestra filtrada <sup>{{ $item->Simbologia }}</sup> <br> análisis color
                                            </td>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                NMX-AA-008-SCFI-2016
                                            </td>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">UpH</td>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                @if (@$item->Ph_muestra != null)
                                                    {{ number_format(@$item->Ph_muestra, 1, '.', '') }}
                                                @else
                                                    -------
                                                @endif

                                            </td>
                                            @if ($tipo == 1)
                                                <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                    @if (@$item->Ph_muestra != null)
                                                        {{ number_format(@$item->Ph_muestra, 1, '.', '') }}
                                                    @else
                                                        -------
                                                    @endif

                                                </td>
                                            @endif
                                            @if ($incerAux > 0)
                                                <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                    {{ $item->Incertidumbre }}</td>
                                            @endif
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                @if (@$item->Ph_muestra != null)
                                                    {{ @$item->AnalizoInicial }}
                                                @else
                                                    -------
                                                @endif
                                            </td>
                                        </tr>
                                    @break

                                    @case(370)
                                        @php
                                            $cod = DB::table('codigo_parametro')
                                                ->where('Id_codigo', $item->Id_codigo)
                                                ->first();
                                        @endphp
                                        <tr>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;" height="25">
                                                {{ @$item->Parametro }}<sup>{{ $item->Simbologia }}</sup>
                                            </td>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                {{ $item->Metodo }}
                                            </td>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">{{ @$item->Unidad }}
                                            </td>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                {{ $item->Resultado2 }}
                                            </td>

                                            @if ($tipo == 1)
                                                <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                    @if (@$item->Resultado2 != null)
                                                        @switch($item->Id_parametro)
                                                            @case(64)
                                                                {{ @$item->LimiteNorma }}
                                                            @break

                                                            @default
                                                                {{ @$item->LimiteNorma }}
                                                        @endswitch
                                                    @else
                                                        -------
                                                    @endif

                                                </td>
                                            @endif
                                            @if ($incerAux > 0)
                                                <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                    {{ $item->Incertidumbre }}</td>
                                            @endif
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                @if (@$item->Resultado2 != null)
                                                    {{ @$item->AnalizoInicial }}
                                                @else
                                                    -------
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;" height="25">
                                                pH muestra filtrada <sup>{{ $item->Simbologia }}</sup> <br> análisis color
                                            </td>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                APÉNDICE B NORMATIVO B.9 NOM-127-SSA1-2021
                                            </td>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">UpH</td>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                @if (@$item->Ph_muestra != null)
                                                    {{ number_format(@$item->Ph_muestra, 1, '.', '') }}
                                                @else
                                                    -------
                                                @endif

                                            </td>
                                            @if ($tipo == 1)
                                                <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                    N/A
                                                </td>
                                            @endif
                                            @if ($incerAux > 0)
                                                <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                    {{ $item->Incertidumbre }}</td>
                                            @endif
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                @if (@$item->Ph_muestra != null)
                                                    {{ @$item->AnalizoInicial }}
                                                @else
                                                    -------
                                                @endif
                                            </td>
                                        </tr>
                                    @break

                                   

                                    @case(372)
                                        @php
                                            $parametro = DB::table('lote_detalle_directos')
                                                ->where('Id_codigo', $item->Id_codigo)
                                                ->where('Id_control', 1)
                                                ->first();
                                            $cod = DB::table('codigo_parametro')
                                                ->where('Id_codigo', $item->Id_codigo)
                                                ->first();
                                        @endphp
                                        <tr>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;" height="25">
                                                @if ($parametro->Color_a == 0)
                                                    Color Aparente
                                                @elseif ($parametro->Color_v == 0)
                                                    Color Verdadero
                                                @endif
                                            </td>
                                        </tr>

                                        <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                            {{ $item->Metodo }}
                                        </td>
                                        <td class="tableContent bordesTablaBody" style="font-size: 8px;">{{ @$item->Unidad }}
                                        </td>
                                        <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                            {{ @$item->Resultado2 }}
                                        </td>

                                        @if ($tipo == 1)
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                @if (@$item->Resultado2 != null)
                                                    @switch($item->Id_parametro)
                                                        @case(64)
                                                            {{ @$item->LimiteNorma }}
                                                        @break

                                                        @default
                                                            {{ @$item->LimiteNorma }}
                                                    @endswitch
                                                @else
                                                    -------
                                                @endif

                                            </td>
                                        @endif
                                        @if ($incerAux > 0)
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                {{ $item->Incertidumbre }}</td>
                                        @endif
                                        <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                            @if (@$item->Resultado2 != null)
                                                {{ @$item->AnalizoInicial }}
                                            @else
                                                -------
                                            @endif
                                        </td>
                                        </tr>
                                        <tr>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;" height="25">
                                                pH muestra filtrada <sup>{{ $item->Simbologia }}</sup> <br> análisis color
                                            </td>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                APÉNDICE B NORMATIVO B.9 NOM-127-SSA1-2021
                                            </td>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">UpH</td>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                @if (@$item->Ph_muestra != null)
                                                    {{ number_format(@$item->Ph_muestra, 1, '.', '') }}
                                                @else
                                                    -------
                                                @endif

                                            </td>
                                            @if ($tipo == 1)
                                                <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                    N/A
                                                </td>
                                            @endif
                                            @if ($incerAux > 0)
                                                <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                    {{ $item->Incertidumbre }}</td>
                                            @endif
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                @if (@$item->Ph_muestra != null)
                                                    {{ @$item->AnalizoInicial }}
                                                @else
                                                    -------
                                                @endif
                                            </td>
                                        </tr>
                                    @break

                                    @case(102)
                                        @php
                                            $cod = DB::table('codigo_parametro')
                                                ->where('Id_codigo', $item->Id_codigo)
                                                ->first();
                                        @endphp
                                        <tr>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;" height="25">
                                                {{ @$item->Parametro }}<sup>{{ $item->Simbologia }}</sup> <br> Coeficiente de
                                                absorción <br>
                                                espectral 436 nm
                                            </td>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                {{ $item->Metodo }}
                                            </td>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">{{ @$item->Unidad }}
                                            </td>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                @if (@$item->Resultado != null)
                                                    {{ number_format($item->Resultado, 1) }}

                                                @else
                                                    -------
                                                @endif

                                            </td>
                                            @if ($tipo == 1)
                                                <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                    @if (@$item->Resultado != null)
                                                        7
                                                    @else
                                                        -------
                                                    @endif

                                                </td>
                                            @endif
                                            @if ($incerAux > 0)
                                                <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                    {{ $item->Incertidumbre }}</td>
                                            @endif
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                @if (@$item->Resultado != null)
                                                    {{ @$item->AnalizoInicial }}
                                                @else
                                                    -------
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;" height="25">
                                                {{ @$item->Parametro }}<sup>{{ $item->Simbologia }}</sup> <br> Coeficiente de
                                                absorción <br>
                                                espectral 525 nm
                                            </td>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                {{ $item->Metodo }}
                                            </td>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">{{ @$item->Unidad }}
                                            </td>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                @if (@$item->Resultado2 != null)
                                                  {{ number_format($item->Resultado2, 1) }}

                                                @else
                                                    -------
                                                @endif

                                            </td>
                                            @if ($tipo == 1)
                                                <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                    @if (@$item->Resultado2 != null)
                                                        5
                                                    @else
                                                        -------
                                                    @endif

                                                </td>
                                            @endif
                                            @if ($incerAux > 0)
                                                <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                    {{ $item->Incertidumbre }}</td>
                                            @endif
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                @if (@$item->Resultado2 != null)
                                                    {{ @$item->AnalizoInicial }}
                                                @else
                                                    -------
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;" height="25">
                                                {{ @$item->Parametro }}<sup>{{ $item->Simbologia }}</sup> <br> Coeficiente de
                                                absorción <br>
                                                espectral 620 nm
                                            </td>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                {{ $item->Metodo }}
                                            </td>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">{{ @$item->Unidad }}
                                            </td>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                @if (@$item->Resultado_aux != null)
                                                    {{ number_format($item->Resultado_aux, 1) }}

                                                @else
                                                    -------
                                                @endif

                                            </td>
                                            @if ($tipo == 1)
                                                <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                    @if (@$item->Resultado_aux != null)
                                                        3
                                                    @else
                                                        -------
                                                    @endif

                                                </td>
                                            @endif
                                            @if ($incerAux > 0)
                                                <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                    {{ $item->Incertidumbre }}</td>
                                            @endif
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                @if (@$item->Resultado_aux != null)
                                                    {{ @$item->AnalizoInicial }}
                                                @else
                                                    -------
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;" height="25">
                                                pH muestra filtrada <sup>{{ $item->Simbologia }}</sup> <br> análisis color
                                            </td>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                NMX-AA-008-SCFI-2016
                                            </td>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">UpH</td>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                @if (@$item->Ph_muestra != null)
                                                    {{ number_format(@$item->Ph_muestra, 1, '.', '') }}
                                                @else
                                                    -------
                                                @endif

                                            </td>
                                            @if ($tipo == 1)
                                                <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                    @if (@$item->Ph_muestra != null)
                                                        {{ number_format(@$item->Ph_muestra, 1, '.', '') }}
                                                    @else
                                                        -------
                                                    @endif

                                                </td>
                                            @endif
                                            @if ($incerAux > 0)
                                                <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                    {{ $item->Incertidumbre }}</td>
                                            @endif
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                @if (@$item->Ph_muestra != null)
                                                    {{ @$item->AnalizoInicial }}
                                                @else
                                                    -------
                                                @endif
                                            </td>
                                        </tr>
                                    @break

                                    @case(173)
                                    @break

                                    @default
                                        <tr>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;" height="25">
                                                {{ @$item->Parametro }}<sup>{{ $item->Simbologia }} </sup></td>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                {{ $item->Metodo }}
                                            </td>
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">{{ @$item->Unidad }}
                                            </td>
                                            <!-- aqui es donde se coloca el resultado final-->
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                @if ($item->Resultado2 !== null)
                                                    @if (is_numeric($item->Resultado2) && is_numeric($item->Limite))
                                                        @if ($item->Resultado2 < $item->Limite)
                                                        < {{ $item->Limite }} @elseif ($item->Resultado2 > $item->Limite)
                                                            {{ $item->Resultado2 }} @else {{ $item->Resultado2 }}
                                                                @endif
                                                            @else
                                                                {{ $item->Resultado2 }}
                                                        @endif
                                                    @else
                                                        {{ $item->Resultado2 }}
                                                    @endif

                                            </td>
                                            @if ($tipo == 1)
                                                <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                    @if (@$item->Resultado2 != null)
                                                        @switch($item->Id_parametro)
                                                            @case(64)
                                                                <!-- {{ @$limitesCon[$i] }} -->
                                                                {{ @$item->LimiteNorma }}
                                                            @break

                                                            @default
                                                                <!-- {{ @$limitesN[$i] }} -->
                                                                {{ @$item->LimiteNorma }}
                                                        @endswitch
                                                    @else
                                                        -------
                                                    @endif

                                                </td>
                                            @endif
                                            @if ($incerAux > 0)
                                                <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                    {{ $item->Incertidumbre }}</td>
                                            @endif
                                            <td class="tableContent bordesTablaBody" style="font-size: 8px;">
                                                @if (@$item->Resultado2 != null)
                                                    {{ @$item->AnalizoInicial }}
                                                @else
                                                    -------
                                                @endif
                                            </td>
                                        </tr>
                                    @endswitch

                                    @php $i++; @endphp
                                @endif
                            @endforeach

            </tbody>
 </table>
</div>
    @if ($datos2->count() >= 25 && $datos2->count() <= 27)
        <div style="page-break-after: always;"></div>
    @else
        {{-- No hacer nada --}}
    @endif

@endif


                @if ($datos2->contains('Id_parametro', 173))
                    <br>
                    <div id="contenedorTabla">
                        <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0"
                            cellspacing="0" border-color="#000000" width="100%">

                            <thead>
                                <tr>
                                    <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr"
                                        width="10.6%">PARAMETRO &nbsp;</td>
                                    <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr"
                                        width="10.6%"># DE TOMA &nbsp;</td>
                                    <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr"
                                        width="20.6%">
                                        &nbsp;METODO DE PRUEBA&nbsp;&nbsp;</td>
                                    <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr"
                                        width="10.6%">
                                        &nbsp;UNIDAD&nbsp;&nbsp;</td>
                                    <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr"
                                        width="10.6%" colspan="2">&nbsp;CONCENTRACION <br> CUANTIFICADA&nbsp;&nbsp;</td>
                                    <td style="font-size: 8px;" class="tableCabecera bordesTablaBody justificadoCentr"
                                        width="10.6%">
                                        ANALISTA</td>
                                </tr>
                            </thead>

                            <tbody>
                                @php $i = 0; @endphp
                                @foreach ($datos2->where('Id_parametro', 173) as $item)
                                    <tr>
                                        <td class="tableContent bordesTablaBody" style="font-size: 11px;" height="25"
                                            rowspan="6">Toxicidad
                                            aguda ( <i>Vibrio fischeri</i> ) <sup>{{ $item->Simbologia }} </sup></td>
                                        <td class="tableContent bordesTablaBody" style="font-size: 11px;" height="25"
                                            rowspan="6">
                                            {{ $item->Num_muestra }}</td>
                                        <td class="tableContent bordesTablaBody" style="font-size: 11px;" rowspan="6">
                                            {{ $item->Metodo }}
                                        </td>
                                        <td class="tableContent bordesTablaBody" style="font-size: 11px;" rowspan="3">CE50
                                            %</td>
                                        <td class="tableContent bordesTablaBody" style="font-size: 11px;"> 5 Min </td>
                                        <td class="tableContent bordesTablaBody" style="font-size: 11px;">
                                            {{ $item->Resultado ?? '-------' }}</td>

                                        <td class="tableContent bordesTablaBody" style="font-size: 11px;" rowspan="6">
                                            @if (@$item->Resultado2 != null)
                                                {{ @$item->AnalizoInicial }}
                                            @else
                                                -------
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="tableContent bordesTablaBody" style="font-size: 11px;">15 Min</td>
                                        <td class="tableContent bordesTablaBody" style="font-size: 11px;">
                                            {{ $item->Resultado2 }}</td>
                                    </tr>
                                    <tr>
                                        <td class="tableContent bordesTablaBody" style="font-size: 11px;">30 Min</td>
                                        <td class="tableContent bordesTablaBody" style="font-size: 11px;">
                                            {{ $item->Resultado_aux ?? '-------' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="tableContent bordesTablaBody" style="font-size: 11px;" rowspan="3">
                                            @if ($item->Ph_muestra == '1')
                                                %E
                                            @else
                                                {{ @$item->Unidad }}
                                            @endif
                                        </td>
                                        <td class="tableContent bordesTablaBody" style="font-size: 11px;">5 Min</td>
                                        <td class="tableContent bordesTablaBody" style="font-size: 11px;">
                                            {{ $item->Resultado_aux2 ?? '-------' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="tableContent bordesTablaBody" style="font-size: 11px;">15 Min</td>
                                        <td class="tableContent bordesTablaBody" style="font-size: 11px;">
                                            {{ $item->Resultado_aux3 ?? '-------' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="tableContent bordesTablaBody" style="font-size: 11px;">30 Min</td>
                                        <td class="tableContent bordesTablaBody" style="font-size: 11px;">
                                            {{ $item->Resultado_aux4 ?? '-------' }}</td>
                                    </tr>
                                    @php
                                        $i++;
                                    @endphp
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                @endif

                <footer>
                    <div id="contenedorTabla">
                        <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0"
                            cellspacing="0" border-color="#000000" width="100%">
                            <tbody>
                                <tr>
                                    <td class="nombreHeader nom fontSize11 justificadorIzq" style="font-size: 8px;margin:2px">
                                        @if ($datos3->Id_servicio != 3)
                                            @switch($datos3->Id_norma)
                                                @case(1)
                                                @case(27)
                                                    OBSERVACIONES: TEMPERATURA AMBIENTE PROMEDIO DE {{ @$tempAmbienteProm }} °C,
                                                    {{ @$datos3->Olor ? 'LA MUESTRA PRESENTA OLOR' : 'LA MUESTRA NO PRESENTA OLOR' }},
                                                    Y COLOR DE LA MUESTRA {{ $datos3->Color }},
                                                    EL MUESTREO FUE REALIZADO DE ACUERDO A LO ESTABLECIDO EN LA

                                                    @if ($datos3->Fecha_muestreo < '2025-03-21')
                                                        {{-- Solo mostrar esto si la fecha es anterior a 21 marzo 2025 --}}
                                                        @if (@$datos3->Proce_muestreo == 27)
                                                            PE-10-002-1
                                                        @else
                                                            NMX-AA-003-1980
                                                        @endif
                                                    @else
                                                        {{-- Si la fecha es igual o posterior, mostrar esto --}}
                                                        @if (@$datos3->Proce_muestreo == 27)
                                                            PE-10-002-1
                                                        @else
                                                            NMX-AA-003-1980 / NMX-AA-014-1980
                                                        @endif
                                                    @endif

                                                    Y DE ACUERDO A PROCEDIMIENTO
                                                    @if ($datos3->Proce_muestreo)
                                                        PE-10-002-{{ str_pad($datos3->Proce_muestreo, 2, '0', STR_PAD_LEFT) }}
                                                    @endif

                                                    {{ @$datos3->Observaciones }}
                                                @break

                                                @case(5)
                                                @case(7)

                                                @case(30)
                                                    @if ($datos->Id_servicio != 3)
                                                        OBSERVACIONES: TEMPERATURA AMBIENTE PROMEDIO DE {{ @$tempAmbienteProm }}°C,
                                                        @php if (@$datos3->Olor == true) {
                                                                echo 'LA MUESTRA PRESENTA OLOR';
                                                            } else {
                                                                echo "LA MUESTRA
                                NO
                                PRESENTA OLOR";
                                                        } @endphp
                                                        Y COLOR DE LA MUESTRA {{ $datos3->Color }} ,
                                                        EL MUESTREO FUE REALIZADO DE ACUERDO A LO ESTABLECIDO EN EL PROCEDIMIENTO

                                                        @if ($datos3->Proce_muestreo)
                                                            PE-10-002-{{ str_pad($datos3->Proce_muestreo, 2, '0', STR_PAD_LEFT) }}
                                                            <br>
                                                        @endif

                                                        @if ($datos3->Proce_muestreo2)
                                                            PEA-10-002-{{ $datos3->Proce_muestreo2 }} <br>
                                                        @endif
                                                        {{ @$datos3->Observaciones }}
                                                    @else
                                                        OBSERVACIONES: MUESTRA REMITIDA AL LABORATORIO POR EL CLIENTE, LOS RESULTADOS SE
                                                        APLICAN A
                                                        LA MUESTRA COMO SE RECIBIÓ
                                                    @endif
                                                @break

                                                @case (8)
                                                    OBSERVACIONES: TEMPERATURA AMBIENTE PROMEDIO DE {{ @$tempAmbienteProm }}°C,
                                                    @phpif (@$datos3->Olor == true) {
                                                            echo 'LA MUESTRA PRESENTA OLOR';
                                                        } else {
                                                            echo "LA MUESTRA
                                NO
                                PRESENTA OLOR";
                                                    } @endphp
                                                    Y COLOR DE LA MUESTRA {{ $datos3->Color }} ,
                                                    EL MUESTREO FUE REALIZADO DE ACUERDO A LO ESTABLECIDO EN EL PROCEDIMIENTO

                                                    @if ($datos3->Proce_muestreo)
                                                        PE-10-002-{{ str_pad($datos3->Proce_muestreo, 2, '0', STR_PAD_LEFT) }}
                                                    @endif

                                                    @if ($datos3->Proce_muestreo2)
                                                        PEA-10-002-{{ $datos3->Proce_muestreo2 }}
                                                    @endif <br>
                                                    {{ @$datos3->Observaciones }}
                                                @break

                                                @default
                                                    OBSERVACIONES: TEMPERATURA AMBIENTE PROMEDIO DE {{ @$tempAmbienteProm }}°C,
                                                    {{ @$datos3->Olor ? 'LA MUESTRA PRESENTA OLOR' : 'LA MUESTRA NO PRESENTA OLOR' }},
                                                    Y COLOR DE LA MUESTRA {{ @$datos3->Color }},
                                                    EL MUESTREO FUE REALIZADO DE ACUERDO A LO ESTABLECIDO EN LA


                                                    @if ($datos2->contains('Id_parametro', 173))
                                                        NMX-AA-003-1980 / NMX-AA-014-1980
                                                    @else
                                                        NMX-AA-003-1980
                                                    @endif



                                                    Y DE ACUERDO A PROCEDIMIENTO
                                                    @if ($datos3->Proce_muestreo)
                                                        PE-10-002-{{ str_pad($datos3->Proce_muestreo, 2, '0', STR_PAD_LEFT) }}
                                                    @endif

                                                    @if ($datos3->Proce_muestreo2)
                                                        PEA-10-002-{{ $datos3->Proce_muestreo2 }}
                                                    @endif
                                                    <br>
                                                    {{ @$datos3->Observaciones }}
                                            @endswitch
                                        @else
                                            <p> OBSERVACIONES: MUESTRA REMITIDA AL LABORATORIO POR EL CLIENTE, LOS RESULTADOS SE
                                                APLICAN
                                                A LA MUESTRA COMO SE RECIBIÓ. <br>
                                                @if (@$datos3->Obs_proceso != null || @$datos3->Obs_proceso != '' || @$datos3->Obs_proceso != 'NULL')
                                                    {{ $datos3->Obs_proceso }}
                                                    {{ @$datos3->Observaciones }}
                                                @endif
                                            </p>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="contenedorTabla">
                        <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0"
                            cellspacing="0" border-color="#000000" width="100%">
                            <tbody>
                                <tr>
                                    <td class="nombreHeader nom fontSize11 justificadorIzq"
                                        style="font-size: 8px;margin:4px;width: 50%">
                                        <br>
                                        <br>
                                        @if ($datos->Firma_superviso != '')
                                            <img style="width: 30%; height: auto;"
                                                src="data:image/gif;base64,{{ $datos->Firma_superviso }}">
                                            <center>
                                                <p style="font-size: 8px">&nbsp;{{ $firmaEncript1 }}&nbsp;</p>
                                            </center>
                                        @endif
                                        <br>
                                        <br>

                                    </td>
                                    <td class="nombreHeader nom fontSize11 justificadorIzq"
                                        style="font-size: 8px;margin:4px;width: 50%">
                                        <br>
                                        <br>
                                        @if ($datos->Firma_autorizo != '')
                                            <img style="width: 30%; height: auto;"
                                                src="data:image/gif;base64,{{ $datos->Firma_autorizo }}">
                                            <center>
                                                <p style="font-size: 8px">&nbsp;{{ $firmaEncript2 }}&nbsp;</p>
                                            </center>
                                        @endif
                                        <br>
                                        <br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="nombreHeader nom fontSize11 justificadorIzq" style="font-size: 8px;margin:2px">
                                        <center><span class="cabeceraStdMuestra"> REVISÓ SIGNATARIO <br> </span></center>
                                        <center><span class="bodyStdMuestra"> {{ $firma1->name }} {{-- {{@$usuario->name}} --}}
                                            </span></center>
                                    </td>
                                    <td class="nombreHeader nom fontSize11 justificadorIzq" style="font-size: 8px;margin:2px">
                                        <center><span class="cabeceraStdMuestra"> AUTORIZÓ SIGNATARIO <br> </span></center>
                                        <center><span class="bodyStdMuestra"> {{ $firma2->name }} {{-- {{@$usuario->name}} --}}
                                            </span></center>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="contenedorTabla">
                        <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0"
                            cellspacing="0" border-color="#000000" width="100%">
                            <tbody>
                                <tr>
                                    <td class="nombreHeaders fontBold justificadorIzq" style="font-size: 3.9px; width: 50%;">
                                        <p>
                                            @php
                                                echo $impresion[0]->Nota;
                                            @endphp
                                        </p>

                                        @switch(@$datos->Id_norma)
                                            @case(27)
                                                @php
                                                    echo @$impresion[0]->Nota_siralab;
                                                @endphp
                                            @break

                                            @case(7)
                                                <p>
                                                    @php
                                                        echo "LOS PARÁMETROS DE NITRATOS, SAAM, Y CIANUROS SE ANALIZARÁN BAJO ESPECIFICACIÓN
                                PARA AGUA DE USO Y CONSUMO HUMANO.";
                                                    @endphp
                                                </p>
                                            @break

                                            @default
                                        @endswitch
                                        @if ($datos->Nota_4 == 1)
                                            4 PARAMETRO NO ACREDITADO
                                        @endif
                                        <br>
                                        @if ($tipo == 1)
                                            A SOLICITUD DEL CLIENTE SE COMPARA EL INFORME DE RESULTADOS CON LOS LIMITES
                                            PERMISIBLES DE
                                            LA NORMA
                                        @endif

                                    </td>
                                    <td style="width: 15%">
                                        @php
                                            $url =
                                                'http://sistemasofia.ddns.net:86/sofia/clientes/informe-de-resultados-acama/' .
                                                @$folioEncript;
                                            $qr_code =
                                                'data:image/png;base64,' .
                                                \DNS2D::getBarcodePNG((string) $url, 'QRCODE');
                                        @endphp

                                        <center><img style="width: 5%; height: 5%;" src="{{ @$qr_code }}"
                                                alt="qrcode" /> <br> <span class="fontSize9 fontBold">&nbsp;&nbsp;&nbsp;
                                                {{ @$datos->Folio_servicio }}</span>
                                        </center>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="contenedorTabla">
                        @php
                            $temp = [];
                            $sw = false;
                        @endphp

                        @if (@$datos->Num_tomas > 1)

                            <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0"
                                cellspacing="0" border-color="#000000" width="100%">
                                <tbody>
                                    <tr>
                                        <td></td>
                                    </tr>

                                    @foreach ($datos2 as $item)
                                        @php
                                            $num =
                                                @$numTomas && $numTomas->count()
                                                    ? $numTomas->count()
                                                    : $datos->Num_tomas;
                                        @endphp

                                        @switch($item->Id_simbologia)
                                            @case(9)
                                                {{-- No mostrar nada --}}
                                            @break

                                            @case(11)
                                                <tr>
                                                    <td style="font-size: 3.9px" class="fontBold justificadorIzq">
                                                        1++ MEDIA GEOMETRICA DE LAS {{ $num }} MUESTRAS SIMPLES DE
                                                        ESCHERICHIA COLI.
                                                    </td>
                                                </tr>
                                            @break

                                            @case(5)
                                                <tr>
                                                    <td style="font-size: 3.9px" class="fontBold justificadorIzq">
                                                        1# PROMEDIO PONDERADO DE LAS {{ $num }} MUESTRAS SIMPLES DE GRASAS Y
                                                        ACEITES
                                                    </td>
                                                </tr>
                                            @break

                                            @case(4)
                                                <tr>
                                                    <td style="font-size: 3.9px" class="fontBold justificadorIzq">
                                                        1+ MEDIA GEOMETRICA DE LAS {{ $num }} MUESTRAS SIMPLES DE COLIFORMES.
                                                        EL VALOR MINIMO CUANTIFICADO REPORTADO SERA DE 3, COMO CRITERIO CALCULADO PARA
                                                        COLIFORMES EN SIRALAB Y EL LABORATORIO.
                                                    </td>
                                                </tr>
                                            @break

                                            @case(12)
                                                <tr>
                                                    <td style="font-size: 3.9px" class="fontBold justificadorIzq">
                                                        1+++ MEDIA GEOMETRICA DE LAS {{ $num }} MUESTRAS SIMPLES DE
                                                        ENTEROCOCOS FECALES.
                                                    </td>
                                                </tr>
                                            @break

                                            @default
                                                @if ($item->Id_parametro == 97)
                                                    @if ($datos->Num_tomas > 1)
                                                        <tr>
                                                            <td style="font-size: 3.9px" class="fontBold justificadorIzq">
                                                                {{ $item->Simbologia }} {{ $item->Descripcion }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @else
                                                    @if (!empty($item->Descripcion))
                                                        <tr>
                                                            <td style="font-size: 3.5px" class="fontBold justificadorIzq">
                                                                {{ $item->Simbologia }} {{ $item->Descripcion }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endif
                                        @endswitch
                                    @endforeach

                                </tbody>
                            </table>
                        @else
                            <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0"
                                cellspacing="0" border-color="#000000" width="100%">
                                <tbody>
                                    <tr>
                                        <td></td>
                                    </tr>
                                    @php
                                        $auxTemp = '';
                                    @endphp
                                    @foreach ($datos2 as $item)
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
                                                        $auxTemp .= $item->Simbologia . ' , ';
                                                    @endphp
                                                    @php
                                                        array_push($temp, $item->Id_simbologia_info);
                                                    @endphp
                                                @break

                                                @case(5)
                                                    @php
                                                        $auxTemp .= $item->Simbologia . ' , ';
                                                    @endphp
                                                    @php
                                                        array_push($temp, $item->Id_simbologia_info);
                                                    @endphp
                                                @break

                                                @case(4)
                                                    @php
                                                        $auxTemp .= $item->Simbologia . ' , ';
                                                    @endphp
                                                    @php
                                                        array_push($temp, $item->Id_simbologia_info);
                                                    @endphp
                                                @break

                                                @case(12)
                                                    @php
                                                        $auxTemp .= $item->Simbologia . ' , ';
                                                    @endphp
                                                    @php
                                                        array_push($temp, $item->Id_simbologia_info);
                                                    @endphp
                                                @break

                                                @default
                                                    @switch($item->Id_parametro)
                                                        @case(2)
                                                        @case(97)
                                                            @php
                                                                $auxTemp .= $item->Simbologia . ' , ';
                                                            @endphp
                                                            @php
                                                                array_push($temp, $item->Id_simbologia_info);
                                                            @endphp
                                                        @break

                                                        @case(358)
                                                            {{-- <tr>
                                    <td style="font-size: 3.9px" class="fontBold justificadorIzq">{{$item->Simbologia_inf}} @php
                                        echo $item->Descripcion; @endphp</td>
                                </tr> --}}
                                                        @break

                                                        @case(11)
                                                            <tr>
                                                                <td style="font-size: 5.9px" class="fontBold justificadorIzq">
                                                                    {{ $item->Simbologia_inf }} @php
                                                                    echo $item->Descripcion; @endphp</td>
                                                            </tr>
                                                            @php
                                                                array_push($temp, $item->Id_simbologia_info);
                                                            @endphp
                                                        @break

                                                        @default
                                                            @php
                                                                array_push($temp, $item->Id_simbologia_info);
                                                            @endphp
                                                    @endswitch
                                            @endswitch
                                        @endif
                                        @php
                                            $sw = false;
                                        @endphp
                                    @endforeach
                                    @if ($auxTemp != '')
                                        <tr>
                                            <td style="font-size: 3.9px" class="fontBold justificadorIzq">
                                                {{ $auxTemp }} RESULTADO DE LA
                                                MUESTRA SIMPLE. </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        @endif
                    </div>
                </footer>


        </body>
