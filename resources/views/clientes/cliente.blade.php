@extends('voyager::master')

@section('content')

  @section('page_header')

  <h6 class="page-title"> 
      <i class="voyager-person"></i>
      General
  </h6>
  <div class="row">
    <div class="col-md-12">
        @livewire('clientes.table-cliente', ['idUser' => Auth::user()->id])
    </div>
  </div>
  @stop

@endsection  
