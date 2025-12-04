<div id="contenedorTabla">
    <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
        <thead>

            <tr>
                <td style="text-align: right;"><span class="revisiones"></span> 
                <br> 
                <!-- <span class="revisiones">FOA-13-001 Rev. 6 22/05/2025</span></td> -->
                     @php
                        $fechaAnalisis = \Carbon\Carbon::parse($proceso->Periodo_analisis)->addDays(2);
                        $fechaLimite   = \Carbon\Carbon::parse("2025-11-25");
                        $swInfo = 0;
                    @endphp

                    @if ($fechaAnalisis->greaterThanOrEqualTo($fechaLimite))
                        <span class="revisiones">FOA-13-001 Rev. 7 24/11/2025</span></td>
                    @else
                       <span class="revisiones">FOA-13-001 Rev. 6 22/05/2025</span></td>
                    @endif
                
            </tr>
        </thead>                        
    </table>  
</div> 
 