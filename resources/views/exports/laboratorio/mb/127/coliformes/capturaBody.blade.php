<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/exports/bitacoras.css')}}">
    <title>Captura PDF </title>
</head>

<body>

    <div class="procedimiento">
        @php
        echo @$bitacora->Texto;
        @endphp
    </div>
    <br>
    <div id="contenedorTabla">


        <br>

        <table autosize="1" class="tabla" border="1">
            <thead>
                <tr>
                    <th colspan="9">BACTERIAS COLIFORMES FECALES</th>
                </tr>
                <tr>
                    <th colspan="4">PRESUNTIVO</th>
                    <th colspan="5">CONFIRMATIVO</th>
                </tr>
                <tr>
                    <th style="font-size: 10px">No. De muestra</th>
                    <th style="font-size: 10px">Total de tubos</th>
                    <th style="font-size: 10px">Positivos 24 hrs</th>
                    <th style="font-size: 10px">Positivos 48 hrs</th>
                    {{-- <th style="font-size: 10px">Resultado NMP / 100 mL</th> --}}
                    <th style="font-size: 10px">Positivos 24 hrs</th>
                    <th style="font-size: 10px">Positivos 48 hrs</th>
                    <th style="font-size: 10px">Resultado NMP / 100 mL</th>
                    <th style="font-size: 10px"></th>
                    <th style="font-size: 10px"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($loteDetalle as $item)
                    <tr>
                        <td>{{ $item->Codigo }}</td>
                        <td>{{ $item->Confirmativa2}}</td>
                        <td>{{ $item->Presuntiva1 }}</td>
                        <td>{{ $item->Presuntiva2 }}</td>
                        <td>{{ $item->Confirmativa1}}</td>
                        <td>{{ $item->Confirmativa2}}</td>
                        <td>
                            
                            @if($item->Resultado == 8)
                                 >{{ $item->Resultado }}
                            @elseif ($item->Resultado > $item->Limite)
                                {{ $item->Resultado }}
                            @else
                                No Detectable
                            @endif
                        </td>
                        <td>{{ $item->Observacion }}</td>
                        <td>{{ $item->Control }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
</body>

</html>