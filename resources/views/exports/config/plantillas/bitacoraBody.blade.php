<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/mb/coliformes/coliformesPDF.css')}}">
    <title>Captura PDF</title>
</head> 
<body> 
    <div id="contenidoCurva">
        @php
            echo $procedimiento[0];
        @endphp
    </div>

    <br>

 

    <div id="contenidoCurva">
       
       @php
           echo @$procedimiento[1]
       @endphp
    </div>

    <br>

         
</body>
</html>