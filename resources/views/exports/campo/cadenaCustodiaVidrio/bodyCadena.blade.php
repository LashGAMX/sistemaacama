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
{{-- comentario --}}
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
           
            <div id="contenedorTabla">
            <table class="{{-- table --}} {{-- table-bordered border-dark --}} table-sm {{-- colorBorde --}}" cellpadding="0" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <td style="font-size: 8px;" class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri negrita" height="30" width="20.6%">PARAMETRO &nbsp;</td>
                    <td style="font-size: 8px;" class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri negrita" width="20.6%">&nbsp;METODO DE PRUEBA&nbsp;&nbsp;</td>
                    <td style="font-size: 8px;" class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri negrita" width="10.6%">&nbsp;UNIDAD&nbsp;&nbsp;</td>
                    <td style="font-size: 8px;" class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri negrita" colspan="2" width="10.6%">&nbsp;CONCENTRACION <br> CUANTIFICADA&nbsp;&nbsp;</td>       
                </tr>
            </thead>
    
            <tbody>
                @php $i = 0; @endphp
                @foreach ($paramResultado as $item)
                    <tr> 
                            <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri" style="font-size: 11px;" height="25" rowspan="6">{{$i + 1}} - {{@$item->Parametro}}<sup>{{$item->Simbologia}} </sup></td>
                            <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri" style="font-size: 11px;" rowspan="6">
                                {{$item->Clave_metodo}}
                            </td>
                            <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri" style="font-size: 11px;" rowspan="3">CE50 %</td>
                            <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri" style="font-size: 11px;">5 Min</td>
                            <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri" style="font-size: 11px;">{{$item->Resultado}}</td>
                           
                    </tr>   
                    <tr>
                    <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri" style="font-size: 11px;">15 Min</td>
                        <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri" style="font-size: 11px;">{{$item->Resultado2}}</td>
                    </tr>    
                    <tr>
                    <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri" style="font-size: 11px;">30 Min</td>
                        <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri" style="font-size: 11px;">{{$item->Resultado_aux}}</td>
                    </tr>    
                    <tr>
                    <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri" style="font-size: 11px;" rowspan="3">@if ($item->Ph_muestra == "1")
                        %E
                        @else
                            {{@$item->Unidad}}
                        @endif</td>
                    <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri" style="font-size: 11px;">5 Min</td>
                     <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri" style="font-size: 11px;">{{$item->Resultado_aux2}}</td>
                    </tr>    
                    <tr>
                    <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri" style="font-size: 11px;">15 Min</td>
                        <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri" style="font-size: 11px;">{{$item->Resultado_aux3}}</td>
                    </tr>    
                    <tr>
                    <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri" style="font-size: 11px;">30 Min</td>
                        <td class="justifyCenter bordesTabla anchoColumna125 fontSize9 fontCalibri" style="font-size: 11px;">{{$item->Resultado_aux4}}</td>
                    </tr>    
                        @php
                            $i++;
                        @endphp
                @endforeach

            </tbody>        
        </table>  
    </div> 


        </div>
    </div>
</body>

</html>