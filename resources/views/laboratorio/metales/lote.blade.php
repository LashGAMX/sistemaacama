@extends('voyager::master')

@section('content')
<link rel="stylesheet" href="{{asset('/public/assets/summer/summernote.min.css')}}">

<div class="container-fluid">
  <div class="row">
    <div class="col-md-3">
      <label for="tipo">Tipo fórmula</label>
      <select class="form-control" id="tipo">
        @foreach($tipo as $item)
          <option value="{{$item->Id_tipo_formula}}">{{$item->Tipo_formula}}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3">
      <div class="form-group">
        <label for="">Fecha lote</label>
        <input type="date" id="fecha" class="form-control" placeholder="Fecha lote">
      </div>
    </div>
    
    <div class="col-md-2">
      <button class="btn btn-success" id="btnBuscar">Buscar</button>
    </div>        
  </div>
  <div class="row">
    <div class="col-md-12" id="divLotes">
      <table class="table">
        <thead>
          <tr>
            <th>Seleccionar</th>
            <th>Cerrado</th>
            <th>Id Lote</th>
            <th>Fórmula</th>
            <th>Tipo fórmula</th>
            <th>Fecha lote</th>
            <th>Hora</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>


@stop

  @section('javascript')
  <!-- include summernote css/js -->
  <script src="{{asset('/public/assets/summer/summernote.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
  <script src="{{asset('/public/js/laboratorio/metales/lote.js')}}"></script>
  <script src="{{asset('/public/js/libs/componentes.js')}}"></script>
  <script src="{{asset('/public/js/libs/tablas.js')}}"></script>  
@endsection