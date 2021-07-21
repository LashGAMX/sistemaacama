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
                    <table id="tablaSolicitud" class="table">
                        <div class="row">
                            <div class="col-md-1">
                                {{-- <a href="{{url('admin/cotizacion/solicitud/create')}}" class="btn btn-success btn-sm"><i class="voyager-plus"></i> Crear</a> --}}
                                <button id="btnCreate" class="btn btn-success" ><i class="voyager-plus"></i> Crear</button>
                            </div>
                            <div class="col-md-1">
                                <button id="btnEdit" class="btn btn-warning" ><i class="voyager-edit"></i> Editar</button>
                            </div>
                            <div class="col-md-1">
                                <button id="btnImprimir" class="btn btn-info" ><i class="voyager-documentation"></i> Imprimir</button>
                            </div>
                            <div class="col-md-1">
                                <button id="btnGenFolio" class="btn btn-success" ><i class="voyager-file-text"></i> Generar Código</button>
                            </div>
                        </div>
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
                                    <td>{{$item->Id_cotizacion}}</td>
                                    <td>{{$item->Estado}}</td>
                                    <td>{{$item->Folio}}</td>
                                    <td>{{$item->Folio_servicio}}</td>
                                    <td>{{$item->Clave_norma}}</td>
                                    <td>{{$item->Servicio}}</td>
                                    <td>{{$item->Nombre}}</td>
                                    <td>{{$item->Fecha_muestreo}}</td>
                                    <td>{{$item->NomInter}} {{$item->ApeInter}}</td>
                                </tr>
                            @endforeach
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
        <script src="{{ asset('js/cotizacion/solicitud.js') }}"></script>
        <script src="{{ asset('js/libs/componentes.js') }}"></script>
        <script src="{{ asset('js/libs/tablas.js') }}"></script>
    @stop
