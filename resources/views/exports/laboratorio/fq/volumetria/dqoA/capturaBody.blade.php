<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/fq/volumetria/dqoA/dqoAPDF.css')}}">
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
                    <th class="nombreHeader" colspan="7">
                        Resultado de las muestras
                    </th>                    
                </tr>                

                <tr>
                    <th class="tableCabecera anchoColumna">No. de muestras</th>
                    <th class="tableCabecera anchoColumna">Volumen de muestra (mL)</th>
                    <th class="tableCabecera anchoColumna">Volumen del titulante</th>
                    <th class="tableCabecera anchoColumna">DQO mg/L</th>
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
                        <td class="tableContent">{{@$data[$i]->Titulo_blanco}}</td>
                        <td class="tableContent">
                            @php
                                if(@$data[$i]->Resultado > @$limiteDqo->Limite)
                                {
                                    echo @$data[$i]->Resultado;
                                }else{
                                    echo "< " . @$limiteDqo->Limite;
                                }
                            @endphp
                        </td>
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

    <div class="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="">
            <tbody>                              
                <tr>
                    <td class="tableContent2">MILILITROS TITULADOS</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">{{@$valoracion->Vol_k2}}</td>
                </tr>

                <tr>
                    <td class="tableContent2">RESULTADO BLANCO</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">{{@$valoracion->Blanco}}</td>
                </tr>

                <tr>
                    <td class="tableContent2">VOLUMEN TITULADO DE FAS 1</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">{{$valoracion->Vol_titulado1}}</td>
                </tr>

                <tr>
                    <td class="tableContent2">VOLUMEN TITULADO DE FAS 2</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">{{$valoracion->Vol_titulado2}}</td>
                </tr>

                <tr>
                    <td class="tableContent2">VOLUMEN TITULADO DE FAS 3</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">{{$valoracion->Vol_titulado3}}</td>
                </tr>

                <tr>
                    <td class="tableContent2">RESULTADO MOLARIDAD REAL</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">{{$valoracion->Resultado}}</td>
                </tr>
            </tbody>    
        </table>  
    </div>

    <div id="contenidoCurva">
        <span id="curvaProcedimiento">Valoración</span> <br>
        <?php echo html_entity_decode($textoProcedimiento[1]);?>
    </div>
</body>
</html>