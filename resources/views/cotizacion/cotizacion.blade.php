@extends('voyager::master')

@section('content')

  @section('page_header')
<div class="container-fluid">
    <div class="row">
        <p>Cotización 📂</p>
        <div class="col-md-12">
            @livewire('cotizacion.cotizacion', ['idUser' => Auth::user()->id])
        </div>
      </div>
</div>
  @stop

@endsection
