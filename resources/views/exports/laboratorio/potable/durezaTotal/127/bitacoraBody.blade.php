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

    <div id="contenidoCurva">
        @php
             echo $procedimiento[0];
        @endphp
    </div>
    <br> 
    <div id="contenedorTabla">


        <br>

        <table autosize="1" class="table table-borderless" id="tablaDatos">
            <thead> 
                <tr>
                    <th class="tableCabecera anchoColumna">No. De muestra</th>
                    <th class="tableCabecera anchoColumna">Vol. de la Muestra(ml)</th>
                    <th class="tableCabecera anchoColumna">pH</th>
                    <th class="tableCabecera anchoColumna">Titulación 1</th>
                    <th class="tableCabecera anchoColumna">Titulación 2</th>
                    <th class="tableCabecera anchoColumna">Titulación 3</th>
                    <th class="tableCabecera anchoColumna">Prom. Titulación</th>
                    <th class="tableCabecera anchoColumna">DUREZA TOTAL(DT) (como CaCO3) mg/L</th>
                    <th class="tableCabecera anchoColumna">Observaciones</th>
                    <th class="tableCabecera anchoColumna"></th> 
                    <th class="tableCabecera anchoColumna"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($model as $item)
                    <tr>
                        <td class="tableContent">{{ $item->Codigo }}</td>
                        <td class="tableContent">{{ $item->Vol_muestraVal1 }}</td>
                        <td class="tableContent">{{number_format(($item->Ph_muestraVal1 + $item->Ph_muestraVal2 + $item->Ph_muestraVal3) / 3,1) }}</td>
                        <td class="tableContent">{{number_format($item->EdtaVal1,2)}}</td>
                        <td class="tableContent">{{number_format($item->EdtaVal2,2)}}</td>
                        <td class="tableContent">{{number_format($item->EdtaVal3,2)}}</td>
                        <td class="tableContent">{{number_format(($item->EdtaVal1 +$item->EdtaVal2 +$item->EdtaVal3) / 3,2)}}</td>
                        <td class="tableContent">
                            @if ($item->Resultado > $item->Limite)
                                {{number_format($item->Resultado,2)}}
                            @else
                                < {{$item->Limite}}
                            @endif
                        </td>
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

        
</body>

</html>