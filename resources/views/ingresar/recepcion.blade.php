@extends('voyager::master')

@section('content')

@section('page_header')
<div class="row container">
    <div class="col-md-12">
        <br>
        <div class="form-group">
            <input type="text" class="form-control" style="width: 30%" onkeyup="buscarFolio();" placeholder="Buscar folio" id="folioSol" autofocus>
            <button id="btnIngresar" class="btn btn-info" onclick="setIngresar()"><i class="fas fa-arrow-right"></i> Ingresar</button>
        </div>
    </div>
    @stop
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
           <div class="row">
            <div class="col-md-12">
                <input type="text" id="idSol" hidden>
                <label class="col col-sm-12 datosGenerales" >Folio: <input type="text" id="folio" disabled /></label>
                <label class="datosGenerales">Descarga: <input type="text" id="descarga" disabled /></label>
                <label class="datosGenerales">Cliente o Intermediario: <input type="text" size="50" id="cliente"  disabled /></label>
                <label class="datosGenerales" >Empresa: <input type="text" size="50" id="empresa" disabled /></label>
                <label class="fechas" >Hora recepción: <input type="datetime-local" step="1" id="hora_recepcion1" /></label>
                <label class="fechas" >Hora entrada: <input type="datetime-local" step="1" id="hora_entrada"  /></label>
            </div>
            <div class="col-md-12">
                <label class="datosGenerales" for="finMuestreo">Fecha fin de muestreo:  <input type="text" id="finMuestreo" disabled  size="50"/></label>
                <label class="datosGenerales" for="conformacion">Fecha conformación: <input type="text" id="conformacion" disabled  size="50"/></label>
                <label class="datosGenerales" for="procedencia">Procedencia: <input type="text" id="procedencia" disabled  size="50"/></label>
            </div>
    
        </div>
        </div>
        <div class="col-md-4">
            <div class="" id=divCodigos>
                <table id="codigos" class="table" style="height: 100%">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Número Muestra</th>
                            <th>Cant.Total</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row" style="display: block;">
                <div class="col-md-12">
                    <div id="divPuntos">
                        <table id="puntos" class="table" >
                            <thead>
                                <tr>
                                    <th>...</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="col-md-12">

                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@section('javascript')
<script src="{{ asset('/public/js/ingresar/ingresar.js') }}?v=0.0.1"></script>
@stop