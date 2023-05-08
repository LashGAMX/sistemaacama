@extends('voyager::master')

@section('content')

@section('page_header')

    @stop

<div class="container-fluid">

        <div class="row">
            <div class="col-md-4">
                <br>
                <div class="form-group">
                    <label for="foliosol">Folio</label>
                    <input type="text" class="form-control" style="width: 30%" onkeyup="buscarFolio();" placeholder="Buscar folio" id="folioSol" autofocus> 
                    <div id="stdMuestra"></div>
                    <button id="btnIngresar" class="btn btn-info" onclick="setIngresar()"><i class="fas fa-arrow-right"></i> Ingresar</button>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-3">
                        <button id="btnSetCodigo" class="btn btn-warning"><i class="voyager-params"></i> Generar codigo </button>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="conductividad">Conductividad</label>
                            <input class="form-control" type="number" id="conductividad" placeholder="Conductividad">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="cloruros">Cloruros</label>
                            <select class="form-control" id="cloruros">
                                <option value="0">Sin seleccionar</option>
                                <option value="1">Menor de 500</option>
                                <option value="2">Mayor de 500</option>
                                <option value="3">Mayor de 1000</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ph">ph</label>
                            <input class="form-control" type="number" id="ph" placeholder="Ph">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <div class="row">
        <div class="col-md-4">
           <div class="row">
            <div class="col-md-12">
                <input type="text" id="idSol" hidden>
                <label class="col col-sm-12 datosGenerales" >Folio: <input type="text" id="folio" disabled /></label>
                <label class="datosGenerales">Descarga: <input type="text" id="descarga" disabled /></label>
                <label class="datosGenerales">Cliente o Intermediario: <input type="text" size="50" id="cliente"  disabled /></label>
                <label class="datosGenerales" >Empresa: <input type="text" size="50" id="empresa" disabled /></label>
                <label class="fechas" >Hora recepción: <br><input type="datetime-local" step="1" id="hora_recepcion1" /><br><input type="datetime-local" step="1" id="hora_entrada"  /></label>
            </div>
            <div class="col-md-12">
                <label class="datosGenerales" for="finMuestreo">Fecha fin de muestreo:  <input type="datetime-local" id="finMuestreo" disabled  size="50"/></label>
                <label class="datosGenerales" for="conformacion">Fecha conformación: <input type="datetime-local" id="conformacion" disabled  size="50"/></label>
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