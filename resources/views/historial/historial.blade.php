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
            <div class="col-md-3">
                <a href="https://dev.sistemaacama.com.mx/admin/historial/config">
                <div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/HistoryBackground/gears.jpg');">
                    <div class="dimmer"></div>
                    <div class="panel-content">
                        <i class="voyager-params"></i>
                        <h4>Configuraciones</h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <a href="https://dev.sistemaacama.com.mx/admin/historial/clientes">
                <div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/HistoryBackground/clients.png');">
                    <div class="dimmer"></div>
                    <div class="panel-content">
                        <i class="fa fa-users"></i>
                        <h4>Clientes</h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <a href="https://dev.sistemaacama.com.mx/admin/historial/analisisQ">
                <div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/HistoryBackground/qAnalysis.jpg');">
                    <div class="dimmer"></div>
                    <div class="panel-content">
                        <i class="voyager-lab"></i>
                        <h4>Análisis Q</h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <a href="https://dev.sistemaacama.com.mx/admin/historial/precios">
                <div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/HistoryBackground/prices.jpg');">
                    <div class="dimmer"></div>
                    <div class="panel-content">
                        <i class="voyager-dollar"></i>
                        <h4>Precios</h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <a href="https://dev.sistemaacama.com.mx/admin/posts">
                <div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/HistoryBackground/quiz.png');">
                    <div class="dimmer"></div>
                    <div class="panel-content">
                        <i class="voyager-bar-chart"></i>
                        <h4>Encuesta</h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <a href="https://dev.sistemaacama.com.mx/admin/users">
                <div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/HistoryBackground/quotes.jpg');">
                <div class="dimmer"></div>
                
                <div class="panel-content">
                    <i class="voyager-documentation"></i>
                    <h4>Cotizaciones</h4>
                </div>
            </div>

            <div class="col-md-3">
                <a href="https://dev.sistemaacama.com.mx/admin/historial/ingCampo">
                <div class="panel widget center bgimage"
                    style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/HistoryBackground/quotes.jpg');">
                    <div class="dimmer"></div>
                    <div class="panel-content">
                        <i class="voyager-documentation"></i>
                        <h4>Ingeniería de Campo</h4>                        
                    </div>
                </div>
            </div>

        </div>
    </div>


{{--<livewire:historial.config /> --}}
@endsection