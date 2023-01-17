@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="far fa-eye"></i>
    Asignación lote
  </h6>
  @stop

  
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
    <div class="col-md-2">
      <label for="tipo">Tecnica</label>
      <select class="form-control" id="tecnica">
        @foreach($tecnica as $item)
          <option value="{{$item->Id_tecnica}}">{{$item->Tecnica}}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-2">
      <div class="form-group">
        <label for="">Fecha recepción</label>
        <input type="date" id="fechaRecepcion" class="form-control" placeholder="Fecha lote">
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group">
        <label for="">Fecha lote</label>
        <input type="date" id="fechaLote" class="form-control" placeholder="Fecha lote">
      </div>
    </div>
    <div class="col-md-3" style="display: flex">
      <button class="btn-info">Pendientes</button> &nbsp;
      <button class="btn-secondary" id="btnBuscar"><i class="fas fa-search"></i> Buscar</button>
    </div>
  </div>
  <div class="row">
    <div class="" style="float: right;">
      <i class="fas fa-check-square text-info" id="btnSeleccionar"> Seleccionar Todo</i>&nbsp;&nbsp;&nbsp;&nbsp;
      <i class="fas fa-check text-success" id="btnAgregar"></i>
    </div>
    <div class="col-md-12" id="divMuestra">
      <table class="table table-ms">
        <thead>
          <tr>
            <th>Seleccionar</th>
            <th>Id</th>
            <th>Num Muestra</th>
            <th>Cliente</th>
            <th>Punto Muestreo</th>
            <th>Norma</th>
            <th>Formula</th>
            <th>Lote</th>
            <th>Fecha Lote</th> 
          </tr>
        </thead> 
      </table> 
    </div>
  </div>
</div>

  @section('javascript')
  <script src="{{asset('public/js/laboratorio/metales/asignar.js')}}?v=0.0.1"></script>
  <script src="{{asset('public/js/libs/componentes.js')}}"></script>
  <script src="{{asset('public/js/libs/tablas.js')}}"></script>
  @stop

@endsection