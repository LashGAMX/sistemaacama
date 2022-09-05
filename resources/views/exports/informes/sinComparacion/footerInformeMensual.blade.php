<footer>    
    <div autosize="1" class="contenedorPadre12">
        <div autosize="1" class="contenedorSubPadre11" cellpadding="0" cellspacing="0" border-color="#000000">

            <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
                <tbody>            
                        <tr>
                            <td class="nombreHeader nom fontSize727 justificadorIzq">OBSERVACIONES: {{-- OBSERVACIONES: EL MUESTREO FUE REALIZADO DE ACUERDO A LO
                                ESTABLECIDO EN LA NMX-AA-003-1980 Y DE ACUERDO A PROCEDIMIENTO PE-10-002-04 <br>
                                DIA SOLEADO, EQUIPO UTILIZADO INVLAB 583 // NO HUBO DESCARGA EN LAS TOMAS 2, 5 Y 6 CONDICIONES AMBIENTALES: 
                                DÍA SOLEADO, EQUIPO UTILIZADO INVLAB583, EN LAS TOMAS 4, 5 Y 6 NO HAY FLUJO POR LO CUAL NO SE TOMAN MUESTRAS.     --}}                            
                                </td>
                        </tr>                
                </tbody>         
            </table> 

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
                            <td class="justificadorCentr">
                                @php
                                    /*$url = url()->current();*/
                                    $url = "https://sistemaacama.com.mx/clientes/informeMensualSinComparacion/".@$solModel->Id_solicitud;
                                    $qr_code = "data:image/png;base64," . \DNS2D::getBarcodePNG((string) $url, "QRCODE");
                                @endphp
                                                                
                                <br>
                                <img style="width: 11%; height: 11%;" src="{{@$qr_code}}" alt="qrcode" /> <br> <span class="fontSize9 fontBold"> {{@$solModel->Folio_servicio}}</span>
                            </td>                            
                        </tr>
                </tbody>         
            </table>                                                        
        </div>
            
        <div class="contenedorSubPadre12" cellpadding="0" cellspacing="0" border-color="#000000" style="text-align:right;">
            <div class="contenedorHijo12 bordesTablaFirmasInfDer justificadorCentr" style="margin-left: 130px;margin-right:0px;">  
                <br>                         
                <span><img style="width: auto; height: auto; max-width: 100px; max-height: 80px;" src="{{url('public/storage/'.$firma1->firma1)}}"> <br></span>
                <br>
                <span class="bodyStdMuestra fontSize5"> BIOL. GUADALUPE GARCÍA PÉREZ{{-- {{@$usuario->name}} --}}</span> <br>
                <span class="cabeceraStdMuestra fontNormal fontSize5"> REVISÓ SIGNATARIO</span>
            </div>

            <div class="contenedorHijo12 bordesTablaFirmasInfDer justificadorCentr">            
                <br>
                <span><img style="width: auto; height: auto; max-width: 100px; max-height: 80px;" src="{{url('public/storage/'.$firma2->firma2)}}"> <br></span>          
                <br>
                <span class="bodyStdMuestra fontSize5"> TSU. MARÍA IRENE REYES MORALES{{-- {{@$usuario->name}} --}} </span> <br>
                <span class="cabeceraStdMuestra fontNormal fontSize5"> AUTORIZÓ SIGNATARIO</span>      
            </div>                

            <br>

            <span class="revisiones">FO-13-001</span> <br> <span class="revisiones fontSize5">Revisión 5</span>
        </div>    
    </div>    
    <br> <br>
</footer>