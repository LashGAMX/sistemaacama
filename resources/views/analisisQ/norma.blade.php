@extends('voyager::master')

@section('content')

  @section('page_header')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            @livewire('analisis-q.normas', ['idUser' => Auth::user()->id])
        </div>
      </div>
</div> 
  @stop

@endsection  
