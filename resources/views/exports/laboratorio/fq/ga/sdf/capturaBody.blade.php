<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/fq/sdf/sdfPDF.css')}}">
    <title>Captura PDF</title>
</head>
<body>
<div id="contenidoCurva">
        <br>
        @php
            echo $procedimiento[0];
        @endphp
    </div>
    <br>

    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos">
            <thead>

                <tr>
                    <th class="nombreHeader" colspan="10">
                        Resultado de las muestras
                    </th>                    
                </tr>                

                <tr>
                    <th class="tableCabecera anchoColumna">No. de muestra</th>
                    <th class="anchoColumna"></th>
                    <th class="tableCabecera anchoColumna">Valor 1</th>
                    <th class="anchoColumna"></th>
                    <th class="tableCabecera anchoColumna">Valor 2</th> 
                    <th class="tableCabecera anchoColumna">SOLIDOS DISUELTOS FIJOS (SDF) mg/L</th>                    
                    <th class="tableCabecera anchoColumna">Observaciones</th>                    
                    <th class="anchoColumna"></th>
                    <th class="anchoColumna"></th>
                </tr>
            </thead>
    
            <tbody>

                @foreach ($model as $item)
            <tr>
                <td class="tableContent">
                    @if (@$item->Control == 'Muestra Adicionada' || @$item->Control == 'Duplicado'  || @$item->Control == 'Resultado')
                        {{@$item->Folio_servicio}}
                    @else
                        {{@$item->Control}}
                    @endif 
                </td>
                <td class="tableContent">SOLIDOS DISULETOS TOTALES (SDT)</td>
                <td class="tableContent">{{@$item->Masa1}}</td>
                <td class="tableContent">SOLIDOS DISUELTOS VOLÁTILES (SDV)</td>
                <td class="tableContent">{{@$item->Masa2}}</td>
                <td class="tableContent">{{@$item->Resultado}}</td>
                <td class="tableContent">{{@$item->Observacion}}</td>
                <td class="tableContent">
                    @if (@$item->Liberado == 1)
                        Liberado
                    @elseif(@$item->Liberado == 0)
                        No liberado
                    @endif  
                </td>
                <td class="tableContent">{{@$item->Control}}</td>                        
            </tr>           
            @endforeach
            </tbody>        
        </table>  
    </div>

    <div id="contenidoCurva">
        <br>
        @php
            echo $procedimiento[1];
        @endphp
    </div>
</body>
</html>