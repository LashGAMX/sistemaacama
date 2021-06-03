@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="fa fa-check"></i>
    Captura
  </h6>
  @stop

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="col-md-2">
          <input type="Nombre" class="form-control" placeholder="nombre">
        </div>
        <div class="col-md-2">
          <input type="Folio" class="form-control" placeholder="folio">
        </div>
        <div class="col-md-2"> 
          <select type="Mes" class="form-control" placeholder="">
            <option>mes</option>
            <option>Enero</option>
            <option>Febrero</option>
            <option>Marzo</option>
            <option>Abril</option>
            <option>Mayo</option>
            <option>Junio</option>
            <option>Julio</option>
            <option>Agosto</option>
            <option>Septiembre</option>
            <option>Octubre</option>
            <option>Noviembre</option>
            <option>Diciembre</option>
        </select>
        </div>
        <div class="col-md-2">
          <input type="Año" class="form-control" placeholder="Año">
        </div> 
        <div class="col-md-2">
          <button type="button" class="btn btn-success"><i class="fa fa-search"></i> Buscar</button>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
      <div class="col-md-1">
        <button type="button" id="btnImprimir" class="btn btn-basic"><i class="fa fa-print"></i> Imprimir</button>
      </div>
      <div class="col-md-1">
        <button type="button" id="btnGenerar" class="btn btn-success"><i class="fa fa-plus"></i> Generar</button>
      </div>
      <div class="col-md-1">
        <button type="button" id="btnPlanMuestreo" class="btn btn-primary"><i class="fa fa-tools" ></i> Plan de muestreo</button>
      </div>
      </div>
    </div>

@endsection  


@section('javascript')
    <script src="{{asset('js/campo/AsignarMuestreo.js')}}"></script>
    <script src="{{asset('js/libs/componentes.js')}}"></script>
    <script src="{{asset('js/libs/tablas.js')}}"></script>
@stop

