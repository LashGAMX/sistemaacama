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
    <div id="contenedorTabla">
        <style>
            table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            }
        </style>
        <table autosize="1" class="tabla">
            <thead>  
                <tr>
                    <th class="tableCabecera anchoColumna">No. De muestra</th>
                    <th class="tableCabecera anchoColumna">Volumen de muestra</th>
                    <th class="tableCabecera anchoColumna">F.D</th>
                    <th class="tableCabecera anchoColumna">pH de la muestra</th>
                    <th class="tableCabecera anchoColumna">Longitud de Onda (λ nm)</th>
                    <th class="tableCabecera anchoColumna">Abs 1 m-1</th>
                    <th class="tableCabecera anchoColumna">Abs 2 m-1</th>
                    <th class="tableCabecera anchoColumna">Abs 3 m-1</th>
                    <th class="tableCabecera anchoColumna">Abs Promedio</th>
                    <th class="tableCabecera anchoColumna">Color Verdadero nm; m-1</th>

                    <th class="tableCabecera anchoColumna">Observaciones</th>
                    <th class="tableCabecera anchoColumna"></th>
                    <th class="tableCabecera anchoColumna"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($model as $item) 
                    <tr>
                        <td class="tableContent" rowspan="3">
                            @if (@$item->Id_control != 1)
                                {{@$item->Control}}
                                {{@$item->Folio_servicio}}
                            @else
                                {{@$item->Folio_servicio}}
                            @endif                                
                        </td>
                        <td class="tableContent" rowspan="3">{{ $item->Vol_muestra }}</td>
                        <td class="tableContent">{{ $item->Fd1 }}</td>
                        <td class="tableContent" rowspan="3">{{ $item->Ph_muestra }}</td>
                        <td class="tableContent">{{ $item->Longitud1 }}</td>

                        <td class="tableContent">{{ $item->Abs1_436 }}</td>
                        <td class="tableContent">{{ $item->Abs2_436 }}</td>
                        <td class="tableContent">{{ $item->Abs3_436 }}</td>

                        <td class="tableContent">{{ $item->Abs_promedio1 }}</td>
                        <td class="tableContent">{{ $item->Resultado1 }}</td>
                            @if (@$item->Resultado < @$item->Limite)
                            < {{@$item->Limite}}
                            @else
                            {{round(@$item->Resultado,2)}}
                            @endif
                        </td>
                        
                        <td class="tableContent">{{$item->Observacion1}}</td>
                        @if ($item->Liberado != NULL)
                            <td class="tableContent" rowspan="3">LIBERADO</td>
                        @else
                            <td class="tableContent" rowspan="3">NO LIBERADO</td>
                        @endif
                        <td class="tableContent" rowspan="3">{{ $item->Control }}</td>
                    </tr>
                    <tr>
                        <td class="tableContent">{{ $item->Fd2 }}</td>
                        <td class="tableContent">{{ $item->Longitud2 }}</td>
                        <td class="tableContent">{{ $item->Abs1_525 }}</td>
                        <td class="tableContent">{{ $item->Abs2_525 }}</td>
                        <td class="tableContent">{{ $item->Abs3_525 }}</td>
                        <td class="tableContent">{{ $item->Abs_promedio2 }}</td>
                        <td class="tableContent">{{ $item->Resultado2 }}</td>
                        <td class="tableContent">{{$item->Observacion2}}</td>
                    </tr>
                    <tr>
                        <td class="tableContent">{{ $item->Fd3 }}</td>
                        <td class="tableContent">{{ $item->Longitud3 }}</td>
                        <td class="tableContent">{{ $item->Abs1_620 }}</td>
                        <td class="tableContent">{{ $item->Abs2_620 }}</td>
                        <td class="tableContent">{{ $item->Abs3_620 }}</td>
                        <td class="tableContent">{{ $item->Abs_promedio3 }}</td>
                        <td class="tableContent">{{ $item->Resultado3 }}</td>
                        <td class="tableContent">{{$item->Observacion3}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="procedimiento"> 
        @php
        echo @$procedimiento[1];
        @endphp
        
    </div>
</body>


</html>
