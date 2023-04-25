<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/fq/curvaPDF.css')}}">
    <title>Captura PDF</title>
</head>
<body>
<div id="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000">
            <thead>
                <tr>
                    <td class="tableCabecera bordesTabla">pH de las muestras &nbsp;</td>
                    <td class="tableCabecera bordesTabla">&nbsp;No. de muestra&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTabla">&nbsp;No. de cartucho&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTabla">&nbsp;No. de matraz&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTabla">&nbsp;Masa inicial 3 g&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTabla">&nbsp;Vol. de la muestra (mL)&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTabla">&nbsp;Masa con muestra g&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTabla">&nbsp;G y A mg/L&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTabla">&nbsp;Observaciones&nbsp;&nbsp;</td>
                    <td></td>
                    <td></td>
                </tr>
            </thead>

            <tbody>
                @php
                    $cont = 1;
                @endphp
                @foreach ($modelConControl as $item)
                    <tr>
                        {{-- <td class="tableContent bordesTabla">{{@$data[$i]->Ph}}</td> --}}
                        <td class="tableContent bordesTabla">< 2</td>
                        <td class="tableContent bordesTabla">{{@$item->Control}}</td>
                        <td class="tableContent bordesTabla">{{$cont}}</td>
                        <td class="tableContent bordesTabla">{{@$item->Matraz}}</td>
                        <td class="tableContent bordesTabla">{{@$item->M_inicial3}}</td>
                        <td class="tableContent bordesTabla">{{@$item->Vol_muestra}}</td>
                        <td class="tableContent bordesTabla">{{@$item->M_final}}</td>
                        @if (@$item->Resultado < @$item->Limite)
                         <td class="tableContent bordesTabla">< {{@$item->Limite}}</td>
                        @else 
                            <td class="tableContent bordesTabla">{{@$item->Resultado}}</td>
                        @endif                       
                        <td class="tableContent bordesTabla">{{@$item->Observacion}}</td>
                        <td class="tableContent">
                            @if (@$item->Liberado == 1)
                                Liberado
                            @elseif(@$item->Liberado == 0)
                                No liberado
                            @endif
                        </td>
                        <td class="tableContent">{{@$item->Control}}</td>
                    </tr>
                    @php
                        $cont++;
                    @endphp
                @endforeach
                @foreach ($modelSinControl as $item)
                <tr>
                    {{-- <td class="tableContent bordesTabla">{{@$data[$i]->Ph}}</td> --}}
                    <td class="tableContent bordesTabla">< 2</td>
                    <td class="tableContent bordesTabla">{{@$item->Control}}</td>
                    <td class="tableContent bordesTabla">{{$cont}}</td>
                    <td class="tableContent bordesTabla">{{@$item->Matraz}}</td>
                    <td class="tableContent bordesTabla">{{@$item->M_inicial3}}</td>
                    <td class="tableContent bordesTabla">{{@$item->Vol_muestra}}</td>
                    <td class="tableContent bordesTabla">{{@$item->M_final}}</td>
                    @if (@$item->Resultado < @$item->Limite)
                     <td class="tableContent bordesTabla">< {{@$item->Limite}}</td>
                    @else 
                        <td class="tableContent bordesTabla">{{@$item->Resultado}}</td>
                    @endif                       
                    <td class="tableContent bordesTabla">{{@$item->Observacion}}</td>
                    <td class="tableContent">
                        @if (@$item->Liberado == 1)
                            Liberado
                        @elseif(@$item->Liberado == 0)
                            No liberado
                        @endif
                    </td>
                    <td class="tableContent">{{@$item->Control}}</td>
                </tr>
                @php
                    $cont++;
                @endphp
                @endforeach
            </tbody>
        </table>
</div>
</body>
</html>