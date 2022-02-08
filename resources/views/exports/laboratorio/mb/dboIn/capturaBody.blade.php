<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/mb/dboIn/dboInPDF.css')}}">
    <title>Captura PDF</title>
</head>
<body>   
    <p id='curvaProcedimiento'>Procedimiento</p>

    <div id="contenidoCurva">
        <?php echo html_entity_decode($textoProcedimiento[0]);?>
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
                    Vol. de la Muestra (ml)
                </th>

                <th class="nombreHeader bordesTabla">
                    % de dilución expresado en decimales
                </th>

                <th class="nombreHeader bordesTabla">
                    NO. DE BOTELLA INICIAL
                </th>

                <th class="nombreHeader bordesTabla">
                    OXIGENO DISUELTO INICIAL
                </th>

                <th class="nombreHeader bordesTabla">
                    NO. BOTELLA FINAL
                </th>

                <th class="nombreHeader bordesTabla">
                    OXIGENO DISUELTO AL 5to. DIA
                </th>

                <th class="nombreHeader bordesTabla">
                    PH INICIAL
                </th>

                <th class="nombreHeader bordesTabla">
                    PH FINAL
                </th>

                <th class="nombreHeader bordesTabla">
                    DBO5 mg/L
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

                <td class="contenidoBody bordesTabla">
                    PRUEBA
                </td>

                <td class="contenidoBody bordesTabla">
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

    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos" style="width: 100%">
            <tbody>
                <tr>
                    <th></th>

                    <th></th>
                    
                    <th class="tableContent nombreHeaderBold" colspan="2">
                        AIREAR APROX. 1 HORA
                    </th>                   

                    <th></th>                                       

                    <th class="tableContent nombreHeaderBold" colspan="2">
                        ESTANDAR BIT RE-12-001-1A-13
                    </th>                    
                </tr>

                <tr>
                    <td class="tableContent nombreHeaderBold">
                        Cantidad de agua de dilucion
                    </td>

                    <td class="tableContent">AQUÍ VA LA CANTIDAD</td>
                    
                    <td></td>

                    <td>
                        <span class="tableContent nombreHeaderBold">DE</span> <span class="tableContent">AQUÍ VA LA HORA</span>
                    </td>

                    <td>
                        <span class="tableContent nombreHeaderBold">A</span> <span class="tableContent">AQUÍ VA LA HORA</span>
                    </td>

                    <td></td>

                    <td>
                        <span class="tableContent nombreHeaderBold">PAG</span> <span class="tableContent">AQUÍ VA LA PAG</span>
                    </td>

                    <td>
                        <span class="tableContent nombreHeaderBold">N.</span> <span class="tableContent">AQUÍ VA LA N</span>
                    </td>                
                </tr>                

                <tr>                
                    <td>
                        <span class="tableContent nombreHeaderBold">&nbsp;Disoluciones preparadas el día: </span> <span class="tableContent">AQUÍ VA EL DÍA</span>
                    </td>
                </tr>
            </tbody>                      
        </table>          
    </div>    

    <div id="contenidoCurva">
        <span id="curvaProcedimiento">Valoración</span>
        <?php echo html_entity_decode($textoProcedimiento[1]);?>
    </div>
</body>
</html>