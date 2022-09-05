<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <link rel="stylesheet" href="{{asset('/public/css/laboratorio/mb/coliformesTotales/coliformesTotalesPDF.css')}}"> --}}
    <link rel="stylesheet" href="{{asset('public/css/bootstrap.min.css')}}">
    <title>Captura PDF</title>
</head>
<body>        

    <p id='curvaProcedimiento'>Procedimiento</p>
 
    <div id="contenidoCurva">
        
    </div>

    <br>

    <div id="contenedorTabla">
        {{-- <table autosize="1" class="table table-borderless" id="tablaDatos">
            <tbody>
                <tr>
                    <td class="nombreHeader nombreHeaderBold">
                        Fecha de sembrado
                    </td>                   

                    <td class="tableContent">
                        
                    </td>
                    
                    <td></td>                                        

                    <td class="nombreHeader nombreHeaderBold">
                        Hora de sembrado
                    </td>

                    <td></td>

                    <td class="tableContent">
                        
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
                
                    </td>
                    
                    <td></td>                  
                    
                    <td class="tableContent">El medio que se utiliza es:</td>
                    <td></td>
                    <td class="tableContent"></td>                    
                </tr>

                <tr>
                    <td class="tableContent">Para determinar: </td>                    
                    <td class="tableContent"></td>
                    
                    <td></td>                  
                    
                    <td class="tableContent">Preparado:</td>
                    <td></td>
                    <td class="tableContent">

                    </td>                    
                </tr>

                <tr>
                    <td class="tableContent">Fecha y hora de lectura, después <br> 24 hrs. y 48 hrs. de incubación: </td>                    
                    <td class="tableContent">
    
                    </td>
                    
                    <td></td>                  
                    
                    <td class="tableContent">Para determinar: </td>
                    <td></td>
                    <td class="tableContent">
                       
                    </td>                    
                </tr>

                <tr>
                    <td class="tableContent"></td>     
                    <td></td>               
                    <td class="tableContent"></td>
                                                          
                    
                    <td class="tableContent">Fecha y hora de lectura para </td> 
                    <td></td>                   
                    <td class="tableContent">

                    </td>
                </tr>
            </tbody>                      
        </table>   --}}
    </div>
    
    <br>

    <div id="contenidoCurva">
        <p>Fecha de resiembra de la cepa utilizada: AQUÍ VA LA FECHA de la placa N° AQUÍ VA LA PLACA</p>
        <p>Bitácora AQUÍ VA LA BITÁCORA</p> <br>
    </div>

    <div id="contenidoCurva">
        <span id="curvaProcedimiento">Valoración</span>
        
    </div>
 
    <br>

    <table class="table table-sm table-bordered"> 
        <thead>
            <tr>
                <th  style="font-size: 10px">No. De muestra</th>
                <th  style="font-size: 10px">Dilucion empleadas</th>
                <th  style="font-size: 10px" colspan="3">Prueba presuntiva</th>
                <th  style="font-size: 10px">Resultado presuntiva</th>
                <th  style="font-size: 10px">Prueba confirmativa</th>
                <th  style="font-size: 10px">N.M.P obtenido</th>
                <th  style="font-size: 10px">Resultado NMP/100 mL (Tabla)</th>
                <th  style="font-size: 10px">Resultado NMP/100 mL (Formula 1 )</th>
                <th  style="font-size: 10px">Resultado NMP/100 mL (Formula 2 ) mL (Formula 1 )</th>
            </tr>
            <tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</body>
</html>