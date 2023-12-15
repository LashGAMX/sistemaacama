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
            echo @$plantilla[0]->Texto;
        @endphp
    </div> 

    <br>

    <div class="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos">
            <thead>

                <tr>
                    <th class="nombreHeader" colspan="15">
                        Resultado de las muestras
                    </th>                    
                </tr>                

                <tr>
                    <th class="tableCabecera anchoColumna">No. de muestra</th>
                    <th class="tableCabecera anchoColumna">Volumen de muestra (mL)</th>
                    <th class="tableCabecera anchoColumna">Abs 1 Turb.</th>
                    <th class="tableCabecera anchoColumna">Abs 2 Turb.</th>
                    <th class="tableCabecera anchoColumna">Abs 3 Turb.</th>
                    <th class="tableCabecera anchoColumna">Abs Prom. Turb</th>
                    <th class="tableCabecera anchoColumna">Abs 1 Sulf.</th>
                    <th class="tableCabecera anchoColumna">Abs 2 Sulf.</th>
                    <th class="tableCabecera anchoColumna">Abs 3 Sulf.</th>
                    <th class="tableCabecera anchoColumna">Abs Prom. Sulf.</th>
                    <th class="tableCabecera anchoColumna">Abs Dif.</th>
                    <th class="tableCabecera anchoColumna">SULFATOS (SO4) mg/L</th>                    
                    <th class="tableCabecera anchoColumna">Observaciones</th>
                    <th class="anchoColumna"></th>
                    <th class="anchoColumna"></th>
                </tr>
            </thead>
    
            <tbody>

                @foreach ($model as $item)
                <tr>
                    <td class="tableContent">
                        @if (@$item->Control == 'Muestra Adicionada' || @$item->Control == 'Duplicado' || @$item->Control == 'Resultado')
                            {{@$item->Folio_servicio}}
                        @else
                            {{@$item->Control}}
                        @endif                                
                    </td>
                    <td class="tableContent">{{@$item->Vol_muestra}}</td>
                    <td class="tableContent">{{number_format(@$item->Abs1, 3, ".", ".")}}</td>
                    <td class="tableContent">{{number_format(@$item->Abs2, 3, ".", ".")}}</td>
                    <td class="tableContent">{{number_format(@$item->Abs3, 3, ".", ".")}}</td>
                    <td class="tableContent">{{number_format(@$item->Abs7, 3, ".", ".")}}</td>
                    <td class="tableContent">{{number_format(@$item->Abs4, 3, ".", ".")}}</td>
                    <td class="tableContent">{{number_format(@$item->Abs5, 3, ".", ".")}}</td>
                    <td class="tableContent">{{number_format(@$item->Abs6, 3, ".", ".")}}</td>
                    <td class="tableContent">{{number_format(@$item->Abs8, 3, ".", ".")}}</td>
                    <td class="tableContent">{{number_format(@$item->Promedio, 3, ".", ".")}}</td>
                    @if ($item->Resultado < $item->Limite)
                        <td class="tableContent">< {{number_format(@$item->Limite, 3, ".", "")}}</td>
                    @else
                        <td class="tableContent">{{number_format(@$item->Resultado, 3, ".", "")}}</td>
                    @endif
                    <td class="tableContent">{{@$item->Observacion}}</td>
                    <td class="tableContent">
                        @if (@$item->Liberado == 1)
                            Liberado
                        @elseif(@$item->Liberado == 0)
                            No liberado
                        @endif     
                    </td>
                    <td class="tableContent">{{@$item->Control}}</td>
                </tr>
            @endforeach
            </tbody>        
        </table>  
    </div>

    <div class="contenedorSexto">                
        <span><br> Absorbancia B1: {{@$model[0]->Blanco}}</span> <br>
        <span>Absorbancia B2: {{@$model[0]->Blanco}}</span> <br>
        <span>Absorbancia B3: {{@$model[0]->Blanco}}</span> <br>
        <span>RESULTADO BLANCO: {{@$model[0]->Blanco}}</span>
    </div>

    <br>

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
                        <td class="tableContent">< {{number_format(@$model[0]->Limite, 3, ".", ".")}}</td>
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