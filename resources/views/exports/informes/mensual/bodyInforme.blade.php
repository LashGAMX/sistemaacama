<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/informes/conComparacion/conComparacion.css')}}">
    <title>Informe Sin Comparación.</title>
</head>

<body>
    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0" cellspacing="0"
            border-color="#000000" width="100%">
            <thead>
                <tr>
                    @if (@$tipo == 1) 
                        <td class="tableCabecera bordesTablaBody justificadoCentr" width="47%">PARAMETRO &nbsp;</td>
                    @else
                        <td class="tableCabecera bordesTablaBody justificadoCentr" width="40.9%">PARAMETRO &nbsp;</td>    
                    @endif 
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="7%">&nbsp;UNIDAD&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="14%">&nbsp;METODO DE
                        PRUEBA&nbsp;&nbsp;</td> 
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="9.45%">&nbsp;PROMEDIO DIARIO&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="9.45%">&nbsp;PROMEDIO DIARIO&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr">&nbsp;PROMEDIO MENSUAL&nbsp;&nbsp;</td>
                    @if (@$tipo == 1)
                        <td class="tableCabecera bordesTablaBody justificadoCentr" width="9%">&nbsp;P.M&nbsp;&nbsp;</td>
                    @endif
                </tr>
            </thead>

            <tbody>
                @php
                    $cont = 0; 
                @endphp
                @foreach ($model1 as $item)
                    <tr>
                        <td class="tableContentLeft bordesTablaBody">{{$item->Parametro}}<sup>{{$item->Simbologia}}</sup></td>
                        <td class="tableContent bordesTablaBody">{{$item->Unidad}}</td>
                        <td class="tableContent bordesTablaBody">
                            @switch($item->Id_parametro)
                                @case(64) 
                                @case(67)
                                    METODO DIRECTO
                                    @break
                                @default
                                {{$item->Clave_metodo}}
                            @endswitch
                        </td>
                        <td class="tableContent bordesTablaBody">
                            {{@$limitesC1[$cont]}}
                        </td>                    
                        
                        <td class="tableContent bordesTablaBody">
                            {{@$limitesC2[$cont]}}
                        </td>

                        <td class="tableContent bordesTablaBody">
                            @switch($item->Id_parametro)
                                @case(26)
                                    {{number_format(((@$gasto1->Resultado2 + @$gasto2->Resultado2) / 2), 2, ".", "")}}
                                    @break;
                                @case(2)
                                        @if (@$limitesC1[$cont] == "PRESENTE" || $limitesC2[$cont] == "PRESENTE")
                                            PRESENTE    
                                        @else
                                            AUSENTE 
                                        @endif
                                    @break;
                                @case(14)
                                    {{@$ponderado[$cont]}}
                                    @break
                                @case(67)
                                @case(97)
                                    {{@$ponderado[$cont]}}
                                    @break;
                                @default
                                {{@$ponderado[$cont]}}
                                  
                            @endswitch
                        </td>
                        @if (@$tipo == 1)
                            <td class="tableContent bordesTablaBody">
                                {{@$limitesN[$cont]}} 
                            </td>
                        @endif
                    </tr>
                    @php $cont++; @endphp
                @endforeach
                
            </tbody>
        </table>
    </div>
<br>
    <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
        <tbody>            
                <tr> 

                        <td class="nombreHeader nom fontSize727 justificadorIzq">
                        FOLIO {{$solModel1->Folio_servicio}}: OBSERVACIONES - TEMPERATURA AMBIENTE PROMEDIO DE {{round(@$tempProm1)}}°C, 
                        @php if(@$olor1 == true) {echo "LA MUESTRA PRESENTA OLOR Y COLOR ".@$color1; } else{ echo "LA MUESTRA NO PRESENTA OLOR Y COLOR ".@$color1; }@endphp
                        EL MUESTREO FUE REALIZADO DE ACUERDO A LO ESTABLECIDO EN LA NMX-AA-003-1980 Y DE ACUERDO A PROCEDIMIENTO PE-10-002-04 <br>
                        {{@$obs1->Observaciones}}
                        <br>
                        FOLIO {{$solModel2->Folio_servicio}}: OBSERVACIONES - TEMPERATURA AMBIENTE PROMEDIO DE {{round(@$tempProm2)}}°C, 
                        @php if(@$olor2 == true) {echo "LA MUESTRA PRESENTA OLOR Y COLOR ".@$color2; } else{ echo "LA MUESTRA NO PRESENTA OLOR Y COLOR ".@$color2; }@endphp
                        EL MUESTREO FUE REALIZADO DE ACUERDO A LO ESTABLECIDO EN LA NMX-AA-003-1980 Y DE ACUERDO A PROCEDIMIENTO PE-10-002-04 <br>
                        {{@$obs2->Observaciones}}

                        </td>
                </tr>                
        </tbody>         
    </table> 
    
</body>

</html>