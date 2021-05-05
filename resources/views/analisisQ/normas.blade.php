@extends('voyager::master')

@section('content')

  @section('page_header')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            @livewire('analisis-q.parametros', ['idUser' => Auth::user()->id])
        </div>
      </div>
</div>
  @stop

@endsection  
