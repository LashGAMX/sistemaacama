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
                    @php
                        $tempArea = array();
                        $temp = 0;
                        $sw = false
                    @endphp
                    <tbody>
                        @for ($i = 0; $i < sizeof($area); $i++)    
                            <tr>
                                <td class="bordesTablaInfIzqDer fontSize8 fontCalibri negrita">{{$area[$i]}} </td>
                                <td class="bordesTablaInfIzqDer fontCalibri negrita fontSize8"> {{$responsable[$i]}}</td>
                                <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{$numRecipientes[$i]}}</td>
                                <td class="bordesTablaInfIzqDer fontSize8 fontCalibri negrita"> 
                                    @if ($fechasSalidas[$i] != "")
                                        {{\Carbon\Carbon::parse(@$fechasSalidas[$i])->format('d/m/Y')}}
                                    @else
                                        <p style="color: red">Sin captura</p>
                                    @endif
                                </td>
                                <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">
                                    @if ($stdArea[$i] == 1)
                                       --------------- 
                                    @else
                                        @if ($fechasSalidas[$i] != "")
                                            {{\Carbon\Carbon::parse(@$fechasSalidas[$i])->format('d/m/Y')}}
                                        @else
                                            <p style="color: red">Sin captura</p>
                                        @endif
                                    @endif
                                </td>
                                <td class="bordesTablaInfIzqDer fontSize8 fontCalibri negrita">
                                    @if ($stdArea[$i] == 1)
                                      --------------- 
                                    @else
                                        @if ($fechasSalidas[$i] != "")
                                            @if (@$idArea[$i] == 12 || @$idArea[$i]== 6 || @$idArea[$i] == 13 || @$idArea[$i] == 3 ) 
                                                --------------- 
                                            @else
                                                @switch($model->Id_norma)
                                                @case(1)
                                                @case(27)  
                                                    {{\Carbon\Carbon::parse(@$recepcion->Hora_recepcion)->addDays(18)->format('d/m/Y')}}  
                                                    @break
                                                @case(5)
                                                @case(30)   
                                                        {{\Carbon\Carbon::parse(@$recepcion->Hora_recepcion)->addDays(21)->format('d/m/Y')}}  
                                                    @break
                                                    @default
                                                    {{\Carbon\Carbon::parse(@$recepcion->Hora_recepcion)->addDays(18)->format('d/m/Y')}}
                                                @endswitch
                                            @endif

                                        
                                        @else
                                            <p style="color: red">Sin captura</p> 
                                        @endif
                                    @endif
                                </td>
                                <td class="bordesTablaInfIzqDer fontSize8 fontCalibri negrita"> 
                                    @if ($fechasSalidas[$i] != "")
                                        @switch($model->Id_norma)
                                            @case(1)
                                            @case(27)  
                                                {{\Carbon\Carbon::parse(@$recepcion->Hora_recepcion)->addDays(11)->format('d/m/Y')}}  
                                                @break
                                            @case(5)
                                            @case(30)  
                                                {{\Carbon\Carbon::parse(@$recepcion->Hora_recepcion)->addDays(14)->format('d/m/Y')}}  
                                                @break
                                            @default
                                            {{\Carbon\Carbon::parse(@$recepcion->Hora_recepcion)->addDays(11)->format('d/m/Y')}}
                                        @endswitch
                                    @else
                                        <p style="color: red">Sin captura</p>
                                    @endif
                                </td>
                                <td class="bordesTablaInfIzqDer fontSize8 fontCalibri negrita"> 
                                    @if ($fechasSalidas[$i] != "")
                                        <center><img style="width: auto; height: auto; max-width: 45px; max-height: 25px;" src="{{url('public/storage/'.@$firmas[$i])}}"></center>
                                    @else
                                        <p style="color: red">Sin captura</p>
                                    @endif
                                </td>
                            </tr>
                        @endfor
                    </tbody>

                </table>
            </div>

            <br>
            <div class="col-12">
                2. {{$reportesCadena->Seccion2}}
            </div> 
            <br>
            <div id="contenedorTabla" >
                <table class="{{-- table --}} {{-- table-bordered border-dark --}} table-sm {{-- colorBorde --}}" cellpadding="0" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <td class="justifyCenter bordesTabla anchoColumna125 fontSize8 fontCalibri" style="font-size: 8px">Párametro</td>
                            <td class="justifyCenter bordesTabla anchoColumna125 fontSize8 fontCalibri" style="font-size: 8PX">Resultado</td>
                            <td class="justifyCenter bordesTabla anchoColumna125 fontSize8 fontCalibri" style="font-size: 8px">Párametro</td>
                            <td class="justifyCenter bordesTabla anchoColumna125 fontSize8 fontCalibri" style="font-size: 8PX">Resultado</td>
                            <td class="justifyCenter bordesTabla anchoColumna125 fontSize8 fontCalibri" style="font-size: 8px">Párametro</td>
                            <td class="justifyCenter bordesTabla anchoColumna125 fontSize8 fontCalibri" style="font-size: 8PX">Resultado</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $temp = ceil($paramResultado->count() / 3);
                        @endphp 
                        @for ($i = 0; $i < $temp; $i++)
                            <tr>
                                {{-- <td class="justifyCenter bordesTabla anchoColumna125 fontSize8 fontCalibri">{{@$paramResultado[$i]->Id_parametro}}{{@$paramResultado[$i]->Parametro}} - {{@$paramResultado[$i]->Num_muestra}} {{@$paramResultado[$i]->Unidad}}</td>
                                <td class="justifyCenter bordesTabla anchoColumna125 fontSize8 fontCalibri">{{@$resInfo[$i]}}</td>
                                <td class="justifyCenter bordesTabla anchoColumna125 fontSize8 fontCalibri">{{@$paramResultado[$i + $temp]->Id_parametro}}{{@$paramResultado[$i + $temp]->Parametro}} - {{@$paramResultado[$i + $temp]->Num_muestra}} {{@$paramResultado[$i + $temp]->Unidad}}</td>
                                <td class="justifyCenter bordesTabla anchoColumna125 fontSize8 fontCalibri">{{@$resInfo[$i + $temp]}}</td>
                                <td class="justifyCenter bordesTabla anchoColumna125 fontSize8 fontCalibri">{{@$paramResultado[$i + ($temp  * 2)]->Id_parametro}}{{@$paramResultado[$i + ($temp * 2)]->Parametro}} - {{@$paramResultado[$i + ($temp * 2)]->Num_muestra}} {{@$paramResultado[$i + ($temp * 2)]->Unidad}}</td>
                                <td class="justifyCenter bordesTabla anchoColumna125 fontSize8 fontCalibri">{{@$resInfo[$i + ($temp * 2)]}}</td> --}}

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



        </div>
    </div>
</body>

</html>