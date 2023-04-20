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
      <button class="btn btn-success" id="btnBuscar"><i class="fas fa-search"></i> Buscar</button>
    </div>        
    <div class="col align-self-end">
      <button class="btn btn-info" id="btnCrear"><i class="fas fa-check"></i> Crear</button>
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

<!-- Modal -->
<div class="modal fade" id="modalDetalle" tabindex="-1" aria-labelledby="modalDetalleLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDetalleLabel">Detalle lote: <input type="" id="idLote" style="border:none;width: 80%;"></h5>
      </div>
      <div class="modal-body">
       {{-- Inicio de Body  --}}
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">General</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Datos</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-toggle="tab" data-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Plantilla</button>
          </li>
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade active" id="home" role="tabpanel" aria-labelledby="home-tab">
            Dato 1
          </div>
          <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="row">
              <h6>Flama/ Gnerador de hidruros/Horno de grafito/ Alimentos</h6>
              <div class="col-md-12">

              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
            <div class="row">
              <div class="col-md-12">
                <button id="btnBitacora" class="btn bg-success"><i class="fas fa-save"></i> Guardar</button>
              </div>
              <div class="col-md-12">
                <input type="text" id="tituloBit" hidden>
                <div id="divSummer"></div>
                <input type="text" id="revBit" hidden>
              </div>
            </div>
          </div>
        </div>
       {{-- Fin de body --}} 
      </div>
      <div class="modal-footer">
      </div>
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