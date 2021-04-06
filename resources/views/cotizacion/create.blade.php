@extends('voyager::master')

@section('content')

<div class="container-fluid"> 
  <div class="row">
      <div class="col-md-12">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item" role="presentation">
            <a class="nav-link active" id="datos-tab" data-toggle="tab" href="#datos" role="tab" aria-controls="datos" aria-selected="true">1. Datos</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" id="parametro-tab" data-toggle="tab" href="#parametro" role="tab" aria-controls="parametro" aria-selected="false">2. Parametros</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" id="cotizacion-tab" data-toggle="tab" href="#cotizacion" role="tab" aria-controls="cotizacion" aria-selected="false">3. Cotización</a>
          </li>
        </ul>
        <form action="{{url('admin/cotizacion/setCotizacion')}}" method="post">
          @csrf 
        {{-- Contenido de nav --}}
        <div class="tab-content" id="myTabContent">
          {{-- Inicio Datos --}}
          <div class="tab-pane fade" id="datos" role="tabpanel" aria-labelledby="datos-tab">
            <div class="row">

              <div class="col-md-12">
                <div class="form-group">
                  <label for="intermediario">Intermediario</label>
                  <select name="intermediario" id="intermediario" class="form-control select2">
                    @foreach ($intermediarios as $item)
                        <option value="{{$item->Id_cliente}}">{{$item->Nombres}} {{$item->A_paterno}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-12"> 
                <div class="form-group">
                  <label for="clientes">Clientes registrados</label>
                  <select name="clientes" id="clientes" class="form-control">
                    <option value="0">Sin seleccionar</option>
                    @foreach ($generales as $item)
                        <option value="{{$item->Id_cliente}}">{{$item->Empresa}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="dropdown-divider"></div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="nombreCliente">Nombre del cliente</label>
                  <input type="text" class="form-control" placeholder="Nombre del cliente" id="nombreCliente" name="nombreCliente">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="direccion">Dirección</label>
                  <input type="text" class="form-control" placeholder="Dirección" id="direccion" name="direccion">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="atencion">Con atención a</label>
                  <input type="text" class="form-control" placeholder="Con atención a" id="atencion" name="atencion">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="telefono">Teléfono</label>
                  <input type="number" class="form-control" placeholder="Teléfono" id="telefono" name="telefono">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="correo">Correo</label>
                  <input type="email" class="form-control" placeholder="Correo electronico" id="correo" name="correo">
                </div>
              </div>

              <div class="dropdown-divider"></div>

              <div class="col-md-6">
                <div class="form-group">
                  <label for="tipoServicio">Tipo de servicio</label>
                  <select name="tipoServicio" id="tipoServicio" class="form-control">
                    @foreach ($servicios as $item)
                    <option value="{{$item->Id_tipo}}">{{$item->Servicio}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="tipoDescarga">Tipo descarga</label>
                  <select name="tipoDescarga" id="tipoDescarga" class="form-control">
                   @foreach ($descargas as $item)
                       <option value="{{$item->Id_tipo}}">{{$item->Descarga}}</option>
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
                  <label for="fecha">Fecha</label>
                  <input type="date" class="form-control" placeholder="Fecha" id="fecha" name="fecha">
                </div>
              </div> 
              <div class="col-md-6">
                <div class="form-group">
                  <label for="frecuencia">Frecuencia muestreo</label>
                  <input type="number" class="form-control" placeholder="Frecuencia" id="frecuencia" name="frecuencia">
                </div>
              </div>
 
              <div class="col-md-4">
                <div class="form-group">
                  <label for="tipoMuestra">Tipo de muestra</label>
                  <select name="tipoMuestra" id="tipoMuestra" class="form-control">
                    <option>Sin seleccionar</option>
                    <option>INSTANTANEA</option>
                    <option>COMPUESTA</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="promedio">Promedio</label>
                  <select name="promedio"  class="form-control" id="promedio">
                    <option value="MUESTREO INSTANTANEO">MUESTREO INSTANTANEO</option>
                    <option value="MENSUAL">MENSUAL</option>
                    <option value="DIARIO">DIARIO</option>
                </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="puntoMuestro"># de puntos de muestreo</label>
                  <input type="number" class="form-control" placeholder="# puntos de muestreo" id="puntoMuestro" name="puntoMuestro">
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

              <div class="col-md-12">
                <div class="form-group">
                  <label for="condicionVenta">Condiciones de venta</label>
                  <textarea name="condicionVenta" class="form-control" id="condicionVenta" placeholder="Condiciones de venta"></textarea>
                </div>
              </div>

            </div> 
          </div>
          {{-- Fin datos --}}
          {{-- Inicio parametros --}}
          <div class="tab-pane fade" id="parametro" role="tabpanel" aria-labelledby="parametro-tab">

            <div class="row">
 
              <div class="col-md-12">
                <div class="form-group">
                  <label for="normaPa">Norma</label>
                  <input type="text" class="form-control" placeholder="normaPa" id="normaPa" name="normaPa" disabled>
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
                      <textarea name="textDireccion" class="form-control" id="textDireccion" disabled></textarea>
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
                <hr>
                <h6> <b> Datos de Cotización:</b></h6>
            </div>
            <div class="col-md-12">
                <label for="">Norma:</label>
                <input type="text" class="form-control" disabled id="textNorma">
            </div>
            <div class="col-md-12">
                <label for="">Muestreo:</label>
                <input type="text" class="form-control" disabled id="textMuestreo">
                <label for="">Numero de Tomas:</label>
                <input type="text" class="form-control"  id="TextTomas">
                <label for="">Fecha de Muestreo:</label>
                <input type="date" class="form-control" disabled id="fechaCopy">
            </div>
            <div class="col-md-12">
                <hr>
            </div>
            <!-- Tomas de Muestra-->
            <div class="col-md-3">
                <label for=""># tomas Muestreo:</label>
                <input type="text" class="form-control" id="tomasMuestreo">
            </div>
            <!-- Viaticos -->
            <div class="col-md-3">
                <label for="">Viaticos:</label>
                <input type="text" class="form-control" id="viaticos">
            </div>
            <!-- Gastos de Paqueteria -->
            <div class="col-md-3">
                <label for="">Gastos Paqueteria:</label>
                <input type="text" class="form-control" id="paqueteria">
            </div>
            <!-- Gasto Adicional -->
            <div class="col-md-3">
                <label for="">Gasto Adicional:</label>
                <input type="text" class="form-control" id="gastosExtras">
            </div>
            <!-- Numero de Servicio-->
            <div class="col-md-3">
                <label for="">N Servicio:</label>
                <input type="text" class="form-control" id="numeroServicio">
            </div>
            <!-- Km Extra-->
            <div class="col-md-3">
                <label for="">Km Extra:</label>
                <input type="text" class="form-control" id="kmExtra">
            </div>
            <!-- Precio Km-->
            <div class="col-md-3">
                <label for="">Precio Km:</label>
                <input type="text" class="form-control" id="precioKm">
            </div>
            <!-- Tomas de Muestra-->
            <div class="col-md-3">
                <label for="">Precio Km Extra:</label>
                <input type="text" class="form-control" id="precioKmExtra">
            </div>
            <!-- Observación Interna -->
            <div class="col-md-12 mt-1">
                <label for="">Observación interna:</label>
                <textarea cols="30" rows="2" class="form-control" id="observacionInterna">
            </textarea>
            </div>
            <!-- Observación cotización  -->
            <div class="col-md-12 mt-1">
                <label for="">Observación cotización:</label>
                <textarea cols="30" rows="2" class="form-control" id="observacionCotizacion">
            </textarea>
            </div>
            <!-- Forma de pago-->
            <div class="col-md-6">
                <label for="">Forma de Pago</label>
                <select name="" class="form-control" id="tarjeta">
                    <option value="Tarjeta de Credito">Tarjeta de Credito </option>
                    <option value="Deposito">Deposito</option>
                    <option value="Efectivo">Efectivo</option>
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


<script src="{{asset('js/cotizacion/create.js')}}"></script>
<script src="{{asset('js/libs/componentes.js')}}"></script>
<script src="{{asset('js/libs/tablas.js')}}"></script>
@stop
