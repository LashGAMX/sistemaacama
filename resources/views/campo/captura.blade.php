@extends('voyager::master')

@section('content')

  @section('page_header')
  {{-- <h6 class="page-title"> 
    <i class="fa fa-edit"></i>
    Captura
  </h6> --}}
  @stop

  <div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <a class="nav-link active" id="datosGenerales-tab" data-toggle="tab" href="#datosGenerales" role="tab" aria-controls="datosGenerales" aria-selected="true">1. Datos Generales</a>
                </li>
                <li class="nav-item" role="presentation">
                  <a class="nav-link" id="datosMuestreo-tab" data-toggle="tab" href="#datosMuestreo" role="tab" aria-controls="datosMuestreo" aria-selected="false">2. Datos muestreo</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="datosCompuestos-tab" data-toggle="tab" href="#datosCompuestos" role="tab" aria-controls="datosCompuestos" aria-selected="false">3. Datos Compuestos</a>
                  </li>
                  <li class="nav-item" role="presentation">
                    <a class="nav-link" id="evidencia-tab" data-toggle="tab" href="#evidencia" role="tab" aria-controls="evidencia" aria-selected="false">4. Evidencia</a>
                  </li>
              </ul>
        </div>
      
      </div>
    </div>

@endsection  


@section('javascript')
    <script src="{{asset('js/campo/AsignarMuestreo.js')}}"></script>
    <script src="{{asset('js/libs/componentes.js')}}"></script>
    <script src="{{asset('js/libs/tablas.js')}}"></script>
@stop

