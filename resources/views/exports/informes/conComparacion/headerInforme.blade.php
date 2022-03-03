<p id='header1'>
    INFORME DE RESULTADOS AGUA RESIDUAL
</p>

<div id="contenedorTabla">
    <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
        <tbody>            
                <tr>
                    <td class="filasIzq bordesTabla anchoColumna7 bordeDer paddingTopBot" style="background-color: aqua">Empresa:</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzq" style="background-color: red" width="50%">{{@$cliente->Nombres}}</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzq" style="background-color: green">&nbsp;</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzq" style="background-color: blue">&nbsp;</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzq" style="background-color: gray">&nbsp;</td>
                    {{-- <td class="filasIzq bordesTabla fontBold anchoColumna82 bordeIzq" colspan="4" style="background-color: red">{{@$cliente->Nombres}}</td> --}}
                    <td class="filasIzq bordesTabla fontBold anchoColumna11 bordeFinal justificadoDer" style="background-color: orange">{{@$cliente->RFC}}</td>                    
                </tr>

                <tr>                    
                    <td class="filasIzq bordesTabla anchoColumna7 bordeDerSinSup paddingTopBot">Dirección:</td>                    
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup">{{@$direccion->Direccion}}</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup">&nbsp;</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup">&nbsp;</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup">&nbsp;</td>
                    <td class="filasIzq bordesTabla anchoColumna11 fontBold bordeIzqSinSup justificadoDer">TITULO DE CONCESIÓN: {{@$puntoMuestreo->Titulo_consecion}}</td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla anchoColumna7 bordeDerSinSup paddingTopBot">Punto de muestreo:</td>
                    <td class="filasIzq bordesTabla fontBold anchoColumna60 soloBordeInf">{{@$puntoMuestreo->Punto}}</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup">&nbsp;</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup">&nbsp;</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup">&nbsp;</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup bordeSinIzqFinalSup">&nbsp;</td>
                </tr>
                
                <tr>                    
                    <td class="pruebaAncho filasIzq bordesTabla soloBordeDer" colspan="2" rowspan="9">&nbsp;</td>
                    <td class="filasIzq bordesTabla soloBordeDer paddingTopBotInter">GASTO PROMEDIO</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqDerSinSup justificadorCentr">GASTO</td>
                    <td class="filasIzq bordesTabla bordeIzqSinSup fontBold bordeIzqDerSinSup justificadorCentr">UNIDAD</td>                                        
                    <td class="filasIzq bordesTabla soloBordeDer fontBold {{-- anchoColumna28 --}} justificadorCentr" rowspan="9" width="20%">NORMA</td>                    
                </tr>
                    
                <tr>
                    <td class="filasIzq bordesTabla soloBordeSup anchoColumna28 paddingTopBotInter">GASTO LPS</td>
                    <td class="filasIzq bordesTabla bordeDerSinSup anchoColumna28 paddingTopBotInter justificadorCentr">GASTO LPS</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter justificadorCentr">GASTO LPS</td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla soloBordeSup anchoColumna28 paddingTopBotInter">FECHA DE MUESTREO:</td>
                    <td class="filasIzq bordesTabla bordeDerSinSup anchoColumna28 paddingTopBotInter justificadorCentr">{{@$solicitud->Fecha_muestreo}}</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter justificadorCentr">{{@$solicitud->Fecha_muestreo}}</td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla soloBordeSup anchoColumna28 paddingTopBotInter">FECHA DE RECEPCION:</td>
                    <td class="filasIzq bordesTabla bordeDerSinSup anchoColumna28 paddingTopBotInter justificadorCentr">{{@$solicitud->Fecha_muestreo}}</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter justificadorCentr">{{@$solicitud->Fecha_muestreo}}</td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla soloBordeSup anchoColumna28 paddingTopBotInter">FECHA DE EMISIÓN:</td>
                    <td class="filasIzq bordesTabla bordeDerSinSup anchoColumna28 paddingTopBotInter justificadorCentr">{{@$solicitud->Fecha_muestreo}}</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter justificadorCentr">{{@$solicitud->Fecha_muestreo}}</td>
                </tr>
                
                <tr>
                    <td class="filasIzq bordesTabla soloBordeSup anchoColumna28 paddingTopBotInter">PERIODO DE ANALISIS:</td>
                    <td class="filasIzq bordesTabla bordeDerSinSup anchoColumna28 paddingTopBotInter justificadorCentr">DE {{@$solicitud->Fecha_muestreo}} A {{@$solicitud->Fecha_muestreo}}</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter justificadorCentr">DE {{@$solicitud->Fecha_muestreo}} A {{@$solicitud->Fecha_muestreo}}</td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla soloBordeSup anchoColumna28 paddingTopBotInter">TIPO DE MUESTREO:</td>
                    <td class="filasIzq bordesTabla bordeDerSinSup anchoColumna28 paddingTopBotInter justificadorCentr">TIPO</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter justificadorCentr">TIPO</td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla soloBordeSup anchoColumna28 paddingTopBotInter">N° DE MUESTRA:</td>
                    <td class="filasIzq bordesTabla bordeDerSinSup anchoColumna28 paddingTopBotInter justificadorCentr">{{@$solicitud->Folio_servicio}}</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter justificadorCentr">{{@$solicitud->Folio_servicio}}</td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla bordeSupDer anchoColumna28 paddingTopBotInter">N° DE ORDEN:</td>
                    <td class="filasIzq bordesTabla soloBordeDer anchoColumna28 paddingTopBotInter justificadorCentr">{{@$numOrden->Folio_servicio}}</td>
                    <td class="filasIzq bordesTabla bordeSupDer anchoColumna28 paddingTopBotInter justificadorCentr">{{@$numOrden->Folio_servicio}}</td>
                </tr>                
        </tbody>         
    </table>  
</div>