<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/fq/sdt/sdtPDF.css')}}">
    <title>Captura PDF</title>
</head>
<body>


    <div id="contenidoCurva">
        <br>
        @php
            echo $plantilla->Texto;
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
                    <th class="tableCabecera anchoColumna">Numero <br> Capsula</th>
                    <th class="tableCabecera anchoColumna">Vol. Muestra</th>
                    <th class="tableCabecera anchoColumna">P. Cte. 1 sin muestra</th>
                    <th class="tableCabecera anchoColumna">P. Cte. 2 sin muestra</th>
                    <th class="tableCabecera anchoColumna">B</th>
                    <th class="tableCabecera anchoColumna">Peso Cte 1 con muestra</th>
                    <th class="tableCabecera anchoColumna">Peso Cte 2 con muestra</th>
                    <th class="tableCabecera anchoColumna">A</th>
                    <th class="tableCabecera anchoColumna">SOLIDOS DISUELTOS TOTALES</th>                    
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
                <td class="tableContent">{{@$item->Crisol}}</td>
                <td class="tableContent">{{@$item->Vol_muestra}}</td>
                <td class="tableContent">{{@$item->Peso_muestra1}}</td>
                <td class="tableContent">{{@$item->Peso_muestra2}}</td>
                <td class="tableContent">{{@$item->Masa1}}</td>
                <td class="tableContent">{{@$item->Peso_constante1}}</td>
                <td class="tableContent">{{@$item->Peso_constante2}}</td>
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
</body>
</html>