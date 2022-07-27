<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/laboratorio/fq/capturaPDF.css')}}">
    <title>Captura PDF</title>
</head>
<body>    
    
    <p id='curvaProcedimiento'>Procedimiento</p>

    <div id="contenidoCurva">
        <?php echo html_entity_decode($textoProcedimiento->Texto);?>
    </div>

    <div id="contenedorTabla">
        <table autosize="1" class="table table-borderless" id="tablaDatos">
            <thead>
                <tr>
                    <td id="tableCabecera">No. Matraz &nbsp;</td>
                    <td id="tableCabecera">&nbsp;Masa cte. 1&nbsp;&nbsp;</td>
                    <td id="tableCabecera">&nbsp;Masa cte. 2&nbsp;&nbsp;</td>                
                </tr>
            </thead>
    
            <tbody>
                <td>PRUEBA</td>
                <td>PRUEBA</td>
                <td>PRUEBA</td>
               <!--  @for ($i = 0; $i < $datosLength ; $i++)
                    <tr>
                        <td id="tableContent">{{$datos[$i]->Folio_servicio}}</td>
                        <td id="tableContent">{{$datos[$i]->Vol_muestra}}</td>
                        <td id="tableContent">{{$loteModel->Ph}}</td>                        
                    </tr>                
                @endfor   -->                      
            </tbody>        
        </table>  
    </div>    

    <br>

    <div class="contenedorPadre">
        <div class="contenedorHijo1">            
            <span class="cabeceraStdMuestra"> Enfriado de matraces en desecador para masa cte. <br> </span>
            <span class="bodyStdMuestra">Criterio de aceptación para Std ctrl 95-105%. Fórmula; Recuperación(%) = C1/C2x100. Donde: C1 = Concentración leída.
                C2 = Concentración Real.
            </span>
        </div>

        <div class="contenedorHijo1">
            <span class="cabeceraStdMuestra">Secado de cartuchos <br> </span>                                    
            <span class="bodyStdMuestra">DPR (DIFERENCIAL PORCENTUAL RELATIVA) MUESTRA DUPLICADA: La DPR de cada analito obtenido entre la muestra y la
                duplicada debe ser: < 20%. Fórmula: DPR = (|C1-C2|)/[(C1+C2)*100]. Donde: C1 - Concentración de la primera muestra.
                C2 - Concentración de la segunda muestra (muestra duplicada).
            </span>                   
        </div>

        <div class="contenedorHijo1">
            <span class="cabeceraStdMuestra">Tiempo de reflujo <br> </span>                    
            <span class="bodyStdMuestra">Criterio de Aceptación para MA  85 - 115%. Fórmula: Recuperación n = [Cs(V+V1)-(Cr*V1)/Ca*V]100%. Donde: V = Volúmen del
                estándar usado para la muestra adicionada. Ca = Concentración del estándar. V1 = Volúmen de la muestra problema usada 
                para la muestra adicionada. Cr = Concentración de muestra problema. Cs = Concentración de la muestra adicionada. Recuperación
                n: porcentaje del analito adicionado que es medido.
            </span>                   
        </div>
    </div>       
    
    <div>
        <span class="cabeceraStdMuestra">
            Enfriado de matraces en
        </span>

        <span>
            <table autosize="1" class="table table-borderless" id="tablaEnfriado">
                <thead>
                    <tr id="tableCabecera">HORA</tr>
                    <tr>
                        <td id="tableCabecera">Entrada &nbsp;</td>
                        <td id="tableCabecera">&nbsp;Salida&nbsp;&nbsp;</td>                        
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </span>
    </div>

    <br>        
</body>
</html>