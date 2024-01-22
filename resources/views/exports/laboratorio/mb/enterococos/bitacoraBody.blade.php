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
        echo @$plantilla[0]->Texto;
        @endphp
    </div>
    <br>
    <div id="contenedorTabla">

 
    <br>

    <table cellpadding="0" cellspacing="0" border-color="#000000">
        <thead>
            <tr>
                <th class="nombreHeader bordesTabla"   style="font-size: 10px" rowspan="2">No. De muestra</th>
                <th class="nombreHeader bordesTabla"   style="font-size: 10px" rowspan="2">Dilucion empleadas</th>
                <th class="nombreHeader bordesTabla"   style="font-size: 10px" colspan="6">Prueba presuntiva</th>
                <th class="nombreHeader bordesTabla"   style="font-size: 10px" rowspan="2">Resultado presuntiva</th>
                <th class="nombreHeader bordesTabla"   style="font-size: 10px" colspan="6">Prueba confirmativa</th>
                <th class="nombreHeader bordesTabla"   style="font-size: 10px" rowspan="2">N.M.P obtenido</th>
                <th class="nombreHeader bordesTabla"   style="font-size: 10px" rowspan="2">Resultado NMP/100 mL (Tabla)</th>
                <th class="nombreHeader bordesTabla"   style="font-size: 10px" rowspan="2">Resultado NMP/100 mL (Formula 1 )</th>
                <th class="nombreHeader bordesTabla"   style="font-size: 10px" rowspan="2">Resultado NMP/100 mL (Formula 2 ) mL (Formula 1 )</th>
                <th></th> 
            </tr>
            <tr>
                <th class="nombreHeader bordesTabla" style="font-size: 8px" colspan="3">24 horas</th>
                <th class="nombreHeader bordesTabla" style="font-size: 8px" colspan="3">48 horas</th>
                <th class="nombreHeader bordesTabla" style="font-size: 8px" colspan="3">Resultados con agar selectivo 24 horas, registra en las columnas el número de placas que presentan crecimiento de Enterococos (ver morfología colonial)</th>
                <th class="nombreHeader bordesTabla" style="font-size: 8px" colspan="3">Caldo BHI 48 horas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($loteDetalleControles as $item)
            <tr> 
                <td class="contenidoBody bordesTabla">
                    {{$item->Codigo}}
                    <br>
                    {{$item->Control}}
                </td>

                <td class="contenidoBody bordesTabla">{{$item->Dilucion1}}</td>
                <td class="contenidoBody bordesTabla">{{$item->Presuntiva11}}</td>
                <td class="contenidoBody bordesTabla">{{$item->Presuntiva12}}</td>
                <td class="contenidoBody bordesTabla">{{$item->Presuntiva13}}</td>

                <td class="contenidoBody bordesTabla">{{$item->Presuntiva21}}</td>
                <td class="contenidoBody bordesTabla">{{$item->Presuntiva22}}</td>
                <td class="contenidoBody bordesTabla">{{$item->Presuntiva23}}</td>
                <td class="contenidoBody bordesTabla">
                    {{($item->Presuntiva21 + $item->Presuntiva22 + $item->Presuntiva23)}}
                </td>
                <td class="contenidoBody bordesTabla">{{$item->Confirmativa11}}</td>
                <td class="contenidoBody bordesTabla">{{$item->Confirmativa11}}</td>
                <td class="contenidoBody bordesTabla">{{$item->Confirmativa12}}</td>

                <td class="contenidoBody bordesTabla">{{$item->Confirmativa21}}</td>
                <td class="contenidoBody bordesTabla">{{$item->Confirmativa22}}</td>
                <td class="contenidoBody bordesTabla">{{$item->Confirmativa23}}</td>
                <td class="contenidoBody bordesTabla">{{($item->Confirmativa21 + $item->Confirmativa22 + $item->Confirmativa23)}}</td>
                <td class="contenidoBody bordesTabla">
                    @if (@$item->Resultado == 0)
                        < 3
                    @else
                    {{@$item->Resultado}}
                    @endif
                </td>

                <td class="contenidoBody bordesTabla" >
                    --
                </td>

                <td class="contenidoBody bordesTabla"  style="font-weight: bold">
                    --
                </td>
                <td class="contenidoBody bordesTabla"  style="font-weight: bold;font-size: 10px">
                    {{$item->Control}}
                </td>
            </tr>
            @endforeach
            @foreach ($loteDetalle as $item)
                <tr> 
                    @switch($item->Id_control)
                        @case(1)
                            <td class="contenidoBody bordesTabla" rowspan="3">{{$item->Codigo}}</td>
                            @break
                        @default
                        <td class="contenidoBody bordesTabla" rowspan="3">
                                {{$item->Codigo}}
                                <br>
                                {{$item->Control}}
                            </td>
                    @endswitch
                    <td class="contenidoBody bordesTabla">{{$item->Dilucion1}}</td>
                    <td class="contenidoBody bordesTabla">{{$item->Presuntiva11}}</td>
                    <td class="contenidoBody bordesTabla">{{$item->Presuntiva12}}</td>
                    <td class="contenidoBody bordesTabla">{{$item->Presuntiva13}}</td>

                    <td class="contenidoBody bordesTabla">{{$item->Presuntiva21}}</td>
                    <td class="contenidoBody bordesTabla">{{$item->Presuntiva22}}</td>
                    <td class="contenidoBody bordesTabla">{{$item->Presuntiva23}}</td>
                    <td class="contenidoBody bordesTabla">
                        {{($item->Presuntiva21 + $item->Presuntiva22 + $item->Presuntiva23)}}
                    </td>
                    <td class="contenidoBody bordesTabla">{{$item->Confirmativa11}}</td>
                    <td class="contenidoBody bordesTabla">{{$item->Confirmativa11}}</td>
                    <td class="contenidoBody bordesTabla">{{$item->Confirmativa12}}</td>

                    <td class="contenidoBody bordesTabla">{{$item->Confirmativa21}}</td>
                    <td class="contenidoBody bordesTabla">{{$item->Confirmativa22}}</td>
                    <td class="contenidoBody bordesTabla">{{$item->Confirmativa23}}</td>
                    <td class="contenidoBody bordesTabla">{{($item->Confirmativa21 + $item->Confirmativa22 + $item->Confirmativa23)}}</td>
                    @switch($item->Tipo)
                    @case(1)
                        <td class="contenidoBody bordesTabla" rowspan="3">
                            @if (@$item->Resultado == 0)
                                < 3
                            @else
                            {{@$item->Resultado}}
                            @endif
                        </td>
    
                        <td class="contenidoBody bordesTabla" rowspan="3">
                            --
                        </td>
    
                        <td class="contenidoBody bordesTabla" rowspan="3" style="font-weight: bold">
                            --
                        </td>
                        @break
                    @case(2)
                        <td class="contenidoBody bordesTabla" rowspan="3">
                            --
                        </td>
    
                        <td class="contenidoBody bordesTabla" rowspan="3">
                            {{@$item->Resultado}}
                        </td>
    
                        <td class="contenidoBody bordesTabla" rowspan="3" style="font-weight: bold">
                            --
                        </td>
                        @break
                    @case(3)
                        <td class="contenidoBody bordesTabla" rowspan="3">
                            --
                        </td>
    
                        <td class="contenidoBody bordesTabla" rowspan="3">
                            --
                        </td>
    
                        <td class="contenidoBody bordesTabla" rowspan="3" style="font-weight: bold">
                            {{@$item->Resultado}}
                        </td>
                        @break
                    @default
                    <td class="contenidoBody bordesTabla" rowspan="3">
                        --
                    </td>

                    <td class="contenidoBody bordesTabla" rowspan="3">
                        --
                    </td>

                    <td class="contenidoBody bordesTabla" rowspan="3" style="font-weight: bold">
                        --
                    </td>
                @endswitch
                    <td class="contenidoBody bordesTabla" rowspan="3" style="font-weight: bold;font-size: 10px">
                        {{$item->Control}}
                    </td>
                </tr>
                <tr>     
                    
                    <td class="contenidoBody bordesTabla">{{$item->Dilucion2}}</td>
                    <td class="contenidoBody bordesTabla">{{$item->Presuntiva14}}</td>
                    <td class="contenidoBody bordesTabla">{{$item->Presuntiva15}}</td>
                    <td class="contenidoBody bordesTabla">{{$item->Presuntiva16}}</td>

                    <td class="contenidoBody bordesTabla">{{$item->Presuntiva24}}</td>
                    <td class="contenidoBody bordesTabla">{{$item->Presuntiva25}}</td>
                    <td class="contenidoBody bordesTabla">{{$item->Presuntiva26}}</td>

                    <td class="contenidoBody bordesTabla">{{($item->Presuntiva24 + $item->Presuntiva25 + $item->Presuntiva26)}}</td>
                    <td class="contenidoBody bordesTabla">{{$item->Confirmativa14}}</td>
                    <td class="contenidoBody bordesTabla">{{$item->Confirmativa15}}</td>
                    <td class="contenidoBody bordesTabla">{{$item->Confirmativa16}}</td>

                    <td class="contenidoBody bordesTabla">{{$item->Confirmativa24}}</td>
                    <td class="contenidoBody bordesTabla">{{$item->Confirmativa25}}</td>
                    <td class="contenidoBody bordesTabla">{{$item->Confirmativa26}}</td>
                    <td class="contenidoBody bordesTabla">{{($item->Confirmativa24 + $item->Confirmativa25 + $item->Confirmativa26)}}</td>

                </tr>
                <tr>     
                    
                    <td class="contenidoBody bordesTabla">{{$item->Dilucion3}}</td>
                    <td class="contenidoBody bordesTabla">{{$item->Presuntiva17}}</td>
                    <td class="contenidoBody bordesTabla">{{$item->Presuntiva18}}</td>
                    <td class="contenidoBody bordesTabla">{{$item->Presuntiva19}}</td>
                    
                    <td class="contenidoBody bordesTabla">{{$item->Presuntiva27}}</td>
                    <td class="contenidoBody bordesTabla">{{$item->Presuntiva28}}</td>
                    <td class="contenidoBody bordesTabla">{{$item->Presuntiva29}}</td>
                    
                    <td class="contenidoBody bordesTabla">{{($item->Presuntiva27 + $item->Presuntiva28 + $item->Presuntiva29)}}</td>
                    <td class="contenidoBody bordesTabla">{{$item->Confirmativa17}}</td>
                    <td class="contenidoBody bordesTabla">{{$item->Confirmativa18}}</td> 
                    <td class="contenidoBody bordesTabla">{{$item->Confirmativa19}}</td>

                    <td class="contenidoBody bordesTabla">{{$item->Confirmativa27}}</td>
                    <td class="contenidoBody bordesTabla">{{$item->Confirmativa28}}</td>
                    <td class="contenidoBody bordesTabla">{{$item->Confirmativa29}}</td>
                    <td class="contenidoBody bordesTabla">{{($item->Confirmativa27 + $item->Confirmativa28 + $item->Confirmativa29)}}</td>

                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>