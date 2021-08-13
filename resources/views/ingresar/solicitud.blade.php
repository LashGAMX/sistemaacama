@extends('voyager::master')

@section('content')

@section('page_header')
    <div class="row">
        <h6 class="page-title">
            <i class="fas fa-angle-double-right"></i>
            Solicitudes
        </h6>
    </div>

    <!--<div class="row">
        <div class="col-md-2">
            <input type="search" class="form-control" placeholder="Buscar">
        </div>

        <div class="col-md-1">
            <button id="btnBuscar" class="btn btn-success"><i class="fas fa-search"></i> Buscar</button>
        </div>
    </div>-->
@stop

<div>
    {{-- Be like water. --}}    

    <!--Tabla -->
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-borderless">

                <thead>
                    <tr>       
                        <th class="text-center"><svg class="icono-circulo img-fluid" fill="currentcolor" xmlns="http://www.w3.org/2000/svg" width="189.6" height="189.6"><circle cx="94.8" cy="94.8" r="94.8" id="ingresoLab"/></svg></th>
                        <th class="text-center"><svg class="icono-circulo img-fluid" fill="currentcolor" xmlns="http://www.w3.org/2000/svg" width="189.6" height="189.6"><circle cx="94.8" cy="94.8" r="94.8" id="proceso"/></svg></th>
                        <th class="text-center"><svg class="icono-circulo img-fluid" fill="currentcolor" xmlns="http://www.w3.org/2000/svg" width="189.6" height="189.6"><circle cx="94.8" cy="94.8" r="94.8" id="reporteListo"/></svg></th>
                        <th class="text-center"><svg class="icono-circulo img-fluid" fill="currentcolor" xmlns="http://www.w3.org/2000/svg" width="189.6" height="189.6"><circle cx="94.8" cy="94.8" r="94.8" id="cliente"/></svg></th>
                    </tr>
                </thead>

                <tbody>                    
                    <tr> 
                        <td class="text-center">Ingreso Laboratorio</td>
                        <td class="text-center">Proceso</td>
                        <td class="text-center">Reporte Listo</td>
                        <td class="text-center">Cliente</td>
                    </tr>                    
                </tbody>
            </table>
        </div>
    </div>

</div>
{{-- <livewire:historial.config/> --}}
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/ingresar/generar.css')}}">
@endsection

@section('javascript')
    <script src="{{ asset('js/ingresar/generar.js') }}"></script>
    <script src="{{ asset('js/libs/componentes.js') }}"></script>
    <script src="{{ asset('js/libs/tablas.js') }}"></script>
@stop
