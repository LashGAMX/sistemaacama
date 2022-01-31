<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/fq/volumetria/cloroR/cloroRPDF.css')}}">
    <title>Captura PDF</title>
</head>
<body>   
    <p id='curvaProcedimiento'>Procedimientos</p>

    <div id="contenidoCurva">
        <?php echo html_entity_decode($textoProcedimiento->Texto);?>
    </div>

    <table cellpadding="0" cellspacing="0" border-color="#000000">
        <thead>  
            <tr>
                <th class="nombreHeader" colspan="13">
                    Resultado de las muestras
                </th>
            </tr>
            
            <tr>
                <th class="nombreHeader bordesTabla">
                    No. de muestra
                </th>

                <th class="nombreHeader bordesTabla">
                    Volumen de muestra (mL)
                </th>

                <th class="nombreHeader bordesTabla">
                    Vol. Titulante (mL)
                </th>

                <th class="nombreHeader bordesTabla">
                    CLORO RESIDUAL mg/L
                </th>

                <th class="nombreHeader bordesTabla">
                    pH inicial de la
                </th>

                <th class="nombreHeader bordesTabla">
                    pH final de la muestra
                </th>

                <th class="nombreHeader bordesTabla">
                    Observaciones
                </th>                

                <th class="nombreHeader">
                    
                </th>

                <th class="nombreHeader">
                    
                </th>
            </tr>                        
        </thead>
        
        <tbody>
            <tr>
                <td class="contenidoBody bordesTabla">
                    PRUEBA
                </td>

                <td class="contenidoBody bordesTabla">
                    PRUEBA
                </td>

                <td class="contenidoBody bordesTabla">
                    PRUEBA
                </td>

                <td class="contenidoBody bordesTabla">
                    PRUEBA
                </td>

                <td class="contenidoBody bordesTabla">
                    PRUEBA
                </td>

                <td class="contenidoBody bordesTabla">
                    PRUEBA
                </td>

                <td class="contenidoBody bordesTabla">
                    PRUEBA
                </td>

                <td class="contenidoBody bordesTabla">
                    PRUEBA
                </td>

                <td class="contenidoBody bordesTabla">
                    PRUEBA
                </td>                        
            </tr>                                    
        </tbody>
    </table>

    <br>

    <div class="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="">
            <tbody>                              
                <tr>
                    <td class="tableContent2 anchoColumna">mL TITULADOS DE TIOSULFATO DE SODIO (1)</td>
                    <td class=""></td>
                    <td class="tableContent2 anchoColumna">9.9</td>
                </tr>                

                <tr>
                    <td class="tableContent2 anchoColumna">mL TITULADOS DE TIOSULFATO DE SODIO (2)</td>
                    <td class=""></td>
                    <td class="tableContent2 anchoColumna">9.9</td>
                </tr>                

                <tr>
                    <td class="tableContent2 anchoColumna">mL TITULADOS DE TIOSULFATO DE SODIO (3)</td>
                    <td class=""></td>
                    <td class="tableContent2 anchoColumna">9.9</td>
                </tr>                

                <tr>
                    <td class="tableContent2 anchoColumna">RESULTADO NORMALIDAD REAL</td>
                    <td class=""></td>
                    <td class="tableContent2 anchoColumna">0.01</td>
                </tr>
            </tbody>    
        </table>  
    </div>
</body>
</html>