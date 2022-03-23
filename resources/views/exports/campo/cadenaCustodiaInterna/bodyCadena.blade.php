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
                <div class="fontNormal fontCalibri justifyRight fontSize13">
                        CADENA DE CUSTODIA INTERNA
                    </div>                
                    <div class="fontNormal fontCalibri">
                        1.-DATOS GENERALES
                    </div>
                    <div class="fontCalibri">
                        <table class="table-sm" width="100%">
                            <tr>
                                <td>N° de Muestra &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="negrita">{{$model->Folio_servicio}}</span></td>                                
                                <td>Tipo de Muestra: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="negrita">{{@$tipoMuestra->Descarga}}</span></td>
                                <td>Norma Aplicable: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="negrita">{{@$norma->Clave_norma}}</span></td>                                
                            </tr>
                        </table>
                    </div>                    
                </div>                                
            </div>

            <br>

            <div class="col-md-12">
                <table class="{{-- table --}} {{-- table-bordered border-dark --}} table-sm {{-- colorBorde --}}" cellpadding="0" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <td class="justifyCenter bordesTabla anchoColumna125 fontSize8 fontCalibri">ÁREA</td>
                            <td class="justifyCenter bordesTablaSupInfDer fontSize8 anchoColumna125">NOMBRE DEL RESPONSABLE</td>
                            <td class="justifyCenter bordesTablaSupInfDer fontSize8 anchoColumna125">RECIPIENTES RECIBIDOS</td>
                            <td class="justifyCenter bordesTablaSupInfDer fontSize8 anchoColumna125">FECHA DE SALIDA DEL REFRIGERADOR P/ANALISIS</td>
                            <td class="justifyCenter bordesTablaSupInfDer fontSize8 anchoColumna125">FECHA ENTRADA DEL REFRIGERADOR P/GUARDAR</td>
                            <td class="justifyCenter bordesTablaSupInfDer fontSize8 anchoColumna125">FECHA SALIDA DEL REFRIGERADOR P/ELIMINAR</td>
                            <td class="justifyCenter bordesTablaSupInfDer fontSize8 anchoColumna125">FECHA EMISION DE RESULTADOS</td>
                            <td class="justifyCenter bordesTablaSupInfDer fontSize8 anchoColumna125">FIRMA</td>                    
                        </tr>
                    </thead>

                    <tbody>                                           
                        @for ($i = 0; $i < @$paqueteLength; $i++)
                            <tr>
                                <td class="bordesTablaInfIzqDer fontSize8 fontCalibri negrita">{{@$paquete[$i]->Area}}                                    
                                </td>
                                <td class="bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{@$responsables[$i]}}</td>
                                <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{@$paquete[$i]->Cantidad}}</td>
                                <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{\Carbon\Carbon::parse(@$paquete[$i]->created_at)->format('d/m/Y')}}</td>
                                <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{\Carbon\Carbon::parse(@$paquete[$i]->created_at)->format('d/m/Y')}}</td>
                                <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{\Carbon\Carbon::parse(@$paquete[$i]->created_at)->addDays(rand(12,14))->format('d/m/Y')}}</td>
                                <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{\Carbon\Carbon::parse(@$fechaEmision)->format('d/m/Y')}}</td>
                                <td class="justifyCenter bordesTablaInfIzqDer"><img style="width: auto; height: auto; max-width: 75px; max-height: 55px;" src="https://sistemaacama.com.mx/public/storage/users/January2022/3hR0dNwIyWQiodmdxvLX.png"></td>                                
                            </tr>
                        @endfor
                    </tbody>
                    
                </table>
            </div>

            <div class="col-12">
                3. RESULTADOS
            </div>

            <div class="col-md-12">
                <table class="{{-- table --}} {{-- table-bordered border-dark --}} table-sm {{-- colorBorde --}}" cellpadding="0" cellspacing="0" width="100%">

                    @php                        
                        $semaforo = 0;                        

                        if($paramResultadoLength >= 0 && $paramResultadoLength < 10){
                            $semaforo = 1;
                        }else if($paramResultadoLength >= 10 && $paramResultadoLength < 20){
                            $semaforo = 2;
                        }else if($paramResultadoLength >= 20 && $paramResultadoLength < 30){
                            $semaforo = 3;
                        }else if($paramResultadoLength >= 30){
                            $semaforo = 4;
                        }
                    @endphp

                    <thead>
                        <tr>
                            @if ($paramResultadoLength >= 0 && $paramResultadoLength < 10)
                                <td class="negrita bordesTabla anchoColumna125 fontSize8 fontCalibri">Parametro</td>
                                <td class="negrita justifyCenter bordesTablaSupInfDer fontSize8 anchoColumna125">Resultado</td>
                            @endif

                            @if ($paramResultadoLength >= 10 && $paramResultadoLength < 20)
                                <td class="negrita bordesTablaSupInfDer fontSize8 anchoColumna125">Parametro</td>
                                <td class="negrita justifyCenter bordesTablaSupInfDer fontSize8 anchoColumna125">Resultado</td>
                                <td class="negrita bordesTablaSupInfDer fontSize8 anchoColumna125">Parametro</td>
                                <td class="negrita justifyCenter bordesTablaSupInfDer fontSize8 anchoColumna125">Resultado</td>
                            @endif
                            
                            @if ($paramResultadoLength >= 20 && $paramResultadoLength < 30)
                                <td class="negrita bordesTablaSupInfDer fontSize8 anchoColumna125">Parametro</td>
                                <td class="negrita justifyCenter bordesTablaSupInfDer fontSize8 anchoColumna125">Resultado</td>
                                <td class="negrita bordesTablaSupInfDer fontSize8 anchoColumna125">Parametro</td>
                                <td class="negrita justifyCenter bordesTablaSupInfDer fontSize8 anchoColumna125">Resultado</td>
                                <td class="negrita bordesTablaSupInfDer fontSize8 anchoColumna125">Parametro</td>
                                <td class="negrita justifyCenter bordesTablaSupInfDer fontSize8 anchoColumna125">Resultado</td>
                            @endif

                            @if ($paramResultadoLength >= 30)
                                <td class="negrita bordesTablaSupInfDer fontSize8 anchoColumna125">Parametro</td>
                                <td class="negrita justifyCenter bordesTablaSupInfDer fontSize8 anchoColumna125">Resultado</td>
                                <td class="negrita bordesTablaSupInfDer fontSize8 anchoColumna125">Parametro</td>
                                <td class="negrita justifyCenter bordesTablaSupInfDer fontSize8 anchoColumna125">Resultado</td>
                                <td class="negrita bordesTablaSupInfDer fontSize8 anchoColumna125">Parametro</td>
                                <td class="negrita justifyCenter bordesTablaSupInfDer fontSize8 anchoColumna125">Resultado</td>
                                <td class="negrita bordesTablaSupInfDer fontSize8 anchoColumna125">Parametro</td>
                                <td class="negrita justifyCenter bordesTablaSupInfDer fontSize8 anchoColumna125">Resultado</td>
                            @endif                            
                        </tr>
                    </thead>

                    <tbody>                                                                                    
                        @for ($i = 0; $i < $paramResultadoLength; $i+=$semaforo)
                            <tr>                                
                                @if ($paramResultadoLength < 10)
                                    <td class="bordesTablaInfIzqDer fontSize8 fontCalibri negrita">{{@$paramResultado[$i]->Parametro}}</td>
                                    <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{$limitesC[$i]}}</td>
                                @endif                                
                                                                
                                @if ($paramResultadoLength >= 10 && $paramResultadoLength < 20)                                    
                                    <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{@$paramResultado[$i]->Parametro}}</td>
                                    <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{$limitesC[$i]}}</td>
                                    <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{@$paramResultado[$i+1]->Parametro}}</td>
                                    <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{$limitesC[$i+1]}}</td>

                                @endif                                
                                                                
                                @if ($paramResultadoLength >= 20 && $paramResultadoLength <= 30)
                                    <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{@$paramResultado[$i]->Parametro}}</td>
                                    <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{$limitesC[$i]}}</td>
                                    <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{@$paramResultado[$i+1]->Parametro}}</td>
                                    <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{$limitesC[$i+1]}}</td>
                                    <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{@$paramResultado[$i+2]->Parametro}}</td>
                                    <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{$limitesC[$i+2]}}</td>
                                @endif                                
                                
                                @if ($paramResultadoLength >= 30)
                                    <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{@$paramResultado[$i]->Parametro}}</td>
                                    <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{$limitesC[$i]}}</td>
                                    <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{@$paramResultado[$i+1]->Parametro}}</td>
                                    <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{$limitesC[$i+1]}}</td>
                                    <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{@$paramResultado[$i+2]->Parametro}}</td>
                                    <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{$limitesC[$i+2]}}</td>
                                    <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{@$paramResultado[$i+3]->Parametro}}</td>
                                    <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{$limitesC[$i+3]}}</td>
                                @endif                                                                                     
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
                                <td class="fontCalibri anchoColumna111 fontSize8">GRASAS Y ACEITES (G Y A) mg/Ls</td>
                                <td class="fontCalibri anchoColumna111 fontSize8">{{@$promedioPonderadoGA}}</td>
                                <td class="fontCalibri anchoColumna111 fontSize8">COLIFORMES FECALES NMP/100mL</td>
                                <td class="fontCalibri anchoColumna111 fontSize8">{{@$mAritmeticaColi}}</td>
                                <td class="fontCalibri anchoColumna111 fontSize8">GASTO L/s</td>
                                <td class="fontCalibri anchoColumna111 fontSize8">{{@$gastoPromFinal}}</td>
                                <td class="fontCalibri anchoColumna111 justifyCenter"><span class="fontSize7 negrita">FIRMA RESPONSABLE</span> <br> <span class="fontSize8">Q.F.B. RODRÍGUEZ BLANCO AGUEDA</span> &nbsp;&nbsp; </td>
                                <td class="justifyCenter anchoColumna111"><img style="width: auto; height: auto; max-width: 60px; max-height: 40px;" src="https://sistemaacama.com.mx/public/storage/users/January2022/3hR0dNwIyWQiodmdxvLX.png"></td>
                                
                                @php
                                    $bar_code = "data:image/png;base64," . \DNS1D::getBarcodePNG($model->Folio_servicio, "C39");
                                @endphp

                                <td class="justifyCenter anchoColumna111"><img style="width: 10%" src="{{$bar_code}}" alt="barcode" /> <br> {{@$model->Folio_servicio}}</td>
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
                      <td class="anchoColumna">10 Sur No. 7301, Col. Loma linda C.P 72477</td>
                      <td class="justifyCenter">PUEBLA, PUE.</td>
                      <td class="justifyCenter">Email:labacama@prodigy.net.mx</td>
                      <td class="justifyRight">RE-11-003-1</td>
                    </tr>

                    <tr>
                        <td class="anchoColumna" style="border: 0">Tels (222) 2-45-69-72 / 7-55-50-05 / 7-55-50-14 / 6-37-94-04</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td class="justifyRight">Rev. 9</td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td class="justifyRight">Fecha ultima revisión: 01/04/2016</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>    
</body>
</html>