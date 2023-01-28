@extends('voyager::master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="datos-tab" data-toggle="tab" href="#datos" role="tab"
                            aria-controls="datos" aria-selected="true">1. Datos</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="parametro-tab" data-toggle="tab" href="#parametro" role="tab"
                            aria-controls="parametro" aria-selected="false">2. Parametros</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="cotizacion-tab" data-toggle="tab" href="#cotizacion" role="tab"
                            aria-controls="cotizacion" aria-selected="false">3. Cotización</a>
                    </li>
                </ul>
        
                <div class="tab-content" id="myTabContent">
                    {{-- Inicio Datos --}}
                    <div class="tab-pane fade" id="datos" role="tabpanel" aria-labelledby="datos-tab">
                     <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <h6>Datos cliente</h6>
                                <hr> 
                            </div>
                            <div class="dropdown-divider"></div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="intermediario">Intermediario</label>
                                    <select name="intermediario" id="intermediario" onchange="getClientesIntermediarios()" class="form-control select2">
                                        <option>Sin seleccionar</option>
                                        @foreach ($intermediarios as $item)
                                            <option value="{{ $item->Id_intermediario }}">{{ $item->Nombres }}{{ $item->A_paterno }}</option>
                                        @endforeach 
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="cliente">Clientes registrados padre</label>
                                    <select  id="cliente" onchange="getSucursal()" class="form-control select2">
                                        <option value="0">Sin seleccionar</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="clienteSucursal">Clientes Sucursal (Hijos) </label> 
                                            <select onchange="getDataCliente()" id="clienteSucursal" class="form-control select2">
                                                <option value="0">Sin seleccionar</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="clienteDir">Dirección cliente</label> 
                                            <select id="clienteDir" class="form-control select2" onchange="setDireccionCliente()">
                                                <option value="0">Sin seleccionar</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <td>Cliente</td>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" placeholder="Nombre del cliente" id="nomCli" style="width: 100%">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <td>Dirección</td>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" placeholder="Dirección del cliente" id="dirCli" style="width: 100%">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <td>Con atención a</td>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" placeholder="Con a tención a" id="atencion" style="width: 100%">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            Telefono
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" placeholder="Telefono" id="telCli" style="width: 100%">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <td>Correo</td>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" placeholder="Correo electronico" id="correoCli" style="width: 100%">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="dropdown-divider"></div>
                            <div class="col-md-12">
                                <h6>Datos generales</h6>
                                <hr> 
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="tipoServicio">Tipo de servicio</label>
                                            <select name="tipoServicio" id="tipoServicio" class="form-control">
                                                @foreach ($servicios as $item)
        
                                                    @if (@$model->Tipo_servicio == $item)
                                                        <option value="{{ $item->Id_tipo }}" selected>{{ $item->Servicio }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $item->Id_tipo }}">{{ $item->Servicio }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="tipoDescarga">Tipo descarga</label>
                                            <select name="tipoDescarga" id="tipoDescarga" class="form-control">
                                                @foreach ($descargas as $item)
                                                    @if (@$model->Tipo_descarga == $item->Id_tipo)
                                                        <option value="{{ $item->Id_tipo }}" selected>{{ $item->Descarga }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $item->Id_tipo }}">{{ $item->Descarga }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="norma">Clasificación de la norma</label>
                                                <select name="norma" id="norma" class="form-control">
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="subnorma">Norma</label>
                                                <select name="subnorma" id="subnorma" class="form-control">
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="fecha">Fecha de muestreo</label>
                                            <input type="date" class="form-control" placeholder="Fecha" id="fecha" name="fecha" value="{{ @$model->Fecha_muestreo }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="frecuencia">Frecuencia muestreo</label>
                                            <select class="form-control" placeholder="Frecuencia" id="frecuencia"
                                                name="frecuencia">
                                                @foreach ($frecuencia as $item)
                                                    @if (@$model->Frecuencia_muestreo == $item->Id_frecuencia)
                                                        <option value="{{ $item->Id_frecuencia }}" selected>
                                                            {{ $item->Descripcion }}</option>
                                                    @else
                                                        <option value="{{ $item->Id_frecuencia }}">{{ $item->Descripcion }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="tomas"># de tomas</label>
                                            <input type="number" class="form-control" placeholder="# de tomas" id="tomas2" name="tomas2" value="{{ @$model->Tomas }}" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <div class="col-md-12">
                                <h6>Espesificaciones</h6>
                                <hr> 
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="tipoMuestra">Tipo de muestra</label>
                                            <select name="tipoMuestra" id="tipoMuestra" class="form-control">                                        
                                                @foreach($tipoMuestraCot as $item)
                                                    @if (@$model->Id_tipoMuestra == $item->Id_muestraCot)
                                                        <option value="{{$item->Id_muestraCot}}" selected>{{$item->Tipo}}</option>    
                                                    @else
                                                        <option value="{{$item->Id_muestraCot}}">{{$item->Tipo}}</option>    
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="promedio">Promedio</label>
                                            <select name="promedio" class="form-control" id="promedio">
                                                @foreach($promedioCot as $item)
                                                    @if (@$model->Id_promedio == $item->Id_promedioCot)
                                                        <option value="{{$item->Id_promedioCot}}" selected>{{$item->Promedio}}</option>    
                                                    @else
                                                        <option value="{{$item->Id_promedioCot}}">{{$item->Promedio}}</option>    
                                                    @endif 
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="tipoReporte">Tipo de reporte</label>
                                            <select name="tipoReporte" id="tipoReporte" class="form-control">
                                                <option value="0">Sin seleccionar</option>
                                                @foreach (@$categorias001 as $item)
                                                    @if ($item->Id_detalle == @$model->Tipo_reporte)
                                                        <option value="{{$item->Id_detalle}}" selected>{{$item->Detalle}} ({{$item->Tipo}})</option>
                                                    @else
                                                        <option value="{{$item->Id_detalle}}">{{$item->Detalle}} ({{$item->Tipo}})</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                    <div class="col-md-8">
                                        <label for="puntoMuestro">Punto de muestreo</label>
                                        <button id="addRow" type="button" class="btn bg-success"><i class="voyager-list-add"></i> Agregar</button>
                                        <button id="delRow" type="button" class="btn bg-danger"><i class="voyager-trash"></i> Eliminar</button>
                                        <table id="puntoMuestro" class="display" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th style="width: 90%">Descripción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $contPunto = 0;
                                                @endphp
                                                @if (@$sw == 1)
                                                    @foreach ($cotizacionPuntos as $item)
                                                        @php
                                                            $contPunto++;
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $contPunto }}</td>
                                                            <td><input class="form-control" id="punto{{ $contPunto - 1 }}" value="{{ $item->Descripcion }}"></td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>

                                        </table>
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn btn-success btn-sm"><i class="fas fa-save"></i> Guardar</button>
                                    </div>
                           
                            </div>

                        </div>
                     </div>
                    {{-- Fin datos --}}
                    {{-- Inicio parametros --}}
                    <div class="tab-pane fade" id="parametro" role="tabpanel" aria-labelledby="parametro-tab">

                    </div>
                    {{-- Fin parametros --}}
                    {{-- Inicio datos Cotizacion --}}
                    <div class="tab-pane fade" id="cotizacion" role="tabpanel" aria-labelledby="cotizacion-tab">
                      
                    </div>

                    <div class="col-md-12" hidden>                        
                        <input type="number" class="form-control" id="tomas" name="tomas" value="{{ @$model->Tomas }}">
                      </div>
                    </form>
                    {{-- Fin datos Cotizacion --}}
                </div>
            </div>
        </div>

        <div id="divModal">
        </div>
 
    @endsection
    @section('javascript')

        <script src="{{ asset('/public/js/cotizacion/create.js') }}?v=0.0.4"></script>
        
    @stop
