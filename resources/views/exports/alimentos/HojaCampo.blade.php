<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoja de Campo Alimentos </title>
</head>
<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }


   

    .container,
    .container2 {
        max-width: 900px;
        margin: 0 auto;
        background-color: #fff;
        padding: 20px;

    }

    h2,
    h3 {
        margin-bottom: 20px;
        color: #333;
        font-size: 10px;
        margin: 0;
        /* Tamaño de letra para los títulos */
    }

    .orden {
        margin-bottom: 20px;
        color: #333;
        font-size: 10px;
        margin: 0;
        text-align: right;
        /* Alinea el texto a la derecha */
    }

    table {

        width: 100%;
        margin-bottom: 20px;
        border-collapse: collapse;
    }

    table,
    th,
    td {
        border: 1px solid #000000;
    }

    th,
    td {
        padding: 5px;
        text-align: left;
        font-size: 10px;
        /* Tamaño de letra para el contenido de las tablas */
    }

    .small-col {
        min-width: 15px;
        width: 15px;
    }

    .large-cell {
        height: 50px;
        /* Ajusta la altura de las celdas grandes */
    }

    p {
        font-size: 10px;
        /* Tamaño de letra para los párrafos */
    }

    /* Estilo para la tabla de muestreo */
    .tabla-muestreo {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid black;
    }

    .tabla-muestreo td {
        border: 1px solid black;
        padding: 10px;
        text-align: left;
        vertical-align: middle;
    }

    /* Ajustar ancho de la última columna */
    .tabla-muestreo td:nth-child(3) {
        width: 30%;
    }

    /* Estilo para las observaciones */
    .observaciones {
        height: 100px;
    }

    /* Estilo para el texto de error de corrección */
    .error-correccion {
        font-size: 10px;
        margin-top: 10px;
    }

    .orden {
        text-align: end;
    }


    #parametros {
        width: 100%;
        /* Ajusta el ancho total de la tabla */
        border-collapse: collapse;
        margin: 0;
        /* Elimina el espacio entre tablas */

        /* Elimina los espacios entre bordes */
    }

    #parametros th,
    #parametros td {
        border: 1px solid #000;
        text-align: center;
        padding: 8px;
        min-width: 15px;

    }

    #parametros th {

        font-size: 7px;

    }

    #parametros td:nth-child(1),
    #parametros td:nth-child(3),
    #parametros td:nth-child(5) {
        font-size: 7px;
        text-align: left;

    }

    #parametros td:nth-child(2),
    #parametros td:nth-child(4),
    #parametros td:nth-child(6) {
        width: 15px;
    }

    #parametros {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #000;
    }

    #parametros th,
    #parametros td {
        border: 1px solid #000;
        text-align: center;
        padding: 5px;
    }



    /* Celdas con texto alineado a la izquierda */
    .left-align {
        text-align: left;
        padding-left: 10px;
    }

    /* Ajustar el ancho de las columnas */
    .small-col {
        width: 10%;
    }

    .large-col {
        width: 40%;
    }

    #preservadores {
        margin: 0;
        /* Elimina el espacio entre tablas */

        width: 100%;
        border-collapse: collapse;
    }


    #preservadores td {
        border: 1px solid black;
        text-align: left;
        padding: 5px;
        font-size: 7px;
    }

    #preservadores th,
    {
    border: 1px solid black;
    text-align: center;

    font-size: 7px;

    }

    /* Estilo para las columnas 1, 3, 5: ancho 30px */
    #preservadores td:nth-child(odd) {
        width: 30px;

    }

    /* Estilo para las columnas 2, 4, 6: ancho 10px */
    #preservadores td:nth-child(even) {
        width: 10px;
    }

    #Material {
        width: 100%;
        margin: 0;
        /* Elimina el espacio entre tablas */

        /* Ajusta el ancho total de la tabla */
        border-collapse: collapse;
        /* Elimina los espacios entre bordes */
    }

    #Material th,
    #Material td {
        border: 1px solid #000;
        text-align: center;
        padding: 8px;
        min-width: 15px;

    }

    #Material th {

        font-size: 7px;
    }

    #Material td:nth-child(1),
    #Material td:nth-child(3),
    #Material td:nth-child(5) {
        font-size: 7px;
        text-align: left;

    }

    #Material td:nth-child(2),
    #Material td:nth-child(4),
    #Material td:nth-child(6) {
        width: 15px;
    }

    #Material {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #000;
    }

    #Material th,
    #Material td {
        border: 1px solid #000;
        text-align: center;
        padding: 5px;
    }


    #Complementos {
        width: 100%;
        margin: 0;
        /* Elimina el espacio entre tablas */

        /* Ajusta el ancho total de la tabla */
        border-collapse: collapse;
        /* Elimina los espacios entre bordes */
    }

    #Complementos th,
    #Complementos td {
        border: 1px solid #000;
        text-align: center;
        padding: 8px;
        min-width: 15px;

    }

    #Complementos th {

        font-size: 7px;
        text-align: center
    }

    #Complementos td:nth-child(1),
    #Complementos td:nth-child(3),
    #Complementos td:nth-child(5) {
        font-size: 7px;
        text-align: left;

    }

    #Complementos td:nth-child(2),
    #Complementos td:nth-child(4),
    #Complementos td:nth-child(6) {
        width: 15px;
    }

    #Complementos {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #000;
    }

    #Complementos th,
    #Complementos td {
        border: 1px solid #000;
        text-align: center;
        padding: 5px;
    }

    #croquis {
        margin: 0;
        /* Elimina el espacio entre tablas */

        width: 100%;
        border-collapse: collapse;
    }


    #croquis td {
        border: 1px solid black;
        text-align: left;
        padding: 5px;
        font-size: 7px;
    }

    #croquis th,
    {
    border: 1px solid black;
    text-align: center;

    font-size: 10px;

    }

    /* Estilo para las columnas 1, 3, 5: ancho 30px */
    #croquis td:nth-child(odd) {
        width: 1000px;
        height: 270px;

    }

    /* Estilo para las columnas 2, 4, 6: ancho 10px */
    #croquis td:nth-child(even) {
        width: 10px;
    }
</style>

<body>
    <div class="container">
        <h2>Datos Generales</h2>

        <table>
            <tr>
                <td>No. De Muestras:</td>
                <td>No. de orden:</td>
                <td>Fecha y hora de Muestreo:</td>
            </tr>
            <tr>
                <td colspan="3">Empresa/Cliente:</td>
            </tr>
            <tr>
                <td colspan="3">Dirección:</td>
            </tr>
            <tr>
                <td>Atención a:</td>
                <td colspan="2">Teléfono y/o email:</td>
            </tr>
        </table>

        <h3>Datos de la Muestras a Analizar</h3>

        <table>
            <thead>
                <tr>
                    <th class="small-col">No. de muestra</th>
                    <th>Descripción de la Muestra</th>
                    <th>Análisis a realizar</th>
                    <th class="small-col">T. Muestreo (°C)</th>
                    <th class="small-col">T. de recepción (°C)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <table>
            <tr>
                <td rowspan="6" style="width: 10%; text-align: center;">Condiciones de muestreo:</td>

            </tr>
            <tr>
                <td style="height: 20px;"></td>
            </tr>
            <tr>
                <td style="height: 20px;"></td>
            </tr>
            <tr>
                <td style="height: 20px;"></td>
            </tr>
            <tr>
                <td style="height: 20px;"></td>
            </tr>
            <tr>
                <td style="height: 20px;"></td>
            </tr>
            <tr>
                <td rowspan="6" style="width: 10%; text-align: center;">Condiciones ambientales:</td>

            </tr>
            <tr>
                <td style="height: 20px;"></td>
            </tr>
            <tr>
                <td style="height: 20px;"></td>
            </tr>
            <tr>
                <td style="height: 20px;"></td>
            </tr>
            <tr>
                <td style="height: 20px;"></td>
            </tr>
            <tr>
                <td style="height: 20px;"></td>
            </tr>
        </table>
        <table>
            <!-- Fila con aceptación, método y responsable -->
            <tr>
                <td colspan="2" class="text-left">
                    Aceptación y conformidad por parte del cliente con los datos registrados (Nombre y Firma):
                </td>
                <td class="text-left">
                    Método de muestreo: PEA-10-002-1
                </td>
            </tr>
            <tr>
                <td colspan="3" class="text-left">
                    Responsable del muestreo y entrega al laboratorio:
                </td>
            </tr>

            <!-- Recepción de muestras en el laboratorio -->
            <tr>
                <td colspan="3" class="text-left no-border">
                    <strong>Recepción de muestras en el laboratorio:</strong>
                </td>
            </tr>
            <tr>
                <td>
                    Nombre:<br>
                    Fecha y hora:
                </td>
                <td>
                    Número de unidades recibidas:
                </td>
                <td></td>
            </tr>

            <!-- Observaciones -->
            <tr>
                <td colspan="3" class="observaciones">
                    Observaciones:
                </td>
            </tr>

            <!-- Error de corrección -->
            <tr>
                <td class="text-left">
                    Error de corrección:
                </td>
                <td colspan="2" style="font-size: 7px;">
                    Dependiendo el signo que tenga el error, se debe sumar o restar a la temperatura que marca el
                    display del termómetro.
                    Por ejemplo: Error de corrección = -0.4°C, temperatura de la muestra es 5.9°C, Temperatura real
                    corregida es de 6.3°C;
                    o bien, Error de corrección = 0.5°C, temperatura de la muestra = 23.6°C. Temperatura real corregida
                    = 23.1°C.
                </td>
            </tr>
        </table>
    </div>




    <div class="container">
        <h5 class="orden">No. DE ORDEN:</h5>
        <h2>Muestreador:</h2>
        <table id="parametros">
            <!-- TIPO DE MATRIZ A TOMAR -->
            <tr>
                <th colspan="6">TIPO DE MATRIZ A TOMAR</th>
            </tr>
            <tr>
                <td class="left-align">ALIMENTO</td>
                <td class="small-col"></td>
                <td class="left-align">AGUA PURIFICADA</td>
                <td class="small-col"></td>
                <td class="left-align">AGUA POTABLE</td>
                <td class="small-col"></td>
            </tr>
            <tr>
                <td class="left-align">HIELO</td>
                <td></td>
                <td class="left-align">MATERIA PRIMA</td>
                <td></td>
                <td class="left-align">PRODUCTO TERMINADO</td>
                <td></td>
            </tr>
            <tr>
                <td class="left-align">MEDIO AMBIENTE (MA)</td>
                <td></td>
                <td class="left-align">FROTIS DE SUPERFICIE INERTE (FSI 50 mL)</td>
                <td></td>
                <td class="left-align">FROTIS DE MANOS (FM 50 mL)</td>
                <td></td>
            </tr>
            <tr>
                <td class="left-align">ENJUAGUE DE SUPERFICIE INERTE (50 mL)</td>
                <td></td>
                <td class="left-align">ENJUAGUE DE MANOS (50 mL)</td>
                <td></td>
                <td class="left-align">CONTRAMUESTRA</td>
                <td></td>
            </tr>
            <tr>
                <td class="left-align">AGUA DE USO RECREATIVO</td>
                <td></td>
                <td class="left-align"></td>
                <td></td>
                <td class="left-align"></td>
                <td></td>

            </tr>

            <!-- EQUIPO DE SEGURIDAD -->
            <tr>
                <th colspan="6">EQUIPO DE SEGURIDAD</th>
            </tr>
            <tr>
                <td class="left-align">BATA</td>
                <td></td>
                <td class="left-align">COFIA</td>
                <td></td>
                <td class="left-align">CUBREBOCAS</td>
                <td></td>
            </tr>
            <tr>
                <td class="left-align">GUANTES ESTERILES</td>
                <td></td>
                <td class="left-align">ZAPATOS DE SEGURIDAD</td>
                <td></td>
                <td class="left-align">LENTES DE SEGURIDAD</td>
                <td></td>
            </tr>
        </table>
        <table id="preservadores">
            <!-- PRESERVADORES -->
            <tr>
                <th colspan="10">PRESERVADORES</th>
            </tr>
            <tr>
                <td>GEL REFIGERANTE</td>
                <td></td>
                <td>HIELO</td>
                <td></td>
                <td>Na<sub>2</sub>S<sub>2</sub>O<sub>3</sub></td>
                <td></td>
                <td>K<sub>2</sub>Cr<sub>2</sub>O<sub>7</sub> 5%</td>
                <td></td>
                <td>NA<sub>2</sub>S<sub>2</sub>O<sub>2</sub></td>
                <td>HNO<sub>3</sub>SUPRAPURO </td>

            </tr>
        </table>
        <table id="Material">
            <!-- TIPO DE MATRIZ A TOMAR -->
            <tr>
                <th colspan="6">MATERIAL PARA EL MUESTREO</th>
            </tr>
            <tr>
                <td class="left-align">BOLSAS ESTERILES GRANDES <sub>(250 mL)</sub></td>
                <td class="small-col"></td>
                <td class="left-align">PLACAS CON AGUA PAPA DEXTROSA</td>
                <td class="small-col"></td>
                <td class="left-align">RECIPIENTES DE 1000 ml para metales </td>
                <td class="small-col"></td>
            </tr>
            <tr>
                <td class="left-align">BOLSAS ESTERILES GRENDES <sub>(250 mL)</sub> <br>CON TIOSULFATO DE SODIO</td>
                <td></td>
                <td class="left-align">PLACAS CON AGAR CUENTA ESTANDAR </td>
                <td></td>
                <td class="left-align">TERMOMETRO DE VASTAGO DIGITAL</td>
                <td></td>
            </tr>
            <tr>
                <td class="left-align">TUBOS CON SOLUCION BUFFERS <sub>50 mL</sub></td>
                <td></td>
                <td class="left-align">PLACAS CON AGAR ROJO BILIS VIOLETA</td>
                <td></td>
                <td class="left-align">TERMOMETRO INFRAROJO </td>
                <td></td>
            </tr>
            <tr>
                <td class="left-align">HISOPOS ESTERILES</td>

                <td></td>
                <td class="left-align">PLACAS CON AGAR XLD</td>
                <td></td>
                <td class="left-align">BOLSAS DE POLIETILENO NO ESTERILES</td>
                <td></td>
            </tr>
            <tr>
                <td class="left-align">PLANTILLAS DE ALUMINIO DE <sub> 25 cm</sub></td>

                <td></td>
                <td class="left-align">TENEDORES ESTERILES</td>
                <td></td>
                <td class="left-align">TORNUDAS CON ALCOHOL AL 70%</td>
                <td></td>
            </tr>
            <tr>
                <td class="left-align">PLANTILLAS DE ALUMINIO DE <sub> 100 cm</sub></td>

                <td></td>
                <td class="left-align">PICAHIELO ESTERILES</td>
                <td></td>
                <td class="left-align">TORNUDAS CON SOLUCION DE HIPOCLORITO DE <br>SODIO <sub>(0.0.1%)</sub></td>
                <td></td>
            </tr>
            <tr>
                <td class="left-align">PINZAS DE DICECCION </td>

                <td></td>
                <td class="left-align">CUCHARONES ESTERILES</td>
                <td></td>
                <td class="left-align">BOLSAS ESTERILES GRANDES <sub>1000 mL</sub></td>
                <td></td>
            </tr>
            <tr>
                <td class="left-align">TIJERAS DE ACERO INOXIDABLE </td>

                <td></td>
                <td class="left-align">CUCHARONES ESTERILES</td>
                <td></td>
                <td class="left-align">FRASCO ESTERIL<sub>1000 mL</sub></td>
                <td></td>
            </tr>
            <tr>
                <td class="left-align">CUCHILLOS ESTERILES </td>

                <td></td>
                <td class="left-align">ENCENDEDOR</td>
                <td></td>
                <td class="left-align">FRASCO ESTERIL CON TIOSULFATO DE SODIO AL 3%</td>
                <td></td>
            </tr>
        </table>
        <table id="Complementos">
            <tr>
                <th colspan="6">COMPLEMENTOS</th>
            </tr>
            <tr>
                <td class="left-align">ETIQUETAS</td>
                <td class="small-col"></td>
                <td class="left-align">TABLA PARA ANOTAR</td>
                <td class="small-col"></td>
                <td class="left-align">FRASCO DE COLOR AMBAR O <br>TRANSPARENTE DE 1.0L</td>
                <td class="small-col"></td>
            </tr>
            <tr>
                <td class="left-align">GEL SANITIZANTE</td>
                <td class="small-col"></td>
                <td class="left-align">PLUMON INDELEBLE</td>
                <td class="small-col"></td>
                <td class="left-align"></td>
                <td class="small-col"></td>
            </tr>
            <tr>
                <td class="left-align">SANITAS</td>
                <td class="small-col"></td>
                <td class="left-align">SELLO DE SEGURIDAD</td>
                <td class="small-col"></td>
                <td class="left-align"></td>
                <td class="small-col"></td>
            </tr>
            <tr>
                <td class="left-align">EMBUDO DE PLASTICO</td>
                <td class="small-col"></td>
                <td class="left-align">HOJA DE CAMPO</td>
                <td class="small-col"></td>
                <td class="left-align"></td>
                <td class="small-col"></td>
            </tr>
            <tr>
                <td class="left-align">CEPILLO SUAVE</td>
                <td class="small-col"></td>
                <td class="left-align">HIELERA</td>
                <td class="small-col"></td>
                <td class="left-align"></td>
                <td class="small-col"></td>
            </tr>
            <tr>
                <td class="left-align">DESARMADOR PLANO</td>
                <td class="small-col"></td>
                <td class="left-align">CINCHILLO DE PLASTICO</td>
                <td class="small-col"></td>
                <td class="left-align"></td>
                <td class="small-col"></td>
            </tr>
            <tr>
                <td class="left-align">MASKING TAPE </td>
                <td class="small-col"></td>
                <td class="left-align">KIT COMPARDOR DE CLORO Y PH</td>
                <td class="small-col"></td>
                <td class="left-align"></td>
                <td class="small-col"></td>
            </tr>
            <tr>
                <td class="left-align">LAPICERO </td>
                <td class="small-col"></td>
                <td class="left-align">PIPETAS PASTEUR </td>
                <td class="small-col"></td>
                <td class="left-align"></td>
                <td class="small-col"></td>
            </tr>
        </table>
        <table id="croquis">
            <tr>
                <th colspan="6"><strong> CROQUIS</strong></th>
            </tr>
            <tr>
                <td></td>

            </tr>

        </table>

    </div>


</body>

</html>