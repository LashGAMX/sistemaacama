@extends('voyager::master')
@section('content')

<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="menu">
      <a class="nav-link" id="sucursal-tab" data-toggle="tab" href="#sucursal" role="tab" aria-controls="sucursal" aria-selected="true">Sucursal</a>
    </li>
    <li class="nav-item" role="menu">
      <a class="nav-link" id="simbologia-tab" data-toggle="tab" href="#simbologia" role="tab" aria-controls="simbologia" aria-selected="false">Simbologia</a>
    </li>
    <li class="nav-item" role="menu">
      <a class="nav-link" id="unidad-tab" data-toggle="tab" href="#unidad" role="tab" aria-controls="unidad" aria-selected="false">Unidad</a>
    </li>
  </ul>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="sucursal" role="tabpanel" aria-labelledby="sucursal-tab">  

    
@section('page_header')
<h1 class="page-title">
    <i class="{{ $dataType->icon }}"></i>
    {{ __('voyager::generic.'.(isset($dataTypeContent->id) ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular') }}
</h1>
@stop


    </div>
    <div class="tab-pane fade" id="simbologia" role="tabpanel" aria-labelledby="simbologia-tab">Simbologia</div>
    <div class="tab-pane fade" id="unidad" role="tabpanel" aria-labelledby="unidad-tab">Unidad</div>
  </div>
@endsection 