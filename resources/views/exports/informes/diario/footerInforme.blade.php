<div id="contenedorTabla">
    <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0"
        border-color="#000000" width="100%">
        <thead>

            <tr>
                <td style="text-align: right;"><span class="revisiones">{{@$impresion[0]->Clave}}</span>
                    <br>
                    @php
                    use Carbon\Carbon;

                    $fechaCorte = Carbon::createFromFormat('d/m/Y', '05/06/2025');
                    $emision = $modelProcesoAnalisis->Emision_informe ?
                    Carbon::parse($modelProcesoAnalisis->Emision_informe) : null;
                    @endphp

                    <span class="revisiones">
                        Revisión
                        @if ($emision && $emision->gt($fechaCorte))
                        6   05/06/2025
                        @else
                        {{ @$impresion[0]->Num_rev }}
                        @endif
                    </span>

            </tr>
        </thead>
    </table>
</div>