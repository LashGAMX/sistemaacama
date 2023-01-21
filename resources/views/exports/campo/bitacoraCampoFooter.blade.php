<br>

<footer>    
    <table autosize="1" style="width: 100%" cellpadding="2" cellspacing="0" border-color="#000000">        
        <tbody>
            <tr>
                <td class="fontNormal fontCalibri fontSize10 justificadorCentr" width="33.33%">{{@$muestreador->name}}</td>
                <td class="fontNormal fontCalibri fontSize10 justificadorCentr" width="33.33%">NOMBRE DEL REVISOR</td>
                <td class="fontNormal fontCalibri fontSize10 justificadoDer" width="33.33%">RE-12-001-25 REV15 <br> 26/03/2019</td>
            </tr>

            <tr>
                <td class="justificadorCentr">
                    <img style="width: auto; height: auto; max-width: 100px; max-height: 80px;" src="https://sistemaacama.com.mx/public/storage/{{$muestreador->firma}}">
                </td>

                <td class="justificadorCentr">
                    <img style="width: auto; height: auto; max-width: 100px; max-height: 80px;" src="data:image/gif;base64{{$campoGeneral->Firma_revisor}}">
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