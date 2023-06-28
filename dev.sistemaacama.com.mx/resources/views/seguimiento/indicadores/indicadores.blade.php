
@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title">
    <i class="voyager-window-list"></i>
    Indicadores
</h6>
  @stop
<div class = "container-fluid">
</div>

@section('javascript')
<script src="{{asset('/public/js/seguimiento/indicadores/indicadores.js')}}?v=0.0.1"></script>
@stop

@endsection
 
 