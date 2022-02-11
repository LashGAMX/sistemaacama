<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/fq/st/stPDF.css')}}">
    <title>Captura PDF</title>
</head>
<body>    

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
                    <th class="tableCabecera anchoColumna">No. Capsula</th>
                    <th class="tableCabecera anchoColumna">Volumen de muestra (mL)</th>
                    <th class="tableCabecera anchoColumna">Peso cte 1</th>
                    <th class="tableCabecera anchoColumna">Peso cte 2</th>
                    <th class="tableCabecera anchoColumna">Masa 1</th>
                    <th class="tableCabecera anchoColumna">Peso cte c/muestra 1</th>
                    <th class="tableCabecera anchoColumna">Peso cte c/muestra 2</th>
                    <th class="tableCabecera anchoColumna">Masa 3</th>
                    <th class="tableCabecera anchoColumna">SOLIDOS TOTALES (ST) mg/L</th>
                    <th class="tableCabecera anchoColumna">Observaciones</th>                                        
                    <th class="anchoColumna"></th>
                    <th class="anchoColumna"></th>
                </tr>
            </thead>
    
            <tbody>
                @for ($i = 0; $i < @$dataLength ; $i++)
                    <tr>
                        <td class="tableContent">{{@$data[$i]->Folio_servicio}}</td>
                        <td class="tableContent">PRUEBA</td>
                        <td class="tableContent">{{@$data[$i]->Vol_muestra}}</td>
                        <td class="tableContent">{{@$data[$i]->Peso_constante1}}</td>
                        <td class="tableContent">{{@$data[$i]->Peso_constante2}}</td>
                        <td class="tableContent">{{@$data[$i]->Masa1}}</td>
                        <td class="tableContent">PRUEBA</td>
                        <td class="tableContent">PRUEBA</td>
                        <td class="tableContent">PRUEBA</td>
                        <td class="tableContent">{{@$data[$i]->Resultado}}</td>
                        <td class="tableContent">{{@$data[$i]->Observacion}}</td>
                        <td class="tableContent">
                            @if (@$data[$i]->Liberado == 1)
                                Liberado
                            @elseif(@$data[$i]->Liberado == 0)
                                No liberado
                            @endif     
                        </td>
                        <td class="tableContent">{{@$data[$i]->Control}}</td>
                    </tr>                
                @endfor
            </tbody>        
        </table>  
    </div>

    <br>

    <div id="contenidoCurva">
        <span id="curvaProcedimiento">Valoración / Observación</span>
        <?php echo html_entity_decode($textoProcedimiento[1]);?>
    </div>
</body>
</html>