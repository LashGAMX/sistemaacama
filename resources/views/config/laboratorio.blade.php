@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
      <i class="voyager-lab"></i>
      Laboratorio
  </h6>
  @stop

  <ul class="nav nav-tabs" id="config-tab" role="tablist"> 
    <li class="nav-item" role="menu">
      <a class="nav-link active" id="sucursal-tab" data-toggle="tab" href="#sucursal" role="tab" aria-controls="sucursal" aria-selected="true">Sucursal</a>
    </li>
    {{-- <li class="nav-item" role="menu">
      <a class="nav-link" id="simbologia-tab" data-toggle="tab" href="#simbologia" role="tab" aria-controls="simbologia" aria-selected="false">Simbologia</a>
    </li> --}}
    <li class="nav-item" role="menu">
      <a class="nav-link" id="unidad-tab" data-toggle="tab" href="#unidad" role="tab" aria-controls="unidad" aria-selected="false">Unidad</a>
    </li>
  </ul>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade  active" id="sucursal" role="tabpanel" aria-labelledby="sucursal-tab">  
      @livewire('config.table-sucursal',['idUser'=>$idUser])
    </div> 
    {{-- <div class="tab-pane fade" id="simbologia" role="tabpanel" aria-labelledby="simbologia-tab">
      @livewire('config.table-simbologia', ['idUser' => $idUser])
    </div> --}}
    <div class="tab-pane fade" id="unidad" role="tabpanel" aria-labelledby="unidad-tab">
      @livewire('config.table-unidad', ['idUser' => $idUser])
    </div>
  </div>


@endsection  
@section('javascript')
<script>
  $(document).ready(function () {
    $('#sucursal-tab').click();
});
</script>
@stop