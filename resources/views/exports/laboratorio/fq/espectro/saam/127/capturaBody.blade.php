<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/fq/espectro/saam/saamPDF.css')}}">
    <title>Captura PDF</title>
</head>
<body>

    <div id="contenidoCurva">
        @php
            echo @$plantilla[0]->Texto;
        @endphp
    </div>

    <br>
    <br>
    <br>

    <div class="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos">
            <thead>

                <tr>
                    <th class="nombreHeader" colspan="10"> 
                        Resultado de las muestras
                    </th>                    
                </tr>                

                <tr>
                    <th class="tableCabecera anchoColumna">No. de muestra</th>
                    <th class="tableCabecera anchoColumna">Volumen de muestra (mL)</th>
                    <th class="tableCabecera anchoColumna">Abs 1</th>
                    <th class="tableCabecera anchoColumna">Abs 2</th>
                    <th class="tableCabecera anchoColumna">Abs 3</th>
                    <th class="tableCabecera anchoColumna">Abs Promedio</th>
                    <th class="tableCabecera anchoColumna">SUSTANCIAS ACTIVAS AL AZUL DE METILENO (SAAM) mg/L</th>                    
                    <th class="tableCabecera anchoColumna">Observaciones</th>
                    <th class="anchoColumna"></th>
                    <th class="anchoColumna"></th>
                </tr>
            </thead>
    
            <tbody>
                @foreach ($model as $item)
                    <tr>
                        <td class="tableContent">
                         @if (@$item->Id_control != 1)
                                {{@$item->Control}}<br>
                                {{@$item->Folio_servicio}}
                            @else
                            {{@$item->Folio_servicio}}
                            @endif                                     
                        </td>
                        <td class="tableContent">{{@$item->Vol_muestra}}</td>
                        <td class="tableContent">{{@$item->Abs1}}</td>
                        <td class="tableContent">{{@$item->Abs2}}</td>
                        <td class="tableContent">{{@$item->Abs3}}</td>
                        <td class="tableContent">{{number_format(@$item->Promedio, 3, ".", ".")}}</td>
                        @if ($item->Resultado > $item->Limite)
                            <td class="tableContent">{{number_format(@$item->Resultado, 3, ".", ".")}}</td>
                        @else 
                            <td class="tableContent">< {{number_format(@$item->Limite, 3, ".", ".")}}</td>
                        @endif
                        <td class="tableContent">{{@$item->Observacion}}</td>
                        <td class="tableContent">
                            @if (@$item->Liberado == 1)
                                Liberado
                            @elseif(@$item->Liberado == 0)
                                No liberado
                            @endif     
                        </td>
                        <td class="tableContent">{{@$item->Control}}</td>
                    </tr>
                @endforeach
           
            </tbody>        
        </table>  
    </div>

    <div class="contenedorSexto">                
        <span><br> Absorbancia B1: 0</span> <br><br>
        <span>Absorbancia B2: 0</span> <br><br>
        <span>Absorbancia B3: 0</span> <br><br>
        <span>RESULTADO BLANCO: 0</span>
    </div>
    
 
    </div>
</body>
</html>