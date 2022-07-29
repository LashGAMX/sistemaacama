<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('/public/css/informes/conComparacion/conComparacionOr.css') }}">
    <title>Informe Con Comparación</title>
</head>

<body>
    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0"
            border-color="#000000" width="100%">
            <thead>
                <tr>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" height="30">PARAMETRO &nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr">&nbsp;METODO DE PRUEBA&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr">&nbsp;UNIDAD&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr">&nbsp;CONCENTRACION
                        CUANTIFICADA&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr">&nbsp;CONCENTRACION PERMISIBLE&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr">ANALISTA</td>
                </tr>
            </thead>

            <tbody>
                @for ($i = 0; $i < @$solicitudParametrosLength; $i++)
                    <tr>
                        <td class="tableContent bordesTablaBody" height="25">{{ @$solicitudParametros[$i]->Parametro }}<sup>{{$sParam[$i]}}</sup></td>
                        <td class="tableContent bordesTablaBody" width="16.6%">{{ @$solicitudParametros[$i]->Clave_metodo }}</td>
                        <td class="tableContent bordesTablaBody" width="10.6%">{{ @$solicitudParametros[$i]->Unidad }}</td>
                        <td class="tableContent bordesTablaBody">
                            @if (@$solicitudParametros[$i]->Id_parametro == 2)
                                                @if (@$solicitudParametros[$i]->Resultado2 == 1)
                                                    PRESENTE
                                                @else
                                                    AUSENTE
                                                @endif
                                              @else
                                              @if (@$solicitudParametros[$i]->Id_parametro == 14)
                                              {{@$solicitudParametros[$i]->Resultado2}}
                                                @else
                                                    @if (@$solicitudParametros[$i]->Resultado2 < @$solicitudParametros[$i]->Limite)
                                                    < {{@$solicitudParametros[$i]->Limite}}
                                                    @else
                                                    {{@$solicitudParametros[$i]->Resultado2}}
                                                    @endif
                                                @endif
                                              @endif
                        </td>
                        <td class="tableContent bordesTablaBody">
                            {{ @$limitesN[$i] }}
                        </td>
                        <td class="tableContent bordesTablaBody" width="20.6%">
                            {{@$solicitudParametros[$i]->iniciales}}
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</body>

</html>
