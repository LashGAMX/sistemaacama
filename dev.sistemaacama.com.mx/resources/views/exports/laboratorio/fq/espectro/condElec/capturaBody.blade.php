<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/fq/espectro/condElec/condElecPDF.css')}}">
    <title>Captura PDF</title>
</head>
<body>
    <p id='curvaProcedimiento'>Procedimientos</p>

    <div id="contenidoCurva">
        <?php echo html_entity_decode($textoProcedimiento[0]);?>
    </div>

    <br>

    <div class="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos">
            <thead>

                <tr>
                    <th class="nombreHeader" colspan="9">
                        Resultado de las muestras
                    </th>                    
                </tr>                

                <tr>
                    <th class="tableCabecera anchoColumna">No. de muestras</th>
                    <th class="tableCabecera anchoColumna">TEMPERATURA DE LA MUESTRA</th>
                    <th class="tableCabecera anchoColumna">LECTURA 1</th>
                    <th class="tableCabecera anchoColumna">LECTURA 2</th>
                    <th class="tableCabecera anchoColumna">LECTURA 3</th>
                    <th class="tableCabecera anchoColumna">PROMEDIO DE CONDUCTIVIDAD ELECTRICA UNIDAD</th>
                    <th class="tableCabecera anchoColumna">Observaciones</th>
                    <th class="anchoColumna"></th>
                    <th class="anchoColumna"></th>
                </tr>
            </thead>
    
            <tbody>
                @for ($i = 0; $i < @$dataLength ; $i++)
                    <tr>
                        <td class="tableContent">
                            @if (@$data[$i]->Control == 'Muestra Adicionada' || @$data[$i]->Control == 'Duplicado' || @$data[$i]->Control == 'Resultado')
                                {{@$data[$i]->Folio_servicio}}
                            @else
                                {{@$data[$i]->Control}}    
                            @endif 
                        </td>
                        <td class="tableContent">PRUEBA</td>
                        <td class="tableContent">{{@$data[$i]->Abs1}}</td>
                        <td class="tableContent">{{@$data[$i]->Abs2}}</td>
                        <td class="tableContent">{{@$data[$i]->Abs3}}</td>
                        <td class="tableContent">{{@$data[$i]->Promedio}}</td>
                        <td class="tableContent">{{@$observaciones[$i]->Observaciones}}</td>
                        <td class="tableContent">
                            @if (@$data[$i]->Liberado == 1)
                                Liberado
                            @elseif(@$data[$i]->Liberado == 0)
                                No liberado
                            @endif
                        </td>
                        <td class="tableContent">{{@$data[$i]->Descripcion}}</td>                        
                    </tr>                
                @endfor
            </tbody>        
        </table>  
    </div>

    <div id="contenidoCurva">
        <span id="curvaProcedimiento">Valoración</span> 
        <?php echo html_entity_decode($textoProcedimiento[1]);?>
    </div>
</body>
</html>