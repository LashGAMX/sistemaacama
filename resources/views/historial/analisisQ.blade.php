@extends('voyager::master')

@section('content')

  @section('page_header')

  <h6 class="page-title"> 
      <i class="fa fa-history"></i>
      Historial Análisis Q
  </h6>
   
  @stop
  <div>
    <div class="page-content">
        <div class="alerts">
    </div>
        <div class="clearfix container-fluid row">
        <div class="col-md-3"><div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F02.jpg');">
    <div class="dimmer"></div>
    <div class="panel-content">
        <i class="voyager-params"></i>        <h4>Parametros</h4>
        
        <a href="{{route("voyager.hist-analisispara.index")}}" class="btn btn-primary">Ver Historial</a>
    </div>
    </div>
    </div><div class="col-md-3"><div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F01.jpg');">
    <div class="dimmer"></div>
    <div class="panel-content">
        <i class="voyager-book"></i>        <h4>Normas</h4>
        
        <a href="https://dev.sistemaacama.com.mx/admin/historial/clientes" class="btn btn-primary">Ver Historial</a>
    </div>
    </div>
    </div>
    
    
    <div class="col-md-3"><div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F01.jpg');">
        <div class="dimmer"></div>
        <div class="panel-content">
            <i class="voyager-code"></i>        <h4>Limites</h4>
            
            <a href="https://dev.sistemaacama.com.mx/admin/historial/analisisQ" class="btn btn-primary">Ver Historial</a>
        </div>
        </div>
        </div>

  </div>

@endsection   