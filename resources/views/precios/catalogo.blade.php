@extends('voyager::master')

@section('content')

  @section('page_header')
<div class="container-fluid">

    <div class="row">
          {{$idUser}}
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @livewire('precios.filter-catalogo',['idSucursal' => @$idSucursal])
                </div>
              </div>
        </div>
        
        <div class="col-md-12"> 
          <div class="card">
            <div class="card-body">
              {{-- @livewire('precios.catalogo', ['idSucursal' => @$idSucursal]) --}}
              @livewire('precios.catalogo', ['idSucursal' => @$idSucursal,'idUser' => @idUser])
              {{-- @livewire('precios.catalogo', ['idUser' => @idUser]) --}}
            </div>
          </div>
        </div>

      </div>

</div> 
  @stop

@endsection 
