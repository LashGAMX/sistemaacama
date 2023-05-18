<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/fq/espectro/cianuros/cianurosPDF.css')}}">
    <title>Captura PDF</title>
</head>

<body>

    <div id="contenidoCurva">
        @php
        echo @$plantilla[0]->Texto;
        @endphp
    </div>
    <br>
    <div class="contenedorTabla">
        <table autosize="1" class="table" border="1" id="tablaDatos">
            <thead>
                <tr>
                    <th style="font-size: 10px" rowspan="2">No. De muestra</th>
                    <th style="font-size: 10px" rowspan="2">Dilucion empleadas</th>
                    <th style="font-size: 10px" colspan="6">Prueba presuntiva</th>
                    <th style="font-size: 10px" rowspan="2">Resultado presuntiva</th>
                    <th style="font-size: 10px" colspan="6">Prueba confirmativa</th>
                    <th style="font-size: 10px" rowspan="2">N.M.P obtenido</th>
                    <th style="font-size: 10px" rowspan="2">Resultado NMP/100 mL (Tabla)</th>
                    <th style="font-size: 10px" rowspan="2">Resultado NMP/100 mL (Formula 1 )</th>
                    <th style="font-size: 10px" rowspan="2">Resultado NMP/100 mL (Formula 2 ) mL (Formula 1 )</th>
                </tr>
                <tr>
                    <th style="font-size: 8px" colspan="3">24 horas</th>
                    <th style="font-size: 8px" colspan="3">48 horas</th>
                    <th style="font-size: 8px" colspan="3">Caldo E.C 24 horas</th>
                    <th style="font-size: 8px" colspan="3">Agua triptona 24 horas</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($loteDetalleControles as $item)
                    <tr>
                    <td>{{$item->Control}}</td>
                    <td>{{$item->Dilucion1}}</td>
                    <td>{{$item->Presuntiva1}}</td>
                    <td>{{$item->Presuntiva4}}</td>
                    <td>{{$item->Presuntiva7}}</td>

                    <td>{{$item->Presuntiva10}}</td>
                    <td>{{$item->Presuntiva13}}</td>
                    <td>{{$item->Presuntiva16}}</td>
                    <td>
                        {{($item->Presuntiva1 + $item->Presuntiva4 + $item->Presuntiva7)}}
                    </td>
                    <td>{{$item->Confirmativa1}}</td>
                    <td>{{$item->Confirmativa4}}</td>
                    <td>{{$item->Confirmativa7}}</td>

                    <td>{{$item->Confirmativa1}}</td>
                    <td>{{$item->Confirmativa4}}</td>
                    <td>{{$item->Confirmativa7}}</td>
                    <td>{{($item->Confirmativa1 + $item->Confirmativa2 + $item->Confirmativa7)}}</td>
                    @switch($item->Tipo)
                    @case(1)
                    <td class="contenidoBody bordesTabla" >
                        @if (@$item->Resultado == 0)
                        < 3 @else {{@$item->Resultado}}
                            @endif
                    </td>

                    <td class="contenidoBody bordesTabla" >
                        --
                    </td>

                    <td class="contenidoBody bordesTabla"  style="font-weight: bold">
                        --
                    </td>
                    @break
                    @case(2)
                    <td class="contenidoBody bordesTabla" >
                        --
                    </td>

                    <td class="contenidoBody bordesTabla" >
                        {{@$item->Resultado}}
                    </td>

                    <td class="contenidoBody bordesTabla"  style="font-weight: bold">
                        --
                    </td>
                    @break
                    @case(3)
                    <td class="contenidoBody bordesTabla" >
                        --
                    </td>

                    <td class="contenidoBody bordesTabla" >
                        --
                    </td>

                    <td class="contenidoBody bordesTabla"  style="font-weight: bold">
                        {{@$item->Resultado}}
                    </td>
                    @break
                    @default
                    <td class="contenidoBody bordesTabla" >
                        --
                    </td>

                    <td class="contenidoBody bordesTabla" >
                        --
                    </td>

                    <td class="contenidoBody bordesTabla"  style="font-weight: bold">
                        --
                    </td>
                    @endswitch
                    </tr>
                @endforeach

                @foreach ($loteDetalle as $item)
                <tr>
                    <td rowspan="3">{{$item->Codigo}}</td>
                    <td>{{$item->Dilucion1}}</td>
                    <td>{{$item->Presuntiva1}}</td>
                    <td>{{$item->Presuntiva4}}</td>
                    <td>{{$item->Presuntiva7}}</td>

                    <td>{{$item->Presuntiva10}}</td>
                    <td>{{$item->Presuntiva13}}</td>
                    <td>{{$item->Presuntiva16}}</td>
                    <td>
                        {{($item->Presuntiva1 + $item->Presuntiva4 + $item->Presuntiva7)}}
                    </td>
                    <td>{{$item->Confirmativa1}}</td>
                    <td>{{$item->Confirmativa4}}</td>
                    <td>{{$item->Confirmativa7}}</td>

                    <td>{{$item->Confirmativa1}}</td>
                    <td>{{$item->Confirmativa4}}</td>
                    <td>{{$item->Confirmativa7}}</td>
                    <td>{{($item->Confirmativa1 + $item->Confirmativa2 + $item->Confirmativa7)}}</td>
                    @switch($item->Tipo)
                    @case(1)
                    <td class="contenidoBody bordesTabla" rowspan="3">
                        @if (@$item->Resultado == 0)
                        < 3 @else {{@$item->Resultado}}
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
                    <td>{{$item->Presuntiva2}}</td>
                    <td>{{$item->Presuntiva5}}</td>
                    <td>{{$item->Presuntiva8}}</td>

                    <td>{{$item->Presuntiva11}}</td>
                    <td>{{$item->Presuntiva14}}</td>
                    <td>{{$item->Presuntiva17}}</td>

                    <td>{{($item->Presuntiva2 + $item->Presuntiva5 + $item->Presuntiva8)}}</td>
                    <td>{{$item->Confirmativa2}}</td>
                    <td>{{$item->Confirmativa5}}</td>
                    <td>{{$item->Confirmativa8}}</td>

                    <td>{{$item->Confirmativa2}}</td>
                    <td>{{$item->Confirmativa5}}</td>
                    <td>{{$item->Confirmativa8}}</td>
                    <td>{{($item->Confirmativa2 + $item->Confirmativa5 + $item->Confirmativa8)}}</td>

                </tr>
                <tr>

                    <td>{{$item->Dilucion3}}</td>
                    <td>{{$item->Presuntiva3}}</td>
                    <td>{{$item->Presuntiva6}}</td>
                    <td>{{$item->Presuntiva9}}</td>

                    <td>{{$item->Presuntiva12}}</td>
                    <td>{{$item->Presuntiva15}}</td>
                    <td>{{$item->Presuntiva18}}</td>

                    <td>{{($item->Presuntiva3 + $item->Presuntiva6 + $item->Presuntiva9)}}</td>
                    <td>{{$item->Confirmativa3}}</td>
                    <td>{{$item->Confirmativa6}}</td>
                    <td>{{$item->Confirmativa9}}</td>

                    <td>{{$item->Confirmativa3}}</td>
                    <td>{{$item->Confirmativa6}}</td>
                    <td>{{$item->Confirmativa9}}</td>
                    <td>{{($item->Confirmativa3 + $item->Confirmativa6 + $item->Confirmativa9)}}</td>

                </tr>
                @endforeach
            </tbody>
        </table>
</body>

</html>