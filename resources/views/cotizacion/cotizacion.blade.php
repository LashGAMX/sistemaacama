@extends('voyager::master')
@section('page_header')
    @stop
@section('content')


    <div class="container-fluid">
        <p>Cotización 💰</p>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <!-- Parte de Encabezado-->
                    <div class="col-md-12">
                           
                    </div>

                    <div class="col-md-4 mt-2">
                        <input type="date" id="inicio" placeholder="Fecha inicio" class="form-control" value="">
                    </div>
                    <div class="col-md-4 mt-2">
                        <input type="date" id="fin" placeholder="Fecha inicio" class="form-control" value="">
                    </div>

                    <div class="col-md-2 mt-2">
                        <button id="btnBuscar" class="btn btn-success btn-sm">BUSCAR</button> 
                    </div>
                    <!-- Fin Parte de Encabezado-->

                    <!--Tabla -->
                  <div class="col-md-12">
                   <div class="table-responsive">
                    <table id="tablaCotizacion" class="table">
                        <div class="row">
                            <div class="col-md-1">
                                <a href="{{url('admin/cotizacion/create')}}" class="btn btn-success btn-sm"><i class="voyager-plus"></i> Crear</a>
                            </div>
                            <div class="col-md-2">
                                <button id="btnEdit" class="btn btn-warning" ><i class="voyager-edit"></i> Editar</button>
                            </div>
                            <div class="col-md-2">
                                <button id="btnShow" class="btn btn-warning" ><i class="voyager-edit"></i> Ver</button>
                            </div>
                            <div class="col-md-2">
                                <button id="btnPrint" class="btn btn-info" ><i class="voyager-edit"></i> Imprimir</button>
                            </div>

                            <div class="col-md-2">
                                <button id="btnDuplicar" class="btn btn-info" ><i class="voyager-file-text"></i> Duplicar Cotización</button>
                            </div>
                        </div>
                        <thead class="">
                            <tr>
                                <th>Id</th>
                                <th>Folio servicio</th>
                                <th>Folio cotización</th>
                                <th>Fecha cotización</th>
                                <th>Nombre cliente</th>
                                <th>Norma</th>
                                <th>Tipo descarga</th>
                                <th>Estado cotización</th>
                                <th>Creado por</th>
                                <th>Fecha creación</th> 
                                <th>Actualizado por</th> 
                                <th>Fecha actualización</th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach (@$model as $item)
                              <tr>
                                  <td>{{$item->Id_cotizacion}}</td>
                                  <td>{{$item->Folio_servicio}}</td>
                                  <td>{{$item->Folio}}</td>
                                  <td>{{$item->created_at}}</td>
                                  <td>{{$item->Nombre}}</td>
                                  <td>{{$item->Clave_norma}}</td>
                                  <td>{{$item->Descarga}}</td>
                                  <td>{{$item->Estado}}</td>
                                  <td>{{$item->NameA}}</td>
                                  <td>{{$item->created_at}}</td>
                                  <td>{{$item->NameA}}</td>
                                  <td>{{$item->updated_at}}</td>
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
        <script src="{{ asset('public/js/cotizacion/cotizacion.js') }}?v=0.0.1"></script> 
    @stop
