<footer>
    
    <!-- <div id="pie1">
        <div id="pie2">
            <table class="table table-borderless">
                <thead>
                    <tr>
                        <td id="analizo">ANALIZÓ</td>
                        <td id="superviso">SUPERVISÓ</td>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td rowspan="2"><img style="width: 60px; height: 40px;" src="https://dev.sistemaacama.com.mx//storage/users/May2021/ZApPzkPb5RId7WHFQHon.jpeg"></td>
                        <td>AQUÍ VA LA FIRMA</td>
                    </tr>
                    <tr>
                        <td id="nombreAnalizo">{{$usuario->name}}</td>
                        <td id="nombreSuperviso">NOMBRE SUPERVISÓ</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div> -->

    <div class="contenedorPadre11">
        <div class="contenedorHijo11">            
            <span class="cabeceraStdMuestra"> ANALIZÓ <br> </span>
            <span><img style="width: auto; height: auto; max-width: 100px; max-height: 80px;" src="https://dev.sistemaacama.com.mx//storage/users/May2021/ZApPzkPb5RId7WHFQHon.jpeg"> <br></span>
            <span class="bodyStdMuestra"> {{$usuario->name}} </span>
        </div>

        <div class="contenedorHijo11">
            <span class="cabeceraStdMuestra">SUPERVISÓ <br> </span>                                    
            <span><img style="width: auto; height: auto; max-width: 100px; max-height: 80px;" src="https://dev.sistemaacama.com.mx//storage/users/May2021/ZApPzkPb5RId7WHFQHon.jpeg"> <br></span>
            <span class="bodyStdMuestra"> {{$usuario->name}} </span>
        </div>        
    </div>

    <div id="revisiones">
        <span>RE-12-001-18</span><br>
        <span>2017-04-07</span><br>
        <span>REV.9</span>
    </div>
</footer>