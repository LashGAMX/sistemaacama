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
        <table class="table"  style="border-collapse: collapse">
            <thead>
                <tr>
                    <th style="border: 1px solid black" class="tableCabecera anchoColumna" rowspan="2">No. De muestra</th>
                    <th style="border: 1px solid black" class="tableCabecera anchoColumna" rowspan="2">Dilucion empleadas</th>
                    <th style="border: 1px solid black" class="tableCabecera anchoColumna" colspan="6">Prueba presuntiva</th>
                    <th style="border: 1px solid black" class="tableCabecera anchoColumna" rowspan="2">Resultado presuntiva</th>
                    <th style="border: 1px solid black" class="tableCabecera anchoColumna" colspan="6">Prueba confirmativa</th>
                    <th style="border: 1px solid black" class="tableCabecera anchoColumna" rowspan="2">N.M.P obtenido</th>
                    <th style="border: 1px solid black" class="tableCabecera anchoColumna" rowspan="2">Resultado NMP/100 mL (Tabla)</th>
                    <th style="border: 1px solid black" class="tableCabecera anchoColumna" rowspan="2">Resultado NMP/100 mL (Formula 1 )</th>
                    <th style="border: 1px solid black" class="tableCabecera anchoColumna" rowspan="2">Resultado NMP/100 mL (Formula 2 ) mL</th>
                </tr>
                <tr>
                    <th style="border: 1px solid black" class="tableCabecera anchoColumna" colspan="3">24 horas</th>
                    <th style="border: 1px solid black" class="tableCabecera anchoColumna" colspan="3">48 horas</th>
                    <th style="border: 1px solid black" class="tableCabecera anchoColumna" colspan="3">Caldo E.C 24 horas</th>
                    <th style="border: 1px solid black" class="tableCabecera anchoColumna" colspan="3">Agua triptona 24 horas</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($loteDetalleControles as $item)
                    <tr>
                    <td style="border: 1px solid black" class="tableContent ">{{$item->Control}}</td>
                    <td style="border: 1px solid black" class="tableContent ">{{$item->Dilucion1}}</td>
                    <td style="border: 1px solid black" class="tableContent ">{{$item->Presuntiva1}}</td>
                    <td style="border: 1px solid black" class="tableContent ">{{$item->Presuntiva2}}</td>
                    <td style="border: 1px solid black" class="tableContent ">{{$item->Presuntiva3}}</td>

                    <td style="border: 1px solid black" class="tableContent ">{{$item->Presuntiva10}}</td>
                    <td style="border: 1px solid black" class="tableContent ">{{$item->Presuntiva11}}</td>
                    <td style="border: 1px solid black" class="tableContent ">{{$item->Presuntiva12}}</td>
                    <td style="border: 1px solid black" class="tableContent ">
                        {{($item->Presuntiva10 + $item->Presuntiva11 + $item->Presuntiva12)}}
                    </td>

                    <td style="border: 1px solid black" class="tableContent ">{{$item->Confirmativa10}}</td>
                    <td style="border: 1px solid black" class="tableContent ">{{$item->Confirmativa11}}</td>
                    <td style="border: 1px solid black" class="tableContent ">{{$item->Confirmativa12}}</td>

                    <td style="border: 1px solid black" class="tableContent ">{{$item->Confirmativa1}}</td>
                    <td style="border: 1px solid black" class="tableContent ">{{$item->Confirmativa2}}</td>
                    <td style="border: 1px solid black" class="tableContent ">{{$item->Confirmativa3}}</td>
                     
                    <td style="border: 1px solid black" class="tableContent ">{{($item->Confirmativa1 + $item->Confirmativa2 + $item->Confirmativa3)}}</td>
                    @switch($item->Tipo)
                    @case(1)
                    <td style="border: 1px solid black" class="contenidoBody " >
                        @if (@$item->Resultado == 0)
                        < 3 @else {{@$item->Resultado}}
                            @endif
                    </td>

                    <td style="border: 1px solid black" style="border: 1px solid black" class="contenidoBody " >
                        --
                    </td>

                    <td style="border: 1px solid black" class="contenidoBody "  >
                        --
                    </td>
                    @break
                    @case(2)
                    <td style="border: 1px solid black" class="contenidoBody " >
                        --
                    </td>

                    <td style="border: 1px solid black" class="contenidoBody " >
                        {{@$item->Resultado}}
                    </td>

                    <td style="border: 1px solid black" class="contenidoBody "  >
                        --
                    </td>
                    @break
                    @case(3)
                    <td style="border: 1px solid black" class="contenidoBody " >
                        --
                    </td>

                    <td style="border: 1px solid black" class="contenidoBody " >
                        --
                    </td>

                    <td style="border: 1px solid black" class="contenidoBody "  >
                        {{@$item->Resultado}}
                    </td>
                    @break
                    @default
                    <td style="border: 1px solid black" class="contenidoBody " >
                        --
                    </td>

                    <td style="border: 1px solid black" class="contenidoBody " >
                        --
                    </td>

                    <td style="border: 1px solid black" class="contenidoBody " >
                        --
                    </td>
                    @endswitch
                    </tr>
                @endforeach

                @foreach ($loteDetalle as $item)
                <tr>
                    <td class="tableContent " rowspan="3" style="border: 1px solid black" >{{$item->Codigo}}</td>
                    <td class="tableContent " style="border: 1px solid black" >{{$item->Dilucion1}}</td>
                    <td class="tableContent " style="border: 1px solid black" >{{$item->Presuntiva1}}</td>
                    <td class="tableContent " style="border: 1px solid black" >{{$item->Presuntiva2}}</td>
                    <td class="tableContent " style="border: 1px solid black" >{{$item->Presuntiva3}}</td>

                    <td class="tableContent " style="border: 1px solid black" >{{$item->Presuntiva10}}</td>
                    <td class="tableContent " style="border: 1px solid black" >{{$item->Presuntiva11}}</td>
                    <td class="tableContent " style="border: 1px solid black" >{{$item->Presuntiva12}}</td>
                    <td class="tableContent " style="border: 1px solid black">
                        {{($item->Presuntiva10 + $item->Presuntiva11 + $item->Presuntiva12)}}
                    </td>

                    <td class="tableContent " style="border: 1px solid black">{{$item->Confirmativa10}}</td>
                    <td class="tableContent " style="border: 1px solid black">{{$item->Confirmativa11}}</td>
                    <td class="tableContent " style="border: 1px solid black">{{$item->Confirmativa12}}</td>

                    <td class="tableContent " style="border: 1px solid black">{{$item->Confirmativa1}}</td>
                    <td class="tableContent " style="border: 1px solid black">{{$item->Confirmativa2}}</td>
                    <td class="tableContent " style="border: 1px solid black">{{$item->Confirmativa3}}</td>

                    <td class="tableContent " style="border: 1px solid black">{{($item->Confirmativa1 + $item->Confirmativa2 + $item->Confirmativa3)}}</td>
                    @switch($item->Tipo)
                    @case(1)
                    <td style="border: 1px solid black" class="contenidoBody bordesTabla" rowspan="3">
                        @if (@$item->Resultado == 0)
                        < 3 @else {{@$item->Resultado}}
                            @endif
                    </td>

                    <td style="border: 1px solid black" class="contenidoBody bordesTabla" rowspan="3">
                        --
                    </td>

                    <td style="border: 1px solid black" class="contenidoBody bordesTabla" rowspan="3" >
                        --
                    </td>
                    @break
                    @case(2)
                    <td style="border: 1px solid black" class="contenidoBody bordesTabla" rowspan="3">
                        --
                    </td>

                    <td style="border: 1px solid black" class="contenidoBody bordesTabla" rowspan="3">
                        {{@$item->Resultado}}
                    </td>

                    <td style="border: 1px solid black" class="contenidoBody bordesTabla" rowspan="3" >
                        --
                    </td>
                    @break
                    @case(3)
                    <td style="border: 1px solid black" class="contenidoBody bordesTabla" rowspan="3">
                        --
                    </td>

                    <td style="border: 1px solid black" class="contenidoBody bordesTabla" rowspan="3">
                        --
                    </td>

                    <td style="border: 1px solid black" class="contenidoBody bordesTabla" rowspan="3" >
                        {{@$item->Resultado}}
                    </td>
                    @break
                    @default
                    <td style="border: 1px solid black" class="contenidoBody bordesTabla" rowspan="3">
                        --
                    </td>

                    <td style="border: 1px solid black" class="contenidoBody bordesTabla" rowspan="3">
                        --
                    </td>

                    <td style="border: 1px solid black" class="contenidoBody bordesTabla" rowspan="3" >
                        --
                    </td>
                    @endswitch
                </tr>
                <tr>

                    <td class="tableContent " style="border: 1px solid black">{{$item->Dilucion2}}</td>
                    <td class="tableContent " style="border: 1px solid black">{{$item->Presuntiva4}}</td>
                    <td class="tableContent " style="border: 1px solid black">{{$item->Presuntiva5}}</td>
                    <td class="tableContent " style="border: 1px solid black">{{$item->Presuntiva6}}</td>

                    <td class="tableContent " style="border: 1px solid black">{{$item->Presuntiva13}}</td>
                    <td class="tableContent " style="border: 1px solid black">{{$item->Presuntiva14}}</td>
                    <td class="tableContent " style="border: 1px solid black">{{$item->Presuntiva15}}</td>

                    <td class="tableContent " style="border: 1px solid black">{{($item->Presuntiva13 + $item->Presuntiva14 + $item->Presuntiva15)}}</td>

                    <td class="tableContent " style="border: 1px solid black">{{$item->Confirmativa13}}</td>
                    <td class="tableContent " style="border: 1px solid black">{{$item->Confirmativa14}}</td>
                    <td class="tableContent " style="border: 1px solid black">{{$item->Confirmativa15}}</td>

                    <td class="tableContent " style="border: 1px solid black">{{$item->Confirmativa4}}</td>
                    <td class="tableContent " style="border: 1px solid black">{{$item->Confirmativa5}}</td>
                    <td class="tableContent " style="border: 1px solid black">{{$item->Confirmativa6}}</td>

                    <td class="tableContent " style="border: 1px solid black">{{($item->Confirmativa4 + $item->Confirmativa5 + $item->Confirmativa6)}}</td>

                </tr>
                <tr>

                    <td class="tableContent " style="border: 1px solid black">{{$item->Dilucion3}}</td>
                    <td class="tableContent " style="border: 1px solid black">{{$item->Presuntiva7}}</td>
                    <td class="tableContent " style="border: 1px solid black">{{$item->Presuntiva8}}</td>
                    <td class="tableContent " style="border: 1px solid black">{{$item->Presuntiva9}}</td>

                    <td class="tableContent " style="border: 1px solid black">{{$item->Presuntiva16}}</td>
                    <td class="tableContent " style="border: 1px solid black">{{$item->Presuntiva17}}</td>
                    <td class="tableContent " style="border: 1px solid black">{{$item->Presuntiva18}}</td>

                    <td class="tableContent " style="border: 1px solid black">{{($item->Presuntiva16 + $item->Presuntiva17 + $item->Presuntiva18)}}</td>

                    <td class="tableContent " style="border: 1px solid black">{{$item->Confirmativa16}}</td>
                    <td class="tableContent " style="border: 1px solid black">{{$item->Confirmativa17}}</td>
                    <td class="tableContent " style="border: 1px solid black">{{$item->Confirmativa18}}</td>

                    <td class="tableContent " style="border: 1px solid black">{{$item->Confirmativa7}}</td>
                    <td class="tableContent " style="border: 1px solid black">{{$item->Confirmativa8}}</td>
                    <td class="tableContent " style="border: 1px solid black">{{$item->Confirmativa9}}</td>

                    <td class="tableContent " style="border: 1px solid black">{{($item->Confirmativa7 + $item->Confirmativa8 + $item->Confirmativa9)}}</td>

                </tr> 
                @endforeach
            </tbody>
        </table>
</body>

</html>