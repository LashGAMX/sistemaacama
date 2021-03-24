@extends('voyager::master')

@section('content')

  @section('page_header')
<div class="container-fluid">

    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @livewire('precios.filter-catalogo')
                </div>
              </div>
        </div>
        
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              {{-- @livewire('precios.catalogo', ['idSucursal' => @$idSucursal]) --}}
            </div>
          </div>
        </div>

      </div>

</div> 
  @stop

@endsection  
