@extends('voyager::master')

@section('content')

@section('page_header')

@stop
<div class="modal fade" id="modalFoto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Acciones para foto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img src="" alt="foto" id="fotoGrande" style="max-width: 100%">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-danger" id="eliminarFoto" onclick="">Eliminar Foto</button>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">

    <div class="row" style="scale: (0.5)">
        <div class="col-md-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="ingreso" id="ingreso"></label>
                        <br>
                        <label for="foliosol">Folio</label><br>
                        <input type="text" style="width: 70%" placeholder="Ingresa un folio" id="folioSol" autofocus>
                        <button onclick="buscarFolio()" class="btn-success"><i class="fas fa-search"></i>
                            Buscar</button>
                        <div id="stdMuestra"></div>
                        <div id="stdMuestraSiralab"></div>
                    </div>
                    <input type="text" id="idSol" hidden>
                    <div class="form-group">
                        <label for="folio">Folio:</label>
                        <input type="text" style="width: 100%" id="folio" placeholder="Folio servicio" disabled />
                    </div>
                    <!-- <div class="form-group">
                        <label for="descarga">Descarga:</label>
                        <input type="text" style="width: 100%" id="descarga" placeholder="Tipo descarga" disabled />
                    </div> -->
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
                        <input type="datetime-local" style="width: 100%" step="1" id="hora_entrada" />
                    </div>
                    <div class="form-group">
                        <select name="recibe" id="recibe" class="form-control">
                            <option value="0">Sin seleccionar</option>
                            <option value="1" @if (Auth::user()->id == 1) selected @endif> Admin</option>
                            <option value="107" @if (Auth::user()->id == 107) selected @endif>Alejandra Arrellano Carmona</option>
                            <option value="101" @if (Auth::user()->id == 101) selected @endif>Mary Carmen Durán Gutiérrez</option>
                            <option value="109" @if (Auth::user()->id == 109) selected @endif>SAUL LUCERO GARCIA</option>
                            <option value="110" @if (Auth::user()->id == 110) selected @endif>DOLORES MARIELA ANDRADE JAIMES</option>
                            <option value="100" @if (Auth::user()->id == 100) selected @endif>Brandon Netzair Diaz Murga</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-12">
                    <button id="btnIngresar" class="btn-info"><i class="fas fa-arrow-right"></i> Ingresar</button>
                    <button id="btnSetCodigos" class="btn-warning"><i class="voyager-params"></i> Generar
                        codigos</button>
                        <button id="btnGetBitacora" class="btn-success"><i class="voyager-params"></i> Bitacora de Recepcion</button>
                </div>

                <div class="col-md-12">

                    <div id="divCodigos">
                        <table id="codigos" class="table">
                            <thead>
                                <tr>
                                    <th style="width: 10%">Codigo</th>
                                    <th style="width: 20%">Parametro</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Las filas se agregarán dinámicamente con JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div id="divPuntos">
                <table id="puntos" class="table">
                    <thead>
                        <tr>
                            <th >#</th>
                            <th >Descripción</th>
                            <th>Tem. Muestra</th>
                            <th>Tem. Recepcion</th>
                            <th>Observacion</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Las filas se agregarán dinámicamente con JavaScript -->
                    </tbody>
                </table>
            </div>


        </div>
    </div>
    <div class="row" style="scale: (0.5)">
        <div class="col">
            <div class="row">
                <div class="col text-center">
                    <h4>Imágenes</h4>
                </div>
            </div>
            <div id="fotos" class="py-3">
                <div class="row">
                    <div class="col text-center">
                        <h5>No hay imágenes que mostrar</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection


@section('javascript')
<script src="{{ asset('/public/js/alimentos/recepcionMuestras.js') }}?v=1.0.10"></script>
@stop