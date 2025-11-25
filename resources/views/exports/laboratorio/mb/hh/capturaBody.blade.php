<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/fq/espectro/cianuros/cianurosPDF.css')}}">
    <title>Captura PDF</title>
</head>
<body>

    <div id="contenidoCurva">
        @php
            echo @$procedimiento[0];
        @endphp
    </div>
    <br>

    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos">
            <thead>       
                <tr>
                    <th class="tableCabecera">No. de muestra</th>
                    <th class="tableCabecera">A.Lumbricoides</th>
                    <th class="tableCabecera">Uncinarias</th>
                    <th class="tableCabecera">H. nana</th>
                    <th class="tableCabecera">T. Trichiura</th>
                    <th class="tableCabecera">Taenia sp</th>
                    <th class="tableCabecera">H/L</th>
                    <th class="tableCabecera">Observaciones</th>                    
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($loteDetalle as $item)
                <tr>
                    <td class="tableContent">{{@$item->Folio_servicio}}</td>
                    <td class="tableContent">{{@$item->A_alumbricoides}}</td>
                    <td class="tableContent">{{@$item->Uncinarias}}</td>
                    <td class="tableContent">{{@$item->H_nana}}</td>
                    <td class="tableContent">{{@$item->T_trichiura}}</td>
                    <td class="tableContent">{{@$item->Taenia_sp}}</td>
                    <td class="tableContent">
                        @if ($item->Resultado < $item->Limite)
                         < {{@$item->Limite}}
                        @else
                            {{@$item->Resultado}}    
                        @endif
                        </td>
                    <td class="tableContent">{{@$item->Observacion}}</td>
                    <td class="tableContent">
                        @if (@$item->Liberado == 1)
                            Liberado
                        @elseif(@$item->Liberado == 0)
                            No liberado
                        @endif </td>
                    <td class="tableContent">{{@$item->Control}}</td>
                </tr>    
                @endforeach
            </tbody>        
        </table>  
    </div>
    <br>
    <div id="contenidoCurva">
        
        @php
            echo @$procedimiento[1];
        @endphp
    </div>
</body>
</html>