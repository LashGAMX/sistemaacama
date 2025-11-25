@php
    use Carbon\Carbon;
    $fechaCorte = Carbon::create(2025, 6, 5);
    $fechaMuestreo = Carbon::parse($model->Fecha_muestreo);
@endphp

<footer>
    <div class="col-md-12">
        <table class="table table-sm fontSize7" width="100%">
            <tr>
                <td class="anchoColumna">&nbsp;</td>
                <td class="justifyCenter">&nbsp;</td>
                <td class="justifyCenter">&nbsp;</td>
                <td class="justifyRight">
                    RE-11-002 <br> 
                    @if ($fechaMuestreo->gte($fechaCorte))
                        Rev. 14 05/06/2025
                    @else
                        Rev. 13 02/10/2017
                    @endif
                </td>
            </tr>
            <tr>
                <td class="anchoColumna" style="border: 0">&nbsp;</td>
            </tr>
        </table>
    </div>  
</footer>  
