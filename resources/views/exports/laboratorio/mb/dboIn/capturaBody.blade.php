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

    <br>

    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos">
            <tbody>
                <tr>
                    <th></th>
                    
                    <th class="nombreHeader nombreHeaderBold" colspan="2">
                        AIREAR APROX. 1 HORA
                    </th>                   

                    <th></th>                                        

                    <th class="nombreHeader nombreHeaderBold" colspan="2">
                        ESTANDAR BIT RE-12-001-1A-13
                    </th>                    
                </tr>

                <tr>
                    <td class="nombreHeader nombreHeaderBold">
                        Cantidad de agua de dilucion
                    </td>

                    <td class="tableContent">AQUÍ VA LA CANTIDAD</td>                                      

                    <td class="nombreHeader">
                        DE <span>AQUÍ VA LA HORA</span>
                    </td>
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
                    <td class="tableContent">Fecha y hora de lectura, después <br> 24 hrs. y 48 hrs. de incubación: </td>                    
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
                                                          
                    
                    <td class="tableContent">Fecha y hora de lectura para </td> 
                    <td></td>                   
                    <td class="tableContent">AQUÍ VA LA FECHA Y HORA</td>                    
                </tr>
            </tbody>                      
        </table>  
    </div>
    
    <br>

    <div id="contenidoCurva">
        <p>Fecha de resiembra de la cepa utilizada: AQUÍ VA LA FECHA de la placa N° AQUÍ VA LA PLACA</p>
        <p>Bitácora AQUÍ VA LA BITÁCORA</p>
    </div>
</body>
</html>