<footer>
    <div class="contenedorPadre12">
        <div class="contenedorHijo12">            
            <span class="cabeceraStdMuestra"> ANALIZÓ <br> </span>
            <span><img style="width: auto; height: auto; max-width: 100px; max-height: 80px;" src="https://sistemaacama.com.mx/public/storage/{{@$analizo->firma}}"> <br></span>
            <span class="bodyStdMuestra"> {{@$analizo->name}} </span>
        </div>

        <div class="contenedorHijo12">
            <span class="cabeceraStdMuestra">REVISÓ <br> </span>                                    
            <span><img style="width: auto; height: auto; max-width: 100px; max-height: 80px;"  src="https://sistemaacama.com.mx/public/storage/{{@$reviso->firma}}"> <br></span>
            <span class="bodyStdMuestra">  {{@$reviso->name}} </span>
        </div>        
    </div>

    <div id="revisiones">
        <span>RE-12-001-33</span><br>
        <span>2015-01-02</span><br>
        <span>REV.8</span>
    </div>
</footer>