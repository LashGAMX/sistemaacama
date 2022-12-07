<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/exports/bitacoras.css')}}">
    <title>Captura PDF</title>
</head>

<body>
    <div class="procedimiento">
        @php
        echo @$bitacora->Texto;
        @endphp
    </div> 
    <br>
    <div id="contenedorTabla">
        <br> 
        <br>
       
            <table autosize="1" class="tabla" border="1">
                <thead>
                    <tr>
                        <th>MUESTRA</th>
                        <th>POSITIVOS</th>
                        <th>COLONIA1</th>
                        <th>COLONIA2</th>
                        <th>COLONIA3</th>
                        <th>COLONIA4</th>
                        <th>COLONIA5</th>
                        <th>OBSERVACION</th>
                        <th>RESULTADO</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($loteDetalle as $item)
                    <tr>
                        <td>{{$item->Codigo}}</td>
                        <td>{{$item->Positivos}}</td>
                        <td>{{$item->Colonia1}}</td>
                        <td>{{$item->Colonia2}}</td>
                        <td>{{$item->Colonia3}}</td>
                        <td>{{$item->Colonia4}}</td>
                        <td>{{$item->Colonia5}}</td>
                        <td>{{$item->Observacion}}</td>
                        @if ($item->Limite > $item->Resultado)
                            <td>< 1.1</td>    
                        @else
                            <td>{{$item->Resultado}}</td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
          

            <br>
            <h5>CONVINACIONES</h5>
            
            <table autosize="1" class="tabla" border="1">
                <thead>
                    <tr>
                        <th>MUESTRA</th>
                        <th>COLONIA</th>
                        <th>INDOL</th>
                        <th>RM</th>
                        <th>VP</th>
                        <th>CITRATO</th>
                        <th>BGN</th>
                        <th>RESULTADO</th>
                    </tr>
                </thead>
                <tbody>
                   @foreach ($convinaciones as $item)
                    <tr>
                        <td>{{$item->Codigo}}</td>
                        <td>{{$item->Colonia}}</td>
                        <td>{{$item->Indol}}</td>
                        <td>{{$item->Rm}}</td>
                        <td>{{$item->Vp}}</td>
                        <td>{{$item->Citrato}}</td>
                        <td>{{$item->BGN}}</td>
                        <td>{{$item->ResUno}}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>{{$item->Colonia}}(2)</td>
                        <td>{{$item->Indol2}}</td>
                        <td>{{$item->Rm2}}</td>
                        <td>{{$item->Vp2}}</td>
                        <td>{{$item->Citrato2}}</td>
                        <td>{{$item->BGN2}}</td>
                        <td>{{$item->ResDos}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
</body>

</html>