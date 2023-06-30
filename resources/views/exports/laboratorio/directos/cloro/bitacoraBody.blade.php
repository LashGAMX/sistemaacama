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
  echo $plantilla[0]->Texto; 
        @endphp
    </div>
    <br>

    <div id="contenedorTabla">
    <table autosize="1" class="tabla2" border="0">
        <thead> 
            <tr>
                <th class="tableCabecera anchoColumna">No. De muestra</th>
                <th class="tableCabecera anchoColumna">Volumen de muestra (mL)</th>
                <th class="tableCabecera anchoColumna">Lectura 1</th>
                <th class="tableCabecera anchoColumna">Lectura 2</th>
                <th class="tableCabecera anchoColumna">Lectura 3</th>
                <th class="tableCabecera anchoColumna">Lectura Prom.</th>
                <th class="tableCabecera anchoColumna">CLORO mg/L</th>
                <th class="tableCabecera anchoColumna">Observaciones</th>
                <th class="tableCabecera anchoColumna"></th>
                <th class="tableCabecera anchoColumna"></th>
                
            </tr> 
        </thead>
        <tbody>
            @foreach ($model as $item)
                <tr>
                    <td class="tableContent">{{ $item->Codigo }}</td>
                    <td class="tableContent">{{ $item->Vol_muestra }}</td> 
                    <td class="tableContent">{{ $item->Lectura1 }}</td>
                    <td class="tableContent">{{ $item->Lectura2 }}</td>
                    <td class="tableContent">{{ $item->Lectura3 }}</td>
                    <td class="tableContent">{{ round($item->Promedio,2) }}</td>
                    <td class="tableContent">
                        @if (@$item->Resultado <= @$item->Limite)
                        < {{@$item->Limite}}
                        @else
                        {{round(@$item->Resultado,2)}}
                        @endif
                    </td>
                    <td class="tableContent">{{ $item->Observacion }}</td>
                    @if ($item->Liberado != NULL)
                        <td class="tableContent">LIBERADO</td>
                    @else
                        <td class="tableContent">NO LIBERADO</td>
                    @endif
                        <td class="tableContent">{{ $item->Control }}<td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</body>

</html>