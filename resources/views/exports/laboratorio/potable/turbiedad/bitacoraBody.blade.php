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
    <br>
    <div id="contenedorTabla">


        <br>

        <table autosize="1" class="tabla2" border="0">
            <thead> 
                <tr>
                    <th class="tableCabecera anchoColumna">No. De muestra</th>
                    <th class="tableCabecera anchoColumna">Vol. Muestra</th>
                    <th class="tableCabecera anchoColumna">Turb 1</th>
                    <th class="tableCabecera anchoColumna">Turb 2</th>
                    <th class="tableCabecera anchoColumna">Turb 3</th>
                    <th class="tableCabecera anchoColumna">Promedio de lecturas</th>
                    <th class="tableCabecera anchoColumna">F.D</th>
                    <th class="tableCabecera anchoColumna">turbiedad UTN</th>
                    <th class="tableCabecera anchoColumna">Observaciones</th>
                    <th class="tableCabecera anchoColumna"></th>
                    <th class="tableCabecera anchoColumna"></th>
                <th class="tableCabecera anchoColumna">
            </thead>
            <tbody>
                @foreach ($model as $item)
                    <tr>
                        <td class="tableContent">
                            @if ($item->Id_control != 1)
                                {{ $item->Control }}
                                {{ $item->Codigo }}
                            @else
                                {{ $item->Codigo }}
                            @endif
                        </td>
                        <td class="tableContent">{{ $item->Vol_muestra }}</td>
                        <td class="tableContent">{{ $item->Lectura1 }}</td>
                        <td class="tableContent">{{ $item->Lectura2 }}</td>
                        <td class="tableContent">{{ $item->Lectura3 }}</td>
                        <td class="tableContent">{{ round($item->Promedio,2) }}</td>
                        <td class="tableContent">{{ $item->Factor_dilucion }}</td>
                        @php
                    $resultado = "";
                    if ($item->Resultado > 10) {
                    $resultado = ">10";
                    } elseif ($item->Resultado <= $item->Limite) {
                        $resultado = "< " . $item->Limite;
    } else {
        $resultado = round($item->Resultado, 1);
    }
@endphp
                               
                        <td class="tableContent">{{ $resultado }}</td>
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

        <div id="contenidoCurva">
        @php
            echo @$procedimiento[1];
        @endphp 
    </div>
        
</body>

</html>
