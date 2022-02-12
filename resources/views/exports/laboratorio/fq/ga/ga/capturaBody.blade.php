2<!DOCTYPE html>
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
        <?php echo html_entity_decode($textoProcedimiento[0]);?>
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
            @for ($i = 0; $i < @$matracesLength ; $i++)
                <tr>
                    <td class="contenidoBody">
                        {{@$matraces[$i]->Num_serie}}
                    </td>

                    <td class="contenidoBody">
                        {{@$matraces[$i]->Min}}
                    </td>

                    <td class="contenidoBody">
                        {{@$matraces[$i]->Max}}
                    </td>                
                </tr>
            @endfor                                        
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
                    {{@$calMatraces[0]->Temperatura}}
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$calMatraces[0]->Entrada}}
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$calMatraces[0]->Salida}}
                </td>

                <td class="contenidoBody bordesTabla">
                    1
                </td>
            </tr>
                
            <tr>
                <td class="contenidoBody bordesTabla">
                    {{@$calMatraces[1]->Temperatura}}
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$calMatraces[1]->Entrada}}
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$calMatraces[1]->Salida}}
                </td>

                <td class="contenidoBody bordesTabla">
                    2
                </td>
            </tr>

            <tr>
                <td class="contenidoBody bordesTabla">
                    {{@$calMatraces[2]->Temperatura}}
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$calMatraces[2]->Entrada}}
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$calMatraces[2]->Salida}}
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
                    {{@$enfMatraces[0]->Entrada}}
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$enfMatraces[0]->Salida}}
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$enfMatraces[0]->Pesado_matraz}}
                </td>

                <td class="contenidoBody bordesTabla">
                    1
                </td>
            </tr>
                
            <tr>
                <td class="contenidoBody bordesTabla">
                    {{@$enfMatraces[1]->Entrada}}
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$enfMatraces[1]->Salida}}
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$enfMatraces[1]->Pesado_matraz}}
                </td>

                <td class="contenidoBody bordesTabla">
                    2
                </td>
            </tr>

            <tr>
                <td class="contenidoBody bordesTabla">
                    {{@$enfMatraces[2]->Entrada}}
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$enfMatraces[2]->Salida}}
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$enfMatraces[2]->Pesado_matraz}}
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
                    {{@$secCartuchos->Temperatura}}
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$secCartuchos->Entrada}}
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$secCartuchos->Salida}}
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
                    {{@$tiempoReflujo->Entrada}}
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$tiempoReflujo->Salida}}
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
                    {{@$enfMatraz->Entrada}}
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$enfMatraz->Entrada}}
                </td>                              
            </tr>                            
        </tbody>
    </table>

    <br>

    <div id="contenidoCurva">
        <span id="curvaProcedimiento">Valoración / Observación</span>
        <?php echo html_entity_decode($textoProcedimiento[1]);?>
    </div>
</body>
</html>