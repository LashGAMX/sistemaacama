@extends('voyager::master')

@section('content')
  @section('page_header')
<div class="container-fluid">
  <p>Cotización</p>
    <div class="row">
        <div class="col-md-12">
            {{-- {{Auth::user()->id}}
            {{Auth::user()->name}} --}}

            <div class="row">
                {{-- <input type="text" value="{{$idUser}}"> --}}
                <!-- Parte de Encabezado-->
              <div class="col-md-2">
                <button class="btn btn-success btn-sm"  onclick="abrirModal(1)">
                    <i class="voyager-plus"></i> Crear</button>
              </div>
                {{-- {{$idUser}} --}}
               <div class="col-md-4 mt-2">
                   <input type="date"  placeholder="Fecha inicio" class="form-control" value="">
                </div>
               <div class="col-md-4 mt-2">
                   <input type="date"  placeholder="Fecha inicio" class="form-control" value="">
               </div>

              <div class="col-md-2 mt-2">
                <input type="search" class="form-control" placeholder="Buscar">
              </div>
               <!-- Fin Parte de Encabezado-->

                <!--Tabla -->
                <table class="table table-sm">
                    <thead class="">
                        <tr>
                            <th>Cliente</th>
                            <th>Folio Servicio</th>
                            <th>Cotización Folio</th>
                            <th>Empresa</th>
                            <th>Servicio</th>
                            <th>Fecha Cotización</th>
                            <th>Supervición</th>
                            <th>Acciónes</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($model as $item)
                      <td>{{$item->Cliente}}</td>
                      <td>{{$item->Folio_servicio}}</td>
                      <td>{{$item->Cotizacion_folio}}</td>
                      <td>{{$item->Empresa}}</td>
                      <td>{{$item->Servicio}}</td>
                      <td>{{$item->Fecha_cotizacion}}</td>
                      <td>{{$item->Supervicion}}</td>
                      <td>
                        <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalCotizacionPrincipal">
                        <i class="voyager-edit"></i> <span hidden-sm hidden-xs>Editar</span> </button>
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalCotizacionHistorico" wire:click="details('{{$item->Id_cotizacion}}')">
                        <i class="voyager-list" aria-hidden="true"></i>
                        <span hidden-sm hidden-xs>Historico</span> </button>
                            <button type="button" class="btn btn-sm btn-dark" data-toggle="modal" data-target="#modalCotizacionHistorico" wire:click="details('{{$item->Id_cotizacion}}')">
                                <i class="voyager-documentation" aria-hidden="true"></i>
                                <span hidden-sm hidden-xs>Duplicar</span> </button>
                      </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            {{-- @livewire('cotizacion.cotizacion', ['idUser' => Auth::user()->id]) --}}
        </div>
      </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalCotizacionPrincipal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width:98%">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="tituloModal"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Información Basica</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Parametros</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Información de Cotización</a>
                </li>
              </ul>
              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">...</div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">...</div>
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
              </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </div>
    </div>
  </div>
  @stop

@endsection
@section('javascript')
<script src="{{asset('js/cotizacion/cotizacion.js')}}"></script>
<script src="{{asset('js/libs/componentes.js')}}"></script>
<script src="{{asset('js/libs/tablas.js')}}"></script>
@stop
