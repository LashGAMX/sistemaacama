@extends('voyager::master')

@section('content')

@section('page_header')

    <h6 class="page-title">
        <i class="fa fa-history"></i>
        Historial Precios
    </h6>

@stop
<div>
    <div class="page-content">
        <div class="alerts">
        </div>
        <div class="clearfix container-fluid row">
            <div class="col-md-3">
                <a href="{{route('voyager.hist-preciocatalogo.index')}}">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/HistoryBackground/catalogue.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="fa fa-store"></i>
                            <h4>Catálogo</h4>
                        </div>
                    </div>
                </a>
            </div>                    

            <div class="col-md-3">
                <a href="{{route('voyager.hist-analisispara.index')}}">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/HistoryBackground/box.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="fa fa-box"></i>
                            <h4>Paquete</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{route('voyager.hist-analisispara.index')}}">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/HistoryBackground/business.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="fa fa-user"></i>
                            <h4>Intermediario</h4>
                        </div>
                    </div>
                </a>
            </div>            

        </div>

    @endsection
