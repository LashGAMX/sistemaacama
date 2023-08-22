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
                    <td class="tableCabecera bordesTablaBody justificadoCentr">&nbsp;PROMEDIO MENSUAL&nbsp;&nbsp;</td>
                    @if (@$tipo == 1)
                        <td class="tableCabecera bordesTablaBody justificadoCentr" width="9%">&nbsp;P.M&nbsp;&nbsp;</td>
                    @endif
                </tr>
            </thead>

            <tbody>
                @php
                    $cont = 0; 
                @endphp
                @foreach ($model1 as $item)
                    <tr>
                        <td class="tableContentLeft bordesTablaBody">{{$item->Parametro}}<sup></sup></td>
                        <td class="tableContent bordesTablaBody">{{$item->Unidad}}</td>
                        <td class="tableContent bordesTablaBody">
                            @switch($item->Id_parametro)
                                @case(64) 
                                @case(67)
                                    METODO DIRECTO
                                    @break
                                @default
                                {{$item->Clave_metodo}}
                            @endswitch
                        </td>
                        <td class="tableContent bordesTablaBody">
                            {{@$limitesC1[$cont]}}
                        </td>                    
                        
                        <td class="tableContent bordesTablaBody">
                            {{@$limitesC2[$cont]}}
                        </td>

                        <td class="tableContent bordesTablaBody">
                            @php
                             $promedio = (@$item->Resultado2 + @$model2[$cont]->Resultado2) / 2;
                            @endphp
                         
                             
                         @switch($item->Id_parametro)
                            @case(6)
                            @case(4)
                            @if ($ponderado[$cont] <= $item->Limite)
                                < {{$item->Limite}}
                                @else
                                {{round($ponderado[$cont], 2)}}
                                @endif 
                                @break
                             @case(97)
                                {{round($promedio)}}
                                @break;
                             @case(12)
                             @case(5)
                             @case(6)
                             @case(13)
                             @case(15)
                             @case(26)
                             @case(83)
                             @case(10)
                             @if ($promedio <= $item->Limite)
                                 < {{$item->Limite}}
                             @else
                                {{round($promedio, 2)}}
                             @endif 
                                 @break
                            @case(2)
                                @if ($item->Resultado2 == 1)
                                    PRESENTE
                                @else
                                    AUSENTE
                                @endif
                                @break
                             @default
                             @if ($promedio <= $item->Limite)
                                 <{{$item->Limite}}
                             @else
                                {{round($promedio, 3)}}
                             @endif 
                               
                         @endswitch
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
    
</body>

</html>