<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/custodiaInterna/custodiaInterna.css')}}">

    <title>Cadena de custodia interna</title>
</head>

<body>
    <div class="container" id="pag">
        <div class="row">
            <div class="col-12 negrita">
                <div>
                    <div class="fontNormal fontCalibri justifyCenter fontSize13">
                        CADENA DE CUSTODIA INTERNA </div>
                    <div class="fontNormal fontCalibri" style="font-size: 8px">
                        1.-DATOS GENERALES
                    </div>
                    <div class="fontCalibri">
                        <table class="table-sm" width="100%">
                            <tr>
                                <td>No. de Muestra &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="negrita">{{
                                        $data1['Folio_servicio'] }}</span></td>
                                <td>Tipo de Muestra &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="negrita"> {{
                                        $data1['Descarga'] }}</span></td>
                                <td>Norma Aplicable &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="negrita">{{
                                        $data1['Clave_norma'] }}</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <table class="{{-- table --}} {{-- table-bordered border-dark --}} table-sm {{-- colorBorde --}}"
                    cellpadding="0" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <td class="justifyCenter bordesTabla anchoColumna125 fontSize8 fontCalibri">ÁREA</td>
                            <td class="justifyCenter bordesTablaSupInfDer fontSize8 anchoColumna125">NOMBRE DEL
                                RESPONSABLE</td>
                            <td class="justifyCenter bordesTablaSupInfDer fontSize8 anchoColumna125"
                                style="width: 25px">RECIPIENTES
                                RECIBIDOS</td>
                            <td class="justifyCenter bordesTablaSupInfDer fontSize8 anchoColumna125">FECHA DE SALIDA DEL
                                REFRIGERADOR P/ANALISIS</td>
                            <td class="justifyCenter bordesTablaSupInfDer fontSize8 anchoColumna125">FECHA ENTRADA DEL
                                REFRIGERADOR P/GUARDAR</td>
                            <td class="justifyCenter bordesTablaSupInfDer fontSize8 anchoColumna125">FECHA SALIDA DEL
                                REFRIGERADOR P/ELIMINAR</td>
                            <td class="justifyCenter bordesTablaSupInfDer fontSize8 anchoColumna125">FECHA EMISION DE
                                RESULTADOS</td>
                            <td class="justifyCenter bordesTablaSupInfDer fontSize8 anchoColumna125">FIRMA</td>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($data2->count())
                        @foreach ($data2 as $item)
                        <tr>
                            <td class="bordesTablaInfIzqDer fontSize8 fontCalibri negrita"
                                style="font-size:7px; margin:10px">{{ $item['Area'] }}</td>
                            <td class="bordesTablaInfIzqDer fontCalibri negrita fontSize8"
                                style="font-size:7px; margin:10px"> {{ $item['Responsable'] }}</td>
                            <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8"
                                style="font-size:7px; margin:10px">{{$item->Recipientes}}</td>
                            <td class="justifyCenter bordesTablaInfIzqDer fontSize8 fontCalibri negrita"
                                style="font-size:7px; margin:10px">{{$item->Fecha_salida}}</td>
                            <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8"
                                style="font-size:7px; margin:10px">{{$item->Fecha_entrada}}</td>
                            <td class="justifyCenter bordesTablaInfIzqDer fontSize8 fontCalibri negrita"
                                style="font-size:7px; margin:10px">{{$item->Fecha_salidaEli}}</td>
                            <td class="justifyCenter bordesTablaInfIzqDer fontSize8 fontCalibri negrita"
                                style="font-size:7px; margin:10px">{{$item->Fecha_emision}}</td>
                            <td class="bordesTablaInfIzqDer fontSize8 fontCalibri negrita"
                                style="font-size:7px; margin:10px">
                                <center><img style="width: auto; height: auto; max-width: 45px; max-height: 25px;"
                                        src="{{url('public/storage/'.@$item['Firma'])}}"></center>
                            </td>
                        </tr>
                        @endforeach
                        @endif

                    </tbody>

                </table>
            </div>

            <div class="col-12" style="font-size: 9px">

                2. RESULTADOS
            </div>
            @php
            $totalItems = $codigo->count();
            $columnCount = 3;
            $rows = ceil($totalItems / $columnCount);

            $verticalMatrix = [];
            for ($col = 0; $col < $columnCount; $col++) { for ($row=0; $row < $rows; $row++) { $index=$row + ($rows *
                $col); $verticalMatrix[$row][$col]=$codigo[$index] ?? null; } } @endphp <table class="table-sm"
                cellpadding="0" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        @for ($i = 0; $i < 3; $i++) <td
                            class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri negrita"
                            style="font-size: 9px">Parámetro</td>
                            <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri negrita"
                                style="font-size: 9px">Resultado</td>
                            @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach ($verticalMatrix as $fila)
                    <tr>
                        @foreach ($fila as $item)
                        <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri"
    style="font-size: 8.5PX; width: 25px">
    @if ($item)
        @php
            // Contar repeticiones del parámetro
            $repeticiones = collect($verticalMatrix)->flatten()->where('parametro.Parametro', $item->parametro->Parametro)->count();
        @endphp

        @if ($repeticiones > 1 || in_array($item->parametro->Id_parametro, [16, 13, 12, 35, 253]))
            <!-- Mostrar número de muestra junto con el parámetro y unidad -->
            {{ $item->Num_muestra }} - {{ $item->parametro->Parametro ?? '-----------' }}
            {{ $item->parametro->unidad->Unidad ?? '' }}
        @else
            <!-- Solo el parámetro y unidad -->
            {{ $item->parametro->Parametro ?? '-----------' }}
            {{ $item->parametro->unidad->Unidad ?? '' }}
        @endif
    @else
        ----------- 
    @endif
</td>


                        <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri"
                            style="font-size: 8.5PX; width: 25px">
                            {{ $item->resTemp ?? '-----------' }}
                        </td>
                        @endforeach
                    </tr>
                    @endforeach

                </tbody>
                </table>



        </div>
    </div>

</body>

</html>