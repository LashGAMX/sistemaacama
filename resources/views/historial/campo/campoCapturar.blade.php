@extends('voyager::master')

@section('content')

@section('page_header')

    <h6 class="page-title">
        <i class="fa fa-history"></i>
        Historial Capturar
    </h6>

@stop
<div>
    <div class="page-content">
        <div class="alerts">
        </div>
        <div class="clearfix container-fluid row">
            
            <div class="col-md-3">
                <a href="https://dev.sistemaacama.com.mx/admin/historial/ingCampo/capturar/generales">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/HistoryBackground/gears_2.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="voyager-params"></i>
                            <h4>Datos generales</h4>
                        </div>
                    </div>
                </a>
            </div>  
            
            
            <div class="col-md-3">
                <a href="https://dev.sistemaacama.com.mx/admin/historial/ingCampo/capturar/muestreo">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/HistoryBackground/rules.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="voyager-book"></i>
                            <h4>Datos muestreo</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{route('voyager.hist-campocaptcompuesto.index')}}">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/HistoryBackground/maths.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="voyager-code"></i>
                            <h4>Datos compuestos</h4>
                        </div>
                    </div>
                </a>
            </div>            

        </div>

    @endsection