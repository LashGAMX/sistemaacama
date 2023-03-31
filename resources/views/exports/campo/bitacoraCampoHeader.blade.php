<div class="col-md-12">
    <table class="table table-borderless table-sm">
        <tr>
            <td class="fontBold fontCalibri fontSize14">
                @switch($model->Id_norma)
                    @case(5)
                    @case(30)
                        <center>PROCEDIMIENTO INTERNO DE MUESTREO PE-10-002-27</center>
                        @break
                    @default
                    <center>NORMA DE MUESTREO NMX-AA-003-1980</center>
                @endswitch
            </td>
        </tr>
    </table>
</div>

<div class="col-12 fontNormal fontCalibri justificadoDer fontStyleNormal">
    {PAGENO} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; / &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {nbpg}
</div>

<div class="col-12 fontBold fontCalibri justificadorIzq fontStyleNormal">
    Folio: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="fontNormal">{{$model->Folio_servicio}}</span>
</div>

<div class="col-12 fontBold fontCalibri justificadorIzq fontStyleNormal">
    Fecha: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="fontNormal">{{\Carbon\Carbon::parse(@$model->Fecha_muestreo)->format('d/m/Y')}}</span>
</div>