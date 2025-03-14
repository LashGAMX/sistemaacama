@extends('voyager::master')

@section('content')

<link rel="stylesheet" type="text/css" href="{{asset('/public/css/libs/duallist/jquery.transfer.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('/public/css/libs/duallist/icon_font/css/icon_font.css')}}" />
<link rel="stylesheet" href="{{asset('/public/assets/summer/summernote.min.css')}}">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">

            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="datos-tab" data-toggle="tab" href="#datos" role="tabpanel"
                        aria-controls="datos" aria-selected="true">1. Datos</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="parametro-tab" data-toggle="tab" href="#parametro" role="tabpanel"
                        aria-controls="parametro" aria-selected="false">2. Parametros</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="cotizacion-tab" data-toggle="tab" href="#cotizacion" role="tabpanel"
                        aria-controls="cotizacion" aria-selected="false">3. Cotización</a>
                </li>
            </ul>
 
            <div class="tab-content" id="myTabContent">
                {{-- Inicio Datos --}}
                <div class="tab-pane fade" id="datos" role="tabpanel" aria-labelledby="datos-tab">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <input type="number" name="" id="idCot" value="{{@$model->Id_cotizacion}}" hidden>
                                <h6>Datos cliente</h6>
                                <hr> 
                            </div>
                            <div class="dropdown-divider"></div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="intermediario">Intermediario</label>
                                    <select id="intermediario" onchange="getClientesIntermediarios()" class="form-control select2">
                                        <option value="0">Sin seleccionar</option>
                                        @foreach ($intermediarios as $item)
                                            <option value="{{ $item->Id_intermediario }}">{{ $item->Nombres }}{{$item->A_paterno }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="cliente">Clientes registrados padre</label>
                                    <select id="cliente" onchange="getSucursal()" class="form-control select2">
                                        <option value="0">Sin seleccionar</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="clienteSucursal">Clientes Sucursal (Hijos) </label>
                                            <select onchange="getDataCliente()" id="clienteSucursal"
                                                class="form-control select2">
                                                <option value="0">Sin seleccionar</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="clienteDir">Dirección cliente</label>
                                            <select id="clienteDir" class="form-control select2" onchange="setDireccionCliente()">
                                                <option value="0">Sin seleccionar</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="clienteGen">Datos Generales</label>
                                            <select id="clienteGen" class="form-control select2" onchange="setDatoGeneral()">
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
                                            <input type="text" placeholder="Nombre del cliente" id="nomCli"
                                                style="width: 100%" value="{{@$model->Nombre}}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <td>Dirección</td>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" placeholder="Dirección del cliente" id="dirCli"
                                                style="width: 100%" value="{{@$model->Direccion}}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <td>Con atención a</td>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" placeholder="Con a tención a" id="atencion" style="width: 100%" value="{{@$model->Atencion}}"> 
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            Telefono
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" placeholder="Telefono" id="telCli" style="width: 100%" value="{{@$model->Telefono}}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <td>Correo</td>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" placeholder="Correo electronico" id="correoCli"
                                                style="width: 100%" value="{{@$model->Correo}}">
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

                                                @if (@$model->Tipo_servicio == $item->Id_tipo)
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
                                            <select name="tipoDescarga" id="tipoDescarga" class="form-control"
                                                onchange="getNormas()">
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
                                            <select id="norma" class="form-control" onchange="getSubNormas()">
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="subnorma">Norma</label>
                                            <select id="subnorma" class="form-control" onchange="getParametrosNorma()">
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="fecha">Fecha de muestreo</label>
                                            <input type="date" class="form-control" placeholder="Fecha" id="fecha"
                                                name="fecha" value="{{ @$model->Fecha_muestreo }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="frecuencia">Frecuencia muestreo</label>
                                            <select class="form-control" placeholder="Frecuencia" id="frecuencia"
                                                onchange="getFrecuenciaMuestreo()">
                                                <option value="0">Sin seleccionar</option>
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
                                            <input type="number" class="form-control" placeholder="# de tomas"
                                                id="tomas" value="{{ @$model->Tomas }}" disabled>
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
                                        <div class="col-md-2">
                                            <label for="tipoMuestra">Tipo de muestra</label>
                                            <select name="tipoMuestra" id="tipoMuestra" class="form-control">
                                                @foreach($tipoMuestraCot as $item)
                                                @if (@$model->Id_tipoMuestra == $item->Id_muestraCot)
                                                <option value="{{$item->Id_muestraCot}}" selected>{{$item->Tipo}}
                                                </option>
                                                @else
                                                <option value="{{$item->Id_muestraCot}}">{{$item->Tipo}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="promedio">Promedio</label>
                                            <select name="promedio" class="form-control" id="promedio">
                                                @foreach($promedioCot as $item)
                                                @if (@$model->Id_promedio == $item->Id_promedioCot)
                                                <option value="{{$item->Id_promedioCot}}" selected>{{$item->Promedio}}
                                                </option>
                                                @else
                                                <option value="{{$item->Id_promedioCot}}">{{$item->Promedio}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="tipoReporte">Tipo de reporte 1996</label>
                                            <select name="tipoReporte" id="tipoReporte" class="form-control">
                                                <option value="0">Sin seleccionar</option>
                                                @foreach (@$categorias001 as $item) 
                                                @if ($item->Id_categoria == @$model->Tipo_reporte)
                                                <option value="{{$item->Id_categoria}}" selected>{{$item->Categoria}}</option>
                                                @else
                                                <option value="{{$item->Id_categoria}}">{{$item->Categoria}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="tipoReporte2">Tipo de reporte 2021</label>
                                            <select name="tipoReporte2" id="tipoReporte2" class="form-control">
                                                <option value="0">Sin seleccionar</option>
                                                @foreach (@$categorias0012 as $item)
                                                @if ($item->Id_categoria == @$model->Tipo_reporte)
                                                <option value="{{$item->Id_categoria}}" selected>{{$item->Categoria}}</option>
                                                @else
                                                <option value="{{$item->Id_categoria}}">{{$item->Categoria}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <label for="puntoMuestro">Punto de muestreo</label>

                                            @if ($show == true)
                                            <button id="addRow" type="button" class="btn bg-success"><i
                                                class="voyager-list-add"></i> Agregar</button>
                                        <button id="delRow" type="button" class="btn bg-danger"><i
                                                class="voyager-trash"></i> Eliminar</button>
                                        @endif
                                    <table id="puntoMuestro" class="display" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th style="width: 90%">Descripción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $contPunto = 1;
                                            @endphp
                                            @if (@$model->Id_cotizacion != NULL)
                                            @foreach ($cotizacionPuntos as $item)
                                            <tr>
                                                <td>{{ $contPunto++; }}</td>
                                                <td><input class="form-control" value="{{ $item->Descripcion }}"></td>
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>

                                    </table>
                                </div>
                                <div class="col-md-4">
                                    @if ($show == true)
                                        {{-- <button class="btn btn-success btn-sm" id="btnGuardarCot" style=""><i class="fas fa-save"></i> Guardar</button> --}}
                                    @endif
                                    
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                {{-- Fin datos --}}
                {{-- Inicio parametros --}}
                <div class="tab-pane fade" id="parametro" role="tabpanel" aria-labelledby="parametro-tab">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-8">
                                    <label for="normaPa">Norma: </label>
                                    <input type="text" placeholder="Sin seleccionar norma" id="normaPa"
                                        style="width: 60%;border: none;" disabled>
                                </div>
                                <div class="col-md-4">
                                    
                            @if ($show == true)
                                <button type="button" id="btnSetParametro" onclick="getParametrosSelected()" class="btn btn-success" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-save"></i> Agragar y/o Eliminar</button>
                            @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-8">
                                    <table class="table" id="tableParametros">
                                        <thead>
                                            <th></th>
                                            <th>#</th>
                                            <th>Id</th>
                                            <th>Parametro</th>
                                        </thead>
                                        <tbody id="tabParametros">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" style="width: 55%">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Parametros</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <div  id="divTrans">

                        </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" id="btnEditarParametros" onclick="updateParametroCot()" class="btn btn-success">Guardar</button>
                    </div>
                </div>
                </div>
            </div>
            <style>
                .transfer-demo {
                    width: 100%;
                    height: 400px;
                    margin: 0 auto;
                }
            </style>
                {{-- Fin parametros --}}
                {{-- Inicio datos Cotizacion --}}
                <div class="tab-pane fade" id="cotizacion" role="tabpanel" aria-labelledby="cotizacion-tab">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <h6>Datos cliente</h6>
                                    <hr>
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="textInter">Intermediario</label>
                                                <input type="text" class="form-control" id="textInter" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="textEstado">Estado Cotización</label>
                                                <input type="text" class="form-control" id="textEstado" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="textServicio">Servicio</label>
                                                <input type="text" class="form-control" id="textServicio" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="textDescarga">Tipo descarga</label>
                                                <input type="text" class="form-control" id="textDescarga" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <h6>Cliente</h6>
                                    <hr>
                                </div>

                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="textCliente">Nombre cliente</label>
                                                <input type="text" class="form-control" id="textCliente" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="textAtencion">Con atención a</label>
                                                <input type="text" class="form-control" id="textAtencion" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="textDireccion">Dirección cotización</label>
                                                <textarea name="textDireccion" class="form-control" id="textDireccion"
                                                    disabled></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="textTelefono">Teléfono</label>
                                                        <input type="text" class="form-control" id="textTelefono"
                                                            disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="textEmail">Correo</label>
                                                        <input type="text" class="form-control" id="textEmail" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <h6>Datos cotización</h6>
                                    <hr>
                                </div>
                                <div class="col-md-12">
                                    <label for="">Norma:</label>
                                    <input type="text" class="form-control" disabled id="textNorma">
                                </div>
                                <div class="col-md-12" id="divMuestreo3">
                                    <label for="">Muestreo:</label>
                                    <input type="text" class="form-control" disabled id="textMuestreo">
                                    <label for="">Numero de Tomas:</label>
                                    <input type="text" class="form-control" disabled id="textTomas">
                                    <label for="">Fecha de Muestreo:</label>
                                    <input type="date" class="form-control" disabled id="fechaMuestreo">
                                </div>
                                                  <!-- Tomas de Muestra-->
                            <div class="col-md-12" id="divMuestreo">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h6>Datos muestreo<code>Si algún dato de esta sección no es utilizable marcar la
                                                casilla en 0</code></h6>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label for=""># tomas Muestreo:</label>
                                        <input type="number" class="form-control" id="tomasMuestreo" disabled>
                                        <small id="emailHelp" class="form-text text-muted">Sección viaticos</small>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">Dias hospedaje:</label>
                                        <input type="number" class="form-control" name="diasHospedaje" id="diasHospedaje"
                                            value="{{ @$muestreo->Dias_hospedaje }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">Hospedaje $:</label>
                                        <input type="number" class="form-control" name="hospedaje" id="hospedaje"
                                            value="{{ @$muestreo->Hospedaje }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="">Dias muestreo:</label>
                                        <input type="number" class="form-control" name="diasMuestreo" id="diasMuestreo"
                                            value="{{ @$muestreo->Dias_muestreo }}">
                                    </div>
                           
                                    <div class="col-md-2">
                                        <label for="">Caseta $:</label>
                                        <input type="number" class="form-control" name="caseta" id="caseta"
                                            value="{{ @$muestreo->Caseta }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="">Kilometros:</label>
                                        <input type="number" class="form-control" name="km" id="km"
                                            onkeyup="cantGasolinaTeorico();" value="{{ @$muestreo->Km }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="">Kilometros Extra:</label>
                                        <input type="number" class="form-control" name="kmExtra" id="kmExtra"
                                            onkeyup="cantGasolinaTeorico();" value="{{ @$muestreo->Km_extra }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="">Gasolina Teorico:</label>
                                        <input type="text" class="form-control" name="gasolinaTeorico"
                                            id="gasolinaTeorico" value="{{ @$muestreo->Gasolina_teorico }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="">Cantidad gasolina:</label>
                                        <input type="number" class="form-control" name="cantidadGasolina"
                                            id="cantidadGasolina" value="{{ @$muestreo->Cantidad_gasolina }}">
                                    </div>
                                    <div class="col-md-12">
                                        <small id="emailHelp" class="form-text text-muted">Gasto Extra</small>
                                    </div>
                                    <div class="col-md-3"> 
                                        <label for=""># de Servicios:</label>
                                        <input type="number" class="form-control" name="numeroServicio"
                                            id="numeroServicio" value="{{ @$model->Num_servicios }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for=""># de muestreadores:</label>
                                        <input type="number" class="form-control" name="numMuestreador"
                                            id="numMuestreador" value="{{ @$muestreo->Num_muestreador }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">Estado:</label>
                                        <select class="form-control" placeholder="Estado" id="estado" name="estado" onchange="getLocalidad()">
                                            @foreach ($estados as $item)
                                                @if (@$muestreo->Estado == $item->Id_estado)
                                                    <option value="{{ $item->Id_estado }}" selected>{{ $item->Nombre }}
                                                    </option>
                                                @else
                                                    <option value="{{ $item->Id_estado }}">{{ $item->Nombre }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- Tomas de Muestra-->
                                    <div class="col-md-3">
                                        <label for="">Localidad:</label>
                                        <select class="form-control select2" placeholder="Localidad" id="localidad"
                                            name="localidad">

                                        </select>
                                    </div>
                                    <div class="col-md-3">

                                            @if ($show == true)
                                            <button class="btn btn-sm btn-success" id="btnCalcularMuestreo" type="button"
                                            onclick="setPrecioMuestreo()">Calcular</button>
                                        @endif
                                    </div>
                                    <div class="col-md-12">
                                        <label for="">$ Total muestreo:</label>
                                        <input type="text" class="form-control" id="totalMuestreo" value="{{ @$muestreo->Total }}" disabled>
                                    </div>
                                </div>
                                </div>
                            <!-- Observación Interna -->
                            <div class="col-md-12 mt-1">
                                <label for="">Observación interna:</label>
                                <textarea cols="30" rows="2" class="form-control" name="observacionInterna"
                                    id="observacionInterna">{{ @$model->Observacion_interna }}</textarea>
                            </div>
                            <!-- Observación cotización  -->
                            <div class="col-md-12 mt-1">
                                <label for="">Observación cotización:</label>
                                <input type="text" id="obsCot" value="{{ @$model->Observacion_cotizacion }}" hidden>
                                <div id="divSummer"></div>
                                {{-- <textarea cols="30" rows="2" class="form-control" name="observacionCotizacion"
                                    id="observacionCotizacion">{{ @$model->Observacion_cotizacion }}</textarea> --}}
                            </div>
                            <!-- Forma de pago-->
                            <div class="col-md-6">
                                <label for="">Forma de Pago</label>
                                <select name="metodoPago" class="form-control" id="metodoPago">
                                    @foreach ($metodoPago as $item)
                                        @if (@$model->Metodo_pago == $item->Id_metodo)
                                            <option value="{{ $item->Id_metodo }}" selected>{{ $item->Metodo }}</option>
                                        @else
                                            <option value="{{ $item->Id_metodo }}">{{ $item->Metodo }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <!-- Tiempo de Entrega -->
                            <div class="col-md-6">
                                <label for="">Tiempo de Entrega (Dias)</label>
                                <input type="number" class="form-control" name="tiempoEntrega" id="tiempoEntrega" value="10"
                                    value="{{ @$model->Tiempo_entrega }}">
                            </div>

                            <div class="col-md-12" id="divMuestreo2">
                                <h6>Puntos de Muestreo</h6>
                                <div id="puntoMuestreo3">

                                </div>
                            </div>
                            <div class="col-md-12">
                                <h6>Parametros</h6>
                                <div id="parametros3">

                                </div>
                            </div>
                            <div class="col-md-12">
                                <h6>Folio cotizacion</h6>
                                <input type="date" id="fechaCot" value="{{@$model->Fecha_cotizacion}}">
                                <input type="text" id="folio" value="{{@$model->Folio}}" disabled>
                                <button class="btn btn-info" id="btnFolio"><i class="voyager-forward"> Crear Folio</i></button>
                            </div>

                            <div class="col-md-12">
                                @if ($show == true)
                                <div class="col-md-3">
                                    <button class="btn btn-success" onclick="btnDescuento()" type="button"><i class="voyager-tag"></i> Descuento</button>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-success" onclick="btnReccalcular()" type="button"><i class="fas fa-calculator"></i> Re-Calcular</button>
                                </div>
                            @endif


                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="width: 40%">Servicio</th>
                                            <th scope="col">Sub total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Analisis  <input type="text" id="contSer" style="border: none" disabled></td>
                                            <td><input type="number" class="form-control" id="precioAnalisis"
                                                    name="precioAnalisis" placeholder="Precio análsis"
                                                    value="{{ @$model->Precio_analisis }}"></td>
                                        </tr>
                                        <tr>
                                            <td>Parametro Extra <input type="text" id="extra" style="border: none" disabled></td>
                                            <td><input type="number" class="form-control" id="precioCat"
                                                    name="precioCat" placeholder="Parametro Extra"
                                                    value="{{ @$model->Precio_catalogo }}"></td>
                                        </tr>
                                        <tr>
                                            <td>Gasto Adicional $:</td>
                                            <td><input type="number" class="form-control" name="gastosExtras" id="gastosExtras"
                                                value="{{ @$model->Extras }}" placeholder="Precio Adicional"></td>
                                        </tr>
                                        <tr>
                                            <td>Gasto de paqueteria $: </td>
                                            <td>    <input type="number" class="form-control" name="paqueteria" id="paqueteria"
                                                value="{{ @$model->Paqueteria }}" placeholder="Precio de paqueteria"></td> 
                                        </tr>
                                        <tr>
                                            <td># de servicio</td>
                                            <td>
                                                <input type="number" class="form-control" 
                                                    id="numServicio" value="{{ @$model->Num_servicios }}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><code>Nota: El descuento solo aplica directamente al análisis</code></td>
                                            <td></td>
                                        </tr>
                                        <tr class="activarDescuento" hidden>
                                            <td>Descuento</td>
                                            <td>
                                                <input type="text" class="form-control" id="descuento" name="descuento"
                                                    placeholder="Descuento" value="{{ @$model->Descuento }}">
                                                <button type="button" class="btn btn-info"
                                                    onclick="aplicarDescuento()">Aplicar</button>
                                            </td>                                            
                                        </tr>
                                        <tr class="activarDescuento" hidden>
                                            <td>Precio Analisis Con descuento</td>
                                            <td>
                                                <input type="text" class="form-control" id="precioAnalisisCon" 
                                                    placeholder="PrecioAnalisis con descuento" value="{{ @$model->Descuento }}">
                                            </td>                                            
                                        </tr>
                                        <tr id="divMuestreo4">
                                            <td>Muestreo</td>
                                            <td><input type="text" class="form-control" id="precioMuestra"
                                                    name="precioMuestra" placeholder="Precio muestreo"
                                                    value="{{ @$model->Precio_muestreo }}"></td>
                                        </tr>
                                        <tr>
                                            <td>Iva</td>
                                            <td><input type="text" class="form-control" id="iva" name="iva"
                                                    placeholder="Iva" disabled value="16"></td>
                                        </tr>
                                        <tr>
                                            <td>SubTotal</td>
                                            <td><input type="text" class="form-control" id="subTotal" name="subTotal"
                                                    placeholder="Sub total" value="{{ @$model->Sub_total }}"></td>
                                        </tr>
                                        <tr>
                                            <td>Total</td>
                                            <td><input type="text" class="form-control" id="precioTotal"
                                                    name="precioTotal" placeholder="Precio total"
                                                    value="{{ @$model->Costo_total }}"></td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>

                            @if ($show == true)
                                <button type="button" id="btnSetCotizacion" onclick="setPrecioCotizacion()" class="btn btn-primary" hidden>Guardar</button>
                            @endif

                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Fin datos Cotizacion --}}
            </div>
            <div class="col-md.12">
                <div  style="position: fixed;left: 90%;top:80%"> <button style="border: none" class="btn btn-success" id="btnGuardarCotizacion"><i class="voyager-paper-plane"></i> Guardar</button></div><br>
              </div>
        </div>
    </div>

    <div id="divModal">
    </div>

    @endsection
    @section('javascript')
 
    <script src="{{ asset('/public/js/cotizacion/create.js') }}?v=1.0.7"></script>
    <script src="{{ asset('/public/js/libs/duallist/jquery.transfer.js') }}"></script>
<!-- include summernote css/js -->
<script src="{{asset('/assets/summer/summernote.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    @stop