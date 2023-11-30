@extends('voyager::master')

@section('content')
<link rel="stylesheet" href="{{asset('/public/assets/summer/summernote.min.css')}}">

<div class="container-fluid">
  <div class="row">
    <div class="col-md-3">
      <label for="tipo">Tipo fórmula</label>
      <select class="form-control" id="tipo">
        @foreach($tipo as $item)
          <option value="{{$item->Id_tipo_formula}}">{{$item->Tipo_formula}}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3">
      <div class="form-group">
        <label for="">Fecha lote</label>
        <input type="date" id="fecha" class="form-control" placeholder="Fecha lote">
      </div>
    </div>
    
    <div class="col-md-2">
      <button class="btn btn-success" id="btnBuscar"><i class="fas fa-search"></i> Buscar</button>
    </div>        
    <div class="col align-self-end">
      <button class="btn btn-info" id="btnCrear"><i class="fas fa-check"></i> Crear</button>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12" id="divLotes">
      <table class="table">
        <thead>
          <tr>
            <th>Seleccionar</th>
            <th>Cerrado</th>
            <th>Id Lote</th>
            <th>Fórmula</th>
            <th>Tipo fórmula</th>
            <th>Fecha lote</th>
            <th>Hora</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalDetalle" tabindex="-1" aria-labelledby="modalDetalleLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDetalleLabel">Detalle lote: <input type="" id="idLote" style="border:none;width: 80%;"></h5>
      </div>
      <div class="modal-body">
       {{-- Inicio de Body  --}}
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">General</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Datos</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-toggle="tab" data-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Plantilla</button>
          </li>
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade active" id="home" role="tabpanel" aria-labelledby="home-tab">
            Dato 1
          </div>
          <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="row" style="padding 5px;">
              <div class="col-md-12">
                <button class="btn btn-success" id="btnGuardarDetalle" type="button"><i class="fas fa-save"></i> Guardar</button>
              </div>
              <div class="col-md-12">
                <h6>Flama/ Gnerador de hidruros/Horno de grafito/ Alimentos</h6>
              </div>
              <div class="col-md-12">
                <div class="dropdown-divider"></div>
              </div>
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-4">
                    <label for="">Fecha/Hora digestión</label>
                    <input type="datetime-local"  class="form-control form-control-sm" id="fechaDigestion">
                  </div>
                  <div class="col-md-4">
                    <label>Longitud de onda</label> 
                    <input type="text" class="form-control form-control-sm" id="longitudOnda"> 
                  </div>
                  <div class="col-md-4">
                    <label for="">Fecha de preparacion</label>
                    <input type="datetime-local"  class="form-control form-control-sm" id="fechaPreparacion">
                  </div>
                  <div class="col-md-4">
                    <label>No Inventario</label> 
                    <input type="text" class="form-control form-control-sm" id="noInventario"> 
                  </div>
                  <div class="col-md-4">
                    <label for="">Corriente</label>
                    <input type="text" class="form-control" id="corriente">
                  </div>
                  <div class="col-md-4">
                    <label for="">Gas</label>
                    <input type="text" class="form-control" id="gas">
                  </div>
                  <div class="col-md-4">
                    <label for="">Flujo gas</label>
                    <input type="text" class="form-control" id="flujoGas">
                  </div>
                  <div class="col-md-4">
                    <label for="">No Inventario de lampara</label>
                    <input type="text" class="form-control" id="noLampara">
                  </div>
                  <div class="col-md-4">
                    <label for="">Energia</label>
                    <input type="text" class="form-control" id="energia">
                  </div>
                  <div class="col-md-4">
                    <label for="">Aire</label>
                    <input type="text" class="form-control" id="aire">
                  </div>
                  <div class="col-md-4">
                    <label for="">Equipo</label>
                    <input type="text" class="form-control" id="equipo">
                  </div>
                  <div class="col-md-4">
                    <label for="">Slit</label>
                    <input type="text" class="form-control" id="slit">
                  </div>
                  <div class="col-md-4">
                    <label for="">Conc. Std</label>
                    <input type="text" class="form-control" id="conStd">
                  </div>
                  <div class="col-md-4">
                    <label for="">Oxido nitroso</label>
                    <input type="text" class="form-control" id="oxidoNitroso">
                  </div>
                </div>
              </div>

              <div class="col-md-12">
                <div class="dropdown-divider"></div>
              </div>
              <div class="col-md-12">
                <h6>Blanco de curva</h6>
              </div>
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-6">
                    <div class="row">
                      <div class="col-md-12">
                        <label for="">Verificación de blanco</label>
                        <input type="text"  class="form-control" id="verificacionBlanco">
                      </div>
                      <div class="col-md-12">
                        <label for="">ABS teorica de blanco</label>
                        <input type="text"  class="form-control" id="absTeoricaB">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="row">
                      <div class="col-md-12">
                        <label for="">Abs 1</label>
                        <input type="text"  class="form-control" id="abs1B">
                      </div>
                      <div class="col-md-12">
                        <label for="">Abs 2</label>
                        <input type="text"  class="form-control" id="abs2B">
                      </div>
                      <div class="col-md-12">
                        <label for="">Abs 3</label>
                        <input type="text"  class="form-control" id="abs3B">
                      </div>
                      <div class="col-md-12">
                        <label for="">Abs 4</label>
                        <input type="text"  class="form-control" id="abs4B">
                      </div>
                      <div class="col-md-12">
                        <label for="">Abs 5</label>
                        <input type="text"  class="form-control" id="abs5B">
                      </div>
                      <div class="col-md-12">
                        <label for="">Promedio</label>
                        <input type="text"  class="form-control" id="promedioB">
                      </div>
                      <div class="col-md-12">
                        <label for="">Conclusión</label>
                        <input type="text"  class="form-control" id="conclusionB">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-12">
                <div class="dropdown-divider"></div>
              </div>
              <div class="col-md-12">
                <h6>Verificacion del espectofotometro</h6>
              </div>
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-6">
                    <div class="row">
                      <div class="col-md-12">
                        <label for="">Std. Cal</label>
                        <input type="text"  class="form-control" id="stdCalE">
                      </div>
                      <div class="col-md-12">
                        <label for="">ABS teorica</label>
                        <input type="text"  class="form-control" id="absTeoricaE">
                      </div>
                      <div class="col-md-12">
                        <label for="">Conc. mg/L</label>
                        <input type="text"  class="form-control" id="concE">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="row">
                      <div class="col-md-12">
                        <label for="">Abs 1</label>
                        <input type="text"  class="form-control" id="abs1E">
                      </div>
                      <div class="col-md-12">
                        <label for="">Abs 2</label>
                        <input type="text"  class="form-control" id="abs2E">
                      </div>
                      <div class="col-md-12">
                        <label for="">Abs 3</label>
                        <input type="text"  class="form-control" id="abs3E">
                      </div>
                      <div class="col-md-12">
                        <label for="">Abs 4</label>
                        <input type="text"  class="form-control" id="abs4E">
                      </div>
                      <div class="col-md-12">
                        <label for="">Abs 5</label>
                        <input type="text"  class="form-control" id="abs5E">
                      </div>
                      <div class="col-md-12">
                        <label for="">Promedio</label>
                        <input type="text"  class="form-control" id="promedioE">
                      </div>
                      <div class="col-md-12">
                        <label for="">Masa caracteristica (pg/0.0044 A-s)</label>
                        <input type="text"  class="form-control" id="masaE">
                      </div>
                      <div class="col-md-12">
                        <label for="">Conclusión</label>
                        <input type="text"  class="form-control" id="conclusionE">
                      </div>
                      <div class="col-md-12">
                        <label for="">Conc. Obtenida</label>
                        <input type="text"  class="form-control" id="concObtenidaE">
                      </div>
                      <div class="col-md-12">
                        <label for="">% Rec</label>
                        <input type="text"  class="form-control" id="recE">
                      </div>
                      <div class="col-md-12">
                        <label for="">Cumple</label>
                        <input type="text"  class="form-control" id="cumpleE">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-12">
                <div class="dropdown-divider"></div>
              </div>
              <div class="col-md-12" hidden>
                <h6>Estandar de verificación del instrumento</h6>
              </div>
              <div class="col-md-12" hidden>
                <div class="row">
                  <div class="col-md-6">
                    <div class="row">
                      <div class="col-md-12">
                        <label for="">Conc. (mg/L)</label>
                        <input type="text"  class="form-control" id="concI">
                      </div>
                      <div class="col-md-12">
                        <label for="">DESV. STD</label>
                        <input type="text"  class="form-control" id="desvI">
                      </div>
                      <div class="col-md-12">
                        <label for="">Cumple</label>
                        <input type="text"  class="form-control" id="cumpleI">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="row">
                      <div class="col-md-12">
                        <label for="">Abs 1</label>
                        <input type="text"  class="form-control" id="abs1I">
                      </div>
                      <div class="col-md-12">
                        <label for="">Abs 2</label>
                        <input type="text"  class="form-control" id="abs2I">
                      </div>
                      <div class="col-md-12">
                        <label for="">Abs 3</label>
                        <input type="text"  class="form-control" id="abs3I">
                      </div>
                      <div class="col-md-12">
                        <label for="">Abs 4</label>
                        <input type="text"  class="form-control" id="abs4I">
                      </div>
                      <div class="col-md-12">
                        <label for="">Abs 5</label>
                        <input type="text"  class="form-control" id="abs5I">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-12">
                <div class="dropdown-divider"></div>
              </div>
              <div class="col-md-12">
                <h6>Curva de calibración</h6>
              </div>
              <div class="6">
                <label for="">Bitacora</label> 
                <input type="text" class="form-control" id="bitacora">
              </div>
              <div class="6">
                <label for="">Folio</label> 
                <input type="text" class="form-control" id="folio">
              </div>

              <div class="col-md-12">
                <div class="dropdown-divider"></div>
              </div>
              <div class="col-md-12">
                <h6>Generador de hidruros</h6>
              </div>
              <div class="6">
                <label for="">Valor</label> 
                <input type="text" class="form-control" id="valor">
              </div>

            </div>
          </div>
          <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
            <div class="row">
              <div class="col-md-12">
                <button id="btnBitacora" class="btn bg-success"><i class="fas fa-save"></i> Guardar</button>
              </div>
              <div class="col-md-12">
                <input type="text" id="tituloBit" hidden>
                <div id="divSummer"></div>
                <input type="text" id="revBit" hidden>
              </div>
            </div>
          </div>
        </div>
       {{-- Fin de body --}} 
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>



@stop

  @section('javascript')
  <!-- include summernote css/js -->
  <script src="{{asset('/public/assets/summer/summernote.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
  <script src="{{asset('/public/js/laboratorio/metales/lote.js')}}?v=1.0.7"></script>
  <script src="{{asset('/public/js/libs/componentes.js')}}"></script>
  <script src="{{asset('/public/js/libs/tablas.js')}}"></script>  
@endsection