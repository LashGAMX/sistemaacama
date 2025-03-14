<div class="col-12 negrita">
    <div>
        <div>
            <table class="table-sm" width="100%">
                <tr>
                    <!-- @php
                        $aux = DB::table('codigo_parametro')->where('Id_solicitud',$model->Id_solicitud)->where('Id_parametro',102)->get();
                    @endphp
                    @if ($aux->count())
                        <td class="fontCalibri anchoColumna111 fontSize8">
                            Color verdadero 436nm: {{number_format(@$aux[0]->Resultado,3,'.','')}} | 525nm: {{number_format(@$aux[0]->Resultado2,3,'.','')}} | 620nm : {{number_format(@$aux[0]->Resultado_aux,3,'.','')}}
                        </td>
                    @endif -->
                    @switch(@$norma->Id_norma)
                    @case(2)
                        @if (@$promGra->count())
                            <td class="fontCalibri anchoColumna111 fontSize8">GRASAS Y ACEITES (G Y A) mg/L</td>
                            <td class="fontCalibri anchoColumna111 fontSize8">
                                @if (@$promGra[0]->Resultado2 <= @$promGra[0]->Limite)
                                    < {{@$promGra[0]->Limite}}
                                @else
                                    {{number_format(@$promGra[0]->Resultado2, 2, ".", "")}}
                                @endif
                            </td>
                            @endif

                        @if (@$promGas->count())
                            <td class="fontCalibri anchoColumna111 fontSize8">GASTO L/s</td>
                            <td class="fontCalibri anchoColumna111 fontSize8">{{number_format(@$promGas[0]->Resultado2, 2,'.','')}}</td>
                        @endif

                        </td>
                    @break
                    @case(4)
                        @if (@$promGra->count())
                            <td class="fontCalibri anchoColumna111 fontSize8">GRASAS Y ACEITES (G Y A) mg/L</td>
                            <td class="fontCalibri anchoColumna111 fontSize8">
                                @if (@$promGra[0]->Resultado2 <= @$promGra[0]->Limite)
                                    < {{@$promGra[0]->Limite}}
                                @else
                                    {{number_format(@$promGra[0]->Resultado2, 2, ".","")}}
                                @endif
                            </td>
                            @endif

                        @if (@$promGas->count())
                            <td class="fontCalibri anchoColumna111 fontSize8">GASTO L/s</td>
                            <td class="fontCalibri anchoColumna111 fontSize8">{{number_format(@$promGas[0]->Resultado2, 2,'.','')}}</td>
                        @endif
                        @if (@$promCol->count())
                            <td class="fontCalibri anchoColumna111 fontSize8">COLIFORMES FECALES NMP/100mL</td>
                            <td class="fontCalibri anchoColumna111 fontSize8">
                                @if (@$promCol[0]->Resultado2 < @$promCol[0]->Limite)
                                    < {{@$promCol[0]->Limite}}
                                @else
                                    {{number_format(@$promCol[0]->Resultado2, 2, ".", "")}}
                                @endif
                            </td>
                        @endif

                    @break
                    @case(30)
                    
                    @break
                    @case(1)
                        @if (@$promGra->count())
                        <td class="fontCalibri anchoColumna111 fontSize8">GRASAS Y ACEITES (G Y A) mg/L</td>
                        <td class="fontCalibri anchoColumna111 fontSize8">
                            @if (@$promGra[0]->Resultado2 <= @$promGra[0]->Limite)
                                < {{@$promGra[0]->Limite}}
                            @else
                                {{number_format(@$promGra[0]->Resultado2, 2, ".","")}}
                            @endif
                        </td>
                        @endif
                    
                        @if (@$promCol->count())
                        <td class="fontCalibri anchoColumna111 fontSize8">COLIFORMES FECALES NMP/100mL</td>
                        <td class="fontCalibri anchoColumna111 fontSize8">
                            @if (@$promCol[0]->Resultado2 >= @$promCol[0]->Limite)
                                {{number_format(@$promCol[0]->Resultado2, 2, ".", "")}}
                            @else
                                < {{@$promCol[0]->Limite}}
                            @endif
                        </td>
                         @endif
                    @if (@$promCol2->count())
                        <td class="fontCalibri anchoColumna111 fontSize8">COLIFORMES TOTAL NMP/100mL</td>
                        <td class="fontCalibri anchoColumna111 fontSize8">
                            @if (@$promCol2[0]->Resultado2 <= @$promCol2[0]->Limite)
                                < {{@$promCol2[0]->Limite}}
                            @else
                                {{number_format(@$promCol2[0]->Resultado2, 2, ".", "")}}
                            @endif
                        </td>
                    @endif
                        @if (@$promGas->count())
                        <td class="fontCalibri anchoColumna111 fontSize8">GASTO L/s</td>
                        <td class="fontCalibri anchoColumna111 fontSize8">
                            {{-- @if (@$promGas[0]->Resultado2 <= @$promGas[0]->Limite)
                                < {{@$promGas[0]->Limite}}
                            @else
                                {{round(@$promGas[0]->Resultado2,2)}}
                            @endif --}}
                            {{number_format(@$promGas[0]->Resultado2, 2,'.','')}}
                        </td>
                         @endif

                    @break
                    @case(27)
                    @case(33)
                        @if (@$promGra->count())
                        <td class="fontCalibri anchoColumna111 fontSize8">GRASAS Y ACEITES (G Y A) mg/L</td>
                        <td class="fontCalibri anchoColumna111 fontSize8">
                            @if (@$promGra[0]->Resultado2 <= @$promGra[0]->Limite)
                                < {{@$promGra[0]->Limite}}
                            @else
                                {{number_format(@$promGra[0]->Resultado2,2,'.','')}}
                            @endif
                        </td>
                        @endif
                    
                        @if (@$promCol->count())
                            <td class="fontCalibri anchoColumna111 fontSize8">COLIFORMES FECALES NMP/100mL</td>
                            <td class="fontCalibri anchoColumna111 fontSize8">
                                @if (@$promCol[0]->Resultado2 < @$promCol[0]->Limite)
                                    < {{@$promCol[0]->Limite}}
                                @else
                                    {{number_format(@$promCol[0]->Resultado2, 2, ".", "")}}
                                @endif
                            </td>
                        @endif
                        @if (@$promCol2->count())
                            <td class="fontCalibri anchoColumna111 fontSize8">COLIFORMES TOTAL NMP/100mL</td>
                            <td class="fontCalibri anchoColumna111 fontSize8">
                                @if (@$promCol2[0]->Resultado2 < @$promCol2[0]->Limite)
                                    < {{@$prpromCol2mCol[0]->Limite}}
                                @else
                                    {{number_format(@$promCol2[0]->Resultado2, 2, ".", "")}}
                                @endif
                            </td>
                        @endif
                        @if (@$promEco->count())
                            <td class="fontCalibri anchoColumna111 fontSize8">Escherichia coli NMP/100mL</td>
                            <td class="fontCalibri anchoColumna111 fontSize8">
                                @if (@$promEco[0]->Resultado2 < @$promEco[0]->Limite)
                                    < {{@$promEco[0]->Limite}}
                                @else
                                    {{number_format(@$promEco[0]->Resultado2, 2, ".", "")}}
                                @endif
                            </td>
                        @endif
                        @if (@$promEnt->count())
                            <td class="fontCalibri anchoColumna111 fontSize8">	Enterococos Fecales NMP/100mL</td>
                            <td class="fontCalibri anchoColumna111 fontSize8">
                                @if (@$promEnt[0]->Resultado2 < @$promEnt[0]->Limite)
                                    < {{@$promEnt[0]->Limite}}
                                @else
                                    {{number_format(@$promEnt[0]->Resultado2, 2, ".", "")}}
                                @endif
                            </td>
                        @endif
                        @if (@$promGas->count())
                            <td class="fontCalibri anchoColumna111 fontSize8">GASTO L/s</td>
                            <td class="fontCalibri anchoColumna111 fontSize8">
                                {{-- @if (@$promGas[0]->Resultado2 <= @$promGas[0]->Limite)
                                    < {{@$promGas[0]->Limite}}
                                @else
                                    {{round(@$promGas[0]->Resultado2,2)}}
                                @endif --}}
                         
                                {{number_format(@$promGas[0]->Resultado2, 2, ".", "")}}
                            </td>
                        @endif

            
                    @break
                        @default        
                        @if (@$model->Num_tomas > 1)
                            @if (@$promEco->count())
                                <td class="fontCalibri anchoColumna111 fontSize8">Escherichia coli NMP/100mL</td>
                                <td class="fontCalibri anchoColumna111 fontSize8">
                                    @if (@$promEco[0]->Resultado2 < @$promEco[0]->Limite)
                                        < {{@$promEco[0]->Limite}}
                                    @else
                                        {{number_format(@$promEco[0]->Resultado2, 2, ".", "")}}
                                    @endif
                                </td>
                            @endif
                            @if (@$promEnt->count())
                                <td class="fontCalibri anchoColumna111 fontSize8">	Enterococos Fecales NMP/100mL</td>
                                <td class="fontCalibri anchoColumna111 fontSize8">
                                    @if (@$promEnt[0]->Resultado2 < @$promEnt[0]->Limite)
                                        < {{@$promEnt[0]->Limite}}
                                    @else
                                        {{number_format(@$promEnt[0]->Resultado2, 2, ".", "")}}
                                    @endif
                                </td>
                            @endif
                         @endif
                    @endswitch
                        <td class="fontCalibri anchoColumna111 justifyCenter">
                        
                            <span class="fontSize7 negrita">FIRMA RESPONSABLE</span> <br> 
                            <span class="fontSize8">{{$reportesCadena->Titulo_responsable}} {{$reportesCadena->Nombre_responsable}}</span> &nbsp;&nbsp; </td>
                    <td class="justifyCenter anchoColumna111">
                        <img
                            style="width: auto; height: auto; max-width: 60px; max-height: 40px;"
                            src="{{url('public/storage/'.@$firmaRes->firma)}}">
                        
                        </td>
                    @php
                    /*$bar_code = "data:image/png;base64," . \DNS1D::getBarcodePNG($model->Folio_servicio,
                    "C39");*/
                    /*$url = url()->current();*/
                    $url = "https://sistemaacama.com.mx/clientes/cadena-custodia-interna/".@$folioEncript;
                    $qr_code = "data:image/png;base64," . \DNS2D::getBarcodePNG((string) $url, "QRCODE");
                    @endphp

                    <td class="justifyCenter anchoColumna111"><img style="width: 5%; height: 5%;" src="{{$qr_code}}" alt="barcode" /> <br> {{@$model->Folio_servicio}}</td>
                </tr>
            </table>
        </div>
    </div>
</div>


<div class="col-md-12"> 
    
    <table class="table table-sm fontSize7" width="100%">
        <tr>
            <td class="anchoColumna" style="border: 0"></td>
            <td>&nbsp;</td>
            <td class="justifyRight"></td>
            <td class="justifyRight">RE-11-003-1</td>
        </tr>
        <tr>
            <td class="anchoColumna" style="border: 0"></td>
            <td>&nbsp;</td>
            <td class="justifyRight"></td>
            <td class="justifyRight">Rev. {{$reportesCadena->Num_rev}}</td>
        </tr>
        <tr>
            <td class="anchoColumna" style="border: 0"></td>
            <td>&nbsp;</td>
            <td class="justifyRight"></td>
            <td class="justifyRight">Fecha ultima revisión: 01/04/2016</td>
        </tr>
    </table>
</div>