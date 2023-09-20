<br>
<footer>    
    <table autosize="1" style="width: 100%" cellpadding="2" cellspacing="0" border-color="#000000">        
        <tbody>
            <tr>
                <td class="fontNormal fontCalibri fontSize10 justificadorCentr" width="33.33%">{{@$muestreador->name}}</td>
                <td class="fontNormal fontCalibri fontSize10 justificadorCentr" width="33.33%">NOMBRE DEL SUPERVISOR</td>
                <td class="fontNormal fontCalibri fontSize10 justificadoDer" width="33.33%">RE-12-001-25 REV. 15 26/03/2019 <br></td>
            </tr>

            <tr>
                <td class="justificadorCentr">
                    <img style="width: auto; height: auto; max-width: 100px; max-height: 80px;" src="https://sistemaacama.com.mx/public/storage/{{$muestreador->firma}}">
                </td>

                <td class="justificadorCentr">
                    @if (@$solGenTemp->Id_muestreador == 15)
                        <img style="width: auto; height: auto; max-width: 100px; max-height: 80px;" src="https://sistemaacama.com.mx/public/storage/{{$firmaRevisor->firma}}">
                    @else
                        @if ($campoGeneral->Firma_revisor == null)
                            ---             
                        @else
                            <img style="width: auto; height: auto; max-width: 100px; max-height: 80px;" src="data:image/png;base64,{{$campoGeneral->Firma_revisor}}">
                        @endif
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