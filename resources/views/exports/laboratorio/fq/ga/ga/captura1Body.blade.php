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
                    <th class="nombreHeader" colspan="10">
                        Resultado de las muestras
                    </th>                    
                </tr>

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
                {{-- Imprime primero los contorles de calidad --}}
                @for ($i = 0; $i < @$dataLength ; $i++)
                @if (@$data[$i]->Control != 'Resultado')
                    <tr>
                        {{-- <td class="tableContent bordesTabla">{{@$data[$i]->Ph}}</td> --}}
                        <td class="tableContent bordesTabla">< 2</td>
                        <td class="tableContent bordesTabla">{{@$data[$i]->Control}}</td>
                        <td class="tableContent bordesTabla">{{$cont}}</td>
                        <td class="tableContent bordesTabla">{{@$data[$i]->Matraz}}</td>
                        <td class="tableContent bordesTabla">{{@$data[$i]->M_inicial3}}</td>
                        <td class="tableContent bordesTabla">{{@$data[$i]->Vol_muestra}}</td>
                        <td class="tableContent bordesTabla">{{@$data[$i]->M_final}}</td>
                        <td class="tableContent bordesTabla">{{@$limites[$i]}}</td>
                        <td class="tableContent bordesTabla">{{@$data[$i]->Observacion}}</td>
                        <td class="tableContent">
                            @if (@$data[$i]->Liberado == 1)
                                Liberado
                            @elseif(@$data[$i]->Liberado == 0)
                                No liberado
                            @endif
                        </td>
                        <td class="tableContent">{{@$data[$i]->Control}}</td>
                    </tr>
                    @php $cont++; @endphp
                @endif                    
            
                @endfor
                {{-- Imprimo el resto --}}
                @for ($i = 0; $i < @$dataLength ; $i++)
                    @if (@$data[$i]->Control == 'Resultado')
                        <tr>
                            {{-- <td class="tableContent bordesTabla">{{@$data[$i]->Ph}}</td> --}}
                            <td class="tableContent bordesTabla">< 2</td>
                            <td class="tableContent bordesTabla">{{@$data[$i]->Codigo}}</td>
                            <td class="tableContent bordesTabla">{{$cont}}</td>
                            <td class="tableContent bordesTabla">{{@$data[$i]->Matraz}}</td>
                            <td class="tableContent bordesTabla">{{@$data[$i]->M_inicial3}}</td>
                            <td class="tableContent bordesTabla">{{@$data[$i]->Vol_muestra}}</td>
                            <td class="tableContent bordesTabla">{{@$data[$i]->M_final}}</td>
                            <td class="tableContent bordesTabla">{{@$limites[$i]}}</td>
                            <td class="tableContent bordesTabla">{{@$data[$i]->Observacion}}</td>
                            <td class="tableContent">
                                @if (@$data[$i]->Liberado == 1)
                                    Liberado
                                @elseif(@$data[$i]->Liberado == 0)
                                    No liberado
                                @endif
                            </td>
                            <td class="tableContent">{{@$data[$i]->Control}}</td>
                        </tr>
                        @php $cont++; @endphp
                     @endif   
                @endfor
            </tbody>
        </table>
    </div>
</body>
</html>