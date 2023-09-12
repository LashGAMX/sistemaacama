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
    <br>
    <p style="font-size: 10px">Resultado de las muestras</p>
    <div class="contenedorTabla"> 
        <div class="contenedorTabla">
            <p style="font-size: 10px">Resultado de las muestras</p>
            <table autosize="1" class="table table-borderless" id="tablaDatos">
                <thead>
                    <tr>
                        <th class="tableCabecera anchoColumna">No. de muestras</th>
                        <th class="tableCabecera anchoColumna">Volumen de muestra (mL)</th>
                        <th class="tableCabecera anchoColumna">Volumen del titulante</th>
                        <th class="tableCabecera anchoColumna">DQO mg/L</th>
                        <th class="tableCabecera anchoColumna">Observaciones</th>                    
                        <th class="anchoColumna"></th>
                        <th class="anchoColumna"></th>
                    </tr>
                </thead>
        
                <tbody>
                    @foreach ($loteDetalle as $item)
                    <tr>
                        <td class="tableContent">
                            @if (@$item->Control == 'Muestra Adicionada' || @$item->Control == 'Duplicado' || @$item->Control == 'Resultado')
                               {{@$item->Folio_servicio}}
                            @else
                                {{@$item->Control}}
                            @endif 
                        </td>
                        <td class="tableContent">{{$item->Vol_muestra}}</td>
                        <td class="tableContent">{{$item->Titulo_muestra}}</td>
                        @if ($item->Resultado < $item->Limite)
                          <td class="tableContent">< {{@$item->Limite}}</td>
                        @else
                            <td class="tableContent">{{@$item->Resultado}}</td>
                        @endif
                        <td class="tableContent">{{$item->Observacion}}</td>
                        <td class="tableContent">@if ($item->Liberado == 1)
                            Liberado
                        @else
                            No liberado
                        @endif</td>
                        <td class="tableContent">{{$item->Control}}</td>
                    </tr>
                    @endforeach
                </tbody>        
            </table>  
        </div>    
    
    </div>   

        
    <div class="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="">
            <tbody>                              
                <tr>
                    <td class="tableContent">MILILITROS TITULADOS DEL BLANCO</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent">{{@$valDqo->Vol_k2}}</td>
                </tr>

                <tr>
                    <td class="tableContent">RESULTADO BLANCO</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent">{{@$valDqo->Blanco}}</td>
                </tr>

                <tr>
                    <td class="tableContent">MILILITROS TITULADOS DE FAS 1</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent">{{@$valDqo->Vol_titulado1}}</td>
                </tr>

                <tr>
                    <td class="tableContent">MILILITROS TITULADOS DE FAS 2</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent">{{@$valDqo->Vol_titulado2}}</td>
                </tr>                

                <tr>
                    <td class="tableContent">MILILITROS TITULADOS DE FAS 3</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent">{{@$valDqo->Vol_titulado3}}</td>
                </tr>

                <tr>
                    <td class="tableContent">RESULTADO MOLARIDAD REAL</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent">{{@$valDqo->Resultado}}</td>
                </tr>
            </tbody>    
        </table>  
    </div>

    <div id="contenidoCurva">
        @php
             echo @$procedimiento[1];
        @endphp
    </div>

</body>
</html>