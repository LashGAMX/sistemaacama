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
    <p id='curvaProcedimiento'>Procedimiento</p>

    <div id="contenidoCurva">
        <?php echo html_entity_decode($textoProcedimiento->Texto);?>
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
                    <th class="tableCabecera anchoColumna">SOLIDOS DISUELTOS TOTALES (SDT) mg/L</th>                    
                    <th class="tableCabecera anchoColumna">Observaciones</th>
                    <th class="anchoColumna"></th>
                    <th class="anchoColumna"></th>
                </tr>
            </thead>
    
            <tbody>
                @for ($i = 0; $i < @$dataLength ; $i++)
                    <tr>
                        <td class="tableContent">
                            @if (@$data[$i]->Control == 'Estandar')
                                ESTANDAR
                            @elseif(@$data[$i]->Control == 'Blanco')
                                BLANCO
                            @else
                                {{@$data[$i]->Folio_servicio}}
                            @endif 
                        </td>
                        <td class="tableContent">{{@$paramSt->Parametro}}</td>
                        <td class="tableContent">{{@$data[$i]->Masa1}}</td>
                        <td class="tableContent">{{@$paramSt2->Parametro}}</td>
                        <td class="tableContent">{{@$data[$i]->Masa2}}</td>
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
</body>
</html>