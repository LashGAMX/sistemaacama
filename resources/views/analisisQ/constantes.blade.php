@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="fab fa-cuttlefish"></i>
    Constantes
  </h6>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
         @livewire('analisis-q.constantes')
        </div>
      </div>
</div>  
  @stop

@endsection  
