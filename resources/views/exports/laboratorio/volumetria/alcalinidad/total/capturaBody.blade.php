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
                    ALCALINIDAD A LA FENOLFTALEINA  (como CaCO₃) mg/L
                </th>

                <th class="nombreHeader bordesTabla">
                    ALCALINIDAD AL ANARANJADO DE METILO (como CaCO3)     mg/L
                </th>

                <th class="nombreHeader bordesTabla">
                    ALCALINIDAD TOTAL  
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
                        {{@$loteDetalle[$i]->Titulados}}
                    </td>

                    <td class="contenidoBody bordesTabla">
                        {{@$loteDetalle[$i]->Normalidad}}
                    </td>

                    <td class="contenidoBody bordesTabla">
                        @if ($loteDetalle[$i]->Resultado != "")
                            @if (@$loteDetalle[$i]->Limite > @$loteDetalle[$i]->Resultado)
                                < {{@$loteDetalle[$i]->Limite}}
                            @else
                                {{@$loteDetalle[$i]->Resultado}} 
                            @endif
                        @else
                            
                        @endif
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

    <br>

    <div id="contenidoCurva">
        @php
             echo @$procedimiento[1];
        @endphp
    </div>
</body>
</html>