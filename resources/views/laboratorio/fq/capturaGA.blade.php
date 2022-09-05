@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="voyager-window-list"></i>
    Captura de resultados
  </h6>
  
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
              <label for="exampleFormControlSelect1">Fórmula tipo</label>
                <select class="form-control" name="formulaTipo" id="formulaTipo">
                    <option value="0">Sin seleccionar</option>
                    @foreach ($parametro as $parametros)
                        <option value= {{$parametros->Id_parametro}}>{{$parametros->Parametro}} ({{$parametros->Tipo_formula}})</option>
                    @endforeach
                  </select>
            </div>
        </div>
        {{-- <div class="col-md-3"> 
            <div class="form-group">
                <label for="">Núm. muestra</label>
                <input type="text" style="width: " class="form-control" id="numeroMuestra">
            </div>
        </div> --}}
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Fecha análisis</label>
                <input type="date" class="form-control" id="fechaAnalisis">
            </div>
        </div>
        <div class="col-md-3">
            <button class="btn btn-success" onclick="getDataCaptura()" id="btnBuscar">Buscar</button>
        </div>
        <div class="col-md-3">
    <input  id="idLote" hidden>   
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
                            <!-- <button class="btn btn-success" id="btnImprimir" onclick="imprimir();"><i class="fas fa-file-download"></i></button> -->
                          </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-3">
                            <p class="">Información global</p>
                            <div id="infoGlobal">

                                <button class="btn btn-success" onclick="getDataCaptura()" id="btnBuscar">Buscar</button>

                            </div>
                        </div>
                        <div class="col-md-9">
                            <p class="">Información</p>
                            <div id="infoGen">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="row">
                {{-- <div class="col-md-1">
                    <button class="btn btn-secondary" id="ejecutar">Ejecutar</button>
                </div> --}}
                <div class="col-md-1">
                    <button class="btn btn-secondary" id="btnLiberar">Liberar</button>
                </div>
                <div class="col-md-1">
                  <button class="btn btn-primary" id="btnLiberarTodo">Liberar Todo</button>
              </div>
                <div class="col-md-1">
                    {{-- <button class="btn btn-secondary">Liberar todo</button> --}}
                </div>
                <div class="col-md-1">
                    {{-- <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                        <label class="form-check-label" for="defaultCheck1">
                          Blanco
                        </label>
                      </div> --}}
                </div>
                <div class="col-md-2">
                    <button class="btn btn-secondary"  data-toggle="modal" data-target="#modalCalidad" id="btnGenControlInd">Generar control</button>
                </div>
              <div class="col-md-2">
                    <button class="btn btn-primary" id="btnGenControles" onclick="generarControles();">Generar controles</button>
                </div>

                <div class="col-md-1">
                    {{-- <button class="btn btn-secondary">Duplicar</button> --}}
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div id="divTablaControles">
                <table class="table" id="tablaControles">
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
                    
                    {{-- <button class="btn btn-success" id="btnImprimir" onclick="imprimir();"><i class="fas fa-file-download"></i></button> --}}
                </table> 
            </div>
        </div>
      </div>
        <!-- Modal -->
        <div class="modal fade" id="modalCaptura" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" >
                <div class="modal-content">
                 <form wire:submit.prevent="create">
                  <div class="modal-header">
                    <h5 class="modal-title" id="">Captura de resultados</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body"> 
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="">Observación</label>
                          <input type="text" class="form-control" placeholder="Observacion de la muestra" id="observacion">
                        </div>
                        <div class="form-group">
                          <button class="btn btn-success" type="button" onclick="updateObsMuestraGA()" id="btnAplicarObs">Aplicar</button>
                        </div>
                        <div class="dropdown-divider"></div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                            <label for="resultado">Resultado</label>
                            <input type="text" id="resultado" style="font-size: 20px;color:red;"
                                placeholder="Resultado">
                        </div>
                    </div>
                      <div class="col-md-6">
                        <input type="text" id="p" style="font-size: 20px;color:blue;" placeholder="No. Serie Matraz">
                      </div>
                        <div class="col-md-12">
                            <table class="table" id=""> 
                                <thead>
                                  <tr>
                                    <th>Parametro</th>
                                    <th>Descripción</th>
                                    <th>Valor</th>
                                    <th>Valor2</th>
                                    <th>Tipo</th>
                                  </tr>
                                </thead>
                                <!-- <button class="btn btn-success" id="btnImprimir" onclick="imprimir();"><i class="fas fa-file-download"></i></button> -->
                                <tbody>
                                    <tr>
                                        <td>H</td>
                                        <td>Masa Final</td>
                                        <td><input type="text" id="h1" value="0"></td>
                                        <td><input type="text" id="h2" value="0"></td>
                                        <td>V</td>
                                    </tr>
                                    <tr>
                                        <td>J</td>
                                        <td>Masa Inicial 1</td>
                                        <td><input type="text" id="j1" value="0"></td>
                                        <td><input type="text" id="j2" value="0"></td>
                                        <td>V</td>
                                    </tr>
                                    <tr>
                                        <td>K</td>
                                        <td>Masa Inicial 2</td>
                                        <td><input type="text" id="k1" value="0"></td>
                                        <td><input type="text" id="k2" value="0"></td>
                                        <td>V</td>
                                    </tr>
                                    <tr>
                                        <td>C</td>
                                        <td>Masa Inicial 3</td>
                                        <td><input type="text" id="c1" value="0"></td>
                                        <td><input type="text" id="c2" value="0"></td>
                                        <td>V</td>
                                    </tr>
                                    <tr>
                                        <td>L</td>
                                        <td>Ph</td>
                                        <td><input type="text" id="l1" value="0"></td>
                                        <td><input type="text" id="l2" value="0"></td>
                                        <td>V</td>
                                    </tr>
                                    <tr>
                                        <td>I</td>
                                        <td>Volumen</td>
                                        <td><input type="text" id="i1" value="0"></td>
                                        <td><input type="text" id="i2" value="0"></td>
                                        <td>V</td>
                                    </tr>
                                    <tr>
                                        <td>G</td>
                                        <td>Blanco</td>
                                        <td><input type="text" id="g1" value="0"></td>
                                        <td><input type="text" id="g2" value="0"></td>
                                        <td>F</td>
                                    </tr>
                                    <tr>
                                        <td>E</td>
                                        <td>Factor de conversión</td>
                                        <td><input type="text" id="e1" value="1000000"></td>
                                        <td><input type="text" id="e2" value="1000000"></td>
                                        <td>C</td>
                                    </tr>
                                </tbody>
                              </table>
                        </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button"  id="guardar" class="btn btn-primary">Guardar y jecutar</button>
                  </div>
                </form>
                </div>
              </div>
              
             </div>

               <!-- Modal Control Calidad-->
        <div class="modal fade" id="modalCalidad" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                 <form wire:submit.prevent="create">
                  <div class="modal-header">
                    <h5 class="modal-title" id="">Control de calidad</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body"> 
                    <div class="row">
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
                </form>
                </div>
              </div>
             </div>
             
</div>
  @stop

  @section('javascript')
  <script src="{{asset('/public/js/laboratorio/fq/capturaGA.js')}}?v=0.001"></script>
  @stop

@endsection  


