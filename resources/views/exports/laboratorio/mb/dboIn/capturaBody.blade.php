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
                    Vol. de la Muestra
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
                    Oxigeno disuelto inicial del blanco con inóculo
                </th>
                <th class="tableCabecera anchoColumna">
                    Oxigeno disuleto al 5to
                </th>
                <th class="tableCabecera anchoColumna">
                    Oxigeno disuleto al 5to día del blanco con inóculo
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
                    {{@$item->Porcentaje_dilucion}}
                </td>

                <td class="tableContent">
                    {{@$item->Botella_od}}
                </td>

                <td class="tableContent">
                    {{@$item->Oxigeno_inicial }}
                </td>

                <td class="tableContent">
                    {{@$item->Botella_fin}}
                </td>

                <td class="tableContent">
                    {{@$item->Oxigeno_disueltoini}}
                </td>

                <td class="tableContent">
                    {{@$item->Oxigeno_final}}
                </td>
                <td class="tableContent">
                    {{@$item->Oxigeno_disueltofin}}
                </td>
                <td class="tableContent">
                    {{@$item->Ph_inicial}}
                </td>
                <td class="tableContent">
                    {{@$item->Ph_final}}
                </td>
                @if ($item->Resultado < $item->Limite)
                  <td class="tableContent">< {{number_format(@$item->Limite, 2, ".", ".")}}</td>
                @else
                    <td class="tableContent">{{number_format(@$item->Resultado, 2, ".", ".")}}</td>
                @endif

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
    <div id="contenidoCurva">
        @php
             echo @$procedimiento[1];
        @endphp
    </div>

    <br>
    <br>

</body>
</html>