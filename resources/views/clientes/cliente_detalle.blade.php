@extends('voyager::master')

@section('content')

  @section('page_header')

  <h6 class="page-title"> 
      <i class="voyager-person"></i>
      Detalle cliente
  </h6>
  <div class="row">
    <div class="col-md-6">
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
                        @livewire('clientes.table-sucursal', ['idUser' => Auth::user()->id])
                    </div>
                </div>
            </div>
          </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
              <h5 class="card-title">Card title</h5>
              <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>
              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
              <a href="#" class="card-link">Card link</a>
              <a href="#" class="card-link">Another link</a>
            </div>
          </div>
    </div>
  </div>
  @stop

@endsection   