<footer> 
    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <tbody>            
                    <tr>
                        <td class="nombreHeader nom fontSize11 justificadorIzq" height="57">
                            OBSERVACIONES: TEMPERATURA AMBIENTE PROMEDIO DE {{@$tempAmbienteProm->Resultado2}}°C, @php if(@swOlor == true) {echo "LA MUESTRA PRESENTA OLOR Y COLOR " .@$color;} else{ echo "LA MUESTRA PRESENTA COLOR ".@$color; }@endphp
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
                <span class="bodyStdMuestra"> {{$reportesInformes->Analizo}} {{-- {{@$usuario->name}} --}} </span>
            </div>         
            
            <div class="contenedorHijo12 bordesTablaFirmasInfDer">            
                <span class="cabeceraStdMuestra"> AUTORIZÓ SIGNATARIO <br> </span>
                <span class="bodyStdMuestra"> {{$reportesInformes->Reviso}} {{-- {{@$usuario->name}} --}} </span>
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
            @php
                $temp = array();
                $sw = false;
            @endphp
            <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
                <tbody>            
                        @foreach ($model as $item)
                            @for ($i = 0; $i < sizeof($temp); $i++)
                                @if ($temp[$i] == $item->Id_simbologia_info)
                                    @php $sw = true; @endphp
                                @endif
                            @endfor
                            @if ($sw != true)
                                @switch($item->Id_parametro)
                                    @case(97)
                                        <tr>
                                            <td class="nombreHeaders fontBold fontSize9 justificadorIzq">{{$item->Simbologia_inf}} @php print  $item->Descripcion2; @endphp</td>
                                        </tr>
                                        <tr>
                                            <td class="nombreHeaders fontBold fontSize9 justificadorIzq">*** LA DETERMINACIÓN DE LA TEMPERATURA DE LA MUESTRA COMPUESTA ES DE {{@$campoCompuesto->Temp_muestraComp}}°C Y EL PH COMPUESTO ES DE {{@$campoCompuesto->Ph_muestraComp}}</td>
                                        </tr>
                                        @php
                                            array_push($temp,$item->Id_simbologia_info);
                                        @endphp
                                        @break
                                    @default
                                    <tr>
                                        <td class="nombreHeaders fontBold fontSize9 justificadorIzq">{{$item->Simbologia_inf}} @php print  $item->Descripcion2; @endphp</td>
                                    </tr>
                                    @php
                                        array_push($temp,$item->Id_simbologia_info);
                                    @endphp
                                @endswitch
                               
                            @endif
                            @php
                                $sw = false;
                            @endphp
                        @endforeach
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