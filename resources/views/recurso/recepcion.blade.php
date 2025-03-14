@extends('voyager::master')

@section('content')

@section('page_header')
    <div class="row">
        <h6 class="page-title">
            <i class="fa fa-camera"></i>
            Recepción
        </h6>
    </div>    
@stop

<div>
    {{-- Be like water. --}}

    <div class="page-content">
        <div class="alerts">
        </div>
        <div class="clearfix container-fluid row">
            <div class="col-md-3">
                <a href="{{url('')}}/admin/recursos/recepcion/appRecepcion">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/Recursos/stock-624712_1920.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="fas fa-mobile-alt"></i>
                            <h4>App</h4>
                        </div>
                    </div>
                </a>
            </div>                        
        </div>
    @endsection