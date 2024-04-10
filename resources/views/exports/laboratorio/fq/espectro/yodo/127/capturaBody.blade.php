<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/fq/espectro/boro/boroPDF.css')}}">
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
                    <th class="nombreHeader" colspan="10">
                        Resultado de las muestras
                    </th>                    
                </tr>                

                <tr>
                    <th class="tableCabecera anchoColumna">No. de muestra</th>
                    <th class="tableCabecera anchoColumna">Volumen de muestra (mL)</th>
                    <th class="tableCabecera anchoColumna">Abs 1</th>
                    <th class="tableCabecera anchoColumna">Abs 2</th>
                    <th class="tableCabecera anchoColumna">Abs 3</th>
                    <th class="tableCabecera anchoColumna">Abs Promedio</th>
                    <th class="tableCabecera anchoColumna">Yodo (Y) mg/L</th>                    
                    <th class="tableCabecera anchoColumna">Observaciones</th>
                    <th class="anchoColumna"></th>
                    <th class="anchoColumna"></th>
                </tr>
            </thead>
    
            <tbody>
                @for ($i = 0; $i < @$model->count() ; $i++)
                    <tr>
                        <td class="tableContent">
                            @if (@$model[$i]->Id_control != 1)
                                {{@$model[$i]->Control}}
                                {{@$model[$i]->Folio_servicio}}
                            @else
                                {{@$model[$i]->Folio_servicio}}
                            @endif 
                        </td>
                        <td class="tableContent">{{@$model[$i]->Vol_muestra}}</td>
                        <td class="tableContent">{{number_format(@$model[$i]->Abs1,3)}}</td>
                        <td class="tableContent">{{number_format(@$model[$i]->Abs2,3)}}</td>
                        <td class="tableContent">{{number_format(@$model[$i]->Abs3,3)}}</td>
                        <td class="tableContent">{{number_format(@$model[$i]->Promedio,3)}}</td>
                        <td class="tableContent">
                            @if (@$model[$i]->Resultado > @$model[$i]->Limite)
                                {{number_format(@$model[$i]->Resultado,3)}}
                            @else
                                < {{@$model[$i]->Limite}}
                            @endif
                        </td>
                        <td class="tableContent">{{@$model[$i]->Observacion}}</td>
                        <td class="tableContent">
                            @if (@$model[$i]->Liberado == 1)
                                Liberado
                            @elseif(@$model[$i]->Liberado == 0)
                                No liberado
                            @endif 
                        </td>
                        <td class="tableContent">{{@$model[$i]->Control}}</td>
                    </tr>
                @endfor
            </tbody>        
        </table>  
    </div>    

    <div class="contenedorSexto">                
        <!-- <span><br> Absorbancia B1: {{@$model[0]->Blanco}}</span> <br>
        <span>Absorbancia B2: {{@$model[0]->Blanco}}</span> <br>
        <span>Absorbancia B3: {{@$model[0]->Blanco}}</span> <br>
        <span>RESULTADO BLANCO: {{@$model[0]->Blanco}}</span> -->

        <span><br> Absorbancia B1: 0</span> <br>
        <span>Absorbancia B2: 0</span> <br>
        <span>Absorbancia B3: 0</span> <br>
        <span>RESULTADO BLANCO: 0</span>
    </div>

    <br>

    <div id="contenidoCurva">
        <span id='curvaProcedimiento'>Valoración / Observación</span>
        
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