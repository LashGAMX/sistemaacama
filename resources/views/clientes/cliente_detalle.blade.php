@extends('voyager::master')

@section('content')

  @section('page_header')

  <h6 class="page-title"> 
      <i class="voyager-person"></i>
      Detalle cliente
  </h6>
  <div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="card-title"><strong>Status: </strong>@if ($cliente->deleted_at != 'null') Activo @else Inactivo @endif</h5>
                    </div>
                    <div class="col-md-6">
                        <h5 class="card-title"><strong>Id cliente: </strong>{{$cliente->Id_cliente}}</h5>
                    </div>
                    <div class="col-md-6">
                        <h5 class="card-title"><strong>Nombre: </strong>{{$cliente->Empresa}}</h5>
                    </div>
                    <div class="col-md-6">
                        <h5 class="card-title"><strong>Intermediario: </strong>{{$cliente->Nombres}} {{$cliente->A_paterno}}</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @livewire('clientes.table-sucursal', ['idUser' => Auth::user()->id, 'idCliente' => $cliente->Id_cliente])
                    </div>
                </div>
            </div>
          </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-md-4">
                  <h6>Id_cliente: {{@$sucursal->Id_sucursal}}</h6>
                </div>
                <div class="col-md-4">
                  <h6>Sucursal: {{@$sucursal->Empresa}}</h6>
                </div>
                <div class="col-md-4">
                  <h6>Estado: {{@$sucursal->Estado}}</h6>
                </div>
              </div>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                      <a class="nav-link active" id="reporte-tab" data-toggle="tab" href="#reporte" role="tab" aria-controls="reporte" aria-selected="true">Reporte</a>
                    </li>
                    <li class="nav-item" role="presentation">
                      <a class="nav-link" id="siralab-tab" data-toggle="tab" href="#siralab" role="tab" aria-controls="siralab" aria-selected="false">Siralab</a>
                    </li>
                  </ul>
                  <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade active" id="reporte" role="tabpanel" aria-labelledby="reporte-tab">
                        <div class="accordion" id="accordionExample">
                            <div class="card">
                              <div class="card-header" id="headingOne">
                                <h2 class="mb-0">
                                  <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#reporteRFC" aria-expanded="true" aria-controls="reporteRFC">
                                        RFC
                                  </button>
                                </h2>
                              </div>
                          
                              <div id="reporteRFC" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="card-body">
                                    @livewire('clientes.table-rfc-reporte', ['idUser' => Auth::user()->id ,'idSuc' => @$idSuc])
                                </div>
                              </div>
                            </div>
                            <div class="card">
                              <div class="card-header" id="reporteConsesion1">
                                <h2 class="mb-0">
                                  <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#reporteConsesion" aria-expanded="false" aria-controls="reporteConsesion">
                                    Titulo de concesión
                                  </button> 
                                </h2>
                              </div>
                              <div id="reporteConsesion" class="collapse" aria-labelledby="reporteConsesion1" data-parent="#accordionExample">
                                <div class="card-body">
                                    @livewire('clientes.table-titulo-reporte', ['idUser' => Auth::user()->id ,'idSuc' => @$idSuc]) 
                                </div>
                              </div>
                            </div>
                            <div class="card">
                              <div class="card-header" id="direccionReporte1">
                                <h2 class="mb-0">
                                  <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#direccionReporte" aria-expanded="false" aria-controls="direccionReporte">
                                    Dirección reporte
                                  </button> 
                                </h2>
                              </div>
                              <div id="direccionReporte" class="collapse" aria-labelledby="direccionReporte1" data-parent="#accordionExample">
                                <div class="card-body">
                                    @livewire('clientes.table-direccion-reporte',['idUser' => Auth::user()->id ,'idSuc' => @$idSuc]) 
                                </div>
                              </div>
                            </div>
                            <div class="card">
                              <div class="card-header" id="puntoReporte1">
                                <h2 class="mb-0">
                                  <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#puntoReporte" aria-expanded="false" aria-controls="puntoReporte">
                                    Punto de muestreo
                                  </button> 
                                </h2>
                              </div>
                              <div id="puntoReporte" class="collapse" aria-labelledby="puntoReporte1" data-parent="#accordionExample">
                                <div class="card-body">
                                    @livewire('clientes.table-punto-reporte',['idUser' => Auth::user()->id ,'idSuc' => @$idSuc]) 
                                </div>
                              </div>
                            </div>
                          </div>
                    </div>
                    <div class="tab-pane fade" id="siralab" role="tabpanel" aria-labelledby="siralab-tab">
                        
                      <div class="accordion" id="accordionExample">
                        <div class="card">
                          <div class="card-header" id="siralabRfc1">
                            <h2 class="mb-0">
                              <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#siralabRfc" aria-expanded="true" aria-controls="siralabRfc">
                                RFC
                              </button>
                            </h2>
                          </div>
                      
                          <div id="siralabRfc" class="collapse " aria-labelledby="siralabRfc1" data-parent="#accordionExample">
                            <div class="card-body">
                              @livewire('clientes.table-rfc-siralab',['idUser' => Auth::user()->id ,'idSuc' => @$idSuc]) 
                            </div>
                          </div>
                        </div>

                        <div class="card">
                          <div class="card-header" id="siralabTitulo1">
                            <h2 class="mb-0">
                              <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#siralabTitulo" aria-expanded="false" aria-controls="siralabTitulo">
                                Titulo de conseción
                              </button>
                            </h2>
                          </div>
                          <div id="siralabTitulo" class="collapse" aria-labelledby="siralabTitulo1" data-parent="#accordionExample">
                            <div class="card-body">
                              Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                            </div>
                          </div>
                        </div>
                        <div class="card">
                          <div class="card-header" id="siralabDireccion1">
                            <h2 class="mb-0">
                              <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#siralabDireccion" aria-expanded="false" aria-controls="siralabDireccion">
                                Dirección reporte
                              </button>
                            </h2>
                          </div>
                          <div id="siralabDireccion" class="collapse" aria-labelledby="siralabDireccion1" data-parent="#accordionExample">
                            <div class="card-body">
                              Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                            </div>
                          </div>
                        </div>
                        <div class="card">
                          <div class="card-header" id="siralabPunto1">
                            <h2 class="mb-0">
                              <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#siralabPunto" aria-expanded="false" aria-controls="siralabPunto">
                                Punto de muestreo
                              </button>
                            </h2>
                          </div>
                          <div id="siralabPunto" class="collapse" aria-labelledby="siralabPunto1" data-parent="#accordionExample">
                            <div class="card-body">
                              Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
            </div>
          </div>
    </div>
  </div>
  @stop

@endsection   