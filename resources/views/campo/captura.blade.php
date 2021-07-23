@extends('voyager::master')

@section('content')

@section('page_header')
    {{-- <h6 class="page-title"> 
    <i class="fa fa-edit"></i>
    Captura
  </h6> --}}
@stop

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="datosGenerales-tab" data-toggle="tab" href="#datosGenerales" role="tab"
                        aria-controls="datosGenerales" aria-selected="true">1. Datos Generales</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="datosMuestreo-tab" data-toggle="tab" href="#datosMuestreo" role="tab"
                        aria-controls="datosMuestreo" aria-selected="false">2. Datos muestreo</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="datosCompuestos-tab" data-toggle="tab" href="#datosCompuestos" role="tab"
                        aria-controls="datosCompuestos" aria-selected="false">3. Datos Compuestos</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="evidencia-tab" data-toggle="tab" href="#evidencia" role="tab"
                        aria-controls="evidencia" aria-selected="false">4. Evidencia</a>
                </li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade" id="datosGenerales" role="tabpanel" aria-labelledby="datosGenerales-tab">
                    <form>
                        <div class="row">
                            <div class="col-md-12">
                                <h6>Datos generales</h6>
                                <hr>
                            </div>
                            <div class="col-md-2">
                                <p>Id Solicitud: {{ $model->Id_solicitud }}</p>
                                <input type="text" id="idSolicitud" value="{{ $model->Id_solicitud }}" hidden>
                            </div>
                            <div class="col-md-2">
                                <p>Id Ing campo: </p>
                            </div>
                            <div class="col-md-2">
                                <p>Folio servicio: {{ $model->Folio_servicio }}</p>
                            </div>
                            <div class="col-md-2">
                                <p>Captura: {{ $general->Captura }}</p>
                            </div>
                            <div class="col-md-2">
                                <p>Siralab: </p>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <p>Punto de muestreo:</p>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="">Equipo serie</label>
                                <select name="termometro" id="termometro" class="form-control">
                                    <option>Sin seleccionar</option>
                                    @foreach ($termometros as $item)
                                        @if ($general->Id_equipo == $item->Id_termometro)
                                            <option value="{{ $item->Id_termometro }}" selected>{{ $item->Equipo }} /
                                                {{ $item->Marca }} / {{ $item->Modelo }} / {{ $item->Serie }}</option>
                                        @else
                                            <option value="{{ $item->Id_termometro }}">{{ $item->Equipo }} /
                                                {{ $item->Marca }} / {{ $item->Modelo }} / {{ $item->Serie }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Temperatura ambiente</label>
                                <input type="number" class="form-control" placeholder="Temperatura" id="tempAmbiente"
                                    onkeyup="valTempAmbiente()" value="{{ $general->Temperatura_a }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Temperatura búffer</label>
                                <input type="number" class="form-control" placeholder="Temperatura" id="tempBuffer"
                                    onkeyup="valTempAmbiente()" value="{{ $general->Temperatura_b }}">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <h6>Empresa</h6>
                            <hr>
                        </div>
                        <div class="col-md-12">
                            <p>Cliente: {{ $model->Empresa }}</p>
                        </div>
                        <div class="col-md-12">
                            <h6>Croquis punto de muestreo</h6>
                            <hr>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Punto de muestreo</label>
                                <input type="text" class="form-control" placeholder="Punto de muestreo"
                                    value="{{ $model->Direccion }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Latitud</label>
                                <input type="text" class="form-control" placeholder="Latitud" id="latitud"
                                    value="{{ $general->Latitud }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Longitud</label>
                                <input type="text" class="form-control" placeholder="Longitud" id="longitud"
                                    value="{{ $general->Longitud }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Altitud</label>
                                <input type="text" class="form-control" placeholder="Altitud" id="altitud"
                                    value="{{ $general->Altitud }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <p>Muestreo: {{ $frecuencia->Descripcion }}</p>
                        </div>
                        <div class="col-md-4">
                            <p>Número de muestras: {{ $model->Num_tomas }}</p>
                        </div>
                        <div class="col-md-4">
                            <p>Tipo de descarga: {{ $model->Descarga }}</p>
                        </div>
                        <div class="col-md-12">
                            <p>Norma / Material usado muestreo</p>
                            <table class="table" id="materialUsado">
                                <thead>
                                    <tr>
                                        <th>Norma</th>                                        
                                        <th>Preservador</th>
                                        <th>Recipiente</th>
                                        <th>Volúmen</th>
                                        <th>Unidad</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <p>Factor de corrección de temperatura</p>
                            <div class="" id="factorDeConversion">
                                <table class="table" id="">
                                    <thead>
                                        <tr>
                                            <th>De °C</th>
                                            <th>a °C</th>
                                            <th>Factor corrección</th>
                                            <th>Factor de corrección aplicada</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <p>PH trazable</p>
                            <table class="table" id="phTrazable">
                                <thead>
                                    <tr>
                                        <th>PH Trazable</th>
                                        <th>PH Trazable</th>
                                        <th>Marca</th>
                                        <th>No Lote</th>
                                        <th>Lectura 1</th>
                                        <th>Lectura 2</th>
                                        <th>Lectura 3</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select id="phTrazable1" focus>
                                                <option value="0">Sin seleccionar</option>
                                                @foreach ($phTrazable as $item)                                                                                                
                                                    @if ($phCampoTrazable[0]->Id_phTrazable == $item->Id_ph)
                                                        <option value="{{ $item->Id_ph }}" selected>
                                                            {{ $item->Ph }}</option>
                                                    @else
                                                        <option value="{{ $item->Id_ph }}">{{ $item->Ph }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <p id="phTNombre1"></p>
                                        </td>
                                        <td>
                                            <p id="phTMarca1"></p>
                                        </td>
                                        <td>
                                            <p id="phTLote1"></p>
                                        </td>
                                        <td>
                                            <input type="text" class="" placeholder="L1" id="phTl11"
                                                value="{{ $phCampoTrazable[0]->Lectura1 }}"
                                                onkeyup="valPhTrazable('phTl11','phT21','phTl31','phTEstado1', 'phTrazable1')">
                                        </td>
                                        <td>
                                            <input type="text" class="" placeholder="L2" id="phT21"
                                                value="{{ $phCampoTrazable[0]->Lectura2 }}"
                                                onkeyup="valPhTrazable('phTl11','phT21','phTl31','phTEstado1', 'phTrazable1')">
                                        </td>
                                        <td>
                                            <input type="text" class="" placeholder="L3" id="phTl31"
                                                value="{{ $phCampoTrazable[0]->Lectura3 }}"
                                                onkeyup="valPhTrazable('phTl11','phT21','phTl31','phTEstado1', 'phTrazable1')">
                                        </td>
                                        <td><input type="text" id="phTEstado1"
                                                value="{{ $phCampoTrazable[0]->Estado }}"></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select id="phTrazable2">
                                                <option value="0">Sin seleccionar</option>
                                                @foreach ($phTrazable as $item)
                                                    <p>{{ $phCampoTrazable }}</p>
                                                    @if ($phCampoTrazable[1]->Id_phTrazable == $item->Id_ph)
                                                        <option value="{{ $item->Id_ph }}" selected>
                                                            {{ $item->Ph }}</option>
                                                    @else
                                                        <option value="{{ $item->Id_ph }}">{{ $item->Ph }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <p id="phTNombre2"></p>
                                        </td>
                                        <td>
                                            <p id="phTMarca2"></p>
                                        </td>
                                        <td>
                                            <p id="phTLote2"></p>
                                        </td>
                                        <td>
                                            <input type="text" class="" placeholder="L1" id="phTl12"
                                                value="{{ $phCampoTrazable[1]->Lectura1 }}"
                                                onkeyup="valPhTrazable2('phTl12','phT22','phTl32','phTEstado2', 'phTrazable2')">
                                        </td>
                                        <td>
                                            <input type="text" class="" placeholder="L2" id="phT22"
                                                value="{{ $phCampoTrazable[1]->Lectura2 }}"
                                                onkeyup="valPhTrazable2('phTl12','phT22','phTl32','phTEstado2', 'phTrazable2')">
                                        </td>
                                        <td>
                                            <input type="text" class="" placeholder="L3" id="phTl32"
                                                value="{{ $phCampoTrazable[1]->Lectura3 }}"
                                                onkeyup="valPhTrazable2('phTl12','phT22','phTl32','phTEstado2', 'phTrazable2')">
                                        </td>
                                        <td><input type="text" id="phTEstado2"
                                                value="{{ $phCampoTrazable[1]->Estado }}"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <p>PH control calidad</p>
                            <table class="table" id="phControlCalidad">
                                <thead>
                                    <tr>
                                        <th>PH calidad</th>
                                        <th>PH calidad</th>
                                        <th>Marca</th>
                                        <th>No Lote</th>
                                        <th>Lectura 1</th>
                                        <th>Lectura 2</th>
                                        <th>Lectura 3</th>
                                        <th>Estado</th>
                                        <th>Promedio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>                                            
                                            <select id="phCalidad1">
                                                <option value="0">Sin seleccionar</option>
                                                @foreach ($phCalidad as $item)
                                                    @if ($phCampoCalidad[0]->Id_phCalidad == $item->Id_ph)
                                                        <option value="{{ $item->Id_ph }}" selected>
                                                            {{ $item->Ph_calidad }}</option>
                                                    @else
                                                        <option value="{{ $item->Id_ph }}">{{ $item->Ph_calidad }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <p id="phCNombre1"></p>
                                        </td>
                                        <td>
                                            <p id="phCMarca1"></p>
                                        </td>
                                        <td>
                                            <p id="phCLote1"></p>
                                        </td>
                                        <td>
                                            <input type="text" class="" placeholder="L1" id="phC11"
                                                value="{{ $phCampoCalidad[0]->Lectura1 }}"
                                                onkeyup="valPhCalidad('phC11','phC21','phC31','phCEstado1','phCPromedio1')">
                                        </td>
                                        <td>
                                            <input type="text" class="" placeholder="L2" id="phC21"
                                                value="{{ $phCampoCalidad[0]->Lectura2 }}"
                                                onkeyup="valPhCalidad('phC11','phC21','phC31','phCEstado1','phCPromedio1')">
                                        </td>
                                        <td>
                                            <input type="text" class="" placeholder="L3" id="phC31"
                                                value="{{ $phCampoCalidad[0]->Lectura3 }}"
                                                onkeyup="valPhCalidad('phC11','phC21','phC31','phCEstado1','phCPromedio1')">
                                        </td>
                                        <td><input type="text" id="phCEstado1"
                                                value="{{ $phCampoCalidad[0]->Estado }}"></td>
                                        <td><input type="text" id="phCPromedio1"
                                                value="{{ $phCampoCalidad[0]->Promedio }}"></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select id="phCalidad2">
                                                <option value="0">Sin seleccionar</option>
                                                @foreach ($phCalidad as $item)
                                                    @if ($phCampoCalidad[1]->Id_phCalidad == $item->Id_ph)
                                                        <option value="{{ $item->Id_ph }}" selected>
                                                            {{ $item->Ph_calidad }}</option>
                                                    @else
                                                        <option value="{{ $item->Id_ph }}">{{ $item->Ph_calidad }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <p id="phCNombre2"></p>
                                        </td>
                                        <td>
                                            <p id="phCMarca2"></p>
                                        </td>
                                        <td>
                                            <p id="phCLote2"></p>
                                        </td>
                                        <td>
                                            <input type="text" class="" placeholder="L1" id="phC12"
                                                value="{{ $phCampoCalidad[1]->Lectura1 }}"
                                                onkeyup="valPhCalidad('phC12','phC22','phC23','phCEstado2','phCPromedio2')">
                                        </td>
                                        <td>
                                            <input type="text" class="" placeholder="L2" id="phC22"
                                                value="{{ $phCampoCalidad[1]->Lectura2 }}"
                                                onkeyup="valPhCalidad('phC12','phC22','phC23','phCEstado2','phCPromedio2')">
                                        </td>
                                        <td>
                                            <input type="text" class="" placeholder="L3" id="phC23"
                                                value="{{ $phCampoCalidad[1]->Lectura3 }}"
                                                onkeyup="valPhCalidad('phC12','phC22','phC23','phCEstado2','phCPromedio2')">
                                        </td>
                                        <td><input type="text" id="phCEstado2"
                                                value="{{ $phCampoCalidad[1]->Estado }}"></td>
                                        <td><input type="text" id="phCPromedio2"
                                                value="{{ $phCampoCalidad[1]->Promedio }}"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <p>Conductividad trazable</p>
                            <table class="table" id="tableConTrazable">
                                <thead>
                                    <tr>
                                        <th>Conductividad Trazable</th>
                                        <th>Conductividad Trazable</th>
                                        <th>Marca</th>
                                        <th>No Lote</th>
                                        <th>Lectura 1</th>
                                        <th>Lectura 2</th>
                                        <th>Lectura 3</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select id="conTrazable">
                                                <option value="0">Sin seleccionar</option>
                                                @foreach ($conTrazable as $item)
                                                    <option value="{{ $item->Id_conductividad }}">
                                                        {{ $item->Conductividad }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <p id="conNombre"></p>
                                        </td>
                                        <td>
                                            <p id="conMarca"></p>
                                        </td>
                                        <td>
                                            <p id="conLote"></p>
                                        </td>
                                        <td>
                                            <input type="text" class="" placeholder="L1" id="conT1"
                                                onkeyup="valConTrazable('conT1','conT2','conT3','conTEstado')">
                                        </td>
                                        <td>
                                            <input type="text" class="" placeholder="L2" id="conT2"
                                                onkeyup="valConTrazable('conT1','conT2','conT3','conTEstado')">
                                        </td>
                                        <td>
                                            <input type="text" class="" placeholder="L3" id="conT3"
                                                onkeyup="valConTrazable('conT1','conT2','conT3','conTEstado')">
                                        </td>
                                        <td><input type="text" id="conTEstado"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <p>Conductividad control calidad</p>
                            <table class="table" id="tableConCalidad">
                                <thead>
                                    <tr>
                                        <th>Conductividad calidad</th>
                                        <th>Conductividad calidad</th>
                                        <th>Marca</th>
                                        <th>No Lote</th>
                                        <th>Lectura 1</th>
                                        <th>Lectura 2</th>
                                        <th>Lectura 3</th>
                                        <th>Estado</th>
                                        <th>Promedio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select id="conCalidad">
                                                <option value="0">Sin seleccionar</option>
                                                @foreach ($conCalidad as $item)
                                                    <option value="{{ $item->Id_conductividad }}">
                                                        {{ $item->Conductividad }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <p id="conCNombre"></p>
                                        </td>
                                        <td>
                                            <p id="conCMarca"></p>
                                        </td>
                                        <td>
                                            <p id="conCLote"></p>
                                        </td>
                                        <td>
                                            <input type="text" class="" placeholder="L1" id="conCl1"
                                                onkeyup="valConCalidad('conCl1','conCl2','conCl3','conCEstado','conCPromedio')">
                                        </td>
                                        <td>
                                            <input type="text" class="" placeholder="L2" id="conCl2"
                                                onkeyup="valConCalidad('conCl1','conCl2','conCl3','conCEstado','conCPromedio')">
                                        </td>
                                        <td>
                                            <input type="text" class="" placeholder="L3" id="conCl3"
                                                onkeyup="valConCalidad('conCl1','conCl2','conCl3','conCEstado','conCPromedio')">
                                        </td>
                                        <td><input type="text" id="conCEstado"></td>
                                        <td><input type="text" id="conCPromedio"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <p class="">Cálculo de la pendiente</p>
                            <table class="table" id="tableCalPendiente">
                                <tbody>
                                    <tr>
                                        <th>Valor de la pendiente</th>
                                        <th>Criterio</th>
                                    </tr>
                                </tbody>
                                <tbody>
                                    <tr>
                                        <td><input type="text" id="pendiente" placeholder="% Valor"
                                                value="{{ $general->Pendiente }}"
                                                onkeyup="valPendiente('pendiente','criterioPendiente')"></td>
                                        <td><input type="text" id="criterioPendiente" placeholder="Criterio"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <button type="button" class="btn btn-success" onclick="setDataGeneral()"><i
                                class="fa fa-save"></i> Guardar</button>
                    </form>
                </div>
                <div class="tab-pane fade" id="datosMuestreo" role="tabpanel" aria-labelledby="datosMuestreo-tab">
                    <div class="row">
                        <div class="col-md-12">
                            {{-- <button class="btn btn-danger" id="btnCancelarMuestra"><i class="voyager-x"></i> Cancelar Muestra</button> --}}
                        </div>
                        <form>
                            <input type="text" class="" id="numTomas" value="{{ $model->Num_tomas }}" hidden>
                            <div class="col-md-12">
                                <p>PH</p>
                                <table class="table" id="phMuestra">
                                    <thead>
                                        <tr>
                                            <th>Núm Muestra</th>
                                            <th>Materia flotante</th>
                                            <th>Olor</th>
                                            <th>Color</th>
                                            <th>pH 1</th>
                                            <th>pH 2</th>
                                            <th>pH 3</th>
                                            <th>pH Promedio</th>
                                            <th>Fecha muestreo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < $model->Num_tomas; $i++)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td>
                                                    <select id="materia{{ $i }}">
                                                        <option value="0">Sin seleccionar</option>
                                                        <option value="1">Presente</option>
                                                        <option value="2">Ausente</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select id="olor{{ $i }}">
                                                        <option value="0">Sin seleccionar</option>
                                                        <option value="1">Si</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select id="color{{ $i }}">
                                                        <option value="0">Sin seleccionar</option>
                                                        <option value="1">Rojo</option>
                                                        <option value="2">Verde</option>
                                                    </select>
                                                </td>
                                                <td><input type="number" id="phl1{{ $i }}"
                                                        onkeyup='valPhMuestra("phl1{{ $i }}","phl2{{ $i }}","phl3{{ $i }}","phprom{{ $i }}", "phprom1{{ $i }}");'>
                                                </td>
                                                <td><input type="number" id="phl2{{ $i }}"
                                                        onkeyup='valPhMuestra("phl1{{ $i }}","phl2{{ $i }}","phl3{{ $i }}","phprom{{ $i }}", "phprom1{{ $i }}");'>
                                                </td>
                                                <td><input type="number" id="phl3{{ $i }}"
                                                        onkeyup='valPhMuestra("phl1{{ $i }}","phl2{{ $i }}","phl3{{ $i }}","phprom{{ $i }}", "phprom1{{ $i }}");'>
                                                </td>                                                
                                                <td><p id="phprom1{{ $i }}"></p></td>
                                                <td><input type="datetime-local" id="phf{{ $i }}"></td>
                                                <td><input type="text" id="phprom{{ $i }}" hidden></td>
                                            </tr>


                                        @endfor
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-12">
                                <p>Temperatura del agua</p>
                                <table class="table" id="tempAgua">
                                    <thead>
                                        <tr>
                                            <th>Núm Muestra</th>
                                            <th>Temperatura 1 (°C)</th>
                                            <th>Factor de corrección aplicado</th>
                                            <th>Temperatura 2 (°C)</th>
                                            <th>Factor de corrección aplicado</th>
                                            <th>Temperatura 3 (°C)</th>
                                            <th>Factor de corrección aplicado</th>
                                            <th>Temperatura Promedio (°C)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < $model->Num_tomas; $i++)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td><input type="text" id="temp1{{ $i }}"
                                                        onkeyup='valTempMuestra("temp1{{ $i }}","temp2{{ $i }}","temp3{{ $i }}","tempprom{{ $i }}","factorTemp1{{ $i }}","factorTemp2{{ $i }}","factorTemp3{{ $i }}", "tempprom1{{ $i }}");'>
                                                </td>
                                                <td>
                                                    <p id="factorTemp1{{ $i }}"></p>
                                                </td>
                                                <td><input type="text" id="temp2{{ $i }}"
                                                        onkeyup='valTempMuestra("temp1{{ $i }}","temp2{{ $i }}","temp3{{ $i }}","tempprom{{ $i }}","factorTemp1{{ $i }}","factorTemp2{{ $i }}","factorTemp3{{ $i }}", "tempprom1{{ $i }}");'>
                                                </td>
                                                <td>
                                                    <p id="factorTemp2{{ $i }}"></p>
                                                </td>
                                                <td><input type="text" id="temp3{{ $i }}"
                                                        onkeyup='valTempMuestra("temp1{{ $i }}","temp2{{ $i }}","temp3{{ $i }}","tempprom{{ $i }}","factorTemp1{{ $i }}","factorTemp2{{ $i }}","factorTemp3{{ $i }}", "tempprom1{{ $i }}");'>
                                                </td>
                                                <td>
                                                    <p id="factorTemp3{{ $i }}"></p>
                                                </td>
                                                <td><p id="tempprom1{{ $i }}"></p></td>
                                                <td><input type="text" id="tempprom{{ $i }}" hidden></td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-12">
                                <p>Conductividad</p>
                                <table class="table" id="conductividad">
                                    <thead>
                                        <tr>
                                            <th>Núm Muestra</th>
                                            <th>Conductividad 1 (µS)</th>
                                            <th>Conductividad 2 (µS)</th>
                                            <th>Conductividad 3 (µS)</th>
                                            <th>Conductividad Promedio (µS)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < $model->Num_tomas; $i++)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td><input type="text" id="con1{{ $i }}"
                                                        onkeyup='valConMuestra("con1{{ $i }}","con2{{ $i }}","con3{{ $i }}","conprom{{ $i }}","conprom1{{ $i }}");'>
                                                </td>
                                                <td><input type="text" id="con2{{ $i }}"
                                                        onkeyup='valConMuestra("con1{{ $i }}","con2{{ $i }}","con3{{ $i }}","conprom{{ $i }}","conprom1{{ $i }}");'>
                                                </td>
                                                <td><input type="text" id="con3{{ $i }}"
                                                        onkeyup='valConMuestra("con1{{ $i }}","con2{{ $i }}","con3{{ $i }}","conprom{{ $i }}","conprom1{{ $i }}");'>
                                                </td>
                                                <td><p id="conprom1{{ $i }}"></p></td>
                                                <td><input type="text" id="conprom{{ $i }}" hidden></td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-12">
                                <p>Gasto</p>
                                <table class="table" id="gasto">
                                    <thead>
                                        <tr>
                                            <th>Núm Muestra</th>
                                            <th>Gasto 1 (L/s)</th>
                                            <th>Gasto 2 (L/s)</th>
                                            <th>Gasto 3 (L/s)</th>
                                            <th>Gasto Promedio (L/s)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < $model->Num_tomas; $i++)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td><input type="text" id="gas1{{ $i }}"
                                                        onkeyup='valGastoMuestra("gas1{{ $i }}","gas2{{ $i }}","gas3{{ $i }}","gasprom{{ $i }}","gasprom1{{ $i }}");'>
                                                </td>
                                                <td><input type="text" id="gas2{{ $i }}"
                                                        onkeyup='valGastoMuestra("gas1{{ $i }}","gas2{{ $i }}","gas3{{ $i }}","gasprom{{ $i }}","gasprom1{{ $i }}");'>
                                                </td>
                                                <td><input type="text" id="gas3{{ $i }}"
                                                        onkeyup='valGastoMuestra("gas1{{ $i }}","gas2{{ $i }}","gas3{{ $i }}","gasprom{{ $i }}","gasprom1{{ $i }}");'>
                                                </td>
                                                <td><p id="gasprom1{{ $i }}"></p></td>
                                                <td><input type="text" id="gasprom{{ $i }}" hidden></td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>

                    </div>

                    <button type="button" id="btnSaveMuestreo" onclick="setDataMuestreo()" class="btn btn-success"><i
                            class="fa fa-save"></i> Guardar</button>

                    </form>
                </div>
                <div class="tab-pane fade" id="datosCompuestos" role="tabpanel" aria-labelledby="datosCompuestos-tab">
                    <form>
                        <div class="row">
                            <div class="col-md-12">
                                <p>Muestra compuesta</p>
                                <table class="table" id="phTrazable">
                                    <thead>
                                        <tr>
                                            <th>Núm Muestra</th>
                                            <th>Litros a tomar</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <p>Tipo descarga: RESIDUAL</p>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Método aforo</label>
                                    <select name="" id="" class="form-control">
                                        <option>Sin seleccionar</option>
                                        @foreach ($aforo as $item)
                                            <option value="{{ $item->Id_aforo }}">{{ $item->Aforo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Con tratamiento</label>
                                    <select name="" id="" class="form-control">
                                        <option>Sin seleccionar</option>
                                        @foreach ($conTratamiento as $item)
                                            <option value="{{ $item->Id_tratamiento }}">{{ $item->Tratamiento }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Tipo tratamiento</label>
                                    <select name="" id="" class="form-control">
                                        <option>Sin seleccionar</option>
                                        @foreach ($tipo as $item)
                                            <option value="{{ $item->Id_tratamiento }}">{{ $item->Tratamiento }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Procedimiento de Muestreo PE-10-02-</label>
                                    <input type="number" class="form-control" placeholder="Procedimiento">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <textarea class="form-group" style="width: 100%;"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <p>Observación solicitud: ES UN PUNTO DE MUESTREO // SUPERVISIÓN DEL SERVICIO:
                                        ____________ </p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h6></h6>
                                <hr>
                            </div>
                            <div class="col-md-12">
                                <p>Cálculo de muestreo</p>
                                <table class="table" id="phTrazable">
                                    <thead>
                                        <tr>
                                            <th>Norma</th>
                                            <th>Volúmen calculado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $model->Clave_norma }}</td>
                                            <td><input type="text" class="" id="volCalculado" placeholder="Volumen"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>                            

                            <div class="col-md-12">
                                <button class="btn btn-success" onclick="btnGenerar(); return false">Generar</button>
                                
                                <table class="table" id="muestrasQi">
                                    <thead>
                                        <tr>
                                            <th>Núm muestra</th>
                                            <th>Qi</th>
                                            <th>Qt</th>
                                            <th>Qi / Qt</th>
                                            <th>Vmc</th>
                                            <th>Vmsi</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>                            

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">PH Muestra compuesta</label>
                                    <input type="number" class="form-control" placeholder="PH muestra">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Temperatura muestra</label>
                                    <input type="number" class="form-control" id="valTemp" placeholder="Temperatura muestra" onkeyup='valTempCompuesto("valTemp", "facTempApl");'>                                    
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <p>Factor de corrección aplicado: </p>
                                    <p id="facTempApl"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <p>Fecha recepción: </p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <p>Signatario: {{ Auth::user()->name }}</p>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success" onclick="setDataMuestreo()"><i
                                        class="fa fa-save"></i> Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="evidencia" role="tabpanel" aria-labelledby="evidencia-tab">                                        

                    <div class="col-md-4">
                        <input type="file" name="foto" id="imgEvidencia1" accept="image/png, image/jpeg" />
                    </div>
                    <div class="col-md-4">
                        <input type="file" name="foto" id="imgEvidencia2" accept="image/png, image/jpeg" />
                    </div>
                    <div class="col-md-4">                        
                        <button type="submit" class="btn btn-success">Subir Imagen</button>
                    </div>
                    
                    <!--<form method="post" id="formulario-imagen" action="/public/js/campo/captura.js" enctype="multipart/form-data">
                        
                        Datos Evidencia
                        <hr>                        
                        <div class="col-md-4">
                            <input type="file" name="foto" id="imgEvidencia1" accept="image/png, image/jpeg" />
                        </div>
                        <div class="col-md-4">
                            <input type="file" name="foto" id="imgEvidencia2" accept="image/png, image/jpeg" />
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-success" onclick="setEvidencia()">Subir Imagen</button>
                        </div>
                    </form>-->

                </div>
            </div>
        </div>

    </div>
</div>

@endsection


@section('javascript')
<script src="{{ asset('js/campo/captura.js') }}"></script>
<script src="{{ asset('js/libs/componentes.js') }}"></script>
<script src="{{ asset('js/libs/tablas.js') }}"></script>
@stop
