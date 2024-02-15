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
        <option value="0">Sin seleccionar</option>
        @foreach($tipo as $item)
          <option value="{{$item->Id_tipo_formula}}">{{$item->Tipo_formula}}</option>
        @endforeach
      </select>
    </div>
    <!-- <div class="col-md-3">
      <label for="norma">Norma</label>
      <select class="form-control select2" id="norma">
        <option value="0">Sin seleccionar</option>
        @foreach($norma as $item)
          <option value="{{$item->Id_norma}}">{{$item->Clave_norma}}</option>
        @endforeach
      </select>
    </div> -->
    <div class="col-md-2">
      <label for="tecnica">Tecnica</label>
      <select class="form-control" id="tecnica">
        <option value="0">Sin seleccionar</option>
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
      <button class="btn-info" type="button" id="btnPendiente" data-toggle="modal" data-target="#pendientes">Pendientes</button> &nbsp;
      <button class="btn-secondary" id="btnBuscar"><i class="fas fa-search"></i> Buscar</button>
    </div>

  </div>
  <div class="row">
    <div class="" style="float: right;">
      <button id="btnSeleccionar" class="btn bg-info"><i class="fas fa-check-square " id=""> Seleccionar Todo</i></button>&nbsp;&nbsp;&nbsp;&nbsp;
      <button id="btnAgregar" class="btn bg-success"><i class="fas fa-check " id=""></i></button>
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

<!-- Modal -->
<div class="modal fade" id="pendientes"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Pendientes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="divPendientes">
        <table class="table">
          <thead>
            <tr>
              <th>Folio</th>
              <th>Parametro</th>
              <th>Fecha recepción</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>

  @section('javascript')
  <script src="{{asset('public/js/laboratorio/metales/asignar.js')}}?v=0.0.1"></script>
  <script src="{{asset('public/js/libs/componentes.js')}}"></script>
  <script src="{{asset('public/js/libs/tablas.js')}}"></script>
  @stop

@endsection