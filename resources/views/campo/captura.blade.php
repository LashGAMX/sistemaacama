@extends('voyager::master')

@section('content')

@section('page_header')
<input type="text" id="numTomas" value="{{$model->Num_tomas}}" hidden>
<input type="text" id="idNorma" value="{{$model->Id_norma}}" hidden>
@stop



<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs" id="myTab" role="tab">
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
                    <div class="row">
                        <div class="col-md-12">
                            <h6>Datos generales</h6>
                            <hr>
                        </div>
                        <div class="col-md-2">
                            <button type="button" id="btnCancelarPunto" class="btn btn-danger btnSubir"><i
                                    class="fas fa-times"></i>
                                CANCELAR</button>
                            </form>
                        </div>
                        <div class="col-md-2">
                            <p>Id Solicitud: {{ $model->Id_solicitud }}</p>
                            <input type="text" id="idSolicitud" value="{{ $model->Id_solicitud }}" hidden>
                        </div>
                        <div class="col-md-2">
                            <p>Id Ing campo: {{$model->Id_muestreador}} </p>
                        </div>
                        <div class="col-md-2">
                            <p>Folio servicio: {{ $model->Folio_servicio }}</p>
                        </div>
                        <div class="col-md-2">
                            {{-- <p>Captura: {{ $general->Captura }}</p> --}}
                        </div>
                        {{-- <div class="col-md-2">
                            <p>Siralab: </p>
                        </div> --}}
                    </div>

                    <div class="col-md-12">
                        <p>Punto de muestreo:</p>
                    </div>

                    <div class="col-md-4">
                        <div>
                            <label for="">Equipo serie (PC-100)</label>
                            <select name="termometro" id="termometro" class="form-control"
                                onchange="getFactorCorreccion(1,'termometro')">
                                <option value="0">Sin seleccionar</option>
                                @foreach ($termometros2 as $item)
                                @if (@$general->Id_equipo == @$item->Id_termometro)
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
                        <div>
                            <label for="">Equipo serie 2 (HANNA)</label>
                            <select name="termometro2" id="termometro2" class="form-control"
                                onchange="getFactorCorreccion(2,'termometro2')">
                                <option value="0">Sin seleccionar</option>
                                @foreach ($termometros as $item)
                                @if (@$general->Id_equipo2 == @$item->Id_termometro)
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
                    <div class="col-md-2">
                        <div>
                            <label for="">Temperatura ambiente</label>
                            <input type="number" class="" placeholder="Temperatura" id="tempAmbiente"
                                onkeyup='valTempAmbiente("tempAmbiente", "tempBuffer")'
                                value="{{ @$general->Temperatura_a }}"
                                oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                maxlength="5"
                                onblur='diferenciaTemperaturas("tempAmbiente", "tempBuffer", "tempAmbiente")' required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Temperatura búffer</label>
                            <input type="number" class="" placeholder="Temperatura" id="tempBuffer"
                                onkeyup='valTempAmbiente("tempAmbiente", "tempBuffer")'
                                oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                maxlength="5" value="{{ @$general->Temperatura_b }}"
                                onblur='diferenciaTemperaturas("tempAmbiente", "tempBuffer", "tempBuffer")'>
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
                        <div>
                            <label for="">Punto de muestreo</label>
                            <input type="text" class="form-control" placeholder="Punto de muestreo" id="puntoMuestreo"
                                value="{{ $puntoSol->Punto }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div>
                            <label for="">Latitud</label>
                            <input type="text" step="any" class="form-control" placeholder="Latitud" id="latitud"
                                value="{{ $general->Latitud }}" onkeyup='validacionLatitud("latitud");'>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div>
                            <label for="">Longitud</label>
                            <input type="text" step="any" class="form-control" placeholder="Longitud" id="longitud"
                                value="{{ $general->Longitud }}" onkeyup='validacionLongitud("longitud");'>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div>
                            <label for="">Altitud</label>
                            <input type="text" step="any" class="form-control" placeholder="Altitud" id="altitud"
                                value="{{ $general->Altitud }}" onkeyup='validacionAltitud("altitud");'>
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
                            <tbody>

                                @foreach ($materiales as $item)
                                <tr>
                                    <td>{{$model->Clave_norma}}</td>
                                    <td>{{$item->Preservacion}}</td>
                                    <td>{{$item->Nombre}}</td>
                                    <td>{{$item->Volumen}}</td>
                                    <td>{{$item->UniEnv}}</td>
                                </tr>
                                @endforeach

                            </tbody>
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
                                @php
                                $temp1 = "";
                                $temp2 = "";
                                $temp3 = "";
                                $cont = 1;
                                @endphp
                                @foreach ($phCampoTrazable as $item)
                                @if ($item->Estado == "Aprobado")
                                <tr id="trTrazable{{$item->Id_ph}}" class="bg-success">
                                    @else
                                <tr id="trTrazable{{$item->Id_ph}}">
                                    @endif
                                    <td>
                                        <select id="phTrazable{{$item->Id_ph}}" name="phTrazable" onchange="getPhTrazable('phTrazable{{$item->Id_ph}}',{{$item->Id_ph}},{{$cont}})">
                                            <option value="0">Sin seleccionar</option>
                                            @foreach ($phTrazable as $item2)
                                                @if ($item2->Id_ph == $item->Id_phTrazable)
                                                <option value="{{ $item2->Id_ph }}" selected> {{ $item2->Ph }}</option>
                                                    @php
                                                    $temp1 = $item2->Ph;
                                                    $temp2 = $item2->Marca;
                                                    $temp3 = $item2->Lote;
                                                    @endphp
                                                @else
                                                    @if ($item2->deleted_at == null)
                                                        <option value="{{ $item2->Id_ph }}">{{ $item2->Ph }}</option>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <p id="phTNombre{{$item->Id_ph}}">{{$temp1}}</p>
                                    </td>
                                    <td>
                                        <p id="phTMarca{{$item->Id_ph}}">{{$temp2}}</p>
                                    </td>
                                    <td>
                                        <p id="phTLote{{$item->Id_ph}}">{{$temp3}}</p>
                                    </td>
                                    <td>
                                        <input type="number" id="phT1{{$item->Id_ph}}" value="{{$item->Lectura1}}"
                                            onkeyup="valPhTrazable('{{$item->Id_ph}}')">
                                    </td>
                                    <td><input type="number" id="phT2{{$item->Id_ph}}"
                                            onkeyup="valPhTrazable('{{$item->Id_ph}}')" value="{{$item->Lectura2}}">
                                    </td>
                                    <td><input type="number" id="phT3{{$item->Id_ph}}"
                                            onkeyup="valPhTrazable('{{$item->Id_ph}}')" value="{{$item->Lectura3}}">
                                    </td>
                                    <td><input type="text" id="phTEstado{{$item->Id_ph}}" value="{{$item->Estado}}">
                                    </td>
                                </tr>
                                @php
                                $cont++;
                                @endphp
                                @endforeach
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
                                @php
                                $temp1 = "";
                                $temp2 = "";
                                $temp3 = "";
                                $tempNum = "";
                                @endphp
                                @foreach ($phCampoCalidad as $item)
                                @if ($item->Estado == "Aprobado")
                                <tr id="trCalidad{{$item->Id_ph}}" class="bg-success">
                                    @else
                                <tr id="trCalidad{{$item->Id_ph}}">
                                    @endif
                                    @foreach ($phCampoCalTemo as $item2)
                                        @if ($item->Id_phCalidad == $item2->Id_ph)
                                            @php
                                            $temp1 = $item2->Ph_calidad;
                                            $temp2 = $item2->Marca;
                                            $temp3 = $item2->Lote;
                                            @endphp
                                        @endif
                                    @endforeach
                                    <td>
                                        <input type="number" id="phCalidad{{$item->Id_ph}}" value="{{$temp1}}" disabled>
                                    </td>
                                    <td>
                                        <p id="phCNombre{{$item->Id_ph}}">{{$temp1}}</p>
                                    </td>
                                    <td>
                                        <p id="phCMarca{{$item->Id_ph}}">{{$temp2}}</p>
                                    </td>
                                    <td>
                                        <p id="phCLote{{$item->Id_ph}}">{{$temp3}}</p>
                                    </td>
                                    <td><input type="number" id="phC1{{$item->Id_ph}}" value="{{$item->Lectura1}}"
                                            onkeyup="valPhCalidad('{{$item->Id_ph}}')"></td>
                                    <td><input type="number" id="phC2{{$item->Id_ph}}" value="{{$item->Lectura2}}"
                                            onkeyup="valPhCalidad('{{$item->Id_ph}}')"></td>
                                    <td><input type="number" id="phC3{{$item->Id_ph}}" value="{{$item->Lectura3}}"
                                            onkeyup="valPhCalidad('{{$item->Id_ph}}')"></td>
                                    <td><input type="text" id="phCEstado{{$item->Id_ph}}" value="{{$item->Estado}}">
                                    </td>
                                    <td><input type="number" id="phCPromedio{{$item->Id_ph}}"
                                            value="{{$item->Promedio}}"></td>
                                </tr>
                                @endforeach
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
                                @php
                                $temp1 = "";
                                $temp2 = "";
                                $temp3 = "";
                                @endphp
                                @if ($conCampoTrazable->Estado == "Aceptado")
                                <tr class="bg-success">
                                    @else
                                <tr>
                                    @endif
                                    <td>
                                        <select id="conTrazable" name="conTrazable"
                                            onchange="getConTrazable('conTrazable')">
                                            <option value="0">Sin seleccionar</option>
                                            @foreach ($conTrazable as $item)
                                            @if (@$conCampoTrazable->Id_conTrazable == $item->Id_conductividad)
                                            <option value="{{ $item->Id_conductividad }}" selected> {{
                                                $item->Conductividad }}</option>
                                            @php
                                            $temp1 = $item->Conductividad;
                                            $temp2 = $item->Marca;
                                            $temp3 = $item->Lote;
                                            @endphp
                                            @else
                                           
                                            @if ($item->deleted_at == null)
                                                <option value="{{ $item->Id_conductividad }}" selected> {{
                                                    $item->Conductividad }}
                                                </option>
                                            @endif
                                            @endif
                                            @endforeach
                                        </select>
                                        </select>
                                    </td>
                                    <td>
                                        <p id="conTNombre">{{$temp1}}</p>
                                    </td>
                                    <td>
                                        <p id="conTMarca">{{$temp2}}</p>
                                    </td>
                                    <td>
                                        <p id="conTLote">{{$temp3}}</p>
                                    </td>
                                    <td>
                                        <input type="number" step="any" class="" placeholder="L1" id="conT1"
                                            value="{{@$conCampoTrazable->Lectura1}}"
                                            onkeyup="valConTrazable('conT1','conT2','conT3','conTEstado')"
                                            onblur='validacionConTrazable("conT1", "conT2", "conT3", "conT1")'>
                                    </td>
                                    <td>
                                        <input type="number" step="any" class="" placeholder="L2" id="conT2"
                                            value="{{@$conCampoTrazable->Lectura2}}"
                                            onkeyup="valConTrazable('conT1','conT2','conT3','conTEstado')"
                                            onblur='validacionConTrazable("conT1", "conT2", "conT3", "conT2")'>
                                    </td>
                                    <td>
                                        <input type="number" step="any" class="" placeholder="L3" id="conT3"
                                            value="{{@$conCampoTrazable->Lectura3}}"
                                            onkeyup="valConTrazable('conT1','conT2','conT3','conTEstado')"
                                            onblur='validacionConTrazable("conT1", "conT2", "conT3", "conT3")'>
                                    </td>
                                    <td><input type="text" id="conTEstado" disabled
                                            value="{{@$conCampoTrazable->Estado}}"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <p>Conductividad control calidad</p>
                        @php
                        $temp1 = "";
                        $temp2 = "";
                        $temp3 = "";
                        @endphp
                        <table class="table" id="tableConCalidad">
                            <thead>
                                <tr>
                                    <th>Conductividad Calidad</th>
                                    <th>Conductividad Calidad</th>
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
                                @if ($conCampoCalidad->Estado == "Aceptado")
                                <tr class="bg-success">
                                    @else
                                <tr>
                                    @endif
                                    <td>
                                        <select id="conCalidad" name="conCalidad"
                                            onchange="getConCalidad('conCalidad')">
                                            <option value="0">Sin seleccionar</option>
                                            @foreach ($conCalidad as $item)
                                            @if (@$conCampoCalidad->Id_conCalidad == $item->Id_conductividad)
                                            <option value="{{ $item->Id_conductividad }}" selected>{{
                                                $item->Conductividad }}</option>
                                            @php
                                            $temp1 = $item->Conductividad;
                                            $temp2 = $item->Marca;
                                            $temp3 = $item->Lote;
                                            @endphp
                                            @else
                                            @if ($item->deleted_at == null)
                                                <option value="{{ $item->Id_conductividad }}" selected> {{
                                                    $item->Conductividad }}
                                                </option>
                                            @endif
                                            @endif
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <p id="conCNombre">{{$temp1}}</p>
                                    </td>
                                    <td>
                                        <p id="conCMarca">{{$temp2}}</p>
                                    </td>
                                    <td>
                                        <p id="conCLote">{{$temp3}}</p>
                                    </td>
                                    <td>
                                        <input type="number" step="any" class="" placeholder="L1" id="conCl1"
                                            value="{{@$conCampoCalidad->Lectura1}}"
                                            onkeyup="valConCalidad('conCl1','conCl2','conCl3','conCEstado','conCPromedio')"
                                            onblur='validacionConCalidad("conCl1", "conCl2", "conCl3", "conCl1")'>
                                    </td>
                                    <td>
                                        <input type="number" step="any" class="" placeholder="L2" id="conCl2"
                                            value="{{@$conCampoCalidad->Lectura2}}"
                                            onkeyup="valConCalidad('conCl1','conCl2','conCl3','conCEstado','conCPromedio')"
                                            onblur='validacionConCalidad("conCl1", "conCl2", "conCl3", "conCl2")'>
                                    </td>
                                    <td>
                                        <input type="number" step="any" class="" placeholder="L3" id="conCl3"
                                            value="{{@$conCampoCalidad->Lectura3}}"
                                            onkeyup="valConCalidad('conCl1','conCl2','conCl3','conCEstado','conCPromedio')"
                                            onblur='validacionConCalidad("conCl1", "conCl2", "conCl3", "conCl3")'>
                                    </td>
                                    <td><input type="text" id="conCEstado" value="{{@$conCampoCalidad->Estado}}"
                                            disabled></td>
                                    <td><input type="text" id="conCPromedio" disabled
                                            value="{{@$conCampoCalidad->Promedio}}"></td>
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
                                @if ($general->Criterio == "Aceptado")
                                <tr class="bg-success">
                                    @else
                                <tr>
                                    @endif
                                    <td><input type="number" step="any" id="pendiente" placeholder="% Valor"
                                            value="{{ $general->Pendiente }}"
                                            onkeyup="valPendiente('pendiente','criterioPendiente')"
                                            onblur='validacionValPendiente("pendiente")'></td>
                                    <td><input type="text" id="criterioPendiente" value="{{@$general->Criterio}}"
                                            disabled></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>


                    <div class="col-md-6">
                        <p class="">Supervisor</p>
                        <input type="text" id="nombreSupervisor" value="{{@$general->Supervisor}}"
                            placeholder="Nombre Supervisor"></td>
                        <br><br>
                    </div>
                    {{-- <div class="col-md-6">
                        <p class="">Firma del supervisor</p>
                        <input type="file" id="firmaSupervisor" value="" placeholder="Firma Supervisor"></td>
                        <br><br>
                    </div> --}}

                    <button type="button" class="btn btn-success" onclick="setDataGeneral()"><i class="fa fa-up"></i>
                        Guardar</button>
                    <button type="button" id="btnSubir" class="btn btn-info btnSubir"><i class="fas fa-arrow-up"></i>
                        Subir</button>
                    </form>
                </div>
                <div class="tab-pane fade" id="datosMuestreo" role="tabpanel" aria-labelledby="datosMuestreo-tab">
                    <div class="row">
                        <div class="col-md-12">
                            {{-- <button class="btn btn-danger" id="btnCancelarMuestra"><i class="voyager-x"></i>
                                Cancelar Muestra</button> --}}
                        </div>
                        <form>
                            <input type="text" class="" id="numTomas" value={{ $model->Num_tomas }} hidden>
                            <div class="col-md-12">
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-target="#modalProbar">Cancelar muestra</button><br>
                                <p>PH : Fecha a empezar muestreo : {{ $model->Fecha_muestreo}}</p>
                                <input type="text" value="{{ $model->Fecha_muestreo}}" id="FechaMuestreo" hidden>
                                <table class="table" id="phMuestra">
                                    <thead>
                                        {{-- <button id="setPhMuestra" type="button" class="btn-success"><i
                                                class="fas fa-save"></i> Guardar</button> --}}
                                        <tr>
                                            <th>Núm Muestra</th>
                                            @if (@$materia->count())
                                            <th>Materia flotante</th>
                                            @else
                                            <th hidden>Materia flotante</th>
                                            @endif
                                            <th>Olor</th>
                                            <th>Color</th>
                                            <th>pH 1</th>
                                            <th>pH 2</th>
                                            <th>pH 3</th>
                                            <th>pH Promedio</th>
                                            <th>Fecha muestreo</th>
                                            <th hidden>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $cont = 0;
                                        @endphp
                                        @foreach ($phMuestra as $item)
                                        @php if ($item->Activo == 1) { $std = ""; } else { $std = "disabled"; } @endphp
                                        @php
                                        $cont++;
                                        @endphp
                                        @if ($item->Promedio != NULL)
                                        <tr id="trPh{{$item->Id_ph}}" class="bg-success">
                                            @else
                                        <tr id="trPh{{$item->Id_ph}}">
                                            @endif
                                            <td>{{$cont}}</td>
                                            @if (@$materia->count())
                                            <td>
                                                @else
                                            <td hidden>
                                                @endif
                                                <select id="materia{{$item->Id_ph}}" {{$std}}>
                                                    @if ($item->Materia == "0") <option value="0" selected>Sin
                                                        seleccionar</option> @else <option value="0">Sin seleccionar
                                                    </option> @endif
                                                    @if ($item->Materia == "Presente") <option value="Presente"
                                                        selected>Presente</option> @else <option value="Presente">
                                                        Presente</option> @endif
                                                    @if ($item->Materia == "Ausente") <option value="Ausente" selected>
                                                        Ausente</option> @else <option value="Ausente">Ausente</option>
                                                    @endif
                                                </select>
                                            </td>
                                            <td>
                                                <select id="olor{{$item->Id_ph}}" {{$std}}>
                                                    @if ($item->Olor == "0") <option value="0" selected>Sin seleccionar
                                                    </option> @else <option value="0">Sin seleccionar</option> @endif
                                                    @if ($item->Olor == "Si") <option value="Si" selected>Si</option>
                                                    @else <option value="Si">Si</option> @endif
                                                    @if ($item->Olor == "No") <option value="No" selected>No</option>
                                                    @else <option value="No">No</option> @endif
                                                </select>
                                            </td>
                                            <td>
                                                <select id="color{{ $item->Id_ph }}" {{$std}}>
                                                    <option value="">Seleccionar</option>
                                                    @foreach ($color as $item2)
                                                    @if ($item->Color == $item2->Color)
                                                    <option value="{{$item2->Color}}" selected>{{$item2->Color}}
                                                    </option>
                                                    @else
                                                    <option value="{{$item2->Color}}">{{$item2->Color}}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="number" id="ph1{{ $item->Id_ph }}"
                                                    onkeyup="valPhMuestra('{{$item->Id_ph}}')" value="{{$item->Ph1}}"
                                                    {{$std}}>
                                            </td>
                                            <td><input type="number" id="ph2{{ $item->Id_ph }}"
                                                    onkeyup="valPhMuestra('{{$item->Id_ph}}')" value="{{$item->Ph2}}"
                                                    {{$std}}>
                                            </td>
                                            <td><input type="number" id="ph3{{ $item->Id_ph }}"
                                                    onkeyup="valPhMuestra('{{$item->Id_ph}}')" value="{{$item->Ph3}}"
                                                    {{$std}}>
                                            </td>
                                            <td>
                                                <input type="text" step="any" style="border: none;"
                                                    id="phProm{{ $item->Id_ph }}" value="{{@$item->Promedio}}" disabled>
                                            </td>
                                            <td style="display:flex">
                                                <input type="datetime-local" id="phf{{ $item->Id_ph }}"
                                                    value="{{@$item->Fecha}}"
                                                    onchange='validacionFechaMuestreo("phf{{@$item->Id_ph}}","FechaMuestreo",1);'>
                                            </td>
                                            <td><input type="text" id="phStatus{{$item->Id_ph}}" value="1" hidden></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-12">
                                <p>Temperatura del agua </p>
                                <table class="table" id="tempAgua">
                                    <thead>
                                        {{-- <button type="button" id="btnTempAgua" onclick="GuardarTempAgua()"
                                            class="btn-success"><i class="fas fa-save"></i> Guardar</button> --}}
                                        <tr>
                                            <th>Núm Muestra</th>
                                            <th>Temperatura 1 (°C)</th>
                                            <th>Temperatura corregida</th>
                                            <th>Temperatura 2 (°C)</th>
                                            <th>Temperatura corregida</th>
                                            <th>Temperatura 3 (°C)</th>
                                            <th>Temperatura corregida</th>
                                            <th>Temperatura Promedio (°C)</th>
                                            <th hidden>Estados</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tempMuestra as $item)
                                        @php if ($item->Activo == 1) { $std = ""; } else { $std = "disabled"; } @endphp
                                        @if ($general->Criterio =="Aceptado")
                                        <tr class="bg-success" id="trTempAgua{{$item->Id_temperatura}}">
                                            @else
                                        <tr id="trTempAgua{{$item->Id_temperatura}}">
                                            @endif
                                            <td>{{$item->Num_toma}}</td>
                                            <td>
                                                <input type="number" id="temp1{{ $item->Id_temperatura }}"
                                                    value="{{$item->TemperaturaSin1}}"
                                                    onkeyup="valTemperaturaAgua({{$item->Id_temperatura}})" {{$std}}>
                                            </td>
                                            <td>
                                                <input type="number" id="tempSin1{{ $item->Id_temperatura }}"
                                                    value="{{$item->Temperatura1}}" disabled>
                                            </td>
                                            <td>
                                                <input type="number" id="temp2{{ $item->Id_temperatura }}"
                                                    value="{{$item->TemperaturaSin2}}"
                                                    onkeyup="valTemperaturaAgua({{$item->Id_temperatura}})" {{$std}}>
                                            </td>
                                            <td>
                                                <input type="number" id="tempSin2{{ $item->Id_temperatura }}"
                                                    value="{{$item->Temperatura2}}" disabled>
                                            </td>
                                            <td>
                                                <input type="number" id="temp3{{ $item->Id_temperatura }}"
                                                    value="{{$item->TemperaturaSin3}}"
                                                    onkeyup="valTemperaturaAgua({{$item->Id_temperatura}})" {{$std}}>
                                            </td>
                                            <td>
                                                <input type="number" id="tempSin3{{ $item->Id_temperatura }}"
                                                    value="{{$item->Temperatura3}}" disabled>
                                            </td>
                                            <td>
                                                <input type="text" id="tempprom{{ $item->Id_temperatura }}"
                                                    value="{{$item->Promedio}}" disabled>
                                            </td>
                                            <td><input type="text" id="tempStatus{{$item->Id_temperatura}}" value="1"
                                                    hidden></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>

                            <div class="col-md-12">
                                <p>Temperatura del ambiente </p>
                                <table class="table" id="tabTempAmbiente">
                                    <thead>
                                        {{-- <button type="button" id="btnTempAmb" onclick="GuardarTempAmb()"
                                            class="btn-success"><i class="fas fa-save"></i> Guardar</button> --}}
                                        <tr>
                                            <th>Núm Muestra</th>
                                            <th>Temperatura 1 (°C)</th>
                                            <th>Fact. Aplicado</th>
                                            <th>Temperatura corregida</th>
                                            <th hidden>Estados</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tempAmbiente as $item)
                                        <tr id="trTempAmbiente{{$item->Id_temperatura}}">
                                            <td>{{$item->Num_toma}}</td>
                                            <td>
                                                <input type="number" id="tempa1{{ $item->Id_temperatura }}"
                                                    value="{{$item->TemperaturaSin1}}"
                                                    onkeyup="valTemperaturaAmbiente({{$item->Id_temperatura}})">
                                            </td>
                                            <td>
                                                <input type="number" id="tempaApl1{{ $item->Id_temperatura }}"
                                                    value="{{$item->Fact_apl}}" disabled>
                                            </td>
                                            <td>
                                                <input type="number" id="tempaSin1{{ $item->Id_temperatura }}"
                                                    value="{{$item->Temperatura1}}" disabled>
                                            </td>
                                            <td><input type="text" id="tempAStatus{{$item->Id_temperatura}}" value="1"
                                                    hidden></td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>


                            </div>

                            @if ($model->Num_tomas > 1)
                            <div class="col-md-12">
                                @else
                                <div class="col-md-12" hidden>
                                    @endif
                                    <p>PH control calidad</p>
                                    <table class="table" id="phControlCalidadMuestra">
                                        <thead>
                                            {{-- <button type="button" onclick="GuardarPhControlCalidad()"
                                                class="btn-success"><i class="fas fa-save"></i> Guardar</button> --}}
                                            <tr>
                                                <th>Núm Muestra</th>
                                                <th>PH calidad</th>
                                                <th>Lectura 1</th>
                                                <th>Lectura 2</th>
                                                <th>Lectura 3</th>
                                                <th>Estado</th>
                                                <th>Promedio</th>
                                                <th hidden>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($phCalidadCampo as $item)
                                            @php if ($item->Activo == 1) { $std = ""; } else { $std = "disabled"; }
                                            @endphp
                                            <tr id="trCalidadMuestra{{$item->Id_phCalidad}}">
                                                <td>{{$item->Num_toma}}</td>
                                                <td>
                                                    <select id="phControlMuestra{{$item->Id_phCalidad}}" {{$std}}>
                                                        <option value="0">Sin seleccionar</option>
                                                        @if ($item->Ph_calidad == 7)
                                                        <option value="7" selected>7</option>
                                                        @else
                                                        <option value="7">7</option>
                                                        @endif
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" step="any" value="{{$item->Lectura1}}"
                                                        onkeyup="valPhCalidadMuestra('{{$item->Id_phCalidad}}')"
                                                        id="phCM1{{$item->Id_phCalidad}}" {{$std}}>
                                                </td>

                                                <td>
                                                    <input type="number" step="any" value="{{$item->Lectura2}}"
                                                        onkeyup="valPhCalidadMuestra('{{$item->Id_phCalidad}}')"
                                                        id="phCM2{{$item->Id_phCalidad}}" {{$std}}>
                                                </td>

                                                <td>
                                                    <input type="number" step="any" value="{{$item->Lectura3}}"
                                                        onkeyup="valPhCalidadMuestra('{{$item->Id_phCalidad}}')"
                                                        id="phCM3{{$item->Id_phCalidad}}" {{$std}}>
                                                </td>
                                                <td>
                                                    <input type="text" id="phCMEstado{{$item->Id_phCalidad}}"
                                                        value="{{$item->Estado}}" disabled>
                                                </td>
                                                <td><input type="text" id="phCMPromedio{{$item->Id_phCalidad}}"
                                                        value="{{$item->Promedio}}" disabled>
                                                </td>
                                                <td><input type="text" id="phCMStatus{{$item->Id_phCalidad}}" value="1"
                                                        hidden></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-12">
                                    <p>Conductividad</p>
                                    <table class="table" id="conductividad">
                                        <thead>
                                            {{-- <button type="button" id="btnConductividad"
                                                onclick="GuardarConductividad()" class="btn-success"><i
                                                    class="fas fa-save"></i> Guardar</button> --}}
                                            <tr>
                                                <th>Núm Muestra</th>
                                                <th>Conductividad 1 (µS)</th>
                                                <th>Conductividad 2 (µS)</th>
                                                <th>Conductividad 3 (µS)</th>
                                                <th>Conductividad Promedio (µS)</th>
                                                <th hidden>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($conductividadMuestra as $item)
                                            @php if ($item->Activo == 1) { $std = ""; } else { $std = "disabled"; }
                                            @endphp
                                            <tr id="trConducMuestra{{$item->Id_conductividad}}">
                                                <td>{{$item->Num_toma}}</td>
                                                <td><input type="number"
                                                        onkeyup="valConMuestra({{$item->Id_conductividad}})"
                                                        id="con1{{ $item->Id_conductividad }}"
                                                        value="{{$item->Conductividad1}}" {{$std}}></td>
                                                <td><input type="number"
                                                        onkeyup="valConMuestra({{$item->Id_conductividad}})"
                                                        id="con2{{ $item->Id_conductividad }}"
                                                        value="{{$item->Conductividad2}}" {{$std}}></td>
                                                <td><input type="number"
                                                        onkeyup="valConMuestra({{$item->Id_conductividad}})"
                                                        id="con3{{ $item->Id_conductividad }}"
                                                        value="{{$item->Conductividad3}}" {{$std}}></td>
                                                <td><input type="number"
                                                        onkeyup="valConMuestra({{$item->Id_conductividad}})"
                                                        id="conProm{{$item->Id_conductividad}}"
                                                        value="{{$item->Promedio}}" disabled></td>
                                                <td><input type="number"
                                                        onkeyup="valConMuestra({{$item->Id_conductividad}})"
                                                        id="conStatus{{$item->Id_conductividad}}" value="1" hidden></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-12">
                                    <p>Gasto</p>
                                    <table class="table" id="gasto">
                                        <thead>
                                            {{-- <button type="button" id="btnGasto" onclick="GuardarGasto()"
                                                class="btn-success"><i class="fas fa-save"></i> Guardar</button> --}}
                                            <tr>
                                                <th>Núm Muestra</th>
                                                <th>Gasto 1 (L/s)</th>
                                                <th>Gasto 2 (L/s)</th>
                                                <th>Gasto 3 (L/s)</th>
                                                <th>Gasto Promedio (L/s)</th>
                                                <th hidden>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($gastoMuestra as $item)
                                            @php if ($item->Activo == 1) { $std = ""; } else { $std = "disabled"; }
                                            @endphp
                                            <tr id="trGastoMuestra{{$item->Id_gasto}}">
                                                <td>{{$item->Num_toma}}</td>
                                                <td><input type="text" id="gas1{{ $item->Id_gasto }}"
                                                        onkeyup="valGastoMuestra({{$item->Id_gasto}})"
                                                        value="{{$item->Gasto1}}" {{$std}}></td>
                                                <td><input type="text" id="gas2{{ $item->Id_gasto }}"
                                                        onkeyup="valGastoMuestra({{$item->Id_gasto}})"
                                                        value="{{$item->Gasto2}}" {{$std}}></td>
                                                <td><input type="text" id="gas3{{ $item->Id_gasto }}"
                                                        onkeyup="valGastoMuestra({{$item->Id_gasto}})"
                                                        value="{{$item->Gasto3}}" {{$std}}></td>
                                                <td><input type="text" id="gasprom{{$item->Id_gasto}}"
                                                        value="{{$item->Promedio}}" disabled></td>
                                                <td><input type="text" id="gastoStatus{{$item->Id_gasto}}" value="1"
                                                        hidden>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Esqueleto Nuevo parametro Vidrio Fisher -->
                                <div class="col-md-12">
                                    <p>VIBRIO FICHERI</p>
                                    <table class="table" id="vidrio">
                                        <thead>
                                            <tr>
                                                <th>Núm Muestra</th>
                                                <th>Oxigeno D</th>
                                                <th>Burbujas/Espuma</th>
                                                <th hidden>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($vidrio as $item)
                                            <tr id="trVidrio{{$item->Id_vidrio}}">
                                                <td>{{$item->Num_toma}}</td>
                                                <td>
                                                    <input type="text" id="Vidrio1{{$item->Id_vidrio}}"
                                                        value="{{ $item->Oxigeno }}"
                                                        onkeyup="valVidrio({{$item->Id_vidrio}})">
                                                </td>
                                                <td>
                                                    <select id="selectVidrio{{$item->Id_vidrio}}"
                                                        onchange="valVidrio({{$item->Id_vidrio}})">
                                                        <option value="" disabled {{ $item->Burbujas === null ?
                                                            'selected' : '' }}>Seleccione una opción</option>
                                                        <option value="si" {{ $item->Burbujas == 1 ? 'selected' : ''
                                                            }}>Sí</option>
                                                        <option value="no" {{ $item->Burbujas == 0 ? 'selected' : ''
                                                            }}>No</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>



                                <button type="button" id="btnGuardarTodo" class="btn btn-success"><i
                                        class="fas fa-save"></i>Guardar</button> &nbsp;
                                <button type="button" id="btnSubir" class="btn btn-info btnSubir"><i
                                        class="fas fa-arrow-up"></i>Subir</button>
                            </div>


                    </div>
                    <div class="tab-pane fade" id="datosCompuestos" role="tabpanel"
                        aria-labelledby="datosCompuestos-tab">
                        <form>
                            <div class="row">
                                <div class="col-md-12" {{$hidden}}>
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
                                    <p>Tipo descarga: </p>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Método aforo</label>
                                        <select name="" id="aforoCompuesto" class="form-control">
                                            <option value="0">Sin seleccionar</option>
                                            @foreach ($aforo as $item)
                                            @if ($compuesto->Metodo_aforo == $item->Id_aforo)
                                            <option value="{{ $item->Id_aforo }}" selected>{{ $item->Aforo }}</option>
                                            @else
                                            <option value="{{ $item->Id_aforo }}">{{ $item->Aforo }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Con tratamiento</label>
                                        <select name="" id="conTratamientoCompuesto" class="form-control">
                                            <option value="0">Sin seleccionar</option>
                                            @foreach ($conTratamiento as $item)
                                            @if ($compuesto->Con_tratamiento == $item->Id_tratamiento)
                                            <option value="{{ $item->Id_tratamiento }}" selected>{{ $item->Tratamiento
                                                }}
                                            </option>
                                            @else
                                            <option value="{{ $item->Id_tratamiento }}">{{ $item->Tratamiento }}
                                            </option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Tipo tratamiento</label>
                                        <select name="" id="tipoTratamientoCompuesto" class="form-control">
                                            <option value="0">Sin seleccionar</option>
                                            @foreach ($tipo as $item)
                                            @if ($compuesto->Tipo_tratamiento == $item->Id_tratamiento)
                                            <option value="{{ $item->Id_tratamiento }}" selected>{{ $item->Tratamiento
                                                }}
                                            </option>
                                            @else
                                            <option value="{{ $item->Id_tratamiento }}">{{ $item->Tratamiento }}
                                            </option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Procedimiento de Muestreo PE-10-02-</label>
                                        <input type="number" id="procedimientoCompuesto" class="form-control"
                                            placeholder="Procedimiento" value="{{$compuesto->Proce_muestreo}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <textarea id="observacionCompuesto" class="form-group"
                                            style="width: 100%;">{{@$compuesto->Observaciones}}</textarea>
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
                                <div class="col-md-12" {{$hidden}}>
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
                                                <td><input type="text" class="" id="volCalculado"
                                                        value="{{@$compuesto->Volumen_calculado}}"
                                                        placeholder="Volumen">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-12" {{$hidden}}>
                                    <button class="btn btn-success" type="button"
                                        onclick="generarVmsi()">Generar</button>

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

                                <div class="col-md-4" {{$hidden}}>
                                    <div class="form-group">
                                        <label for="">PH Muestra compuesta</label>
                                        <input type="number" id="phMuestraCompuesto"
                                            value="{{@$compuesto->Ph_muestraComp}}" class="form-control"
                                            placeholder="PH muestra">
                                    </div>
                                </div>
                                <div class="col-md-4" {{$hidden}}>
                                    <div class="form-group">
                                        <label for="">Temperatura muestra compuesta</label>
                                        <input type="number" class="form-control" id="valTemp"
                                            value="{{@$compuesto->Temp_muestraComp}}" placeholder="Temperatura muestra"
                                            onkeyup='valTempCompuesto("valTemp", "facTempApl");'>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Cloruros</label>
                                        {{-- <input type="number" class="form-control" id="valCloruros"
                                            value="{{@$compuesto->Cloruros}}" placeholder="Cloruros"> --}}
                                        {{-- <select id="valCloruros" class="form-control">
                                            @if (@$compuesto->Cloruros == "")<option selected value="0"> Sin seleccionar
                                            </option> @else <option value="0"> Sin seleccionar</option> @endif
                                            @if (@$compuesto->Cloruros == 0)<option selected value="0">
                                                <= 500</option> @else
                                            <option value="0">
                                                <= 500</option> @endif
                                                    @if (@$compuesto->Cloruros == 500)
                                            <option selected value="500">500</option> @else <option value="500">500
                                            </option> @endif
                                            @if (@$compuesto->Cloruros == 1000)<option selected value="1000">1000
                                            </option> @else <option value="1000">1000</option> @endif
                                            @if (@$compuesto->Cloruros == 1500)<option selected value="1500">1500
                                            </option> @else <option value="1500">1500</option> @endif
                                            @if (@$compuesto->Cloruros == 2000)<option selected value="2000">2000
                                            </option> @else <option value="2000">2000</option> @endif
                                            @if (@$compuesto->Cloruros == 3000)<option selected value="3000">>= 3000
                                            </option> @else <option value="3000">>= 3000</option> @endif
                                        </select> --}}
                                        <select id="valCloruros" class="form-control">
                                            @if (@$compuesto->Cloruros == "")<option selected value=""> Sin seleccionar
                                            </option> @else <option value=""> Sin seleccionar</option> @endif
                                            @if (@$compuesto->Cloruros == 499)<option selected value="499">
                                                < 500</option> @else
                                            <option value="499">
                                                < 500</option> @endif
                                                    @if (@$compuesto->Cloruros == 500)
                                            <option selected value="500">500</option> @else <option value="500">500
                                            </option> @endif
                                            @if (@$compuesto->Cloruros == 1000)<option selected value="1000">1000
                                            </option> @else <option value="1000">1000</option> @endif
                                            @if (@$compuesto->Cloruros == 1500)<option selected value="1500">> 1000
                                            </option> @else <option value="1500">> 1000</option> @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Cloro</label>
                                        <input type="text" class="form-control" id="cloroMuestra"
                                            value="{{@$compuesto->Cloro}}" placeholder="Cloro muestra">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <p>Redondeo de temperatura</p>
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
                                    <button type="button" class="btn btn-success" onclick="SetDatosCompuestos()"><i
                                            class="fa fa-save"></i> Guardar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- Seccion de evidencia -->
                    <div class="tab-pane fade" id="evidencia" role="tabpanel" aria-labelledby="evidencia-tab">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <form action="{{url('/admin/campo/captura/setEvidencia')}}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <label for="">Evidencia de campo </label>
                                            <input type="text" id="idSolEv" name="idSolEv"
                                                value="{{$puntos->Id_solicitud}}" hidden>
                                            <input type="text" id="idPuntEv" name="idPuntEv"
                                                value="{{$puntos->Id_muestreo}}" hidden>
                                            <input type="file" name="file" id="" accept="image/*" required>
                                            <button type="submit" class="btn btn-primary">Subir imagen</button>
                                        </form>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <form action="{{url('/admin/campo/captura/setEvidenciaFirma')}}"
                                                    method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    <label for="firma">Subir firma de supervisor</label>
                                                    <input type="text" id="idSolEvFir" name="idSolEvFir"
                                                        value="{{$puntos->Id_solicitud}}" hidden>
                                                    <input type="text" id="idPuntEvFir" name="idPuntEvFir"
                                                        value="{{$puntos->Id_muestreo}}" hidden>
                                                    <input type="file" name="file" id="" accept="image/*" required>
                                                    <button type="submit" class="btn btn-primary">Subir imagen</button>
                                                </form>
                                            </div>
                                            <div class="col-md-6">
                                                @if (@$general->Firma_revisor == NULL)
                                                <p>Sin firma registrada</p>
                                                @else
                                                <img class="zoom"
                                                    src="data:image/gif;base64,{{@$general->Firma_revisor}}"
                                                    style="width: 100px;height: auto;">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Imagen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($evidencia as $item)
                                @if ($item->Id_solicitud == $puntos->Id_solicitud)
                                <tr class="bg-success">
                                    @else
                                <tr class="bg-info">
                                    @endif

                                    <td>{{$item->created_at}}</td>
                                    <td><img class="zoom" src="data:image/gif;base64,{{$item->Base64}}"
                                            style="width: 100px;height: auto;"></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Seccion de evidencia -->
                </div>
            </div>



        </div>
    </div>

    <style>
        .zoom {
            transition: transform .2s;
        }

        .zoom:hover {
            transform: scale(4.5);
        }
    </style>


    {{-- Modal; Cancelar muestra --}}
    <div class="modal fade" id="modalProbar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                {{-- Modal; Header --}}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <h5 class="modal-title" id="exampleModalLabel">Cancelar muestra</h5>

                </div>

                {{-- Modal; Body --}}
                <div class="modal-body" id="modal">

                    <center>
                        <p>¿Qué número de muestra deseas cancelar?</p>

                        <select class="form-select" aria-label="Default select example" id="selectCancelMuestra">
                            <option value="0" selected>Sin seleccionar</option>
                            @for ($i = 1; $i <= $model->Num_tomas; $i++)
                                <option value="{{$i}}">{{$i}}</option>
                                @endfor

                        </select>
                    </center>

                </div>

                {{-- Modal; Footer --}}
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" onclick="CancelarMuestra()">Aplicar</button>

                </div>
            </div>
        </div>
    </div>
    {{-- Fin modal; Cancelar muestra --}}

    {{-- Modal; Revertir muestra --}}

    <div class="modal fade" id="modalProbar2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                {{-- Modal; Header --}}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <h5 class="modal-title" id="exampleModalLabel">Revertir muestra</h5>

                </div>

                {{-- Modal; Body --}}
                <div class="modal-body" id="modal">

                    <p>¿Qué número de muestra deseas revertir?</p>

                    <select class="form-select" aria-label="Default select example" id="selectRevierteMuestra">
                        <option value="0" selected>Sin seleccionar</option>

                        @for ($i = 1; $i <= $model->Num_tomas; $i++)
                            {{-- @if (@$phMuestra[$i-1]->Activo == 0)
                            <option value={{$i}}>{{$i}}</option>
                            @endif --}}
                            <option value={{$i}}>{{$i}}</option>
                            @endfor

                    </select>

                </div>

                {{-- Modal; Footer --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" {{-- data-dismiss="modal" --}}
                        onclick="revierteMuestra();">Aplicar</button>

                </div>
            </div>
        </div>
    </div>

    <style>
        .ir-arriba {
            display: none;
            padding: 20px;
            background: #024959;
            font-size: 20px;
            color: #fff;
            cursor: pointer;
            position: fixed;
            bottom: 20px;
            right: 20px;
        }
    </style>

    {{-- Fin modal; Revertir muestra --}}

    @endsection

    @section('css')
    <link rel="stylesheet" href="{{ asset('/public/css/campo/captura.css')}}">
    @endsection

    @section('javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"
        integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('/public/js/campo/captura.js') }}?v=1.0.8"></script>
    @stop