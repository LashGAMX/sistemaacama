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
             echo $procedimiento[0];
        @endphp
    </div>

    <table autosize="1" class="table table-borderless" id="tablaDatos">
        <thead>  
            <tr>
                <th class="nombreHeader" colspan="13">
                    Resultado de las muestras
                </th>
            </tr>
            
            <tr>
                <th class="tableCabecera anchoColumna">
                    No. de muestra
                </th>

                <th class="tableCabecera anchoColumna">
                    Vol. de la Muestra (ml)
                </th>

                <th class="tableCabecera anchoColumna">
                    % de dilucion expresado en decimales
                </th>

                <th class="tableCabecera anchoColumna">
                    NO. DE BOTELLA INICIAL
                </th>

                <th class="tableCabecera anchoColumna">
                    OXIGENO DISUELTO INICIAL
                </th>

                <th class="tableCabecera anchoColumna">
                    NO. BOTELLA FINAL
                </th>

                <th class="tableCabecera anchoColumna">
                    OXIGENO DISUELTO AL 5to. DIA
                </th>

                <th class="tableCabecera anchoColumna">
                    PH INICIAL
                </th>

                <th class="tableCabecera anchoColumna">
                    PH FINAL
                </th>

                <th class="tableCabecera anchoColumna">
                    DBO5 mg/L
                </th>

                <th class="tableCabecera anchoColumna">
                    Observaciones
                </th>

                <th class="anchoColumna"></th>
                    
                </th>

                <th class="anchoColumna"></th>
                    
                </th>
            </tr>                        
        </thead>
        
        <tbody>
            @foreach ($loteDetalle as $item)
            <tr>
                <td class="tableContent">
                    @if (@$item->Id_control == 5 || @$item->Id_control == 4 || @$item->Id_control == 7)
                        {{@$item->Control}}
                    @else
                        {{@$item->Codigo}}
                    @endif 
                </td>

                <td class="tableContent">
                    {{@$item->Vol_muestra}}
                </td>

                <td class="tableContent">
                    {{@$item->Dilucion}}
                </td>

                <td class="tableContent">
                    {{@$item->Botella_od}}
                </td>

                <td class="tableContent">
                    {{@$item->Odi}}
                </td>

                <td class="tableContent">
                    {{@$item->Botella_final}}
                </td>

                <td class="tableContent">
                    {{@$item->Odf}}
                </td>

                <td class="tableContent">
                    {{@$item->Ph_inicial}}
                </td>

                <td class="tableContent">
                    {{@$item->Ph_final}}
                </td>
                <td class="tableContent">
                  @if (@$item->Id_control != 5)
                          @if ($item->Resultado < $item->Limite)
                             < {{number_format(@$item->Limite, 2, ".", ".")}}
                        @else
                            {{number_format(@$item->Resultado, 2, ".", ".")}}
                        @endif
                    @else
                        @if ($item->Resultado < 2)
                             < {{number_format(2, 2, ".", ".")}}
                        @else
                            {{number_format(@$item->Resultado, 2, ".", ".")}}
                        @endif
                    @endif 
                </td>
              

            <td class="tableContent">
                    {{@$item->Observacion}}
                </td>

                <td class="tableContent">
                    @if (@$item->Liberado == 1)
                        @if (@$item->Sugerido == 0)
                            Analizado
                        @else
                            Liberado
                        @endif
                    @elseif(@$item->Liberado == 0)
                        No liberado
                    @endif
                </td>

                <td class="tableContent">
                    {{@$item->Control}}
                </td>           
            </tr>  
            @endforeach
                              
        </tbody>
    </table>

    <br>
    <br>
    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos" style="width: 100%">
            <tbody>
                <tr>
                    <th></th>

                    <th></th>
                    
                    <th class="tableContent nombreHeaderBold" colspan="2">
                        AIREAR APROX. 1 HORA
                    </th>                   

                    <th></th>                                       

                    <th class="tableContent nombreHeaderBold" colspan="2">
                        ESTANDAR BIT RE-12-001-1A-13
                    </th>                    
                </tr>

                <tr>
                    <td class="tableContent nombreHeaderBold">
                        Cantidad de agua de dilucion
                    </td>

                    <td class="tableContent">{{$detalleLote->Cant_dilucion}}</td>
                    
                    <td></td>

                    <td>
                        <span class="tableContent nombreHeaderBold">DE</span> <span class="tableContent">{{$detalleLote->De}}</span>
                    </td>

                    <td>
                        <span class="tableContent nombreHeaderBold">A</span> <span class="tableContent">{{$detalleLote->A}}</span>
                    </td>

                    <td></td>

                    <td>
                        <span class="tableContent nombreHeaderBold">Disoluciones preparadas el día</span> <span class="tableContent">{{$detalleLote->Pag}}</span>
                    </td>

                    <td>
                        <span class="tableContent nombreHeaderBold">Diluciones registrada.</span> <span class="tableContent">{{$detalleLote->N}}</span>
                    </td>                
                </tr>                

                <tr>                
                    <td>
                        <span class="tableContent nombreHeaderBold">&nbsp;Estandares preparadas el día: </span> <span class="tableContent">{{$detalleLote->Dilucion}}</span>
                    </td>
                </tr>
                <tr>                
                    <td>
                        <span class="tableContent nombreHeaderBold">&nbsp;Estandares registrados: </span> <span class="tableContent">{{$detalleLote->Estandares_bit}}</span>
                    </td>
                </tr>
            </tbody>                      
        </table>  
    </div>  

    <br>

    <div id="contenidoCurva">
        @php
             echo @$procedimiento[1];
        @endphp
    </div> 

</body>
</html>