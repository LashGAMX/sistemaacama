<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/mb/coliformes/coliformesPDF.css')}}">
    <title>Captura PDF</title>
</head>
<body>
    <p id='curvaProcedimiento'>Procedimientos</p>

    <div id="contenidoCurva">
        <?php echo html_entity_decode($textoProcedimiento->Texto);?>
    </div>

    <br>
    
    <table cellpadding="0" cellspacing="0" bordercolor="#000000">
        <thead>            
            <tr>
                <th class="nombreHeader bordesTabla">
                    No. de muestra
                </th>

                <th class="nombreHeader bordesTabla" rowspan="3">
                    Diluciones empleadas
                </th>

                <th class="nombreHeader bordesTabla" colspan="3" rowspan="3">
                    Prueba presuntiva
                </th>

                <th class="nombreHeader bordesTabla" rowspan="3">
                    Resultado presuntiva
                </th>

                <th class="nombreHeader bordesTabla" colspan="3" rowspan="3">
                    Prueba confirmativa
                </th>

                <th class="nombreHeader bordesTabla" rowspan="3">
                    N.M.P. Obtenido
                </th>

                <th class="nombreHeader bordesTabla">
                    Resultado NMP/100 mL (Tabla)
                </th>

                <th class="nombreHeader bordesTabla">
                    Resultado NMP/100 mL (Fórmula 1)
                </th>

                <th class="nombreHeader bordesTabla">
                    Resultado NMP/100 mL (Fórmula 2)
                </th>

                <th class="nombreHeader bordesTabla">
                    
                </th>

                <th class="nombreHeader bordesTabla">
                    
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
</body>
</html>