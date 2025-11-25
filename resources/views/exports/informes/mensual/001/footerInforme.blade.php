<footer style="padding-bottom: 30px">    
    <div autosize="1" class="contenedorPadre12 borderFooter">        
        {{-- <table id="tablaDatos" cellpadding="0" cellspacing="0" style="border: 1px solid black; border-collapse: collapse;" width="100%"> --}}
            <table id="tablaDatos" cellpadding="0" cellspacing="0"  width="100%">
            <tbody>        
                    <tr class="borderFooter">
                        <td  class="borderFooter" style="padding:10px">
                           
                            <span class="bodyStdMuestra fontSize5" style="font-size: 8px;"> 
                                @php
                                
                                for ($i = 0; $i < strlen(@$firmaEncript1); $i++) {
                                       // echo $firmaEncript1[$i] . "\n";
                                    }
                              @endphp
                            </span>
                            <br>
                        </td>
                        <td  class="borderFooter" style="padding:10px">
                            <span class="bodyStdMuestra fontSize5" style="font-size: 8px;">
                                @php
                                
                                for ($i = 0; $i < strlen(@$firmaEncript2); $i++) {
                                       // echo $firmaEncript2[$i] . "\n";
                                    }
                             @endphp
                            </span>
                            <br>
                        </td>
                    </tr>  
                    <tr class="borderFooter">
                        <td  class="borderFooter">
                            <span class="bodyStdMuestra fontSize5" style="font-size: 10px;"> {{$firma1->name}}</span> <br> 
                            <center><span class="adaptarPcabeceraStdMuestra fontNormal fontSize5" style="font-size: 10px;"> REVISÓ SIGNATARIO</span></center>
                        </td>
                        <td  class="borderFooter">
                             <span class="bodyStdMuestra fontSize5" style="font-size: 10px;">{{$firma2->name}}</span> <br>
                            <center><span class="cabeceraStdMuestra fontNormal fontSize5" style="font-size: 10px;"> AUTORIZÓ SIGNATARIO</span> </center>
                        </td>
                    </tr> 
            </tbody>
        </table>
    </div>
    
<div id="contenedorTabla">
    <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
        <thead>
            <tr>   
                </td>
                <td style="text-align: right;"><span class="revisiones" style="font-size: 8px">FO-13-001</span> <br> <span class="revisiones" style="font-size: 8px">Revisión 6 05/06/2025</span></td>
            </tr>
        </thead>                        
    </table>  
</div> 
       
</footer> 
 
