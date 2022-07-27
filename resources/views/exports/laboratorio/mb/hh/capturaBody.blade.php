<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/mb/curvaPDF.css')}}">
    <title>Captura PDF</title>
</head>
<body>
    <p id='curvaProcedimiento'>Procedimiento</p>

    <div id="contenidoCurva">
        <?php echo html_entity_decode(@$textoProcedimiento[0]);?>
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
                    <th class="tableCabecera">No. de muestra</th>
                    <th class="tableCabecera">A.Lumbricoides</th>
                    <th class="tableCabecera">Uncinarias</th>
                    <th class="tableCabecera">H. nana</th>
                    <th class="tableCabecera">T. Trichiura</th>
                    <th class="tableCabecera">Taenia sp</th>
                    <th class="tableCabecera">H/L</th>
                    <th class="tableCabecera">Observaciones</th>                    
                    <th></th>
                    <th></th>
                </tr>
            </thead>
    
            <tbody>
                @for ($i = 0; $i < @$dataLength ; $i++)
                    <tr>
                        <td class="tableContent">{{@$data[$i]->Folio_servicio}}</td>
                        <td class="tableContent">{{@$data[$i]->A_alumbricoides}}</td>
                        <td class="tableContent">{{@$data[$i]->Uncinarias}}</td>
                        <td class="tableContent">{{@$data[$i]->H_nana}}</td>
                        <td class="tableContent">{{@$data[$i]->T_trichiura}}</td>
                        <td class="tableContent">{{@$data[$i]->Taenia_sp}}</td>
                        <td class="tableContent">
                            @if (@$data[$i]->Resultado > @$data[$i]->Limite)
                            {{@$data[$i]->Resultado}}    
                            @else
                                < {{@$data[$i]->Limite}}
                            @endif
                            </td>
                        <td class="tableContent">{{@$data[$i]->Observacion}}</td>
                        <td class="tableContent">
                            @if (@$data[$i]->Liberado == 1)
                                Liberado
                            @elseif(@$data[$i]->Liberado == 0)
                                No liberado
                            @endif </td>
                        <td class="tableContent">{{@$controlesCalidad[$i]->Control}}</td>
                    </tr>                
                @endfor
            </tbody>        
        </table>  
    </div>

    <br>

    <div id="contenidoCurva">
        <span id="curvaProcedimiento">Validación</span>
        <?php echo html_entity_decode(@$textoProcedimiento[1]);?>
    </div>
</body>
</html>