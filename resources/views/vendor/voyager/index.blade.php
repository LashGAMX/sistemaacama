@extends('voyager::master')

@section('content')
    <div class="page-content">
        @include('voyager::alerts')
        {{-- @include('voyager::dimmers') --}}
    
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul class="list-group">

                        {{-- <li class="list-group-item list-group-item-warning"></li> --}}
                        {{-- <li class="list-group-item list-group-item-success"></li> --}}
                        <li class="list-group-item list-group-item-success">Ya se encuentra la nueva versión de la App de campo</li>
                        <li class="list-group-item ">Se estara corrigiendo funciones con la sección de bitacoras para las areas de análisi</li> 
                        <li class="list-group-item ">Modulo de Lab analisis actualizado para Potable y Micro Biologia</li>
                        <li class="list-group-item ">El modulo de Lab analisis ya ah sido agregado a los perfiles, si a alguien le hizo falta agregar el modulo , por favor avisarme para agrgarselo.</li>
                        <li class="list-group-item ">Modulos por actualizar</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
@stop 


@section('javascript')

@stop
  