
 <!DOCTYPE html>    
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/custodiaInterna/custodiaInterna.css')}}">
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous"> --}}
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
                                <td>Tipo de Muestra: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="negrita">{{@$model->Descarga}}</span></td>
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
                                <td class="bordesTablaInfIzqDer fontSize8 fontCalibri negrita">{{@$paquete[$i]->Area}}</td>
                                <td class="bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{@$paquete[$i]->name}}</td>
                                @if (@$paquete[$i]->Id_area == 2 || @$paquete[$i]->Id_area == 7 || @$paquete[$i]->Id_area == 16)
                                    <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{@$recibidos->count()}}</td>
                                @else
                                    <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{@$paquete[$i]->Cantidad}}</td>
                                @endif
                                <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{\Carbon\Carbon::parse(@$recepcion->Hora_entrada)->format('d/m/Y')}}</td>
                                <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">                                    
                                    @if (@$paquete[$i]->Id_area == 2 || @$paquete[$i]->Id_area == 9 || @$paquete[$i]->Id_area == 16)
                                        ---------------
                                    @else
                                    {{\Carbon\Carbon::parse(@$recepcion->Hora_entrada)->format('d/m/Y')}}
                                    @endif
                                </td>
                                <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">
                                    @if (@$paquete[$i]->Id_area == 2 || @$paquete[$i]->Id_area == 9 || @$paquete[$i]->Id_area == 16)
                                        ---------------
                                    @else
                                        {{\Carbon\Carbon::parse(@$recepcion->Hora_entrada)->addDays(14)->format('d/m/Y')}}
                                    @endif
                                </td>
                                <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{\Carbon\Carbon::parse(@$recepcion->Hora_entrada)->addDays(7)->format('d/m/Y')}}</td>
                                <td class="justifyCenter bordesTablaInfIzqDer"><img style="width: auto; height: auto; max-width: 45px; max-height: 25px;" src="https://sistemaacama.com.mx/public/storage/users/January2022/3hR0dNwIyWQiodmdxvLX.png"></td>                                
                            </tr>
                        @endfor
                    </tbody>
                    
                </table>
            </div>

            <div class="col-12">
                3. RESULTADOS 
            </div>
            <div class="divFlex">
                <table class="{{-- table --}} {{-- table-bordered border-dark --}} table-sm {{-- colorBorde --}}" cellpadding="0" cellspacing="0" width="100%" >
                    <tbody>
                        <tr>
                            <td style="width: 30%;">
                                <table id="tabPara" class="{{-- table --}} {{-- table-bordered border-dark --}} table-sm {{-- colorBorde --}}" cellpadding="0" cellspacing="0" width="100%"  border="1">
                                    <thead>
                                        <tr>
                                            <th>Parametro</th>
                                            <th>Resultado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < 9; $i++)
                                        <tr>    
                                            <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{@$paramResultado[$i]->Parametro}}</td>
                                            <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8" style="padding: 0.4%">
                                            @if (strpos(@$limitesC[$i], "< AUS") !== 0)
                                                {{@$limitesC[$i]}}
                                            @else
                                                AUSENTE
                                            @endif 
                                            </td>
                                        </tr>                             
                                                                                                                     
                                        </tr>
                                   
                                    @endfor     
                                    </tbody>
                                </table>
                            </td>
                            <td style="width: 30%;">
                                <table  id="tabPara" class="{{-- table --}} {{-- table-bordered border-dark --}} table-sm {{-- colorBorde --}}" cellpadding="0" cellspacing="0" width="100%" border="1">
                                    <thead>
                                        <tr>
                                            <th>Parametro</th>
                                            <th>Resultado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 10; $i < 19; $i++)
                                        <tr>    
                                            <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{@$paramResultado[$i]->Parametro}}</td>
                                            <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8" style="padding: 0.4%">
                                            @if (strpos(@$limitesC[$i], "< AUS") !== 0)
                                                {{@$limitesC[$i]}}
                                            @else
                                                AUSENTE
                                            @endif 
                                            </td>
                                        </tr>                             
                                                                                                                     
                                        </tr>
                             
                                    @endfor     
                                    </tbody>
                                </table>
                            </td>
                            <td style="width: 30%;">
                                <table  id="tabPara" class="{{-- table --}} {{-- table-bordered border-dark --}} table-sm {{-- colorBorde --}}" cellpadding="0" cellspacing="0" width="100%"  border="1">
                                    <thead>
                                        <tr>
                                            <th>Parametro</th>
                                            <th>Resultado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 19; $i < 30; $i++)
                                            <tr>    
                                                @if (@$paramResultado[$i]->Parametro == "")
                                                   
                                                @else
                                                    <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8">{{@$paramResultado[$i]->Parametro}}</td>
                                                    <td class="justifyCenter bordesTablaInfIzqDer fontCalibri negrita fontSize8" style="padding: 0.4%">
                                                    @if (strpos(@$limitesC[$i], "< AUS") !== 0)
                                                        {{@$limitesC[$i]}}
                                                    @else 
                                                        AUSENTE
                                                    @endif 
                                                    </td>
                                                @endif
                                                </td>                                                                    
                                            </tr>
                                            
                                        @endfor     
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

  

            <br>

            <div class="col-12 negrita">
                <div>                                    
                    <div>
                        <table class="table-sm" width="100%">
                            <tr>
                                <td class="fontCalibri anchoColumna111 fontSize8">GRASAS Y ACEITES (G Y A) mg/L</td>
                                <td class="fontCalibri anchoColumna111 fontSize8">{{@$promedioPonderadoGA}}</td>
                                <td class="fontCalibri anchoColumna111 fontSize8">COLIFORMES FECALES NMP/100mL</td>
                                <td class="fontCalibri anchoColumna111 fontSize8">{{@$mAritmeticaColi}}</td>
                                <td class="fontCalibri anchoColumna111 fontSize8">GASTO L/s</td>
                                <td class="fontCalibri anchoColumna111 fontSize8">{{round(@$gastoPromFinal, 2)}}</td> 
                                <td class="fontCalibri anchoColumna111 justifyCenter"><span class="fontSize7 negrita">FIRMA RESPONSABLE</span> <br> <span class="fontSize8">Q.F.B. RODRÍGUEZ BLANCO AGUEDA</span> &nbsp;&nbsp; </td>
                                <td class="justifyCenter anchoColumna111"><img style="width: auto; height: auto; max-width: 60px; max-height: 40px;" src="https://sistemaacama.com.mx/public/storage/users/January2022/3hR0dNwIyWQiodmdxvLX.png"></td>
                                
                                @php
                                    /*$bar_code = "data:image/png;base64," . \DNS1D::getBarcodePNG($model->Folio_servicio, "C39");*/
                                    /*$url = url()->current();*/
                                    $url = "https://sistemaacama.com.mx/clientes/exportPdfCustodiaInterna/".@$model->Id_solicitud;
                                    $qr_code = "data:image/png;base64," . \DNS2D::getBarcodePNG((string) $url, "QRCODE");
                                @endphp

                                <td class="justifyCenter anchoColumna111"><img style="width: 8%; height: 8%;" src="{{$qr_code}}" alt="barcode" /> <br> {{@$model->Folio_servicio}}</td>
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
                        <td class="justifyRight">Rev. 9</td>
                        <td class="justifyRight">Fecha ultima revisión: 01/04/2016</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>    
</body>
</html>

