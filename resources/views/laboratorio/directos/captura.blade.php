@extends('voyager::master')

@section('content')

@section('page_header')
<h6 class="page-title">
    <i class="voyager-window-list"></i>
    Captura Directos.
</h6>

@stop
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="exampleFormControlSelect1">Fórmula tipo</label>
                <select class="form-control" name="formulaTipo" id="formulaTipo">
                    <option value="0">Sin seleccionar</option>
                    @foreach ($parametro as $parametros)
                    <option value={{$parametros->Id_parametro}}>({{$parametros->Id_parametro}}) {{$parametros->Parametro}} ({{$parametros->Tipo_formula}})</option> 
                    @endforeach
                </select>
            </div>
        </div>


        <div class="col-md-3">
            <div class="form-group">
                <label for="">Fecha análisis</label>
                <input type="date" class="form-control" id="fechaAnalisis">
            </div>
        </div>
        <div class="col-md-3">
            <button class="btn btn-success" onclick="getLote()" id="btnBuscar">Buscar</button>
        </div>
        <div class="col-md-3">
            <input id="idLote" hidden>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <div id="divLote">
                        <table class="table" id="">
                            <thead>
                                <tr>
                                    <th>Folio</th>
                                    <th>Fecha lote</th>
                                    <th>Total asignados</th>
                                    <th>Total liberados</th>
                                    <th>Opc</th>
                                </tr>
                            </thead>
                            <!-- <button class="btn btn-success" id="btnImprimir"><i class="fas fa-file-download"></i></button> -->
                            <!-- <button class="btn btn-success" id="btnImprimir" onclick="imprimir();"><i class="fas fa-file-download"></i></button> -->
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-2">
                            <p class="">Información global</p>
            
                        </div>
                            <div class="col-md-2">
                                <p class="">Información</p>
                                
                            </div>
                            <div class="col-md-3">
                                <textarea id="observacion"></textarea>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-success" id="enviar" onclick="enviarObsGeneral()">enviar</button>
                            </div>
                        </div>
                    </div>
                
                </div>
            </div>

            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-1">
                        <button class="btn btn-secondary" id="btnLiberar">Liberar</button>
                    </div>
                    <div class="col-md-1">
                    </div>
                    <div class="col-md-1">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-secondary" data-toggle="modal" data-target="#modalCalidad"
                            id="btnGenControl">Generar
                            controles</button>
                    </div>

                    <div class="col-md-1">
                        {{-- <button class="btn btn-secondary">Duplicar</button> --}}
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div id="divTablaControles">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Opc</th>
                                <th>Folio</th>
                                {{-- <th># Toma</th> --}}
                                <th>Norma</th>
                                <th>Resultado</th>
                                <th>Observacion</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>

    </div>
</div>

        <!-- Modal -->
        <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="">Directo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                        <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Observación</label>
                                        <input type="text" class="form-control" id="obsMuestraDirecto"
                                            placeholder="Observacion de la muestra">
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-success" type="button"
                                            onclick="updateObsMuestra('obsMuestraDirecto')"
                                            id="btnAplicarObs"><i class="voyager-check"></i> Aplicar</button>
                                    </div>
                                </div>
                            <div class="col-md-12">
                                <table class="table" id="">
                                    <thead>
                                        <tr>
                                            <th>Parametro</th>
                                           
                                            <th>Valor</th>
                                            <th>Valor2</th>
                                            
                                        </tr>
                                    </thead>
                                    <!-- <button class="btn btn-success" id="btnImprimir" onclick="imprimir();"><i class="fas fa-file-download"></i></button> -->
                                    <tbody>
                                       
                                        <tr>
                                            <td>L1.</td>
                                            <td><input type="text" id="lecturaUno1" value="0"></td>
                                            <td><input type="text" id="lecturaUno2" value="0"></td>
                                            
                                        </tr>
                                        <tr>
                                            <td>L2.</td>
                                            <td><input type="text" id="lecturaDos1" value="0"></td>
                                            <td><input type="text" id="lecturaDos2" value="0"></td>
                                            
                                        </tr>
                                        <tr>
                                            <td>L3.</td>                                         
                                            <td><input type="text" id="lecturaTres1" value="0"></td>
                                            <td><input type="text" id="lecturaTres2" value="0"></td>
                                           
                                        </tr>
                                        <tr>
                                            <td>Temperatura.</td>
                                            <td><input type="text" id="temperatura1" value="0"></td>
                                            <td><input type="text" id="temperatura2" value="0"></td>
                                          
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="resultado">Resultado</label>
                            <input type="text" id="resultado" style="font-size: 20px;color:red;"
                                placeholder="Resultado">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" onclick="operacion()" id="guardar"
                            class="btn btn-primary">Calcular</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Turbiedad-->
        <div class="modal fade" id="modalTurbiedad" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="">Turbiedad</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                        <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Observación</label>
                                        <input type="text" class="form-control" id="obsMuestraTurbiedad"
                                            placeholder="Observacion de la muestra">
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-success" type="button"
                                            onclick="updateObsMuestra('obsMuestraTurbiedad')"
                                            id="btnAplicarObs"><i class="voyager-check"></i> Aplicar</button>
                                    </div>
                                </div>
                            <div class="col-md-12">
                                <table class="table" id="">
                                    <thead>
                                        <tr>
                                            <th>Parametro</th>
                                           
                                            <th>Valor</th>
                                            <th>Valor2</th>
                                            
                                        </tr>
                                    </thead>
                                    <!-- <button class="btn btn-success" id="btnImprimir" onclick="imprimir();"><i class="fas fa-file-download"></i></button> -->
                                    <tbody>
                                        <tr>
                                            <td>Factor de Dilución.</td>
                                            <td><input type="text" id="dilusionTurb1" value="1"></td>
                                            <td><input type="text" id="dilusionTurb2" value="1"></td>
                                          
                                        </tr>
                                        <tr>
                                            <td>Volumen de Muestra.</td>
                                            <td><input type="text" id="valumenTurb1" value="0"></td>
                                            <td><input type="text" id="volumenTurb2" value="0"></td>
                                          
                                        </tr>
                                        <tr>
                                            <td>L1.</td>
                                            <td><input type="text" id="lecturaUnoTurb1" value="0"></td>
                                            <td><input type="text" id="lecturaUnoTurb2" value="0"></td>
                                            
                                        </tr>
                                        <tr>
                                            <td>L2.</td>
                                            <td><input type="text" id="lecturaDosTurb1" value="0"></td>
                                            <td><input type="text" id="lecturaDosTurb2" value="0"></td>
                                            
                                        </tr>
                                        <tr>
                                            <td>L3.</td>                                         
                                            <td><input type="text" id="lecturaTresTurb1" value="0"></td>
                                            <td><input type="text" id="lecturaTresTurb2" value="0"></td>
                                           
                                        </tr>
                                        <tr>
                                            <td>Promedio.</td>
                                            <td><input type="text" id="promedioTurb1" value="0"></td>
                                            <td><input type="text" id="promedioTurb2" value="0"></td>
                                          
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="resultado">Resultado</label>
                            <input type="text" id="resultadoTurbiedad" style="font-size: 20px;color:red;"
                                placeholder="Resultado">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" onclick="operacionTurbiedad()" id="guardar" class="btn btn-primary">Calcular</button>
                    </div>
                </div>
            </div>
        </div>

          <!-- Modal Cloro-->
          <div class="modal fade" id="modalCloro" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="">Cloro</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                        <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Observación</label>
                                        <input type="text" class="form-control" id="obsMuestraCloro"
                                            placeholder="Observacion de la muestra">
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-success" type="button"
                                            onclick="updateObsMuestra('obsMuestraCloro')"
                                            id="btnAplicarObs"><i class="voyager-check"></i> Aplicar</button>
                                    </div>
                                </div>
                            <div class="col-md-12">
                                <table class="table" id="">
                                    <thead>
                                        <tr>
                                            <th>Parametro</th>
                                           
                                            <th>Valor</th>
                                            <th>Valor2</th>
                                            
                                        </tr>
                                    </thead>
                                    <!-- <button class="btn btn-success" id="btnImprimir" onclick="imprimir();"><i class="fas fa-file-download"></i></button> -->
                                    <tbody>
                                        <tr>
                                            <td>Fact. Dilución.</td>
                                            <td><input type="text" id="dilucionCloro1" value="0"></td>
                                            <td><input type="text" id="dilucionCloro2" value="0"></td>
                                          
                                        </tr>
                                        <tr>
                                            <td>Volumen de Muestra.</td>
                                            <td><input type="text" id="volumenCloro1" value="0"></td>
                                            <td><input type="text" id="volumenCloro2" value="0"></td>
                                          
                                        </tr>
                                        <tr>
                                            <td>L1.</td>
                                            <td><input type="text" id="lecturaUnoCloro1" value="0"></td>
                                            <td><input type="text" id="lecturaUnoCloro2" value="0"></td>
                                            
                                        </tr>
                                        <tr>
                                            <td>L2.</td>
                                            <td><input type="text" id="lecturaDosCloro1" value="0"></td>
                                            <td><input type="text" id="lecturaDosCloro2" value="0"></td>
                                            
                                        </tr>
                                        <tr>
                                            <td>L3.</td>                                         
                                            <td><input type="text" id="lecturaTresCloro1" value="0"></td>
                                            <td><input type="text" id="lecturaTresCloro2" value="0"></td>
                                           
                                        </tr>
                                        <tr>
                                            <td>Promedio.</td>
                                            <td><input type="text" id="promedioCloro1" value="0"></td>
                                            <td><input type="text" id="promedioCloro2" value="0"></td>
                                          
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="resultado">Resultado</label>
                            <input type="text" id="resultadoCloro" style="font-size: 20px;color:red;"
                                placeholder="Resultado">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" onclick="operacionCloro()" id="guardar" class="btn btn-primary">Calcular</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para temperatura -->
        <div class="modal fade" id="modalTemperatura" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="">Temperatura</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                        <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Observación</label>
                                        <input type="text" class="form-control" id="obsMuestraTemperatura"
                                            placeholder="Observacion de la muestra">
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-success" type="button"
                                            onclick="updateObsMuestra('obsMuestraTemperatura')"
                                            id="btnAplicarObs"><i class="voyager-check"></i> Aplicar</button>
                                    </div>
                                </div>
                            <div class="col-md-12">
                                <table class="table" id="">
                                    <thead>
                                        <tr>
                                            <th>Parametro</th>
                                           
                                            <th>Valor</th>
                                            <th>Valor2</th>
                                            
                                        </tr>
                                    </thead>
                                    <!-- <button class="btn btn-success" id="btnImprimir" onclick="imprimir();"><i class="fas fa-file-download"></i></button> -->
                                    <tbody>
                                       
                                        <tr>
                                            <td>L1.</td>
                                            <td><input type="text" id="lecturaUno1T" value="0"></td>
                                            <td><input type="text" id="lecturaUno2T" value="0"></td>
                                            
                                        </tr>
                                        <tr>
                                            <td>L2.</td>
                                            <td><input type="text" id="lecturaDos1T" value="0"></td>
                                            <td><input type="text" id="lecturaDos2T" value="0"></td>
                                            
                                        </tr>
                                        <tr>
                                            <td>L3.</td>                                         
                                            <td><input type="text" id="lecturaTres1T" value="0"></td>
                                            <td><input type="text" id="lecturaTres2T" value="0"></td>
                                           
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="resultado">Resultado</label>
                            <input type="text" id="resultadoT" style="font-size: 20px;color:red;"
                                placeholder="Resultado">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" onclick="operacionTemperatura()" id="guardar"
                            class="btn btn-primary">Calcular</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal para Color -->
        <div class="modal fade" id="modalColor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="">Color</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                        <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Observación</label>
                                        <input type="text" class="form-control" id="obsMuestraColor"
                                            placeholder="Observacion de la muestra">
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-success" type="button"
                                            onclick="updateObsMuestra('obsMuestraColor')"
                                            id="btnAplicarObs"><i class="voyager-check"></i> Aplicar</button>
                                    </div>
                                </div>
                            <div class="col-md-12">
                                <table class="table" id="">
                                    <thead>
                                        <tr>
                                            <th>Parametro</th>
                                           
                                            <th>Valor</th>
                                            <th>Valor2</th>
                                            
                                        </tr>
                                    </thead>
                                    <!-- <button class="btn btn-success" id="btnImprimir" onclick="imprimir();"><i class="fas fa-file-download"></i></button> -->
                                    <tbody>
                                       
                                        <tr>
                                            <td>A.</td>
                                            <td>Color aparente.</td>
                                            <td><input type="text" id="aparente1" ></td>
                                            <td><input type="text" id="" ></td>
                                            
                                        </tr>
                                        <tr>
                                            <td>B.</td>
                                            <td>Color verdadero.</td>
                                            <td><input type="text" id="verdadero1" ></td>
                                            <td><input type="text" id="" ></td>
                                            
                                        </tr>
                                        <tr>
                                            <td>C.</td>
                                            <td>Factor dilución.</td>                                         
                                            <td><input type="text" id="dilusion1" ></td>
                                            <td><input type="text" id="" ></td>
                                           
                                        </tr>
                                        <tr>
                                            <td>D.</td>
                                            <td>Volumen muestra.</td>
                                            <td><input type="text" id="volumen1" ></td>
                                            <td><input type="text" id="" ></td>
                                            
                                        </tr>
                                        <tr>
                                            <td>E.</td>
                                            <td>ph.</td>
                                            <td><input type="text" id="ph1" ></td>
                                            <td><input type="text" id="" ></td>
                                            
                                        </tr>
                                        <tr>
                                            <td>G.</td>
                                            <td>Facor de correción.</td>                                         
                                            <td><input type="text" id="factor1" ></td>
                                            <td><input type="text" id="" ></td>
                                           
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="resultado">Resultado</label>
                            <input type="text" id="resultadoColor" style="font-size: 20px;color:red;"
                                placeholder="Resultado">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" onclick="operacionColor()" id="guardar"
                            class="btn btn-primary">Calcular</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Control Calidad-->
        <div class="modal fade" id="modalCalidad" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                            <h5 class="modal-title" id="">Control de calidad</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                {{-- <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Formula</label>
                                        <input type="text" id="mFormula" disabled value="Forms">
                                    </div>
                                </div> --}}
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Tipo</label>
                                        <select class="form-control" id="controlCalidad">
                                            @foreach ($controlModel as $item)
                                            <option value="{{$item->Id_control}}">{{$item->Control}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" onclick="createControlCalidad()" id="guardar" class="btn btn-primary">Generar</button>
                        </div>
                  
                </div>
            </div>
        </div>



    @section('javascript')
    <script src="{{asset('/public/js/laboratorio/directos/captura.js')}}?v=0.0.1"></script>
    @stop

    @endsection