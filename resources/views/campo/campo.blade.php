@extends('voyager::master')

@section('content')

@section('page_header')

    <h6 class="page-title">
        <i class="fa fa-truck-pickup"></i>
        Ingeniería de Campo
    </h6>

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
                    <a href="{{url('/admin/config/termometros')}}">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/thermometer_1.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="fas fa-fill"></i>
                            <h4>Termómetros</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                    <a href="{{url('/admin/conductividad-trazable')}}">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/traceableConductivity.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="voyager-treasure-open"></i>
                            <h4>Conductividad Trazables</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                    <a href="{{url('/admin/conductividad-calidad')}}">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/qualityConductivity.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="voyager-treasure-open"></i>
                            <h4>Conductividad Calidad</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                    <a href="{{url('/admin/materiales-campo')}}">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/materialCamp.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="voyager-backpack"></i>
                            <h4>Materiales Campos</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                    <a href="{{url('/admin/materiales-muestreo')}}">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/samplingMaterial.png');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="fas fa-box-open"></i>
                            <h4>Materiales Muestreos</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                    <a href="{{url('/admin/ph-trazable')}}">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/phTraceable.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="fas fa-fill"></i>
                            <h4>Ph Trazables</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{url('/admin/ph-calidad')}}">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/phQuality.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="fas fa-fill-drip"></i>
                            <h4>Ph Calidad</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{url('/admin/metodo-aforo')}}">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/methodAforo.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="fas fa-book"></i>
                            <h4>Método Aforos</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                    <a href="{{url('/admin/con-tratamiento')}}">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/withTreatments.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="fas fa-vials"></i>
                            <h4>Con Tratamientos</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{url('/admin/tipo-tratamiento')}}">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/treatmentTypes.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="fas fa-eye-dropper"></i>
                            <h4>Tipo Tratamientos</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{url('/admin/usuarios-app')}}">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/users.png');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="fas fa-user"></i>
                            <h4>Usuarios App</h4>
                        </div>
                    </div>
                </a>
            </div>

        </div>
        {{-- <livewire:historial.config/> --}}
    @endsection
