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
            echo $plantilla[0]->Texto; 
        @endphp
    </div>

    <br>

    <div class="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos">
            <thead>

                <tr>
                    <th class="nombreHeader" colspan="13">
                        Resultado de las muestras 
                    </th>                    
                </tr>                

                <tr>
                    <th colspan="6"></th>
                    <th colspan="3" style="border: 1px;font-size: 8px;">Interferencias</th>
                </tr>
                <tr>
                    <th class="tableCabecera anchoColumna">No. de muestra</th>
                    <th class="tableCabecera anchoColumna">Volumen de muestra (mL)</th>
                    <th class="tableCabecera anchoColumna">Abs 1</th>
                    <th class="tableCabecera anchoColumna">Abs 2</th>
                    <th class="tableCabecera anchoColumna">Abs 3</th>
                    <th class="tableCabecera anchoColumna">Abs Promedio</th>
                    <th class="tableCabecera anchoColumna">Sulfuros</th>
                    <th class="tableCabecera anchoColumna">Nitratos</th>
                    <th class="tableCabecera anchoColumna">Nitritos</th>
                    <th class="tableCabecera anchoColumna">CIANUROS (CN ̄)
                        mg/L</th>
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
                    <td class="tableContent">{{@$item->Abs1}}</td>
                    <td class="tableContent">{{@$item->Abs2}}</td>
                    <td class="tableContent">{{@$item->Abs3}}</td>
                    <td class="tableContent">{{@$item->Promedio}}</td>
                    <td class="tableContent">
                        @if (@$item->Sulfuros == NULL)
                            -----        
                        @else
                            {{@$item->Sulfuros}}
                        @endif
                    </td>
                    <td class="tableContent">
                        @if (@$item->Nitratos == NULL)
                        -----        
                        @else
                            {{@$item->Nitratos}}
                        @endif
                    </td> 
                    <td class="tableContent">
                        @if (@$item->Nitritos == NULL)
                        -----        
                        @else
                            {{@$item->Nitritos}}
                        @endif
                    </td>
                    @if ($item->Resultado <= $item->Limite)
                        <td class="tableContent">< {{@$item->Limite}}</td>
                    @else
                        <td class="tableContent">{{@$item->Resultado}}</td>
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
        <span><br> Absorbancia B1: 0</span> <br><br>
        <span>Absorbancia B2: 0</span> <br><br>
        <span>Absorbancia B3: 0</span> <br><br>
        <span>RESULTADO BLANCO: 0</span>
    </div>

    <div id="contenidoCurva">
        {{-- <span id='curvaProcedimiento'>Valoración</span>  --}}
       @php
           echo @$plantilla[1]->Texto; 
       @endphp
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
                        <td class="tableContent"> <{{@$limiteC->Limite}}</td>
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