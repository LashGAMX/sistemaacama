<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/fq/curvaPDF.css')}}">
    <title>Captura PDF</title>
</head>
<body>
    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos">
            <thead>
                <tr>
                    <td class="tableCabecera">pH de las muestras &nbsp;</td>
                    <td class="tableCabecera">&nbsp;No. de muestra&nbsp;&nbsp;</td>
                    <td class="tableCabecera">&nbsp;No. de cartucho&nbsp;&nbsp;</td>
                    <td class="tableCabecera">&nbsp;No. de matraz&nbsp;&nbsp;</td>
                    <td class="tableCabecera">&nbsp;Masa inicial 3 g&nbsp;&nbsp;</td>
                    <td class="tableCabecera">&nbsp;Vol. de la muestra(ml)&nbsp;&nbsp;</td>
                    <td class="tableCabecera">&nbsp;Masa con muestra g&nbsp;&nbsp;</td>
                    <td class="tableCabecera">&nbsp;G y A mg/L&nbsp;&nbsp;</td>
                    <td class="tableCabecera">&nbsp;Observaciones&nbsp;&nbsp;</td>                                                            
                    <td></td>
                    <td></td>
                </tr>
            </thead>
    
            <tbody>
                @for ($i = 0; $i < @$dataLength ; $i++)
                    <tr>
                        <td class="tableContent">{{@$data[$i]->Ph}}</td>
                        <td class="tableContent">{{@$data[$i]->Folio_servicio}}</td>
                        <td class="tableContent">PRUEBA</td>
                        <td class="tableContent">PRUEBA</td>
                        <td class="tableContent">PRUEBA</td>
                        <td class="tableContent">{{@$data[$i]->Vol_muestra}}</td>
                        <td class="tableContent">PRUEBA</td>
                        <td class="tableContent">PRUEBA</td>
                        <td class="tableContent">{{@$data[$i]->Observaciones}}</td>
                        <td class="tableContent">LIBERADO</td>                        
                        <td class="tableContent">PRUEBA</td>                        
                    </tr>                
                @endfor
            </tbody>        
        </table>  
    </div>
</body>
</html>