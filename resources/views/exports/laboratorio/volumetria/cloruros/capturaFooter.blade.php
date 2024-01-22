<footer>
    <div class="contenedorPadre12">
        <div class="contenedorHijo12">            
            <span class="cabeceraStdMuestra"> ANALIZÓ <br> </span>
            @if ($comprobacion->count())
                <span>-------------</span><br>
                <span class="bodyStdMuestra"> Muestras sin liberar </span>
            @else
                <span><img style="width: auto; height: auto; max-width: 100px; max-height: 80px;" src="http://sistemasofia.ddns.net:85/sofia/public/storage/{{$analizo->firma}}"> <br></span>
                <span class="bodyStdMuestra"> {{$analizo->name}} </span>
            @endif
        </div>

        <div class="contenedorHijo12">
            <span class="cabeceraStdMuestra">REVISÓ <br> </span>                                    
            <span><img style="width: auto; height: auto; max-width: 100px; max-height: 80px;" src="http://sistemasofia.ddns.net:85/sofia/public/storage/{{$reviso->firma}}"> <br></span>
            <span class="bodyStdMuestra"> {{@$reviso->name}} </span>
        </div>        
    </div>

    <div style="font-size: 8px;">
        @php
            echo $plantilla[0]->Rev;
        @endphp 
    </div>
</footer> 