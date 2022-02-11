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
        <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000">
            <thead>
                <tr>
                    <td class="tableCabecera bordesTabla">pH de las muestras &nbsp;</td>
                    <td class="tableCabecera bordesTabla">&nbsp;No. de muestra&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTabla">&nbsp;No. de cartucho&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTabla">&nbsp;No. de matraz&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTabla">&nbsp;Masa inicial 3 g&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTabla">&nbsp;Vol. de la muestra(ml)&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTabla">&nbsp;Masa con muestra g&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTabla">&nbsp;G y A mg/L&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTabla">&nbsp;Observaciones&nbsp;&nbsp;</td>                                                            
                    <td class="bordesTabla"></td>
                    <td class="bordesTabla"></td>
                </tr>
            </thead>
    
            <tbody>
                @for ($i = 0; $i < @$dataLength ; $i++)
                    <tr>
                        <td class="tableContent bordesTabla">{{@$data[$i]->Ph}}</td>
                        <td class="tableContent bordesTabla">
                            @if (@$data[$i]->Control == 'Estandar')
                                ESTANDAR
                            @elseif(@$data[$i]->Control == 'Blanco')
                                BLANCO
                            @else
                                {{@$data[$i]->Folio_servicio}}
                            @endif    
                        </td>
                        <td class="tableContent bordesTabla">{{@$data[$i]->Id_matraz}}</td>
                        <td class="tableContent bordesTabla">{{@$data[$i]->Matraz}}</td>
                        <td class="tableContent bordesTabla">{{@$data[$i]->M_inicial3}}</td>
                        <td class="tableContent bordesTabla">{{@$data[$i]->Vol_muestra}}</td>
                        <td class="tableContent bordesTabla">{{@$data[$i]->M_final}}</td>
                        <td class="tableContent bordesTabla">{{@$data[$i]->Resultado}}</td>
                        <td class="tableContent bordesTabla">{{@$data[$i]->Observacion}}</td>
                        <td class="tableContent bordesTabla">
                            @if (@$data[$i]->Liberado == 1)
                                Liberado
                            @elseif(@$data[$i]->Liberado == 0)
                                No liberado
                            @endif 
                        </td>                        
                        <td class="tableContent bordesTabla">{{@$data[$i]->Control}}</td>                        
                    </tr>                
                @endfor
            </tbody>        
        </table>  
    </div>    
</body>
</html>