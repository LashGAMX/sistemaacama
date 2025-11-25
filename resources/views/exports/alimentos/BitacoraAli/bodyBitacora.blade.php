<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitacora de Recepcion en Area de Alimentos </title>
</head>
<style>
    .container {
        max-width: 900px;
        margin: 0 auto;
        background-color: #fff;
        padding: 10px;

    }

    h2,
    {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
    font-size: 10px;
    margin: 0;
    }

    h3 {
        margin-bottom: 20px;
        color: #333;
        font-size: 10px;
        margin: 0;
        /* Tamaño de letra para los títulos */
    }

    body {
        font-family: Arial, sans-serif;
    }

    .tabla-word {
        width: 100%;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
        font-size: 12px;

    }


    .tabla-word th {
        background-color: #4F81BD;
        /* Azul clásico Word */
        color: white;
        text-align: center;
        padding: 8px;
        border: 1px solid #ddd;
    }

    .tabla-word td {
        border: 1px solid #ddd;
        padding: 8px;
        font-size: 10px;
        text-align: left;
    }

    .tabla-word tr:nth-child(even) {
        background-color: #DCE6F1;
        /* Azul claro */
    }

    .tabla-word tr:hover {
        background-color: #B8CCE4;
        /* Azul intermedio al pasar el mouse */
    }
    .revisiones{
       font-family: Arial, Helvetica, sans-serif;
        font-size:12px;
    }
</style>

<body>

    <div class="container">
        <h2>Bitácora de Recepción de Alimentos</h2>

        <table class="tabla-word">
            <thead style="background-color: #f2f2f2; text-align: center;">
                <tr>
                    <th>Folio</th>
                    <th>Muestra</th>
                    <th>Recepcion Muestra</th>
                    <th>Hora Recepcion</th>
                    <th>Hora en Area</th>
                    <th>Recibió en Área</th>
                    <th>Resguardo En</th>
                    <th>Analizo</th>
                    <th>Fecha de inicio</th>
                    <th>Fecha de Resguardo</th>
                    <th>Lugar Resg</th>
                    <th>Analista Desecha</th>
                    <th>Fecha de Desecho</th>
                    <th>Lugar de Desecho</th>
                    <th>Fecha Muestreo</th>
                    <th>Horas Trancurridas</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($model as $item)
                <tr>
                    <td>{{ $item->Folio ?? 'N/A' }}</td>
                    <td>{{ $item->Muestra ?? 'N/A' }}</td>
                    <td>{{ $item->Recibio ?? 'N/A' }}</td>
                    <td>{{ $item->Hora_recepcion ?? 'N/A' }}</td>
                    <td>{{ $item->Fecha ?? 'N/A' }}</td>
                    <td>{{ $item->AnalistaRecep ?? 'N/A' }}</td>
                    <td>{{ $item->Resguardo ?? 'N/A' }}</td>
                    <td>{{ $item->AnalistaRes ?? 'N/A' }}</td>
                    <td>{{ $item->Fecha_inicio ?? 'N/A' }}</td>
                    <td>{{ $item->Fecha_resguardo ?? 'N/A' }}</td>
                    <td>{{ $item->Resguardo2 ?? 'N/A' }}</td>
                    <td>{{ $item->Analista_desecho ?? 'N/A' }}</td>
                    <td>{{ $item->Fecha_desecho ?? 'N/A' }}</td>
                    <td>{{ $item->Lugar_desecho ?? 'N/A' }}</td>
                    <td>{{ $item->Fecha_muestreo ?? 'N/P' }}</td>
                    <td>{{ $item->Horas ?? 'N/P' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>



    </div>

</body>

</html>