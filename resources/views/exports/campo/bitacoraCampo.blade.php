<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('/public/css/pdf/style.css')}}">

    <title>Bitácora de Campo </title>
</head>

<body>
    <div class="container" id="pag">
        <div class="row">

            <div class="col-md-12" style="border:1px solid">
            </div>

            <div class="col-md-12">
                <table class="table table-borderless table-sm" width="100%">
                    <tr>
                        <td class="fontNormal justificadorCentr fontSize10 fontCalibri" width="35%">Se realiza la
                            Calibración Analitica Interna del equipo</td>
                        <td class="fontBold justificadorCentr fontSize10 fontCalibri" width="45%">MEDIDOR DE PH,
                            TEMPERATURA Y CONDUCTIVIDAD</td>

                        <td class="fontBold justificadorCentr fontSize10 fontCalibri" width="10%">
                            {{@$termometro1->Modelo}}
                        </td>
                        <td class="fontBold justificadorCentr fontSize10 fontCalibri" width="10%">
                            {{@$termometro1->Marca}}
                        </td>

                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="fontBold justificadorCentr fontSize10 fontCalibri" width="10%">
                            {{@$termometro2->Modelo}}
                        </td>
                        <td class="fontBold justificadorCentr fontSize10 fontCalibri" width="10%">
                            {{@$termometro2->Marca}}
                        </td>
                    </tr>
                    <tr>
                        <td class="fontNormal justificadorIzq fontSize10 fontCalibri">Con No. serie:
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="fontBold">{{@$campoGen->Serie}}</span></td>
                        <td class="fontNormal justificadorIzq fontSize10 fontCalibri">a una temperatura de
                            <span class="fontBold">
                                {{round(@$campoGen->Temperatura_a, 0)}}°C.
                            </span>
                        </td>
                        <td class="fontNormal justificadorIzq fontSize10 fontCalibri">&nbsp;</td>
                        <td class="fontBold justificadorIzq fontSize10 fontCalibri">&nbsp;</td>
                    </tr>
                </table>
            </div>

            <div class="col-md-12">
                <table class="table table-borderless">
                    <tr>
                        <td>
                            <table class="table border table-sm">
                                <tr>
                                    <td colspan="7" class="fontCalibri fontBold fontSize12">
                                        <center>pH (Trazable)</center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fontCalibri fontBold fontSize12 justificadorCentr">pH</td>
                                    <td class="fontCalibri fontBold fontSize12 justificadorCentr">Marca</td>
                                    <td class="fontCalibri fontBold fontSize12 justificadorCentr">No. Lote</td>
                                    <td class="fontCalibri fontBold fontSize12 justificadorCentr">1° Lectura</td>
                                    <td class="fontCalibri fontBold fontSize12 justificadorCentr">2° Lectura</td>
                                    <td class="fontCalibri fontBold fontSize12 justificadorCentr">3° Lectura</td>
                                    <td class="fontCalibri fontBold fontSize8 justificadorCentr">+-0.05 unidades de pH y
                                        0.03 entre lecturas</td>
                                </tr>
                                @foreach (@$phTrazable as $item)
                                <tr>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">{{$item->Ph}}</td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">{{$item->Marca}}
                                    </td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">{{$item->Lote}}</td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">
                                        {{number_format($item->Lectura1, 2, ".", ",")}}
                                    </td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">
                                        {{number_format($item->Lectura2, 2, ".", ",")}}
                                    </td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">
                                        {{number_format($item->Lectura3, 2, ".", ",")}}
                                    </td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">{{$item->Estado}}
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </td>
                        <td>
                            <table class="table border table-sm">
                                <tr>
                                    <td colspan="7" class="fontCalibri fontBold fontSize12">
                                        <center>pH (Control de Calidad)</center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fontCalibri fontBold fontSize12 justificadorCentr">pH</td>
                                    <td class="fontCalibri fontBold fontSize12 justificadorCentr">Marca</td>
                                    <td class="fontCalibri fontBold fontSize12 justificadorCentr">No. Lote</td>
                                    <td class="fontCalibri fontBold fontSize12 justificadorCentr">1° Lectura</td>
                                    <td class="fontCalibri fontBold fontSize12 justificadorCentr">2° Lectura</td>
                                    <td class="fontCalibri fontBold fontSize12 justificadorCentr">3° Lectura</td>
                                    <td class="fontCalibri fontBold fontSize8 justificadorCentr">+-2% de aceptación</td>
                                </tr>
                                @foreach (@$phCalidad as $item)
                                <tr>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">
                                        {{$item->Ph_calidad}}</td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">{{$item->Marca}}
                                    </td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">{{$item->Lote}}</td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">
                                        {{number_format($item->Lectura1, 2, ".", ",")}}
                                    </td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">
                                        {{number_format($item->Lectura2, 2, ".", ",")}}
                                    </td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">
                                        {{number_format($item->Lectura3, 2, ".", ",")}}
                                    </td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">{{$item->Estado}}
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="col-md-12">
                <table class="table table-borderless">
                    <tr>
                        <td>
                            <table class="table border table-sm">
                                <tr>
                                    <td colspan="7" class="fontCalibri fontBold fontSize12">
                                        <center>CONDUCTIVIDAD (Trazable)</center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fontCalibri fontBold fontSize8 justificadorCentr">Cloruro de
                                        potasio
                                        (μS/cm)</td>
                                    <td class="fontCalibri fontBold fontSize12 justificadorCentr">Marca</td>
                                    <td class="fontCalibri fontBold fontSize12 justificadorCentr">No. Lote</td>
                                    <td class="fontCalibri fontBold fontSize12 justificadorCentr">1° Lectura</td>
                                    <td class="fontCalibri fontBold fontSize12 justificadorCentr">2° Lectura</td>
                                    <td class="fontCalibri fontBold fontSize12 justificadorCentr">3° Lectura</td>
                                    <td class="fontCalibri fontBold fontSize8 justificadorCentr">
                                        @if (@$tempCon[0]->Inicio_rango != NULL)
                                            Comparacion contra Incertidumbre
                                        @else
                                            +- 5% de
                                            aceptación valor
                                            nominal
                                        @endif
                                    </td>
                                </tr>
                                @foreach (@$campoConTrazable as $item)
                                <tr>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">{{@$tempCon[0]->Conductividad}}</td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">{{$tempCon[0]->Marca}}
                                    </td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">{{$tempCon[0]->Lote}}</td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">{{$item->Lectura1}}
                                    </td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">{{$item->Lectura2}}
                                    </td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">{{$item->Lectura3}}
                                    </td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">{{$item->Estado}}
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </td>
                        <td>
                            <table class="table border table-sm">
                                <tr>
                                    <td colspan="7" class="fontCalibri fontBold fontSize12">
                                        <center>CONDUCTIVIDAD (Control de Calidad)</center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fontCalibri fontBold fontSize8 justificadorCentr">Cloruro de
                                        potasio
                                        (μS/cm)</td>
                                    <td class="fontCalibri fontBold fontSize12 justificadorCentr">Marca</td>
                                    <td class="fontCalibri fontBold fontSize12 justificadorCentr">No. Lote</td>
                                    <td class="fontCalibri fontBold fontSize12 justificadorCentr">1° Lectura</td>
                                    <td class="fontCalibri fontBold fontSize12 justificadorCentr">2° Lectura</td>
                                    <td class="fontCalibri fontBold fontSize12 justificadorCentr">3° Lectura</td>
                                    <td class="fontCalibri fontBold fontSize8 justificadorCentr">
                                        @if (@$tempCon[0]->Inicio_rango != NULL)
                                            Comparacion contra Incertidumbre
                                        @else
                                            +- 5% de
                                            aceptación valor
                                            nominal
                                        @endif
                                    </td>
                                </tr>
                                @foreach (@$campoConCalidad as $item)
                                <tr>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">
                                        {{$tempConCal[0]->Conductividad}}</td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">{{$tempConCal[0]->Marca}}
                                    </td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">{{$tempConCal[0]->Lote}}</td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">{{$item->Lectura1}}
                                    </td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">{{$item->Lectura2}}
                                    </td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">{{$item->Lectura3}}
                                    </td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">{{$item->Estado}}
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="col-12 fontBold fontCalibri fontSize12">
                Empresa
            </div>

            <div class="col-12 fontCalibri fontSize12 fontNormal">
                NOMBRE DE LA EMPRESA: <span class="fontBold">{{@$model->Empresa_suc}}</span>
            </div>

            <div class="col-12 fontCalibri fontSize12 fontNormal">
                PUNTO DE MUESTREO: <span class="fontBold">
                    {{@$punto->Punto}} {{-- <br> --}}
                </span>
            </div>
            @if (@$idNorma != 1)
            <div class="col-12 fontCalibri fontSize12 fontNormal">

            </div>

            @else
            <div class="col-12 fontCalibri fontSize12 fontNormal">
                TIPO DE REPORTE: <span class="fontBold">{{@$tipoReporte->Categoria}}</span>
            </div>
            @endif


            <br>

            <div class="col-md-12">
                <table class="table table-borderless table-sm" style="border:none" width="100%">
                    <tr>
                        <td class="fontBold fontCalibri fontSize12" width="25%">Muestreo</td>
                        <td width="25%">&nbsp;</td>
                        <td width="25%">&nbsp;</td>
                        <td width="25%">&nbsp;</td>
                    </tr>

                    <tr>
                        <td class="fontNormal fontCalibri fontSize12">NORMA A MUESTREAR</td>
                        <td class="fontBold fontCalibri fontSize12">{{@$model->Clave_norma}}</td>
                        <td class="fontNormal fontCalibri fontSize12">TIPO DE MUESTRA</td>
                        <td class="fontBold fontCalibri fontSize12">{{@$model->Descarga}}</td>
                    </tr>

                    <tr>
                        <td class="fontNormal fontCalibri fontSize12">TIPO DE MUESTREO</td>
                        <td class="fontBold fontCalibri fontSize12">{{@$model->Id_muestra}}
                            ({{@$frecuenciaMuestreo->Frecuencia_muestreo}})</td>
                        <td class="fontNormal fontCalibri fontSize12">NUMERO DE MUESTRAS</td>
                        <td class="fontBold fontCalibri fontSize12">{{@$model->Num_tomas}}</td>
                    </tr>

                    <tr>
                        <td class="fontNormal fontCalibri fontSize12">MATERIAL USADO EN EL MUESTREO</td>
                        <td class="fontNormal fontCalibri fontSize10">Ver plan de muestreo RE-11-005</td>
                        {{-- <td class="fontBold fontCalibri fontSize12">{{$tipoReporte->Categoria}}</td> --}}

                    </tr>

                </table>
            </div>

            <div class="contenidoCurva">
                <span class="fontBold fontCalibri fontSize13">MEDIO AMBIENTE DURANTE EL MUESTREO</span>

                <table autosize="1" style="width: 20%" cellpadding="2" cellspacing="0" border-color="#000000">
                    <thead>
                        <tr>
                            <th class="fontBold fontCalibri fontSize9 bordesTablaBody justificadorCentr">No. Muestra
                            </th>
                            <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">temperatura °C</th>
                        </tr>
                    </thead>

                    <tbody>
                        @for ($i = 0; $i < @$model->Num_tomas; $i++)
                            <tr>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">{{$i +
                                    1}}</td>
                                <td class="fontNormal fontCalibri fontSize9 bordeFinal justificadorCentr">
                                    {{number_format(@$tempAmbiente[$i]->Temperatura1, 1, ".", ",")}}
                                </td>
                            </tr>
                            @endfor
                    </tbody>
                </table>
            </div>
            <br>
            <br>
            <div class="contenedorPadre12">
                <div class="contenedorHijo121">
                    <span class="fontBold fontCalibri fontSize12">Temperatura del agua {{@$campoGen->Modelo}}</span>

                    <table autosize="1" style="width: 95%" cellpadding="2" cellspacing="0" border-color="#000000">
                        <thead>
                            <tr>
                                <th class="fontBold fontCalibri fontSize9 bordesTablaBody justificadorCentr">°C</th>
                                <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Error</th>
                                <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Factor de
                                    correción</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($factorCorreccion as $item)
                            <tr>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">
                                    {{@$item->De_c}} - {{@$item->A_c}}</td>
                                <td class="fontNormal fontCalibri fontSize9 bordeFinal justificadorCentr">
                                    {{@$item->Factor}}</td>
                                <td class="fontNormal fontCalibri fontSize9 bordeFinal justificadorCentr">
                                    {{@$item->Factor_aplicado}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="contenedorHijo122">
                    <span class="fontBold fontCalibri fontSize12">Ejemplo de aplicación del factor de corrección</span>

                    <table autosize="1" style="width: 100%" cellpadding="2" cellspacing="0" border-color="#000000">
                        <thead>
                            <tr>
                                <th class="fontBold fontCalibri fontSize9 bordesTablaBody justificadorCentr">No. Muestra
                                </th>
                                <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">temperatura °C
                                </th>
                                <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">+/-</th>
                                <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Error</th>
                                <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Factor de
                                    correción</th>
                                <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">temperatura
                                    ajustada</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $aux = 0;
                            @endphp
                            @foreach ($tempMuestra as $item)
                            <tr>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">
                                    {{$item->Num_toma}}</td>
                                <td class="fontNormal fontCalibri fontSize9 bordeFinal justificadorCentr" colspan="2">
                                    @if ($item->Activo != 0)
                                        @php
                                        echo number_format((@$item->TemperaturaSin1 + @$item->TemperaturaSin2 + @$item->TemperaturaSin3) / 3, 1, ".", ".");
                                        @endphp
                                    @else
                                        ----
                                    @endif
                                </td>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">
                                    @if ($item->Activo != 0)
                                        {{@$factCorrec[$aux]}}
                                    @else
                                        ----
                                    @endif
                                </td>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">
                                    @if ($item->Activo != 0)
                                        {{@$factApl[$aux]}}
                                    @else
                                        ----
                                    @endif
                                </td>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">
                                    @if ($item->Activo != 0)
                                        @php
                                        echo number_format(@$item->Promedio, 1, ".", ".");
                                        @endphp
                                    @else
                                        ----
                                    @endif
                                </td>
                                @php
                                $aux++;
                                @endphp
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <br>

            <div class="contenedorPadre12">
                <div class="contenedorHijo121">
                    <span class="fontBold fontCalibri fontSize12">Temperatura ambiente {{@$campoGen->Modelo2}}</span>

                    <table autosize="1" style="width: 95%" cellpadding="2" cellspacing="0" border-color="#000000">
                        <thead>
                            <tr> 
                                <th class="fontBold fontCalibri fontSize9 bordesTablaBody justificadorCentr">°C</th>
                                <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Error</th>
                                <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Factor de
                                    correción</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($factorCorreccion2 as $item)
                            <tr>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">
                                    {{@$item->De_c}} - {{@$item->A_c}}</td>
                                <td class="fontNormal fontCalibri fontSize9 bordeFinal justificadorCentr">
                                    {{@$item->Factor}}</td>
                                <td class="fontNormal fontCalibri fontSize9 bordeFinal justificadorCentr">
                                    {{@$item->Factor_aplicado}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="contenedorHijo122">
                    <span class="fontBold fontCalibri fontSize12">Ejemplo de aplicación del factor de corrección</span>

                    <table autosize="1" style="width: 100%" cellpadding="2" cellspacing="0" border-color="#000000">
                        <thead>
                            <tr>
                                <th class="fontBold fontCalibri fontSize9 bordesTablaBody justificadorCentr">No. Muestra
                                </th>
                                <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">temperatura °C
                                </th>
                                <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">+/-</th>
                                <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Error</th>
                                <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Factor de
                                    correción</th>
                                <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">temperatura
                                    ajustada</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                            $aux = 0;
                            @endphp
                            @foreach ($tempAmbiente as $item)
                            <tr>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">
                                    {{$item->Num_toma}}</td>
                                <td class="fontNormal fontCalibri fontSize9 bordeFinal justificadorCentr" colspan="2">
                                    @php
                                        echo number_format(@$item->TemperaturaSin1, 1, ".", ".");
                                    @endphp
                                </td>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">
                                    {{@$factCorrec2[$aux]}}
                                </td>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">
                                    {{@$factApl2[$aux]}}
                                </td>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">
                                    @php
                                    echo number_format((@$item->Temperatura1 - @$factoApl2[$aux]), 1, ".", ".");
                                    @endphp
                                </td>
                                @php
                                $aux++;
                                @endphp
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
<br>
            <div class="col-12 fontCalibri fontSize10 fontNormal">
                Nota. Los demas valores registrados de temperatura llevan aplicado la temperatura corregida de la
                temperatura correspondiente. <br>
                La medición de la temperatura del agua es realizada con el sensor de temperatura
                del equipo PC-100 con No. de serie
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <span class="fontBold">{{$campoGen->Serie}}</span>
            </div>

            <div class="col-12 fontCalibri fontSize12 fontBold">
                Datos de Campo
            </div>

            {{-- <br> --}}

            <div class="contenidoTabla">
                <table autosize="1" style="width: 100%" cellpadding="2" cellspacing="0" border-color="#000000">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th class="fontBold fontCalibri fontSize9 sinBorde justificadorCentr">MUESTRA COMPUESTA</th>
                        </tr>

                        <tr>
                            <th class="fontBold fontCalibri fontSize9 bordesTablaBody justificadorCentr">No. Muestra
                            </th>
                            <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Materia Flotante
                                Aus/Pres</th>
                            <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Color</th>
                            <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Olor (Si/No)</th>
                            <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">pH (unidad de pH)
                            </th>
                            <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">pH (unidad de pH)
                            </th>
                            <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">pH (unidad de pH)
                            </th>
                            <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Promedio pH (unidad
                                de pH)</th>
                            <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Temperatura °C</th>
                            <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Temperatura °C</th>
                            <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Temperatura °C</th>
                            <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Promedio temp. °C
                            </th>
                            <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Conductividad
                                (µS/cm)</th>
                            <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Conductividad
                                (µS/cm)</th>
                            <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Conductividad
                                (µS/cm)</th>
                            <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Promedio Conduc.
                                µS/cm (+-5%)</th>
                            <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Gasto (L/s)</th>
                            <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Gasto (L/s)</th>
                            <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Gasto (L/s)</th>
                            <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Promedio Gasto(L/s)
                            </th>
                            <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Litros a tomar
                                muestra compuesta</th>
                        </tr>
                    </thead>

                    <tbody>
                        @for ($i = 0; $i < @$model->Num_tomas; $i++)
                            <tr>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">{{@$i+1}}
                                </td>
                                <td class="fontNormal fontCalibri fontSize9 bordeFinal justificadorCentr">
                                    @if (@$phMuestra[$i]->Activo == 0 || @$phMuestra[$i]->Materia == "0")
                                    ----
                                    @else
                                    @switch($model->Id_norma)
                                    @case(5)
                                    @case(30)
                                    @if ($materia->count())
                                    {{@$phMuestra[$i]->Materia}}
                                    @else
                                    ----
                                    @endif
                                    @break
                                    @default
                                    @if ($materia->count())
                                    {{@$phMuestra[$i]->Materia}}
                                    @else
                                    ----
                                    @endif

                                    @endswitch
                                    @endif
                                </td>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">
                                    @if (@$phMuestra[$i]->Activo == 0 || @$phMuestra[$i]->Color == "0")
                                    -----
                                    @else
                                    {{@$phMuestra[$i]->Color}}
                                    @endif
                                </td>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">
                                    @if (@$phMuestra[$i]->Activo == 0 || @$phMuestra[$i]->Olor == "0")
                                    -----
                                    @else
                                    {{@$phMuestra[$i]->Olor}}
                                    @endif
                                </td>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">
                                    @if (@$phMuestra[$i]->Activo == 0 || @$phMuestra[$i]->Ph1 == 0)
                                    -----
                                    @else
                                    {{number_format(@$phMuestra[$i]->Ph1, 2)}}
                                    @endif
                                </td>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">
                                    @if (@$phMuestra[$i]->Activo == 0 || @$phMuestra[$i]->Ph2 == 0)
                                    -----
                                    @else
                                    {{number_format(@$phMuestra[$i]->Ph2, 2)}}
                                    @endif
                                </td>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">
                                    @if (@$phMuestra[$i]->Activo == 0 || @$phMuestra[$i]->Ph3 == 0)
                                    -----
                                    @else
                                    {{number_format(@$phMuestra[$i]->Ph3, 2)}}
                                    @endif
                                </td>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">
                                    @if (@$phMuestra[$i]->Activo == 0 || @$phMuestra[$i]->Promedio == 0)
                                    -----
                                    @else
                                    {{number_format(@$phMuestra[$i]->Promedio, 2)}}
                                    @endif
                                </td>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">
                                    @if (@$phMuestra[$i]->Activo == 0 || @$tempMuestra[$i]->Temperatura1 == 0 || @$tempMuestra[$i]->TemperaturaSin1 == 0)
                                    -----
                                    @else
                                    {{number_format(@$tempMuestra[$i]->Temperatura1, 1)}}
                                    @endif
                                </td>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">
                                    @if (@$phMuestra[$i]->Activo == 0 || @$tempMuestra[$i]->Temperatura2 == 0 || @$tempMuestra[$i]->TemperaturaSin2 == 0)
                                    -----
                                    @else
                                    {{number_format(@$tempMuestra[$i]->Temperatura2, 1)}}
                                    @endif
                                </td>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">
                                    @if (@$phMuestra[$i]->Activo == 0 || @$tempMuestra[$i]->Temperatura3 == 0 || @$tempMuestra[$i]->TemperaturaSin3 == 0)
                                    -----
                                    @else
                                    {{number_format(@$tempMuestra[$i]->Temperatura3, 1)}}
                                    @endif
                                </td>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">

                                    @if (@$phMuestra[$i]->Activo == 0 || @$tempMuestra[$i]->Promedio == 0 ||  @$tempMuestra[$i]->TemperaturaSin3 == 0)
                                    -----
                                    @else
                                    {{round(@$tempMuestra[$i]->Promedio, 0)}}
                                    @endif

                                </td>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">
                                    @if (@$phMuestra[$i]->Activo == 0 || @$conMuestra[$i]->Conductividad1 == 0)
                                    -----
                                    @else
                                    {{round(@$conMuestra[$i]->Conductividad1, 2)}}
                                    @endif
                                </td>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">
                                    @if (@$phMuestra[$i]->Activo == 0 || @$conMuestra[$i]->Conductividad2 == 0)
                                    -----
                                    @else
                                    {{round(@$conMuestra[$i]->Conductividad2, 2)}}
                                    @endif
                                </td>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">
                                    @if (@$phMuestra[$i]->Activo == 0 || @$conMuestra[$i]->Conductividad3 == 0)
                                    -----
                                    @else
                                    {{round(@$conMuestra[$i]->Conductividad3, 2)}}
                                    @endif
                                </td>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">
                                    @if (@$phMuestra[$i]->Activo == 0 || @$conMuestra[$i]->Promedio == 0)
                                    -----
                                    @else
                                    {{round(@$conMuestra[$i]->Promedio, 2)}}
                                    @endif
                                </td>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">
                                    @if (@$phMuestra[$i]->Activo == 0 || @$gastoMuestra[$i]->Gasto1 == 0)
                                    -----
                                    @else
                                    {{round(@$gastoMuestra[$i]->Gasto1, 2)}}
                                    @endif
                                </td>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">
                                    @if (@$phMuestra[$i]->Activo == 0 || @$gastoMuestra[$i]->Gasto2 == 0)
                                    -----
                                    @else
                                    {{round(@$gastoMuestra[$i]->Gasto2, 2)}}
                                    @endif
                                </td>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">
                                    @if (@$phMuestra[$i]->Activo == 0 || @$gastoMuestra[$i]->Gasto3 == 0)
                                    -----
                                    @else
                                    {{round(@$gastoMuestra[$i]->Gasto3, 2)}}
                                    @endif
                                </td>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">
                                    @if (@$phMuestra[$i]->Activo == 0 || @$gastoMuestra[$i]->Promedio == 0)
                                    -----
                                    @else
                                    {{round(@$gastoMuestra[$i]->Promedio, 2)}}
                                    @endif
                                </td>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">
                                    {{-- @if (@$phMuestra[$i]->Activo == 0 || @$gastoMuestra[$i]->Promedo == 0)
                                    -----
                                    @else
                                    @if (@$gastoMuestra[$i]->Promedio === NULL)
                                    0
                                    @else
                                    @php
                                    echo round((round($gastoMuestra[$i]->Promedio / $gastoTotal, 4)) *
                                    $campoCompuesto->Volumen_calculado, 4);
                                    @endphp
                                    @endif
                                    @endif
                                    --}}
                                    @php
                                    $auxGasto = 0;
                                    @endphp
                                    @if (@$gastoMuestra[$i]->Promedio === NULL || @$gastoMuestra[$i]->Promedio === 0)
                                    -----
                                    @else
                                    @php
                                    $auxGasto = (@$gastoMuestra[$i]->Promedio / $gastoTotal) *
                                    $campoCompuesto->Volumen_calculado;
                                    @endphp
                                    {{number_format($auxGasto, 2, ".", ".")}}
                                    @endif
                                </td>
                            </tr>
                            @endfor

                            <tr>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th class="fontBold fontCalibri fontSize9 sinBorde justificadorCentr" colspan="2">Gasto
                                    Total {{@$gastoTotal}} L/s</th>
                                <th>&nbsp;</th>
                            </tr>
                    </tbody>
                </table>
            </div>
           

            @if (empty($Vidrio))

@else
    <!-- Aqui va si hay  -->
    <div class="contenidoTabla">
     <table style="width: 30%; border: 1px solid #000000; border-collapse: collapse;" cellpadding="2" cellspacing="0">
    <thead>
    <tr>
    <td colspan="2" class="fontCalibri fontBold fontSize12">
                                        <center>    Vibrio Fischeri </center>
                                    </td>
                                </tr>
        <tr>
            <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Oxigeno Disuelto</th>
            <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Burbujas/Espumas</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($Vidrio as $item)
        <tr>
            <td class="fontNormal fontCalibri fontSize9 bordeFinal justificadorCentr">
                {{ $item->Oxigeno }}
            </td>
            <td class="fontNormal fontCalibri fontSize9 bordeFinal justificadorCentr">
               {{ $item->Burbujas == 1 ? 'Sí' : 'No' }}
            </td>

        </tr>
    @endforeach
</tbody>

</table>
      
    </div>
    <br>
@endif

            

            {{-- <br> --}}

            <div class="col-12 fontCalibri fontSize12 fontNormal">
                <table autosize="1" style="width: 100%" cellpadding="2" cellspacing="0">
                    <tbody>
                        {{-- <tr>
                            <td class="fontNormal fontCalibri fontSize12" width="40%">Metodo de aforo</td>
                            <td class="fontCalibri fontSize12 fontBold" width="60%">{{@$metodoAforo->Aforo}}</td>
                        </tr> --}}

                        <tr>
                            <td class="fontNormal fontCalibri fontSize12" width="25%">Procedimiento de muestreo</td>
                            <td class="fontCalibri fontSize12 fontBold" width="75%">
                                @if (@$campoCompuesto->Proce_muestreo)
                                    PE-10-002-0{{@$campoCompuesto->Proce_muestreo}}
                                @endif
                                @if (@$campoCompuesto->Proce_muestreo2)
                                    PEA-10-002-1{{@$campoCompuesto->Proce_muestreo2}}
                                @endif
                                </td> 
                        </tr>
                    </tbody>
                </table>

                Procedimiento de pH PE-10-002-3, procedimiento de Temperatura PE-10-002-2, Procedimiento de
                Conductividad PE-10-002-1 <br>
                Procedimiento de recepción de mtas, PG-11-001, Cadena de Custodia RE-11-002<br>

                <table autosize="1" style="width: 100%" cellpadding="2" cellspacing="0">
                    <tbody>
                        <tr>
                            <td class="fontNormal fontCalibri fontSize12" width="25%">Cuenta con tratamiento</td>
                            <td class="fontCalibri fontSize12 fontBold" width="25%">{{@$conTratamiento->Tratamiento}}
                            </td>
                            <td class="fontNormal fontCalibri fontSize12" width="25%">Tipo</td>
                            <td class="fontCalibri fontSize12 fontBold" width="25%">{{@$tipoTratamiento->Tratamiento}}
                            </td>
                        </tr>

                        @switch(@$model->Id_norma)
                        @case(30)
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            @break
                        </tr>
                        @default
                        @if (@$model->Num_tomas > 1)
                        <tr>
                            <td class="fontNormal fontCalibri fontSize12" width="25%">Temperatura muestra compuesta</td>
                            <td class="fontCalibri fontSize12 fontBold" width="25%">
                                {{number_format(@$campoCompuesto->Temp_muestraComp, 1, ".", ",")}} °C</td>
                            <td class="fontNormal fontCalibri fontSize12" width="25%">pH muestra compuesta</td>
                            <td class="fontCalibri fontSize12 fontBold" width="25%">
                                {{number_format(@$campoCompuesto->Ph_muestraComp, 2, ".", ",")}} u pH</td>
                        </tr>
                        @php
                            $auxCloruros = ""
                        @endphp
                        @switch($punto->Cloruros)
                        @case(0)

                            @break
                        @case(499)
                            @php
                                $auxCloruros = "< 500"
                            @endphp
                            @break
                        @case(500)
                            @php
                                $auxCloruros = 500
                            @endphp
                            @break
                        @case(1000)
                                @php
                                    $auxCloruros = 1000
                                @endphp
                            @break
                        @case(1500)
                                @php
                                    $auxCloruros =  "> 1000"
                                @endphp
                            @break
                            @default

                        @endswitch

                        @if ($auxCloruros != "")
                            <tr>
                                <td class="fontNormal fontCalibri fontSize12" width="25%">Cloruros</td>
                                <td class="fontCalibri fontSize12 fontBold" width="25%">{{@$auxCloruros}}</td>
                                <td class="fontNormal fontCalibri fontSize12" width="25%"></td>
                                <td class="fontCalibri fontSize12 fontBold" width="25%"></td>
                            </tr>
                        @endif
                    
                        @else

                        @endif
                        @endswitch
                    </tbody>
                </table>

                <br>

                <table autosize="1" style="width: 100%" cellpadding="2" cellspacing="0">
                    <tbody>
                        <tr>
                            <td class="fontNormal fontCalibri fontSize12" width="40%">Observaciones</td>
                            <td class="fontCalibri fontSize12 fontBold" width="60%">{{@$campoCompuesto->Observaciones}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <br>

            @switch(@$model->Id_norma)
            @case(30)

            @break

            @default
            @if (@$model->Num_tomas > 1)
            <div class="contenedorPadre12">
                <div class="contenedorHijo131">
                    <table autosize="1" style="width: 100%" cellpadding="2" cellspacing="0">
                        <tbody>
                            <tr>
                                <td class="fontBold fontCalibri fontSize12 justificadorIzq" colspan="3">
                                    Calculo de muestra compuesta
                                </td>
                            </tr>

                            <tr>
                                <td class="fontNormal fontCalibri fontSize12">VMSI = VMC x (Qi / QT)</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td class="fontCalibri fontSize12 fontNormal" colspan="2">VOLUMEN CALCULADO</td>
                            </tr>

                            <tr>
                                <td class="fontBold fontCalibri fontSize12">{{@$model->Clave_norma}}</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td class="fontCalibri fontSize12 fontBold">{{@$campoCompuesto->Volumen_calculado}}</td>
                                <td class="fontCalibri fontSize12 fontBold">L</td>
                            </tr>

                            <tr>
                                <td class="fontBold fontCalibri fontSize12">QT (GASTO TOTAL)</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td class="fontCalibri fontSize12 fontBold">{{@$gastoTotal}}</td>
                                <td class="fontCalibri fontSize12 fontBold">L/s</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="contenedorHijo132">
                    <table autosize="1" style="width: 100%" cellpadding="2" cellspacing="0">
                        <tbody>
                            <tr>
                                <td colspan="5">&nbsp;</td>
                            </tr>

                            <tr>
                                <td class="fontCalibri fontSize12 fontNormal justificadorCentr">&nbsp;</td>
                                <td class="fontCalibri fontSize12 fontNormal justificadorCentr">(Qi / QT)</td>
                                <td class="fontCalibri fontSize12 fontNormal justificadorCentr">x</td>
                                <td class="fontCalibri fontSize12 fontNormal justificadorCentr">VMC</td>
                                <td class="fontCalibri fontSize12 fontNormal justificadorCentr">VMSI</td>
                            </tr>

                            @for ($i = 0; $i < @$model->Num_tomas; $i++)
                                <tr>
                                    <td class="fontCalibri fontSize12 fontBold justificadorCentr">{{$i+1}}</td>
                                    <td class="fontCalibri fontSize12 fontNormal justificadorCentr">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        @if (@$gastoMuestra[$i]->Promedio === NULL)
                                        ---
                                        @else
                                        {{@$gastoMuestra[$i]->Promedio}}
                                        @endif
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; / &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                        @if (@$gastoMuestra[$i]->Promedio === NULL)
                                        ---
                                        @else
                                        {{@$gastoTotal}}
                                        @endif
                                        &nbsp;&nbsp;&nbsp; =</td>
                                    <td class="fontCalibri fontSize12 fontBold justificadorCentr">
                                        @if (@$gastoMuestra[$i]->Promedio === NULL)
                                        ---
                                        @else
                                        @php
                                        echo round($gastoMuestra[$i]->Promedio / $gastoTotal, 3);
                                        @endphp
                                        @endif
                                    </td>
                                    <td class="fontCalibri fontSize12 fontBold justificadorCentr">
                                        @if (@$gastoMuestra[$i]->Promedio === NULL)
                                        ---
                                        @else
                                        {{@$campoCompuesto->Volumen_calculado}}
                                        @endif
                                    </td>
                                    <td class="fontCalibri fontSize12 fontBold justificadorCentr">
                                        @php
                                        $auxGasto = 0;
                                        @endphp
                                        @if (@$gastoMuestra[$i]->Promedio === NULL)
                                        ---
                                        @else
                                        {{-- {{number_format((@$gastoMuestra[$i]->Promedio / $gastoTotal, 4)) *
                                        $campoCompuesto->Volumen_calculado) , 2, ".", ".")}} --}}
                                        @php
                                        $auxGasto = (@$gastoMuestra[$i]->Promedio / $gastoTotal) *
                                        $campoCompuesto->Volumen_calculado;
                                        @endphp
                                        {{number_format($auxGasto, 2, ".", ".")}}
                                        @endif
                                    </td>
                                </tr>
                                @endfor
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td class="fontCalibri fontSize12 fontNormal justificadorCentr">VMSI =
                                        @php
                                        $sumaVMSI = 0;

                                        for($i = 0; $i < @$model->Num_tomas; $i++){
                                            if (@$gastoMuestra[$i]->Promedio === NULL)
                                            $sumaVMSI += 0;
                                            else{
                                            $sumaVMSI += round((round($gastoMuestra[$i]->Promedio / $gastoTotal, 3)) *
                                            $campoCompuesto->Volumen_calculado, 3);
                                            }
                                            }
                                            echo round($sumaVMSI, 1)."L";
                                            @endphp
                                    </td>
                                </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @else

            @endif


            @endswitch


        </div>
    </div>
</body>

</html>