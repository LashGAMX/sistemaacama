<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/informes/sinComparacion/sinComparacion.css')}}">
    
</head>
<body>
    
    <div id="contenedorTabla">
        <p id='header1' style="font-size: 10px">METODO DE PRUEBA NMX-AA-008-SCFI-2016 DETERMINACIÓN DE POTENCIAL DE HIDROGENO </p>
        <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <thead>
                <tr>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" height="30">HORA DE TOMA &nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="16.6%">&nbsp;N. MUESTRA&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="16.6%">&nbsp;CONCENTRACION Unidades de pH&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="10.6%">&nbsp;GASTO L/seg&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr">&nbsp;DECLARACION DE LA CONFORMIDAD  &nbsp;&nbsp;</td>       
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="25.6%">EVALUACIÓN DE LA CONFORMIDAD</td>
                </tr>
            </thead>
    
            <tbody>
                @php
                    $aux = 0;
                @endphp
               @foreach ($phMuestra as $item)
                   <tr>
                        <td class="tableContent bordesTablaBody">{{$item->Fecha}}</td>
                        <td class="tableContent bordesTablaBody">{{$item->Num_toma}}</td>
                        @if ($item->Activo == 1)
                            <td class="tableContent bordesTablaBody">{{$item->Promedio}}</td>
                            <td class="tableContent bordesTablaBody">{{$gasto[0]->Promedio}}</td>
                        @else
                            <td class="tableContent bordesTablaBody">----</td>
                            <td class="tableContent bordesTablaBody">----</td>
                        @endif
                        @if ($aux == 0)
                            <td class="tableContent bordesTablaBody" rowspan="{{$phMuestra->count()}}">{{$promPh}}</td>
                            <td class="tableContent bordesTablaBody" rowspan="{{$phMuestra->count()}}"></td>
                        @endif
                   </tr>
                   @php
                       $aux++;
                   @endphp
               @endforeach
            </tbody>        
        </table>  
    </div> 

    <div id="contenedorTabla">
        <p id='header1' style="font-size: 10px">METODO DE PRUEBA NMX-AA-007-SCFI-2013 DETERMINACIÓN DE POTENCIAL DE HIDROGENO  </p>
        <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <thead>
                <tr>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" height="30">HORA DE TOMA &nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="16.6%">&nbsp;N. MUESTRA&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="16.6%">&nbsp;CONCENTRACION TEMPERATURA °C&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="10.6%">&nbsp;GASTO L/seg&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr">&nbsp;DECLARACION DE LA CONFORMIDAD  &nbsp;&nbsp;</td>       
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="25.6%">EVALUACIÓN DE LA CONFORMIDAD</td>
                </tr>
            </thead>
    
            <tbody>
                @php
                    $aux = 0;
                @endphp
               @foreach ($phMuestra as $item)
                   <tr>
                        <td class="tableContent bordesTablaBody">{{$item->Fecha}}</td>
                        <td class="tableContent bordesTablaBody">{{$item->Num_toma}}</td>
                        @if ($item->Activo == 1)
                            <td class="tableContent bordesTablaBody">{{$tempMuestra[$aux]->Promedio}}</td>
                            <td class="tableContent bordesTablaBody">{{$gasto[$aux]->Promedio}}</td>
                        @else
                            <td class="tableContent bordesTablaBody">----</td>
                            <td class="tableContent bordesTablaBody">----</td>
                        @endif
                        @if ($aux == 0)
                            <td class="tableContent bordesTablaBody" rowspan="{{$phMuestra->count()}}">{{$promTemp}}</td>
                            <td class="tableContent bordesTablaBody" rowspan="{{$phMuestra->count()}}"></td>
                        @endif
                   </tr>
                   @php
                       $aux++;
                   @endphp
               @endforeach
            </tbody>        
        </table>  
    </div> 
    
    <div id="contenedorTabla">
        <p id='header1' style="font-size: 10px">METODO DE PRUEBA NMX-AA-005-SCFI-2013 DETERMINACIÓN DE GRASAS Y ACEITES </p>
        <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <thead>
                <tr>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" height="30">HORA DE TOMA &nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="16.6%">&nbsp;N. MUESTRA&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="16.6%">&nbsp;CONCENTRACION mg/L&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="10.6%">&nbsp;GASTO L/seg&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr">&nbsp;DECLARACION DE LA CONFORMIDAD  &nbsp;&nbsp;</td>       
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="25.6%">EVALUACIÓN DE LA CONFORMIDAD</td>
                </tr>
            </thead>
    
            <tbody>
                @php 
                    $aux = 0;
                @endphp
               @foreach ($phMuestra as $item)
                   <tr>
                        <td class="tableContent bordesTablaBody">{{$item->Fecha}}</td>
                        <td class="tableContent bordesTablaBody">{{$item->Num_toma}}</td>
                        @if ($item->Activo == 1)
                            <td class="tableContent bordesTablaBody">{{$grasasModel[$aux]->Resultado}}</td>
                            <td class="tableContent bordesTablaBody">{{$gasto[$aux]->Promedio}}</td>
                        @else 
                            <td class="tableContent bordesTablaBody">----</td>
                            <td class="tableContent bordesTablaBody">----</td>
                        @endif
                        @if ($aux == 0)
                            <td class="tableContent bordesTablaBody" rowspan="{{$phMuestra->count()}}">{{$promGa}}</td>
                            <td class="tableContent bordesTablaBody" rowspan="{{$phMuestra->count()}}"></td>
                        @endif
                   </tr>
                   @php
                       $aux++;
                   @endphp
               @endforeach
            </tbody>        
        </table>  
    </div>
    
   @if ($ecoliModel->count())
    <div id="contenedorTabla">
        <p id='header1' style="font-size: 10px">METODO DE PRUEBA NMX-AA-042-SCFI-2015 / NMX-AA-167-SCFI-2017 DETERMINACIÓN DE E. COLI / ENTEROCOCOS </p>
        <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <thead>
                <tr>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" height="30">HORA DE TOMA &nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="16.6%">&nbsp;N. MUESTRA&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="16.6%">&nbsp;CONCENTRACION mg/L&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="10.6%">&nbsp;GASTO L/seg&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr">&nbsp;DECLARACION DE LA CONFORMIDAD  &nbsp;&nbsp;</td>       
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="25.6%">EVALUACIÓN DE LA CONFORMIDAD</td>
                </tr>
            </thead>

            <tbody>
                @php 
                    $aux = 0;
                @endphp
            @foreach ($phMuestra as $item)
                <tr>
                        <td class="tableContent bordesTablaBody">{{$item->Fecha}}</td>
                        <td class="tableContent bordesTablaBody">{{$item->Num_toma}}</td>
                        @if ($item->Activo == 1)
                            <td class="tableContent bordesTablaBody">{{@$ecoliModel[$aux]->Resultado}}</td>
                            <td class="tableContent bordesTablaBody">{{$gasto[$aux]->Promedio}}</td>
                        @else 
                            <td class="tableContent bordesTablaBody">----</td>
                            <td class="tableContent bordesTablaBody">----</td>
                        @endif
                        @if ($aux == 0)
                            <td class="tableContent bordesTablaBody" rowspan="{{$phMuestra->count()}}">{{@$promEcoli}}</td>
                            <td class="tableContent bordesTablaBody" rowspan="{{$phMuestra->count()}}"></td>
                        @endif
                </tr>
                @php
                    $aux++;
                @endphp
            @endforeach
            </tbody>        
        </table>  
    </div> 
   @endif

</body>
</html>