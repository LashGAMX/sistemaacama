@extends('voyager::master')

@section('content')

@section('page_header')
    <div class="row">
        <h6 class="page-title">
            <i class="fas fa-angle-double-right"></i>
            Solicitudes
        </h6>
    </div>  
    
    <div class="col-md-3">
        <div class="table-responsive">
            <table class="table">
                <thead>                    
                    <tr>
                        <th id="cabecera1">
                            <input type="search" style="height: 40px" class="form-control" placeholder="#Folio" id="buscarSol" autofocus>
                        </th>
                        
                        <th id="cabecera1">
                            <button id="btnBuscar" style="height: 35px" class="btn btn-success" onclick='buscadorSol("buscarSol", "ingresoLab", "proceso", "reporteListo", "cliente", "mensajeBusqueda")'><i class="fas fa-search"></i> Buscar</button>                            
                        </th>                                                
                    </tr>

                    <tr>
                        <th id="cabecera1">
                            <span id="mensajeBusqueda"></span>
                        </th>
                    </tr>
                </thead>                                
            </table>
        </div>
    </div>
@stop

<div>
    {{-- Be like water. --}}

    <!--Tabla -->
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table">

                <thead>
                    <tr>
                        <th class="text-center" id="cabecera1">
                            <svg class="icono-circulo-default img-fluid" fill="currentcolor" xmlns="http://www.w3.org/2000/svg" width="189.6" height="189.6"><circle cx="94.8" cy="94.8" r="94.8" id="ingresoLab"/></svg>
                        </th>

                        <th class="text-center" id="cabecera1">
                            <svg class="icono-circulo-default img-fluid" fill="currentcolor" xmlns="http://www.w3.org/2000/svg" width="189.6" height="189.6"><circle cx="94.8" cy="94.8" r="94.8" id="proceso"/></svg>
                        </th>

                        <th class="text-center" id="cabecera1">
                            <svg class="icono-circulo-default img-fluid" fill="currentcolor" xmlns="http://www.w3.org/2000/svg" width="189.6" height="189.6"><circle cx="94.8" cy="94.8" r="94.8" id="reporteListo"/></svg>
                        </th>

                        <th class="text-center" id="cabecera1">
                            <svg class="icono-circulo-default img-fluid" fill="currentcolor" xmlns="http://www.w3.org/2000/svg" width="189.6" height="189.6"><circle cx="94.8" cy="94.8" r="94.8" id="cliente"/></svg>
                        </th>
                    </tr>
                </thead>

                <tbody>                    
                    <tr> 
                        <td class="text-center" id="cabecera1">Ingreso Laboratorio</td>
                        <td class="text-center" id="cabecera1">Proceso</td>
                        <td class="text-center" id="cabecera1">Reporte Listo</td>
                        <td class="text-center" id="cabecera1">Cliente</td>
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
