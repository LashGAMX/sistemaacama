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
                    <th class="tableCabecera anchoColumna">Temperatura muestra (C°)</th>
                    <th class="tableCabecera anchoColumna">Lectura de pH1</th>
                    <th class="tableCabecera anchoColumna">Lectura de pH2</th>
                    <th class="tableCabecera anchoColumna">Lectura de pH3</th>
                    <th class="tableCabecera anchoColumna">Promedio de Potencial de <br> Hidrogeno (UPH)</th>
                    <th class="tableCabecera anchoColumna">Observaciones</th>
                    <th class="tableCabecera anchoColumna"></th>
                    <th class="tableCabecera anchoColumna"></th>
                </tr> 
            </thead>
            <tbody>
                @foreach ($model as $item)
                    <tr>
                        <td class="tableContent">{{ $item->Codigo }}</td>
                        <td class="tableContent">{{ $item->Temperatura }}</td>
                        <td class="tableContent">{{ $item->Lectura1 }}</td>
                        <td class="tableContent">{{ $item->Lectura2 }}</td>
                        <td class="tableContent">{{ $item->Lectura3 }}</td>
                        <td class="tableContent">{{ $item->Resultado }}</td>
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

        <p style="font-size: 8px">Cálculos: <br>Las lecturas se obtienen directamente de los equipos sacando un promedio de pH. </p>

        <div class="procedimiento">
            @php
                    echo $procedimiento[1];
            @endphp
        </div>
</body>

</html>