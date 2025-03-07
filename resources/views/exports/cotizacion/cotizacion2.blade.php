<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cotización{{@$model->Folio}}</title>
</head>
<style>
    /* Aplica estilos de letras acorde a la situcion */

    .op {
        font-size: 12px;
        font-family: 'Calibri', sans-serif;
        font-weight: bold;
    }

    .op1 {
        font-size: 9px;
        font-family: 'Calibri', sans-serif;
    }

    .op2 {
        font-size: 14px;
        font-family: 'Calibri', sans-serif;
        font-weight: bold;
    }

    .op3 {
        font-size: 14px;
        font-family: 'Calibri', sans-serif;
        font-weight: bold;
        text-decoration: underline;
        text-align: center;
    }

    .op4 {
        font-size: 12px;
        font-family: 'Calibri', sans-serif;
        font-weight: bold;
        text-align: center;
    }

    /* Aplica estilos a la linea de la firma */

    .firma-linea {
        border-top: 1px solid #000;
        padding-top: 10px;
        text-align: center;
    }


    /* Aplica estilos a la tabla con el ID datos */
    #datos {
        width: 100%;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
    }

    #datos th,
    #datos td {
        border: 1px solid #000;
        padding: 8px;
        text-align: center;
    }

    #datos th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    #datos .servicio {
        text-align: left;
        padding-left: 8px;
    }

    #datos .right-align {
        text-align: right;
        padding-right: 8px;
    }

    #datos .total {
        font-weight: bold;
    }

    /* Aplica estilos a la tabla con el ID parametros */
    #parametros {
        width: 50%;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
    }

    #parametros th,
    #parametros td {
        border: 1px solid #000;
        padding: 8px;
        text-align: center;
        font-size: 8px;
    }

    #parametros th {
        background-color: #f2f2f226;
        font-weight: bold;
        font-size: 10px;
    }

    /* Aplica estilos a la tabla con el ID simbologia  */

    #simbologia {
        width: 50%;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
    }

    #simbologia th,
    #simbologia td {
        border: 1px solid #000;
        padding: 8px;
        text-align: left;
        font-size: 8px;
    }

    #simbologia th {
        background-color: #f2f2f226;
        font-weight: bold;
        font-size: 10px;
    }
</style>

<body>
    <div id="container">
        <p class="op" align="right">FOLIO COTIZACIÓN:{{@$model->Folio}}
        <p>
    </div>
    <div class="row" style="display: block">
        <div class="col-12 op">
            {{@$model->Nombre}} <br>
            {{@$model->Direccion}}
        </div>
    </div> <br>

    <div class="row">
        <div class="col-md-12 op">
            TELF: {{@$model->Telefono}}<br>
            email: {{@$model->Correo}}<br>
            AT'N: {{@$model->Atencion}}<br>
        </div>

        <div class="col-md-12 fontNormal fontCalibri fontSize12">
            <p class="fontNormal fontCalibri fontSize12" align="right">
                {{\Carbon\Carbon::parse(@$model->created_at)->format('d/m/Y')}}</p>

            <p>ME PERMITO SOMETER A SU AMABLE CONSIDERACIÓN LA SIGUIENTE COTIZACIÓN DEL SERVICIO DE MUESTREO Y ANÁLISIS
                DE AGUA DE ACUERDO A:</p>

        </div>
        <div class="col-md-12">
            <table class="table table-borderless" style="border:none" width="100%">
                <tr>
                    <td class="op2">SERVICIO: </td>
                    <td class="op">
                        @foreach ($servicio as $item)
                        @if ($item->Id_tipo == $model->Tipo_servicio)
                        {{@$item->Servicio}}
                        @endif
                        @endforeach
                    </td>
                    <td class="op2">PUNTOS MUESTREO:</td>
                    <td class="op">{{@$puntos->count()}}</td>
                    <td class="op2">SERVICIOS:</td>
                    <td class="op">{{@$numServicios}}</td>
                </tr>
            </table>
            <table class="table table-borderless" style="border:none" width="100%">
                <tr>
                    <td class="op2">TIPO MUESTRA: </td>
                    <td class="op">
                        @foreach ($tipo as $item)
                        @if ($item->Id_muestraCot == $model->Tipo_muestra)
                        {{@$item->Tipo}} ({{@$model->Tomas}})
                        @endif
                        @endforeach
                    </td>
                    <td class="op2">NORMA:</td>
                    <td class="op">{{@$norma->Clave_norma}}</td>
                </tr>
            </table>
        </div>
        <div class="col-md-12">
            <table id="datos">
                <tr>
                    <th>PARTIDA</th>
                    <th>SERVICIO</th>
                    <th>CANTIDAD</th>
                    <th>COSTO UNITARIO</th>
                    <th>COSTO TOTAL</th>
                </tr>
                <tr>
                    <td>1</td>
                    <td class="servicio">
                        <strong>
                            <p>{{@$norma->Norma}}</p>
                        </strong>
                    </td>
                    <td>{{$numServicios}}</td>
                    <td class="right-align"> $
                        @php
                        echo number_format(($model->Precio_analisis), 2, ".", ",");
                        @endphp
                    </td>
                    <td class="right-align">$
                        @php
                        echo number_format(($model->Precio_analisis * $numServicios), 2, ".", ",");
                        @endphp
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="total right-align">TOTAL CON IVA</td>
                    <td class="right-align">$
                        @php
                        echo number_format(@$model->Costo_total, 2, ".", ",");
                        @endphp
                    </td>
                </tr>
            </table>
            <table id="parametros">
                <tr>
                    <th>PARAMETROS</th>
                    <th>METODO DE PRUEBA</th>
                </tr>
                @foreach (@$parametros as $item)
                <tr>
                    <td>{{$item->Parametro}} <sup>({{$item->Simbologia}})</td>
                    <td>{{$item->Clave_metodo}}</td>
                </tr>
                @endforeach
                <tr>
                    <th colspan="2">PARAMETROS ADICIONALES</th>
                </tr>
                <tr>
                    <th>PARAMETROS</th>
                    <th>METODO DE PRUEBA</th>
                </tr>
                @if(!empty($parametrosExtra) && $parametrosExtra->isNotEmpty())
                @foreach ($parametrosExtra as $item)
                <tr>
                    <td>{{ $item->Parametro }} <sup>({{ $item->Simbologia }})</sup></td>
                    <td>{{ $item->Clave_metodo }}</td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="2">No hay Parámetros adicionales</td>
                </tr>
                @endif

            </table>
            <p class="op3">*LOS ANALISIS CUENTAN CON ACREDITAMIENTO EMA / ANALISIS DE CONTROL INTERNO <br> @php
                echo @$model->Observacion_cotizacion;
                @endphp </p>

            <table id="simbologia">
                <tr>
                    <th>Simbologia</th>
                </tr>
                <tr>
                    <td>
                        @php
                        echo $impresion->Simbologia;
                        @endphp
                    </td>
                </tr>
            </table>
            <div class="op1">
                @php
                echo $impresion->Texto;
                @endphp
            </div>

            <div class="op4">
                He leído la presente cotización y acepto los términos indicados en ella
            </div>

            <br>

            <div class="col-md-12">
                <table class="table" width="100%">
                    <tr>
                        <td width="20%">&nbsp;</td>
                        <td width="60%">&nbsp;</td>
                        <td width="20%">&nbsp;</td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                        <td class="firma-linea op4">Firma de aceptación del Cliente</td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </div>
            <br>
            <div class="op4">
                En espera de poder servirles, de antemano agradecemos su preferencia. Reciban un cordial saludo.
            </div>

            <br>

            <div class="col-md-12">
                <table class="table" width="100%">
                    <tr>
                        <td width="20%">&nbsp;</td>
                        <td class="op4" width="60%">
                            Atentamente
                        </td>
                        <td width="20%">&nbsp;</td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                        <td class="op4"><img style="width: auto; height: auto; max-width: 100px; max-height: 80px;"
                                src="{{asset('public/storage/'.$firma->firma)}}"></td>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                        <td class="firma-linea op4">Ing. Maribel Campos Reyes</td>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                        <td class="op4">Responsable de Cotización</td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </div>


        </div>

</body>

</html>