<footer style="">    
    @php
        $temp = array();
        $sw = false;
        $url = "https://sistemaacama.com.mx/clientes/informe-de-resultados-acama-mensual/".@$folioEncript1."/".@$folioEncript2;
        $qr_code = "data:image/png;base64," . \DNS2D::getBarcodePNG((string) $url, "QRCODE");
    @endphp
    <img style="width: 8%; height: 8%;float: right;" src="{{@$qr_code}}" alt="qrcode" /> 
    {{-- <div style="float: right">
        <br> <span class="fontSize9 fontBold"> {{$solModel1->Folio_servicio}}</span>
        <br> <span class="fontSize9 fontBold"> {{$solModel2->Folio_servicio}}</span>
    </div> --}}
    <div autosize="1" class="" cellpadding="0" cellspacing="0" border-color="#000000">
        <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <tbody>            
                    <tr>
                        <td class="nombreHeaders fontBold fontSize5 justificadorIzq" colspan="2">

                            @php
                                echo $reportesInformes->Nota;

                            @endphp
                        </td>
                    </tr>
                    <tr></tr> 
   
            </tbody>         
        </table>                                                        
    </div>
    
    <div id="contenedorTabla">
   
        
        <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <tbody>            
                    @foreach ($model1 as $item)
                        @for ($i = 0; $i < sizeof($temp); $i++)
                            @if ($temp[$i] == $item->Id_simbologia_info)
                                @php $sw = true; @endphp
                            @endif
                        @endfor
                        @if ($sw != true)
                            @switch($item->Id_parametro)
                                @case(97)
                                    <tr>
                                        <td class="nombreHeaders fontBold fontSize5 justificadorIzq">{{$item->Simbologia_inf}} @php print  $item->Descripcion2; @endphp</td>
                                    </tr>
                                    <tr>
                                        <td class="nombreHeaders fontBold fontSize5 justificadorIzq">*** LA DETERMINACIÓN DE LA TEMPERATURA DE LA MUESTRA COMPUESTA ES DE {{round(@$campoCompuesto1->Temp_muestraComp)}}°C Y EL PH COMPUESTO ES DE {{ number_format(@$campoCompuesto1->Ph_muestraComp, 2, ".", ".")}} FOLIO {{$numOrden1->Folio_servicio}} </td>
                                    </tr>
                                    <tr>
                                        <td class="nombreHeaders fontBold fontSize5 justificadorIzq">*** LA DETERMINACIÓN DE LA TEMPERATURA DE LA MUESTRA COMPUESTA ES DE {{round(@$campoCompuesto2->Temp_muestraComp)}}°C Y EL PH COMPUESTO ES DE {{ number_format(@$campoCompuesto2->Ph_muestraComp, 2, ".", ".")}} FOLIO {{$numOrden2->Folio_servicio}} </td>
                                    </tr>
                                    @php
                                        array_push($temp,$item->Id_simbologia_info);
                                    @endphp
                                    @break
                                @default
                                    @if ($item->Id_simbologia_info != 9)
                                        <tr>
                                            <td class="nombreHeaders fontBold fontSize5 justificadorIzq">{{$item->Simbologia_inf}} @php print  $item->Descripcion2; @endphp</td>
                                        </tr>
                                        @php
                                            array_push($temp,$item->Id_simbologia_info);
                                        @endphp
                                    @endif
                            @endswitch
                           
                        @endif
                        @php
                            $sw = false;
                        @endphp
                    @endforeach
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>        
                        <td>
                            {{-- <span><img style="width: auto; height: auto; max-width: 100px; max-height: 80px;" src="{{asset('public/storage/'.$firma1->firma)}}"> <br></span> --}}
                            <!-- <span class="bodyStdMuestra fontSize5" style="font-size: 8px;"> BIOL. GUADALUPE GARCÍA PÉREZ{{-- {{@$usuario->name}} --}}</span> <br>
                            <center><span class="cabeceraStdMuestra fontNormal fontSize5" style="font-size: 8px;"> REVISÓ SIGNATARIO</span></center> -->
                            {{-- <span><img style="width: auto; height: auto; max-width: 100px; max-height: 80px;" src="{{asset('public/storage/'.$firma1->firma)}}"> <br></span> --}}
                            <!-- {{-- <span class="bodyStdMuestra fontSize5" style="font-size: 8px;">I.A. MARÍA LUISA ZAYAS RAMÍREZ{{@$usuario->name}}</span> <br> --}} -->
                            <span class="bodyStdMuestra fontSize5" style="font-size: 8px;">BIOL. GUADALUPE GARCÍA PÉREZ{{-- {{@$usuario->name}} --}}</span> <br>
                            <center><span class="cabeceraStdMuestra fontNormal fontSize5" style="font-size: 8px;"> REVISÓ SIGNATARIO</span></center>
                        </td>
                        <td>
                            {{-- <span><img style="width: auto; height: auto; max-width: 100px; max-height: 80px;" src="{{asset('public/storage/'.$firma2->firma)}}"> <br></span>           --}}
                            
                             <!-- <span class="bodyStdMuestra fontSize5" style="font-size: 8px;"> Q.F.B SANDRA ROJAS NAVARRO</span> <br>  -->
                            <span class="bodyStdMuestra fontSize5" style="font-size: 8px;"> I.A. MARÍA LUISA ZAYAS RAMÍREZ</span> <br>
                            <center><span class="cabeceraStdMuestra fontNormal fontSize5" style="font-size: 8px;"> AUTORIZÓ SIGNATARIO</span> </center>
                            <!-- <span class="bodyStdMuestra fontSize5" style="font-size: 8px;"> I.A. MARÍA LUISA ZAYAS RAMÍREZ</span> <br>
                            <center><span class="cabeceraStdMuestra fontNormal fontSize5" style="font-size: 8px;"> AUTORIZÓ SIGNATARIO</span> </center> -->
                        </td>
                    </tr>
                    </tr>
                    
            </tbody>         
           
        </table>  
 
    </div>    

 
    
<div id="contenedorTabla">
    <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
        <thead>
            <tr>                    
                                                                                 
            </tr>

            <tr>
                <td style="text-align: right;"><span class="revisiones" style="font-size: 8px">FO-13-001</span> <br> <span class="revisiones" style="font-size: 8px">Revisión 5</span></td>
            </tr>
        </thead>                        
    </table>  
</div> 
           
</footer>