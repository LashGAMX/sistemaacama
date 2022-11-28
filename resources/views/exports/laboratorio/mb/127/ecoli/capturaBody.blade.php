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

        <table autosize="1" class="tabla" border="1">
            <thead>
                <tr>
                    <th>Presuntiva</th>
                    <th>Confirmativa</th>
                    <th>MORFOLOGIA COLONIAL EN AGAR EMB-L </th>
                    <th>INDOL</th>
                    <th>RM</th>
                    <th>VP</th>
                    <th>CITRATO</th>
                    <th>T.GRAM</th>
                    <th>Resultado NMP</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>1</td>
                    <td>1</td>
                    <td>1</td>
                    <td>1</td>
                    <td>1</td>
                    <td>1</td>
                    <td>1</td>
                    <td></td>
                </tr>
            </tbody>
        </table>

 
</body>

</html>