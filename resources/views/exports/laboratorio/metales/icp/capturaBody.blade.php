<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/mb/coliformes/coliformesPDF.css')}}">
    <title>Captura PDF</title>
</head>
<body id="bodyMargin">

<div id="contenidoCurva">
        @php
            echo $plantilla[0]->Texto; 
        @endphp
    </div>
    <br>

    <div class="contenedorTabla">
        <p id='header1' style="font-size: 12px">RESULTADOS ICP-OES AGUA USO Y CONSUMO</p>
        <table autosize="1" class="table table-borderless" id="tablaDatos">
            <thead>
                <tr>
                    <th class="tableCabecera anchoColumna">Folio</th> 
                    <th class="tableCabecera anchoColumna">Parametro</th>
                    <th class="tableCabecera anchoColumna">CPS Prom</th>
                    <th class="tableCabecera anchoColumna">Resultado (mg/L)</th>
                    <th class="tableCabecera anchoColumna">Fecha Análisis</th>
                    <th class="anchoColumna"></th>
                </tr>
            </thead>
            <tbody>
                 @php 
                    $cont = 0;
                @endphp
                @foreach ($controles as $item)
            

                @if ($cont == 3)
                    <tr class="tablaDatosSmall">
                        <td class="tableContent">{{$item->Id_codigo}}</td>
                        <td class="tableContent">{{$item->Parametro}}</td>
                        <td class="tableContent">{{$item->Cps}}</td>
                        @if ($item->Resultado == NULL)
                        <td class="tableContent">-----</td>
                        @else
                            <td class="tableContent">{{number_format($item->Resultado,3)}}</td>
                        @endif
                        <td class="tableContent">{{$item->Fecha}}</td> 
                        <td class="tableContent">Control</td> 
                    </tr>
                        @php 
                            $cont = 0;
                        @endphp
                @endif
                    @php 
                        $cont++;
                    @endphp
                @endforeach

                @php 
                    $cont = 0;
                @endphp
                @foreach ($resultados as $item) 
                    @if ($cont == 3)
                        <tr>
                            <td class="tableContent">{{$item->Id_codigo}}</td>
                            <td class="tableContent">{{$item->Parametro}}</td>
                            <td class="tableContent">{{$item->Cps}}</td>
                            @if ($item->Resultado == NULL)
                            <td class="tableContent">-----</td>
                            @else
                                <td class="tableContent">{{number_format($item->Resultado,3)}}</td>
                            @endif
                            <td class="tableContent">{{$item->Fecha}}</td>
                            <td class="tableContent">Resultado</td>
                        </tr> 
                        @php 
                            $cont = 0;
                        @endphp
                    @endif
                    @php 
                        $cont++;
                    @endphp
                @endforeach
                
            </tbody>        
        </table>  
    </div>

</body>
</html>