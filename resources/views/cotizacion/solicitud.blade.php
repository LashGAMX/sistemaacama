@extends('voyager::master')
@section('page_header')
    @stop
@section('content')
 

    <div class="container-fluid">
        <p>Solicitud</p>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <!-- Parte de Encabezado-->
                    <div class="col-md-12">
                           
                    </div>

                    <div class="col-md-4 mt-2">
                        <input type="date" placeholder="Fecha inicio" class="form-control" value=""> 
                    </div>
                    <div class="col-md-4 mt-2">
                        <input type="date" placeholder="Fecha inicio" class="form-control" value="">
                    </div> 

                    <div class="col-md-2 mt-2">
                        <input type="search" class="form-control" placeholder="Buscar">
                    </div>
                    <!-- Fin Parte de Encabezado-->

                    <!--Tabla -->
                  <div class="col-md-12">
                   <div class="table-responsive">
                    <table id="tablaCotizacion" class="table">
                        <div class="row">
                            <div class="col-md-1">
                                <a href="{{url('admin/cotizacion/solicitud/create')}}" class="btn btn-success btn-sm"><i class="voyager-plus"></i> Crear</a>
                            </div>
                            <div class="col-md-1">
                                <button id="btnEdit" class="btn btn-warning" ><i class="voyager-edit"></i> Editar</button>
                            </div>
                        </div>
                        <thead class="">
                            <tr> 
                                <th>Estado</th>
                                <th>Folio cotizacion</th>
                                <th>Folio servicio</th>
                                <th>Norma</th>
                                <th>Servicio</th>
                                <th>Cliente</th>
                                <th>Fecha muestreo</th>
                                <th>Intermediario</th>
                            </tr>
                        </thead>
                        <tbody>
                    
                        </tbody>
                    </table>
                   </div>
                  </div>
                    <!-- Fin de la Tabla -->
                </div>
            </div>
        </div>
    </div>
@endsection
    @section('javascript')
        {{-- <script src="{{ asset('js/cotizacion/cotizacion.js') }}"></script> --}}
        <script src="{{ asset('js/libs/componentes.js') }}"></script>
        <script src="{{ asset('js/libs/tablas.js') }}"></script>
    @stop
