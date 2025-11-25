@extends('voyager::master')

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center align-items-center" style="height: 100vh;">
        <div class="row w-100">
            <!-- Tarjeta para el registro de bitácora de alimento -->
            <div class="col-md-5">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h2>Bitácora Alimento</h2>
                    </div>
                    <div class="card-body">
                        <!-- Pestañas de registro -->
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="recepcion-tab" data-toggle="tab" href="#recepcion"
                                    role="tab" aria-controls="recepcion" aria-selected="true">Recepción Laboratorio</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="resguardo-tab" data-toggle="tab" href="#resguardo" role="tab"
                                    aria-controls="resguardo" aria-selected="false">Resguardo de Alimentos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="desecho-tab" data-toggle="tab" href="#desecho" role="tab"
                                    aria-controls="desecho" aria-selected="false">Desecho Muestras</a>
                            </li>
                        </ul>
                        <!-- Contenido de las pestañas -->
                        <div class="tab-content mt-3" id="myTabContent">
                            <!-- Pestaña Recepción -->
                            <div class="tab-pane fade" id="recepcion" role="tabpanel" aria-labelledby="recepcion-tab">
                                <input type="text" name="idrep" id="idrep" hidden>
                                <div class="form-group">
                                    <label for="folio">Folio</label>
                                    <input type="text" name="Folio" id="folio" class="form-control" required disabled>
                                </div>
                                <div class="form-group">
                                    <label for="fecha">Fecha</label>
                                    <input type="datetime-local" name="Fecha" id="fecha" class="form-control" required>
                                </div>
                                       <div class="form-group">
                                    <label for="fecha">Fecha Real Ingreso Recepcion</label>
                                    <input type="datetime-local" name="Fecha" id="fechaRealRecep"  disabled class="form-control" required>
                                </div>
                                     <div class="form-group">
                                    <label for="fecha">Fecha Real Ingreso Alimento</label>
                                    <input type="datetime-local" name="Fecha" id="fechaoriginal"  class="form-control" >
                                </div>            
                                <div class="form-group">
                                    <label for="usuario2">Recibio Muestra</label>
                                    <select name="usuario" id="usuario2" class="form-control" required>
                                        <option value="" disabled selected>Sin seleccionar</option>
                                        <option value="109|SAUL LUCERO GARCIA" @if (Auth::user()->id == 109) selected @endif>SAUL LUCERO GARCIA</option>
                                        <option value="110|DOLORES MARIELA ANDRADE JAIMES" @if (Auth::user()->id == 110) selected @endif>DOLORES MARIELA ANDRADE JAIMES</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="usuario">Resguardo de recepcion</label>
                                    <select name="resRecep" id="resRecep" class="form-control" required>
                                        <option value="" disabled selected>Sin seleccionar</option>
                                        <option value="No aplica">No aplica</option>
                                        <option value="Temperatura Ambiente">Temperatura Ambiente</option>
                                        <option value="INVLAB291">INVLAB291</option>
                                    </select>
                                    
                                </div>
                            </div>
                            <!-- Pestaña Resguardo -->
                            <div class="tab-pane fade" id="resguardo" role="tabpanel" aria-labelledby="resguardo-tab">
                                <div class="form-group">
                                    <label for="usuario">Analizo</label>
                                    <select name="usuario" id="usuario" class="form-control" required>
                                        <option value="" disabled selected>Sin seleccionar</option>
                                        <option value="109|SAUL LUCERO GARCIA" @if (Auth::user()->id == 109) selected
                                            @endif>SAUL LUCERO GARCIA</option>
                                        <option value="110|DOLORES MARIELA ANDRADE JAIMES" @if (Auth::user()->id == 110)
                                            selected @endif>DOLORES MARIELA ANDRADE JAIMES</option>

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="fecha">Fecha Inicio de Analisis</label>
                                    <input type="datetime-local" name="Fecha" id="fechainicio" class="form-control"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="fecha">Fecha Fin de Analisis</label>
                                    <input type="datetime-local" name="Fecha2" id="fecha2" class="form-control"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="usuario">Resguardo despues del analisis</label>
                                    <select name="resRecep" id="resRecep2" class="form-control" required>
                                        <option value="" disabled selected>Sin seleccionar</option>
                                        <option value="No aplica">No aplica</option>
                                        <option value="INVLAB264-1">INVLAB264-1</option>
                                    </select>
                                    
                                </div>
                            </div>

                            <!-- Pestaña Desecho -->
                            <div class="tab-pane fade" id="desecho" role="tabpanel" aria-labelledby="desecho-tab">
                                <div class="form-group">
                                    <label for="fecha">Fecha</label>
                                    <input type="date" name="fecha3" id="fecha3" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="analistadesecho">Seleccione el Analista Asignado</label>
                                    <select name="analistadesecho" id="analistadesecho" class="form-control" required>
                                        <option value="1" disabled selected>Sin seleccionar</option>
                                        <option value="SAUL LUCERO GARCIA">SAUL LUCERO GARCIA</option>
                                        <option value="DOLORES MARIELA ANDRADE JAIMES">DOLORES MARIELA ANDRADE JAIMES</option>
                                        <option value="GUADALUPE GARCIA PÉREZ">GUADALUPE GARCIA PÉREZ</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="desceholugar">¿Dónde se desechará?</label>
                                    <select name="Lugardedesecho" id="Lugardedesecho" class="form-control" required>
                                        <option value="1" disabled selected>Sin seleccionar</option>
                                        <option value="RPBI">RPBI</option>
                                        <option value="Basura">Basura</option>
                                        <option value="Tarja">Tarja</option>
                                    </select>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn bg-success" id="btnRep">Guardar</button>
                        <button class="btn bg-warning" id="btnCon">Consultar</button>
                        <button class="btn bg-info" id="btnBitacora">Bitacoras</button>
                         <button class="btn bg-danger" id="btnHistorial">Historial</button>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card shadow-lg">
                    <div class="card-header bg-success text-white text-center">
                        <h2>Recepción En Area de alimentos</h2>
                    </div>
                    <div class="card-body">
                        <table class="table" id="datosrecepcion">
                            <thead>
                                <tr>
                                    <th>Ent.</th>
                                    <th>Folio</th>
                                    <th>Muestra</th>
                                    <th>Recepcion</th>
                                    <th>Hora en Area</th>
                                    <th>Hora Recepción</th>
                                    <th>Resguardo</th>
                                    <th>Recibio</th>
                                    <th>Parametro</th>
                                     <th>Lib.</th>
                                     

                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row w-100 mt-3">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-header bg-warning text-white text-center">
                        <h2>Resguardo de Alimentos</h2>
                    </div>
                    <div class="card-body">
                        <table class="table" id="Resguardo">
                            <thead>
                                <tr>
                                    <th>Folio</th>
                                    <th>Analista</th>
                                    <th>F/H INICIO</th>
                                    <th>F/H FIN</th>
                                    <th>Almacenamiento</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-header bg-danger text-white text-center">
                        <h2>Desecho Muestras</h2>
                    </div>
                    <div class="card-body">
                        <table class="table" id="Desecho">
                            <thead>
                                <tr>
                                    <th>Folio</th>
                                    <th>Fecha</th>
                                    <th>Analista</th>
                                    <th>Disposición Final</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

@section('javascript')

<script src="{{ asset('public/js/alimentos/recepccion.jsx') }}?v=0.0.1"></script>
@endsection