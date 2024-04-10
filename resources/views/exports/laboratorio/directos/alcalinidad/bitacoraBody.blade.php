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

    <div class="procedimiento">
        @php
  echo $plantilla[0]->Texto; 
        @endphp
    </div>
    <br>

    <div id="contenedorTabla">
    <table autosize="1" class="tabla2" border="0">
        <thead> 
            <tr>
                <th class="tableCabecera anchoColumna">No. De muestra</th>
                <th class="tableCabecera anchoColumna">Volumen de muestra (mL)</th>
                <th class="tableCabecera anchoColumna">Volumen titulante</th>
                <th class="tableCabecera anchoColumna">
                    @switch($model[0]->Id_parametro)
                        @case(28)
                            Alcalinidad a la fenolftaleina mg/L
                            @break
                        @case(29)
                            Alcalinidad a la anaranjado mg/L
                            @break
                        @default
                            
                    @endswitch
                </th>
                <th class="tableCabecera anchoColumna">Observaciones</th>
                <th class="tableCabecera anchoColumna"></th>
                <th class="tableCabecera anchoColumna"></th>
                
            </tr> 
        </thead>
        <tbody>
            @foreach ($model as $item)
                <tr>
                    <td class="tableContent">{{ $item->Codigo }}</td>
                    <td class="tableContent">100</td> 
                    <td class="tableContent">
                        @php
                            if(@$item->Resultado <= @$item->Limite){
                                echo "0.".@$item->Resultado;
                            }else{
                                @$ultimoDigito = $item->Resultado % 10;
                                @$primerDigito = floor($item->Resultado / (10 ** (strlen($item->Resultado) - 1)));
                                echo $primerDigito.".".$ultimoDigito;
                            }
                        @endphp
                    </td> 
                    <td class="tableContent">
                        @if (@$item->Resultado <= @$item->Limite)
                        < {{@$item->Limite}}
                        @else
                        {{round(@$item->Resultado,2)}}
                        @endif
                    </td>
                    <td class="tableContent">{{ $item->Observacion }}</td>
                    @if ($item->Liberado != NULL)
                        <td class="tableContent">LIBERADO</td>
                    @else
                        <td class="tableContent">NO LIBERADO</td>
                    @endif
                        <td class="tableContent">{{ $item->Control }}<td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</body>

</html>