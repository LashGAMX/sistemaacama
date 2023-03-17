<footer> 
    
        <div autosize="1" class="contenedorPadre12 paddingTop" cellpadding="0" cellspacing="0" border-color="#000000">
            <div class="contenedorHijo12 bordesTablaFirmasSupIzq">
                <span><img style="width: auto; height: auto; max-width: 90px; max-height: 70px;" src="{{url('public/storage/'.$firma1->firma)}}"> <br></span>            
            </div>
    
            <div class="contenedorHijo12 bordesTablaFirmasSupDer">            
                <span><img style="width: auto; height: auto; max-width: 90px; max-height: 70px;" src="{{url('public/storage/'.$firma2->firma)}}"> <br></span>            
            </div>  
    
            <div class="contenedorHijo12 bordesTablaFirmasInfIzq">            
                <span class="cabeceraStdMuestra"> REVISÓ SIGNATARIO <br> </span>            
                <span class="bodyStdMuestra"> {{@$reportesInformes->Reviso}} {{-- {{@$usuario->name}} --}} </span>
            </div>         
            
            <div class="contenedorHijo12 bordesTablaFirmasInfDer">            
                <span class="cabeceraStdMuestra"> AUTORIZÓ SIGNATARIO <br> </span>
                <span class="bodyStdMuestra"> {{@$reportesInformes->Analizo}} {{-- {{@$usuario->name}} --}} </span>
            </div>
        </div>
    
        <div id="contenedorTabla">
            <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
                <tbody>            
                        <tr>
                            <td class="nombreHeaders fontBold fontSize9 justificadorIzq">{{$reportesInformes->Nota}}
    
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
                        <td style="text-align: right;"><span class="revisiones">{{$reportesInformes->Clave}}</span> <br> <span class="revisiones">Revisión {{$reportesInformes->Num_rev}}</span></td>
                    </tr>
                </thead>                        
            </table>  
        </div> 
        
        <br>
    </footer>