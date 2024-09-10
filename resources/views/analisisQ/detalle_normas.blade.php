@extends('voyager::master')

@section('content')

  @section('page_header') 

  <div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                      <h5 class="card-title"><strong>Norma: </strong>{{@$norma->Norma}}</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @livewire('analisis-q.detalle-norma', ['idUser' => Auth::user()->id,'idNorma' => $norma->Id_norma,'name' => $norma->Clave_norma])
                    </div>
                </div>
            </div>
          </div>
    </div>
    <div class="col-md-7">
      <div class="row">
        <div class="col-md-12">
          <h6>Norma: {{@$subnorma->Clave}}</h6>
          <input type="text" id="idSub" value="{{@$idSub}}" hidden>
          <input type="text" id="idNorma" value="{{@$id}}" hidden>
        </div>
        <div class="col-md-12">
            <h5>Parametros</h5>
        </div>
        <div class="col-md-12">
          {{-- @livewire('analisis-q.norma-parametros', ['idUser' => Auth::user()->id,'idSub' => @$idSub]) --}}
          <div id="tabParametros"> 
          </div>
        </div>
    </div>
    </div>
  </div>

  <div id="divModal">

  </div>


  @stop


@endsection


@section('javascript')
<script src="{{asset('public/js/analisisQ/detalle_normas.js')}}?v=1.0.1"></script>
<script src="{{asset('public/js/libs/componentes.js')}}?v=0.0.1"></script>
<script src="{{asset('public/js/libs/tablas.js')}}?v=0.0.1"></script>
@stop


