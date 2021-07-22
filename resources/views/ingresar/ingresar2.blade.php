@extends('voyager::master')

@section('content')

@section('page_header')
    <div class="row">
        <h6 class="page-title">
            <i class="fas fa-angle-double-right"></i>
            Ingresar
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

    <p class="text-center" style="font-size: 250%">Datos Generales</p>

    <!--Tabla -->
    <div class="col-md-12">
        <div id="divTablaDatos">
            <div class="table-responsive">
                <table id="tablaDatos" class="table">
                    <thead class="">
                        <tr>
                            <th style="font-size: 200%">Folio</th>
                            <th style="font-size: 200%">Descarga</th>
                            <th style="font-size: 200%">Cliente o Intermediario</th>
                            <th style="font-size: 200%">Empresa</th>
                            <th style="font-size: 200%">Hora Recepción</th>
                            <th style="font-size: 200%">Hora Entrada</th>
                        </tr>
                    </thead>

                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <br>
    <br>
    <br>

    <div class="col-md-1">
        <button id="btnIngresar" class="btn btn-info"><i class="fas fa-arrow-right"></i> Ingresar</button>
    </div>

    <br>
    <br>
    <br>
    <br>
    <br>

    <!--Tabla -->
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="tablaSolicitud" class="table">

                <thead class="">
                    <tr>
                        <th>#</th>
                        <th>Estado</th>
                        <th>Folio cotización</th>
                        <th>Folio servicio</th>
                        <th>Norma</th>
                        <th>Servicio</th>
                        <th>Cliente</th>
                        <th>Fecha muestreo</th>
                        <th>Intermediario</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($model as $item)
                        <tr>
                            <td>{{ $item->Id_cotizacion }}</td>
                            <td>{{ $item->Estado }}</td>
                            <td>{{ $item->Folio }}</td>
                            <td>{{ $item->Folio_servicio }}</td>
                            <td>{{ $item->Clave_norma }}</td>
                            <td>{{ $item->Servicio }}</td>
                            <td>{{ $item->Nombre }}</td>
                            <td>{{ $item->Fecha_muestreo }}</td>
                            <td>{{ $item->NomInter }} {{ $item->ApeInter }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <p class="text-center" style="font-size: 250%">Solicitudes</p>    

</div>
{{-- <livewire:historial.config/> --}}
@endsection

@section('javascript')
    <script src="{{ asset('js/ingresar/ingresar.js') }}"></script>
    <script src="{{ asset('js/libs/componentes.js') }}"></script>
    <script src="{{ asset('js/libs/tablas.js') }}"></script>
@stop
