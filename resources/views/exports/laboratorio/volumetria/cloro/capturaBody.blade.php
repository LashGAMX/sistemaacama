<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/fq/volumetria/cloroR/cloroRPDF.css')}}">
    <title>Captura PDF</title>
</head>
<body>   
    <p id='curvaProcedimiento'>Procedimientos</p>

    <div id="contenidoCurva">
        @php
             echo $procedimiento[0];
        @endphp
    </div>

    <table cellpadding="0" cellspacing="0" border-color="#000000">
        <thead>  
            <tr>
                <th class="nombreHeader" colspan="13">
                    Resultado de las muestras
                </th>
            </tr> 
            
            <tr>
                <th class="nombreHeader bordesTabla">
                    No. de muestra
                </th>

                <th class="nombreHeader bordesTabla">
                    Volumen de muestra (mL)
                </th>

                <th class="nombreHeader bordesTabla">
                    Vol. Titulante (mL)
                </th>

                <th class="nombreHeader bordesTabla">
                    CLORO RESIDUAL mg/L
                </th>

                <th class="nombreHeader bordesTabla">
                    pH inicial de la
                </th>

                <th class="nombreHeader bordesTabla">
                    pH final de la muestra
                </th>

                <th class="nombreHeader bordesTabla">
                    Observaciones
                </th>                

                <th class="nombreHeader">
                    
                </th>

                <th class="nombreHeader">
                    
                </th>
            </tr>                        
        </thead>
        
        <tbody>
            @for ($i = 0; $i < @$loteDetalle->count() ; $i++)
                <tr>
                    <td class="contenidoBody bordesTabla">
                        @if (@$loteDetalle[$i]->Control == 'Muestra Adicionada' || @$loteDetalle[$i]->Control == 'Duplicado' || @$loteDetalle[$i]->Control == 'Resultado')
                            {{@$loteDetalle[$i]->Folio_servicio}}
                        @else
                            {{@$loteDetalle[$i]->Control}}
                        @endif 
                    </td>

                    <td class="contenidoBody bordesTabla">
                        {{@$loteDetalle[$i]->Ml_muestra}}
                    </td>

                    <td class="contenidoBody bordesTabla">
                        {{@$loteDetalle[$i]->Vol_muestra}}
                    </td>

                    <td class="contenidoBody bordesTabla">
                        @if (@$loteDetalle[$i]->Limite > @$loteDetalle[$i]->Resultado)
                            <{{@$loteDetalle[$i]->Limite}}
                        @else
                            {{@$loteDetalle[$i]->Resultado}}
                        @endif
                    </td>

                    <td class="contenidoBody bordesTabla">
                        {{@$loteDetalle[$i]->Ph_inicial}}
                    </td>

                    <td class="contenidoBody bordesTabla">
                        {{@$loteDetalle[$i]->Ph_final}}
                    </td>

                    <td class="contenidoBody bordesTabla">
                        {{@$loteDetalle[$i]->Observacion}}
                    </td>

                    <td class="contenidoBody">
                        @if (@$loteDetalle[$i]->Liberado == 1)
                                Liberado
                            @elseif(@$loteDetalle[$i]->Liberado == 0)
                                No liberado
                        @endif
                    </td>

                    <td class="contenidoBody">
                        {{@$loteDetalle[$i]->Control}}
                    </td>                        
                </tr> 
            @endfor                                   
        </tbody>
    </table>

    <br>

    <div class="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="">
            <tbody>                              
                <tr>
                    <td class="tableContent2 anchoColumna">mL TITULADOS DE TIOSULFATO DE SODIO (1)</td>
                    <td class=""></td>
                    <td class="tableContent2 anchoColumna">{{@$valoracion->Ml_titulado1}}</td>
                </tr>                

                <tr>
                    <td class="tableContent2 anchoColumna">mL TITULADOS DE TIOSULFATO DE SODIO (2)</td>
                    <td class=""></td>
                    <td class="tableContent2 anchoColumna">{{@$valoracion->Ml_titulado1}}</td>
                </tr>                

                <tr>
                    <td class="tableContent2 anchoColumna">mL TITULADOS DE TIOSULFATO DE SODIO (3)</td>
                    <td class=""></td>
                    <td class="tableContent2 anchoColumna">{{@$valoracion->Ml_titulado1}}</td>
                </tr>                

                <tr>
                    <td class="tableContent2 anchoColumna">RESULTADO NORMALIDAD REAL</td>
                    <td class=""></td>
                    <td class="tableContent2 anchoColumna">{{@$valoracion->Resultado}}</td>
                </tr>
            </tbody>    
        </table>  
    </div>

    <br>

</body>
</html>