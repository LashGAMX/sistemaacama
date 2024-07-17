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
        <table autosize="1" class="tabla" border="0">
            <thead>  
                <tr>
                    <th class="tableCabecera anchoColumna">No. De muestra</th>
                    <th class="tableCabecera anchoColumna">Volumen de muestra (mL)</th>
                    <th class="tableCabecera anchoColumna">pH de la muestra</th>
                    <th class="tableCabecera anchoColumna">COLOR Pt/Co</th>
                    <th class="tableCabecera anchoColumna">Observaciones</th>
                    <th class="tableCabecera anchoColumna"></th>
                    <th class="tableCabecera anchoColumna"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($model as $item) 
                    <tr>
                        <td class="tableContent">
                            @if (@$item->Id_control != 1)
                                {{@$item->Control}}
                                {{@$item->Folio_servicio}}
                            @else
                                {{@$item->Folio_servicio}}
                            @endif                                
                        </td>
                        <td class="tableContent">{{ $item->Vol_muestra }}</td>
                        <td class="tableContent">{{ $item->Ph }}</td>
                        <td class="tableContent">
                            @if (@$item->Resultado < @$item->Limite)
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
                        <td class="tableContent">{{ $item->Control }}</td>
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
