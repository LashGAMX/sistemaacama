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
        <div class="clearfix container-fluid row" id="container">
            <div class="col-md-3" id="img">
                <a href="https://dev.sistemaacama.com.mx/admin/config/laboratorio">
                    <div class="panel widget center bgimage"
                        style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/laboratory.jpg');">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="voyager-lab"></i>
                            <h4>Laboratorio</h4>
                </a>
            </div>
        </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="https://dev.sistemaacama.com.mx/admin/config/analisis">
                    <div class=" panel widget center bgimage"
            style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/analysis.jpg');">
            <div class="dimmer"></div>
            <div class="panel-content">
                <i class="voyager-exclamation"></i>
                <h4>Análisis</h4>
        </a>
    </div>
</div>
</a>
</div>

<div class="col-md-3">
    <a href="https://dev.sistemaacama.com.mx/admin/tipo-servicios">
        <div class="panel widget center bgimage"
            style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx/admin/voyager-assets?path=images%2Fwidget-backgrounds%2F02.jpg');">
            <div class="dimmer"></div>
            <div class="panel-content">
                <i class="fa fa-concierge-bell"></i>
                <h4>Tipo Servicios</h4>
    </a>
</div>
</div>
</a>
</div>

<div class="col-md-3">
    <a href="https://dev.sistemaacama.com.mx/admin/costo-muestreo">
        <div class="panel widget center bgimage"
            style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/accountancy.jpg');">
            <div class="dimmer"></div>
            <div class="panel-content">
                <i class="voyager-pie-chart"></i>
                <h4>Costo Muestreos</h4>
    </a>
</div>
</div>
</a>
</div>

<div class="col-md-3">
    <a href="https://dev.sistemaacama.com.mx/admin/localidades">
        <div class="panel widget center bgimage"
            style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/localities-min.jpg');">
            <div class="dimmer"></div>
            <div class="panel-content">
                <i class="fas fa-map-marked-alt"></i>
                <h4>Localidades</h4>
    </a>
</div>
</div>
</a>
</div>

<div class="col-md-3">
    <a href="https://dev.sistemaacama.com.mx/admin/estados">
        <div class="panel widget center bgimage"
            style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/mexicoMap.jpg');">
            <div class="dimmer"></div>
            <div class="panel-content">
                <i class="voyager-location"></i>
                <h4>Estados</h4>
    </a>
</div>
</div>
</a>
</div>

<div class="col-md-3">
    <a href="https://dev.sistemaacama.com.mx/admin/config/campo">
        <div class="panel widget center bgimage"
            style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/fieldEngineering.jpg');">
            <div class="dimmer"></div>
            <div class="panel-content">
                <i class="fa fa-truck-pickup"></i>
                <h4>Ing. Campo</h4>
    </a>
</div>
</div>
</a>
</div>

<div class="col-md-3">
    <a href="https://dev.sistemaacama.com.mx/admin/conductividad-trazable">
        <div class="panel widget center bgimage"
            style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/traceableConductivity.jpg');">
            <div class="dimmer"></div>
            <div class="panel-content">
                <i class="voyager-treasure-open"></i>
                <h4>Conductividad Trazables</h4>
    </a>
</div>
</div>
</a>
</div>

<div class="col-md-3">
    <a href="https://dev.sistemaacama.com.mx/admin/conductividad-calidad">
        <div class="panel widget center bgimage"
            style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/qualityConductivity.jpg');">
            <div class="dimmer"></div>
            <div class="panel-content">
                <i class="voyager-treasure-open"></i>
                <h4>Conductividad Calidad</h4>
    </a>
</div>
</div>
</a>
</div>

<div class="col-md-3">
    <a href="https://dev.sistemaacama.com.mx/admin/materiales-campo">
        <div class="panel widget center bgimage"
            style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/materialCamp.jpg');">
            <div class="dimmer"></div>
            <div class="panel-content">
                <i class="voyager-backpack"></i>
                <h4>Materiales Campos</h4>
    </a>
</div>
</div>
</a>
</div>

<div class="col-md-3">
    <a href="https://dev.sistemaacama.com.mx/admin/materiales-muestreo">
        <div class="panel widget center bgimage"
            style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/samplingMaterial.png');">
            <div class="dimmer"></div>
            <div class="panel-content">
                <i class="fas fa-box-open"></i>
                <h4>Materiales Muestreos</h4>
    </a>
</div>
</div>
</a>
</div>

<div class="col-md-3">
    <a href="https://dev.sistemaacama.com.mx/admin/ph-trazable">
        <div class="panel widget center bgimage"
            style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/phTraceable.jpg');">
            <div class="dimmer"></div>
            <div class="panel-content">
                <i class="fas fa-fill"></i>
                <h4>Ph Trazables</h4>
    </a>
</div>
</div>
</a>
</div>

<div class="col-md-3">
    <a href="https://dev.sistemaacama.com.mx/admin/ph-calidad">
        <div class="panel widget center bgimage"
            style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/phQuality.jpg');">
            <div class="dimmer"></div>
            <div class="panel-content">
                <i class="fas fa-fill-drip"></i>
                <h4>Ph Calidad</h4>
    </a>
</div>
</div>
</a>
</div>

<div class="col-md-3">
    <a href="https://dev.sistemaacama.com.mx/admin/metodo-aforo">
        <div class="panel widget center bgimage"
            style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/methodAforo.jpg');">
            <div class="dimmer"></div>
            <div class="panel-content">
                <i class="fas fa-book"></i>
                <h4>Método Aforos</h4>
    </a>
</div>
</div>
</a>
</div>

<div class="col-md-3">
    <a href="https://dev.sistemaacama.com.mx/admin/con-tratamiento">
        <div class="panel widget center bgimage"
            style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/withTreatments.jpg');">
            <div class="dimmer"></div>
            <div class="panel-content">
                <i class="fas fa-vials"></i>
                <h4>Con Tratamientos</h4>
    </a>
</div>
</div>
</a>
</div>

<div class="col-md-3">
    <a href="https://dev.sistemaacama.com.mx/admin/tipo-tratamiento">
        <div class="panel widget center bgimage"
            style="margin-bottom:0;overflow:hidden;background-image:url('https://dev.sistemaacama.com.mx//storage/ConfigurationBackground/treatmentTypes.jpg');">
            <div class="dimmer"></div>
            <div class="panel-content">
                <i class="fas fa-eye-dropper"></i>
                <h4>Tipo Tratamientos</h4>
    </a>
</div>
</div>
</a>
</div>
</div>

@endsection

@section('javascript')

@stop
