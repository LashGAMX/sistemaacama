@extends('voyager::master')

@section('content')

  @section('page_header')

  <h6 class="page-title"> 
      <i class="voyager-exclamation"></i>
      Capacitación
  </h6>
  @stop

  <div class="row">
    <div class="col-md-12">
        @livewire('capacitacion.capacitacion')
    </div>
  </div>

@endsection    
