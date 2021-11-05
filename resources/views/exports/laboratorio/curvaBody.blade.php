<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/laboratorio/curvaPDF.css')}}">
    <title>Captura PDF</title>
</head>
<body>
    <div id="contenidoCurva">
        <?php echo html_entity_decode($textoProcedimiento->Texto);?>
    </div>
</body>
</html>