@extends('voyager::master')

@section('content')

  @section('page_header')
  <!-- <h6 class="page-title"> 
    <i class="voyager-window-list"></i>
    Captura de resultados
  </h6>
  -->
<div class="container-fluid">
    <div class="row"> 
        <div class="col-md-3">
            <div class="form-group">
              <label for="exampleFormControlSelect1">Fórmula tipo</label>
                <select class="form-control select2" name="formulaTipo" id="formulaTipo">
                    <option value="0">Sin seleccionar</option>
                    @foreach ($parametro as $parametros)
                        <option value= "{{$parametros->Id_parametro}}">({{$parametros->Id_parametro}}){{$parametros->Parametro}} ({{$parametros->Tipo_formula}})</option>
                    @endforeach
                  </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Folio</label>
                <input type="text" style="width: " class="form-control" id="folio" placeholder="xxx-xx/xx-x">
            </div>
        </div>
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
                        <table class="table" id="tablaLote"> 
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
                          </table>
                    </div>
                </div>
                <div class="col-md-3">
                    <p class="">Observacion Metales</p>
                    </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4">                                
                            <div class="form-group">
                                <h6>B <input placeholder="B" id="b"></h6>
                                <h6>M <input placeholder="M" id="m"></h6>
                                <h6>R <input placeholder="R" id="r"></h6>
                            </div>
                          </div>
                          <div class="col-md-2">
                            <div class="form-group">
                              <label for="exampleFormControlTextarea1">Observaciones</label>
                              <textarea class="form-control" id="observacion" rows="3"></textarea>
                            </div>
                          </div>
              
                          <div class="col-md-2">
                            <button class="btn btn-success" id="enviarObservacion">Aplicar</button> 
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
                    <button class="btn btn-secondary" data-toggle="modal" data-target="#modalCalidad"
                        id="btnGenControlInd">Generar control</button>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary" id="btnGenerarControles">Generar controles</button>
                </div>
                <div class="col-md-2">
                    <h2><input placeholder="Resultado" style="color:red" id="resDato"></h2>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div id="divTablaControles">
                <table class="table" id="tablaControles">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>NumMuestra</th>
                            <th>NomCliente</th>
                            {{-- <th>PuntoMuestreo</th> --}}
                            <th>Vol. Muestra E</th>
                            <th>Abs1</th>
                            <th>Abs2</th>
                            <th>Abs3</th>
                            <th>Absorción
                                promedio
                            </th>
                            <th>Factor dilución D</th>
                            <th>Factor conversion G</th>
                            <th>Resultado</th> 
                        </tr>
                    </thead>
                </table>
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
                                <button type="button" onclick="createControlCalidad()" id="guardar"
                                    class="btn btn-primary">Generar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


             <!-- Modal Control Calidad-->
             <div class="modal fade" id="modalHistorial" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="">Historial</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="divHistorial">

                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
             
</div>
  @stop

  @section('javascript')
  <script src="{{asset('public/js/laboratorio/metales/captura.js')}}?v=1.1.3"></script>
  @stop

@endsection    


