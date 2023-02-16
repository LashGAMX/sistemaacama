@extends('voyager::master')

@section('content')

@section('page_header')
    <div class="row">
        <h6 class="page-title">
            <i class="voyager-file-text"></i>
            Reportes
        </h6>
    </div>

   
@stop

<div>
    
    <div class="page-content">
        <div class="alerts">
        </div>
        <div class="clearfix container-fluid row">
            <div class="col-md-3">
                    <a href="{{url('/admin/reportes-informes')}}">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="voyager-file-text"></i>
                            <h4>Informe de Resultados</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="clearfix container-fluid row">
                <div class="col-md-3">
                        <a href="{{url('/admin/reportes-cadena')}}">
                        <div class="panel widget center bgimage"
                            style="margin-bottom:0;overflow:hidden;background-image:url('');">
                            <div class="dimmer"></div>
                            <div class="panel-content">
                                <i class="voyager-file-text"></i>
                                <h4>Cadena de custodia</h4>
                            </div>
                        </div>
                    </a>
                </div>
    
            
        </div>
        
    @endsection