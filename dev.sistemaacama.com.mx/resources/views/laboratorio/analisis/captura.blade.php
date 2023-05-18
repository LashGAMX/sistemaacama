@extends('voyager::master')

@section('content')

  @section('page_header')
  @stop

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-6">
            <center><h4 class="text-info">Lotes</h4></center>
            <table class="table table-sm" id="tabLote">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Fecha</th>
                  <th>Parametro</th>
                  <th>Asignados</th>
                  <th>Liberados</th>
                  <th>Opc</th>
                </tr>
              </thead>
            </table>
          </div>
          <div class="col-md-6">
            <center><h4 class="text-warning">Datos lote</h4></center>
            <div class="row">
              <div class="col-md-6">
                <label for="">Filtros de busqueda</label>
                <div class="form-group">
                  <label for="">Parametro</label>
                    <select class="form-control select2" id="parametro"> 
                      <option value="0">Sin seleccionar</option>
                      @foreach ($model as $item)
                        <option value="{{$item->Id_parametro}}">({{$item->Id_parametro}}) {{$item->Parametro}} ({{$item->Tipo_formula}})</option>
                      @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <input type="date" style="width: 100%">
                </div>
                <div class="form-group">
                  <input type="text" placeholder="Buscar por folio" style="width: 100%">
                </div>
              </div>
              <div class="col-md-4">
                <label for=""> </label>
                <div class="form-group">
                  <button class="btn-info" id="btnBuscarLote" style="width: 100%"><i class="fas fa-search"></i> Buscar lote</button>
                </div>
                <div class="form-group">
                  <button class="btn-success" style="width: 100%"><i class="fas fa-plus"></i> Crear lote</button>
                </div>
                <div class="form-group">
                  <button class="btn-primary" style="width: 100%" id="btnPendientes" data-toggle="modal" data-target="#modalPendientes"><i class="voyager-news"></i> Pendientes</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <center><h4 class="text-success">Captura de resultados</h4></center>
        <table class="table" id="tabCaptura">
          <thead>
            <tr>
              <th>Opc</th>
              <th>Folio</th>
              <th>Norma</th>
              <th>Resultado</th>
              <th>Observacion</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>


{{-- Inicio modal pendientes --}}
<div class="modal fade" id="modalPendientes" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Pendientes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12" id="divPendientes">

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Fin modal pendientes --}}

@endsection  

@section('javascript')
    <script src="{{asset('/public/js/laboratorio/analisis/captura.js')}}?v=0.0.1"></script>
    <script src="{{ asset('/public/js/libs/componentes.js')}}"></script>
    <script src="{{ asset('/public/js/libs/tablas.js') }}"></script>
@stop