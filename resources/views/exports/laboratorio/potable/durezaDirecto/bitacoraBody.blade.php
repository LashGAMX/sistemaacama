<!DOCTYPE html>
<html lang="en">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/mb/coliformes/coliformesPDF.css')}}">
    <title>Captura PDF</title>
</head>

<body>

    <div class="procedimiento">
        @php
            echo $plantilla[0]->Texto; 
        @endphp
    </div>
    </div>
    <br>
    <div id="contenedorTabla">


        <br>

        <table autosize="1" class="table table-borderless" id="tablaDatos">
            <thead> 
                <tr>
                    <th class="tableCabecera anchoColumna">No. De muestra</th>
                    <th class="tableCabecera anchoColumna">DUREZA TOTAL(DT) (como CaCO3) mg/L</th>
                    <th class="tableCabecera anchoColumna">DUREZA CALCIO(DC) (como CaCO3) mg/L</th>
                    <th class="tableCabecera anchoColumna">Dureza de Magnesio</th>
                    <th class="tableCabecera anchoColumna">Observaciones</th>
                    <th class="anchoColumna"></th> 
                    <th class="anchoColumna"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($model as $item)
                    <tr>
                        <td class="tableContent">{{ $item->Codigo }}</td>
                        <td class="tableContent">{{ $item->Lectura1 }}</td>
                        <td class="tableContent">{{ $item->Lectura2 }}</td>
                        <td class="tableContent">
                            {{$item->Resultado}}
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