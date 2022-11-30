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
        //echo @$bitacora->Texto;
        @endphp
    </div> 
    <br>
    <div id="contenedorTabla">
        <br> 
       
            <table autosize="1" class="tabla">
                <thead>
                    <tr>
                        <th>MUESTRA</th>
                        <th>POSITIVOS</th>
                        <th>COLONIA1</th>
                        <th>COLONIA2</th>
                        <th>COLONIA3</th>
                        <th>COLONIA4</th>
                        <th>COLONIA5</th>
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
                        <td>{{$item->Resultado}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        

        <table autosize="1" class="tabla" >
            <thead>
                <tr>
                    <th>MUESTRA</th>
                    <th>COLONIA</th>
                    <th>MORFOLOGIA COLONIAL EN AGAR EMB-L </th>
                    <th>INDOL</th>
                    <th>RM</th>
                    <th>VP</th>
                    <th>CITRATO</th>
                    <th>T.GRAM</th>
                    <th>Resultado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($convinaciones as $item)
                <tr>
                    <td>{{$detalle->Codigo}}</td>
                    <td>{{$item->Colonia}}</td>
                    <td>1</td>
                    <td>{{$item->Indol}}</td>
                    <td>{{$item->Rm}}</td>
                    <td>{{$item->Vp}}</td>
                    <td>{{$item->Citrato}}</td>
                    <td>{{$item->BGN}}</td>
                    <td>{{$item->Resultado}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
</body>

</html>