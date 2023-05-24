@extends('voyager::master')

@section('content')


@section('page_header')
<h6 class="page-title"> 
  <i class="voyager-treasure"></i>
  Lista muestreo
</h6>
@stop
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="text" placeholder="Nombre cliente" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <input type="text" placeholder="Folio" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <select type="Mes" class="form-control" placeholder="">
                                <option>mes</option>
                                <option>Enero</option>
                                <option>Febrero</option>
                                <option>Marzo</option>
                                <option>Abril</option>
                                <option>Mayo</option>
                                <option>Junio</option>
                                <option>Julio</option>
                                <option>Agosto</option>
                                <option>Septiembre</option>
                                <option>Octubre</option>
                                <option>Noviembre</option>
                                <option>Diciembre</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <input type="text" placeholder="Año" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <button class="btn btn-success"><i class="fa fa-search"></i>Buscar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2">
                        <button id="btnCapturar" class="btn btn-primary"><i class="fa fa-file-invoice"></i> Capturar</button>
                    </div>
                    <div class="col-md-2">
                        <button id="btnHojaCampo" class="btn btn-secundary"><i class="fa fa-print"></i> Hoja campo</button>
                    </div>
                    <div class="col-md-2">
                        <button id="btnBitacora" class="btn btn-secundary"><i class="fa fa-print"></i> Bitacora</button>
                    </div>
                    <div class="col-md-2">
                        <button id="btnBitacora" class="btn btn-secundary" data-toggle="modal" data-target="#modalObs"><i class="fa fa-add"></i> Observacion</button>
                    </div>
                </div>
                
                <table class="table table-sm" id="listaServicio">
                    <thead>
                        <tr>
                            <th>Id solicitud</th>
                            <th>Solicitud</th>
                            <th>Cliente</th>
                            {{-- <th>PM</th> --}}
                            <th>Fecha muestreo</th>
                            <th>Estado</th>
                            <th>Captura</th>
                            <th>Equipo</th>
                            <th>Marca</th>
                            <th>Modelo</th> 
                            <th>Serie</th>
                            <th>Muestreador</th>
                            <th>Fecha creación</th>
                            <th>Fecha modificación</th>
                        </tr>
                    </thead>
                    <tbody>
                     @foreach ($model as $item)
                     <tr>
                        <td>{{$item->Id_solicitud}}</td>
                        <td>{{$item->Folio_servicio}}</td>
                        <td>{{$item->Empresa}}</td>
                        {{-- <td>{{$item->Direccion}}</td> --}}
                        <td>{{$item->Fecha_muestreo}}</td>
                        @switch($item->StdSol)
                            @case(1)
                                <td>Asignado</td>
                                @break
                            @case(2)
                                <td>Capturando</td>
                                @break
                            @case(3)
                                <td>Cerrado</td>
                                @break
                            @default
                                <td>Sin Asignar</td>
                        @endswitch
                        <td>{{$item->Captura}}</td>

                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{$item->name}}</td>
                        <td>{{$item->created_at}}</td>
                        <td>{{$item->updated_at}}</td>
                    </tr>
                     @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
<!-- Modal -->
<div class="modal fade" id="modalObs" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Observacion plan de muestreo</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
       <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">Observaciónde plan de muestreo</span>
  </div>
  <textarea class="form-control" aria-label="With textarea"></textarea>
</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
@endsection   


@section('javascript')
    <script src="{{asset('public/js/campo/listaMuestreo.js')}}?v=1.0.1"></script> 
@stop

