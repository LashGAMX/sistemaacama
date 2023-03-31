<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/fq/espectro/sulfatos/sulfatosPDF.css')}}">
    <title>Captura PDF</title>
</head>
<body>

    <div id="contenidoCurva">
        @php
            // echo $plantilla->Texto;
        @endphp
    </div>

    <br>

    <div class="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos">
            <thead>

                <tr>
                    <th class="nombreHeader" colspan="15">
                        Resultado de las muestras // Necesito simbologia que usa el equipo en ICP
                    </th>                    
                </tr>                

                <tr>
                    <th class="tableCabecera anchoColumna">ID: Muestra, Control de calidad</th>
                    <th class="tableCabecera anchoColumna">Cu</th>
                    <th class="tableCabecera anchoColumna">Fe</th>
                    <th class="tableCabecera anchoColumna">Mn</th>
                    <th class="tableCabecera anchoColumna">Ba</th>
                    <th class="tableCabecera anchoColumna">Al</th>
                    <th class="tableCabecera anchoColumna">Cr</th>
                    <th class="tableCabecera anchoColumna">Ni</th>
                    <th class="tableCabecera anchoColumna">Se</th>
                    <th class="tableCabecera anchoColumna">Ag</th>
                </tr>
            </thead>
    
            <tbody>

            </tbody>        
        </table>  
    </div>

</body>
</html>