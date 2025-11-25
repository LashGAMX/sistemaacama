<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/fq/espectro/cianuros/cianurosPDF.css')}}">
    <title>Captura PDF</title>
</head>

<body>

    <div class="procedimiento">
        @php
        echo @$procedimiento[0];
        @endphp
    </div>
    <div id="contenedorTabla">
        <table autosize="1" class="tabla" border="0">
            @php
            $mostrarRed = $model->contains('Id_parametro', 365);
            @endphp

            <thead>
                <tr>
                    <th class="tableCabecera anchoColumna">No. De muestra</th>
                    <th class="tableCabecera anchoColumna">Volumen de muestra (mL)</th>
                    <th class="tableCabecera anchoColumna">F.D</th>
                    <th class="tableCabecera anchoColumna">pH de la muestra</th>
                    <th class="tableCabecera anchoColumna">Color aparente</th>
                    <th class="tableCabecera anchoColumna">Color verdadero</th>
                    <th class="tableCabecera anchoColumna">COLOR Pt/Co</th>
                    @if ($mostrarRed)
                    <th class="tableCabecera anchoColumna">COLOR Pt/Co (R)</th>
                    @endif


                    <th class="tableCabecera anchoColumna">Observaciones</th>
                    <th class="tableCabecera anchoColumna"></th>
                    <th class="tableCabecera anchoColumna"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($model as $item)
                <tr>
                    <td class="tableContent">
                        @if (@$item->Id_control != 1)
                        {{@$item->Control}}
                        {{@$item->Folio_servicio}}
                        @else
                        {{@$item->Folio_servicio}}
                        @endif
                    </td>
                    <td class="tableContent">{{ $item->Vol_muestra }}</td>
                    <td class="tableContent">{{ $item->Factor_dilucion }}</td>
                    <td class="tableContent">{{ $item->Ph }}</td>
                    <td class="tableContent">{{ $item->Color_a == "" ? '-----' : $item->Color_a }}</td>
                    <td class="tableContent">{{ $item->Color_v == "" ? '-----' : $item->Color_v }}</td>

                    <td class="tableContent">
                        @switch($lote->Id_tecnica)
                            @case(365)
                                    @if (@$item->Resultado < 2.5)
                                        < 2.5
                                    @else
                                        {{round(@$item->Resultado,2)}}
                                    @endif
                                @break
                            @default
                                 @if (@$item->Resultado < @$item->Limite)
                                    < {{@$item->Limite}}
                                @else
                                    @switch($lote->Id_tecnica)
                                    @case(365)
                                        {{round(@$item->Resultado,2)}}
                                    @break
                                    @default
                                        @if ($item->Resultado > 70)
                                            >70
                                        @else
                                            {{round(@$item->Resultado,2)}}
                                        @endif
                                    @endswitch
                                @endif
                        @endswitch
                       
                    </td>
                    @if ($item->Id_parametro == 365)
                    <td class="tableContent">
                        @if ($item->Id_control != 1)
                            N/A
                        @else
                        @if ($item->Aux === '')
                            -----
                        @elseif (is_numeric($item->Aux) && $item->Aux < 3) 
                            &lt;3
                        @else {{ $item->Aux }}
                            @endif
                        @endif
                    </td>

                    @endif

                    <td class="tableContent">{{ $item->Observacion }}</td>
                    @if ($item->Liberado != NULL)
                    <td class="tableContent">LIBERADO</td>
                    @else
                    <td class="tableContent">NO LIBERADO</td>
                    @endif
                    <td class="tableContent">{{ $item->Control }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="procedimiento">
            @php
            echo @$procedimiento[1];
            @endphp

        </div>
</body>


</html>