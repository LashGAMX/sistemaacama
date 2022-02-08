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
    <p id='curvaProcedimiento'>Procedimiento</p>

    <div id="contenidoCurva">
        <?php echo html_entity_decode($textoProcedimiento[0]);?>
    </div>

    <br>

    <div class="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos">
            <thead>

                <tr>
                    <th class="nombreHeader" colspan="13">
                        Resultado de las muestras
                    </th>                    
                </tr>                

                <tr>
                    <th class="tableCabecera anchoColumna">No. de muestra</th>
                    <th class="tableCabecera anchoColumna">Volumen de muestra (mL)</th>
                    <th class="tableCabecera anchoColumna">Abs 1</th>
                    <th class="tableCabecera anchoColumna">Abs 2</th>
                    <th class="tableCabecera anchoColumna">Abs 3</th>
                    <th class="tableCabecera anchoColumna">Abs Promedio</th>
                    <th class="tableCabecera anchoColumna">Sulfuros</th>
                    <th class="tableCabecera anchoColumna">Nitratos</th>
                    <th class="tableCabecera anchoColumna">Nitritos</th>
                    <th class="tableCabecera anchoColumna">CIANUROS (CN ̄)
                        mg/L</th>
                    <th class="tableCabecera anchoColumna">Observaciones</th>                                        
                    <th class="anchoColumna"></th>
                    <th class="anchoColumna"></th>
                </tr>
            </thead>
    
            <tbody>
                @for ($i = 0; $i < @$dataLength ; $i++)
                    <tr>
                        <td class="tableContent">{{@$data[$i]->Folio_servicio}}</td>
                        <td class="tableContent">{{@$data[$i]->Vol_muestra}}</td>
                        <td class="tableContent">{{@$data[$i]->Abs1}}</td>
                        <td class="tableContent">{{@$data[$i]->Abs2}}</td>
                        <td class="tableContent">{{@$data[$i]->Abs3}}</td>
                        <td class="tableContent">{{@$data[$i]->Promedio}}</td>
                        <td class="tableContent">{{@$data[$i]->Sulfuros}}</td>
                        <td class="tableContent">{{@$data[$i]->Nitratos}}</td>
                        <td class="tableContent">{{@$data[$i]->Nitritos}}</td>
                        <td class="tableContent">{{@$limites[$i]}}</td>
                        <td class="tableContent">{{@$observaciones[$i]->Observaciones}}</td>
                        <td class="tableContent">LIBERADO</td>
                        <td class="tableContent">{{@$data[$i]->Descripcion}}</td>
                    </tr>                
                @endfor
            </tbody>        
        </table>  
    </div>            
</body>
</html>