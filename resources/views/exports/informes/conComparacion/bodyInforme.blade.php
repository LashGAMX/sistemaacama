<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/informes/conComparacion/conComparacion.css')}}">
    <title>Informe Sin Comparación</title>
</head>
<body>
    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless paddingTop1" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
            <thead>
                <tr>
                    <td class="tableCabecera bordesTablaBody justificadoCentr" width="40%" height="30">PARAMETRO &nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr">&nbsp;UNIDAD&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr">&nbsp;METODO DE PRUEBA&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr">&nbsp;PROMEDIO DIARIO&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr">&nbsp;PROMEDIO DIARIO&nbsp;&nbsp;</td>
                    <td class="tableCabecera bordesTablaBody justificadoCentr">&nbsp;CONCENTRACION PROMEDIO MENSUAL PONDERADO&nbsp;&nbsp;</td>                    
                </tr>
            </thead>
    
            <tbody>
                @for ($i = 0; $i < 8/* @$solicitudParametrosLength */ ; $i++)
                    <tr>
                        <td class="tableContent bordesTablaBody" height="25">{{@$solicitudParametros[$i]->Parametro}}</td>
                        <td class="tableContent bordesTablaBody">{{@$solicitudParametros[$i]->Unidad}}</td>
                        <td class="tableContent bordesTablaBody">{{@$solicitudParametros[$i]->Metodo_prueba}}</td>
                        <td class="tableContent bordesTablaBody">PROMEDIO DIARIO</td>
                        <td class="tableContent bordesTablaBody">PROMEDIO DIARIO</td>
                        <td class="tableContent bordesTablaBody">RESULTADO</td>
                    </tr>                
                @endfor
            </tbody>        
        </table>  
    </div> 
</body>
</html>