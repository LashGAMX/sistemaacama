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
                    {{$reportesCadena->Encabezado}}
                    </div>
                    <div class="fontNormal fontCalibri"  style="font-size: 8px">
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
                                        class="negrita">{{@$model->Clave_norma}}</span></td>
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
                            <td class="justifyCenter bordesTablaSupInfDer fontSize8 anchoColumna125" style="width: 25px">RECIPIENTES
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
                    @if ($cadenaGenerales->count())
                        <tbody>
                            @foreach ($cadenaGenerales as $item)
                                 <tr>
                                    <td class="bordesTablaInfIzqDer fontSize8 fontCalibri negrita" style="font-size:7px; margin:10px">{{$item->Area}}</td>
                                    <td class="bordesTablaInfIzqDer fontCalibri negrita fontSize8" style="font-size:7px; margin:10px"> {{$item->Responsable}}</td>
                                    <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8" style="font-size:7px; margin:10px">{{$item->Recipientes}}</td>
                                    <td class="justifyCenter bordesTablaInfIzqDer fontSize8 fontCalibri negrita" style="font-size:7px; margin:10px">{{$item->Fecha_salida}}</td>
                                    <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8" style="font-size:7px; margin:10px">{{$item->Fecha_entrada}}</td>
                                    <td class="justifyCenter bordesTablaInfIzqDer fontSize8 fontCalibri negrita" style="font-size:7px; margin:10px">{{$item->Fecha_salidaEli}}</td>
                                    <td class="justifyCenter bordesTablaInfIzqDer fontSize8 fontCalibri negrita" style="font-size:7px; margin:10px">{{$item->Fecha_emision}}</td>
                                    <td class="bordesTablaInfIzqDer fontSize8 fontCalibri negrita" style="font-size:7px; margin:10px">
                                        <center><img style="width: auto; height: auto; max-width: 45px; max-height: 25px;" src="{{url('public/storage/'.@$item->Firma)}}"></center>
                                    </td>
                                </tr>
                            @endforeach
                           
                        </tbody>

                    @else
                        @php
                            $tempArea = array(); 
                            $temp = 0;
                            $sw = false
                        @endphp
                        <tbody>
                            @for ($i = 0; $i < sizeof($area); $i++)    
                                <tr>
                                    <!-- <td class="bordesTablaInfIzqDer fontSize8 fontCalibri negrita">{{$area[$i]}} {{@$stdArea[$i]}}</td> -->
                                    <td class="bordesTablaInfIzqDer fontSize8 fontCalibri negrita" style="font-size:7px; margin:10px">{{$area[$i]}}</td>
                                    <td class="bordesTablaInfIzqDer fontCalibri negrita fontSize8" style="font-size:7px; margin:10px"> {{$responsable[$i]}}</td>
                                    <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8" style="font-size:7px; margin:10px">{{$numRecipientes[$i]}}</td>
                                    <td class="justifyCenter bordesTablaInfIzqDer fontSize8 fontCalibri negrita" style="font-size:7px; margin:10px"> 
                                        @if (@$fechasSalidas[$i] != "")
                                            {{\Carbon\Carbon::parse(@$fechasSalidas[$i])->format('d/m/Y')}}

                                        @else
                                            <p style="color: red">Sin captura</p>
                                        @endif
                                    </td>
                                    <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8" style="font-size:7px; margin:10px">

                                    @if ($stdArea[$i] == 1)
                                        ---------------
                                    @else
                                        @if (@$fechasSalidas[$i] != "")
                                            @if (@$idArea[$i] == 12 || @$idArea[$i]== 6 || @$idArea[$i] == 13 || @$idArea[$i] == 3 ) 
                                                ---------------
                                            @else
                                            {{\Carbon\Carbon::parse(@$fechasSalidas[$i])->format('d/m/Y')}}
                                            @endif

                                        
                                        @else
                                            <p style="color: red">Sin captura</p> 
                                        @endif
                                    @endif
                                    </td>
                                    <td class="justifyCenter bordesTablaInfIzqDer fontSize8 fontCalibri negrita" style="font-size:7px; margin:10px">
                                        @if ($stdArea[$i] == 1)
                                        --------------- 
                                        @else
                                            @if (@$fechasSalidas[$i] != "")
                                                @if (@$idArea[$i] == 12 || @$idArea[$i]== 6 || @$idArea[$i] == 13 || @$idArea[$i] == 3 ) 
                                                    ---------------
                                                @else
                                                    @switch($model->Id_norma)
                                                    @case(1)
                                                    @case(27)  
                                                    @case(33) 
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
                                    <td class="justifyCenter bordesTablaInfIzqDer fontSize8 fontCalibri negrita" style="font-size:7px; margin:10px"> 
                                        @if (@$fechasSalidas[$i] != "")
                                            @switch($model->Id_norma)
                                                @case(1)
                                                @case(27)  
                                                @case(33)  
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
                                    <td class="bordesTablaInfIzqDer fontSize8 fontCalibri negrita" style="font-size:7px; margin:10px"> 
                                        @if (@$fechasSalidas[$i] != "")
                                            <center><img style="width: auto; height: auto; max-width: 45px; max-height: 25px;" src="{{url('public/storage/'.@$firmas[$i])}}"></center>
                                        @else
                                            <p style="color: red">Sin captura</p>
                                        @endif
                                    </td>
                                </tr>
                            @endfor
                        </tbody>

                    @endif
                  
                </table>
            </div>

            
            <div class="col-12" style="font-size: 9px">
               
                2. {{$reportesCadena->Seccion2}}
  
            </div> 
           
            <div id="contenedorTabla" >
                
                <table class="{{-- table --}} {{-- table-bordered border-dark --}} table-sm {{-- colorBorde --}}" cellpadding="0" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri negrita" style="font-size: 9px">Párametro</td>
                            <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri negrita" style="font-size: 9PX; width: 25px">Resultado</td>
                            <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri negrita" style="font-size: 9px">Párametro</td>
                            <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri negrita" style="font-size: 9PX; width: 25px">Resultado</td>
                            <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri negrita" style="font-size: 9px">Párametro</td>
                            <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri negrita" style="font-size: 9PX; width: 25px">Resultado</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $temp = ceil($paramResultado->count() / 3);
                        @endphp 
                        @for ($i = 0; $i < $temp; $i++)
                            <tr>
                                {{-- <td class="justifyCenter bordesTabla anchoColumna125 fontSize8 fontCalibri" style="font-size:8.5px">{{@$paramResultado[$i]->Id_parametro}}{{@$paramResultado[$i]->Parametro}} - {{@$paramResultado[$i]->Num_muestra}} {{@$paramResultado[$i]->Unidad}}</td>
                                <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri "style="font-size: 8.5PX; width: 25px">{{@$resInfo[$i]}}</td>
                                <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri " style="font-size:8.5px">{{@$paramResultado[$i + $temp]->Id_parametro}}{{@$paramResultado[$i + $temp]->Parametro}} - {{@$paramResultado[$i + $temp]->Num_muestra}} {{@$paramResultado[$i + $temp]->Unidad}}</td>
                                <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri "style="font-size: 8.5PX; width: 25px">{{@$resInfo[$i + $temp]}}</td>
                                <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri " style="font-size:8.5px">{{@$paramResultado[$i + ($temp  * 2)]->Id_parametro}}{{@$paramResultado[$i + ($temp * 2)]->Parametro}} - {{@$paramResultado[$i + ($temp * 2)]->Num_muestra}} {{@$paramResultado[$i + ($temp * 2)]->Unidad}}</td>
                                <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri "style="font-size: 8.5PX; width: 25px">{{@$resInfo[$i + ($temp * 2)]}}</td> --}}
                                <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri " style="font-size:8.5px">
                                    @if (@$paramResultado[$i]->Id_area == 12 || @$paramResultado[$i]->Id_area == 6 || @$paramResultado[$i]->Id_area == 13 || @$paramResultado[$i]->Id_area == 3 ) 
                                        @switch(@$paramResultado[$i]->Id_parametro)
                                            @case(5)
                                            @case(71)
                                            {{@$paramResultado[$i]->Parametro}} {{@$paramResultado[$i]->Unidad}}    
                                                @break
                                            @default
                                            {{@$paramResultado[$i]->Num_muestra}} - {{@$paramResultado[$i]->Parametro}} {{@$paramResultado[$i]->Unidad}}
                                        @endswitch
                                    @else 
                                        {{@$paramResultado[$i]->Parametro}} {{@$paramResultado[$i]->Unidad}}
                                    @endif 
                                </td>
                                <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri "style="font-size: 8.5PX; width: 25px">{{@$resInfo[$i]}}</td>
                                <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri " style="font-size:8.5px">
                                    @if (@$paramResultado[$i + $temp]->Id_area == 12 || @$paramResultado[$i + $temp]->Id_area == 6 || @$paramResultado[$i + $temp]->Id_area == 13 || @$paramResultado[$i + $temp]->Id_area == 3) 
                                        @switch(@$paramResultado[$i + $temp]->Id_parametro)
                                        @case(5)
                                        @case(71)
                                            {{@$paramResultado[$i + $temp]->Parametro}} {{@$paramResultado[$i + $temp]->Unidad}}
                                            @break
                                        @default
                                            {{@$paramResultado[$i + $temp]->Num_muestra}} - {{@$paramResultado[$i + $temp]->Parametro}} {{@$paramResultado[$i + $temp]->Unidad}}
                                        @endswitch
                                    @else
                                        {{@$paramResultado[$i + $temp]->Parametro}} {{@$paramResultado[$i + $temp]->Unidad}}
                                    @endif
                                </td>
                                <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri "style="font-size: 8.5PX; width: 25px">{{@$resInfo[$i + $temp]}}</td>
                                <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri " style="font-size:8.5px">
                                    @if (@$paramResultado[$i + ($temp * 2)]->Id_area == 12 || @$paramResultado[$i + ($temp * 2)]->Id_area == 6 || @$paramResultado[$i + ($temp * 2)]->Id_area == 13 || @$paramResultado[$i + ($temp * 2)]->Id_area == 3) 
                                        @switch(@$paramResultado[$i + ($temp * 2)]->Id_parametro)
                                        @case(5)
                                        @case(71)
                                            {{@$paramResultado[$i + ($temp * 2)]->Parametro}} {{@$paramResultado[$i + ($temp * 2)]->Unidad}}
                                            @break
                                        @default
                                            {{@$paramResultado[$i + ($temp * 2)]->Num_muestra}} - {{@$paramResultado[$i + ($temp * 2)]->Parametro}} {{@$paramResultado[$i + ($temp * 2)]->Unidad}}
                                        @endswitch
                                    @else
                                        {{@$paramResultado[$i + ($temp * 2)]->Parametro}} {{@$paramResultado[$i + ($temp * 2)]->Unidad}}
                                    @endif
                                </td>
                                <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri "style="font-size: 8.5PX; width: 25px">{{@$resInfo[$i + ($temp * 2)]}}</td>
                            </tr>
                        @endfor
                    </tbody> 
                </table>
            
            </div>



        </div>
    </div>
</body>

</html>