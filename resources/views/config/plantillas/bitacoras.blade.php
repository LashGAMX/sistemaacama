@extends('voyager::master')

@section('content')

  @section('page_header')
  <link rel="stylesheet" href="{{asset('/public/assets/summer/summernote.min.css')}}">
  <h6 class="page-title">
        <i class="fas fa-cogs"></i>
        Configuraciones Plantillas 
        @switch($tipo)
            @case(1)
                Fq
                @break
            @case(2)
                
                @break
            @default
                
        @endswitch
        <input type="text" id="tipo" value="{{$tipo}}" style="border: none;" disabled hidden>
    </h6> 
  @stop

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <button class="bg-success"><i class="fas fa-plus"></i> Crear</button>
            </div>
            <div class="col-md-12" id="tabPlantillas">
                
            </div>
        </div>
    </div>


        <!-- Modal -->
<div class="modal fade" id="modalParametros" tabindex="-1" aria-labelledby="modalDetalleLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalDetalleLabel">Crear nueva plantilla</h5>
        </div>
        <div class="modal-body">
         {{-- Inicio de Body  --}}
            <div class="row">
                <div class="col-md-12">
                    
                </div>
            </div>
         {{-- Fin de body --}} 
        </div>
        <div class="modal-footer">
        </div>
      </div>
    </div>
  </div>
    
 
    <!-- Modal -->
<div class="modal fade" id="modalDetalle" tabindex="-1" aria-labelledby="modalDetalleLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalDetalleLabel">Plantilla de parametro: <input type="" id="parametro" style="border:none;"></h5>
        </div>
        <div class="modal-body">
         {{-- Inicio de Body  --}}
            <div class="row">
                <div class="col-md-12">
                    <button class="btn bg-success" id="btnGuardar"><i class="fas fa-save"></i> Guardar</button>
                </div>
                <div class="col-md-12">
                    <div id="divSummer"></div>
                  </div>
            </div>
         {{-- Fin de body --}} 
        </div>
        <div class="modal-footer">
        </div>
      </div>
    </div>
  </div>

  @section('javascript')
      <script src="{{asset('/public/js/config/plantillas.js')}}?v=0.2.1"></script>
      <script src="{{asset('/assets/summer/summernote.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
@stop  
@endsection
  