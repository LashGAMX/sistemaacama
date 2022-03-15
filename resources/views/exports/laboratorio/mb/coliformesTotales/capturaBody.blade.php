<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/mb/coliformesTotales/coliformesTotalesPDF.css')}}">
    <title>Captura PDF</title>
</head>
<body>       

    <p id='curvaProcedimiento'>Procedimiento</p>

    <div id="contenidoCurva">
        <?php echo html_entity_decode($textoProcedimiento[0]);?>
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
                        {{@$fechaConFormato}}
                    </td>
                    
                    <td></td>                                        

                    <td class="nombreHeader nombreHeaderBold">
                        Hora de sembrado
                    </td>

                    <td></td>

                    <td class="tableContent">
                        {{@$hora}}
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
                    <td class="tableContent">{{@$parametroDeterminar}}</td>
                    
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
            @for ($i = 0; $i < $dataLength ; $i++)
                <tr>
                    <td class="contenidoBody bordesTabla" rowspan="3">
                        @if (@$data[$i]->Control == 'Estandar')
                            ESTANDAR
                        @elseif(@$data[$i]->Control == 'Blanco')
                            BLANCO
                        @elseif(@$data[$i]->Control == 'Positivo')
                            POSITIVO
                        @elseif(@$data[$i]->Control == 'Negativo')
                            NEGATIVO
                        @else
                            {{@$data[$i]->Folio_servicio}}
                        @endif 
                    </td>

                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Dilucion1}}
                    </td>

                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Presuntiva1}}
                    </td>

                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Presuntiva2}}
                    </td>

                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Presuntiva3}}
                    </td>

                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Presuntiva1 + @$data[$i]->Presuntiva2 + @$data[$i]->Presuntiva3}}
                    </td>

                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Confirmativa1}}
                    </td>

                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Confirmativa2}}
                    </td>

                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Confirmativa3}}
                    </td>

                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Confirmativa1 + @$data[$i]->Confirmativa2 + @$data[$i]->Confirmativa3}}
                    </td>

                    <td class="contenidoBody bordesTabla" rowspan="3">
                        {{@$loteColi[$i]->Resultado}}
                    </td>

                    <td class="contenidoBody bordesTabla" rowspan="3">
                        {{@$loteColi[$i]->Resultado}}
                    </td>

                    <td class="contenidoBody bordesTabla" rowspan="3">
                        PRUEBA
                    </td>

                    <td class="contenidoBody" rowspan="3">
                        @if (@$data[$i]->Liberado == 1)
                            Liberado
                        @elseif(@$data[$i]->Liberado == 0)
                            No liberado
                        @endif 
                    </td>

                    <td class="contenidoBody" rowspan="3">
                        {{@$data[$i]->Control}}
                    </td>
                </tr>
                
                <tr>
                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Dilucion2}}
                    </td>

                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Presuntiva4}}
                    </td>

                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Presuntiva5}}
                    </td>

                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Presuntiva6}}
                    </td>

                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Presuntiva4 + @$data[$i]->Presuntiva5 + @$data[$i]->Presuntiva6}}
                    </td>

                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Confirmativa4}}
                    </td>

                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Confirmativa5}}
                    </td>

                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Confirmativa6}}
                    </td>

                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Confirmativa4 + @$data[$i]->Confirmativa5 + @$data[$i]->Confirmativa6}}
                    </td>       
                </tr>

                <tr>
                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Dilucion3}}
                    </td>

                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Presuntiva7}}
                    </td>

                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Presuntiva8}}
                    </td>

                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Presuntiva9}}
                    </td>

                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Presuntiva7 + @$data[$i]->Presuntiva8 + @$data[$i]->Presuntiva9}}
                    </td>

                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Confirmativa7}}
                    </td>

                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Confirmativa8}}
                    </td>

                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Confirmativa9}}
                    </td>

                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Confirmativa7 + @$data[$i]->Confirmativa8 + @$data[$i]->Confirmativa9}}
                    </td>                          
                </tr>
            @endfor
        </tbody>
    </table>
</body>
</html>