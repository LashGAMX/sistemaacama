@extends('voyager::master')

@section('content')

  @section('page_header')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @livewire('analisis-q.lista-norma',['idNorma' => @$idNorma])
                </div>
              </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6>Parametros</h6>
                    @livewire('analisis-q.limites', ['idNorma' => @$idNorma])
                </div>
              </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    
                </div>
              </div>
        </div>
      </div>
</div> 
  @stop

@endsection  
