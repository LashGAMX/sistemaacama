<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe de Resultados de Analisis de Alimentos </title>
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

    #info {
        width: 100%;
        border-collapse: collapse;
    }

    #info {
        width: 100%;
        border: 2px solid black;
        /* Contorno de la tabla */
        border-collapse: collapse;
    }

    #info td {
        border: none;
        /* Elimina los bordes de las celdas */
        padding: 1px;
        text-align: left;
        font-size: 10px;
        font-family: Arial, Helvetica, sans-serif;
    }

    #info tr td:nth-child(4),
    #info tr td:nth-child(5) {
        width: 50px;
        text-align: left;
    }

    #info tr td:nth-child(2),
    #info tr td:nth-child(4),
    #info tr td:nth-child(6) {
        text-align: left;
    }



    #descripcion {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        font-size: 10px;
        font-family: Arial, Helvetica, sans-serif;
    }

    #descripcion td {
        border: 2px solid black;
        padding: 5px;
        text-align: left;
        vertical-align: middle;
    }

    #descripcion td[colspan="6"] {
        font-weight: bold;
        text-align: left;
    }

    #descripcion td[rowspan="2"] {
        vertical-align: middle;
    }

    #descripcion .footer {
        text-align: left;
        font-size: 12px;
        font-style: italic;
        padding: 5px;
        border-top: 1px solid black;
    }

    #parametro {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    #parametro td,
    #parametro th {
        border: 2px solid black;
        padding: 10px;
        font-size: 10px;
        text-align: center;
        vertical-align: middle;
    }

    #parametro .small-col {
        width: 10%;
        /* Ancho de las columnas pequeñas */
    }

    #parametro .large-col {
        width: 40%;
        /* Ancho de la columna grande */
    }

    #obs {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
        text-align: left;
        vertical-align: middle;
    }

    #obs td,
    #obs th {
        border: 2px solid #000;
        padding: 8px;
        text-align: left;
    }

    #obs tr:first-child td,
    #obs tr:nth-child(2) td {
        width: 50%;
    }

    #obs tr:nth-child(3) td {
        width: 25%;
    }


    #autor {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
        text-align: center;
        vertical-align: middle;
        margin: 20px
    }

    #autor td,
    #autor th {
        border: 0px solid #ffffff;
        padding: 8px;
        text-align: center;
    }




    #nota {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
        text-align: left;
        vertical-align: left;
        margin: 25px font-size: 7px;

    }

    #nota td,
    #nota th {
        border: 2px solid #000000;
        padding: 8px;
        text-align: left;
    }
</style>

<body>
    <div class="container">
        <h2>Informe de Resultado de Análisis</h2>

        <h3>No. de Orden: {{$solicitud->Folio}}</h3>
        <h3>No. de Muestra: {{$muestra->Muestra}}</h3>
        <table id="info">
            <tr>
                <td>Empresa:</td>
                <td colspan="3">{{$proceso->Empresa}}</td>
                <td>Fecha de recepción: </td>
                <td colspan="3">{{$proceso->Hora_recepcion}}</td>
            </tr>
            <tr>
                <td>Dirección:</td>
                <td colspan="3">{{$solicitud->Direccion}}</td>
                <td>Periodo de análisis:</td>
                <td></td>
                <td>al</td>
                <td></td>
            </tr>
            <tr>
                <td>Atención a:</td>
                <td colspan="3">{{$solicitud->Atencion}}</td>
                <td>Fecha de reporte:</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <table id="descripcion">
            <tr>
                <td colspan="7">Descripción de la muestra:</td>
            </tr>
            <tr>
                <td rowspan="2">Fecha y hora de muestreo:</td>
                <td colspan="3" rowspan="2"></td>
                <td colspan="2">Temperatura °C:</td>
                <td colspan="1"></td>

            </tr>
            <tr>
                <td colspan="2">Cantidad de muestra (mL):</td>
                <td colspan="1"></td>

            </tr>
            <tr>
                <td>Método de muestreo:</td>
                <td colspan="6">Muestra remitida por el cliente</td>
            </tr>
            <tr>
                <td colspan="7" class="footer" style="font-size: 10px;">
                    <strong>Norma de Especificación:</strong> NOM-245-SSA1-2010, Requisitos sanitarios y calidad del
                    agua que deben cumplir las albercas
                </td>
            </tr>

            <!-- <tr>
                <td colspan="7" class="footer">
            </tr> -->
        </table>
        <br>
        <table id="parametro">
            <tr>
                <th class="large-col">Parárametro</th>
                <th>Método de prueba</th>
                <th>Unidad</th>
                <th>Resultado</th>
                <th class="small-col ">Limite</th>
                <th class="small-col ">Analizó</th>
            </tr>
            @foreach ($codigo as $item)
            <tr>
                <td>{{$item->Parametro}}</td>
                <td>{{$item->Clave_metodo}}</td>
                <td>{{$item->Unidad}}</td>
                <td>{{$item->Resultado2}}</td>
                <td class="small-col ">{{$item->Limite}}</td>
                <td class="small-col ">{{$item->iniciales}}</td>
            </tr>
            @endforeach
        </table>
        <br>
        <table id="obs">
            <tr>
                <td colspan="4">Observaciones:</td>
              
            </tr>
            <tr>
                <td colspan="4">Temperatura:</td>
               
            </tr>

            <tr>
                <td colspan="2" >PH:</td>
             
                <td colspan="2">Cloro Libre:</td>
              
            </tr>

            <tr>
                <td colspan="4">1 REG. ACREDIT. ENTIDAD MEXICANA DE ACREDITACIÓN ema No. AG-057-025/12, CONTINUARÁ
                    VIGENTE</td>
            </tr>
        </table>

        <table id="autor">
            <tr>
                <td colspan="2">Autorizó: <br>
                    Biol. Elsa Rivera Rivera</td>
                <td colspan="2">Revisó: <br>
                    Biol. Guadalupe Garcia Perez</td>
            </tr>
        </table>

        <table id="nota">
            <tr>
                <td colspan="2">Este informe de resultados no debe reproducirse sin la aprobación por escrito del
                    laboratorio emisor <br>
                    La información de dirección, empresa, atención a y descripción de muestras son proporcionados por el
                    cliente <br>
                    Los resultados expresados avalan únicamente a la muestra analizada bajo las condiciones de recepción
                    en el laboratorio aceptadas por el cliente

                </td>

            </tr>
        </table>

    </div>
</body>

</html>