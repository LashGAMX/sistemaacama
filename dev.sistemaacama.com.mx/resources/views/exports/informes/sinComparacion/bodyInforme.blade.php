<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/informes/sinComparacion/sinComparacion.css')}}">
    <title>Informe Sin Comparación</title>
</head>
<body>
    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <thead>
                <tr>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" height="30">PARAMETRO &nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="16.6%">&nbsp;METODO DE PRUEBA&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="10.6%">&nbsp;UNIDAD&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr">&nbsp;CONCENTRACION CUANTIFICADA&nbsp;&nbsp;</td>                    
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="25.6%">ANALISTA</td>
                </tr>
            </thead>
    
            <tbody>
                @foreach ($model as $item)
                <tr>
                    <td class="tableContent bordesTablaBody" height="25">{{@$item->Parametro}}<sup>{{$item->Simbologia}}</sup></td>
                    <td class="tableContent bordesTablaBody">{{@$item->Clave_metodo}}</td>
                    <td class="tableContent bordesTablaBody">{{@$item->Unidad}}</td>
                    <td class="tableContent bordesTablaBody">
                        {{$item->Resultado2}}
                    </td>
                    <td class="tableContent bordesTablaBody">{{@$item->iniciales}}</td>
                </tr>   
                @endforeach

            </tbody>        
        </table>  
    </div> 
</body>
</html> 