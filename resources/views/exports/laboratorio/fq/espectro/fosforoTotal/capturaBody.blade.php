<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/fq/espectro/fosforoTotal/fosforoTotalPDF.css')}}">
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
                    <th class="nombreHeader" colspan="10">
                        Resultado de las muestras
                    </th>                    
                </tr>                

                <tr>
                    <th class="tableCabecera anchoColumna">No. de muestra</th>
                    <th class="tableCabecera anchoColumna">Volumen de muestra (mL)</th>
                    <th class="tableCabecera anchoColumna">Abs 1</th>
                    <th class="tableCabecera anchoColumna">Abs 2</th>
                    <th class="tableCabecera anchoColumna">Abs 3</th>
                    <th class="tableCabecera anchoColumna">Abs Promedio</th>
                    <th class="tableCabecera anchoColumna">FOSFORO TOTAL (P) mg/L</th>                    
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
                        <td class="tableContent">PRUEBA</td>
                        <td class="tableContent">PRUEBA</td>
                    </tr>                
                {{-- @endfor --}}
            </tbody>        
        </table>  
    </div>

    <br>

    <div class="contenedorSexto">                
        <span><br> Absorbancia B1: PRUEBA</span> <br>
        <span>Absorbancia B2: PRUEBA</span> <br>
        <span>Absorbancia B3: PRUEBA</span> <br>
        <span>RESULTADO BLANCO: PRUEBA</span>
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
                        <td class="tableContent">PRUEBA</td>                        
                        <td class="tableCabecera">Fecha de preparación: </td>
                        <td class="tableContent">PRUEBA</td>                                                
                    </tr>

                    <tr>
                        <td class="tableCabecera">m = </td>
                        <td class="tableContent">PRUEBA</td>                        
                        <td class="tableCabecera">Límite de cuantificación: </td>
                        <td class="tableContent">PRUEBA</td>
                    </tr>

                    <tr>
                        <td class="tableCabecera">r = </td>
                        <td class="tableContent">PRUEBA</td>
                    </tr>
                {{-- @endfor --}}
            </tbody>        
        </table>  
    </div>
</body>
</html>