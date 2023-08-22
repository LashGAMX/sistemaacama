<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/informes/conComparacion/conComparacion.css')}}">
    <title>Informe Sin Comparación.</title>
</head>

<body>
    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0" cellspacing="0"
            border-color="#000000" width="100%">
            <thead>
                <tr>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="47%">PARAMETRO &nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="7%">&nbsp;UNIDAD&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="14%">&nbsp;METODO DE
                        PRUEBA&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="9.45%">&nbsp;PROMEDIO DIARIO&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="9.45%">&nbsp;PROMEDIO DIARIO&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr">&nbsp;CONCENTRACION PROMEDIO MENSUAL
                        PONDERADO&nbsp;&nbsp;</td>
                </tr>
            </thead>

            <tbody>
                @php
                    $cont = 0;
                @endphp
                @foreach ($model1 as $item)
                    <tr>
                        <td class="tableContentLeft bordesTablaBody">{{$item->Parametro}}<sup></sup></td>
                        <td class="tableContent bordesTablaBody">{{$item->Unidad}}</td>
                        <td class="tableContent bordesTablaBody">{{$item->Clave_metodo}}</td>
                        <td class="tableContent bordesTablaBody">
                            {{$item->Resultado2}}
                        </td>                    
                        
                        <td class="tableContent bordesTablaBody">
                            {{$model2[$cont]->Resultado2}}
                        </td>

                        <td class="tableContent bordesTablaBody">
                            {{$item->Resultado2 / $model2[$cont]->Resultado2}} 
                        </td>
                    </tr>
                    @php $cont++; @endphp
                @endforeach
            </tbody>
        </table>
    </div>
    
</body>

</html>