<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe Diario - Folio: {{ $folio }}</title>
    <link rel="stylesheet" href="{{ asset('/public/css/exports/orden_alimento.css') }}">
</head>

<body>
    <div class="container">
        <h1>Informe de Resultados</h1>

        <p><strong>Folio:</strong> {{ $folio }}</p>
        <p><strong>Cliente:</strong> {{ $cliente }}</p>
        <p><strong>Dirección:</strong> {{ $direccion }}</p>
        <p><strong>Contacto:</strong> {{ $contacto }}</p>
        <p><strong>Teléfono:</strong> {{ $numero }}</p>
        <p><strong>Email:</strong> {{ $email }}</p>
        <p><strong>Observaciones:</strong> {{ $observacion }}</p>
        <p><strong>Servicio:</strong> {{ $servicio }}</p>
        <p><strong>Fecha de Muestreo:</strong> {{ $fecha }}</p>
        <p><strong>Hora de Muestreo:</strong> {{ $hora }}</p>
    </div>
</body>

</html>
