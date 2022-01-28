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
                        <option value= {{$parametros->Id_parametro}}>{{$parametros->Parametro}}</option>
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
                <div class="col-md-1">
                    <button class="btn btn-secondary" id="ejecutar">Ejecutar</button>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-secondary" id="btnLiberar">Liberar</button>
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
                    <button class="btn btn-secondary" id="btnGenControl" onclick="generarControles();">Generar controles</button>
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
                            <th># Toma</th>
                            <th>Norma</th>
                            <th>Resultado</th>
                            <th>Tipo Analisis</th> 
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
                          <input type="text" class="form-control" id="observacion">
                        </div>
                        <div class="form-group">
                          <button class="btn btn-success" type="button" onclick="updateObsMuestraGA()" id="btnAplicarObs">Aplicar</button>
                        </div>
                        <div class="dropdown-divider"></div>
                      </div>
                      <div class="col-md-12">
                        <input type="number" id="resultado" placeholder="Resultado"></input>
                        <input type="number" id="p" placeholder="No. Serie Matraz"></input>
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
                                        <td>Masa 1</td>
                                        <td><input name="campos" type="text" id="m11" value="0"></td>
                                        <td><input name="campos" type="text" id="m12" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>Masa 2</td>
                                        <td><input name="campos" type="text" id="m21" value="0"></td>
                                        <td><input name="campos" type="text" id="m22" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>Peso constante c/muestra 1</td>
                                        <td><input name="campos" type="text" id="pcm11" value="0"></td>
                                        <td><input name="campos" type="text" id="pcm12" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>Peso constante c/muestra 2</td>
                                        <td><input name="campos" type="text" id="pcm21" value="0"></td>
                                        <td><input name="campos" type="text" id="pcm22" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>Peso constante 1</td>
                                        <td><input name="campos" type="text" id="pc1" value="0"></td>
                                        <td><input name="campos" type="text" id="pc2" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>Peso constante 2</td>
                                        <td><input name="campos" type="text" id="pc21" value="0"></td>
                                        <td><input name="campos" type="text" id="pc22" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>Volumen de muestra</td>
                                        <td><input name="campos" type="text" id="v1" value="0"></td>
                                        <td><input name="campos" type="text" id="v2" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>Factor de conversión</td>
                                        <td><input name="campos" type="text" id="f1" value="1000000"></td>
                                        <td><input name="campos" type="text" id="f2" value="1000000"></td>
                                    </tr>
                                </tbody>
                              </table>
                        </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button"  id="guardar" class="btn btn-primary">Ejecutar y Guardar</button>
                  </div>
                </form>
                </div>
              </div>
              
             </div>

               <!-- Modal Solidos (Diferencias) -->
        <div class="modal fade" id="modalCapturaSol" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        <input type="text" class="form-control" id="observacion">
                      </div>
                      <div class="form-group">
                        <button class="btn btn-success" type="button" onclick="updateObsMuestraGA()" id="btnAplicarObs">Aplicar</button>
                      </div>
                      <div class="dropdown-divider"></div>
                    </div>
                    <div class="col-md-12">
                      <input type="number" id="resultado" placeholder="Resultado"></input>
                      <input type="number" id="p" placeholder="No. Serie Matraz"></input>
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
                                      <td>Masa 1</td>
                                      <td><input type="text" id="m11" value="0"></td>
                                      <td><input type="text" id="m12" value="0"></td>
                                  </tr>
                     
                              </tbody>
                            </table>
                      </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                  <button type="button"  id="guardar" class="btn btn-primary">Guardar</button>
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
                                  @foreach ($controlModel as $item) --}}
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
  <script src="{{asset('/public/js/laboratorio/fq/capturaSolidos.js')}}?v=0.001"></script>
  <script src="{{asset('/public/js/libs/funciones.js')}}"></script>
  @stop

@endsection  