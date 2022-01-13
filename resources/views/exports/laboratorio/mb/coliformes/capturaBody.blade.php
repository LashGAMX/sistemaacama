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
    <table cellpadding="0" cellspacing="0" border-color="#000000">
        <thead>            
            <tr>
                <th class="nombreHeader bordesTabla">
                    No. de muestra
                </th>

                <th class="nombreHeader bordesTabla">
                    Diluciones empleadas
                </th>

                <th class="nombreHeader bordesTabla" colspan="3">
                    Prueba presuntiva
                </th>

                <th class="nombreHeader bordesTabla">
                    Resultado presuntiva
                </th>

                <th class="nombreHeader bordesTabla" colspan="3">
                    Prueba confirmativa
                </th>

                <th class="nombreHeader bordesTabla">
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

                <th class="nombreHeader">
                    
                </th>

                <th class="nombreHeader">
                    
                </th>
            </tr>                        
        </thead>
        
        <tbody>
            <tr>
                <td class="contenidoBody bordesTabla" rowspan="3">
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

                <td class="contenidoBody bordesTabla" rowspan="3">
                    PRUEBA
                </td>

                <td class="contenidoBody bordesTabla" rowspan="3">
                    PRUEBA
                </td>

                <td class="contenidoBody bordesTabla" rowspan="3">
                    PRUEBA
                </td>

                <td class="contenidoBody" rowspan="3">
                    PRUEBA
                </td>

                <td class="contenidoBody" rowspan="3">
                    PRUEBA
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

    <p id='curvaProcedimiento'>Procedimiento</p>

    <div id="contenidoCurva">
        <?php echo html_entity_decode($textoProcedimiento->Texto);?>
    </div>

    <br>

    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos" {{-- style="width: 100%" --}}>
            <tbody>
                <tr>
                    <td class="nombreHeader nombreHeaderBold">
                        Fecha de sembrado
                    </td>                   

                    <td class="tableContent">
                        AQUÍ VA LA FECHA
                    </td>
                    
                    <td></td>                                        

                    <td class="nombreHeader nombreHeaderBold">
                        Hora de sembrado
                    </td>

                    <td></td>

                    <td class="tableContent">
                        AQUÍ VA LA HORA
                    </td>
                </tr>

                <tr>
                    <th class="nombreHeader" colspan="2">
                        Prueba presuntiva
                    </th>                    

                    <td></td>                                      

                    <th class="nombreHeader" colspan="3">
                        Prueba confirmativa
                    </th>
                </tr>                

                <tr>
                    <td class="tableContent">Caldo lactosado se prepara el día: </td>                    
                    <td class="tableContent">AQUÍ VA LA FECHA</td>
                    
                    <td></td>                  
                    
                    <td class="tableContent">El medio que se utiliza es:</td>
                    <td></td>
                    <td class="tableContent">AQUÍ VA EL MEDIO</td>                    
                </tr>

                <tr>
                    <td class="tableContent">Para determinar: </td>                    
                    <td class="tableContent">AQUÍ VA COLIFORMES FECALES</td>
                    
                    <td></td>                  
                    
                    <td class="tableContent">Preparado:</td>
                    <td></td>
                    <td class="tableContent">AQUÍ VA LA FECHA</td>                    
                </tr>

                <tr>
                    <td class="tableContent">Fecha y hora de lectura, después 24 hrs. y 48 hrs. de incubación: </td>                    
                    <td class="tableContent">AQUÍ VA LA FECHA Y HORA</td>
                    
                    <td></td>                  
                    
                    <td class="tableContent">Para determinar: </td>
                    <td></td>
                    <td class="tableContent">AQUÍ VA COLIFORMES FECALES</td>                    
                </tr>

                <tr>
                    <td class="tableContent"></td>
                    <td></td>
                    <td class="tableContent"></td>
                    
                    <td></td>                  
                    
                    <td class="tableContent">Fecha y hora de lectura para </td>
                    <td></td>
                    <td class="tableContent">AQUÍ VA LA FECHA Y HORA</td>                    
                </tr>
            </tbody>
                      
        </table>  
    </div>
</body>
</html>