<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe de Resultados de Analisis de Alimentos </title>
</head>

<style>
    #nota2 {
        width: 100%;
        font-size: 10px;
    }

    #nota2 p {
        margin: 0;
        padding: 0;
        line-height: 1.1;
    }
    #nota2 .titulo {
        font-size: 10px;
        font-weight: bold;
        padding-top: 10px;
    }

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
    color: #333;font-size: 10px;
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
    border: 0.5px solid black;
    font-size: 10px;
    font-family: Arial, Helvetica, sans-serif;
    table-layout: fixed;
}

#info td {
    border: none;
    padding: 0px 2px;
    text-align: left;
    vertical-align: top;
}

#info tr td:nth-child(odd) {
    font-weight: bold; /* Resalta los títulos */
    width: 15%; /* ancho de las etiquetas */
}

#info tr td:nth-child(even) {
    width: 35%; /* ancho de los valores */
}

    #descripcion {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        font-size: 10px;
        font-family: Arial, Helvetica, sans-serif;
    }

    #descripcion td {
        border: 0.5px solid black;
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
        border: 0.5px solid black;
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
        border: 0.5px solid #000;
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
        margin: 20px;
        margin-top:-10px;
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
        border: none;
        text-align: left;
    }

    .revisiones {

        font-family: 'Times New Roman', Times, serif;
        font-size: 10px;
    }
</style>

<body>
    <div class="container">
        <h2>INFORME DE RESULTADOS DE ANÁLISIS </h2>
         <h3>No. de Orden: {{$solicitud->Folio}}</h3>
         <h3>No. de Muestra: {{$repali->Folio}}</h3>
        <table id="info">
            <tr>
                <td>Empresa:</td>
                <td>{{$proceso->Empresa}}</td>
                <td>Fecha de recepción: </td>
                <td> {{ \Carbon\Carbon::parse($proceso->Hora_recepcion)->format('d-m-Y H:i:s') }}</td>
            </tr>
            <tr>
                <td>Dirección:</td>
                <td>{{$solicitud->Direccion}}</td>
                <td>Periodo de análisis: </td>
                <td> {{ \Carbon\Carbon::parse($proceso->Hora_recepcion)->format('d-m-Y') }} al {{ \Carbon\Carbon::parse($proceso->Periodo_analisis)->format('d-m-Y') }}</td>
            </tr> 
            <tr>
                <td>Atención a:</td>
                <td>{{$solicitud->Atencion}}</td>
                <td>Fecha de reporte:</td>
                <td>{{ \Carbon\Carbon::parse($proceso->Periodo_analisis)->addDays(2)->format('d-m-Y') }}</td>
            </tr>
        </table>
        <table id="descripcion" style="margin-top: 5px">
            <tr>
                <td colspan="7">Descripción de la muestra: {{$muestra->Muestra}}</td>
            </tr>
            <tr>
                <td rowspan="2">Fecha y hora de muestreo:</td>
                <td colspan="3">
                 @php
                     $fecha = \Carbon\Carbon::parse($muestra->Fecha_muestreo);
                 @endphp
             
                 @if($fecha->format('H:i:s') == '00:00:00')
                     {{ $fecha->format('d-m-Y') }} NP
                 @else
                     {{ $fecha->format('d-m-Y H:i:s') }}
                 @endif
             </td>

                <td colspan="2">Temperatura de muestreo °C:</td>
                <td colspan="1"> {{$muestra->Tem_muestra}}</td>

            </tr>
            <tr>
                <td colspan="2">Cantidad de muestra :</td>
                <td colspan="1">{{$muestra->Cantidad}}</td>

            </tr>
            <tr>
                <td>Método de muestreo:</td>
                <td colspan="6">
                    @if ($solicitud->Id_servicio != 1)
                        Muestra remitida por el cliente
                    @else
                        PEA-10-002-1 <sup>1A</sup>
                    @endif
                </td>
            </tr>
            
         <tr>
    <td colspan="7" style="font-size: 10px;">

        @php
            $fechaAnalisis = \Carbon\Carbon::parse($proceso->Periodo_analisis)->addDays(2);
            $fechaLimite   = \Carbon\Carbon::parse("2025-11-25");
            $swInfo = 0;
        @endphp

        @if ($fechaAnalisis->greaterThanOrEqualTo($fechaLimite))
            {{-- No mostrar nada --}}
            @php
                $swInfo = 1;
            @endphp
        @else
            <strong>Norma de Especificación:</strong>
        @endif

        {{ $norma->Espesificacion_ali }}

    </td>
</tr>
            
            <!-- <tr>
                <td colspan="7" class="footer">
            </tr> -->
        </table>
        <br>
        <table id="parametro">
            <tr>
                <th class="large-col">Parámetro</th>
                <th>Método de prueba</th>
                <th>Unidad</th>
                <th>Resultado</th>
                @if ($swInfo == 1)
                    @if($muestra->Id_norma != 38)
                        <th class="small-col ">Límite Permisible</th>
                    @endif
                @else
                    <th class="small-col ">Límite</th>
                @endif
                
                <th class="small-col ">Analizó</th>
            </tr>
            @foreach ($codigo as $item)
            <tr>
                <td style="text-align: left; width: 35%;">{{$item->parametro->Parametro}} <sup>{{$item->parametro->simbologia->Simbologia}}</sup> </td>
                <td>{{$item->parametro->metodo->Clave_metodo}}</td>
                <td style="padding:1.5px;">{{$item->parametrosMatriz->unidad->Unidad}}</td>
                <td style="padding:1.5px;">
                    @if ($item->Resultado2 != "")
                    {{$item->Resultado2}}
                    @else
                    ------
                    @endif
                </td>
                @if ($swInfo == 1)
                    @if($muestra->Id_norma != 38)
                    <td style="width: 15%;">{{@$item->parametrosMatriz->Limite}}</td>
                    @endif
                @else
                    <td style="width: 15%;">{{@$item->parametrosMatriz->Limite}}</td>
                @endif
                <td >{{@$item->usuario->iniciales}}</td>
            </tr>
            @endforeach
        </table>
        <br>
        
        @if($muestra->Observacion !== null)
        <table id="obs">
            <tr>
                <td  colspan="4">{{$muestra->Observacion}}</td>
            </tr>

            <!-- <tr>
                <td colspan="2" >PH:</td>
             
                <td colspan="2">Cloro Libre:</td>
              
            </tr> -->

            <!-- <tr>
                <td colspan="4">1 REG. ACREDIT. ENTIDAD MEXICANA DE ACREDITACIÓN ema No. AG-057-025/12, CONTINUARÁ
                    VIGENTE</td>
            </tr> -->
        </table>
        @endif
        

        <br>
        @php
        $temp = array();
        $sw = false;
        @endphp

        <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0" cellspacing="0"
            border-color="#000000" width="100%">
            <tbody>
                <tr>
                    <td></td>
                </tr>
                @foreach ($codigo as $item)
                @for ($i = 0; $i < sizeof($temp); $i++) @if ($temp[$i]==$item->Id_simbologia_info)
                    @php $sw = true; @endphp
                    @endif
                    @endfor
                    @if ($sw != true)
                    @switch($item->Id_simbologia_info)
                    @case(9)

                    @break
                    @case(11)
                    <tr>
                        <td style="font-size: 7px" class="fontBold justificadorIzq">1++ MEDIA GEOMETRICA DE LAS @php
                            if (@$numTomas->count()) {
                            echo @$numTomas->count();
                            } else {
                            echo $solicitud->Num_tomas;
                            }

                            @endphp MUESTRAS SIMPLES DE ESCHERICHIA COLI.</td>
                    </tr>
                    @php
                    array_push($temp,$item->Id_simbologia_info);
                    @endphp
                    @break
                    @case(5)
                    <tr>
                        <td style="font-size: 7px" class="fontBold justificadorIzq">1# PROMEDIO PONDERADO DE LAS
                            @php
                            if (@$numTomas->count()) {
                            echo @$numTomas->count();
                            } else {
                            echo $solicitud->Num_tomas;
                            }

                            @endphp MUESTRAS SIMPLES DE GRASAS Y ACEITES</td>
                    </tr>
                    @php
                    array_push($temp,$item->Id_simbologia_info);
                    @endphp
                    @break
                    @case(4)
                    <tr>
                        <td style="font-size: 7px" class="fontBold justificadorIzq">1+ MEDIA GEOMETRICA DE LAS @php
                            if (@$numTomas->count()) {
                            echo @$numTomas->count();
                            } else {
                            echo $solicitud->Num_tomas;
                            }

                            @endphp MUESTRAS SIMPLES DE COLIFORMES. EL VALOR MINIMO CUANTIFICADO REPORTADO SERA DE
                            3, COMO CRITERIO CALCULADO PARA COLIFORMES EN SIRALAB Y EL
                            LABORATORIO.</td>
                    </tr>
                    @php
                    array_push($temp,$item->Id_simbologia_info);
                    @endphp
                    @break
                    @case(12)
                    <tr>
                        <td style="font-size: 7px" class="fontBold justificadorIzq">1+++ MEDIA GEOMETRICA DE LAS
                            @php
                            if (@$numTomas->count()) {
                            echo @$numTomas->count();
                            } else {
                            echo $solicitud->Num_tomas;
                            }

                            @endphp MUESTRAS SIMPLES DE ENTEROCOCOS FECALES. </td>
                    </tr>
                    @php
                    array_push($temp,$item->Id_simbologia_info);
                    @endphp
                    @break
                    @default

                    @switch($item->Id_parametro)
                    @case(97)
                    @if ($solicitud->Num_tomas > 1)
                    <tr>
                        <td style="font-size: 7px" class="fontBold justificadorIzq">{{$item->Simbologia_inf}} @php
                            print $item->Descripcion2; @endphp</td>
                    </tr>
                    @else

                    @endif
                    {{-- <tr>
                        <td style="font-size: 7px" class="fontBold justificadorIzq">*** LA DETERMINACIÓN DE LA
                            TEMPERATURA DE LA MUESTRA COMPUESTA ES DE {{@$campoCompuesto->Temp_muestraComp}}°C Y EL
                            PH COMPUESTO ES DE {{@$campoCompuesto->Ph_muestraComp}}</td>
                    </tr> --}}
                    @php
                    array_push($temp,$item->Id_simbologia_info);
                    @endphp
                    @break
                    @default

                    <tr>
                        <td style="font-size: 7px" class="fontBold justificadorIzq">{{$item->Simbologia_inf}} @php
                            echo @$item->Descripcion2; @endphp</td>
                    </tr>
                    @php
                    array_push($temp,$item->Id_simbologia_info);
                    @endphp
                    @endswitch
                    @endswitch

                    @endif
                    @php
                    $sw = false;
                    @endphp
                    @endforeach
            </tbody>
        </table>

            
        <table id="autor">
            <tr>
                
                <td colspan="2">
                    _____________________________ <br>
                    Revisó: <br>
                    Ced. Prof. 4451026 <br>
                    Biol. Guadalupe Garcia Perez</td>
                    <td colspan="2">
                    _____________________________ <br>
                    Autorizó: <br>
                    Ced. Prof. 6632244 <br>
                    Biol. Elsa Rivera Rivera </td>
            </tr>
        </table>

        <table id="parametro">
            <tr>
                <th class="large-col" colspan="8">Condiciones de recepción de la muestra</th>
            </tr>
            <tr>
                <td colspan="8" style="text-align: left;">
                    Temperatura de recepción (°C):  {{$muestra->Tem_recepcion}}
                </td>
            </tr>
            <tr>
                <td>Temperatura</td>
                <td>
                    @if (strpos($muestra->Motivo, "T") !== false)
                        NC
                    @else
                        SC
                    @endif
                </td>
                <td>Tiempo</td>
                <td>
                     @if (strpos($muestra->Motivo, "H") !== false)
                        NC
                    @else
                        SC
                    @endif
                </td>
                <td>Cantidad</td>
                <td>
                     @if (strpos($muestra->Motivo, "C") !== false)
                        NC
                    @else
                        SC
                    @endif
                </td>
                <td>Recipiente</td>
                <td>
                     @if (strpos($muestra->Motivo, "R") !== false)
                        NC
                    @else
                        SC
                    @endif
                </td>
            </tr>

        </table>

          <div id="nota2">
            <br>
        <strong><p>Observaciones</p></strong>

@php
    // Banderas para controlar qué mensajes ya se han mostrado
    $shown_lt = false;        // Solo <
    $shown_gt = false;        // Solo >
    $shown_ast = false;       // Solo *
    $shown_lt_ast = false;    // < y *
    $shown_gt_ast = false;    // > y *
    $shown_lt_gt = false;     // < y >
    $shown_none = false;      // Ninguno de los 3
@endphp

@foreach ($codigo as $item)
    @if (!empty($item->Resultado2)) 
        
        @php
            // Detectamos los caracteres especiales
            $hasLt = strpos($item->Resultado2, '<') !== false;
            $hasGt = strpos($item->Resultado2, '>') !== false;
            $hasAst = strpos($item->Resultado2, '*') !== false;
        @endphp

        {{-- Caso 1: Contiene < y * --}}
        @if ($hasLt && $hasAst && !$shown_lt_ast)
            <p>* es igual a "Valor estimado", "&lt;" indica el valor mínimo cuantificado por el método</p>
            @php $shown_lt_ast = true; @endphp

        {{-- Caso 2: Solo < --}}
        @elseif ($hasLt && !$hasGt && !$hasAst && !$shown_lt)
            <p>"&lt;" indica el valor mínimo cuantificado por el método</p>
            @php $shown_lt = true; @endphp

        {{-- Caso 3: Solo * --}}
        @elseif ($hasAst && !$hasLt && !$hasGt && !$shown_ast)
            <p>* es igual a "Valor estimado"</p>
            @php $shown_ast = true; @endphp

        {{-- Caso 4: Solo > --}}
        @elseif ($hasGt && !$hasLt && !$hasAst && !$shown_gt)
            <p>"&gt;" indica el valor máximo cuantificado por el método</p>
            @php $shown_gt = true; @endphp

        {{-- Caso 5: > y * --}}
        @elseif ($hasGt && $hasAst && !$hasLt && !$shown_gt_ast)
            <p>* es igual a "Valor estimado", "&gt;" indica el valor máximo cuantificado por el método</p>
            @php $shown_gt_ast = true; @endphp

        {{-- Caso 6: > y < --}}
        @elseif ($hasGt && $hasLt && !$hasAst && !$shown_lt_gt)
            <p>"&lt;" indica el valor mínimo, "&gt;" indica el valor máximo cuantificado por el método</p>
            @php $shown_lt_gt = true; @endphp

        {{-- Caso 7: No contiene ninguno --}}
        @elseif (!$hasGt && !$hasLt && !$hasAst && !$shown_none)
            <p>Informe de resultado de análisis para control interno </p>
            @php $shown_none = true; @endphp
        @endif
    @endif
@endforeach

@if ($swInfo == 1)
    @if($muestra->Id_norma != 38)
    <p> A solicitud del cliente se compara el informe de resultados con los limites permisibles de la norma </p>
    @endif
@endif

<br>
       <strong> <p>Simbologia</p></strong>
       <br>
        @php
            $temp = array();
        @endphp
        @foreach ($codigo as $item)
            @if (array_search($item->parametro->simbologia->Id_simbologia, $temp) === false)
                 @if ($item->parametro->simbologia->Id_simbologia === 2)
                    <p>{{ $item->parametro->simbologia->Simbologia }} REG. ACREDIT. ENTIDAD MEXICANA DE ACREDITACIÓN ema No. AG-057-025/12, CONTINUARÁ VIGENTE</p>
                 @else
                    <p>{{ $item->parametro->simbologia->Simbologia }} {{ $item->parametro->simbologia->Descripcion }}</p>
                @endif

                
            @php
                array_push($temp, $item->parametro->simbologia->Id_simbologia);
            @endphp
        @endif
        

        @endforeach
        <p>NP: No proporcionada</p>
        <p>SC: Si cumple</p>
        <p>NC: No cumple</p>
            <!-- <p class="titulo"><strong>SIMBOLOGIA</strong></p>
            <p>Informe de resultado de análisis para control interno. * Es igual a "Valor estimado"</p>
            <p>"<" Indica el valor mínimo cuantificado por el método</p>
            <p>* Es igual a "Valor estimado", "<" Indica el valor mínimo cuantificado por el método</p>
            <p>">" Indica el valor máximo cuantificado por el método</p>
            <p>* Es igual a "Valor estimado", ">" Indica el valor máximo cuantificado por el método</p>
            <p>"<" Indica el valor mínimo cuantificado, ">" Indica el valor máximo cuantificado por el
                                    método</p>
            <p>4 Parámetro no acreditado</p>
            <p>1 REG. ACREDIT. ENTIDAD MEXICANA DE ACREDITACIÓN ema No. AG-057-025/12,CONTINUARÁ
                                        VIGENTE</p>
            <p>1A ACREDITAMIENTO EN ALIMENTOS: REG. ACREDIT. ENTIDAD MEXICANA DE ACREDITACION
                                        ema No. A-0530-047/14, CONTINUARÁ VIGENTE.</p> -->

     <table id="nota"> 
        
            <tr>
                <td>Este informe de resultados no debe reproducirse sin la aprobación por escrito del
                    laboratorio emisor <br>
                    La información de dirección, empresa, atención a y descripción de muestras son proporcionados por el
                    cliente <br>
                    Los resultados expresados avalan únicamente a la muestra analizada bajo las condiciones de recepción
                    en el laboratorio aceptadas por el cliente
                </td>
            </tr>
        </table>
        </div>
    </div>
</body>

</html>