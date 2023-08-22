<footer>    
    <div autosize="1" class="contenedorPadre12">
        <div autosize="1" class="contenedorSubPadre11" cellpadding="0" cellspacing="0" border-color="#000000">

            <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
                <tbody>            
                        <tr> 
    
                                <td class="nombreHeader nom fontSize727 justificadorIzq">
                                FOLIO {{$solModel1->Folio_servicio}}: OBSERVACIONES - TEMPERATURA AMBIENTE PROMEDIO DE {{round(@$tempProm1)}}°C, 
                                @php if(@$olor1 == true) {echo "LA MUESTRA PRESENTA OLOR Y COLOR ".@$color1;; } else{ echo "LA MUESTRA NO PRESENTA OLOR Y COLOR ".@$color1; }@endphp
                                EL MUESTREO FUE REALIZADO DE ACUERDO A LO ESTABLECIDO EN LA NMX-AA-003-1980 Y DE ACUERDO A PROCEDIMIENTO PE-10-002-04 <br>
                                {{@$obs1->Observaciones}}
                                <br>
                                FOLIO {{$solModel2->Folio_servicio}}: OBSERVACIONES - TEMPERATURA AMBIENTE PROMEDIO DE {{round(@$tempProm2)}}°C, 
                                @php if(@$olor2 == true) {echo "LA MUESTRA PRESENTA OLOR Y COLOR ".@$color1;; } else{ echo "LA MUESTRA NO PRESENTA OLOR Y COLOR ".@$color2; }@endphp
                                EL MUESTREO FUE REALIZADO DE ACUERDO A LO ESTABLECIDO EN LA NMX-AA-003-1980 Y DE ACUERDO A PROCEDIMIENTO PE-10-002-04 <br>
                                {{@$obs2->Observaciones}}
    
                            </td>
                        </tr>                
                </tbody>         
            </table>  
            @php
            $temp = array();
            $sw = false;
        @endphp
            <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
                <tbody>            
                        <tr>
                            <td class="nombreHeaders fontBold fontSize5 justificadorIzq" colspan="2">NOTA: INTERPRETAR EL PUNTO (.) COMO SIGNO DECIMAL SEGÚN NORMA NOM-008-SCFI-2002 <br>
                                LOS VALORES CON EL SIGNO MENOR (<) CORRESPONDEN AL VALOR MÍNIMO CUANTIFICADO POR EL MÉTODO. <br>
                                ESTE REPORTE NO DEBE REPRODUCIRSE SIN LA APROBACIÓN DEL LABORATORIO EMISOR. <br>
                                N.A INTERPRETAR COMO NO APLICA. <br>
                                N.N INTERPRETAR COMO NO NORMADO. <br>
                                NOTA 2: LOS DATOS EXPRESADOS AVALAN ÚNICAMENTE LOS RESULTADOS DE LA MUESTRA ANALIZADA. <br> <br>
                         
                            </td>
                        </tr>
                        <tr>
                            @forech
                        </tr>

                        <tr>
                            <td class="justificadorCentr">
                                @php
                                    /*$url = url()->current();*/
                                    $url = "https://sistemaacama.com.mx/clientes/informeMensualSinComparacion/".@$solModel->Id_solicitud;
                                    $qr_code = "data:image/png;base64," . \DNS2D::getBarcodePNG((string) $url, "QRCODE");
                                @endphp
                                                                
                                <br>
                            
                            </td>                            
                        </tr>
                </tbody>         
            </table>                                                        
        </div>
            
        <div class="contenedorSubPadre12" cellpadding="0" cellspacing="0" border-color="#000000" style="text-align:right;">
            <div class="contenedorHijo12 bordesTablaFirmasInfDer justificadorCentr" style="margin-left: 130px;margin-right:0px;">  
                <br>                         
               
                <br>
                <span class="bodyStdMuestra fontSize5"> BIOL. GUADALUPE GARCÍA PÉREZ{{-- {{@$usuario->name}} --}}</span> <br>
                <span class="cabeceraStdMuestra fontNormal fontSize5"> REVISÓ SIGNATARIO</span>
            </div>

            <div class="contenedorHijo12 bordesTablaFirmasInfDer justificadorCentr">            
                <br>
               
                <br>
                <span class="bodyStdMuestra fontSize5"> TSU. MARÍA IRENE REYES MORALES{{-- {{@$usuario->name}} --}} </span> <br>
                <span class="cabeceraStdMuestra fontNormal fontSize5"> AUTORIZÓ SIGNATARIO</span>      
            </div>     
            <br>           
            <img style="width: 8%; height: 8%;" src="{{@$qr_code}}" alt="qrcode" /> <br> <span class="fontSize9 fontBold"> {{@$solModel->Folio_servicio}}</span>
            <br>

            <span class="revisiones">FO-13-001</span> <br> <span class="revisiones fontSize5">Revisión 5</span>
            
            
           
        </div>    
    </div>    
    <br> <br>
</footer>