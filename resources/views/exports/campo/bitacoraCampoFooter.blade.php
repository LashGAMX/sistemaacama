<br>
<footer>
    <table autosize="1" style="width: 100%" cellpadding="2" cellspacing="0" border-color="#000000">
        <tbody>
            @php
            use Carbon\Carbon;
            $fechaCorte = Carbon::create(2025, 6, 5);
            $fechaMuestreo = Carbon::parse($model->Fecha_muestreo);
            @endphp

            <tr>
                <td class="fontNormal fontCalibri fontSize10 justificadorCentr" width="33.33%">{{ @$muestreador->name }}
                </td>

                @if ($solGenTemp->Estado != 4)
                <td class="fontNormal fontCalibri fontSize10 justificadorCentr" width="33.33%">NOMBRE DEL SUPERVISOR
                </td>
                @else
                <td class="fontNormal fontCalibri fontSize10 justificadorCentr" width="33.33%">{{ $firmaRevisor->name }}
                </td>
                @endif

                <td class="fontNormal fontCalibri fontSize10 justificadoDer" width="33.33%">
                    @if ($fechaMuestreo->gte($fechaCorte))
                    RE-12-001-25 REV. 16 05/06/2025 <br>
                    @else
                    RE-12-001-25 REV. 15 26/03/2019 <br>    
                    @endif
                </td>
            </tr>


            <tr>
                <td class="justificadorCentr">
                    <img style="width: auto; height: auto; max-width: 100px; max-height: 80px;"
                        src="http://sistemasofia.ddns.net:85/sofia/public/storage/{{$muestreador->firma}}">
                </td>

                <td class="justificadorCentr">
                    @if ($solGenTemp->Estado != 4)
                    ---
                    @else
                    <img style="width: auto; height: auto; max-width: 100px; max-height: 80px;"
                        src="http://sistemasofia.ddns.net:85/sofia/public/storage/{{$firmaRevisor->firma}}">
                    @endif
                </td>

                <td>
                    &nbsp;
                </td>
            </tr>

            <tr>
                <td class="fontBold fontCalibri fontSize10 justificadorCentr">FIRMA MUESTREADOR</td>
                <td class="fontBold fontCalibri fontSize10 justificadorCentr">REVISO</td>
                <td>&nbsp;</td>
            </tr>
        </tbody>
    </table>
    <br>
</footer>