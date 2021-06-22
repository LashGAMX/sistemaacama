@extends('voyager::master')

@section('content')

@section('page_header')
    <h6 class="page-title">
        <i class="fas fa-cogs"></i>
        Configuraciones
    </h6>
@stop

<div>
    {{-- Be like water. --}}

    <div class="page-content">
        <div class="alerts">
        </div>
        <div class="clearfix container-fluid row">
            <div class="col-md-3">
                <div class="panel widget center bgimage"
                    style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F02.jpg');">
                    <div class="dimmer"></div>
                    <div class="panel-content">
                        <i class="voyager-lab"></i>
                        <h4>Laboratorio</h4>

                        <a href="https://dev.sistemaacama.com.mx/admin/config/laboratorio" class="btn btn-primary">Ver
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel widget center bgimage"
                    style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F01.jpg');">
                    <div class="dimmer"></div>
                    <div class="panel-content">
                        <i class="voyager-exclamation"></i>
                        <h4>Análisis</h4>

                        <a href="https://dev.sistemaacama.com.mx/admin/config/analisis" class="btn btn-primary">Ver
                        </a>
                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <div class="panel widget center bgimage"
                    style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F01.jpg');">
                    <div class="dimmer"></div>
                    <div class="panel-content">
                        <i class="fa fa-concierge-bell"></i>
                        <h4>Tipo Servicios</h4>

                        <a href="https://dev.sistemaacama.com.mx/admin/tipo-servicios" class="btn btn-primary">Ver
                        </a>
                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <div class="panel widget center bgimage"
                    style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F01.jpg');">
                    <div class="dimmer"></div>
                    <div class="panel-content">
                        <i class="voyager-pie-chart"></i>
                        <h4>Costo Muestreos</h4>

                        <a href="https://dev.sistemaacama.com.mx/admin/costo-muestreo" class="btn btn-primary">Ver
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="panel widget center bgimage"
                    style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F02.jpg');">
                    <div class="dimmer"></div>
                    <div class="panel-content">
                        <i class="fas fa-map-marked-alt"></i>
                        <h4>Localidades</h4>

                        <a href="https://dev.sistemaacama.com.mx/admin/localidades" class="btn btn-primary">Ver
                        </a>
                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <div class="panel widget center bgimage"
                    style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F01.jpg');">
                    <div class="dimmer"></div>
                    <div class="panel-content">
                        <i class="voyager-location"></i>
                        <h4>Estados</h4>

                        <a href="https://dev.sistemaacama.com.mx/admin/estados" class="btn btn-primary">Ver
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="panel widget center bgimage"
                    style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F01.jpg');">
                    <div class="dimmer"></div>
                    <div class="panel-content">
                        <i class="fa fa-truck-pickup"></i>
                        <h4>Ing. Campo</h4>

                        <a href="https://dev.sistemaacama.com.mx/admin/config/campo" class="btn btn-primary">Ver
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="panel widget center bgimage"
                    style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F01.jpg');">
                    <div class="dimmer"></div>
                    <div class="panel-content">
                        <i class="voyager-treasure-open"></i>
                        <h4>Conductividad Trazables</h4>

                        <a href="https://dev.sistemaacama.com.mx/admin/conductividad-trazable" class="btn btn-primary">Ver
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="panel widget center bgimage"
                    style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F01.jpg');">
                    <div class="dimmer"></div>
                    <div class="panel-content">
                        <i class="voyager-treasure-open"></i>
                        <h4>Conductividad Calidad</h4>

                        <a href="https://dev.sistemaacama.com.mx/admin/conductividad-calidad" class="btn btn-primary">Ver
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="panel widget center bgimage"
                    style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F01.jpg');">
                    <div class="dimmer"></div>
                    <div class="panel-content">
                        <i class="voyager-backpack"></i>
                        <h4>Materiales Campos</h4>

                        <a href="https://dev.sistemaacama.com.mx/admin/materiales-campo" class="btn btn-primary">Ver
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="panel widget center bgimage"
                    style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F01.jpg');">
                    <div class="dimmer"></div>
                    <div class="panel-content">
                        <i class="fas fa-box-open"></i>
                        <h4>Materiales Muestreos</h4>

                        <a href="https://dev.sistemaacama.com.mx/admin/materiales-muestreo" class="btn btn-primary">Ver
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="panel widget center bgimage"
                    style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F01.jpg');">
                    <div class="dimmer"></div>
                    <div class="panel-content">
                        <i class="fas fa-fill"></i>
                        <h4>Ph Trazables</h4>

                        <a href="https://dev.sistemaacama.com.mx/admin/ph-trazable" class="btn btn-primary">Ver
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="panel widget center bgimage"
                    style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F01.jpg');">
                    <div class="dimmer"></div>
                    <div class="panel-content">
                        <i class="fas fa-fill-drip"></i>
                        <h4>Ph Calidades</h4>

                        <a href="https://dev.sistemaacama.com.mx/admin/ph-calidad" class="btn btn-primary">Ver
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="panel widget center bgimage"
                    style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F01.jpg');">
                    <div class="dimmer"></div>
                    <div class="panel-content">
                        <i class="fas fa-book"></i>
                        <h4>Método Aforos</h4>

                        <a href="https://dev.sistemaacama.com.mx/admin/metodo-aforo" class="btn btn-primary">Ver
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="panel widget center bgimage"
                    style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F01.jpg');">
                    <div class="dimmer"></div>
                    <div class="panel-content">
                        <i class="fas fa-vials"></i>
                        <h4>Con Tratamientos</h4>

                        <a href="https://dev.sistemaacama.com.mx/admin/con-tratamiento" class="btn btn-primary">Ver
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="panel widget center bgimage"
                    style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F01.jpg');">
                    <div class="dimmer"></div>
                    <div class="panel-content">
                        <i class="fas fa-eye-dropper"></i>
                        <h4>Tipo Tratamientos</h4>

                        <a href="https://dev.sistemaacama.com.mx/admin/tipo-tratamiento" class="btn btn-primary">Ver
                        </a>
                    </div>
                </div>
            </div>

        </div>

    @endsection

    @section('javascript')

    @stop
