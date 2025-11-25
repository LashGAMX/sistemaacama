<footer>
    <div class="contenedorPadre12">
        <div class="contenedorHijo12">            
            <span class="cabeceraStdMuestra"> ANALIZÓ <br> </span>
            @if (@$comprobacion->count())
                <span>-------------</span><br>
                <span class="bodyStdMuestra"> Muestras sin liberar </span>
            @else
                <span><img style="width: auto; height: auto; max-width: 100px; max-height: 80px;" src="{{url('public/storage/'.@$analizo->firma)}}" alt="Sin firma"> <br></span>
                <span class="bodyStdMuestra"> {{@$analizo->name}} </span>
            @endif
        </div>

        <div class="contenedorHijo12">
            <span class="cabeceraStdMuestra">REVISÓ <br> </span>                                    
            @if (@$lote->Supervisado == 0)
                <span>-------------</span><br>
                <span class="bodyStdMuestra"> Lote sin supervisar</span>
            @else
                <span><img style="width: auto; height: auto; max-width: 100px; max-height: 80px;" src="{{url('public/storage/'.@$reviso->firma)}}"> <br></span>
                <span class="bodyStdMuestra"> {{@$reviso->name}} </span>
            @endif
        </div>        
    </div>

    <div style="font-size: 8px;">
        @php
            echo $plantilla[0]->Rev;
        @endphp
    </div>
</footer>