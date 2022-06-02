<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/informes/conComparacion/conComparacion.css')}}">
    <title>Informe Sin Comparación</title>
</head>

<body>
    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0" cellspacing="0"
            border-color="#000000" width="100%">
            <thead>
                <tr>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="47%">PARAMETRO &nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="7%">&nbsp;UNIDAD&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="14%">&nbsp;METODO DE
                        PRUEBA&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="9.45%">&nbsp;PROMEDIO DIARIO&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="9.45%">&nbsp;PROMEDIO DIARIO&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr">&nbsp;CONCENTRACION PROMEDIO MENSUAL
                        PONDERADO&nbsp;&nbsp;</td>
                </tr>
            </thead>

            <tbody>
                @for ($i = 0; $i <@$modelLength ; $i++) 
                    <tr>
                        <td class="tableContentLeft bordesTablaBody">{{@$model[$i]->Parametro}}<sup>{{@$sParam[$i]}}</sup></td>
                        <td class="tableContent bordesTablaBody">{{@$model[$i]->Unidad}}</td>
                        <td class="tableContent bordesTablaBody">{{@$model[$i]->Clave_metodo}}</td>
                        <td class="tableContent bordesTablaBody">
                            @if (strpos(@$limitesC[$i], "< AUS") === 0 || strpos(@$model[$i]->Unidad, "AUS") === 0) 
                                AUSENTE
                            @else 
                                @if (@$model[$i]->Parametro == 'Grasas y Aceites ++')
                                    @php
                                        if(@$limExceed1 === 1 || @$limExceed2 === 1){
                                            echo "< ".@$limGras;
                                        }else{
                                            echo round(@$sumaCaudalesFinal, 2);
                                        }                                        
                                    @endphp
                                @elseif (@$model[$i]->Parametro == 'Coliformes Fecales +')
                                    @php
                                        if(@$limExceedColi1 === 1 || @$limExceedColi2 === 1){
                                            echo "< ".@$limColi;
                                        }else{                                            
                                            echo round(@$resColi, 2);
                                        }                                        
                                    @endphp 
                                @elseif (@$model[$i]->Id_parametro === 7)
                                    @php
                                        if(@$limExceedDqo1 === 1 || @$limExceedDqo2 === 1){
                                            echo "< ".@$limDqo;
                                        }else{                                            
                                            echo round(@$dqoFinal1, 2);
                                        }                                        
                                    @endphp
                                @else
                                    {{@$limitesC[$i]}}
                                @endif
                            @endif
                        </td>                    
                        
                        <td class="tableContent bordesTablaBody">
                            @if (strpos(@$limites2C[$i], "< AUS") === 0 || strpos(@$model[$i]->Unidad, "AUS") === 0) 
                                AUSENTE 
                            @else 
                                @if (@$model2[$i]->Parametro == 'Grasas y Aceites ++')
                                    @php
                                        if(@$limExceed1 === 1 || @$limExceed2 === 1){
                                            echo "< ".@$limGras;
                                        }else{
                                            echo round(@$sumaCaudalesFinal2, 2);
                                        }                                          
                                    @endphp
                                @elseif (@$model2[$i]->Parametro == 'Coliformes Fecales +')
                                    @php
                                        if(@$limExceedColi1 === 1 || @$limExceedColi2 === 1){
                                            echo "< ".@$limColi;
                                        }else{                                            
                                            echo round(@$resColi2, 2); 
                                        }                                         
                                    @endphp
                                @elseif (@$model2[$i]->Id_parametro === 7)
                                    @php
                                        if(@$limExceedDqo1 === 1 || @$limExceedDqo2 === 1){
                                            echo "< ".@$limDqo;
                                        }else{                                            
                                            echo round(@$dqoFinal2, 2);
                                        }                                        
                                    @endphp
                                @else
                                    {{@$limites2C[$i]}}
                                @endif
                            @endif
                        </td>

                        <td class="tableContent bordesTablaBody">
                            @if (@$model[$i]->Id_parametro === 14)
                                @php
                                    @$promPonderado = (@$sumaCaudalesFinal + @$sumaCaudalesFinal2) / 2;
                                    
                                    if(@$promPonderado < @$lGras){
                                        echo "< ".@$lGras;
                                    }else{
                                        echo @$promPonderado;
                                    }
                                @endphp
                                @elseif (@$model[$i]->Id_parametro === 13)
                                    @php
                                        @$promPonderado = (@$resColi + @$resColi2) / 2;
                                    
                                        if(@$promPonderado < @$lColi){
                                            echo "< ".@$lColi;
                                        }else{
                                            echo @$promPonderado;
                                        }
                                @endphp
                            @elseif (@$model[$i]->Id_parametro === 13)
                                @php
                                    @$promPonderado = (@$dqoFinal1 + @$dqoFinal2) / 2;
                                    
                                    if(@$promPonderado <= @$lDqo){
                                        echo "< ".@$lDqo;
                                    }else{
                                        echo @$promPonderado;
                                    }
                                @endphp
                            @else
                                @php
                                    $promPonderado = (@$resultsParam[$i] + @$resultsParam2[$i]) / 2;
                                    $promPonderado = round(@$promPonderado, 2);

                                    if(@$promPonderado < @$limitesNorma[$i]){
                                        echo "< ".round(@$limitesNorma[$i], 3);
                                    }else{
                                        echo @$promPonderado;
                                    }
                                @endphp
                            @endif
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</body>

</html>