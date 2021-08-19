@extends('voyager::master')

@section('content')

@section('page_header')
    <div class="row">
        <h6 class="page-title">
            <i class="fas fa-angle-double-right"></i>
            Ingresar
        </h6>
    </div>    

    <div class="col-md-2">
        <input type="text" class="form-control" placeholder="Buscar" id="texto">
    </div>

    <span id="mensajeBusqueda"></span>

    <br><br><br>
@stop

<div>    
    {{-- Be like water. --}}    
    <p id="parrafoDatos">Datos Generales</p>
    <br>            
    <label class="datosGenerales">Folio: <input type="text" id="folio" disabled/></label>
    <label class="datosGenerales">Descarga: <input type="text" id="descarga" disabled/></label> 
    <label class="datosGenerales">Cliente o Intermediario: <input type="text" size="60" id="cliente" disabled/></label>
    <br><br>
    <label class="datosGenerales">Empresa: <input type="text" size="60" id="empresa" disabled/></label>
    <label class="fechas">Hora recepción: <input type="text" id="hora_recepcion" disabled/></label>
    <label class="fechas">Hora entrada: <input type="datetime-local" step="1" id="hora_entrada" onchange='validacionFecha("hora_entrada", "hora_recepcion", "btnIngresar")'/></label>
    <br><br><br>    

    <div class="col-md-1">
        <button id="btnIngresar" class="btn btn-info" onclick="setIngresar()"><i class="fas fa-arrow-right"></i> Ingresar</button>
    </div>

    <br><br><br><br><br>
</div>
{{-- <livewire:historial.config/> --}}
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('css/ingresar/ingresar.css')}}">
@endsection

@section('javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('js/ingresar/ingresar.js') }}"></script>
    <script src="{{ asset('js/libs/componentes.js') }}"></script>
    <script src="{{ asset('js/libs/tablas.js') }}"></script>    
@stop
