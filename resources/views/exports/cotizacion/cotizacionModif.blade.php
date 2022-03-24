<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/pdf/cotizacionModif.css')}}">
    <title>Plan de muestreo</title>
</head>
<body>
    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <thead>
                <tr>
                    <td class="tableCabecera justificadoDer fontSize14 fontBold">FOLIO COTIZACION</td>
                </tr>

                <tr>
                    <td class="tableCabecera justificadorIzq fontSize14 fontBold">EMPRESA</td>
                </tr>                    

                <tr>
                    <td class="tableCabecera justificadorIzq fontSize14 fontBold">DIRECCION</td>
                </tr>                                    

                <tr>
                    <td class="tableCabecera justificadorIzq fontSize14 fontBold">TELEFONO</td>
                </tr>                    
            </thead>                
        </table>        
    </div>  

    <br>

    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <thead>
                <tr>
                    <td class="tableCabecera bordesTablaBody justificadoCentr fontSize14 fontBold">PARAMETRO</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr fontSize14 fontBold">METODO DE PRUEBA</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr fontSize14 fontBold">LIMITE DE CUANTIFICACION DEL METODO</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr fontSize14 fontBold">UNIDAD</td>
                </tr>                
            </thead>
    
            <tbody>                    
                    <tr>
                        <td class="tableContent bordesTablaBody fontSize9 fontBold justificadorCentr" height="25">P</td>
                        <td class="tableContent bordesTablaBody fontSize9 fontBold justificadorCentr">P</td>
                        <td class="tableContent bordesTablaBody fontSize9 fontBold justificadorCentr">P</td>
                        <td class="tableContent bordesTablaBody fontSize9 fontBold justificadorCentr">P</td>
                    </tr>                
            </tbody>        
        </table>        
    </div>     
</body>
</html>