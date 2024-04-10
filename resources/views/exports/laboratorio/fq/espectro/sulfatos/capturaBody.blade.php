<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/fq/espectro/sulfatos/sulfatosPDF.css')}}">
    <title>Captura PDF</title>
</head>
<body>

    <div id="contenidoCurva">
        @php
            echo $plantilla[0]->Texto;
        @endphp
    </div>
    <div class="contenedorTabla">
        <table autosize="1" class="table " id="tablaDatos">
            <thead>
                <tr>
                    <th class="tableCabecera anchoColumna"style=" font-size:10px;" >No. de muestra</th>
                    <th class="tableCabecera anchoColumna" style="font-size:10px;width: 30px">Volumen de <br> muestra (mL)</th>
                    <th class="tableCabecera anchoColumna" style="font-size:10px;width: 45px">Abs 1</th>
                    <th class="tableCabecera anchoColumna" style="font-size:10px;width: 45px">Abs 2</th>
                    <th class="tableCabecera anchoColumna" style="font-size:10px;width: 45px">Abs 3</th>
                    <th class="tableCabecera anchoColumna" style="font-size:10px;width: 45px">Abs 4</th>
                    <th class="tableCabecera anchoColumna" style="font-size:10px;width: 45px">Abs 5</th>
                    <th class="tableCabecera anchoColumna" style="font-size:10px;width: 45px">Abs 6</th>
                    <th class="tableCabecera anchoColumna" style="font-size:10px;width: 45px">Abs 7</th>
                    <th class="tableCabecera anchoColumna" style="font-size:10px;width: 45px">Abs 8</th>
                    <th class="tableCabecera anchoColumna" style="font-size:10px;width: 50px">Abs Promedio</th>
                    <th class="tableCabecera anchoColumna" style="font-size:10px;">SULFATOS (SO4) mg/L</th>                    
                    <th class="tableCabecera anchoColumna" style="font-size:10px;width: 50px">Observaciones</th>
                    <th style="width: 50px" class="anchoColumna"></th>
                    <th style="width: 50px" class="anchoColumna"></th>
                </tr>
            </thead>
    
            <tbody>

                @foreach ($model as $item)
                <tr>
                    <td class="tableContent" style="font-size:10px">
                        @if (@$item->Control == 'Muestra Adicionada' || @$item->Control == 'Duplicado' || @$item->Control == 'Resultado')
                            {{@$item->Folio_servicio}}
                        @else
                            {{@$item->Control}}
                        @endif                                
                    </td>
                    <td class="tableContent" style="font-size:10px">{{@$item->Vol_muestra}}</td>
                    <td class="tableContent" style="font-size:10px">{{number_format(@$item->Abs1,3)}}</td>
                    <td class="tableContent" style="font-size:10px">{{number_format(@$item->Abs2,3)}}</td>
                    <td class="tableContent" style="font-size:10px">{{number_format(@$item->Abs3,3)}}</td>
                    <td class="tableContent" style="font-size:10px">{{number_format(@$item->Abs4,3)}}</td>
                    <td class="tableContent" style="font-size:10px">{{number_format(@$item->Abs5,3)}}</td>
                    <td class="tableContent" style="font-size:10px">{{number_format(@$item->Abs6,3)}}</td>
                    <td class="tableContent" style="font-size:10px">{{number_format(@$item->Abs7,3)}}</td>
                    <td class="tableContent" style="font-size:10px">{{number_format(@$item->Abs8,3)}}</td>
                    <td class="tableContent" style="font-size:10px">{{number_format(@$item->Promedio,3)}}</td>
                    <td class="tableContent" style="font-size:10px">
                       @if ($item->Resultado != null)
                        @if ($item->Resultado < $item->Limite)
                                < {{number_format(@$item->Limite, 3, ".", "")}}
                            @else
                                {{number_format(@$item->Resultado, 3, ".", "")}}
                            @endif
                       @else
                           -------
                       @endif
                        </td>
                    <td class="tableContent" style="font-size:10px">{{@$item->Observacion}}</td>
                    <td class="tableContent" style="font-size:10px">
                        @if (@$item->Liberado == 1)
                            Liberado
                        @elseif(@$item->Liberado == 0)
                            No liberado
                        @endif     
                    </td>
                    <td class="tableContent" style="font-size:10px">{{@$item->Control}}</td>
                </tr>
                @endforeach
            </tbody>        
        </table>  
    </div>

    <div id="contenidoCurva">
        <span id="curvaProcedimiento">Valoración / Observación</span>
        {{-- <?php echo html_entity_decode($textoProcedimiento[1]);?> --}}
    </div>

    <br>

    <div class="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos" style="width: 60%">
            <thead>

                <tr>
                    <th class="nombreHeader" colspan="4">
                        Datos de la curva de calibración
                    </th>                    
                </tr>                
                
            </thead>
    
            <tbody>
                {{-- @for ($i = 0; $i < 100 ; $i++) --}}
                    <tr>
                        <td class="tableCabecera">b = </td>
                        <td class="tableContent">{{@$curva->B}}</td>                        
                        <td class="tableCabecera">Fecha de preparación: </td>
                        <td class="tableContent">{{@$curva->Fecha_inicio}}</td>                                                
                    </tr>

                    <tr>
                        <td class="tableCabecera">m = </td>
                        <td class="tableContent">{{@$curva->M}}</td>                        
                        <td class="tableCabecera">Límite de cuantificación: </td>
                        <td class="tableContent"><{{@$model[0]->Limite}}</td>
                    </tr>

                    <tr>
                        <td class="tableCabecera">r = </td>
                        <td class="tableContent">{{@$curva->R}}</td>
                    </tr>
                {{-- @endfor --}}
            </tbody>        
        </table>  
    </div>
</body>
</html>