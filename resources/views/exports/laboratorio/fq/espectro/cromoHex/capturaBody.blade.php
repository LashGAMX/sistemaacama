<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/fq/espectro/cromoHex/cromoHexPDF.css')}}">
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
                    <th class="nombreHeader" colspan="12">
                        Resultado de las muestras
                    </th>                    
                </tr>                

                <tr>
                    <th class="tableCabecera anchoColumna">No. de muestra</th>
                    <th class="tableCabecera anchoColumna">Volumen de muestra</th>
                    <th class="tableCabecera anchoColumna">Abs 1</th>
                    <th class="tableCabecera anchoColumna">Abs 2</th>
                    <th class="tableCabecera anchoColumna">Abs 3</th>
                    <th class="tableCabecera anchoColumna">Abs Promedio</th>
                    <th class="tableCabecera anchoColumna">Ph Inicial</th>
                    <th class="tableCabecera anchoColumna">Ph Final</th>
                    <th class="tableCabecera anchoColumna">CROMO HEXAVALENTE (Cr+6) mg/L</th>
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
                        <td class="tableContent">{{@$data[$i]->Vol_muestra}}</td>
                        <td class="tableContent">{{@$data[$i]->Abs1}}</td>
                        <td class="tableContent">{{@$data[$i]->Abs2}}</td>
                        <td class="tableContent">{{@$data[$i]->Abs3}}</td>
                        <td class="tableContent">{{@$data[$i]->Promedio}}</td>
                        <td class="tableContent">{{@$data[$i]->Ph_ini}}</td>
                        <td class="tableContent">{{@$data[$i]->Ph_fin}}</td>
                        <td class="tableContent">{{@$limites[$i]}}</td>
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

    <div class="contenedorSexto">                
        <span><br> Absorbancia B1: {{@$data[0]->Blanco}}</span> <br><br>
        <span>Absorbancia B2: {{@$data[0]->Blanco}}</span> <br><br>
        <span>Absorbancia B3: {{@$data[0]->Blanco}}</span> <br><br>
        <span>RESULTADO BLANCO: {{@$data[0]->Blanco}}</span>
    </div>

    <br>

    <div id="contenidoCurva">
        <span id="curvaProcedimiento">Valoración</span> 
        <?php echo html_entity_decode($textoProcedimiento[1]);?>
    </div>

    <br>

    <div class="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos" style="width: 60%">
            <thead>

                <tr>
                    <th class="nombreHeader" colspan="4">
                        Datos de la curva de calibración
                    </th>                    
                </tr>                
                
            </thead>
    
            <tbody>
                {{-- @for ($i = 0; $i < 100 ; $i++) --}}
                    <tr>
                        <td class="tableCabecera">b = </td>
                        <td class="tableContent">{{@$curva->B}}</td>                        
                        <td class="tableCabecera">Fecha de preparación: </td>
                        <td class="tableContent">{{@$curva->Fecha_inicio}}</td>                                                
                    </tr>

                    <tr>
                        <td class="tableCabecera">m = </td>
                        <td class="tableContent">{{@$curva->M}}</td>                        
                        <td class="tableCabecera">Límite de cuantificación: </td>
                        <td class="tableContent"> <{{@$limiteC->Limite}} </td>
                    </tr>

                    <tr>
                        <td class="tableCabecera">r = </td>
                        <td class="tableContent">{{@$curva->R}}</td>
                    </tr>
                {{-- @endfor --}}
            </tbody>        
        </table>  
    </div>
</body>
</html>