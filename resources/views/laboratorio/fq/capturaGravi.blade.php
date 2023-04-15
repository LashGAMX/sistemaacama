@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="voyager-window-list"></i>
    Captura de resultados modif
  </h6>
  
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
              <label for="exampleFormControlSelect1">Fórmula tipo</label>
                <select class="form-control" name="formulaTipo" id="formulaTipo">
                    <option value="0">Sin seleccionar</option>
                    @foreach ($parametro as $parametros)
                        <option value= "{{$parametros->Id_parametro}}">({{$parametros->Id_parametro}}){{$parametros->Parametro}}</option>
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
                    <tbody>
                            <tr>
                            <td><button type="button" class="btn btn-success" onclick="" data-toggle="modal" data-target="#modalCaptura">Capturar</button></td>
                            <td></td>
                            <td></td>
                            <td>Norma</td>
                            <td>Res</td>
                            <td>Tipo</td>
                            <td>Obs</td>
                            </tr>
                    </tbody>
                    
                    {{-- <button class="btn btn-success" id="btnImprimir" onclick="imprimir();"><i class="fas fa-file-download"></i></button> --}}
                </table> 
            </div>
        </div>
      </div>
        <!-- Modal -->
        <div class="modal fade" id="modalCaptura" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                 <form wire:submit.prevent="create">
                  <div class="modal-header">
                    <h5 class="modal-title" id="">Captura de resultados</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                    <input type="number" id="resultado" placeholder="Resultado"></input>
                    {{-- <input type="number" id="p" placeholder="Peso sin muestra"></input> --}}
                  </div>
                  <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table" id=""> 
                                <thead>
                                  <tr>
                                    <th>Parametro</th>
                                    <th>Descripción</th>
                                    <th>Valor</th>
                                    <th>Valor2</th>
                                  </tr>
                                </thead>
                                <!-- <button class="btn btn-success" id="btnImprimir" onclick="imprimir();"><i class="fas fa-file-download"></i></button> -->
                                <tbody>
                               
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
             
</div>
  @stop

  @section('javascript')
  <script src="{{asset('/public/js/laboratorio/fq/capturaGA.js')}}"></script>
  <script src="{{asset('/public/js/libs/componentes.js')}}"></script>
  <script src="{{asset('/public/js/libs/tablas.js')}}"></script>
  @stop

@endsection  


