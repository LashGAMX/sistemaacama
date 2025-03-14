<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de Servicio - Folio: {{ $folio }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/public/css/exports/orden_alimento.css') }}">
</head>

<body>
    <div class="container">
        <div class="header">
            <label>Solicitud de Servicio de Análisis de Alimentos</label>
        </div>

        <div class="details">
            <table>
                <tr>
                    <td><strong>No. DE ORDEN</strong></td>
                    <td>{{ $folio }}</td>
                </tr>
            </table>
        </div>
        <div class="cuerpo">
            <table class="table-1x1">
                <tr>
                    <th class="encabezado">NOMBRE DE LA EMPRESA:</th>
                    <td>{{ $cliente }}</td>
                </tr>
            </table>

            <table class="table-1x1">
                <tr>
                    <th class="encabezado">DIRECCIÓN:</th>
                    <td>{{ $direccion }}</td>
                </tr>
            </table>

            <table class="table-1x1">
                <tr>
                    <th class="encabezado">CONTACTO:</th>
                    <td>{{ $contacto }}</td>
                </tr>
            </table>

            <table class="table-1x1">
                <tr>
                    <th class="encabezado">TELÉFONO Y CORREO <br> ELECTRONICO:</th>
                    <td>{{ $numero }}</td>
                    <td>{{ $email }}</td>
                </tr>
            </table>
            <table class="table-1x1">
                <tr>
                    <th class="encabezado">SERVICIO:</th>
                    <td>{{ $servicio }}</td>
                </tr>
            </table>
            <table class="table-1x1">
                <tr>
                    <th class="encabezado">PARAMETROS:</th>
                    <!-- <td>{{ $servicio }}</td> -->
                    <td>
                        @foreach ($muestras as $item)
                            Muestras : {{$item->Muestra}} <br>
                            @php
                                $temp = DB::table('ViewSolicitudParametrosAli')->where('Id_solicitud',$item->Id_muestra)->get();
                            @endphp
                            Parametros: @foreach ($temp as $item2)
                                {{$item2->Parametro}} , 
                            @endforeach

                        @endforeach
                    </td>
                </tr>
            </table>
            <table class="table-1x1">
                <tr>
                    <th class="encabezado">FECHA Y HORA DE MUESTREO:</th>
                    <td>{{ $fecha }}</td>
                    <td>{{ $hora }}</td>
                </tr>
            </table>
            <table class="table-1x1">
                <tr>
                    <th class="encabezado">OBSERVACIÓN:</th>
                    <td>{{ $observacion }}</td>
                </tr>
            </table>

        </div>
        <br><br><br>
        <div class="Autor">
            <div class="seccion ">
                <label for="elaboro"><strong>ELABORO:</strong></label>
                <p class="subrayado">ESMERALDA SABINO V.</p>
            </div>

        </div>



        <div class="footer">
            <p>Este es un documento generado por pruebas del sistema. Por favor, no lo tomen en cuenta hasta que se le
                indique. By: Netzair</p>
        </div>
    </div>
</body>

</html>