<p id='header1'>
    INFORME DE RESULTADOS AGUA RESIDUAL <br> MUESTRA 
    @if (@$solicitud->Id_muestra == 1)
        INSTANTANEA
    @else
        COMPUESTA
    @endif
</p>

<div id="contenedorTabla">
    <table autosize="1" class="table table-borderless" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
        <tbody>            
                <tr>
                    <td  class="filasIzq bordesTabla anchoColumna7 bordeDer paddingTopBot">Empresa:</td>
                    <td  class="filasIzq bordesTabla fontBold anchoColumna82 bordeIzq">{{@$cliente->Nombres}}</td>
                    <td  class="filasIzq bordesTabla fontBold anchoColumna11 bordeFinal justificadoDer">{{@$cliente->RFC}}</td>                    
                </tr>

                <tr>                    
                    <td class="filasIzq bordesTabla anchoColumna7 bordeDerSinSup paddingTopBot">Dirección:</td>
                    <td class="filasIzq bordesTabla fontBold bordeIzqSinSup" colspan="2">{{@$direccion->Direccion}}</td>                    
                </tr>
                
                <tr>
                    <td class="filasIzq bordesTabla anchoColumna7 bordeDerSinSup" rowspan="6">Punto de muestreo:</td>
                    <td class="filasIzq bordesTabla fontBold anchoColumna60 bordeIzqDerSinSup" rowspan="6">{{@$puntoMuestreo[0]->Punto}}</td>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">Fecha de Muestreo:  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="fontBold"> {{ \Carbon\Carbon::parse(@$solicitud->Fecha_muestreo)->format('d/m/Y')}}</span>
                    </td>                    
                </tr>
                    
                <tr>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">Hora de muestreo: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        @if (@$solicitud->Id_muestra == 1)
                            <span class="fontBold">{{@$horaMuestreo}}</span>
                        @else
                            <span class="fontBold">COMPUESTA</span>
                        @endif
                    </td>
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">Fecha de Recepción: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="fontBold">{{\Carbon\Carbon::parse(@$modelProcesoAnalisis->Hora_entrada)->format('d/m/Y')}}</span>
                    </td>                    
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">Fecha de Emisión: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="fontBold">{{ \Carbon\Carbon::parse(@$modelProcesoAnalisis->Hora_entrada)->addDays(7)->format('d/m/Y')}}</span>
                    </td>
                </tr>                                      

                <tr>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">N° de Muestra: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;
                        <span class="fontBold">{{@$solicitud->Folio_servicio}}</span>
                    </td>                    
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla bordeConIzqFinalSup anchoColumna28 paddingTopBotInter">N° de Orden: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="fontBold">{{@$numOrden->Folio_servicio}}</span>
                    </td>                    
                </tr>

                <tr>
                    <td class="filasIzq bordesTabla anchoColumna11 bordeDer">Periodo de análisis:</td>
                    <td class="filasIzq bordesTabla bordeSinIzqFinalSup anchoColumna28 fontBold" colspan="3">DE {{\Carbon\Carbon::parse(@$modelProcesoAnalisis->Hora_entrada)->format('d/m/Y')}} 
                        A {{ \Carbon\Carbon::parse(@$fechaEmision)->format('d/m/Y')}}
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="fontBold">TITULO DE CONCESIÓN: {{@$titulo->Titulo}}</span>
                    </td>                    
                </tr>
        </tbody>         
    </table>  
</div>

<div id="contenedorTabla">
    <table autosize="1" class="table table-borderless paddingTop" id="tablaDatos" cellpadding="0" cellspacing="0" border-color="#000000" width="100%">
        <tbody>            
                <tr>
                    <td class="nombreHeader fontBold nom fontSize11 justificadorCentr">
                        @switch(@$solicitud->Id_norma)
                            @case(1)
                                DE ACUERDO A NOM-001-SEMARNAT-1996 
                                @if (@$solicitud->Id_muestra == 1)
                                    INSTANTANEA
                                @else
                                    COMPUESTA
                                @endif
                                 TIPO "{{@$tipoReporte->Tipo}}", {{@$tipoReporte->Cuerpo}} - {{$tipoReporte->Detalle}}  QUE ESTABLECE LOS LIMITES MAXIMOS PERMISIBLES DE CONTAMINANTES EN LAS DESCARGAS DE AGUAS RESIDUALES EN AGUAS Y <br> BIENES NACIONALES.
                                @break
                            @case(2)
                                DE ACUERDO A NOM-002-SEMARNAT-1996 PARA MUESTRA 
                                @if (@$solicitud->Id_muestra == 'COMPUESTA')
                                    COMPUESTA
                                @else
                                    INSTANTANEA
                                @endif                                
                                 <br> QUE ESTABLECE LOS LIMITES MAXIMOS PERMISIBLES DE CONTAMINANTES EN LAS DESCARGAS DE AGUAS RESIDUALES A LOS <br> SISTEMAS DE ALCANTARILLADO URBANO O MUNICIPAL.
                                @break                            
                            @case(4)                                
                                DE ACUERDO A NOM-003-SEMARNAT-1997 PARA MUESTRA 
                                @if (@$solicitud->Id_muestra == 'COMPUESTA')
                                    COMPUESTA
                                @else
                                    INSTANTANEA
                                @endif                                        
                                <br> QUE ESTABLECE LOS LIMITES MAXIMOS PERMISIBLES <br> DE CONTAMINANTES PARA LAS AGUAS RESIDUALES <br> TRATADAS QUE SE REUSEN EN SERVICIOS AL PÚBLICO                                
                                @break
                            @case(5)
                                DE ACUERDO A MODIFICACIÓN A LA NORMA OFICIAL MEXICANA NOM-127-SSA1-1994, PARA MUESTRA 
                                @if (@$solicitud->Id_muestra == 'COMPUESTA')
                                    COMPUESTA <br>
                                @else
                                    INSTANTANEA <br>
                                @endif
                                SALUD AMBIENTAL. AGUA PARA USO Y CONSUMO HUMANO. LÍMITES PERMISIBLES DE CALIDAD Y TRATAMIENTOS A QUE DEBE <br> SOMETERSE EL AGUA PARA SU POTABILIZACION.
                                @break
                            @case(7)
                                DE ACUERDO A NORMA OFICIAL MEXICANA NOM-201-SSA1-2015 PARA MUESTRA 
                                @if (@$solicitud->Id_muestra == 'COMPUESTA')
                                    COMPUESTA <br>
                                @else
                                    INSTANTANEA <br>
                                @endif
                                PRODUCTOS Y SERVICIOS. AGUA Y HIELO PARA CONSUMO HUMANO, ENVASADOS A GRANEL. ESPECIFICACIONES SANITARIAS.
                                @break
                        @endswitch
                    </td>
                </tr>                
        </tbody>         
    </table>  
</div>