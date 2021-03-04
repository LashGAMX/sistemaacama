@extends('voyager::master')
@section('content')

<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="menu">
      <a class="nav-link active" id="laboratorio-tab" data-toggle="tab" href="#laboratorio" role="tab" aria-controls="laboratorio" aria-selected="true">Laboratorio</a>
    </li>
    <li class="nav-item" role="menu">
      <a class="nav-link" id="simbologia-tab" data-toggle="tab" href="#simbologia" role="tab" aria-controls="simbologia" aria-selected="false">Simbologia</a>
    </li>
    <li class="nav-item" role="menu">
      <a class="nav-link" id="unidad-tab" data-toggle="tab" href="#unidad" role="tab" aria-controls="unidad" aria-selected="false">Unidad</a>
    </li>
  </ul>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="laboratorio" role="tabpanel" aria-labelledby="laboratorio-tab">  
    </div>
    <div class="tab-pane fade" id="simbologia" role="tabpanel" aria-labelledby="simbologia-tab">Simbologia</div>
    <div class="tab-pane fade" id="unidad" role="tabpanel" aria-labelledby="unidad-tab">Unidad</div>
  </div>
@endsection