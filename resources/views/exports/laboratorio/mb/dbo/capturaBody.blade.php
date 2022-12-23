<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/mb/dbo/dboPDF.css')}}">
    <title>Captura PDF</title>
</head>
<body>   
    <p id='curvaProcedimiento'>Procedimiento</p> 

    <div id="contenidoCurva">
        <?php echo html_entity_decode(@$bitacora->Texto);?>
    </div>

    <table cellpadding="0" cellspacing="0" border-color="#000000">
        <thead>  
            <tr>
                <th class="nombreHeader" colspan="13">
                    Resultado de las muestras
                </th>
            </tr>
            
            <tr>
                <th class="nombreHeader bordesTabla" style="font-size: 12px">
                    No. de muestra
                </th>

                <th class="nombreHeader bordesTabla" style="font-size: 8px">
                    Vol. de la Muestra (ml)
                </th>

                <th class="nombreHeader bordesTabla" style="font-size: 8px">
                    % de dilucion expresado en decimales
                </th>

                <th class="nombreHeader bordesTabla" style="font-size: 8px">
                    NO. DE BOTELLA INICIAL
                </th>

                <th class="nombreHeader bordesTabla" style="font-size: 7px">
                    OXIGENO DISUELTO INICIAL
                </th>

                <th class="nombreHeader bordesTabla" style="font-size: 7px">
                    NO. BOTELLA FINAL
                </th>

                <th class="nombreHeader bordesTabla" style="font-size: 7px">
                    OXIGENO DISUELTO AL 5to. DIA
                </th>

                <th class="nombreHeader bordesTabla" style="font-size: 8px">
                    PH INICIAL
                </th>

                <th class="nombreHeader bordesTabla" style="font-size: 8px">
                    PH FINAL
                </th>

                <th class="nombreHeader bordesTabla" style="font-size: 8px">
                    DBO5 mg/L
                </th>

                <th class="nombreHeader bordesTabla" style="font-size: 8px">
                    Observaciones
                </th>

                <th class="nombreHeader">
                    
                </th>

                <th class="nombreHeader">
                    
                </th>
            </tr>                        
        </thead>
        
        <tbody>

        @for ($i = 0; $i < @$dataLength ; $i++)
            <tr>
                <td class="contenidoBody bordesTabla">
                    @if (@$data[$i]->Id_control == 5 || @$data[$i]->Id_control == 4 || @$data[$i]->Id_control == 7)
                        {{@$data[$i]->Control}}
                    @else
                        {{@$data[$i]->Codigo}}
                    @endif 
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$data[$i]->Vol_muestra}}
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$data[$i]->Dilucion}}
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$data[$i]->Botella_od}}
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$data[$i]->Odi}}
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$data[$i]->Botella_final}}
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$data[$i]->Odf}}
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$data[$i]->Ph_inicial}}
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$data[$i]->Ph_final}}
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$limites[$i]}}
                </td>

                <td class="contenidoBody bordesTabla">
                    {{@$data[$i]->Observacion}}
                </td>

                <td class="contenidoBody">
                    @if (@$data[$i]->Liberado == 1)
                        Liberado
                    @elseif(@$data[$i]->Liberado == 0)
                        No liberado
                    @endif
                </td>

                <td class="contenidoBody">
                    {{@$data[$i]->Control}}
                </td>           
            </tr>  
        @endfor                                  
        </tbody>
    </table>

    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos" style="width: 100%">
            <tbody>
                <tr>
                    <th></th>

                    <th></th>
                    
                    <th class="tableContent nombreHeaderBold" colspan="2">
                        AIREAR APROX. 1 HORA
                    </th>                   

                    <th></th>                                       

                    <th class="tableContent nombreHeaderBold" colspan="2">
                        ESTANDAR BIT RE-12-001-1A-13
                    </th>                    
                </tr>

                <tr>
                    <td class="tableContent nombreHeaderBold">
                        Cantidad de agua de dilucion
                    </td>

                    <td class="tableContent">{{$detalleLote->Cant_dilucion}}</td>
                    
                    <td></td>

                    <td>
                        <span class="tableContent nombreHeaderBold">DE</span> <span class="tableContent">{{$detalleLote->De}}</span>
                    </td>

                    <td>
                        <span class="tableContent nombreHeaderBold">A</span> <span class="tableContent">{{$detalleLote->A}}</span>
                    </td>

                    <td></td>

                    <td>
                        <span class="tableContent nombreHeaderBold">PAG</span> <span class="tableContent">{{$detalleLote->Pag}}</span>
                    </td>

                    <td>
                        <span class="tableContent nombreHeaderBold">N.</span> <span class="tableContent">{{$detalleLote->N}}</span>
                    </td>                
                </tr>                

                <tr>                
                    <td>
                        <span class="tableContent nombreHeaderBold">&nbsp;Disoluciones preparadas el día: </span> <span class="tableContent">{{$detalleLote->Dilucion}}</span>
                    </td>
                </tr>
            </tbody>                      
        </table>  
    </div>  

    <br>
    <br>
    
    <div id="contenidoCurva">
        <span id="curvaProcedimiento">Validación</span>    
        <?php echo html_entity_decode(@$textoProcedimiento[1]);?>
    </div>
</body>
</html>