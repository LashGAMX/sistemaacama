@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="voyager-window-list"></i>
    Análisis
  </h6>
 
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            @livewire('analisis-q.analisis-l', ['idUser' => Auth::user()->id])
        </div>
      </div>
</div>
  @stop

@endsection  