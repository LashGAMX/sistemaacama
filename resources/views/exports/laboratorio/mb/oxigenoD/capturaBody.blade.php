<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/mb/oxigenoD/oxigenoDPDF.css')}}">
    <title>Captura PDF</title>
</head>
<body>
    <p id='curvaProcedimiento'>Procedimiento</p>

    <div id="contenidoCurva">
    @php
             echo $procedimiento[0];
        @endphp
    </div>

    <br>

    <div class="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos">
            <thead>

                <tr>
                    <th class="nombreHeader" colspan="5">
                        Resultado de las muestras
                    </th>                    
                </tr>                

                <tr>
                    <th class="tableCabecera anchoColumna">No. de muestra</th>
                    <th class="tableCabecera anchoColumna">OXIGENO DISUELTO mg/L</th>                    
                    <th class="tableCabecera anchoColumna">Observaciones</th>
                    <th class="anchoColumna"></th>
                    <th class="anchoColumna"></th>
                </tr>
            </thead>
    
            <tbody>
               @foreach ($model as $item)
                   <tr>
                        <td class="tableContent">{{$item->Codigo}}</td>
                        <td class="tableContent">{{$item->Resultado}}</td>
                        <td class="tableContent">{{$item->Observacion}}</td>
                        <td class="tableContent">
                            @if (@$item->Liberado == 1)
                                Liberado
                            @elseif(@$item->Liberado == 0)
                                No liberado
                            @endif
                        </td>

                        <td class="tableContent">
                            {{@$item->Control}}
                        </td>                         
                   </tr>
               @endforeach
            </tbody>        
        </table>  
    </div>
</body>
</html>