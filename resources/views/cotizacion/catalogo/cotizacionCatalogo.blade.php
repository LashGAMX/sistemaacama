@extends('voyager::master')

@section('content')

  @section('page_header')

  <h6 class="page-title">
      <i class="voyager-exclamation"></i>
      Cotización
  </h6>
  @stop

  <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="menu">
      <a class="nav-link active" id="Tipo-tab" data-toggle="tab" href="#Tipo" role="tab" aria-controls="Tipo" aria-selected="true">Tipo de Muestra </a>
    </li>
    <li class="nav-item" role="menu">
      <a class="nav-link" id="matriz-tab" data-toggle="tab" href="#matriz" role="tab" aria-controls="matriz" aria-selected="false">Promedio</a>
    </li>
    <li class="nav-item" role="menu">
      <a class="nav-link" id="rama-tab" data-toggle="tab" href="#rama" role="tab" aria-controls="rama" aria-selected="false">Reporte</a>
    </li>
    <li class="nav-item" role="menu">
      <a class="nav-link" id="tecnica-tab" data-toggle="tab" href="#tecnica" role="tab" aria-controls="tecnica" aria-selected="false">Estado de Cotización</a>
    </li>
    <li class="nav-item" role="menu">
      <a class="nav-link" id="area-tab" data-toggle="tab" href="#area" role="tab" aria-controls="area" aria-selected="false">Tipo Descarga</a>
    </li>
    <li class="nav-item" role="menu">
      <a class="nav-link" id="metodo-tab" data-toggle="tab" href="#metodo" role="tab" aria-controls="metodo" aria-selected="false">Tipo de Servicio</a>
    </li>
  </ul>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade  active" id="Tipo" role="tabpanel" aria-labelledby="Tipo-tab">
      @livewire('cotizacion.table-tipo-muestra',['idUser'=>$idUser])
    <p>hello </p>
 </div>
 <div class="tab-pane fade" id="matriz" role="tabpanel" aria-labelledby="matriz-tab">
    @livewire('config.table-tipo-muestra', ['idUser'=>$idUser])
    Promedio
  </div>
  <div class="tab-pane fade" id="rama" role="tabpanel" aria-labelledby="rama-tab">
    {{-- @livewire('config.table-rama', ['idUser'=>$idUser]) --}}
    Reporte
  </div>
  <div class="tab-pane fade" id="tecnica" role="tabpanel" aria-labelledby="tecnica-tab">
    {{-- @livewire('config.table-tecnica', ['idUser'=>$idUser]) --}}
    Estado de Cotización
  </div>
  <div class="tab-pane fade" id="area" role="tabpanel" aria-labelledby="area-tab">
    {{-- @livewire('config.table-area-analisis', ['idUser'=>$idUser]) --}}
    Tipo Descarga
  </div>
  <div class="tab-pane fade" id="metodo" role="tabpanel" aria-labelledby="metodo-tab">
    {{-- @livewire('config.table-metodo-prueba', ['idUser'=>$idUser]) --}}
    Tipo de Servicio
  </div>


  </div>
@endsection
