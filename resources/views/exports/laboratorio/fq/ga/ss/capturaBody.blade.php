<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/fq/ss/ssPDF.css')}}">
    <title>Captura PDF</title>
</head>
<body>
    <p id='curvaProcedimiento'>Procedimiento</p>

    <div id="contenidoCurva">
        <?php echo html_entity_decode($textoProcedimiento->Texto);?>
    </div>

    <br>

    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos">
            <thead>

                <tr>
                    <th class="nombreHeader" colspan="10">
                        Resultado de las muestras
                    </th>                    
                </tr>                

                <tr>
                    <th class="tableCabecera anchoColumna">No. de muestras</th>
                    <th class="tableCabecera anchoColumna">No. de cono IMHOFF</th>
                    <th class="tableCabecera anchoColumna">SOLIDOS SEDIMENTABLES (S.S)</th>
                    <th class="tableCabecera anchoColumna">LLEGADA 2°C a 8°C</th>
                    <th class="tableCabecera anchoColumna">TEMPERATURA DE LA MUESTRA °C AL ANALIZAR (Temp. Amb)</th>
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
                        <td class="tableContent">PRUEBA</td>                        
                    </tr>                
                {{-- @endfor --}}
            </tbody>        
        </table>  
    </div>
</body>
</html>