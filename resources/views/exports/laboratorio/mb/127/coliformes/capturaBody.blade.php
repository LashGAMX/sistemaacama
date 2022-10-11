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
        <?php echo html_entity_decode(@$textoProcedimiento[0]);?>
    </div>
 
    <br>

    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos">
            <tbody>
                <tr>
                    <td class="nombreHeader nombreHeaderBold">
                        Fecha de sembrado
                    </td>                   

                    <td class="tableContent">
                        {{date("d/m/Y", strtotime(@$bitacora->Sembrado))}}
                    </td>
                    
                    <td></td>                                        

                    <td class="nombreHeader nombreHeaderBold">
                        Hora de sembrado
                    </td>

                    <td></td>

                    <td class="tableContent">
                        {{date("H:i:s", strtotime(@$bitacora->Sembrado))}}
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
                    <td class="tableContent">
                        @php
                            $fechaFormateada = date("d/m/Y", strtotime(@$pruebaPresun->Preparacion));
                            echo $fechaFormateada;
                        @endphp
                    </td>
                    
                    <td></td>                  
                    
                    <td class="tableContent">El medio que se utiliza es:</td>
                    <td></td>
                    <td class="tableContent">{{@$pruebaConf->Medio}}</td>                    
                </tr>

                <tr>
                    <td class="tableContent">Para determinar: </td>                    
                    <td class="tableContent">{{@$parametroDeterminar}}<sup>{{@$simbologiaParam->Simbologia}}</sup></td>
                    
                    <td></td>                  
                    
                    <td class="tableContent">Preparado:</td>
                    <td></td>
                    <td class="tableContent">
                        @php
                            $fechaFormateadaPreparado = date("d/m/Y", strtotime(@$pruebaConf->Preparacion));
                            echo $fechaFormateadaPreparado;
                        @endphp
                    </td>                    
                </tr>

                <tr>
                    <td class="tableContent">Fecha y hora de lectura, después <br> 24 hrs. y 48 hrs. de incubación: </td>                    
                    <td class="tableContent">
                        @php
                            $fechaHoraFormateadaP = date("d/m/Y H:i:s", strtotime(@$pruebaPresun->Lectura));
                            echo $fechaHoraFormateadaP;
                        @endphp
                    </td>
                    
                    <td></td>                  
                    
                    <td class="tableContent">Para determinar: </td>
                    <td></td>
                    <td class="tableContent">{{@$parametroDeterminar}}<sup>{{@$simbologiaParam->Simbologia}}</sup></td>                    
                </tr>

                <tr>
                    <td class="tableContent"></td>     
                    <td></td>               
                    <td class="tableContent"></td>
                                                          
                    
                    <td class="tableContent">Fecha y hora de lectura para </td> 
                    <td></td>                   
                    <td class="tableContent">
                        @php
                            $fechaHoraFormateada = date("d/m/Y H:i:s", strtotime(@$pruebaConf->Lectura));
                            echo $fechaHoraFormateada;
                        @endphp
                    </td>                    
                </tr>
            </tbody>                      
        </table>  
    </div>
    
    <br>

    <div id="contenidoCurva">
        <p>Fecha de resiembra de la cepa utilizada: AQUÍ VA LA FECHA de la placa N° AQUÍ VA LA PLACA</p>
        <p>Bitácora AQUÍ VA LA BITÁCORA</p> <br>
    </div>

    <div id="contenidoCurva">
        <span id="curvaProcedimiento">Valoración</span>
        <?php echo html_entity_decode(@$textoProcedimiento[1]);?>
    </div>

    <br>

    
    <table cellpadding="0" cellspacing="0" border-color="#000000">
        <thead>             
            <tr>
                <th style="font-size: 8px" colspan="10">BACTERIAS COLIFORMES FECALES</th>
            </tr>
            <tr> 
                <th colspan="5" style="font-size: 8px">PRESUNTIVA</th>
                <th colspan="5" style="font-size: 8px">CONFIRMATIVA</th>
            </tr> 
            <tr>
                <th style="font-size: 8px">No. de muestra</th>
                <th style="font-size: 8px">Totales de tubos</th>
                <th style="font-size: 8px"> Positivo 24 hrs</th>
                <th style="font-size: 8px">Positivos 48 hrs</th> 
                <th style="font-size: 8px">Resultado NMP/100mL</th>
                <th style="font-size: 8px">Positivos 24 hrs</th>
                <th style="font-size: 8px">Positivos 48 hrs</th>
                <th style="font-size: 8px">Resultado NMP/100mL</th>
                <th style="font-size: 8px"></th>
                <th style="font-size: 8px"></th>
            </tr>                        
        </thead>
        
        <tbody>
 
        </tbody>
    </table>
</body>
</html>