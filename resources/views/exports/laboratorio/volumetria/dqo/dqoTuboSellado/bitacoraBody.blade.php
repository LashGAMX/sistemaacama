<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/exports/bitacoras.css')}}">>
    <title>Captura PDF</title>
</head>
<body>
    <p class='procedimiento'>Procedimiento</p>

    <div class="procedimiento">
        @php
            echo $textProcedimiento->Texto;
        @endphp
    </div>

    <br>

    <div class="contenedorTabla">
        <p style="font-size: 10px">Resultado de las muestras</p>
        <table autosize="1" class="table table-borderless" id="tablaDatos">
            <thead>
                <tr>
                    <th class="tableCabecera anchoColumna">No. de muestras</th>
                    <th class="tableCabecera anchoColumna">Volumen de muestra (mL)</th>
                    <th class="tableCabecera anchoColumna">Abs 1</th>
                    <th class="tableCabecera anchoColumna">Abs 2</th>
                    <th class="tableCabecera anchoColumna">Abs 3</th>
                    <th class="tableCabecera anchoColumna">Abs Promedio</th>
                    <th class="tableCabecera anchoColumna">DEMANDA QUIMICA DE OXIGENO POR MÉTODO DE TUBO SELLADO DE ALTA CONCENTRACIÓN (DQO) mg/L</th>
                    <th class="tableCabecera anchoColumna">Observaciones</th>                    
                    <th class="anchoColumna"></th>
                    <th class="anchoColumna"></th>
                </tr>
            </thead>
    
            <tbody>
                @foreach ($loteDetalle as $item)
            
                    <tr>
                        <td class="tableContent">{{$item->Codigo}}</td>
                        <td class="tableContent">{{$item->Vol_muestra}}</td>
                        <td class="tableContent">{{$item->Abs1}}</td>
                        <td class="tableContent">{{$item->Abs2}}</td>
                        <td class="tableContent">{{$item->Abs3}}</td>
                        <td class="tableContent">{{$item->Abs_prom}}</td>
                        <td class="tableContent">{{$item->Resultado}}</td>
                        <td class="tableContent">{{$item->Observacion}}</td>
                        <td class="tableContent">@if ($item->Liberado == 1)
                            Liberado
                        @else
                            No liberado
                        @endif</td>
                        <td class="tableContent">{{$item->Control}}</td>
                    </tr>
                @endforeach
            </tbody>        
        </table>  
    </div>   


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
                        <td class="tableContent"> <{{@$loteDetalle[0]->Limite}}</td>
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