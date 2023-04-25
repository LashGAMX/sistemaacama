<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/fq/volumetria/nitrogenoA/nitrogenoAPDF.css')}}">
    <title>Captura PDF</title>
</head>
<body>
    <br>
    <div id="contenidoCurva">
        @php
        echo @$procedimiento[0];
        @endphp
    </div>
    <br>

    <div class="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos">
            <thead>

                <tr>
                    <th class="nombreHeader" colspan="8">
                        Resultado de las muestras
                    </th>                    
                </tr>                

                <tr>
                    {{-- <th class="tableCabecera anchoColumna">pH</th> --}}
                    <th class="tableCabecera anchoColumna">No. de muestra</th>
                    <th class="tableCabecera anchoColumna">Vol. de muestra(mL)</th>
                    <th class="tableCabecera anchoColumna">Vol. de NaOH en estándares</th>
                    <th class="tableCabecera anchoColumna">Vol. de NaOH en muestra</th>
                    <th class="tableCabecera anchoColumna">Lectura de equipo (mg/L)</th>
                    <th class="tableCabecera anchoColumna">Concentraión (mg/L de N-NH3)</th>
                    <th class="tableCabecera anchoColumna">Observaciones</th>
                    <th class="anchoColumna"></th>
                    <th class="anchoColumna"></th>
                </tr>
            </thead>
    
            <tbody>
                @for ($i = 0; $i < @$loteDetalle->count() ; $i++)
                    <tr>
                        {{-- <td class="tableContent"><2</td> --}}
                        <td class="tableContent">
                            @if (@$loteDetalle[$i]->Control == 'Muestra Adicionada' || @$loteDetalle[$i]->Control == 'Duplicado' || @$loteDetalle[$i]->Control == 'Resultado')
                                {{@$loteDetalle[$i]->Folio_servicio}}
                            @else
                                {{@$loteDetalle[$i]->Control}}
                            @endif 
                        </td>
                        <td class="tableContent">{{@$loteDetalle[$i]->Vol_muestra}}</td>
                        <td class="tableContent">{{@$loteDetalle[$i]->Molaridad}}</td>
                        <td class="tableContent">{{@$loteDetalle[$i]->Factor_equivalencia}}</td>
                        <td class="tableContent">{{@$loteDetalle[$i]->Resultado}}</td>
                        <td class="tableContent"> @if (@$loteDetalle[$i]->Resultado > @$loteDetalle[$i]->Limite)
                            {{@$loteDetalle[$i]->Resultado}}
                        @else
                            <{{@$loteDetalle[$i]->Limite}}
                        @endif</td>
                        
                        {{-- <td class="tableContent">{{@$loteDetalle[$i]->Titulado_blanco}}</td>
                        <td class="tableContent">{{@$loteDetalle[$i]->Titulado_muestra}}</td>
                        <td class="tableContent">
                            @if (@$loteDetalle[$i]->Resultado > @$loteDetalle[$i]->Limite)
                                {{@$loteDetalle[$i]->Resultado}}
                            @else
                                {{@$loteDetalle[$i]->Limite}}
                            @endif
                        </td> --}}
                        <td class="tableContent">{{@$loteDetalle[$i]->Observacion}}</td>
                        <td class="tableContent">
                            @if (@$loteDetalle[$i]->Liberado == 1)
                                Liberado
                            @elseif(@$loteDetalle[$i]->Liberado == 0)
                                No liberado
                            @endif
                        </td>
                        <td class="tableContent">{{@$loteDetalle[$i]->Control}}</td>
                    </tr>                
                @endfor
            </tbody>        
        </table>  
    </div> 

    <br>
    
    <div class="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="">
            <tbody>                              
                <tr>
                    <td class="tableContent2">MILILITROS TITULADOS DEL BLANCO</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">{{@$valNitrogenoA->Gramos}}</td>
                </tr>

                <tr>
                    <td class="tableContent2">RESULTADO BLANCO</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">{{@$valNitrogenoA->Blanco}}</td>
                </tr>

                <tr>
                    <td class="tableContent2">MILILITROS 1 TITULADOS DE H2SO4</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">{{@$valNitrogenoA->Titulo1}}</td>
                </tr>

                <tr>
                    <td class="tableContent2">MILILITROS 2 TITULADOS DE H2SO4 2</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">{{@$valNitrogenoA->Titulo2}}</td>
                </tr>                

                <tr>
                    <td class="tableContent2">MILILITROS 3 TITULADOS DE H2SO4 3</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">{{@$valNitrogenoA->Titulo3}}</td>
                </tr>

                <tr>
                    <td class="tableContent2">RESULTADO MOLARIDAD REAL</td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="tableContent2">{{@$valNitrogenoA->Resultado}}</td>
                </tr>
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