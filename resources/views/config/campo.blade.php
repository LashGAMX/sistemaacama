@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
      <i class="fa fa-truck-pickup"></i>
      Ing. Campo
  </h6>
  @stop

  <ul class="nav nav-tabs" id="config-tab" role="tablist"> 
    <li class="nav-item" role="menu">
      <a class="nav-link active" id="termometro-tab" data-toggle="tab" href="#termometro" role="tab" aria-controls="termometro" aria-selected="true">Termometro</a>
    </li>
 
  </ul>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade  active" id="termometro" role="tabpanel" aria-labelledby="termometro-tab">  
        @livewire('config.campo.termometro-calibrado', ['idUser' => Auth::user()->id])
    </div> 

  </div>


@endsection  
@section('javascript')
<script>
  $(document).ready(function () {
    $('#termometro-tab').click();

 
});
</script>
<script>
  $('#tableEquipo').DataTable({
          "ordering": false,
          "language": {
              "lengthMenu": "# _MENU_ por pagina",
              "zeroRecords": "No hay datos encontrados",
              "info": "Pagina _PAGE_ de _PAGES_",
              "infoEmpty": "No hay datos encontrados",   
          }
      });
  </script>
@stop