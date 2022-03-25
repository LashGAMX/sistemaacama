<div class="col-md-12">
    <table class="table table-borderless table-sm">
        <tr>
            <td class="fontBold fontCalibri fontSize14">
                <center>NORMA DE MUESTREO {{$model->Clave_norma}}</center>
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