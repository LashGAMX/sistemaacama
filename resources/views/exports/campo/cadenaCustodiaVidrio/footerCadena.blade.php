<div class="col-12 negrita">
    <div>
        <div>
            <table class="table-sm" width="100%">
                <tr>
 
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
                    $url = "http://sistemasofia.ddns.net:86/sofia/clientes/cadena-custodia-interna/".@$folioEncript;
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