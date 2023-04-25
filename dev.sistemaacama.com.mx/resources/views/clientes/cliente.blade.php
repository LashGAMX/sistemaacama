@extends('voyager::master')

@section('content')

  @section('page_header')

  <div class="row">
    <div class="col-md-12">
        @livewire('clientes.table-cliente', ['idUser' => Auth::user()->id])
    </div>
  </div>
  @stop

@endsection  
 