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
                        <td class="fontBold justificadorCentr fontSize10 fontCalibri" width="10%">{{$campoGen->Marca}}
                        </td>
                        <td class="fontBold justificadorCentr fontSize10 fontCalibri" width="10%">{{$campoGen->Equipo}}
                        </td>
                    </tr>
                    <tr>
                        <td class="fontNormal justificadorIzq fontSize10 fontCalibri">Con No. serie:
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="fontBold">{{$campoGen->Serie}}</span></td>
                        <td class="fontNormal justificadorIzq fontSize10 fontCalibri">a una temperatura de <span
                                class="fontBold">{{$campoGen->Temperatura_a}} °C.</span></td>
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
                                @foreach ($phTrazable as $item)
                                <tr>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">{{$item->Ph}}</td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">{{$item->Marca}}
                                    </td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">{{$item->Lote}}</td>
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
                                        <center>pH (Control de calidad)</center>
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
                                @foreach ($phCalidad as $item)
                                <tr>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">
                                        {{$item->Ph_calidad}}</td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">{{$item->Marca}}
                                    </td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">{{$item->Lote}}</td>
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
                                    <td class="fontCalibri fontBold fontSize8 justificadorCentr">+- 5% de
                                        aceptación valor
                                        nominals</td>
                                </tr>
                                @foreach ($campoConTrazable as $item)
                                <tr>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">
                                        {{$item->Conductividad}}</td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">{{$item->Marca}}
                                    </td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">{{$item->Lote}}</td>
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
                                        <center>CONDUCTIVIDAD (Calidad)</center>
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
                                    <td class="fontCalibri fontBold fontSize8 justificadorCentr">+- 5% de
                                        aceptación valor
                                        nominales</td>
                                </tr>
                                @foreach ($campoConCalidad as $item)
                                <tr>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">
                                        {{$item->Conductividad}}</td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">{{$item->Marca}}
                                    </td>
                                    <td class="fontCalibri fontNormal fontSize12 justificadorCentr">{{$item->Lote}}</td>
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
                NOMBRE DE LA EMPRESA: <span class="fontBold">{{$model->Empresa_suc}}</span>
            </div>

            <div class="col-12 fontCalibri fontSize12 fontNormal">
                PUNTO DE MUESTREO: <span class="fontBold">{{$punto->Punto_muestreo}}</span>
            </div>

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
                        <td class="fontBold fontCalibri fontSize12">NORMA</td>
                        <td class="fontNormal fontCalibri fontSize12">TIPO DE MUESTRA</td>
                        <td class="fontBold fontCalibri fontSize12">TIPO</td>
                    </tr>

                    <tr>
                        <td class="fontNormal fontCalibri fontSize12">TIPO DE MUESTREO</td>
                        <td class="fontBold fontCalibri fontSize12">TIPO</td>
                        <td class="fontNormal fontCalibri fontSize12">NUMERO DE MUESTRAS</td>
                        <td class="fontBold fontCalibri fontSize12">NUMERO</td>
                    </tr>

                    <tr>
                        <td class="fontNormal fontCalibri fontSize12">MATERIAL USADO EN EL MUESTREO</td>
                        <td class="fontNormal fontCalibri fontSize10">Ver plan de muestreo RE-11-005</td>
                        <td class="fontNormal fontCalibri fontSize12">&nbsp;</td>
                        <td class="fontBold fontCalibri fontSize12">&nbsp;</td>
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
                        <tr>
                            <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">1</td>
                            <td class="fontNormal fontCalibri fontSize9 bordeFinal justificadorCentr">17</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <br>

            <div class="contenedorPadre12">
                <div class="contenedorHijo121">
                    <span class="fontBold fontCalibri fontSize12">Temperatura corregida para</span>

                    <table autosize="1" style="width: 95%" cellpadding="2" cellspacing="0" border-color="#000000">
                        <thead>
                            <tr>
                                <th class="fontBold fontCalibri fontSize9 bordesTablaBody justificadorCentr">°C</th>
                                <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">temperatura
                                    corregida</th>
                                <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">temperatura
                                    aplicada</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">RANGO
                                </td>
                                <td class="fontNormal fontCalibri fontSize9 bordeFinal justificadorCentr">FC</td>
                                <td class="fontNormal fontCalibri fontSize9 bordeFinal justificadorCentr">FA</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="contenedorHijo122">
                    <span class="fontBold fontCalibri fontSize12">Ejemplo de aplicación de la temperatura
                        corregida</span>

                    <table autosize="1" style="width: 100%" cellpadding="2" cellspacing="0" border-color="#000000">
                        <thead>
                            <tr>
                                <th class="fontBold fontCalibri fontSize9 bordesTablaBody justificadorCentr">No. Muestra</th>
                                <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">temperatura °C</th>
                                <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">+/-</th>
                                <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">temperatura corregida</th>
                                <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">temperatura aplicada</th>
                                <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">temperatura ajustada</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">1</td>
                                <td class="fontNormal fontCalibri fontSize9 bordeFinal justificadorCentr" colspan="2">
                                    17.00</td>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">0.00</td>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">0.00</td>
                                <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">17</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-12 fontCalibri fontSize10 fontNormal">
                Nota. Los demas valores registrados de temperatura llevan aplicado la temperatura corregida de la
                temperatura correspondiente. <br>
                La medición de la temperatura del agua y temperatura ambiente es realizada con el sensor de temperatura
                del equipo PC-18 con
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <span class="fontBold">{{$campoGen->Serie}}</span>
            </div>

            <br>

            <div class="col-12 fontCalibri fontSize12 fontBold">
                Datos de Campo
            </div>

            <br>

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
                            <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Promedio temp. °C</th>
                            <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Conductividad (µS/cm)</th>
                            <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Conductividad (µS/cm)</th>
                            <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Conductividad (µS/cm)</th>
                            <th class="fontBold fontCalibri fontSize9 bordeFinal justificadorCentr">Promedio Conduc. µS/cm (+-5%)</th>
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
                        @for ($i = 0; $i < $model->Num_tomas; $i++)                                                    
                            @if (@$phMuestra[$i]->Activo != 0)
                                <tr>
                                    <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">{{@$i+1}}</td>
                                    <td class="fontNormal fontCalibri fontSize9 bordeFinal justificadorCentr">{{@$phMuestra[$i]->Materia}}</td>
                                    <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">{{@$phMuestra[$i]->Color}}</td>
                                    <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">{{@$phMuestra[$i]->Olor}}</td>
                                    <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">{{@$phMuestra[$i]->Ph1}}</td>
                                    <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">{{@$phMuestra[$i]->Ph2}}</td>
                                    <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">{{@$phMuestra[$i]->Ph3}}</td>
                                    <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">{{@$phMuestra[$i]->Promedio}}</td>
                                    <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">{{@$tempMuestra[$i]->Temperatura1}}</td>
                                    <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">{{@$tempMuestra[$i]->Temperatura2}}</td>
                                    <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">{{@$tempMuestra[$i]->Temperatura3}}</td>
                                    <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">{{@$conMuestra[$i]->Conductividad1}}</td>
                                    <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">{{@$conMuestra[$i]->Conductividad2}}</td>
                                    <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">{{@$conMuestra[$i]->Conductividad3}}</td>
                                    <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">{{@$conMuestra[$i]->Promedio}}</td>
                                    <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">{{@$gastoMuestra[$i]->Gasto1}}</td>
                                    <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">{{@$gastoMuestra[$i]->Gasto2}}</td>
                                    <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">{{@$gastoMuestra[$i]->Gasto3}}</td>
                                    <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">{{@$gastoMuestra[$i]->Promedio}}</td>
                                    <td class="fontNormal fontCalibri fontSize9 bordesTablaBody justificadorCentr">0</td>
                                </tr>
                            @endif
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
                                Total GASTO</th>
                            <th>&nbsp;</th>
                        </tr>
                    </tbody>
                </table>
            </div>

            <br>

            <div class="col-12 fontCalibri fontSize12 fontNormal">
                <table autosize="1" style="width: 100%" cellpadding="2" cellspacing="0">
                    <tbody>
                        <tr>
                            <td class="fontNormal fontCalibri fontSize12" width="40%">Metodo de aforos</td>
                            <td class="fontCalibri fontSize12 fontBold" width="60%">METODO</td>
                        </tr>

                        <tr>
                            <td class="fontNormal fontCalibri fontSize12" width="25%">Procedimiento de muestreo</td>
                            <td class="fontCalibri fontSize12 fontBold" width="75%">PROCEDIMIENTO</td>
                        </tr>
                    </tbody>
                </table>

                Procedimiento de pH PE-10-02-03, procedimiento de Temperatura PE-10-02-02, Procedimiento de
                Conductividad PE-10-02-01 <br>
                Procedimiento de recepción de mtas, PG-11-01, Cadena de Custodia RE-11-02 <br>

                <table autosize="1" style="width: 100%" cellpadding="2" cellspacing="0">
                    <tbody>
                        <tr>
                            <td class="fontNormal fontCalibri fontSize12" width="25%">Cuenta con tratamiento</td>
                            <td class="fontCalibri fontSize12 fontBold" width="25%">NO</td>
                            <td class="fontNormal fontCalibri fontSize12" width="25%">Tipo</td>
                            <td class="fontCalibri fontSize12 fontBold" width="25%">TIPO</td>
                        </tr>

                        <tr>
                            <td class="fontNormal fontCalibri fontSize12" width="25%">Temperatura muestra compuesta</td>
                            <td class="fontCalibri fontSize12 fontBold" width="25%">TEMPERATURA</td>
                            <td class="fontNormal fontCalibri fontSize12" width="25%">pH muestra compuesta</td>
                            <td class="fontCalibri fontSize12 fontBold" width="25%">PH</td>
                        </tr>
                    </tbody>
                </table>

                <br>

                <table autosize="1" style="width: 100%" cellpadding="2" cellspacing="0">
                    <tbody>
                        <tr>
                            <td class="fontNormal fontCalibri fontSize12" width="40%">Observaciones</td>
                            <td class="fontCalibri fontSize12 fontBold" width="60%">OBSERVACIONES</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <br>

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
                                <td class="fontCalibri fontSize12 fontNormal" colspan="2">VOLUMEN CALCULADO</td>
                            </tr>

                            <tr>
                                <td class="fontBold fontCalibri fontSize12">NORMA</td>
                                <td class="fontCalibri fontSize12 fontBold">VOLUMEN</td>
                                <td class="fontCalibri fontSize12 fontBold">UNIDAD</td>
                            </tr>

                            <tr>
                                <td class="fontBold fontCalibri fontSize12">QT (GASTO TOTAL)</td>
                                <td class="fontCalibri fontSize12 fontBold">VOLUMEN</td>
                                <td class="fontCalibri fontSize12 fontBold">UNIDAD</td>
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

                            <tr>
                                <td class="fontCalibri fontSize12 fontBold justificadorCentr">1</td>
                                <td class="fontCalibri fontSize12 fontNormal justificadorCentr">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;QI &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; / &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; QT &nbsp;&nbsp;&nbsp; =</td>
                                <td class="fontCalibri fontSize12 fontBold justificadorCentr">RESULTADO</td>
                                <td class="fontCalibri fontSize12 fontBold justificadorCentr">VMC</td>
                                <td class="fontCalibri fontSize12 fontBold justificadorCentr">VMSI</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>