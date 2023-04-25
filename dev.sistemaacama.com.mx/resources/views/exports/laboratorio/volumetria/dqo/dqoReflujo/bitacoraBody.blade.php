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
                        <td class="tableContent">{{$item->Codigo}}</td>
                        <td class="tableContent">{{$item->Vol_muestra}}</td>
                        <td class="tableContent">{{$item->Titulo_muestra}}</td>
                        <td class="tableContent">{{$item->Resultado}}</td>
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
                    <td class="tableContent2">MILILITROS TITULADOS DEL BLANCO</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">{{@$valDqo->Vol_k2}}</td>
                </tr>

                <tr>
                    <td class="tableContent2">RESULTADO BLANCO</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">{{@$valDqo->Blanco}}</td>
                </tr>

                <tr>
                    <td class="tableContent2">MILILITROS TITULADOS DE FAS 1</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">{{@$valDqo->Vol_titulado1}}</td>
                </tr>

                <tr>
                    <td class="tableContent2">MILILITROS TITULADOS DE FAS 2</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">{{@$valDqo->Vol_titulado2}}</td>
                </tr>                

                <tr>
                    <td class="tableContent2">MILILITROS TITULADOS DE FAS 3</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">{{@$valDqo->Vol_titulado3}}</td>
                </tr>

                <tr>
                    <td class="tableContent2">RESULTADO MOLARIDAD REAL</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">{{@$valDqo->Resultado}}</td>
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