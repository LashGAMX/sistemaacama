@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title">
        <i class="fas fa-cogs"></i>
        Configuraciones
    </h6>
  @stop

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <a href="{{url('/admin/config/plantillas/bitacoras')}}">
                    <div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;">
                        <div class="dimmer"></div>
                        <div class="panel-content"> 
                            <i class="voyager-down-circled"></i>
                            <h4>Bitacoras</h4>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

  @section('javascript')
      <script src="{{asset('/public/js/analisisQ/parametro.js')}}?v=0.3.1"></script>
@stop  
@endsection
  