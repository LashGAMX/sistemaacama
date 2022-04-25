@extends('voyager::master')

@section('content')

@section('page_header')
    <div class="row">
        <h6 class="page-title">
            <i class="fas fa-cogs"></i>
            Configuraciones
        </h6>
    </div>

    <div class="row">
        <div class="col-md-6">
            <input type="search" class="form-control" placeholder="Buscar">
        </div>
    </div>
@stop

<div>
    {{-- Be like water. --}}

    <div class="page-content">
        <div class="alerts">
        </div>
        <div class="clearfix container-fluid row">
            <div class="col-md-3">
                    <a href="{{url('/admin/config/laboratorio')}}">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/laboratory.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="voyager-lab"></i>
                            <h4>Laboratorio</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                    <a href="{{url('/admin/config/analisis')}}">
                    <div class=" panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/analysis.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="voyager-exclamation"></i>
                            <h4>Análisis</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{url('/admin/tipo-servicios')}}">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F02.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="fa fa-concierge-bell"></i>
                            <h4>Tipo Servicios</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{url('/admin/costo-muestreo')}}">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/accountancy.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="voyager-pie-chart"></i>
                            <h4>Costo Muestreos</h4>
                        </div>
                    </div>
                </a>
            </div>



            <div class="col-md-3">
                <a href="{{url('/admin/localidades')}}">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/localities-min.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="fas fa-map-marked-alt"></i>
                            <h4>Localidades</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{url('/admin/estados')}}">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/mexicoMap.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="voyager-location"></i>
                            <h4>Estados</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{url('/admin/config/campo')}}">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/fieldEngineering.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="fa fa-truck-pickup"></i>
                            <h4>Ing. Campo</h4>
                        </div>
                    </div>
                </a>
            </div>  
            
            <div class="col-md-3">
                <a href="{{url('/admin/areas-lab')}}">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/labArea.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="fas fa-microscope"></i>
                            <h4>Áreas Laboratorios</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{url('/admin/areas-categoria')}}">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/categoryLab.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="fas fa-vial"></i>
                            <h4>Áreas Categoría</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{url('/admin/areas-categoria')}}">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/testTube.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="fas fa-thermometer"></i>
                            <h4>Materiales Laboratorios</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{url('/admin/nmp1-micro')}}">
                        <div class="panel widget center bgimage"
                            style="margin-bottom:0;overflow:hidden;background-image:url('https://sistemaacama.com.mx/public/storage/configuraciones_fondos/numeroProbable.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="voyager-bar-chart"></i>
                            <h4>NMP1 Micros</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{url('/admin/tipo-descargas')}}">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://sistemaacama.com.mx/public/storage/configuraciones_fondos/descarga_residual.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="voyager-down-circled"></i>
                            <h4>Tipo Descargas</h4>
                        </div>
                    </div>
                </a>
            </div>

        </div>
        {{-- <livewire:historial.config/> --}}
    @endsection
