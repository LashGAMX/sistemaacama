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
        
        <div class="col-md-5">
          <div class="card">
            <div class="card-body">
              @livewire('analisis-q.limites', ['idNorma' => @$idNorma])    
            </div>
          </div>
        </div>
 
        <div class="col-md-7">
            <div class="card"> 
                <div class="card-body">
                    <h6>Limites</h6>
                    @if (@$idNorma == 1)
                        @livewire('analisis-q.limite-parametros001', ['idParametro' => @$idParametro])
                    @endif
                    @if (@$idNorma == 2)
                        <h6>Esta es la norma 002</h6>
                    @endif
                </div>
              </div>
        </div>
      </div>

</div> 
  @stop

@endsection  
