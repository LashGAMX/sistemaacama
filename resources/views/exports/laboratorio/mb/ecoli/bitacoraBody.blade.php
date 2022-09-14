<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/exports/bitacoras.css')}}">
    <title>Captura PDF</title>
</head>
<body>        

    <div class="procedimiento">
        @php
            echo @$procedimiento->Texto;
        @endphp
    </div>
    <br>
    <div id="contenedorTabla">

 
    <br>

    <table autosize="1" class="tabla" border="1">
        <thead>
            <tr>
                <th  style="font-size: 10px">No. De muestra</th>
                <th  style="font-size: 10px">Dilucion empleadas</th>
                <th  style="font-size: 10px" colspan="6">Prueba presuntiva</th>
                <th  style="font-size: 10px">Resultado presuntiva</th>
                <th  style="font-size: 10px" colspan="6">Prueba confirmativa</th>
                <th  style="font-size: 10px">N.M.P obtenido</th>
                <th  style="font-size: 10px">Resultado NMP/100 mL (Tabla)</th>
                <th  style="font-size: 10px">Resultado NMP/100 mL (Formula 1 )</th>
                <th  style="font-size: 10px">Resultado NMP/100 mL (Formula 2 ) mL (Formula 1 )</th>
            </tr>
            <tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th style="font-size: 8px" colspan="3">24 horas</th>
                    <th style="font-size: 8px" colspan="3">48 horas</th>
                    <th></th>
                    <th style="font-size: 8px" colspan="3">Caldo E.C 24 horas</th>
                    <th style="font-size: 8px" colspan="3">Agua triptona 24 horas</th>
                    <th></th>
                    <th></thead>
                    <th></th>
                    <th></th>
                </tr>
            </tr>
        </thead>
        <tbody>
            @foreach ($loteDetalle as $item)
                <tr> 
                    <td rowspan="3">{{$item->Codigo}}</td>
                    <td>{{$item->Dilucion1}}</td>
                    <td>{{$item->Presuntiva11}}</td>
                    <td>{{$item->Presuntiva14}}</td>
                    <td>{{$item->Presuntiva17}}</td>

                    <td>{{$item->Presuntiva21}}</td>
                    <td>{{$item->Presuntiva24}}</td>
                    <td>{{$item->Presuntiva27}}</td>
                    <td>
                        {{($item->Presuntiva21 + $item->Presuntiva22 + $item->Presuntiva23)}}
                    </td>
                    <td>{{$item->Confirmativa11}}</td>
                    <td>{{$item->Confirmativa14}}</td>
                    <td>{{$item->Confirmativa17}}</td>

                    <td>{{$item->Confirmativa21}}</td>
                    <td>{{$item->Confirmativa24}}</td>
                    <td>{{$item->Confirmativa27}}</td>
                    <td>{{($item->Confirmativa21 + $item->Confirmativa22 + $item->Confirmativa23)}}</td>
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
                </tr>
                <tr>     
                    
                    <td>{{$item->Dilucion2}}</td>
                    <td>{{$item->Presuntiva12}}</td>
                    <td>{{$item->Presuntiva15}}</td>
                    <td>{{$item->Presuntiva18}}</td>

                    <td>{{$item->Presuntiva22}}</td>
                    <td>{{$item->Presuntiva25}}</td>
                    <td>{{$item->Presuntiva28}}</td>

                    <td>{{($item->Presuntiva22 + $item->Presuntiva25 + $item->Presuntiva26)}}</td>
                    <td>{{$item->Confirmativa12}}</td>
                    <td>{{$item->Confirmativa15}}</td>
                    <td>{{$item->Confirmativa18}}</td>
                    <td>{{$item->Confirmativa22}}</td>
                    <td>{{$item->Confirmativa25}}</td>
                    <td>{{$item->Confirmativa28}}</td>
                    <td>{{($item->Confirmativa23 + $item->Confirmativa24 + $item->Confirmativa25)}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>     
                    
                    <td>{{$item->Dilucion3}}</td>
                    <td>{{$item->Presuntiva19}}</td>
                    <td>{{$item->Presuntiva13}}</td>
                    <td>{{$item->Presuntiva16}}</td>
                    
                    <td>{{$item->Presuntiva23}}</td>
                    <td>{{$item->Presuntiva26}}</td>
                    <td>{{$item->Presuntiva29}}</td>
                    
                    <td>{{($item->Presuntiva27 + $item->Presuntiva28 + $item->Presuntiva29)}}</td>
                    <td>{{$item->Confirmativa19}}</td>
                    <td>{{$item->Confirmativa13}}</td> 
                    <td>{{$item->Confirmativa16}}</td>
                    <td>{{$item->Confirmativa29}}</td>
                    <td>{{$item->Confirmativa23}}</td>
                    <td>{{$item->Confirmativa26}}</td>
                    <td>{{($item->Confirmativa27 + $item->Confirmativa28 + $item->Confirmativa29)}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>