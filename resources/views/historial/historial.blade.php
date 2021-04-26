@extends('voyager::master')

@section('content')

  @section('page_header')

  <h6 class="page-title"> 
      <i class="voyager-folder"></i>
      Historial 
  </h6>
  @stop
  
  <div>
    {{-- Be like water. --}}

    <div class="page-content">
        <div class="alerts">
    </div>
        <div class="clearfix container-fluid row">
        <div class="col-md-3"><div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F02.jpg');">
    <div class="dimmer"></div>
    <div class="panel-content">
        <i class="voyager-params"></i>        <h4>Configuraciones</h4>
        
        <a href="https://dev.sistemaacama.com.mx/admin/historial/config" class="btn btn-primary">Ver Historial</a>
    </div>
    </div>
    </div><div class="col-md-3"><div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F01.jpg');">
    <div class="dimmer"></div>
    <div class="panel-content">
        <i class="fa fa-users"></i>        <h4>Clientes</h4>
        
        <a href="https://dev.sistemaacama.com.mx/admin/historial/config" class="btn btn-primary">Ver Historial</a>
    </div>
    </div>
    </div>
    
    
    <div class="col-md-3"><div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F01.jpg');">
        <div class="dimmer"></div>
        <div class="panel-content">
            <i class="voyager-lab"></i>        <h4>Análisis Q</h4>
            
            <a href="https://dev.sistemaacama.com.mx/admin/users" class="btn btn-primary">Ver Historial</a>
        </div>
        </div>
        </div>
    
    
        <div class="col-md-3"><div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F01.jpg');">
    <div class="dimmer"></div>
    <div class="panel-content">
        <i class="voyager-dollar"></i>        <h4>Precios</h4>
        
        <a href="https://dev.sistemaacama.com.mx/admin/users" class="btn btn-primary">Ver Historial</a>
    </div>
    </div>
    </div>
    
    <div class="col-md-3"><div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F02.jpg');">
    <div class="dimmer"></div>
    <div class="panel-content">
        <i class="voyager-bar-chart"></i>        <h4>Encuesta</h4>
        
        <a href="https://dev.sistemaacama.com.mx/admin/posts" class="btn btn-primary">Ver Historial</a>
    </div>
    </div>
    </div>
    
    
    <div class="col-md-3"><div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F01.jpg');">
        <div class="dimmer"></div>
        <div class="panel-content">
            <i class="voyager-documentation"></i>        <h4>Cotizaciones</h4>
            
            <a href="https://dev.sistemaacama.com.mx/admin/users" class="btn btn-primary">Ver Historial</a>
        </div>
        </div>
        </div>
        
    </div>
  {{-- <livewire:historial.config/> --}}
    
        
  
@endsection   
