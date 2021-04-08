@extends('voyager::master')

@section('content')
    <!-- Cambio 0-->
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
                <form action="{{ url('admin/cotizacion/setCotizacion') }}" method="post">
                    @csrf
                    {{-- Contenido de nav --}}
                    <div class="tab-content" id="myTabContent">
                        {{-- Inicio Datos --}}
                        <div class="tab-pane fade" id="datos" role="tabpanel" aria-labelledby="datos-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <h6>Intermediario</h6>
                                    <hr>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="intermediario">Intermediario</label>
                                        <select name="intermediario" id="intermediario" class="form-control select2">
                                            {{-- @foreach ($intermediarios as $item)
                        <option value="{{$item->Id_cliente}}">{{$item->Nombres}} {{$item->A_paterno}}</option>
                    @endforeach --}}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <h6>Cliente</h6>
                                    <hr>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="clientes">Clientes registrados</label>
                                        <select name="clientes" id="clientes" class="form-control">
                                            {{-- <option value="0">Sin seleccionar</option> --}}
                                            {{-- @foreach ($generales as $item)
                        <option value="{{$item->Id_cliente}}">{{$item->Empresa}}</option>
                    @endforeach --}}
                                        </select>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombreCliente">Nombre del cliente</label>
                                        <input type="text" class="form-control" placeholder="Nombre del cliente"
                                            id="nombreCliente" name="nombreCliente" value="{{ $getCotizacion->Nombre }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="direccion">Dirección</label>
                                        <input type="text" class="form-control" placeholder="Dirección" id="direccion"
                                            name="direccion" value="{{ $getCotizacion->Direccion }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="atencion">Con atención a</label>
                                        <input type="text" class="form-control" placeholder="Con atención a" id="atencion"
                                            name="atencion" value="{{ $getCotizacion->Atencion }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="telefono">Teléfono</label>
                                        <input type="number" class="form-control" placeholder="Teléfono" id="telefono"
                                            name="telefono" value="{{ $getCotizacion->Telefono }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="correo">Correo</label>
                                        <input type="email" class="form-control" placeholder="Correo electronico"
                                            id="correo" name="correo" value="{{ $getCotizacion->Correo }}">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <h6>Datos generales</h6>
                                    <hr>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tipoServicio">Tipo de servicio</label>
                                        <select name="tipoServicio" id="tipoServicio" class="form-control">
                                            @foreach ($tipoServicio as $item)
                                                @if ($item->Id_tipo == $getCotizacion->Tipo_servicio)
                                                    <option selected='selected' value="{{ $item->Id_tipo }}">
                                                        {{ $item->Servicio }}</option>
                                                @else
                                                    <option value="{{ $item->Id_tipo }}">{{ $item->Servicio }}</option>
                                                @endif
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tipoDescarga">Tipo descarga</label>
                                        <select name="tipoDescarga" id="tipoDescarga" class="form-control">
                                            @foreach ($descargas as $itemDescarga)
                                                @if ($itemDescarga->Id_tipo == $getCotizacion->Tipo_descarga)
                                                    <option selected='selected' value="{{ $itemDescarga->Id_tipo }}">
                                                        {{ $itemDescarga->Descarga }}</option>
                                                @else
                                                    <option value="{{ $itemDescarga->Id_tipo }}">
                                                        {{ $itemDescarga->Descarga }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="norma">Clasificaciòn de la norma</label>
                                        <select name="norma" id="norma" class="form-control">

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="subnorma">Norma</label>
                                        <select name="subnorma" id="subnorma" class="form-control">
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fecha">Fecha de muestreo</label>
                                        <input type="date" class="form-control" placeholder="Fecha" id="fecha" name="fecha"
                                            value="{{ $getCotizacion->Fecha_cotizacion }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="frecuencia">Frecuencia muestreo</label>
                                        <select class="form-control" placeholder="Frecuencia" id="frecuencia"
                                            name="frecuencia" value="{{ $getCotizacion->Frecuencia_muestreo }}">
                                            {{-- @foreach ($frecuencia as $item)
                        <option value="{{$item->Id_frecuencia}}">{{$item->Descripcion}}</option>
                    @endforeach --}}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="tomas"># de tomas</label>
                                        <input type="number" class="form-control" placeholder="# de tomas" id="tomas"
                                            name="tomas" value="{{ $getCotizacion->Numero_puntos }}">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <h6>Espesificaciones</h6>
                                    <hr>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tipoMuestra">Tipo de muestra</label>
                                        <select name="tipoMuestra" id="tipoMuestra" class="form-control">
                                            @if ($getCotizacion->Tipo_muestra == 'INSTANTANEA')
                                                <option>Sin seleccionar</option>
                                                <option selected>INSTANTANEA</option>
                                                <option>COMPUESTA</option>
                                            @endif()
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="promedio">Promedio</label>
                                        <select name="promedio" class="form-control" id="promedio">
                                            <option value="MUESTREO INSTANTANEO">MUESTREO INSTANTANEO</option>
                                            <option value="MENSUAL">MENSUAL</option>
                                            <option value="DIARIO">DIARIO</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="tipoReporte">Tipo de reporte</label>
                                        <select name="tipoReporte" id="tipoReporte" class="form-control">
                                            <option value="0">Sin seleccionar</option>

                                        </select>
                                    </div>
                                </div>

                                {{-- <div class="col-md-12">
                <div class="form-group">
                  <label for="condicionVenta">Condiciones de venta</label>
                  <textarea name="condicionVenta" class="form-control" id="condicionVenta" placeholder="Condiciones de venta"></textarea>
                </div>
              </div> --}}

                                {{-- <div class="col-md-12">
                                    <label for="puntoMuestro">Punto de muestreo</label>
                                    <button id="addRow" type="button" class="btn btn-sm btn-success"><i
                                            class="voyager-list-add"></i> Agregar</button>
                                    <table id="puntoMuestro" class="display" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th style="width: 90%">Descripción</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div> --}}

                            </div>
                        </div>
                        {{-- Fin datos --}}
                        {{-- Inicio parametros --}}
                        <div class="tab-pane fade" id="parametro" role="tabpanel" aria-labelledby="parametro-tab">

                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="normaPa">Norma</label>
                                        <input type="text" class="form-control" placeholder="normaPa" id="normaPa"
                                            name="normaPa" disabled>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    {{-- @livewire('analisis-q.norma-parametros', ['idUser' => Auth::user()->id,'idSub' => @$idSub]) --}}
                                    <div id="tabParametros">

                                    </div>
                                </div>
                            </div>

                        </div>
                        {{-- Fin parametros --}}
                        {{-- Inicio datos Cotizacion --}}
                        <div class="tab-pane fade" id="cotizacion" role="tabpanel" aria-labelledby="cotizacion-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <h6>Datos intermediario</h6>
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
                                                <label for="textAtencion">Con atenció a</label>
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
                                                        <input type="text" class="form-control" id="textTelefono" disabled>
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
                                <div class="col-md-12">
                                    <label for="">Muestreo:</label>
                                    <input type="text" class="form-control" disabled id="textMuestreo"
                                        value="{{ $getCotizacion->Numero_puntos }}">
                                    <label for="">Numero de Tomas:</label>
                                    <input type="text" class="form-control" id="TextTomas" disabled>
                                    <label for="">Fecha de Muestreo:</label>
                                    <input type="date" class="form-control" disabled id="fechaMuestreo"
                                        value="{{ $getCotizacion->Numero_puntos }}">
                                </div>
                                <div class="col-md-12">
                                    <hr>
                                </div>
                                <!-- Tomas de Muestra-->
                                <div class="col-md-3">
                                    <label for=""># tomas Muestreo:</label>
                                    <input type="text" class="form-control" id="tomasMuestreo" disabled
                                        value="{{ $getCotizacion->Numero_puntos }}">
                                </div>
                                <!-- Viaticos -->
                                <div class="col-md-3">
                                    <label for="">Viaticos:</label>
                                    <input type="text" class="form-control" id="viaticos"
                                        value="{{ $getCotizacion->Numero_puntos }}">
                                </div>
                                <!-- Gastos de Paqueteria -->
                                <div class="col-md-3">
                                    <label for="">Gastos Paqueteria:</label>
                                    <input type="text" class="form-control" id="paqueteria"
                                        value="{{ $getCotizacion->Numero_puntos }}">
                                </div>
                                <!-- Gasto Adicional -->
                                <div class="col-md-3">
                                    <label for="">Gasto Adicional:</label>
                                    <input type="text" class="form-control" id="gastosExtras"
                                        value="{{ $getCotizacion->Numero_puntos }}">
                                </div>
                                <!-- Numero de Servicio-->
                                <div class="col-md-3">
                                    <label for="">N Servicio:</label>
                                    <input type="text" class="form-control" id="numeroServicio"
                                        value="{{ $getCotizacion->Numero_puntos }}">
                                </div>
                                <!-- Km Extra-->
                                <div class="col-md-3">
                                    <label for="">Km Extra:</label>
                                    <input type="text" class="form-control" id="kmExtra"
                                        value="{{ $getCotizacion->Numero_puntos }}">
                                </div>
                                <!-- Precio Km-->
                                <div class="col-md-3">
                                    <label for="">Precio Km:</label>
                                    <input type="text" class="form-control" id="precioKm"
                                        value="{{ $getCotizacion->Numero_puntos }}">
                                </div>
                                <!-- Tomas de Muestra-->
                                <div class="col-md-3">
                                    <label for="">Precio Km Extra:</label>
                                    <input type="text" class="form-control" id="precioKmExtra"
                                        value="{{ $getCotizacion->Numero_puntos }}">
                                </div>
                                <!-- Observación Interna -->
                                <div class="col-md-12 mt-1">
                                    <label for="">Observación interna:</label>
                                    <textarea cols="30" rows="2" class="form-control" id="observacionInterna"
                                        value="{{ $getCotizacion->Numero_puntos }}">
                    </textarea>
                                </div>
                                <!-- Observación cotización  -->
                                <div class="col-md-12 mt-1">
                                    <label for="">Observación cotización:</label>
                                    <textarea cols="30" rows="2" class="form-control" id="observacionCotizacion"
                                        value="{{ $getCotizacion->Numero_puntos }}">
                    </textarea>
                                </div>
                                <!-- Forma de pago-->
                                <div class="col-md-6">
                                    <label for="">Forma de Pago</label>
                                    <select name="" class="form-control" id="tarjeta">
                                        @foreach ($metodoPago as $item)
                                            <option value="{{ $item->Id_metodo }}">{{ $item->Metodo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Tiempo de Entrega -->
                                <div class="col-md-6">
                                    <label for="">Tiempo de Entrega</label>
                                    <input type="text" class="form-control" id="tiempoEntrega">
                                </div>
                                <div class="col-md-12">
                                    <hr>
                                </div>
                                <div class="col-md-12">
                                    <h6>Puntos de Muestreo</h6>
                                    <div id="inputsPuntoMuestrodiv">

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="precio">Costo cotizacion</label>
                                        <input type="text" class="form-control" id="precio" disabled>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Crear cotización</button>
                            </div>
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


    <script src="{{ asset('js/cotizacion/create.js') }}"></script>
    <script src="{{ asset('js/libs/componentes.js') }}"></script>
    <script src="{{ asset('js/libs/tablas.js') }}"></script>
@stop


{{-- //Guardar Duplicado
// $this->saveCotizacionCopia();
# code...
//    $idCotizacion = $request->id;
// $cotizacionCopia = DB::table('cotizacion')
//     ->where('Id_cotizacion', $idCotizacion)
//     ->first();
// Request $request
// $user = Auth::user();
// $user = $user->name;
// $now = Carbon::now();
// $now->year;
// $now->month;
// $cotizacion = Cotizaciones::withTrashed()->get();
// $num = count($cotizacion);
// $num++;
// $newCotizacionCopia =  [
//     'Cliente' =>  $cotizacionCopia->clienteManual,
//     'Folio_servicio' => '21-92/' . $num.'-2',
//     'Cotizacion_folio' => '21-92/' . $num.'-2',
//     'Empresa' => $cotizacionCopia->atencionA,
//     'Servicio' => $cotizacionCopia->tipoServicio,
//     'Fecha_cotizacion' => $cotizacionCopia->fechaCotizacion,
//     'Supervicion' => 'por Asignar',
//     'deleted_at' => NULL,
//     'created_by' =>  'David Barrita',
//     'Telefono' => $cotizacionCopia->telefono,
//     'Correo' => $cotizacionCopia->correo,
//     'Tipo_descarga' => $cotizacionCopia->tipoDescarga,
//     'Tipo_servicio' => $cotizacionCopia->tipoServicio,
//     'Estado_cotizacion' => $cotizacionCopia->estadoCotizacion,
//     'Puntos_muestreo' => $cotizacionCopia->puntosMuestreo,
//     'Promedio' =>  $cotizacionCopia->promedio,
//     'Tipo_muestra' => $cotizacionCopia->tipoMuestra,
//     'frecuencia' => $cotizacionCopia->frecuencia,
//     'Norma_formulario_uno' => $cotizacionCopia->normaFormularioUno,
//     'clasificacion_norma' => $cotizacionCopia->clasifacionNorma,
//     'Reporte' => $cotizacionCopia->reporte,
//     'condicciones_venta' =>  $cotizacionCopia->codiccionesVenta,
//     'Viaticos' =>  $cotizacionCopia->viaticos,
//     'Paqueteria' => $cotizacionCopia->paqueteria,
//     'Gastos_extras' => $cotizacionCopia->gastosExtras,
//     'Numero_servicio' => $cotizacionCopia->numeroServicio,
//     'Km_extra' => $cotizacionCopia->kmExtra,
//     'observacionInterna' => $cotizacionCopia->observacionInterna,
//     'observacionCotizacion' => $cotizacionCopia->observacionCotizacion,
//     'tarjeta' => $cotizacionCopia->tarjeta,
//     'tiempoEntrega' => NULL,
//     'precioKmExtra' => $cotizacionCopia->precioKmExtra
// ];

// $newCotizacionHistorico = CotizacionHistorico::create([
//     'Cliente' => $cotizacionCopia->clienteManual,
//     'Id_busquedad' => $newCotizacionCopia->Id_cotizacion,
//     'Folio_servicio' => '21-92/' . $num,
//     'Cotizacion_folio' => '21-92/' . $num,
//     'Empresa' => $cotizacionCopia->atencionA,
//     'Servicio' => $cotizacionCopia->tipoServicio,
//     'Fecha_cotizacion' => $cotizacionCopia->fechaCotizacion,
//     'Supervicion' => 'por Asignar',
//     'deleted_at' => NULL,
//     'created_by' =>  'David Barrita',
//     'Telefono' => $cotizacionCopia->telefono,
//     'Correo' => $cotizacionCopia->correo,
//     'Tipo_descarga' => $cotizacionCopia->tipoDescarga,
//     'Tipo_servicio' => $cotizacionCopia->tipoServicio,
//     'Estado_cotizacion' => $cotizacionCopia->estadoCotizacion,
//     'Puntos_muestreo' => $cotizacionCopia->puntosMuestreo,
//     'Promedio' =>  $cotizacionCopia->promedio,
//     'Tipo_muestra' => $cotizacionCopia->tipoMuestra,
//     'frecuencia' => $cotizacionCopia->frecuencia,
//     'Norma_formulario_uno' => $cotizacionCopia->normaFormularioUno,
//     'clasificacion_norma' => $cotizacionCopia->clasifacionNorma,
//     'Reporte' => $cotizacionCopia->reporte,
//     'condicciones_venta' =>  $cotizacionCopia->codiccionesVenta,
//     'Viaticos' =>  $cotizacionCopia->viaticos,
//     'Paqueteria' => $cotizacionCopia->paqueteria,
//     'Gastos_extras' => $cotizacionCopia->gastosExtras,
//     'Numero_servicio' => $cotizacionCopia->numeroServicio,
//     'Km_extra' => $cotizacionCopia->kmExtra,
//     'observacionInterna' => $cotizacionCopia->observacionInterna,
//     'observacionCotizacion' => $cotizacionCopia->observacionCotizacion,
//     'tarjeta' => $cotizacionCopia->tarjeta,
//     'tiempoEntrega' => NULL,
//     'precioKmExtra' => $cotizacionCopia->precioKmExtra,
//     'fecha' => date("Y/m/d"),
//     'hora' => date("h:i:sa"),
//     'autor' =>  $user
// ]);

//Retornar la Respuesta --}}
