@extends('voyager::master')

@section('content')

  @section('page_header')
<div class="container-fluid">

    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    {{-- @livewire('precios.paquete',['idUser' => @$idUser]) --}}
                </div>
              </div>
        </div>
        
        <div class="col-md-12"> 
          <div class="card">
            <div class="card-body">
              @livewire('precios.paquete')
            </div>
          </div>
        </div>

      </div>

</div> 
  @stop

@endsection 
 