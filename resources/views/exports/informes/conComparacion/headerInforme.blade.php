<p id='header1'>
    INFORME DE RESULTADOS AGUA RESIDUAL
</p>

<div id="contenedorTabla">
    <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
        <tbody>            
                <tr>
                    <td class="filasIzq bordesTabla anchoColumna7 bordeDer paddingTopBot">Empresa:</td>
                    <td class="filasIzq bordesTabla fontBold anchoColumna82 bordeIzq" colspan="4">{{@$cliente->Nombres}}</td>
                    <td class="filasIzq bordesTabla fontBold anchoColumna11 bordeFinal justificadoDer">{{@$cliente->RFC}}</td>                    
                </tr>

                <tr>                    
                    <td class="filasIzq bordesTabla anchoColumna7 bordeDerSinSup paddingTopBot">Dirección:</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup" colspan="4">{{@$direccion->Direccion}}</td>
                    <td class="filasIzq bordesTabla anchoColumna11 fontBold bordeFinal justificadoDer">TITULO DE CONCESIÓN: {{@$puntoMuestreo->Titulo_consecion}}</td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla anchoColumna7 bordeDerSinSup paddingTopBot">Punto de muestreo:</td>
                    <td class="filasIzq bordesTabla fontBold anchoColumna60 bordeIzqDerSinSup" colspan="5">{{@$puntoMuestreo->Punto}}</td>
                </tr>
                
                <tr>
                    <td class="filasIzq bordesTabla anchoColumna7 bordeIzqSinSup colorBlanco" colspan="2" rowspan="9">h</td>                    
                    <td class="filasIzq bordesTabla bordeIzqFinalConSup anchoColumna28 paddingTopBotInter">GASTO PROMEDIO</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup justificadoCentr">GASTO</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup justificadoCentr">UNIDAD</td>                                        
                    <td class="filasIzq bordesTabla fontBold anchoColumna28 bordeIzqDerSinSup justificadoCentr" rowspan="9">NORMA</td>                    
                </tr>
                    
                <tr>
                    <td class="filasIzq bordesTabla bordeIzqFinalConSup anchoColumna28 paddingTopBotInter">GASTO LPS</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">GASTO LPS</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">GASTO LPS</td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla bordeIzqFinalConSup anchoColumna28 paddingTopBotInter">FECHA DE MUESTREO:</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">{{@$solicitud->Fecha_muestreo}}</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">{{@$solicitud->Fecha_muestreo}}</td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla bordeIzqFinalConSup anchoColumna28 paddingTopBotInter">FECHA DE RECEPCION:</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">{{@$solicitud->Fecha_muestreo}}</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">{{@$solicitud->Fecha_muestreo}}</td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla bordeIzqFinalConSup anchoColumna28 paddingTopBotInter">FECHA DE EMISIÓN:</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">{{@$solicitud->Fecha_muestreo}}</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">{{@$solicitud->Fecha_muestreo}}</td>
                </tr>
                
                <tr>
                    <td class="filasIzq bordesTabla bordeIzqFinalConSup anchoColumna28 paddingTopBotInter">PERIODO DE ANALISIS:</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">DE {{@$solicitud->Fecha_muestreo}} A {{@$solicitud->Fecha_muestreo}}</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">DE {{@$solicitud->Fecha_muestreo}} A {{@$solicitud->Fecha_muestreo}}</td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla bordeIzqFinalConSup anchoColumna28 paddingTopBotInter">TIPO DE MUESTREO:</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">TIPO</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">TIPO</td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla bordeIzqFinalConSup anchoColumna28 paddingTopBotInter">N° DE MUESTRA:</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">{{@$solicitud->Folio_servicio}}</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">{{@$solicitud->Folio_servicio}}</td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla bordeIzqFinalConSup anchoColumna28 paddingTopBotInter">N° DE ORDEN:</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">{{@$numOrden->Folio_servicio}}</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">{{@$numOrden->Folio_servicio}}</td>
                </tr>                
        </tbody>         
    </table>  
</div>