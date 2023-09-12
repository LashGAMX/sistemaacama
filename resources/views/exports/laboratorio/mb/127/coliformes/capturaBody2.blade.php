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

    <div id="contenidoCurva" >
        @php
            echo $plantilla[0]->Texto; 
        @endphp
    </div>

    <br>
    <div id="contenedorTabla">
        <table cellpadding="0" cellspacing="0" border-color="#000000" autosize="1" class="table table-borderless" id="tablaDatos">
            <thead> 
                <tr>
                    <th class="contenidoBody nombreHeader" colspan="4">PRESUNTIVO</th>
                    <th class="contenidoBody nombreHeader" colspan="5">CONFIRMATIVO</th>
                </tr> 
                <tr>
                    <th class="contenidoBody nombreHeader bordesTabla">No. De muestra</th>
                    <th class="contenidoBody nombreHeader bordesTabla">Total de tubos</th>
                    <th class="contenidoBody nombreHeader bordesTabla">Positivos 24 hrs</th>
                    <th class="contenidoBody nombreHeader bordesTabla">Positivos 48 hrs</th>
                    <th class="contenidoBody nombreHeader bordesTabla">Positivos 24 hrs</th>
                    <th class="contenidoBody nombreHeader bordesTabla">Positivos 48 hrs</th>
                    <th class="contenidoBody nombreHeader bordesTabla">Resultado NMP / 100 mL</th>
                    <th class="contenidoBody nombreHeader"></th>
                    <th class="contenidoBody nombreHeader"></th>
                </tr>
            </thead> 
            <tbody>
                @foreach ($model as $item)
                    <tr>
                        <td class="contenidoBody bordesTabla">{{ $item->Codigo }}</td>
                        <td class="contenidoBody bordesTabla">{{ $item->Confirmativa2}}</td>
                        <td class="contenidoBody bordesTabla">{{ $item->Presuntiva1 }}</td>
                        <td class="contenidoBody bordesTabla">{{ $item->Presuntiva2 }}</td>
                        <td class="contenidoBody bordesTabla">{{ $item->Confirmativa1}}</td>
                        <td class="contenidoBody bordesTabla">{{ $item->Confirmativa2}}</td>
                        <td class="contenidoBody bordesTabla">
                            @if ($item->Resultado < $item->Limite)
                              <{{$item->Limite}}
                            @else
                                {{$item->Resultado}}
                            @endif
                           
                        </td>
                        <td class="contenidoBody">{{ $item->Observacion }}</td>
                        <td class="contenidoBody">{{ $item->Control }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
</body>

</html>