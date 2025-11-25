<div class="col-12 negrita">
    <div>
        <div>
            <table class="table-sm" width="100%">
                <tr>

                    @switch(intval(@$model->Id_norma))
                    @case(2)
                    @if (@$promGra)

                    <td class="fontCalibri anchoColumna111 fontSize8">GRASAS Y ACEITES (G Y A) mg/L</td>
                    <td class="fontCalibri anchoColumna111 fontSize8">
                        @if (@$promGra->Resultado2 <= @$promGra->Limite)
                            < {{@$promGra->Limite}}
                                @else
                                {{number_format(@$promGra->Resultado2, 2, ".", "")}}
                                @endif
                    </td>
                    @endif

                    @if ($promGas)
                    <td class="fontCalibri anchoColumna111 fontSize8">GASTO L/s</td>
                    <td class="fontCalibri anchoColumna111 fontSize8">{{number_format(@$promGas->Resultado2, 2,'.','')}}
                    </td>
                    @endif

                    </td>
                    @break
                    @case(4)
                    @if (@$promGra)
                    <td class="fontCalibri anchoColumna111 fontSize8">GRASAS Y ACEITES (G Y A) mg/L</td>
                    <td class="fontCalibri anchoColumna111 fontSize8">
                        @if (@$promGra->Resultado2 <= @$promGra->Limite)
                            < {{$promGra->Limite}}
                                @else
                                {{number_format(@$promGra->Resultado2, 2, ".","")}}
                                @endif
                    </td>
                    @endif

                    @if (@$promGas)
                    <td class="fontCalibri anchoColumna111 fontSize8">GASTO L/s</td>
                    <td class="fontCalibri anchoColumna111 fontSize8">{{number_format(@$promGas->Resultado2, 2,'.','')}}
                    </td>
                    @endif
                    @if (@$promCol)
                    <td class="fontCalibri anchoColumna111 fontSize8">COLIFORMES FECALES NMP/100mL</td>
                    <td class="fontCalibri anchoColumna111 fontSize8">
                        @if (@$promCol->Resultado2 < @$promCol->Limite)
                            < {{@$promCol->Limite}}
                                @else
                                {{number_format(@$promCol->Resultado2, 2, ".", "")}}
                                @endif
                    </td>
                    @endif

                    @break
                    @case(30)

                    @break
                    @case(1)
                    @if (@$promGra)
                    <td class="fontCalibri anchoColumna111 fontSize8">GRASAS Y ACEITES (G Y A) mg/L</td>
                    <td class="fontCalibri anchoColumna111 fontSize8">
                        @if (@$promGra->Resultado2 <= @$promGra->Limite)
                            < {{@$promGra->Limite}}
                                @else
                                {{number_format(@$promGra->Resultado2, 2, ".","")}}
                                @endif
                    </td>
                    @endif

                    @if (@$promCol)
                    <td class="fontCalibri anchoColumna111 fontSize8">COLIFORMES FECALES NMP/100mL</td>
                    <td class="fontCalibri anchoColumna111 fontSize8">
                        @if (@$promCol->Resultado2 >= @$promCol->Limite)
                        {{number_format(@$promCol->Resultado2, 2, ".", "")}}
                        @else
                        < {{@$promCol->Limite}}
                            @endif
                    </td>
                    @endif
                    @if (@$promCol2)
                    <td class="fontCalibri anchoColumna111 fontSize8">COLIFORMES TOTAL NMP/100mL</td>
                    <td class="fontCalibri anchoColumna111 fontSize8">
                        @if (@$promCol2->Resultado2 <= @$promCol2->Limite)
                            < {{@$promCol2->Limite}}
                                @else
                                {{number_format(@$promCol2->Resultado2, 2, ".", "")}}
                                @endif
                    </td>
                    @endif
                    @if (@$promGas)
                    <td class="fontCalibri anchoColumna111 fontSize8">GASTO L/s</td>
                    <td class="fontCalibri anchoColumna111 fontSize8">
                        {{-- @if (@$promGas->Resultado2 <= @$promGas->Limite)
                            < {{@$promGas->Limite}}
                                @else
                                {{round(@$promGas->Resultado2,2)}}
                                @endif --}}
                                {{number_format(@$promGas->Resultado2, 2,'.','')}}
                    </td>
                    @endif

                    @break
                    @case(27)
                    @case(33)
                    @if (@$promGra != null)
                    <!--  Grasas -->
                    <td class="fontCalibri anchoColumna111 fontSize8">GRASAS Y ACEITES (G Y A) mg/L</td>
                    <td class="fontCalibri anchoColumna111 fontSize8">
                        @if (@$promGra->Resultado2 <= @$promGra->Limite)
                            < {{@$promGra->Limite}}
                                @else
                                {{number_format(@$promGra->Resultado2,2,'.','')}}
                                @endif
                    </td>
                    @endif

                    @if (@$promCol)
                    <td class="fontCalibri anchoColumna111 fontSize8">COLIFORMES FECALES NMP/100mL</td>
                    <td class="fontCalibri anchoColumna111 fontSize8">
                        @if (@$promCol->Resultado2 < @$promCol->Limite)
                            < {{@$promCol->Limite}}
                                @else
                                {{number_format(@$promCol->Resultado2, 2, ".", "")}}
                                @endif
                    </td>
                    @endif
                    @if (@$promCol2)
                    <td class="fontCalibri anchoColumna111 fontSize8">COLIFORMES TOTAL NMP/100mL</td>
                    <td class="fontCalibri anchoColumna111 fontSize8">
                        @if (@$promCol2->Resultado2 < @$promCol2->Limite)
                            < {{@$promCol2->Limite}}
                                @else
                                {{number_format(@$promCol2->Resultado2, 2, ".", "")}}
                                @endif
                    </td>
                    @endif
                    @if (@$promEco)
                    <td class="fontCalibri anchoColumna111 fontSize8">Escherichia coli NMP/100mL</td>
                    <td class="fontCalibri anchoColumna111 fontSize8">
                        @if (@$promEco->Resultado2 < @$promEco->Limite)
                            < {{@$promEco->Limite}}
                                @else
                                {{number_format(@$promEco->Resultado2, 2, ".", "")}}
                                @endif
                    </td>
                    @endif
                    @if (@$promEnt)
                    <td class="fontCalibri anchoColumna111 fontSize8">Enterococos Fecales NMP/100mL</td>
                    <td class="fontCalibri anchoColumna111 fontSize8">
                        @if (@$promEnt->Resultado2 < @$promEnt->Limite)
                            < {{@$promEnt->Limite}}
                                @else
                                {{number_format(@$promEnt->Resultado2, 2, ".", "")}}
                                @endif
                    </td>
                    @endif
                    @if (@$promGas)
                    <td class="fontCalibri anchoColumna111 fontSize8">GASTO L/s</td>
                    <td class="fontCalibri anchoColumna111 fontSize8">

                        {{number_format(@$promGas->Resultado2, 2, ".", "")}}

                    </td>
                    @endif




                    @break
                    @default

                    @if (@$model->Num_tomas > 1)
                    @if (@$promGra != null)
                    <!--  Grasas -->
                    <td class="fontCalibri anchoColumna111 fontSize8">GRASAS Y ACEITES (G Y A) mg/L</td>
                    <td class="fontCalibri anchoColumna111 fontSize8">
                        @if (@$promGra->Resultado2 <= @$promGra->Limite)
                            < {{@$promGra->Limite}}
                                @else
                                {{number_format(@$promGra->Resultado2,2,'.','')}}
                                @endif
                    </td>
                    @endif
                    @if (@$promEco)
                    <td class="fontCalibri anchoColumna111 fontSize8">Escherichia coli NMP/100mL</td>
                    <td class="fontCalibri anchoColumna111 fontSize8">
                        @if (@$promEco->Resultado2 < @$promEco->Limite)
                            < {{@$promEco->Limite}}
                                @else
                                {{number_format(@$promEco->Resultado2, 2, ".", "")}}
                                @endif
                    </td>
                    @endif
                    @if (@$promEnt)
                    <td class="fontCalibri anchoColumna111 fontSize8"> Enterococos Fecales NMP/100mL</td>
                    <td class="fontCalibri anchoColumna111 fontSize8">
                        @if (@$promEnt->Resultado2 < @$promEnt->Limite)
                            < {{@$promEnt->Limite}}
                                @else
                                {{number_format(@$promEnt->Resultado2, 2, ".", "")}}
                                @endif
                    </td>
                    @endif
                    @endif
                    @endswitch
                    <td class="fontCalibri anchoColumna111 justifyCenter">

                        <span class="fontSize7 negrita">FIRMA RESPONSABLE</span> <br>
                        <span class="fontSize8">{{$reportesCadena->Titulo_responsable}}
                            {{$reportesCadena->Nombre_responsable}}</span> &nbsp;&nbsp;
                    </td>
                    <td class="justifyCenter anchoColumna111 justifyCenter">
                        <img style="width: auto; height: auto; max-width: 60px; max-height: 40px;"
                            src="{{url('public/storage/'.@$firmaRes->firma)}}">

                    </td>
                    <td class="justifyCenter anchoColumna111 justifyCenter">
                        @php
                        /* $url = url()->current(); */
                        $url = "http://sistemasofia.ddns.net:86/sofia/clientes/cadena-custodia-interna/".@$folioEncript;
                        $qr_code = "data:image/png;base64," . \DNS2D::getBarcodePNG((string) $url, "QRCODE");
                        @endphp
                    </td>
                    <td class="justifyCenter anchoColumna111"><img style="width: 5%; height: 5%;" src="{{$qr_code}}"
                            alt="barcode" /> <br> {{@$model->Folio_servicio}}
                    </td>
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