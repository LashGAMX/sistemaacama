<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/fq/espectro/materiaF/materiaFPDF.css')}}">
    <title>Captura PDF</title>
</head>
<body>
    <p id='curvaProcedimiento'>Procedimiento</p>

    <div id="contenidoCurva">
        <?php echo html_entity_decode($textoProcedimiento->Texto);?>
    </div>

    <br>

    <div class="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos">
            <thead>

                <tr>
                    <th class="nombreHeader" colspan="5">
                        Resultado de las muestras
                    </th>                    
                </tr>                

                <tr>
                    <th class="tableCabecera anchoColumna">No. de muestra</th>
                    <th class="tableCabecera anchoColumna">MATERIA FLOTANTE</th>                    
                    <th class="tableCabecera anchoColumna">Observaciones</th>
                    <th class="anchoColumna"></th>
                    <th class="anchoColumna"></th>
                </tr>
            </thead>
    
            <tbody>
                @for ($i = 0; $i < @$dataLength ; $i++)
                    <tr>
                        <td class="tableContent">{{@$data[$i]->Folio_servicio}}</td>
                        <td class="tableContent">PRUEBA</td>
                        <td class="tableContent">{{@$observaciones[$i]->Observaciones}}</td>
                        <td class="tableContent">LIBERADO</td>
                        <td class="tableContent">{{@$data[$i]->Descripcion}}</td>                       
                    </tr>                
                @endfor
            </tbody>        
        </table>  
    </div>
</body>
</html>