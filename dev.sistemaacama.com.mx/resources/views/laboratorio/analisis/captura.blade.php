@extends('voyager::master')

@section('content')

  @section('page_header')
  @stop

  <div class="container-fluid">
    <div class="row">

    </div>
  </div>

@endsection  

@section('javascript')
    <script src="{{asset('/public/js/laboratorio/analisis/captura.js')}}?v=0.0.1"></script>
    <script src="{{ asset('/public/js/libs/componentes.js')}}"></script>
    <script src="{{ asset('/public/js/libs/tablas.js') }}"></script>
@stop