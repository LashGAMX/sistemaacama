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
            echo @$textProcedimiento[0]->Texto;
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
                    CLORUROS TOTALES (Cl¯) mg/L
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

            @foreach ($loteDetalle as $item)
            <tr>
                <td class="contenidoBody bordesTabla">
                    @if (@$item->Control == 'Muestra Adicionada' || @$item->Control == 'Duplicado' || @$item->Control == 'Resultado')
                        {{@$item->Folio_servicio}}
                    @else
                        {{@$item->Control}}
                    @endif 
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$item->Ml_muestra}}
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$item->Vol_muestra}}
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$item->Resultado}}
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$item->Ph_inicial}}
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$item->Ph_final}}
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$item->Observacion}}
                </td>

                <td class="contenidoBody">
                    @if (@$item->Liberado == 1)
                            Liberado
                        @elseif(@$item->Liberado == 0)
                            No liberado
                    @endif
                </td>

                <td class="contenidoBody">
                    {{@$item->Control}}
                </td>                        
            </tr> 
            @endforeach                               
        </tbody>
    </table>

    <br>

    <div class="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="">
            <tbody>                              
                <tr>
                    <td class="tableContent2 anchoColumna">MILILITROS TITULADOS DEL BLANCO</td>
                    <td class=""></td>
                    <td class="tableContent2 anchoColumna">{{@$valoracion->Blanco}}</td>
                </tr>                

                <tr>
                    <td class="tableContent2 anchoColumna">RESULTADO BLANCO </td>
                    <td class=""></td>
                    <td class="tableContent2 anchoColumna">{{@$valoracion->Blanco}}</td>
                </tr>                
 
                <tr>
                    <td class="tableContent2 anchoColumna">mL TITULADOS DE AgNO3 (1)</td>
                    <td class=""></td>
                    <td class="tableContent2 anchoColumna">{{@$valoracion->Ml_titulado1}}</td>
                </tr>                
                <tr>
                    <td class="tableContent2 anchoColumna">mL TITULADOS DE AgNO3 (2)</td>
                    <td class=""></td>
                    <td class="tableContent2 anchoColumna">{{@$valoracion->Ml_titulado2}}</td>
                </tr>                
                <tr>
                    <td class="tableContent2 anchoColumna">mL TITULADOS DE AgNO3 (3)</td>
                    <td class=""></td>
                    <td class="tableContent2 anchoColumna">{{@$valoracion->Ml_titulado3}}</td>
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

    <div id="contenidoCurva">
        
        @php
            echo @$textProcedimiento[1]->Texto;
        @endphp
    </div>
</body>
</html>