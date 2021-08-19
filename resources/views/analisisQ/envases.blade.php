@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="fa fa-wine-bottle"></i>
    Envases
  </h6>
 
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            @livewire('analisis-q.envases', ['idUser' => Auth::user()->id])
        </div>
      </div>
</div>
  @stop

@endsection  
