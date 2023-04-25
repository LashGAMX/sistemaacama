@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title">
        <i class="fas fa-cogs"></i>
        Configuraciones Plantillas 
        @switch($tipo)
            @case(1)
                Fq
                @break
            @case(2)
                
                @break
            @default
                
        @endswitch
    </h6> 
  @stop

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12" id="tabPlantillas">
                
            </div>
        </div>
    </div>

  @section('javascript')
      <script src="{{asset('/public/js/config/plantillas.js')}}?v=0.2.1"></script>
@stop  
@endsection
  