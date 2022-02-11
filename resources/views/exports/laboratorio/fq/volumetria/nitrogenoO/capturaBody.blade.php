<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/fq/volumetria/nitrogenoO/nitrogenoOPDF.css')}}">
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
                    <th class="nombreHeader" colspan="8">
                        Resultado de las muestras
                    </th>                    
                </tr>                

                <tr>
                    <th class="tableCabecera anchoColumna">No. de muestra</th>
                    <th class="tableCabecera anchoColumna">VOL. TITULANTE BLANCO</th>
                    <th class="tableCabecera anchoColumna">VOL. MUESTRA</th>
                    <th class="tableCabecera anchoColumna">VOL. TITULANTE MUESTRA</th>
                    <th class="tableCabecera anchoColumna">NITRÓGENO ORGÁNICO (N-Org) mg/L</th>
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
                        <td class="tableContent">PRUEBA</td>
                        <td class="tableContent">{{@$data[$i]->Vol_muestra}}</td>
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

    <div class="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="">
            <tbody>                              
                <tr>
                    <td class="tableContent2">MILILITROS TITULADOS DEL BLANCO</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">0.5</td>
                </tr>

                <tr>
                    <td class="tableContent2">RESULTADO BLANCO</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">0.5</td>
                </tr>

                <tr>
                    <td class="tableContent2">MILILITROS 1 TITULADOS DE H2SO4</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">26.7</td>
                </tr>

                <tr>
                    <td class="tableContent2">MILILITROS 2 TITULADOS DE H2SO4 2</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">26.7</td>
                </tr>                

                <tr>
                    <td class="tableContent2">MILILITROS 3 TITULADOS DE H2SO4 3</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">26.7</td>
                </tr>

                <tr>
                    <td class="tableContent2">RESULTADO MOLARIDAD REAL</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">0.011</td>
                </tr>
            </tbody>    
        </table>  
    </div>

    <div id="contenidoCurva">
        <span id="curvaProcedimiento">Valoración</span> <br>
        Valoración <?php echo html_entity_decode($textoProcedimiento[1]);?>
    </div>
</body>
</html>