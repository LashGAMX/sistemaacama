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

    <div id="contenidoCurva">
        @php
            echo $plantilla[0]->Texto; 
        @endphp
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
                            $fechaFormateada = date("d/m/Y", strtotime(@$bitacora->Preparacion_pre));
                            echo $fechaFormateada;
                        @endphp
                    </td>
                    
                    <td></td>                  
                    
                    <td class="tableContent">El medio que se utiliza es:</td>
                    <td></td>
                    <td class="tableContent">{{@$bitacora->Medio_con}}</td>                    
                </tr>

                <tr>
                    <td class="tableContent">Para determinar: </td>                    
                    <td class="tableContent">{{@$lote->Parametro}}<sup>{{@$simbologiaParam->Simbologia}}</sup></td>
                    
                    <td></td>                  
                    
                    <td class="tableContent">Preparado:</td>
                    <td></td>
                    <td class="tableContent">
                        @php
                            $fechaFormateadaPreparado = date("d/m/Y", strtotime(@$bitacora->Preparacion_con));
                            echo $fechaFormateadaPreparado;
                        @endphp
                    </td>                    
                </tr>

                <tr>
                    <td class="tableContent">Fecha y hora de lectura, después <br> 24 hrs. y 48 hrs. de incubación: </td>                    
                    <td class="tableContent">
                        @php
                            $fechaHoraFormateadaP = date("d/m/Y H:i:s", strtotime(@$bitacora->Lectura_pre));
                            echo $fechaHoraFormateadaP;
                        @endphp
                    </td>
                    
                    <td></td>                  
                    
                    <td class="tableContent">Para determinar: </td>
                    <td></td>
                    <td class="tableContent">{{@$lote->Parametro}}<sup>{{@$simbologiaParam->Simbologia}}</sup></td>                    
                </tr>

                <tr>
                    <td class="tableContent"></td>     
                    <td></td>               
                    <td class="tableContent"></td>
                                                          
                    
                    <td class="tableContent">Fecha y hora de lectura para </td> 
                    <td></td>                   
                    <td class="tableContent">
                        @php
                            $fechaHoraFormateada = date("d/m/Y H:i:s", strtotime(@$bitacora->Lectura_con));
                            echo $fechaHoraFormateada;
                        @endphp
                    </td>                    
                </tr>
            </tbody>                      
        </table>  
    </div>
    
    <br>

    {{-- <div id="contenidoCurva">
        <p>Fecha de resiembra de la cepa utilizada: AQUÍ VA LA FECHA de la placa N° AQUÍ VA LA PLACA</p>
        <p>Bitácora AQUÍ VA LA BITÁCORA</p> <br>
    </div> --}}

    <div id="contenidoCurva">
        
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
                    Prueba presuntiva 24h
                </th>
                <th class="nombreHeader bordesTabla" colspan="3">
                    Prueba presuntiva 48h
                </th>
                <th class="nombreHeader bordesTabla">
                    Resultado presuntiva
                </th>

                <th class="nombreHeader bordesTabla" colspan="3">
                    Prueba confirmativa 24h
                </th>
                <th class="nombreHeader bordesTabla" colspan="3">
                    Prueba confirmativa 48h
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
            @php
                $aux = 3;
            @endphp
            @for ($i = 0; $i < $data->count() ; $i++)
            
                <tr>
                    @switch($data[$i]->Id_control)
                    @case(4)
                    @case(5)
                    @case(8)
                    @case(18)
                        <td class="contenidoBody bordesTabla" >
                            {{@$data[$i]->Codigo}}    
                            {{@$data[$i]->Control}}        
                        </td>
                        @php
                            $aux = 0;
                        @endphp
                        @break
                    @default
                        @php
                             $aux = 3;
                        @endphp
                        @if ($data[$i]->Id_control == 11)
                            <td class="contenidoBody bordesTabla" rowspan="3">
                                {{@$data[$i]->Codigo}}    
                                {{@$data[$i]->Control}}       
                            </td>    
                        @else
                            <td class="contenidoBody bordesTabla" rowspan="3">
                                {{@$data[$i]->Codigo}}    
                            </td>
                        @endif
                    @endswitch
                    

                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Dilucion1}} 
                    </td>

                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Presuntiva1}}</td>
                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Presuntiva2}}</td>
                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Presuntiva3}}</td>
              
                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Presuntiva10}}</td>
                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Presuntiva11}}</td>
                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Presuntiva12}}</td>

                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Presuntiva10 + @$data[$i]->Presuntiva11 + @$data[$i]->Presuntiva12}}                        
                    </td>

                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Confirmativa1}}</td>
                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Confirmativa2}}</td>
                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Confirmativa3}}</td>
                    
                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Confirmativa10}}</td>
                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Confirmativa11}}</td>
                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Confirmativa12}}</td>
                    
                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Confirmativa10 + @$data[$i]->Confirmativa11 + @$data[$i]->Confirmativa12}}
                    </td>

                    @switch($data[$i]->Tipo)
                        @case(1)
                            <td class="contenidoBody bordesTabla" rowspan="{{$aux}}">
                                @if (@$data[$i]->Resultado == 0)
                                    < 3
                                @else
                                {{@$data[$i]->Resultado}}
                                @endif
                            </td>
        
                            <td class="contenidoBody bordesTabla" rowspan="{{$aux}}">
                                --
                            </td>
        
                            <td class="contenidoBody bordesTabla" rowspan="{{$aux}}" style="font-weight: bold">
                                --
                            </td>
                            @break
                        @case(2)
                            <td class="contenidoBody bordesTabla" rowspan="{{$aux}}">
                                --
                            </td>
        
                            <td class="contenidoBody bordesTabla" rowspan="{{$aux}}">
                                @if (@$data[$i]->Resultado == 0)
                                < 3
                            @else
                            {{@$data[$i]->Resultado}}
                            @endif
                            </td>
        
                            <td class="contenidoBody bordesTabla" rowspan="{{$aux}}" style="font-weight: bold">
                                --
                            </td>
                            @break
                        @case(3)
                            <td class="contenidoBody bordesTabla" rowspan="{{$aux}}">
                                --
                            </td>
        
                            <td class="contenidoBody bordesTabla" rowspan="{{$aux}}">
                                --
                            </td>
        
                            <td class="contenidoBody bordesTabla" rowspan="{{$aux}}" style="font-weight: bold">
                                @if (@$data[$i]->Resultado == 0)
                                < 3
                            @else
                            {{@$data[$i]->Resultado}}
                            @endif
                            </td>
                            @break
                        @default
                        <td class="contenidoBody bordesTabla" rowspan="{{$aux}}">
                            --
                        </td>
    
                        <td class="contenidoBody bordesTabla" rowspan="{{$aux}}">
                            --
                        </td>
    
                        <td class="contenidoBody bordesTabla" rowspan="{{$aux}}" style="font-weight: bold">
                            --
                        </td>
                    @endswitch
                  

                    <td class="contenidoBody" rowspan="{{$aux}}">
                        @if (@$data[$i]->Liberado == 1)
                            Liberado
                        @elseif(@$data[$i]->Liberado == 0)
                            No liberado
                        @endif 
                    </td>

                    <td class="contenidoBody" rowspan="{{$aux}}">
                        {{@$data[$i]->Control}}
                    </td>
                </tr>
                
                @switch($data[$i]->Id_control)
                @case(4)
                @case(5)
                @case(8)
                @case(18)
                    
                    @break
                @default
                <tr>
                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Dilucion2}}
                    </td>

                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Presuntiva4}}</td>
                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Presuntiva5}}</td>
                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Presuntiva6}}</td>

                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Presuntiva13}}</td>
                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Presuntiva14}}</td>
                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Presuntiva15}}</td>

                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Presuntiva13 + @$data[$i]->Presuntiva14 + @$data[$i]->Presuntiva15}}                        
                    </td>

                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Confirmativa4}}</td>
                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Confirmativa5}}</td>
                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Confirmativa6}}</td>

                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Confirmativa13}}</td>
                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Confirmativa14}}</td>
                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Confirmativa15}}</td>

                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Confirmativa13 + @$data[$i]->Confirmativa14 + @$data[$i]->Confirmativa15}}
                    </td>       
                </tr>

                <tr>
                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Dilucion3}}
                    </td>

                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Presuntiva7}}</td>
                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Presuntiva8}}</td>
                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Presuntiva9}}</td>

                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Presuntiva16}}</td>
                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Presuntiva17}}</td>
                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Presuntiva18}}</td>

                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Presuntiva16 + @$data[$i]->Presuntiva17 + @$data[$i]->Presuntiva18}}                    
                    </td>

                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Confirmativa7}}</td>
                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Confirmativa8}}</td>
                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Confirmativa9}}</td>

                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Confirmativa16}}</td>
                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Confirmativa17}}</td>
                    <td class="contenidoBody bordesTabla">{{@$data[$i]->Confirmativa18}}</td>
    
                    <td class="contenidoBody bordesTabla">
                        {{@$data[$i]->Confirmativa16 + @$data[$i]->Confirmativa17 + @$data[$i]->Confirmativa18}}
                    </td>                          
                </tr>
                @endswitch

            @endfor
        </tbody>
    </table>
</body>
</html>