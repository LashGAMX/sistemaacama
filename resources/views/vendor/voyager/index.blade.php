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
                        {{-- <li class="list-group-item list-group-item-warning"></li> --}}
                        {{-- <li class="list-group-item list-group-item-success">Sección de</li> --}}
                        <li class="list-group-item list-group-item-danger">Parametros de dureza temporalmente deshabilitados</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
@stop 


@section('javascript')

@stop
  