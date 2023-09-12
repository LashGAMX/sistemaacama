<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/fq/ss/ssPDF.css')}}">
    <title>Captura PDF</title>
</head>
<body>
    <p id='curvaProcedimiento'>Procedimiento</p>

    <div id="contenidoCurva">
        @php
            echo $plantilla[0]->Texto;
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
                    <th class="tableCabecera anchoColumna">No. de muestras</th>
                    <th class="tableCabecera anchoColumna">No. de cono IMHOFF</th>
                    <th class="tableCabecera anchoColumna">SOLIDOS SEDIMENTABLES (S.S) mL/L</th>
                    <th class="tableCabecera anchoColumna">LLEGADA 2°C a 8°C</th>
                    <th class="tableCabecera anchoColumna">TEMPERATURA DE LA MUESTRA °C AL ANALIZAR (Temp. Amb)</th>
                    <th class="tableCabecera anchoColumna">Observaciones</th>                                        
                    <th class="anchoColumna"></th>
                    <th class="anchoColumna"></th>
                </tr>
            </thead>
    
            <tbody>
                @foreach ($model as $item)
                <tr>
                    <td class="tableContent">
                        @if (@$item->Control == 'Muestra Adicionada' || @$item->Control == 'Duplicado' || @$item->Control == 'Resultado')
                            {{@$item->Folio_servicio}}
                        @else
                            {{@$item->Control}}
                        @endif                            
                    </td>
                    <td class="tableContent">{{@$item->Inmhoff}}</td>
                    @if ($item->Resultado < $item->Limite)
                        <td class="tableContent">< {{$item->Limite}}</td>
                    @else
                        <td class="tableContent">{{number_format(@$item->Resultado, 2, ".", ".")}}</td>
                    @endif
                    <td class="tableContent">{{@$item->Temp_muestraLlegada}}</td>
                    <td class="tableContent">{{@$item->Temp_muestraAnalizada}}</td>
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