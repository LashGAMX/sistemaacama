<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/mb/coliformes/coliformesPDF.css')}}">
    <title>Captura PDF </title>
</head>

<body>

    <div id="contenidoCurva">
        @php
            echo $plantilla[0]->Texto; 
        @endphp
    </div>
    <br>
    <div id="contenedorTabla">


        <br>

        <table autosize="1" class="table table-borderless" id="tablaDatos">
            <thead>
                <tr>
                    <th colspan="9" class="tableCabecera anchoColumna">BACTERIAS COLIFORMES FECALES</th>
                </tr>
                <tr>
                    <th colspan="2"></th>
                    <th colspan="2" class="tableCabecera anchoColumna">PRESUNTIVO</th>
                    <th colspan="2" class="tableCabecera anchoColumna">CONFIRMATIVO</th>
                    <th colspan="3"></th>
                </tr>
                <tr>
                    <th class="tableCabecera anchoColumna">No. De muestra</th>
                    <th class="tableCabecera anchoColumna">Total de tubos</th>
                    <th class="tableCabecera anchoColumna">Positivos 24 hrs</th>
                    <th class="tableCabecera anchoColumna">Positivos 48 hrs</th>
                    {{-- <th style="font-size: 10px">Resultado NMP / 100 mL</th> --}}
                    <th class="tableCabecera anchoColumna">Positivos 24 hrs</th>
                    <th class="tableCabecera anchoColumna">Positivos 48 hrs</th>
                    <th class="tableCabecera anchoColumna">Resultado NMP / 100 mL</th>
                    <th class="tableCabecera anchoColumna"></th>
                    <th class="tableCabecera anchoColumna"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($loteDetalle as $item)
                    <tr>
                        <td class="tableContent">{{ $item->Codigo }}</td>
                        <td class="tableContent">{{ $item->Confirmativa2}}</td>
                        <td class="tableContent">{{ $item->Presuntiva1 }}</td>
                        <td class="tableContent">{{ $item->Presuntiva2 }}</td>
                        <td class="tableContent">{{ $item->Confirmativa1}}</td>
                        <td class="tableContent">{{ $item->Confirmativa2}}</td>
                        <td class="tableContent">
                            
                            @if($item->Resultado == 8)
                                 >{{ $item->Resultado }}
                            @elseif ($item->Resultado > $item->Limite)
                                {{ $item->Resultado }}
                            @else
                                No Detectable
                            @endif
                        </td>
                        <td class="tableContent">{{ $item->Observacion }}</td>
                        <td class="tableContent">{{ $item->Control }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
</body>

</html>