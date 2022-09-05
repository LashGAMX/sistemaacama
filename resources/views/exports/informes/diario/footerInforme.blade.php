<footer>
    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <tbody>            
                    <tr>
                        <td class="nombreHeader nom fontSize11 justificadorIzq" height="57">
                            OBSERVACIONES: TEMPERATURA AMBIENTE PROMEDIO DE {{@$campoGeneral->Temperatura_a}}°C, @php if(@swPh == true) {echo "LA MUESTRA PRESENTA OLOR Y COLOR TURBIO";} @endphp 
                            EL MUESTREO FUE REALIZADO DE ACUERDO A LO ESTABLECIDO EN LA NMX-AA-003-1980 Y DE ACUERDO A PROCEDIMIENTO PE-10-002-04 <br>
                            {{@$obsCampo}}
                        </td>
                    </tr>                
            </tbody>         
        </table>  
    </div>
    
        <div autosize="1" class="contenedorPadre12 paddingTop" cellpadding="0" cellspacing="0" border-color="#000000">
            <div class="contenedorHijo12 bordesTablaFirmasSupIzq">
                <span><img style="width: auto; height: auto; max-width: 90px; max-height: 70px;" src="{{url('public/storage/'.$firma1->firma)}}"> <br></span>            
            </div>
    
            <div class="contenedorHijo12 bordesTablaFirmasSupDer">            
                <span><img style="width: auto; height: auto; max-width: 90px; max-height: 70px;" src="{{url('public/storage/'.$firma2->firma)}}"> <br></span>            
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