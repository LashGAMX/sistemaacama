@extends('voyager::master')

@section('content')

@section('page_header')

    @stop

<div class="container-fluid" >
    
    <div class="row" style="scale: (0.5)">
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="foliosol">Folio</label><br>
                        <input type="text" style="width: 70%" placeholder="Ingresa un folio" id="folioSol" autofocus> 
                        <button onclick="buscarFolio()" class="btn-success"><i class="fas fa-search"></i> Buscar</button>
                        <div id="stdMuestra"></div>
                        <div id="stdMuestraSiralab"></div>
                    </div>
                    <input type="text" id="idSol" hidden>
                    <div class="form-group">
                        <label for="folio">Folio:</label>
                        <input type="text" style="width: 100%" id="folio" placeholder="Folio servicio" disabled />
                    </div>
                    <div class="form-group">
                        <label for="descarga">Descarga:</label>
                        <input type="text" style="width: 100%" id="descarga" placeholder="Tipo descarga" disabled />
                    </div>
                    <div class="form-group">
                        <label for="cliente">Cliente o Intermediario:</label>
                        <input type="text" style="width: 100%" id="cliente" placeholder="Cliente" disabled />
                    </div>
                    <div class="form-group">
                        <label for="empresa">Empresa:</label>
                        <input type="text" style="width: 100%" id="empresa" placeholder="Empresa" disabled />
                    </div>
                    <div class="form-group">
                        <label for="empresa">Hora recepción:</label>
                        <input type="datetime-local" style="width: 100%" step="1" id="hora_recepcion1" />
                        <input type="datetime-local" style="width: 100%" step="1" id="hora_entrada"  />
                    </div>
                    <div class="form-group">
                        <label class="datosGenerales" for="finMuestreo">Fecha fin de muestreo:</label>
                        <input type="datetime-local" id="finMuestreo" disabled  style="width: 100%" />
                    </div>
                    <div class="form-group">
                        <label class="datosGenerales" for="conformacion">Fecha conformación:</label>
                        <input type="datetime-local" id="conformacion" disabled  style="width: 100%"/>
                    </div>
                    <div class="form-group">
                        <label class="datosGenerales" for="procedencia">Procedencia:</label>
                        <input type="text" id="procedencia" disabled  style="width: 100%"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-12">
                    <button id="btnIngresar" class="btn-info" ><i class="fas fa-arrow-right"></i> Ingresar</button>
                    <button id="btnSetCodigos" class="btn-warning"><i class="voyager-params"></i> Generar codigos</button>
                    <label for="">sin condiciones </label><input type="checkbox" id="condiciones" onchange="valSinCondiciones()">
                    <label for="">Historial</label><input type="checkbox" id="historial"> 
                    @switch(Auth::user()->role->id)
                        @case(1)
                            <button id="btnActCC" class="btn-info"><i class="voyager-edit"></i> Act. C.C</button>
                            <h6>Fecha emisión: <input type="date" id="fechaEmision" value=""> <span id="btnSetEmision" class="fas fa-edit bg-success"></span></h6>
                            @break
                        @default
                            @switch(Auth::user()->id)
                                @case(65)
                                @case(101)
                                @case(107)
                                <h6>Fecha emisión: <input type="date" id="fechaEmision" value=""> <span id="btnSetEmision" class="fas fa-edit bg-success"></span></h6>      
                                    @break
                                @default
                                    
                            @endswitch
                    @endswitch
                </div>
                
                <div class="col-md-12">
                    <div class="" id=divCodigos>
                        <table id="codigos" class="table">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th># Muestra</th>
                                    <th>Cant.Total</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div id="divPuntos">
                <table id="puntos" class="table" >
                    <thead>
                        <tr>
                            <th style="width: 10%">#</th>
                            <th style="width: 70%">...</th>
                            <th style="width: 20%">Opc</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    
</div>

@endsection


@section('javascript')
<script src="{{ asset('/public/js/ingresar/ingresar.js') }}?v=1.0.9"></script>
@stop