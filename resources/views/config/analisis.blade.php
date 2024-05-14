@extends('voyager::master')

@section('content')

  @section('page_header')

  <h6 class="page-title"> 
      <i class="voyager-exclamation"></i>
      Análisis
  </h6>
  @stop

  <ul class="nav nav-tabs" id="myTab" role="tablist"> 
    <li class="nav-item" role="menu">
      <a class="nav-link active" id="Tipo-tab" data-toggle="tab" href="#Tipo" role="tab" aria-controls="Tipo" aria-selected="true">Tipo fórmula </a>
    </li>
    <li class="nav-item" role="menu">
      <a class="nav-link" id="matriz-tab" data-toggle="tab" href="#matriz" role="tab" aria-controls="matriz" aria-selected="false">Matraz</a>
    </li>
    <li class="nav-item" role="menu">
      <a class="nav-link" id="rama-tab" data-toggle="tab" href="#rama" role="tab" aria-controls="rama" aria-selected="false">Rama</a>
    </li>
    <li class="nav-item" role="menu">
      <a class="nav-link" id="tecnica-tab" data-toggle="tab" href="#tecnica" role="tab" aria-controls="tecnica" aria-selected="false">Técnica</a>
    </li>
    <li class="nav-item" role="menu">
      <a class="nav-link" id="area-tab" data-toggle="tab" href="#area" role="tab" aria-controls="area" aria-selected="false">Área de análisis</a>
    </li>
    <li class="nav-item" role="menu">
      <a class="nav-link" id="metodo-tab" data-toggle="tab" href="#metodo" role="tab" aria-controls="metodo" aria-selected="false">Método prueba</a>
    </li>
    <li class="nav-item" role="menu">
      <a class="nav-link" id="simbologia-tab" data-toggle="tab" href="#simbologia" role="tab" aria-controls="simbologia" aria-selected="false">Simbología</a>
    </li>
    <li class="nav-item" role="menu">
      <a class="nav-link" id="procedimiento-tab" data-toggle="tab" href="#procedimiento" role="tab" aria-controls="procedimiento" aria-selected="false">Procedimiento</a>
    </li>
    <li class="nav-item" role="menu">
      <a class="nav-link" id="matrizGA-tab" data-toggle="tab" href="#matrizGA" role="tab" aria-controls="matrizGA" aria-selected="false">Matraz GA</a>
    </li>
    <li class="nav-item" role="menu">
      <a class="nav-link" id="crisolGA-tab" data-toggle="tab" href="#crisolGA" role="tab" aria-controls="crisolGA" aria-selected="false">Crisol GA</a>
    </li>
    <li class="nav-item" role="menu">
      <a class="nav-link" id="crisolGA-tab" data-toggle="tab" href="#capsulas" role="tab" aria-controls="capsulas" aria-selected="false">Capsulas</a>
    </li>
  </ul>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade  active" id="Tipo" role="tabpanel" aria-labelledby="Tipo-tab">  
      @livewire('config.table-tipo-formula',['idUser'=>$idUser])
    </div> 
    <div class="tab-pane fade" id="matriz" role="tabpanel" aria-labelledby="matriz-tab">
      @livewire('config.table-matriz-parametro', ['idUser'=>$idUser])
    </div>
    <div class="tab-pane fade" id="rama" role="tabpanel" aria-labelledby="rama-tab">
      @livewire('config.table-rama', ['idUser'=>$idUser])
    </div>
    <div class="tab-pane fade" id="tecnica" role="tabpanel" aria-labelledby="tecnica-tab">
      @livewire('config.table-tecnica', ['idUser'=>$idUser])
    </div>
    <div class="tab-pane fade" id="area" role="tabpanel" aria-labelledby="area-tab">
      @livewire('config.table-area-analisis', ['idUser'=>$idUser])
    </div>
    <div class="tab-pane fade" id="metodo" role="tabpanel" aria-labelledby="metodo-tab">
      @livewire('config.table-metodo-prueba', ['idUser'=>$idUser])
    </div>
    <div class="tab-pane fade" id="simbologia" role="tabpanel" aria-labelledby="simbologia-tab">
      @livewire('config.table-simbologia-parametro', ['idUser'=>$idUser])
    </div>
    <div class="tab-pane fade" id="procedimiento" role="tabpanel" aria-labelledby="procedimiento-tab">
      @livewire('config.procedimiento',['idUser'=>$idUser])
    </div>
    <div class="tab-pane fade" id="matrizGA" role="tabpanel" aria-labelledby="matrizGA-tab">
      @livewire('config.matriz-g-a',['idUser'=>$idUser])
    </div>
    <div class="tab-pane fade" id="crisolGA" role="tabpanel" aria-labelledby="crisolGA-tab">
      @livewire('config.crisol-g-a',['idUser'=>$idUser])
    </div>
    <div class="tab-pane fade" id="capsulas" role="tabpanel" aria-labelledby="capsulas-tab">
      @livewire('config.capsulas',['idUser'=>$idUser])
    </div>
  </div>
@endsection   
