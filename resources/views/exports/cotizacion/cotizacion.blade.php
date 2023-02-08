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
                    <td class="fontNormal fontCalibri fontSize10 justificadorIzq">(1) Prueba Acreditada y Aprobada Agua</td>
                    <td class="fontNormal fontCalibri fontSize10 justificadorIzq">(4) Prueba No Acreditada</td>
                </tr>

                <tr>                    
                    <td>&nbsp;</td>
                    <td class="fontNormal fontCalibri fontSize10 justificadorIzq">(1A) Prueba Acreditada Alimentos</td>
                    <td>&nbsp;</td>
                </tr>

                <tr>                    
                    <td>&nbsp;</td>
                    <td class="fontNormal fontCalibri fontSize10 justificadorIzq">(1S) Prueba Acreditada y Aprobada Agua Sucursal</td>
                    <td>&nbsp;</td>
                </tr>

                <tr>                    
                    <td>&nbsp;</td>
                    <td class="fontNormal fontCalibri fontSize10 justificadorIzq">(2) Prueba contratada a un laboratorio Acreditado</td>
                    <td>&nbsp;</td>
                </tr>

                <tr>                    
                    <td>&nbsp;</td>
                    <td class="fontNormal fontCalibri fontSize10 justificadorIzq">(3) Prueba contratada a un laboratorio No Acreditado</td>
                    <td>&nbsp;</td>
                </tr>
           </table>
        </div>

        <div class="col-md-12">
            <p class="fontBold fontCalibri fontSize13">CONDICIONES DE VENTA:</p>
        </div>

        <div class="col-md-12 fontNormal fontCalibri fontSize9">
            <p>Tiempo de entrega es de 10 días después del ingreso de la muestra al laboratorio.<br>El pago es del 50 de anticipo y 50% contra entrega de resultados. O dependiendo de la orden de compra (máximo 30 días).
            <br>"Vigencia de la cotización de 30 días".
            <br>En caso de solicitar correcciones en datos de reporte por error del cliente o solicitar otra copia original el costo será de $150.00+iva.</p><p>Los análisis están respaldados por nuestro Laboratorio Acreditado ante la ema con No. AG-057-025/12 continuará vigente y la aprobación No. CNA-GCA-2336, Vigencia a partir del 15 de diciembre de 2021
            hasta 18 de Noviembre del 2023</p><p>Los análisis están respaldados por nuestro Laboratorio Acreditado Sucursal ante la ema con No. AG-057-025/12-S1 continuará vigente y la aprobación No. CNA-GCA-2393, Vigencia a partir del 03 de febrero
            de 2021 hasta 29 de octubre del 2022 <br> Los análisis en Alimentos están respaldados por nuestro laboratorio acreditado ante la ema con A-0530-047/14 continuará vigente. <br> Por favor confirmar los Datos de Dirección Domiciliaria, Dirección Fiscal, Nombre de la Empresa, etc, ya que la información que ustedes nos proporciones será referida en los Reportes que se emitan una vez
            concluido el servicio solicitado y autorizado. <br> Los servicios serán aceptados después de verificar la capacidad del Laboratorio (equipo y personal) y seran programados de acuerdo a la disponibilidad del laboratorio, si existiera un cambio a la fecha
            programada, será responsabilidad del cliente informarnos para reprogramar el servicio. Se aceptarán cambios con un mínimo a 24 hrs antes de realizar el muestreo. <br> En caso de que no se puedan tomar muestras, por que no estén funcionando en condiciones normales de operación al momento de realizar el muestreo y por cuestiones ajenas a LABACAMA no sean
            adjudicables, se cobrara nuevamente el importe de los viáticos estipulados en esta cotización. <br> En el caso de que sean subcontratados los servicios de muestreo y/o analisis, el laboratorio ACAMA, sera el responsable de los trabajos realizados por el laboratorio Contratado y el tiempo de entrega de los
            resultados serán 20 días hábiles después del ingreso de la muestra al laboratorio. <br> DATOS BANCARIOS PARA EL PAGO: BANCO: BANAMEX, SUCURSAL 817, CUENTA: 0817 4767 514; CLABE 0026 500 817 4767 5149, RAZON SOCIAL: LABORATORIO DE ANALISIS DE CALIDAD
            DEL AGUA Y MEDIO AMBIENTE S.A. DE C.V. Poner en Referencia el No. de cotización y/o Factura para identificar el pago correspondiente. <br> Si el cliente requiere servicios de la norma NOM-001-SEMARNAT-1996 por requerimiento de la CONAGUA, será obligado que el cliente nos proporcione copia del Titulo del Concesión y los resultados
            obligadamente serán capturados y enviados al SIRALAB por parte del laboratorio. <br> De acuerdo a lo establecido en la norma ISO/IEC 17025 (Vigente) 7.1.3 se realizará la declaración de conformidad por medio de una regla de decisión documentada por el laboratorio en los siguientes casos: <br> Cuando la concentración cuantificada se encuentre muy cerca de los límites permisibles de acuerdo a la norma que no permita confirmar el cumplimiento o incumplimiento a un nivel de confianza aceptable,
            contemplando la incertidumbre obtenida por el método. <br> Cuando el cliente solicite una declaración de conformidad con una especificación o norma, este caso se debe definir con el cliente claramente la especificación, norma y la regla de decisión a utilizar y se
            realizará tomando en cuenta la incertidumbre, con respecto a la especificación de la Norma y se ocupará para reportar si excede o no excede. La Declaración de Conformidad, únicamente es informativa para
            el cliente y no es válida para ninguna obligación fiscal en materia de aguas nacionales, ni pago de derecho o sanción. <br> Es RESPONSABILIDAD del cliente adecuar el punto de muestreo, el personal de muestreo del Laboratorio ACAMA sólo hará el muestreo cotizado. El muestreador NO rasca, NO rompe concreto, NO abre
            registros, NO corta maleza, etc. <br> El Laboratorio de Análisis de Calidad del Agua y Medio Ambiente, S.A de C.V tiene implementado dentro de su Sistema de Gestión la Política de Imparcialidad y Confidencialidad de acuerdo a las
            especificaciones de la ISO/IEC:17025;2017; NMX-EC-17025-IMNC-2018, donde indica que NO se permite hacer ninguna acción que demerite nuestro trabajo por parte de cualquier CLIENTE hacia el personal
            del Laboratorio para obtener o generar un beneficio personal. <br> <span class="fontBold fontCalibri fontSize13">ESTIMADO CLIENTE:</span> <br> De acuerdo a su amable respuesta, le hago de su conocimiento que esta cotizacion hace las funciones de un contrato de servicios con el "Laboratorio de Analisis de Calidad del Agua y Medio Ambiente, S.A. 
            de C.V." Si esta de acuerdo, favor de firmar de conformidad y enviar por correo electrónico a labacama@prodigy.net.mx, junto con su Orden de servicio en caso de tenerla y/o en su defecto un acuse de
            aceptación por e-mail; de lo contrario no se podrán iniciar los servicios correspondientes solicitados. <br> El laboratorio no se hace responsable de capturar y enviar los resultados al Sistema de Recepción de análisis de Laboratorio (SIRALAB), si los servicios de muestreo y análisis no están pagados en su
            totalidad. <br> <b>CONDICIONES DE RECEPCIÓN DE MUESTRAS: <br> POR EL CLIENTE:</b> <br> En caso de que las muestras sean entregadas por el CLIENTE, el personal del laboratorio revisará que sean correctas en relación de análisis, se verificará la cantidad de recipientes entregados, la
            preservación, la cantidad de muestra, tiempo de análisis entre la toma, el tiempo de transporte al laboratorio, los datos de muestreo completos y el proceso en el laboratorio, en relación a la petición de análisis
            de cumplir con todos los requisitos antes mencionados la muestra será ACEPTADA y se hará la Recepción de Muestra en el Registro RE-11-011 “Cadena de recepción de muestras interna (muestras tomadas
            por el cliente)”. <br> Y en el caso de que NO CUMPLAN con los criterios de Recepción de muestras que puedan afectar la validez del resultado, se le informará al cliente de dicha situación, anotando en el área de observaciones,
            que “LA MUESTRA ES REMITIDA AL LABORATORIO POR EL CLIENTE Y LOS RESULTADOS SE APLICARÁN A LA MUESTRA COMO SE RECIBIÓ”, por dicha situación el Cliente firmara de conformidad
            y en el informe de prueba quedará asentado como: “MUESTRA REMITIDA AL LABORATORIO POR EL CLIENTE, LOS RESULTADOS SE APLICAN A LA MUESTRA COMO SE RECIBIÓ” o bien la muestra
            será RECHAZADA. <br> POR PERSONAL DEL LABORATORIO ACAMA: <br> Para la recepción de muestras por parte del personal de Ingeniería de Campo (MUESTREO) al laboratorio ACAMA ver el ANEXO No. 1</p>
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