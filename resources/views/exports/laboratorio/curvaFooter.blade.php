<footer>
    <div class="contenedorPadre12">
        <div class="contenedorHijo12">            
            <span class="cabeceraStdMuestra"> ANALIZÓ <br> </span>
            <span><img style="width: auto; height: auto; max-width: 100px; max-height: 80px;" src="http://sistemasofia.ddns.net:85/sofia/public/storage/users/January2022/3hR0dNwIyWQiodmdxvLX.png"> <br></span>
            <span class="bodyStdMuestra"> {{$usuario->name}} </span>
        </div>

        <div class="contenedorHijo12">
            <span class="cabeceraStdMuestra">SUPERVISÓ <br> </span>                                    
            <span><img style="width: auto; height: auto; max-width: 100px; max-height: 80px;" src="http://sistemasofia.ddns.net:85/sofia/public/storage/users/January2022/3hR0dNwIyWQiodmdxvLX.png"> <br></span>
            <span class="bodyStdMuestra"> {{$usuario->name}} </span>
        </div>        
    </div>

    <div id="revisiones">

        {{-- Intrucciones temporales debido a que tiene que ver con históricos--}}
        
            @if (@$tecnicaUsada->Id_tecnica == 20) 
                <span>RE-12-001-18</span><br>
                <span>2017-04-07</span><br>
                <span>REV.9</span>
            @elseif (@$tecnicaUsada->Id_tecnica == 21)
                <span>RE-12-001-19</span><br>
                <span>2017-04-07</span><br>
                <span>REV.9</span>
            @elseif (@$tecnicaUsada->Id_tecnica == 22)
                <span>RE-12-001-56</span><br>
                <span>2017-04-07</span><br>
                <span>REV.2</span>
            @else
                <span>RE-12-001-19</span><br>
                <span>2017-04-07</span><br>
                <span>REV.9</span>
            @endif                    
    </div>
</footer>