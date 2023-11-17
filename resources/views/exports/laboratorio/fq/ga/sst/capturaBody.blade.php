<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/fq/sst/sstPDF.css')}}">
    <title>Captura PDF</title>
</head>
<body>

    <div id="contenidoCurva">
        @php
             echo $procedimiento[0];
        @endphp
    </div>
    <br>

    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos">
            <thead>

                <tr>
                    <th class="nombreHeader" colspan="13">
                        Resultado de las muestras
                    </th>                    
                </tr>                

                <tr>
                    <th class="tableCabecera anchoColumna">No. de muestra</th>
                    <th class="tableCabecera anchoColumna">No. Crisol</th>
                    <th class="tableCabecera anchoColumna">Volumen de muestra (mL)</th>
                    <th class="tableCabecera anchoColumna">Masa cte 1</th>
                    <th class="tableCabecera anchoColumna">Masa cte 2</th>
                    <th class="tableCabecera anchoColumna">Masa 2</th>
                    <th class="tableCabecera anchoColumna">Masa cte c/muestra 1</th>
                    <th class="tableCabecera anchoColumna">Masa cte c/muestra 2</th>
                    <th class="tableCabecera anchoColumna">Masa 6</th>
                    <th class="tableCabecera anchoColumna">SOLIDOS SUSPENDIDOS TOTALES (SST) mg/L</th>
                    <th class="tableCabecera anchoColumna">Observaciones</th>
                    <th class="anchoColumna"></th>
                    <th class="anchoColumna"></th>
                </tr>
            </thead>
    
            <tbody> 
                @foreach ($model as $item)
                <tr>
                    <td class="tableContent">
                        
                        @if ($item->Control != "Resultado")
                        {{@$item->Folio_servicio}}
                        {{@$item->Control}}
                        @else
                        {{@$item->Folio_servicio}}  
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
                    <td class="tableContent">
                        @if ($item->Resultado < $item->Limite)
                            < {{@$item->Limite}}
                        @else
                            {{number_format(@$item->Resultado,2)}}
                        @endif

                    </td>
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
        <span id="curvaProcedimiento">Valoración / Observación</span>
        @php
             echo $procedimiento[1];
        @endphp
    </div>
</body>
</html>