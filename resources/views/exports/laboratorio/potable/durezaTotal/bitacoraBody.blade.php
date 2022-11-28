<!DOCTYPE html>
<html lang="en">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/exports/bitacoras.css')}}">
    <title>Captura PDF</title>
</head>

<body>

    <div class="procedimiento">
        @php
        echo @$textoProcedimiento->Texto;
        @endphp
    </div>
    <br>
    <div id="contenedorTabla">


        <br>

        <table autosize="1" class="tabla1">
            <thead> 
                <tr>
                    <th style="font-size: 10px">No. De muestra</th>
                    <th style="font-size: 10px">Vol. de la Muestra(ml)</th>
                    <th style="font-size: 10px">Vol. Titulante</th>
                    <th style="font-size: 8px">DUREZA TOTAL(DT) (como CaCO3) mg/L</th>
                    <th style="font-size: 10px">Observaciones</th>
                    <th style="font-size: 10px"></th> 
                    <th style="font-size: 10px"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($model as $item)
                    <tr>
                        <td>{{ $item->Codigo }}</td>
                        <td>{{ $item->Vol_muestra }}</td>
                        <td>{{ $item->Edta }}</td>
                        <td>{{ $item->Resultado }}</td>
                        <td>{{ $item->Observacion }}</td>
                        @if ($item->Liberado != NULL)
                            <td>LIBERADO</td>
                        @else
                            <td>NO LIBERADO</td>
                        @endif
                        <td>{{ $item->Control }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
</body>

</html>