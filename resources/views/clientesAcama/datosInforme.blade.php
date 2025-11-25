<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Datos Informe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>

    <div class="container">
        <br>
        <div class="card">
            <div class="card-body">
                <br>
          
            <div class="container text-center">
            <div class="row">
             
                <div class="col align-self-end">
                <center><img src="http://sistemasofia.ddns.net:86/sofia/public/storage/Acama_Imagotipo.png" class="img-fluid" style="width: 30%"></center>
                </div>
            </div>
            </div>
                <table>
                    <tbody>
                        <tr>
                            <td>Folio:</td>
                            <td>{{$model->Folio_servicio}}</td>
                        </tr>
                        <tr>
                            <td>Empresa:</td>
                            <td>{{$model->Empresa_suc}}</td>
                        </tr>
                        <tr>
                            <td>Direccion:</td>
                            <td>{{@$direccion->Direccion}}</td>
                        </tr>
                        <tr>
                            <td>Punto muestreo:</td>
                            <td>{{$puntoMuestreo->Punto}}</td>
                        </tr>
                        <tr>
                            <td>Servicio:</td>
                            <td>{{$model->Servicio}}</td>
                        </tr>
                        <tr>
                            <td>Fecha muestreo:</td>
                            <td>{{$model->Fecha_muestreo}}</td>
                        </tr>
                    </tbody>
                </table>
                <br>
               
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>