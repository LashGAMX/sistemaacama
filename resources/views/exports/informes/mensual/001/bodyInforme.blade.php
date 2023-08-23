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
                    @if (@$tipo == 1)
                        <td class="tableCabecera bordesTablaBody justificadoCentr" width="35%">PARAMETRO &nbsp;</td>
                    @else
                        <td class="tableCabecera bordesTablaBody justificadoCentr" width="40.9%">PARAMETRO &nbsp;</td>    
                    @endif 
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="7%">&nbsp;UNIDAD&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="14%">&nbsp;METODO DE
                        PRUEBA&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="9.45%">&nbsp;PROMEDIO DIARIO&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="9.45%">&nbsp;PROMEDIO DIARIO&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="10%" style="font-size: 8x">&nbsp;PROMEDIO MENSUAL&nbsp;&nbsp;</td>
                    @if (@$tipo == 1)
                        <td class="tableCabecera bordesTablaBody justificadoCentr" width="9%">&nbsp;DECLARACION DE LA CONFORMIDAD &nbsp;&nbsp;</td>
                        <td class="tableCabecera bordesTablaBody justificadoCentr" width="9%">&nbsp;EVALUACIÓN DE LA CONFORMIDAD &nbsp;&nbsp;</td>
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
                        <td class="tableContent bordesTablaBody">{{$item->Clave_metodo}}</td>
                        <td class="tableContent bordesTablaBody">
                            {{@$limitesC1[$cont]}}
                        </td>                    
                        
                        <td class="tableContent bordesTablaBody">
                            {{@$limitesC2[$cont]}}
                        </td>
                        <td class="tableContent bordesTablaBody">
                            @switch($item->Id_parametro)
                                @case(26)
                                    {{(@$gasto1->Resultado2 + @$gasto2->Resultado2) / 2;}}
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
                                @default
                                    @if (@$item->Limite != "N.A" || @$item->Limite != "N.N" || @$item->Limite != "N/A" || @$item->Limite != "N.A.")
                                        @if (@$ponderado[$cont] <= @$item->Limite)
                                            < {{@$item->Limite}}
                                        @else
                                            {{@$ponderado[$cont]}}
                                        @endif
                                    @else
                                        {{@$ponderado[$cont]}}
                                    @endif
                            @endswitch
                        </td>
                        @php
                            $aux = 0;
                        @endphp
                        @if (@$tipo == 1)
                            <td class="tableContent bordesTablaBody">
                                @if (@$limitesN[$cont] == "N.N" || @$limitesN[$cont] == "N/A")
                                     @php
                                         $aux = 1;
                                     @endphp
                                 @endif
                                {{@$limitesN[$cont]}}
                            </td>
                            <td class="tableContent bordesTablaBody">
                                @if ($aux == 1)
                                    ---
                                @else
                                    @switch($item->Id_parametro)
                                        @case(14)
                                            @php
                                                $tempPh = explode('-',$limitesN[$cont]); 
                                            @endphp
                                                @if (@$ponderado[$cont] >= $tempPh[0] && @$ponderado[$cont] <= $tempPh[1]) CUMPLE @else NO CUMPLE @endif
                                            @break
                                        @default
                                            @if (@$item->Limite == "N.A" || @$item->Limite == "N.N" || @$item->Limite == "N/A" || @$item->Limite == "N.A.")
                                                N.A
                                            @else
                                                @if (@$ponderado[$cont] <= $limitesN[$cont] ) CUMPLE @else NO CUMPLE @endif
                                            @endif
                                    @endswitch
                                @endif
                            </td>
                        @endif
                     
                    </tr>
                    @php $cont++; @endphp
                @endforeach
            </tbody>
        </table>
    </div>

    <br>

    <div autosize="1" class="" cellpadding="0" cellspacing="0" border-color="#000000">

        <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <tbody>            
                    <tr> 

                            <td class="nombreHeader nom fontSize727 justificadorIzq">
                            FOLIO {{$solModel1->Folio_servicio}}: OBSERVACIONES - TEMPERATURA AMBIENTE PROMEDIO DE {{round(@$tempProm1)}}°C, 
                            @php if(@$olor1 == true) {echo "LA MUESTRA PRESENTA OLOR Y COLOR ".@$color1;; } else{ echo "LA MUESTRA NO PRESENTA OLOR Y COLOR ".@$color1; }@endphp
                            EL MUESTREO FUE REALIZADO DE ACUERDO A LO ESTABLECIDO EN LA NMX-AA-003-1980 Y DE ACUERDO A PROCEDIMIENTO PE-10-002-04 <br>
                            {{@$obs1->Observaciones}}
                            <br>
                            FOLIO {{$solModel2->Folio_servicio}}: OBSERVACIONES - TEMPERATURA AMBIENTE PROMEDIO DE {{round(@$tempProm2)}}°C, 
                            @php if(@$olor2 == true) {echo "LA MUESTRA PRESENTA OLOR Y COLOR ".@$color1;; } else{ echo "LA MUESTRA NO PRESENTA OLOR Y COLOR ".@$color2; }@endphp
                            EL MUESTREO FUE REALIZADO DE ACUERDO A LO ESTABLECIDO EN LA NMX-AA-003-1980 Y DE ACUERDO A PROCEDIMIENTO PE-10-002-04 <br>
                            {{@$obs2->Observaciones}}

                        </td>
                    </tr>                
            </tbody>         
        </table> 

        <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <tbody>            
                    <tr>
                        <td class="nombreHeaders fontBold fontSize5 justificadorIzq" colspan="2">

                            @php
                                echo $reportesInformes->Nota;

                            @endphp
                        </td>
                    </tr>
   
            </tbody>         
        </table>                                                        
    </div>
    
    <div id="contenedorTabla">
        @php
            $temp = array();
            $sw = false;
        @endphp
        <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <tbody>            
                    @foreach ($model1 as $item)
                        @for ($i = 0; $i < sizeof($temp); $i++)
                            @if ($temp[$i] == $item->Id_simbologia_info)
                                @php $sw = true; @endphp
                            @endif
                        @endfor
                        @if ($sw != true)
                            @switch($item->Id_parametro)
                                @case(97)
                                    <tr>
                                        <td class="nombreHeaders fontBold fontSize5 justificadorIzq">{{$item->Simbologia_inf}} @php print  $item->Descripcion2; @endphp</td>
                                    </tr>
                                    <tr>
                                        <td class="nombreHeaders fontBold fontSize5 justificadorIzq">*** LA DETERMINACIÓN DE LA TEMPERATURA DE LA MUESTRA COMPUESTA ES DE {{round(@$campoCompuesto1->Temp_muestraComp)}}°C Y EL PH COMPUESTO ES DE {{ number_format(@$campoCompuesto1->Ph_muestraComp, 2, ".", ".")}} FOLIO {{$numOrden1->Folio_servicio}} </td>
                                    </tr>
                                    <tr>
                                        <td class="nombreHeaders fontBold fontSize5 justificadorIzq">*** LA DETERMINACIÓN DE LA TEMPERATURA DE LA MUESTRA COMPUESTA ES DE {{round(@$campoCompuesto2->Temp_muestraComp)}}°C Y EL PH COMPUESTO ES DE {{ number_format(@$campoCompuesto2->Ph_muestraComp, 2, ".", ".")}} FOLIO {{$numOrden2->Folio_servicio}} </td>
                                    </tr>
                                    @php
                                        array_push($temp,$item->Id_simbologia_info);
                                    @endphp
                                    @break
                                @default
                                    @if ($item->Id_simbologia_info != 9)
                                        <tr>
                                            <td class="nombreHeaders fontBold fontSize5 justificadorIzq">{{$item->Simbologia_inf}} @php print  $item->Descripcion2; @endphp</td>
                                        </tr>
                                        @php
                                            array_push($temp,$item->Id_simbologia_info);
                                        @endphp
                                    @endif
                            @endswitch
                           
                        @endif
                        @php
                            $sw = false;
                        @endphp
                    @endforeach
                                        
                    </tr>
                    <tr>
                        <td>
                            @php
                            /*$url = url()->current();*/
                            $url = "https://sistemaacama.com.mx/clientes/informeMensualSinComparacion/".@$solModel1->Id_solicitud;
                            $qr_code = "data:image/png;base64," . \DNS2D::getBarcodePNG((string) $url, "QRCODE");
                        @endphp
                        <br>
                        
                        <img style="width: 5%; height: 5%;float: right;" src="{{@$qr_code}}" alt="qrcode" /> <br> <span class="fontSize9 fontBold"> {{@$solModel->Folio_servicio}}</span>
                        </td>
                    </tr>
                    
            </tbody>         
           
        </table>  
 
    </div>    

    
</body>


</html>