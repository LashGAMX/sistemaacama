<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel 8 HTML to PDF Example</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
</head>

<body class="antialiased container mt-5">
    <strong>Public Folder:</strong>
    <img src="{{ public_path('logo-blanco.png') }}" >

    <br/>
    <strong>Storage Folder:</strong>
    <div style="background: black">
        <img src="{{ storage_path('app/public/logo-blanco.png') }}">
    </div>

    <table class="table">
        <thead>
            <tr class="table-primary">
                <td>Product Name</td>
                <td>Price</td>
                <td>In Stock</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($p as $data)
            <tr>
                <td>{{ $data->product_name }}</td>
                <td>{{ $data->price }}</td>
                <td>{{ $data->in_stock }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
