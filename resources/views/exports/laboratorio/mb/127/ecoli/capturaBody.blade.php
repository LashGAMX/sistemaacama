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
                    <th colspan="7">E. Coli</th>
                    <th colspan="2" rowspan="2">MOROFOLOGIA COLONIAL <br>EN AGAR EMB-L</th>
                    <th colspan="10">PRUEBAS BIOQIMICAS</th>
                    <th rowspan="2">Resultado <br> NMP</th>
                </tr>
                <tr>
                    <th colspan="4" rowspan="2">Presuntiva</th>
                    <th colspan="3" rowspan="2">Confirmativa</th>
                    <th colspan="2" rowspan="2">INDOL</th>
                    <th colspan="2" rowspan="2">RM</th>
                    <th colspan="2" rowspan="2">VP</th>
                    <th colspan="2" rowspan="2">CITRATO</th>
                    <th colspan="2" rowspan="2">T.GRAM</th>
                </tr>
                
            </thead>
            <tbody>
                <tr>
                    <td>Total tubos</td>
                    <td>Positivo 24h</td>
                    <td>Positivo 48h</td>
                    <td>Resultado</td>
                    <td>Positivo 24h</td>
                    <td>Positivo 48h</td>
                    <td>Resultado</td>
                    <td>Tubo 1</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

 
</body>

</html>