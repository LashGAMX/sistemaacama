<footer style="">    
    @php
        $temp = array();
        $sw = false;
        $url = "http://sistemasofia.ddns.net:86/sofia/clientes/informe-de-resultados-acama-mensual/".@$folioEncript1."/".@$folioEncript2;
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
                        <td style="border: 1px solid black; padding: 20px;">
                            <span class="bodyStdMuestra fontSize5" style="font-size: 8px;">
                                @if (!empty($firmaEncript1))
                                    @php
                                        for ($i = 0; $i < strlen($firmaEncript1); $i++) {
                                            //echo $firmaEncript1[$i] . "\n";
                                        }
                                    @endphp
                                @else
                                    &nbsp; <!-- Espacio en blanco -->
                                @endif
                            </span>
                        </td>
                        
                        <td style="border: 1px solid black; padding: 20px;">
                            <span class="bodyStdMuestra fontSize5" style="font-size: 8px;">
                                @if (!empty($firmaEncript2))
                                    @php
                                        for ($i = 0; $i < strlen($firmaEncript2); $i++) {
                                            //echo $firmaEncript2[$i] . "\n";
                                        }
                                    @endphp
                                @else
                                    &nbsp; <!-- Espacio en blanco -->
                                @endif
                            </span>
                        </td>
                        
                    </tr>

                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>        
                        <td>
                            
                            <span class="bodyStdMuestra fontSize5" style="font-size: 8px;">GUADALUPE GARCÍA PÉREZ</span> <br> 
                            <!-- <span class="bodyStdMuestra fontSize5" style="font-size: 8px;">MARIANA RAMÍREZ PICAZO</span> <br>  -->
                            <!--<span class="bodyStdMuestra fontSize5" style="font-size: 10px;"> BIOL. ELSA RIVERA RIVERA</span> <br>-->
                            <!-- <span class="bodyStdMuestra fontSize5" style="font-size: 8px;"> I.A. MARÍA LUISA ZAYAS RAMÍREZ</span> <br> -->
                            <!-- <span class="bodyStdMuestra fontSize5" style="font-size: 8px;"> Q.F.B SANDRA ROJAS NAVARRO</span> <br>   -->
                            <!-- <span class="bodyStdMuestra fontSize5" style="font-size: 8px;"> Q.F.B SANDRA ROJAS NAVARRO</span> <br> -->
                            <!-- <span class="bodyStdMuestra fontSize5" style="font-size: 8px;"> Q.F.B SANDRA ROJAS NAVARRO</span> <br>  -->
                            <center><span class="cabeceraStdMuestra fontNormal fontSize5" style="font-size: 8px;"> REVISÓ SIGNATARIO</span></center>
                        </td>
                       
                       
                        <td>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <!-- <span class="bodyStdMuestra fontSize5" style="font-size: 8px;">GUADALUPE GARCÍA PÉREZ</span> <br>  -->
                            <!-- <span class="bodyStdMuestra fontSize5" style="font-size: 8px;">MARIANA RAMÍREZ PICAZO</span> <br>  -->
                            <span class="bodyStdMuestra fontSize5" style="font-size: 8px;"> ELSA RIVERA RIVERA</span> <br>
                            <!-- <span class="bodyStdMuestra fontSize5" style="font-size: 8px;"> I.A. MARÍA LUISA ZAYAS RAMÍREZ</span> <br> -->
                            <!-- <span class="bodyStdMuestra fontSize5" style="font-size: 8px;"> Q.F.B SANDRA ROJAS NAVARRO</span> <br>   -->
                            <!-- <span class="bodyStdMuestra fontSize5" style="font-size: 8px;"> Q.F.B SANDRA ROJAS NAVARRO</span> <br> -->
                            <!-- <span class="bodyStdMuestra fontSize5" style="font-size: 8px;"> Q.F.B SANDRA ROJAS NAVARRO</span> <br>  -->
                            <center><span class="cabeceraStdMuestra fontNormal fontSize5" style="font-size: 8px;"> AUTORIZÓ  SIGNATARIO</span></center>
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
                    <td style="text-align: right;"><span class="revisiones" style="font-size: 8px">FO-13-001</span> <br> <span class="revisiones" style="font-size: 8px">Revisión 6 05/06/2025</span></td>
                </tr>
            </thead>                        
        </table>  
    </div> 
           
</footer>