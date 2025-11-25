<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('/public/css/informes/conComparacion/conComparacionOr.css') }}">
    <title>Informe Con Comparación</title>
</head>

<body>
<p id='header1'>
    INFORME DE RESULTADOS AGUA RESIDUAL <br> MUESTRA 
    @if (@$solicitud->Id_muestra == 'COMPUESTA')
        COMPUESTA
    @else
        INSTANTANEA
    @endif
</p>

<div id="contenedorTabla">
    <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
        <tbody>            
                <tr>
                    <td class="filasIzq bordesTabla anchoColumna7 bordeDer paddingTopBot">Empresa:</td>
                    <td class="filasIzq bordesTabla fontBold anchoColumna82 bordeIzq">{{@$cliente->Nombres}}</td>
                    <td class="filasIzq bordesTabla fontBold anchoColumna11 bordeFinal justificadoDer">{{@$cliente->RFC}}</td>                    
                </tr>

                <tr>                    
                    <td class="filasIzq bordesTabla anchoColumna7 bordeDerSinSup paddingTopBot">Dirección:</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqSinSup" colspan="2">{{@$direccion->Direccion}}</td>                    
                </tr>
                
                <tr>
                    <td class="filasIzq bordesTabla anchoColumna7 bordeDerSinSup" rowspan="6">Punto de muestreo:</td>
                    <td class="filasIzq bordesTabla fontBold anchoColumna60 bordeIzqDerSinSup" rowspan="6">{{@$puntoMuestreo[0]->Punto}}</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">Fecha de Muestreo:  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="fontBold"> {{ \Carbon\Carbon::parse(@$solicitud->Fecha_muestreo)->format('d/m/Y')}}</span>
                    </td>                    
                </tr>
                    
                <tr>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">Hora de muestreo: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="fontBold">{{@$horaMuestreo}}</span>
                    </td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">Fecha de Emisión: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="fontBold">{{ \Carbon\Carbon::parse(@$modelProcesoAnalisis->Hora_entrada)->addDays(7)->format('d/m/Y')}}</span>
                    </td>
                </tr>                                      

                <tr>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">Fecha de Recepción: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="fontBold">{{\Carbon\Carbon::parse(@$modelProcesoAnalisis->Hora_entrada)->format('d/m/Y')}}</span>
                    </td>                    
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">N° de Muestra: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;
                        <span class="fontBold">{{@$solicitud->Folio_servicio}}</span>
                    </td>                    
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">N° de Orden: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="fontBold">{{@$numOrden->Folio_servicio}}</span>
                    </td>                    
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla anchoColumna11 bordeDer">Periodo de análisis:</td>
                    <td class="filasIzq bordesTabla bordeSinIzqFinalSup anchoColumna28 fontBold" colspan="3">DE {{\Carbon\Carbon::parse(@$modelProcesoAnalisis->Hora_entrada)->format('d/m/Y')}} 
                        A {{ \Carbon\Carbon::parse(@$fechaEmision)->format('d/m/Y')}}
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="fontBold">TITULO DE CONCESIÓN: {{@$puntoMuestreo[0]->Titulo_consecion}}</span>
                    </td>                    
                </tr>
        </tbody>         
    </table>  
</div>

<div id="contenedorTabla">
    <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
        <tbody>            
                <tr>
                    <td class="nombreHeader fontBold nom fontSize11 justificadorCentr">
                        @switch(@$solicitud->Id_norma)
                            @case(1)
                                DE ACUERDO A NOM-001-SEMARNAT-1996 
                                @if (@$solicitud->Id_muestra == 'COMPUESTA')
                                    COMPUESTA
                                @else
                                    INSTANTANEA
                                @endif
                                 TIPO "C", RÍOS - PROTECCIÓN DE LA VIDA ACUÁTICA - <br> QUE ESTABLECE LOS LIMITES MAXIMOS PERMISIBLES DE CONTAMINANTES EN LAS DESCARGAS DE AGUAS RESIDUALES EN AGUAS Y <br> BIENES NACIONALES.
                                @break
                            @case(2)
                                DE ACUERDO A NOM-002-SEMARNAT-1996 PARA MUESTRA 
                                @if (@$solicitud->Id_muestra == 'COMPUESTA')
                                    COMPUESTA
                                @else
                                    INSTANTANEA
                                @endif                                
                                 <br> QUE ESTABLECE LOS LIMITES MAXIMOS PERMISIBLES DE CONTAMINANTES EN LAS DESCARGAS DE AGUAS RESIDUALES A LOS <br> SISTEMAS DE ALCANTARILLADO URBANO O MUNICIPAL.
                                @break                            
                            @case(4)                                
                                DE ACUERDO A NOM-003-SEMARNAT-1997 PARA MUESTRA 
                                @if (@$solicitud->Id_muestra == 'COMPUESTA')
                                    COMPUESTA
                                @else
                                    INSTANTANEA
                                @endif                                        
                                <br> QUE ESTABLECE LOS LIMITES MAXIMOS PERMISIBLES <br> DE CONTAMINANTES PARA LAS AGUAS RESIDUALES <br> TRATADAS QUE SE REUSEN EN SERVICIOS AL PÚBLICO                                
                                @break
                            @case(5)
                                DE ACUERDO A MODIFICACIÓN A LA NORMA OFICIAL MEXICANA NOM-127-SSA1-1994, PARA MUESTRA 
                                @if (@$solicitud->Id_muestra == 'COMPUESTA')
                                    COMPUESTA <br>
                                @else
                                    INSTANTANEA <br>
                                @endif
                                SALUD AMBIENTAL. AGUA PARA USO Y CONSUMO HUMANO. LÍMITES PERMISIBLES DE CALIDAD Y TRATAMIENTOS A QUE DEBE <br> SOMETERSE EL AGUA PARA SU POTABILIZACION.
                                @break
                            @case(7)
                                DE ACUERDO A NORMA OFICIAL MEXICANA NOM-201-SSA1-2015 PARA MUESTRA 
                                @if (@$solicitud->Id_muestra == 'COMPUESTA')
                                    COMPUESTA <br>
                                @else
                                    INSTANTANEA <br>
                                @endif
                                PRODUCTOS Y SERVICIOS. AGUA Y HIELO PARA CONSUMO HUMANO, ENVASADOS A GRANEL. ESPECIFICACIONES SANITARIAS.
                                @break
                        @endswitch
                    </td>
                </tr>                
        </tbody>         
    </table>  
</div>

    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0"
            border-color="#000000" width="100%">
            <thead>
                <tr>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" height="30">PARAMETRO &nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr">&nbsp;METODO DE PRUEBA&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr">&nbsp;UNIDAD&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr">&nbsp;CONCENTRACION
                        CUANTIFICADA&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr">&nbsp;CONCENTRACION PERMISIBLE&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr">ANALISTA</td>
                </tr>
            </thead>

            <tbody>
                @for ($i = 0; $i < @$solicitudParametrosLength; $i++)
                    <tr>
                        <td class="tableContent bordesTablaBody" height="25">{{ @$solicitudParametros[$i]->Parametro }}<sup>{{$sParam[$i]}}</sup></td>
                        <td class="tableContent bordesTablaBody" width="16.6%">{{ @$solicitudParametros[$i]->Clave_metodo }}</td>
                        <td class="tableContent bordesTablaBody" width="10.6%">{{ @$solicitudParametros[$i]->Unidad }}</td>
                        <td class="tableContent bordesTablaBody">
                            @if (strpos(@$solicitudParametros[$i]->Unidad, 'AUS') !== 0)
                                @if (@$solicitudParametros[$i]->Parametro == 'Grasas y Aceites ++')
                                    @php
                                        echo round(@$sumaCaudalesFinal, 3);
                                    @endphp
                                @elseif (@$solicitudParametros[$i]->Parametro == 'Coliformes Fecales +')
                                    @php
                                        echo round(@$resColi, 3);
                                    @endphp
                                @else
                                    {{ @$limitesC[$i] }}
                                @endif
                            @else
                                AUSENTE
                            @endif
                        </td>
                        <td class="tableContent bordesTablaBody">
                            @if (strpos(@$solicitudParametros[$i]->Unidad, 'AUS') !== 0)
                                VALOR
                            @else
                                AUSENTE
                            @endif
                        </td>
                        <td class="tableContent bordesTablaBody" width="20.6%">
                            {{@$solicitudParametros[$i]->name}}
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
    <footer>
        <div id="contenedorTabla">
            <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
                <tbody>            
                        <tr>
                            <td class="nombreHeader nom fontSize11 justificadorIzq" height="57">
                                OBSERVACIONES: TEMPERATURA AMBIENTE PROMEDIO DE 25.97°C, LA MUESTRA PRESENTA OLOR Y COLOR TURBIO
                                EL MUESTREO FUE REALIZADO DE ACUERDO A LO ESTABLECIDO EN LA NMX-AA-003-1980 / NMX-AA-014-1980 Y DE ACUERDO A PROCEDIMIENTO PE-10-002-04 <br>
                                {{@$obsCampo}}
                            </td>
                        </tr>                
                </tbody>         
            </table>  
        </div>
        
            <div autosize="1" class="contenedorPadre12 paddingTop" cellpadding="0" cellspacing="0" border-color="#000000">
                <div class="contenedorHijo12 bordesTablaFirmasSupIzq">
                    <span><img style="width: auto; height: auto; max-width: 90px; max-height: 70px;" src="https://sistemaacama.com.mx/public/storage/users/January2022/3hR0dNwIyWQiodmdxvLX.png"> <br></span>            
                </div>
        
                <div class="contenedorHijo12 bordesTablaFirmasSupDer">            
                    <span><img style="width: auto; height: auto; max-width: 90px; max-height: 70px;" src="https://sistemaacama.com.mx/public/storage/users/January2022/3hR0dNwIyWQiodmdxvLX.png"> <br></span>            
                </div>  
        
                <div class="contenedorHijo12 bordesTablaFirmasInfIzq">            
                    <span class="cabeceraStdMuestra"> REVISÓ SIGNATARIO <br> </span>            
                    <span class="bodyStdMuestra"> BIOL. GUADALUPE GARCÍA PÉREZ {{-- {{@$usuario->name}} --}} </span>
                </div>         
                
                <div class="contenedorHijo12 bordesTablaFirmasInfDer">            
                    <span class="cabeceraStdMuestra"> AUTORIZÓ SIGNATARIO <br> </span>
                    <span class="bodyStdMuestra"> TSU. MARÍA IRENE REYES MORALES {{-- {{@$usuario->name}} --}} </span>
                </div>
            </div>
        
            <div id="contenedorTabla">
                <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
                    <tbody>            
                            <tr>
                                <td class="nombreHeaders fontBold fontSize9 justificadorIzq">NOTA: INTERPRETAR EL PUNTO (.) COMO SIGNO DECIMAL SEGÚN NORMA NOM-008-SCFI-2002 <br>
                                    LOS VALORES CON EL SIGNO MENOR (<) CORRESPONDEN AL VALOR MÍNIMO CUANTIFICADO POR EL MÉTODO. <br>
                                    ESTE REPORTE NO DEBE REPRODUCIRSE SIN LA APROBACIÓN DEL LABORATORIO EMISOR. <br>
                                    N.A INTERPRETAR COMO NO APLICA. <br>
                                    N.N INTERPRETAR COMO NO NORMADO. <br>
                                    NOTA 2: LOS DATOS EXPRESADOS AVALAN ÚNICAMENTE LOS RESULTADOS DE LA MUESTRA ANALIZADA. <br> <br>
                                    @for ($i = 0; $i < sizeof(@$simbologiaParam); $i++)                                                            
                                        @switch(@$simbologiaParam[$i])
                                            @case(9)
                                                * EL NT SE OBTIENE DE LA SUMA DE N-ORG,N-NH3,N-N-NO3,N-NO2 DE ACUERDO A SUS RESPECTIVAS NORMAS; NMX-AA-026-SCFI-2010, NMX-AA-079-SCFI-2001, STD-NMX-AA-099-SCFI-2021 <br>
                                                @break
                                            @case(10)
                                                ** MALLA DE 3 mm, DE CLARO LIBRE. <br>
                                                @break
                                            @case(11)
                                                *** LA DETERMINACION DE LA TEMPERATURA DE LA MUESTRA COMPUESTA ES DE {{@$temperaturaC->Temp_muestraComp}} Y LA INCERTIDUMBRE DE PH ES DE +/- 0.02 <br>
                                                @break
                                            @case(13)
                                                + MEDIA GEOMETRICA DE 6 MUESTRAS SIMPLES DE COLIFORMES. EL VALOR MINIMO CUANTIFICADO REPORTADO SERÁ DE 3, COMO CRITERIO CALCULADO PARA COLIFORMES EN SIRALAB Y EL LABORATORIO. <br>
                                                @break
                                            @case(14)
                                                ++ PROMEDIO PONDERADO DE 6 MUESTRAS SIMPLES DE GRASAS Y ACEITES. <br>
                                                @break
                                            @case(2)
                                                1 REG. ACREDIT. ENTIDAD MEXICANA DE ACREDITACIÓN ema No. AG-057-025/12, CONTINUARÁ VIGENTE. <br>
                                                1 APROBACIÓN C.N.A. No CNA-GCA-2316, VIGENCIA A PARTIR DEL 18 DE NOVIEMBRE DE 2021 HASTA 18 DE NOVIEMBRE DEL 2023 <br>
                                                @break
                                            @case(3)
                                                1A ACREDITAMIENTO EN ALIMENTOS: REG. ACREDIT. ENTIDAD MEXICANA DE ACREDITACIÓN EMA NO. A-0530-047/14, CONTINUARÁ VIGENTE.
                                                @break
                                        @endswitch
                                    @endfor
                                </td>
                            </tr>                
                    </tbody>         
                </table>  
            </div>    
        
            <div id="contenedorTabla">
                <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
                    <thead>
                        <tr>                    
                            <td>
                                @php
                                    /* $url = url()->current(); */
                                    $url = "https://sistemaacama.com.mx/clientes/exportPdfSinComparacion/".@$solicitud->Id_solicitud;
                                    $qr_code = "data:image/png;base64," . \DNS2D::getBarcodePNG((string) $url, "QRCODE");
                                @endphp
                                                                
                                <img style="width: 8%; height: 8%;" src="{{@$qr_code}}" alt="qrcode" /> <br> <span class="fontSize9 fontBold">&nbsp;&nbsp;&nbsp; {{@$solicitud->Folio_servicio}}</span>
                            </td>                                                                        
                        </tr>
        
                        <tr>
                            <td style="text-align: right;"><span class="revisiones">FO-13-001</span> <br> <span class="revisiones">Revisión 5</span></td>
                        </tr>
                    </thead>                        
                </table>  
            </div> 
            
            <br>
        </footer>
    
</body>

</html>
