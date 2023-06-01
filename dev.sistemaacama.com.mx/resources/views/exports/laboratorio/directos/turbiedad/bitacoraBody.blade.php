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
        echo @$plantilla->Texto;
        @endphp
    </div>
    <br>
    <div id="contenedorTabla">


        <br>

        <table autosize="1" class="tabla2" border="0">
            <thead> 
                <tr>
                    <th class="tableCabecera anchoColumna">No. De muestra</th>
                    <th class="tableCabecera anchoColumna">Vol. Muestra</th>
                    <th class="tableCabecera anchoColumna">Turb 1</th>
                    <th class="tableCabecera anchoColumna">Turb 2</th>
                    <th class="tableCabecera anchoColumna">Turb 3</th>
                    <th class="tableCabecera anchoColumna">Promedio de lecturas</th>
                    <th class="tableCabecera anchoColumna">turbiedad UTN</th>
                    <th class="tableCabecera anchoColumna">Observaciones</th>
                    <th class="tableCabecera anchoColumna"></th>
                    <th class="tableCabecera anchoColumna"></th>
                <th class="tableCabecera anchoColumna">
            </thead>
            <tbody>
                @foreach ($model as $item)
                    <tr>
                        <td class="tableContent">{{ $item->Codigo }}</td>
                        <td class="tableContent">{{ $item->Vol_muestra }}</td>
                        <td class="tableContent">{{ $item->Lectura1 }}</td>
                        <td class="tableContent">{{ $item->Lectura2 }}</td>
                        <td class="tableContent">{{ $item->Lectura3 }}</td>
                        <td class="tableContent">{{ round($item->Promedio,2) }}</td>
                        @php
                            $resultado = "";
                            if($item->Resultado <= $item->Limite){
                                    $resultado = "< ". $item->Limite;
                                } else{
                                    $resultado = round($item->Resultado,1);
                                }
                        @endphp
                               
                        <td class="tableContent">{{ $resultado }}</td>
                        <td class="tableContent">{{ $item->Observacion }}</td>
                        @if ($item->Liberado != NULL)
                            <td class="tableContent">LIBERADO</td>
                        @else
                            <td class="tableContent">NO LIBERADO</td>
                        @endif
                        <td class="tableContent">{{ $item->Control }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div>
            <p><span style="font-family: arial, helvetica, sans-serif; font-size: 10px;">CALCULOS:</span></p>
<p><span style="font-family: arial, helvetica, sans-serif; font-size: 10px;">Leer la turbiedad directamente de la escala del instrumento.</span></p>
<p><span style="font-family: arial, helvetica, sans-serif; font-size: 10px;">ESTANDAR DE CONTROL ( CRITERIO DE ACEPTACION 80-120 %)</span></p>
<p><span style="font-family: arial, helvetica, sans-serif; font-size: 10px;">% RECUPERACI&Oacute;N = (C1/C2)*100 EN DONDE:</span></p>
<dl>
<dd>
<table width="214" cellspacing="0" cellpadding="0"><colgroup><col width="32"> <col width="35"> <col width="147"> </colgroup>
<tbody>
<tr valign="top">
<td width="32" height="9">
<p><span style="font-family: arial, helvetica, sans-serif; font-size: 10px;">%R</span></p>
</td>
<td width="35">
<p><span style="font-family: arial, helvetica, sans-serif; font-size: 10px;">=</span></p>
</td>
<td width="147">
<p><span style="font-family: arial, helvetica, sans-serif; font-size: 10px;">PORCENTAJE DE RECUPERACI&Oacute;N DEL EST&Aacute;NDAR</span></p>
</td>
</tr>
<tr valign="top">
<td width="32" height="13">
<p><span style="font-family: arial, helvetica, sans-serif; font-size: 10px;">C1</span></p>
</td>
<td width="35">
<p><span style="font-family: arial, helvetica, sans-serif; font-size: 10px;">=</span></p>
</td>
<td width="147">
<p><span style="font-family: arial, helvetica, sans-serif; font-size: 10px;">CONCENTRACI&Oacute;N OBTENIDA (mg/L)</span></p>
</td>
</tr>
<tr valign="top">
<td width="32" height="13">
<p><span style="font-family: arial, helvetica, sans-serif; font-size: 10px;">C2</span></p>
</td>
<td width="35">
<p><span style="font-family: arial, helvetica, sans-serif; font-size: 10px;">=</span></p>
</td>
<td width="147">
<p><span style="font-family: arial, helvetica, sans-serif; font-size: 10px;">CONCENTRACI&Oacute;N TE&Oacute;RICA (mg/L)</span></p>
</td>
</tr>
<tr valign="top">
<td width="32" height="9">
<p><span style="font-family: arial, helvetica, sans-serif; font-size: 10px;">100</span></p>
</td>
<td width="35">
<p><span style="font-family: arial, helvetica, sans-serif; font-size: 10px;">=</span></p>
</td>
<td width="147">
<p><span style="font-family: arial, helvetica, sans-serif; font-size: 10px;">FACTOR DE CONVERSI&Oacute;N A PORCENTAJE</span></p>
</td>
</tr>
</tbody>
</table>
</dd>
</dl>
<p><span style="font-family: arial, helvetica, sans-serif; font-size: 10px;">DPR (DIFERENCIA PORCENTUAL RELATIVA) ( CRITERIO DE ACEPTACION &lt;20%) DPR= ǀ ( ( C1-C2 ) ǀ / ( ( C1+C2 ) / 2 ) ) * 100</span></p>
<table width="213" cellspacing="0" cellpadding="0"><colgroup><col width="49"> <col width="29"> <col width="134"> </colgroup>
<tbody>
<tr valign="top">
<td width="49" height="9">
<p><span style="font-family: arial, helvetica, sans-serif; font-size: 10px;">EN DONDE :</span></p>
</td>
<td colspan="2" width="164">
<p>&nbsp;</p>
</td>
</tr>
<tr valign="top">
<td width="49" height="13">
<p><span style="font-family: arial, helvetica, sans-serif; font-size: 10px;">DPR</span></p>
</td>
<td width="29">
<p><span style="font-family: arial, helvetica, sans-serif; font-size: 10px;">=</span></p>
</td>
<td width="134">
<p><span style="font-family: arial, helvetica, sans-serif; font-size: 10px;">DIFERENCIA PORCENTUAL RELATIVA</span></p>
</td>
</tr>
<tr valign="top">
<td width="49" height="13">
<p><span style="font-family: arial, helvetica, sans-serif; font-size: 10px;">C1</span></p>
</td>
<td width="29">
<p><span style="font-family: arial, helvetica, sans-serif; font-size: 10px;">=</span></p>
</td>
<td width="134">
<p><span style="font-family: arial, helvetica, sans-serif; font-size: 10px;">CONCENTRACI&Oacute;N DE LA PRIMER MUESTRA</span></p>
</td>
</tr>
<tr valign="top">
<td width="49" height="13">
<p><span style="font-family: arial, helvetica, sans-serif; font-size: 10px;">C2</span></p>
</td>
<td width="29">
<p><span style="font-family: arial, helvetica, sans-serif; font-size: 10px;">=</span></p>
</td>
<td width="134">
<p><span style="font-family: arial, helvetica, sans-serif; font-size: 10px;">CONCENTRACI&Oacute;N DE LA SEGUNDA MUESTRA</span></p>
</td>
</tr>
<tr valign="top">
<td width="49" height="9">
<p><span style="font-family: arial, helvetica, sans-serif; font-size: 10px;">100</span></p>
</td>
<td width="29">
<p><span style="font-family: arial, helvetica, sans-serif; font-size: 10px;">=</span></p>
</td>
<td width="134">
<p><span style="font-family: arial, helvetica, sans-serif; font-size: 10px;">FACTOR DE CONVERSI&Oacute;N A PORCENTAJE</span></p>
</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
        </div>
        
</body>

</html>
