@extends('voyager::master')

@section('content')

  @section('page_header')
    <h6>Seguimiento de muestra</h6>
  @stop 

  <div class="row">
    <div class="col-md-12">
        
    </div>
  </div>

  @section('javascript')
  <script src="{{asset('/public/js/seguimiento/muestra.js')}}?v=0.0.1"></script>
@stop
@endsection 
