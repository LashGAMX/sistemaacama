<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/fq/curvaPDF.css')}}">
    <title>Captura PDF</title>
</head>
<body>
    <p id='curvaProcedimiento'>Procedimiento</p>

    <div id="contenidoCurva">
        <?php echo html_entity_decode($textoProcedimiento->Texto);?>
    </div>

    <br>

    <table >
        <thead>
            <tr>
                <th></th>
                
                <th class="nombreHeader">
                    Masas constante
                </th>

                <th></th>
            </tr>
            
            <tr>
                <th class="nombreHeader">
                    No. Matraz
                </th>

                <th class="nombreHeader">
                    Masa cte. 1
                </th>

                <th class="nombreHeader">
                    Masa cte. 2
                </th>                
            </tr>
        </thead>

        <tbody>
            <tr>
                <td class="contenidoBody">
                    PRUEBA
                </td>

                <td class="contenidoBody">
                    PRUEBA
                </td>

                <td class="contenidoBody">
                    PRUEBA
                </td>                
            </tr>                            
        </tbody>
    </table>

    <br>

    <table cellpadding="0" cellspacing="0" bordercolor="#000000">
        <thead>
            <tr>                               
                <th class="nombreHeader" colspan="4">
                    Calentamiento de matraces para masa cte.
                </th>
            </tr>

            <tr>
                <th class="nombreHeader bordesTabla" rowspan="2">
                    Temp de la estufa
                </th>

                <th class="nombreHeader bordesTabla" colspan="2">
                    HORA
                </th>

                <th class="nombreHeader bordesTabla" rowspan="2">
                    Masa cte.
                </th>
            </tr>
            
            <tr>                
                <th class="nombreHeader bordesTabla">
                    Entrada
                </th>

                <th class="nombreHeader bordesTabla">
                    Salida
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
                    1
                </td>
            </tr>
                
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
                    2
                </td>
            </tr>

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
                    3
                </td>
            </tr>
        </tbody>
    </table>

    <br>

    <table cellpadding="0" cellspacing="0" bordercolor="#000000">
        <thead>
            <tr>                
                <th class="nombreHeader" colspan="4">
                    Enfriado de matraces en desecador para masa cte.
                </th>
            </tr>

            <tr>
                <th class="nombreHeader bordesTabla" colspan="3">
                    HORA
                </th>

                <th class="nombreHeader bordesTabla" rowspan="2">
                    Masa cte.
                </th>
            </tr>
            
            <tr>                
                <th class="nombreHeader bordesTabla">
                    Entrada
                </th>

                <th class="nombreHeader bordesTabla">
                    Salida
                </th>

                <th class="nombreHeader bordesTabla">
                    Pesado de
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
                    1
                </td>
            </tr>
                
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
                    2
                </td>
            </tr>

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
                    3
                </td>
            </tr>
        </tbody>
    </table>

    <br>

    <table cellpadding="0" cellspacing="0" bordercolor="#000000">
        <thead>
            <tr>                                
                <th class="nombreHeader" colspan="2">
                    Secado de cartuchos
                </th>
            </tr>

            <tr>
                <th class="nombreHeader bordesTabla" rowspan="2">
                    Temp de la estufa
                </th>

                <th class="nombreHeader bordesTabla" colspan="2">
                    HORA
                </th>                
            </tr>

            <tr>
                <th class="nombreHeader bordesTabla">
                    Entrada
                </th>
                
                <th class="nombreHeader bordesTabla">
                    Salida
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
            </tr>                            
        </tbody>
    </table>

    <br>

    <table cellpadding="0" cellspacing="0" bordercolor="#000000">
        <thead>
            <tr>                
                <th class="nombreHeader" colspan="2">
                    Tiempo de reflujo
                </th>
            </tr>

            <tr>
               <th class="nombreHeader bordesTabla" colspan="2">
                   HORA
                </th> 
            </tr>

            <tr>                
                <th class="nombreHeader bordesTabla">
                    Entrada
                </th>

                <th class="nombreHeader bordesTabla">
                    Salida
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
            </tr>                            
        </tbody>
    </table>

    <br>
    
    <table cellpadding="0" cellspacing="0" bordercolor="#000000">
        
        <thead>
            <tr>                
                <th class="nombreHeader" colspan="2">
                    Enfriado de matraces en
                </th>
            </tr>

            <tr>
                <th class="nombreHeader bordesTabla" colspan="2">
                    HORA
                </th>
            </tr>

            <tr>                
                <th class="nombreHeader bordesTabla">
                    Entrada
                </th>

                <th class="nombreHeader bordesTabla">
                    Salida
                </th>                
            </tr>
        </thead>
        <tbody>
            <tr class="contenidoBody">
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