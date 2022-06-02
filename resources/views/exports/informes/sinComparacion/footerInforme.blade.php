<footer>
<div id="contenedorTabla">
    <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
        <tbody>            
                <tr>
                    <td class="nombreHeader nom fontSize11 justificadorIzq" height="57">{{-- {{@$solicitud->Observacion}} --}} OBSERVACIONES: {{@$solicitud->Observacion}}   <!-- TEMPERATURA AMBIENTE PROMEDIO DE 25.97°C, LA MUESTRA PRESENTA OLOR Y COLOR TURBIO <br>
                        EL MUESTREO FUE REALIZADO DE ACUERDO A LO ESTABLECIDO EN LA NMX-AA-003-1980 Y DE ACUERDO A PROCEDIMIENTO PE-10-002-04 <br>
                        CONDICIONES AMBIENTALES: DÍA SOLEADO, EQUIPO UTILIZADO INVLAB583, EN LAS TOMAS 4,5 Y 6 NO HAY FLUJO POR LO CUAL NO SE TOMAN MUESTRAS. -->
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
                                        *** LA DETERMINACION DE LA TEMPERATURA DE LA MUESTRA COMPUESTA ES DE {{@$tempCompuesta->Temp_muestraComp}} Y LA INCERTIDUMBRE DE PH ES DE +/- 0.02 <br>
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