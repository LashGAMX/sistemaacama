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
                <select class="form-control">
                    <option value="0">Sin seleccionar</option>
                    <option value="1">ALUMINIO (Al) (Metales residual)</option>
                    <option value="2">ARSENICO (As) (Metales residual)</option>
                    <option value="3">ARSENICO (As) (Metales potable)</option>
                    <option value="4">CADMIO (Cd) (Metales residual)</option>
                    <option value="5">COBRE TOTAL (Cu) (Metales residual)</option>
                    <option value="6">CROMO TOTAL (Cr) (Metales residual)</option>
                    <option value="7">FIERRO (Fe) (Metales residual)</option>
                    <option value="8">MERCURIO (Hg) (Metales residual)</option>
                    <option value="9">MERCURIO (Hg) (Metales potable)</option>
                    <option value="10">NIQUEL (Ni) (Metales residual)</option>
                    <option value="11">PLOMO (Pb) (Metales residual)</option>
                    <option value="12">SELENIO (Se) (Metales residual)</option>
                    <option value="13">ZINC (Zn) (Metales residual)</option>
                  </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
              <label for="exampleFormControlSelect1">Parametro</label>
                <select class="form-control">
                    @foreach ($parametro as $item)
                    <option value="{{$item->Id_parametro}}">{{$item->Parametro}}</option>
                @endforeach
                  </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Núm. muestra</label>
                <input type="text" class="form-control">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Fecha análisis</label>
                <input type="date" class="form-control">
            </div>
        </div>
        <div class="col-md-3">
            <button class="btn btn-success">Buscar</button>
        </div>

        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <table class="table" id="tableAnalisis"> 
                        <thead>
                          <tr>
                            <th>Folio</th>
                            <th>Fecha lote</th>
                            <th>Total asignados</th>
                            <th>Total liberados</th>
                            <th></th>
                            <th>Creado por</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                        </tbody>
                      </table>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-3">
                            <p class="">Información global</p>
                        </div>
                        <div class="col-md-9">
                            <p class="">Información</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="row">
                <div class="col-md-1">
                    <button class="btn btn-secondary" data-toggle="modal" data-target="#modalCrear">Ejecutar</button>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-secondary">Liberar</button>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-secondary">Liberar todo</button>
                </div>
                <div class="col-md-1">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                        <label class="form-check-label" for="defaultCheck1">
                          Blanco
                        </label>
                      </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-secondary">Generar controles</button>
                </div>

                <div class="col-md-1">
                    <button class="btn btn-secondary">Duplicar</button>
                </div>
            </div>
            <table class="table" id="tableDatos2">
                <thead>
                    <tr>
                        <th>NumMuestra</th>
                        <th>NomCliente</th>
                        <th>PuntoMuestreo</th>
                        <th>Vol. Muestra E</th>
                        <th>X</th>
                        <th>Y</th>
                        <th>Z</th>
                        <th>Absorción
                            promedio
                        </th>
                        <th>Factor dilución D</th>
                        <th>Factor conversion G</th>
                        <th>Vol. disolución digerida v</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
      </div>
        <!-- Modal -->
        <div wire:ignore.self class="modal fade" id="modalCrear" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                 <form wire:submit.prevent="create">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Nueva constante</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body"> 
                      <div class="row">
                          <div class="col-md-6">
                              
                              <label for="">Nombre</label>
                              <input type="text" id='nombre' wire:model='nombre'  class="form-control" placeholder="Nombre">
                          </div>
                          <div class="col-md-6">
                              <label for="">Volumen</label>
                              <input type="text" id='volumen' wire:model='volumen' class="form-control" placeholder="Volumen">
                          </div>
                          
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                  </div>
                </form>
                </div>
              </div>
              
             </div>

</div>
  @stop

  @section('javascript')
  <script src="{{asset('js/laboratorio/captura.js')}}"></script>
  <script src="{{asset('js/libs/componentes.js')}}"></script>
  <script src="{{asset('js/libs/tablas.js')}}"></script>
  @stop

@endsection  


