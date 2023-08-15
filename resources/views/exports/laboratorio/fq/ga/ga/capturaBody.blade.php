2<!DOCTYPE html>
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
            echo $plantilla[0]->Texto;
        @endphp
    </div>

    <br>    
    <h6>Masas constante</h6>
    <table autosize="1" class="table table-borderless" id="tablaDatos">
        <thead>
            <tr>
                <th class="tableCabecera anchoColumna">No. Matraz</th>
                <th class="tableCabecera anchoColumna">Masa cte. 1</th>
                <th class="tableCabecera anchoColumna">Masa cte. 2</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($matraz as $item) 
            <tr>
                <td class="tableContent">{{@$item->Matraz}}</td>
                <td class="tableContent">{{@$item->Min}}</td>
                <td class="tableContent">{{@$item->Max}}</td> 
            </tr>
        @endforeach
        </tbody>        
    </table>  

    <table autosize="1" class="table table-borderless" id="tablaDatos">
        <thead>
            <tr>                               
                <th class="tableCabecera anchoColumna" colspan="4">
                    Calentamineto de matraces para masa cte.
                </th>
            </tr>

            <tr>
                <th class="tableCabecera anchoColumna">
                    Temp de la estufa
                </th>

                <th class="tableCabecera anchoColumna">
                    HORA
                </th>

                <th class="tableCabecera anchoColumna">
                    Masa cte.
                </th>
            </tr>
            
            <tr>                
                <th class="tableCabecera anchoColumna">
                    Entrada
                </th>

                <th class="tableCabecera anchoColumna">
                    Salida
                </th>                
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="tableContent">
                    {{@$detalle->Calentamiento_temp1}}
                </td>

                <td class="tableContent">
                    {{@$detalle->Calentamiento_entrada1}}
                </td>

                <td class="tableContent">
                    {{@$detalle->Calentamiento_salida1}}
                </td>

                <td class="tableContent">
                    1
                </td>
            </tr>
                
            <tr>
                <td class="tableContent">
                    {{@$detalle->Calentamiento_temp2}}
                </td>

                <td class="tableContent">
                    {{@$detalle->Calentamiento_entrada2}}
                </td>

                <td class="tableContent">
                    {{@$detalle->Calentamiento_salida2}}
                </td>

                <td class="tableContent">
                    2
                </td>
            </tr>

            <tr>
                <td class="tableContent">
                    {{@$detalle->Calentamiento_temp3}}
                </td>

                <td class="tableContent">
                    {{@$detalle->Calentamiento_entrada3}}
                </td>

                <td class="tableContent">
                    {{@$detalle->Calentamiento_salida3}}
                </td>

                <td class="tableContent">
                    3
                </td>
            </tr>
        </tbody>
    </table>

    <br>

    <table autosize="1" class="table table-borderless" id="tablaDatos">
        <thead>
            <tr>                               
                <th class="tableCabecera anchoColumna" colspan="4">
                    Enfriado de matraces en desecador para masa cte.
                </th>
            </tr>

            <tr>
                <th class="tableCabecera anchoColumna">
                    Temp de la estufa
                </th>

                <th class="tableCabecera anchoColumna">
                    HORA
                </th>

                <th class="tableCabecera anchoColumna">
                    Masa cte.
                </th>
            </tr>
            
            <tr>                
                <th class="tableCabecera anchoColumna">
                    Entrada
                </th>

                <th class="tableCabecera anchoColumna">
                    Salida
                </th>                
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="tableContent">
                    {{@$detalle->Enfriado_entrada1}}
                </td>

                <td class="tableContent">
                    {{@$detalle->Enfriado_salida1}}
                </td>

                <td class="tableContent">
                    {{@$detalle->Enfriado_pesado1}}
                </td>

                <td class="tableContent">
                    1
                </td>
            </tr>
                
            <tr>
                <td class="tableContent">
                    {{@$detalle->Enfriado_entrada2}}
                </td>

                <td class="tableContent">
                    {{@$detalle->Enfriado_salida2}}
                </td>

                <td class="tableContent">
                    {{@$detalle->Enfriado_pesado2}}
                </td>

                <td class="tableContent">
                    2
                </td>
            </tr>

            <tr>
                <td class="tableContent">
                    {{@$detalle->Enfriado_entrada3}}
                </td>

                <td class="tableContent">
                    {{@$detalle->Enfriado_salida3}}
                </td>

                <td class="tableContent">
                    {{@$detalle->Enfriado_pesado3}}
                </td>

                <td class="tableContent">
                    3
                </td>
            </tr>
        </tbody>
    </table>

    <br>


    <br>

    <table autosize="1" class="table table-borderless" id="tablaDatos">
        <thead>
            <tr>                                
                <th class="tableCabecera anchoColumna" colspan="2">
                    Secado de cartuchos
                </th>
            </tr>

            <tr>
                <th class="tableCabecera anchoColumna" rowspan="2">
                    Temp de la estufa
                </th>

                <th class="tableCabecera anchoColumna" colspan="2">
                    HORA
                </th>                
            </tr>

            <tr>
                <th class="tableCabecera anchoColumna">
                    Entrada
                </th>
                
                <th class="tableCabecera anchoColumna">
                    Salida
                </th>
            </tr>

        </thead>
        <tbody>
            <tr>
                <td class="tableContent">
                    {{@$detalle->Secado_temp}}
                </td>

                <td class="tableContent">
                    {{@$detalle->Secado_entrada}}
                </td>

                <td class="tableContent">
                    {{@$detalle->Secado_salida}}
                </td>                
            </tr>                            
        </tbody>
    </table>

    <br>

    <table autosize="1" class="table table-borderless" id="tablaDatos">
        <thead>
            <tr>                
                <th class="tableCabecera anchoColumna" colspan="2">
                    Tiempo de reflujo
                </th>
            </tr>

            <tr>
                <th class="tableCabecera anchoColumna" colspan="2">
                   HORA
                </th> 
            </tr>

            <tr>                
                <th class="tableCabecera anchoColumna">
                    Entrada
                </th>

                <th class="tableCabecera anchoColumna">
                    Salida
                </th>                
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="tableContent">
                    {{@$detalle->Reflujo_entrada}}
                </td>

                <td class="tableContent">
                    {{@$detalle->Reflujo_salida}}
                </td>                              
            </tr>                            
        </tbody>
    </table>

    <br>
    
    <table autosize="1" class="table table-borderless" id="tablaDatos">
        
        <thead>
            <tr>                
                <th class="tableCabecera anchoColumna" colspan="2">
                    Enfriado de matraces en
                </th>
            </tr>

            <tr>
                <th class="tableCabecera anchoColumna" colspan="2">
                    HORA
                </th>
            </tr>

            <tr>                
                <th class="tableCabecera anchoColumna">
                    Entrada
                </th>

                <th class="tableCabecera anchoColumna">
                    Salida
                </th>                
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="tableContent">
                    {{@$detalle->Enfriado_matraces_entrada}}
                </td>

                <td class="tableContent">
                    {{@$detalle->Enfriado_matraces_salida}}
                </td>                              
            </tr>                            
        </tbody>
    </table>

    <br>
    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000">
            <thead>
                <tr>
                    <td class="tableCabecera bordesTabla">pH de las muestras &nbsp;</td>
                    <td class="tableCabecera bordesTabla">&nbsp;No. de muestra&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTabla">&nbsp;No. de cartucho&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTabla">&nbsp;No. de matraz&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTabla">&nbsp;Masa inicial 3 <br>g &nbsp;&nbsp;</td>
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
                        <td class="tableContent bordesTabla">{{number_format(@$item->M_inicial3, 4, ".", ".")}}</td>
                        <td class="tableContent bordesTabla">{{@$item->Vol_muestra}}</td>
                        <td class="tableContent bordesTabla">{{number_format(@$item->M_final, 4, ".", ".")}}</td>
                        @if (@$item->Resultado < @$item->Limite)
                        <td class="tableContent bordesTabla">< {{@$item->Limite}}</td>
                       @else  
                           <td class="tableContent bordesTabla"> {{number_format(@$item->Resultado, 2, ".", ".")}}</td> 
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
                    <td class="tableContent bordesTabla">{{@$item->Codigo}}</td>
                    <td class="tableContent bordesTabla">{{$cont}}</td>
                    <td class="tableContent bordesTabla">{{@$item->Matraz}}</td>
                    <td class="tableContent bordesTabla">{{number_format(@$item->M_inicial3, 4, ".", ".")}}</td>
                    <td class="tableContent bordesTabla">{{@$item->Vol_muestra}}</td>
                    <td class="tableContent bordesTabla">{{number_format(@$item->M_final, 4, ".", ".")}}</td>
                    @if (@$item->Resultado < @$item->Limite)
                     <td class="tableContent bordesTabla">< {{@$item->Limite}}</td>
                    @else  
                        <td class="tableContent bordesTabla">{{number_format(@$item->Resultado, 2, ".", ".")}}</td> 
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