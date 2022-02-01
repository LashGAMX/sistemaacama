@extends('voyager::master')

@section('content')

  @section('page_header')
  {{-- <h6 class="page-title"> 
      <i class="voyager-lab"></i>
      Concentraciones
  </h6> --}}
  @stop
  {{-- <div class="page-content">
    <div class="alerts">
    </div>
    <div class="clearfix container-fluid row">
        <div class="col-md-2">
                <div class="panel widget center bgimage"
                    style="max-height:5dp 15dp margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/laboratory.jpg');">
                    <div class="dimmer"></div>
                    <div class="panel-content">
                        <h4>Norma 001</h4>
                    </div>
                </div>
        </div>
        <div class="col-md-2">
                <div class=" panel widget center bgimage"
                    style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/analysis.jpg');">
                    <div class="dimmer"></div>
                    <div class="panel-content">
                        <h4>Norma 127</h4>
                    </div>
                </div>
        </div>
        <div class="col-md-2">
            <div class=" panel widget center bgimage"
                style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F02.jpg');">
                <div class="dimmer"></div>
                <div class="panel-content">
                    <h4>Norma 003</h4>
                </div>
            </div>
    </div>
            <div class="col-md-2">
                <div class=" panel widget center bgimage"
                    style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/accountancy.jpg');">
                    <div class="dimmer"></div>
                    <div class="panel-content">
                        <h4>Norma 004</h4>
                    </div>
                </div>
        </div>
        <div class="col-md-2">
            <div class=" panel widget center bgimage"
                style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/analysis.jpg');">
                <div class="dimmer"></div>
                <div class="panel-content">
                    <h4>Norma 005</h4>
                </div>
            </div>
        </div>
    </div> --}}

    <ul class="nav nav-tabs" id="myTab" role="tablist"> 
        <li class="nav-item" role="menu">
          <a class="nav-link active" id="1-tab" data-toggle="tab" href="#Tipo" role="tab" aria-controls="Tipo" aria-selected="true">Norma 001</a>
        </li>
        <li class="nav-item" role="menu">
          <a class="nav-link" id="2-tab" data-toggle="tab" href="#matriz" role="tab" aria-controls="matriz" aria-selected="false">Norma 127</a>
        </li>
        <li class="nav-item" role="menu">
          <a class="nav-link" id="3-tab" data-toggle="tab" href="#rama" role="tab" aria-controls="rama" aria-selected="false">Norma 003</a>
        </li>
        <li class="nav-item" role="menu">
          <a class="nav-link" id="4-tab" data-toggle="tab" href="#tecnica" role="tab" aria-controls="tecnica" aria-selected="false">Norma 004</a>
        </li>
        <li class="nav-item" role="menu">
          <a class="nav-link" id="5-tab" data-toggle="tab" href="#area" role="tab" aria-controls="area" aria-selected="false">Norma 005</a>
        </li>
      </ul>

@endsection  
@section('javascript')
    <script src="{{ asset('/js/config/concentracion.js')}}"></script>
@stop