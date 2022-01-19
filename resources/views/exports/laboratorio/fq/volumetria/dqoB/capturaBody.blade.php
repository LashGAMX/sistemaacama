<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/fq/volumetria/dqoB/dqoBPDF.css')}}">
    <title>Captura PDF</title>
</head>
<body>
    <p id='curvaProcedimiento'>Procedimiento</p>

    <div id="contenidoCurva">
        <?php echo html_entity_decode($textoProcedimiento->Texto);?>
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
                {{-- @for ($i = 0; $i < 100 ; $i++) --}}
                    <tr>
                        <td class="tableContent">PRUEBA</td>
                        <td class="tableContent">PRUEBA</td>
                        <td class="tableContent">PRUEBA</td>
                        <td class="tableContent">PRUEBA</td>
                        <td class="tableContent">PRUEBA</td>
                        <td class="tableContent">PRUEBA</td>
                        <td class="tableContent">PRUEBA</td>                                              
                    </tr>                
                {{-- @endfor --}}
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
                    <td class="tableContent2">8.1</td>
                </tr>

                <tr>
                    <td class="tableContent2">RESULTADO BLANCO</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">8.1</td>
                </tr>

                <tr>
                    <td class="tableContent2">VOLUMEN DE FAS</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">20</td>
                </tr>

                <tr>
                    <td class="tableContent2">VOLUMEN FAS 2</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">20.1</td>
                </tr>                

                <tr>
                    <td class="tableContent2">VOLUMEN FAS 3</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">20</td>
                </tr>

                <tr>
                    <td class="tableContent2">RESULTADO MOLARIDAD REAL</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">0.012</td>
                </tr>
            </tbody>    
        </table>  
    </div>
</body>
</html>