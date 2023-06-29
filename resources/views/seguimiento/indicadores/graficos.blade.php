
@extends('voyager::master')

@section('content') 

  @section('page_header')
  <h6 class="page-title">
    <i class="voyager-window-list"></i> 
    Graficos
</h6>

  @stop
<div class = "container-fluid">
    <div class="row">
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label for="grafica">Indicadores</label>
                <select class="form-control" id="grafica">
                  <option value="0">Sin seleccionar</option>
                  <option value="1">Grafica 1</option>
                  <option value="2">Grafica 2</option>
                  <option value="3">Grafica 3</option>
                  <option value="4">Grafica 4</option>
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label for="fechaIni">Fecha Inicio</label>
                <input type="date" class="form-control" id="fechaIni">
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label for="fechaFin">Fecha Fin</label>
                <input type="date" class="form-control" id="fechaFin">
              </div>
            </div>
            <div class="col-md-2"><br>
              <button class="btn btn-success" id="btnBuscar"><i class="fas fa-search"></i> Buscar</button>
            </div>
          </div>
        </div>
        <div class="col-md-12" id="newCanva">
            
        </div>
    </div>
</div>


@section('javascript')
  <script src="{{asset('/public/js/seguimiento/indicadores/graficos.js')}}?v=0.0.1"></script>
  {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   --}}
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>
  
@stop

@endsection
 
  