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
                <input type="text" class="form-control" placeholder="Escriba el folio" id="txtFolio">
            </div>
            <div class="col-md-3">
              <button id="btnBuscar" class="btn btn-info"><i class="fas fa-search"></i> Buscar</button>
            </div>
            <div class="col-md-3">
              <select class="custom-select" id="txtPunto">
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
                <p class="card-text">Fecha</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Orden de servicio</h5>
                <p class="card-text">Fecha</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Muestreo</h5>
                <p class="card-text">Fecha</p>
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
                <p class="card-text">Fecha</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Ingreso al lab</h5>
                <p class="card-text">Areas</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Impresión</h5>
                <p class="card-text">Fecha</p>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  @section('javascript')
  <script src="{{asset('/public/js/seguimiento/muestra.js')}}?v=0.0.1"></script>
@stop
@endsection 
