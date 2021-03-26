@extends('voyager::master')

@section('content')
  @section('page_header')
<div class="container-fluid">
  <p>Crear Cotización</p>
    <div class="row">
        <div class="col-md-12">
            @livewire('cotizacion.cotizacion-modal', ['idUser' => Auth::user()->id])
        </div>
      </div>
</div>
  @stop

@endsection
@section('javascript')
<script src="{{asset('js/cotizacion/cotizacion.js')}}"></script>
<script src="{{asset('js/libs/componentes.js')}}"></script>
<script src="{{asset('js/libs/tablas.js')}}"></script>
@stop
