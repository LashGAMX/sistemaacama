<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/custodiaInterna/custodiaInterna.css')}}">
    {{--
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    --}}
    <title>Cadena de custodia interna</title>
</head>

<body>
    <div class="container" id="pag">
        <div class="row">

            <div class="col-12 negrita">
                <div>
                    <div class="fontNormal fontCalibri justifyRight fontSize13">
                    {{$reportesCadena->Encabezado}}
                    </div>
                    <div class="fontNormal fontCalibri">
                        1.-{{$reportesCadena->Seccion1}}
                    </div>
                    <div class="fontCalibri">
                        <table class="table-sm" width="100%">
                            <tr>
                                <td>{{$reportesCadena->Titulo1}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span
                                        class="negrita">{{$model->Folio_servicio}}</span></td>
                                <td>{{$reportesCadena->Titulo2}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span
                                        class="negrita">{{@$model->Descarga}}</span></td>
                                <td>{{$reportesCadena->Titulo3}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span
                                        class="negrita">{{@$norma->Clave_norma}}</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <br>

            <div class="col-md-12">
                <table class="{{-- table --}} {{-- table-bordered border-dark --}} table-sm {{-- colorBorde --}}"
                    cellpadding="0" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <td class="justifyCenter bordesTabla anchoColumna125 fontSize8 fontCalibri">ÁREA</td>
                            <td class="justifyCenter bordesTablaSupInfDer fontSize8 anchoColumna125">NOMBRE DEL
                                RESPONSABLE</td>
                            <td class="justifyCenter bordesTablaSupInfDer fontSize8 anchoColumna125">RECIPIENTES
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
                        @for ($i = 0; $i < @$paqueteLength; $i++) @if (@$paquete[$i]->Reportes == 1)
                            <tr>
                                <td class="bordesTablaInfIzqDer fontSize8 fontCalibri negrita">{{@$paquete[$i]->Area}}
                                </td>
                                <td class="bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{@$paquete[$i]->name}}
                                </td>
                                
                                @if (@$paquete[$i]->Id_area == 2 || @$paquete[$i]->Id_area == 7 || @$paquete[$i]->Id_area == 16 ||  @$paquete[$i]->Id_area == 17 )
                                <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">
                                    {{@$recibidos->count()}}</td>
                                @else
                                <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">
                                    {{@$paquete[$i]->Cantidad}}</td>
                                @endif

                                <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">@if ($fechasSalidas[$i] != "") {{\Carbon\Carbon::parse($fechasSalidas[$i])->format('d/m/Y')}} @else <p style="color: red">Sin captura</p> @endif</td>

                                <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">
                                    @if (@$paquete[$i]->Id_area == 2 || @$paquete[$i]->Id_area == 9 || @$paquete[$i]->Id_area == 16 ||  @$paquete[$i]->Id_area == 17)
                                    --------------- 
                                    @else
                                        @if ($fechasSalidas[$i] != "")
                                            {{\Carbon\Carbon::parse(@$fechasSalidas[$i])->format('d/m/Y')}}
                                        @else
                                            <p style="color: red">Sin captura</p>
                                        @endif
                                    @endif
                                </td>
                                <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">
                                    @if (@$paquete[$i]->Id_area == 2 || @$paquete[$i]->Id_area == 9 || @$paquete[$i]->Id_area == 16 ||  @$paquete[$i]->Id_area == 17)
                                    ---------------
                                    @else
                                        @if ($fechasSalidas[$i] != "")
                                            {{\Carbon\Carbon::parse(@$recepcion->Hora_recepcion)->addDays(18)->format('d/m/Y')}}
                                        @else
                                            <p style="color: red">Sin captura</p>
                                        @endif
                                    @endif
                                </td>
                                <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">
                                    @if ($fechasSalidas[$i] != "")
                                        {{\Carbon\Carbon::parse(@$recepcion->Hora_recepcion)->addDays(11)->format('d/m/Y')}}
                                    @else
                                        <p style="color: red">Sin captura</p>
                                    @endif
                                </td>
                                <td class="justifyCenter bordesTablaInfIzqDer">
                                    @if ($fechasSalidas[$i] != "")
                                        <img style="width: auto; height: auto; max-width: 45px; max-height: 25px;" src="{{url('public/storage/'.@$paquete[$i]->firma)}}">
                                    @else
                                        <p style="color: red">Sin captura</p>
                                    @endif
                                </td>
                            </tr>
                            @endif
                            @endfor
                    </tbody>

                </table>
            </div>

            <div class="col-12">
                2. {{$reportesCadena->Seccion2}}
            </div>
            <div id="contenedorTabla" >
                <table class="{{-- table --}} {{-- table-bordered border-dark --}} table-sm {{-- colorBorde --}}" cellpadding="0" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <td class="justifyCenter bordesTabla anchoColumna125 fontSize8 fontCalibri" style="font-size: 8px">Parametro</td>
                            <td class="justifyCenter bordesTabla anchoColumna125 fontSize8 fontCalibri" style="font-size: 8PX">Resultado</td>
                            <td class="justifyCenter bordesTabla anchoColumna125 fontSize8 fontCalibri" style="font-size: 8px">Parametro</td>
                            <td class="justifyCenter bordesTabla anchoColumna125 fontSize8 fontCalibri" style="font-size: 8PX">Resultado</td>
                            <td class="justifyCenter bordesTabla anchoColumna125 fontSize8 fontCalibri" style="font-size: 8px">Parametro</td>
                            <td class="justifyCenter bordesTabla anchoColumna125 fontSize8 fontCalibri" style="font-size: 8PX">Resultado</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $temp = ceil($paramResultadoLength / 3);
                        @endphp
                        @for ($i = 0; $i < $temp; $i++)
                            <tr>
                                <td class="justifyCenter bordesTabla anchoColumna125 fontSize8 fontCalibri">{{@$paramResultado[$i]->Parametro}} - {{@$paramResultado[$i]->Num_muestra}} {{@$paramResultado[$i]->Unidad}}</td>
                                <td class="justifyCenter bordesTabla anchoColumna125 fontSize8 fontCalibri">{{@$resInfo[$i]}}</td>
                                <td class="justifyCenter bordesTabla anchoColumna125 fontSize8 fontCalibri">{{@$paramResultado[$i + $temp]->Parametro}} - {{@$paramResultado[$i + $temp]->Num_muestra}} {{@$paramResultado[$i + $temp]->Unidad}}</td>
                                <td class="justifyCenter bordesTabla anchoColumna125 fontSize8 fontCalibri">{{@$resInfo[$i + $temp]}}</td>
                                <td class="justifyCenter bordesTabla anchoColumna125 fontSize8 fontCalibri">{{@$paramResultado[$i + ($temp * 2)]->Parametro}} - {{@$paramResultado[$i + ($temp * 2)]->Num_muestra}} {{@$paramResultado[$i + ($temp * 2)]->Unidad}}</td>
                                <td class="justifyCenter bordesTabla anchoColumna125 fontSize8 fontCalibri">{{@$resInfo[$i + ($temp * 2)]}}</td>
                            </tr>
                        @endfor
                    </tbody> 
                </table>
            
            </div>

            <br>

            <div class="col-12 negrita">
                <div>
                    <div>
                        <table class="table-sm" width="100%">
                            <tr>
                                @switch(@$norma->Id_norma)
                                @case(2)
                                <td class="fontCalibri anchoColumna111 fontSize8">GRASAS Y ACEITES (G Y A) mg/L </td>
                                <td class="fontCalibri anchoColumna111 fontSize8">
                                    @if (@$promGra->Resultado2 <= @$promGra->Limite)
                                        < {{@$promGra->Limite}}
                                            @else
                                            {{round(@$promGra->Resultado2,2)}}
                                            @endif
                                </td>

                                <td class="fontCalibri anchoColumna111 fontSize8">GASTO L/s</td>
                                <td class="fontCalibri anchoColumna111 fontSize8">{{round(@$promGas->Resultado2, 2)}}
                                </td>
                                @break
                                @case(30)
                                @break
                                @default
                                <td class="fontCalibri anchoColumna111 fontSize8">GRASAS Y ACEITES (G Y A) mg/L</td>
                                <td class="fontCalibri anchoColumna111 fontSize8">
                                    @if (@$promGra->Resultado2 <= @$promGra->Limite)
                                        < {{@$promGra->Limite}}
                                            @else
                                            {{round(@$promGra->Resultado2,2)}}
                                            @endif
                                </td>

                                <td class="fontCalibri anchoColumna111 fontSize8">COLIFORMES FECALES NMP/100mL</td>
                                <td class="fontCalibri anchoColumna111 fontSize8">{{round(@$promCol->Resultado2,2)}}
                                </td>

                                <td class="fontCalibri anchoColumna111 fontSize8">GASTO L/s</td>
                                <td class="fontCalibri anchoColumna111 fontSize8">{{round(@$promGas->Resultado2, 2)}}
                                </td>
                                @endswitch
                                <td class="fontCalibri anchoColumna111 justifyCenter"><span
                                        class="fontSize7 negrita">FIRMA RESPONSABLE</span> <br> <span
                                        class="fontSize8">{{$reportesCadena->Titulo_responsable}} {{$reportesCadena->Nombre_responsable}}</span> &nbsp;&nbsp; </td>
                                <td class="justifyCenter anchoColumna111"><img
                                        style="width: auto; height: auto; max-width: 60px; max-height: 40px;"
                                        src="{{url('public/storage/'.@$firmaRes->firma)}}"></td>

                                @php
                                /*$bar_code = "data:image/png;base64," . \DNS1D::getBarcodePNG($model->Folio_servicio,
                                "C39");*/
                                /*$url = url()->current();*/
                                $url =
                                "https://sistemaacama.com.mx/clientes/exportPdfCustodiaInterna/".@$model->Id_solicitud;
                                $qr_code = "data:image/png;base64," . \DNS2D::getBarcodePNG((string) $url, "QRCODE");
                                @endphp

                                <td class="justifyCenter anchoColumna111"><img style="width: 8%; height: 8%;"
                                        src="{{$qr_code}}" alt="barcode" /> <br> {{@$model->Folio_servicio}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-12" style="border:1px solid">
            </div>

            <div class="col-md-12">
                <table class="table table-sm fontSize7" width="100%">


                    <tr>
                        <td class="anchoColumna" style="border: 0"></td>
                        <td>&nbsp;</td>
                        <td class="justifyRight">Rev. {{$reportesCadena->Num_rev}}</td>
                        <td class="justifyRight">Fecha ultima revisión: 01/04/2016</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>

</html>