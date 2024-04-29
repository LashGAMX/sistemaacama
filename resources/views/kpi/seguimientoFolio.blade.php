@extends('voyager::master')

@section('content')

  @section('page_header')
    <h4>Seguimiento de muestra</h4>
  @stop 

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
          <div class="row">
            <div class="col-md-3">  
                <input type="text" class="form-control" placeholder="xxx-xx/xx" id="txtFolio">
            </div>
            <div class="col-md-3">
              <button id="btnBuscar" class="btn btn-info"><i class="fas fa-search"></i> Buscar</button>
            </div>
            <div class="col-md-6" id="divPuntos">
              <select class="custom-select" id="selPunto">
                <option selected>No hay punto de muestreo</option>
              </select>
            </div>
          </div>
      </div>

      <div class="col-md-12">
        <div class="row">
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Cotización</h5>
                <div id="divCotizacion"></div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Orden de servicio</h5>
                <div id="divOrden"></div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Muestreo</h5>
                <div id="divMuestreo"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Recepción</h5>
                <div id="divRecepcion"></div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Ingreso al lab</h5>
                <div id="divLab"></div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Impresión</h5>
                <div id="divImpresion"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  @section('javascript')
  <script src="{{ asset('public/js/kpi/seguimientoFolio.jsx') }}?v=0.0.1"></script>
@stop
@endsection 
