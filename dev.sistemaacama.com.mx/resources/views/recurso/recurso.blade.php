@extends('voyager::master')

@section('content')

@section('page_header')
    <div class="row">
        <h6 class="page-title">
          <i class="fas fa-wrench"></i>
            Recursos
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
                <a href="https://sistemaacama.com.mx/admin/recursos/ingCampo">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/fieldEngineering.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="fa fa-truck-pickup"></i>
                            <h4>Ing.Campo</h4>
                        </div>
                    </div>
                </a>
            </div>                        
        </div>
        {{-- <livewire:historial.config/> --}}
    @endsection
 