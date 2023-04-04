<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- <link rel="stylesheet" href="'.asset('css/pdf/style.css').'"> --}}
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('/public/css/pdf/style.css')}}">

    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous"> --}}
    {{-- <link rel="stylesheet" href="'.asset('css/pdf/style.css').'" media="mpdf"> --}}

    <title>Cotizacion {{@$model->Folio}}</title> 
</head>
<body> 
    <div class="container" id="pag">
        <!-- <div class="row"> -->
            <!-- <div class="col align-self-end"> -->
                <p class="fontBold fontCalibri fontSize12" align="right">FOLIO COTIZACIÓN: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                {{@$model->Folio}}<p>
            <!-- </div> -->
        <!-- </div> -->
        
        <div class="row" style="display: block">
            <div class="col-12 fontBold fontCalibri fontSize12">
                {{@$model->Nombre}} <br>
                {{@$model->Direccion}}
            </div>        
        </div> <br>
        
        <div class="row">
            <div class="col-md-12 fontBold fontCalibri fontSize12">
                TELF: {{@$model->Telefono}}<br>
                email: {{@$model->Correo}}<br>
                AT'N: {{@$model->Atencion}}<br>
            </div>

            <div class="col-md-12 fontNormal fontCalibri fontSize12">
                <p class="fontNormal fontCalibri fontSize12" align="right">{{\Carbon\Carbon::parse(@$model->created_at)->format('d/m/Y')}}</p>
                
                <p>ME PERMITO SOMETER A SU AMABLE CONSIDERACIÓN LA SIGUIENTE COTIZACIÓN DEL SERVICIO DE MUESTREO Y ANÁLISIS DE AGUA DE ACUERDO A:</p>
                @php
                    echo $reportesInformes->Id_reporte;
                @endphp
            </div>
            <div class="col-md-12">
                <table class="table table-borderless" style="border:none" width="100%">
                    <tr>
                        <td class="fontNormal fontCalibri fontSize12">SERVICIO: </td>
                        <td class="fontBold fontCalibri fontSize14">{{@$model->Servicio}}</td>                        
                        <td class="fontNormal fontCalibri fontSize12">PUNTOS MUESTREO:</td>
                        <td class="fontBold fontCalibri fontSize14">{{@$puntos->count()}}</td>
                        <td class="fontNormal fontCalibri fontSize12">SERVICIOS:</td>
                        <td class="fontBold fontCalibri fontSize14">1</td>
                    </tr>
                </table>
                <table class="table table-borderless" style="border:none" width="100%">
                <tr>
                    <td class="fontNormal fontCalibri fontSize12">TIPO MUESTRA: </td>
                    <td class="fontBold fontCalibri fontSize14">{{@$model->Tipo}} ({{@$model->Tomas}})</td>
                    <td class="fontNormal fontCalibri fontSize12">NORMA:</td>
                    <td class="fontBold fontCalibri fontSize14">{{@$model->Clave_norma}}</td>
                </tr>
            </table>
            </div>

            <div class="col-md-12 fontBold fontCalibri fontSize12">
                {{-- <strong><p>QUE ESTABLECE LOS LIMITES MAXIMOS PERMISIBLES DE CONTAMINANTES EN LAS DESCARGAS DE AGUAS RESIDUALES A LOS SISTEMAS DE ALCANTARILLADO URBANO O MUNICIPAL.</p></strong> --}}
                <strong><p>{{@$model->Norma}}</p></strong>
            </div>
        </div>
        
        <table autosize="1" class="table table-bordered table-sm" style="width: 100%" cellpadding="2" cellspacing="0" border-color="#000000">
            <thead>
                <tr>
                    <th class="fontBold fontCalibri fontSize11 bordesTablaBody">PARAMETRO</th>
                    <th class="fontBold fontCalibri fontSize11 bordeFinal">METODO DE PRUEBA</th>
                    <th class="fontBold fontCalibri fontSize11 bordeFinal"><small>LIMITE DE CUANTIFICACIÓN DEL METODO</small></th>
                    <th class="fontBold fontCalibri fontSize11 bordeFinal">UNIDAD</th>
                </tr>
            </thead>
            <tbody>
                @foreach (@$parametros as $item)
                <tr>
                    <td class="fontNormal fontCalibri fontSize11 bordesTablaBody">{{$item->Parametro}} <sup>({{$item->Simbologia}})</sup></td>
                    <td class="fontNormal fontCalibri fontSize11 bordeFinal justificadorCentr">{{$item->Clave_metodo}}</td>
                    @if (is_numeric($item->Limite))
                    <td class="fontNormal fontCalibri fontSize11 bordeFinal justificadorCentr"> < {{$item->Limite}}</td>
                    @else
                    <td class="fontNormal fontCalibri fontSize11 bordeFinal justificadorCentr">{{$item->Limite}}</td>
                    @endif
                    <td class="fontNormal fontCalibri fontSize11 bordeFinal justificadorCentr">{{$item->Unidad}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <table class="table" style="font-size: 9px;" width="100%">
            <tr>
                <td class="fontBold fontCalibri fontSize10">CANTIDAD SERVICIOS: </td>
                <td class="fontBold fontCalibri fontSize10">{{$puntos->count()}}</td>
                <td class="fontBold fontCalibri fontSize10">COSTO PAQUETE</td>
                <td class="fontBold fontCalibri fontSize10">$                    
                    @php
                        echo number_format(@$model->Precio_analisis, 2, ".", ",");
                    @endphp
                </td>
                <!-- <td class="fontBold fontCalibri fontSize10">COSTO TOTAL SIN IVA</td>
                <td class="fontBold fontCalibri fontSize10">$                    
                    @php
                        echo number_format(@$model->Sub_total, 2, ".", ",");
                    @endphp
                </td> -->
            </tr>
        </table>

        @if (sizeof(@$parametrosExtra) > 0)
            <br><br><br>            
            
            <div class="col-md-12 fontNormal fontCalibri fontSize12">                
                <p class="bordeSup justificadorCentr" style="font-weight: bold">PARAMETROS ADICIONALES</p>
            </div>
            
            <table autosize="1" class="table table-bordered table-sm" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
                <thead>
                    <tr>
                        <th class="fontBold fontCalibri fontSize11 bordesTablaBody">PARAMETRO</th>
                        <th class="fontBold fontCalibri fontSize11 bordeFinal">METODO DE PRUEBA</th>
                        <th class="fontBold fontCalibri fontSize11 bordeFinal"><small>LIMITE DE CUANTIFICACIÓN DEL METODO</small></th>
                        <th class="fontBold fontCalibri fontSize11 bordeFinal">UNIDAD</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (@$parametrosExtra as $item)
                    <tr>
                        <td class="fontNormal fontCalibri fontSize11 bordesTablaBody">{{$item->Parametro}} <sup>({{$item->Simbologia}})</sup></td>
                        <td class="fontNormal fontCalibri fontSize11 bordeFinal justificadorCentr">{{$item->Clave_metodo}}</td>
                        <td class="fontNormal fontCalibri fontSize11 bordeFinal justificadorCentr">{{$item->Limite}}</td>
                        <td class="fontNormal fontCalibri fontSize11 bordeFinal justificadorCentr">{{$item->Unidad}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @if ($model->Precio_catalogo > 0)
            
        <table class="table" style="font-size: 9px;" width="100%">
            <tr>
                <td class="fontBold fontCalibri fontSize10">CANTIDAD SERVICIOS: </td>
                <td class="fontBold fontCalibri fontSize10">{{$puntos->count()}}</td>
                <td class="fontBold fontCalibri fontSize10">COSTO PARAMETROS ESPECIALES</td>
                <td class="fontBold fontCalibri fontSize10">$                    
                    {{@$model->Precio_catalogo}}
                </td>
                <!-- <td class="fontBold fontCalibri fontSize10">COSTO TOTAL SIN IVA</td>
                <td class="fontBold fontCalibri fontSize10">$                    
                    @php
                        echo number_format(@$model->Sub_total, 2, ".", ",");
                    @endphp
                </td> -->
            </tr>
        </table>        
        @endif


        <div class="col-md-12">
            <p class="fontBold fontCalibri fontSize9 bordeIzqDerSinSup justificadorCentr">Totales</p>
            <p class="fontBold fontCalibri fontSize9">OBSERVACIONES COTIZACIÓN</p>
            <p class="fontBold fontCalibri fontSize14">{{@$model->Observacion_cotizacion}}</p>
        </div><br>

        <div class="col-md-12">
           <table class="table" width="100%">
            <tr>
                <td class="fontBold fontCalibri fontSize15" width="35%">PRECIO ANALISIS</td>
                <td width="20%">&nbsp;</td>
                <td class="fontBold fontCalibri fontSize15 justificadoDer" width="35%">$                        
                    @php
                        echo number_format(@$model->Precio_analisis , 2, ".", ",");
                    @endphp
                </td>
            </tr>
            @if (@$model->Descuento > 0)
            <tr>
                <td class="fontBold fontCalibri fontSize15" width="35%">DESCUENTO DE ANALISIS</td>
                <td width="20%">&nbsp;</td>
                <td class="fontBold fontCalibri fontSize15 justificadoDer" width="35%">                      
                    {{@$model->Descuento}}%
                </td>
            </tr>
            <tr>
                <td class="fontBold fontCalibri fontSize15" width="35%">PRECIO ANALISIS CON DESCUENTO</td>
                <td width="20%">&nbsp;</td>
                <td class="fontBold fontCalibri fontSize15 justificadoDer" width="35%">                      
                    @php
                        echo number_format(@$model->Precio_analisisCon , 2, ".", ",");
                    @endphp
                </td>
            </tr>
            @endif    
            @if (@$model->Tipo_servicio != 3)
            <tr>
                <td class="fontBold fontCalibri fontSize15">PRECIO MUESTREO</td>
                <td width="20%">&nbsp;</td>
                <td class="fontBold fontCalibri fontSize15 justificadoDer bordeSup" width="35%">$                        
                    @php
                        echo number_format(@$model->Precio_muestreo , 2, ".", ",");
                    @endphp
                </td>
            </tr> 
            @endif        
            @if (@$model->Precio_catalogo > 0)
                <tr>
                    <td class="fontBold fontCalibri fontSize15" width="35%">PARAMETROS ADICIONALES</td>
                    <td width="20%">&nbsp;</td>
                    <td class="fontBold fontCalibri fontSize15 justificadoDer" width="35%">$                        
                        @php
                            echo number_format(@$model->Precio_catalogo, 2, ".", ",");
                        @endphp
                    </td>
                </tr>
            @endif
            
                <tr>
                    <td class="fontBold fontCalibri fontSize15" width="35%">SUBTOTAL</td>
                    <td width="20%">&nbsp;</td>
                    <td class="fontBold fontCalibri fontSize15 justificadoDer bordeSup" width="35%">$      
                        @php                     
                             echo number_format(@$model->Sub_total, 2, ".", ","); 
                        @endphp                  
                                                       
                    </td>
                </tr>

                <tr>
                    <td class="fontBold fontCalibri fontSize15">IVA</td>
                    <td>&nbsp;</td>
                    <td class="fontBold fontCalibri fontSize15 justificadoDer">
                        @php                     
                            echo number_format(((@$model->Sub_total * @$model->Iva )/100), 2, ".", ",");
                        @endphp
                    </td>
                </tr>

                <tr>
                    <td class="fontBold fontCalibri fontSize15 bordeIzqDerSinSup">TOTAL CON IVA</td>
                    <td class="bordeIzqDerSinSup">&nbsp;</td>
                    <td class="fontBold fontCalibri fontSize15 justificadoDer bordeIzqDerSinSup">$                        
                        @php
                            echo number_format(@$model->Costo_total, 2, ".", ","); 
                        @endphp
                    </td>
                </tr>

                <tr>
                    <td class="fontNormal fontCalibri fontSize10 justificadorCentr">Simbología:</td>
                    
                </tr>

                <tr>
                    <td class="fontNormal fontCalibri fontSize10 justificadorLeft">
                        @php
                            echo $reportesInformes->Simbologia;
                        @endphp
                    </td>
                </tr>
                

               
           </table>
        </div>

        <div class="col-md-12">
            <p class="fontBold fontCalibri fontSize13">CONDICIONES DE VENTA:</p>
        </div>

        <div class="col-md-12 fontNormal fontCalibri fontSize9">
           @php
               echo $reportesInformes->Texto;
           @endphp
        </div>

        <div class="col-md-12 fontNormal fontCalibri fontSize12 justificadorCentr">
            He leído la presente cotización y acepto los términos indicados en ella
        </div>

        <br>

        <div class="col-md-12">
           <table class="table" width="100%">
                <tr>
                    <td width="20%">&nbsp;</td>
                    <td width="60%">
                        &nbsp;
                    </td>
                    <td width="20%">&nbsp;</td>
                </tr>

                <tr>
                    <td>&nbsp;</td>
                    <td class="fontNormal fontCalibri fontSize12 justificadorCentr bordeSup">Firma de aceptación del Cliente</td>
                    <td>&nbsp;</td>
                </tr>                
           </table>
        </div>

        <br>

        <div class="col-md-12 fontNormal fontCalibri fontSize12 justificadorCentr">
            En espera de poder servirles, de antemano agradecemos su preferencia. Reciban un cordial saludo.
        </div>

        <br>

        <div class="col-md-12">
           <table class="table" width="100%">
                <tr>
                    <td width="20%">&nbsp;</td>
                    <td class="fontNormal fontCalibri fontSize12 justificadorCentr" width="60%">
                        Atentamente
                    </td>
                    <td width="20%">&nbsp;</td>
                </tr>

                <tr>
                    <td>&nbsp;</td>
                    <td class="justificadorCentr"><img style="width: auto; height: auto; max-width: 100px; max-height: 80px;" src="{{asset('public/storage/'.$firma->firma)}}"></td>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td>&nbsp;</td>
                    <td class="fontNormal fontCalibri fontSize12 justificadorCentr bordeSup">Ing. Maribel Campos Reyes</td>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td>&nbsp;</td>
                    <td class="fontNormal fontCalibri fontSize12 justificadorCentr">Responsable de Cotización</td>
                    <td>&nbsp;</td>
                </tr>
           </table>
        </div>
    </div>
</body>
</html> 