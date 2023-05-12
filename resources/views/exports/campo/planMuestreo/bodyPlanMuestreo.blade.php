<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/campo/planMuestreo/planMuestreo.css')}}">
    <title>Plan de muestreo</title>
</head>
<body>
    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <thead>
                <tr>
                    <td class="tableCabecera bordesTablaBody justificadoCentr fontSize14 fontBold" colspan="3">MATERIAL DE MUESTREO</td>
                </tr>

                <tr>
                    <td class="tableCabecera bordesTablaBody justificadoCentr fontSize12 fontCursiva fontNormal" height="15" width="40%">Parámetro envase &nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr fontSize12 fontNormal fontCursiva" width="20%">&nbsp;Total&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr fontSize12 fontNormal fontCursiva" width="40%">&nbsp;Volumen&nbsp;&nbsp;</td>                                       
                </tr>
            </thead>
    
            <tbody>    
                @for ($i = 0; $i < $paqueteLength; $i++)
                <tr>
                    <td class="tableContent bordesTablaBody fontSize9 fontBold justificadorCentr" height="25">{{@$paquete[$i]->Area}}</td>
                    <td class="tableContent bordesTablaBody fontSize9 fontBold justificadorCentr">                            
                        @if (@$paquete[$i]->Id_area == 2 || @$paquete[$i]->Id_area == 7 || @$paquete[$i]->Id_area == 16 || @$paquete[$i]->Id_area == 17 || @$paquete[$i]->Id_area == 45 || @$paquete[$i]->Id_area == 34 || @$paquete[$i]->Id_area == 33)
                            {{@$paquete[$i]->Cantidad * @$model->Num_tomas * $puntos}}
                        @else 
                            {{@$paquete[$i]->Cantidad * $puntos}}
                        @endif 
                    </td>
                    <td class="tableContent bordesTablaBody fontSize9 fontBold justificadorCentr">{{@$paquete[$i]->Envase}} {{@$paquete[$i]->Volumen}} {{@$paquete[$i]->Unidad}}</td>
                </tr>
                @endfor
            </tbody>        
        </table>        
    </div> 

    <div id="contenedorTabla" width="100%">
        
        <div class="floatLeft">
            <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
                <thead>                

                    <tr>
                        <td class="tableCabecera bordesTablaBody justificadoCentr fontSize9 fontCursiva fontBold" height="25">PRESERVADORES &nbsp;</td>                    
                    </tr>
                </thead>
        
                <tbody>  
                    @for ($i = 0; $i < @$preservacionesArrayLength; $i++)
                        <tr>
                            <td class="tableContent bordesTablaBody fontBold justificadorIzq">{{@$preservacionesArray[$i]}}</td>
                        </tr>
                    @endfor                                  
                </tbody>        
            </table>
        </div>

        <div class="floatLeft">
            <table autosize="1" class="table table-borderless floatLeft" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" style="width:100%">
                <thead>                

                    <tr>
                        <td class="tableCabecera bordesTablaBody justificadoCentr fontSize8 fontCursiva fontBold" height="25">PARA MEDICION DE FLUJO Y ACONDICIONAMIENTO DE LA DESCARGA&nbsp;</td>                    
                    </tr>
                </thead>        

                <tbody> 
                
                    @for ($i = 0; $i < $complementoCampoTipo1Length; $i++)
                        <tr>
                            <td class="tableContent bordesTablaBody fontBold">{{@$complementoCampoTipo1[$i]->Complemento}}</td>
                        </tr>
                    @endfor                                    
                </tbody>        
            </table>
        </div>

        <div class="floatLeft">
            <table autosize="1" class="table table-borderless floatLeft" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" style="width:100%">
                <thead>                

                    <tr>
                        <td class="tableCabecera bordesTablaBody justificadoCentr fontSize9 fontCursiva fontBold" height="25">PARA EFECTUAR EL MUESTREO Y EQUIPO DE SEGURIDAD&nbsp;</td>
                    </tr>
                </thead>
        
                <tbody>                
                    
                    @for ($i = 0; $i < $complementoCampoTipo2Length; $i++)
                        <tr>
                            <td class="tableContent bordesTablaBody fontBold">{{@$complementoCampoTipo2[$i]->Complemento}}</td>
                        </tr>
                    @endfor                    
                </tbody>        
            </table>
        </div>

        <div class="floatLeft">
            <table autosize="1" class="table table-borderless floatLeft" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" style="width:100%">
                <thead>                

                    <tr>
                        <td class="tableCabecera bordesTablaBody justificadoCentr fontSize9 fontCursiva fontBold" height="25">COMPLEMENTOS&nbsp;</td>                    
                    </tr>
                </thead>
        
                <tbody>   
                    @for ($i = 0; $i < $complementoCampoTipo3Length; $i++)
                        <tr>
                            <td class="tableContent bordesTablaBody fontBold">{{@$complementoCampoTipo3[$i]->Complemento}}</td>
                        </tr>
                    @endfor
                </tbody>        
            </table>
        </div>
    </div>
    <div width="100%">
        <p style="font-style: normal">Observaciones: @if (@$model->Observacion_plan == null)
            N/A
        @else
            {{@$model->Observacion_plan}}
        @endif</p>
    </div>
</body>
</html>