@extends('voyager::master')

@section('content')

@section('page_header')

    <h6 class="page-title">
        <i class="fa fa-history"></i>
        Historial Datos Muestreo
    </h6>

@stop
<div>
    <div class="page-content">
        <div class="alerts">
        </div>
        <div class="clearfix container-fluid row">
            
            <div class="col-md-3">
                <a href="{{route('voyager.hist-campocaptmuestreoph.index')}}">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/HistoryBackground/gears_2.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="voyager-params"></i>
                            <h4>Ph</h4>
                        </div>
                    </div>
                </a>
            </div>  
            
            
            <div class="col-md-3">
                <a href="{{route('voyager.hist-campocaptmuestreotemp.index')}}">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/HistoryBackground/rules.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="voyager-book"></i>
                            <h4>Temperatura</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{route('voyager.hist-campocaptmuestreophcalidad.index')}}">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/HistoryBackground/maths.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="voyager-code"></i>
                            <h4>Ph control calidad</h4>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-md-3">
                <a href="{{route('voyager.hist-campocaptmuestreoconductividad.index')}}">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/HistoryBackground/maths.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="voyager-code"></i>
                            <h4>Conductividad</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{route('voyager.hist-campocaptmuestreogasto.index')}}">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/HistoryBackground/maths.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="voyager-code"></i>
                            <h4>Gasto</h4>
                        </div>
                    </div>
                </a>
            </div>

        </div>

    @endsection