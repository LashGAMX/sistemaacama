<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/fq/volumetria/dqoA/dqoAPDF.css')}}">
    <title>Captura PDFs</title>
</head>
<body>    

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
                        <td class="tableContent">{{@$data[$i]->Folio_servicio}}</td>
                        <td class="tableContent">{{@$data[$i]->Vol_muestra}}</td>
                        <td class="tableContent">{{@$data[$i]->Titulo_blanco}}</td>
                        <td class="tableContent">{{@$data[$i]->Resultado}}</td>
                        <td class="tableContent">{{@$data[$i]->Observacion}}</td>
                        <td class="tableContent">{{@$data[$i]->Descripcion}}</td>
                        <td class="tableContent">{{@$data[$i]->Control}}</td>                                              
                    </tr>                
                @endfor
            </tbody>        
        </table>  
    </div>

    <br>

    <div class="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="">
            <tbody>                              
                <tr>
                    <td class="tableContent2">MILILITROS TITULADOS</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">10</td>
                </tr>

                <tr>
                    <td class="tableContent2">RESULTADO BLANCO</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">10</td>
                </tr>

                <tr>
                    <td class="tableContent2">VOLUMEN TITULADO DE FAS 1</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">20.1</td>
                </tr>

                <tr>
                    <td class="tableContent2">VOLUMEN TITULADO DE FAS 2</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">20.1</td>
                </tr>

                <tr>
                    <td class="tableContent2">VOLUMEN TITULADO DE FAS 3</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">20</td>
                </tr>

                <tr>
                    <td class="tableContent2">RESULTADO MOLARIDAD REAL</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">0.12</td>
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