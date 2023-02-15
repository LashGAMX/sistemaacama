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
          @if (@$sw == true)
          @switch(@$idNorma)
            @case(1)
                @livewire('analisis-q.limite-parametros001', ['idParametro' => @$idParametro,'idNorma' => @$idNorma])
              @break
            @case(2)
                @livewire('analisis-q.limite-parametros002', ['idParametro' => @$idParametro,'idNorma' => @$idNorma])
              @break
            @case(4) //Nom003
                @livewire('analisis-q.limite-parametros003', ['idParametro' => @$idParametro,'idNorma' => @$idNorma])
              @break
            @case(5) // Nom127
                @livewire('analisis-q.limite-parametros127', ['idParametro' => @$idParametro,'idNorma' => @$idNorma])
              @break
            @case(30) // Nom127
                @livewire('analisis-q.limite-parametros127', ['idParametro' => @$idParametro,'idNorma' => @$idNorma])
              @break
            @case(7) //Nom003
                @livewire('analisis-q.limite-parametro201', ['idParametro' => @$idParametro,'idNorma' => @$idNorma])
              @break
            @case(27) 
                @livewire('analisis-q.limite-parametro0012021', ['idParametro' => @$idParametro,'idNorma' => @$idNorma])
              @break
          @default

          @endswitch
          @endif
        </div>
      </div>
    </div>
  </div>

</div>
@stop

@endsection