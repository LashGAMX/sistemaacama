
@extends('voyager::master')

@section('content') 

  @section('page_header')
  <h6 class="page-title">
    <i class="voyager-window-list"></i> 
    Graficos
</h6>

  @stop
<div class = "container-fluid">
    <div class="row">
        <div class="col-md-12">
            <button id="grafica1"> Grafica 1</button>
            <button id="grafica2"> Grafica 2</button>
            <button id="grafica3"> Grafica 3</button>
            <button id="grafica4"> Grafica 4</button>
        </div>
        <div class="col-md-12">
            <canvas id="myChart"></canvas>
        </div>
    </div>
</div>


@section('javascript')
  <script src="{{asset('/public/js/seguimiento/indicadores/graficos.js')}}?v=0.0.1"></script>
  {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   --}}
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>
  
@stop

@endsection
 
  