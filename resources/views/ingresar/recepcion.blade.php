@extends('voyager::master')

@section('content')

@section('page_header')
    <div class="row">
        <h1 class="page-title">
            <i class="fas fa-angle-double-right"></i>
            Ingresar
        </h1>
    </div>    
    <div class="col-md-4">
        <div class="form-group">
            <input type="text" class="form-control" onchange="buscarFolio();" placeholder="Buscar folio" id="folioSol" autofocus> 
        </div>
    </div>
    <span id="mensajeBusqueda"></span>
    <br><br><br>
@stop

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h3>Datos generales</h3>
        </div>
        <div class="col-md-6">
            <div class="row">
                <input type="text" id="idSol" hidden>
                <label class="col col-sm-12 datosGenerales" style="font-size: 25px">Folio: <input type="text" id="folio" style="font-size: 25px" disabled/></label>            
                <label class="datosGenerales" style="font-size: 25px">Descarga: <input type="text" id="descarga" style="font-size: 25px" disabled/></label> 
                <label class="datosGenerales" style="font-size: 25px">Cliente o Intermediario: <input type="text" size="60" id="cliente" style="font-size: 25px" disabled/></label>
                <label class="datosGenerales" style="font-size: 25px">Empresa: <input type="text" size="50" id="empresa" style="font-size: 25px" disabled/></label>
                <label class="fechas" style="font-size: 25px">Hora recepción: <input type="datetime-local" step="1" id="hora_recepcion1" style="font-size: 25px" /></label>
                <label class="fechas" style="font-size: 25px">Hora entrada: <input type="datetime-local" step="1" id="hora_entrada" style="font-size: 25px"/></label>            
            </div>
        </div>
        <div class="col-md-6">
            <div class="tableRecepcion">
    
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
           
            </div>
        </div>
    </div>
</div>
 
<div>    


    <br>
    <div class="col-md-1">
        <button id="btnIngresar" class="btn btn-info" onclick="setIngresar()"><i class="fas fa-arrow-right"></i> Ingresar</button>
    </div>
    <br><br><br>

    <div class="container datos">
        <div class="row">
            <label class="datosGenerales" style="font-size: 25px">Fecha fin de muestreo: <input type="text" id="f_fin" style="font-size: 25px" disabled/></label> 
        </div>
        <br>

        <div class="row">
            <label class="datosGenerales" style="font-size: 25px">Fecha conformación: <input type="text" id="f_con" style="font-size: 25px" disabled/></label> 
        </div>
        <br>

        <div class="row">
            <label class="datosGenerales" style="font-size: 25px">Procedencia: <input type="text" id="procedencia" style="font-size: 25px" disabled/></label> 
        </div>
    </div>

    <br><br><br><br><br>    
</div>
{{-- <livewire:historial.config/> --}}
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('/public/css/ingresar/ingresar.css')}}">
@endsection

@section('javascript')
    <script src="{{ asset('/public/js/ingresar/ingresar.js') }}?v=0.0.1"></script>
@stop
