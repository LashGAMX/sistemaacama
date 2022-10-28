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
                    <option value={{$parametros->Id_parametro}}>{{$parametros->Id_parametro}}/{{$parametros->Parametro}} / {{$parametros->Tipo_formula}}</option> 
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
                                        <input type="text" class="form-control" id="obsMuestra"
                                            placeholder="Observacion de la muestra">
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-success" type="button"
                                            onclick="updateObsMuestra()"
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
                                        <input type="text" class="form-control" id="obsMuestra"
                                            placeholder="Observacion de la muestra">
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-success" type="button"
                                            onclick="updateObsMuestra()"
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
        <!-- Modal para temperatura -->
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
                                        <input type="text" class="form-control" id="obsMuestra"
                                            placeholder="Observacion de la muestra">
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-success" type="button"
                                            onclick="updateObsMuestra()"
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


    @section('javascript')
    <script src="{{asset('/public/js/laboratorio/directos/captura.js')}}?v=0.0.1"></script>
    @stop

    @endsection