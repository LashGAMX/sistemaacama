@extends('voyager::master')

@section('content')

<div class="container-fluid">
  <input type="text" value="{{@$sw}}" id="sw" hidden> 
  <input type="text" value="{{@$idCotizacion}}" id="idCotizacion" hidden> 
  
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
            <a class="nav-link" id="cotizacion-tab" data-toggle="tab" href="#cotizacion"  role="tab" aria-controls="cotizacion" aria-selected="false">3. Cotización</a>
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
                <h6>Intermediario</h6>
                <hr>
            </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label for="intermediario">Intermediario</label>
                  <select name="intermediario" id="intermediario" class="form-control select2">
                    @foreach ($intermediarios as $item)
                        @if (@$model->Id_intermedio == $item->Id_cliente)
                          <option value="{{$item->Id_cliente}}" selected>{{$item->Nombres}} {{$item->A_paterno}}</option>
                        @else
                          <option value="{{$item->Id_cliente}}">{{$item->Nombres}} {{$item->A_paterno}}</option>
                        @endif
                    @endforeach
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
                  <select name="clientes" id="clientes" class="form-control select2">
                    <option value="0">Sin seleccionar</option>
                    @foreach ($generales as $item)
                        @if (@$model->Id_cliente == $item->Id_cliente)
                          <option value="{{$item->Id_cliente}}" selected>{{$item->Empresa}}</option>
                        @else
                          <option value="{{$item->Id_cliente}}">{{$item->Empresa}}</option>
                        @endif
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="dropdown-divider"></div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="nombreCliente">Nombre del cliente</label>
                  <input type="text" class="form-control" placeholder="Nombre del cliente" id="nombreCliente" name="nombreCliente" value="{{@$model->Nombre}}">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="direccion">Dirección</label>
                  <input type="text" class="form-control" placeholder="Dirección" id="direccion" name="direccion" value="{{@$model->Direccion}}">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="atencion">Con atención a</label>
                  <input type="text" class="form-control" placeholder="Con atención a" id="atencion" name="atencion" value="{{@$model->Atencion}}">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="telefono">Teléfono</label>
                  <input type="number" class="form-control" placeholder="Teléfono" id="telefono" name="telefono" value="{{@$model->Telefono}}">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="correo">Correo</label>
                  <input type="email" class="form-control" placeholder="Correo electronico" id="correo" name="correo" value="{{@$model->Correo}}"> 
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
                    @foreach ($servicios as $item)
                    
                    @if (@$model->Tipo_servicio == $item)
                    <option value="{{$item->Id_tipo}}" selected>{{$item->Servicio}}</option>    
                    @else
                    <option value="{{$item->Id_tipo}}">{{$item->Servicio}}</option>
                    @endif
                    @endforeach
                  </select>
                </div>
              </div> 
              <div class="col-md-6">
                <div class="form-group">
                  <label for="tipoDescarga">Tipo descarga</label>
                  <select name="tipoDescarga" id="tipoDescarga" class="form-control">
                   @foreach ($descargas as $item)
                       @if (@$model->Tipo_descarga == $item->Id_tipo)
                       <option value="{{$item->Id_tipo}}" selected>{{$item->Descarga}}</option>
                       @else
                       <option value="{{$item->Id_tipo}}">{{$item->Descarga}}</option>
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
                  <input type="date" class="form-control" placeholder="Fecha" id="fecha" name="fecha" value="{{@$model->Fecha_muestreo}}">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="frecuencia">Frecuencia muestreo</label>
                  <select  class="form-control" placeholder="Frecuencia" id="frecuencia" name="frecuencia">
                    @foreach ($frecuencia as $item)
                      @if (@$model->Frecuencia_muestreo == $item->Id_frecuencia)
                      <option value="{{$item->Id_frecuencia}}" selected>{{$item->Descripcion}}</option>
                      @else
                      <option value="{{$item->Id_frecuencia}}">{{$item->Descripcion}}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label for="tomas"># de tomas</label>
                  <input type="number" class="form-control" placeholder="# de tomas" id="tomas" name="tomas" value="{{@$model->Tomas}}">
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
                    <option>Sin seleccionar</option>
                    <option>INSTANTANEA</option>
                    <option>COMPUESTA</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="promedio">Promedio</label>
                  <select name="promedio"  class="form-control" id="promedio">
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

              <div class="col-md-12">
                <label for="puntoMuestro">Punto de muestreo</label>
                <button id="addRow" type="button" class="btn btn-sm btn-success"><i class="voyager-list-add"></i> Agregar</button>
                <button id="delRow" type="button" class="btn btn-sm btn-danger"><i class="voyager-trash"></i> Eliminar</button>
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
                            <td>{{$contPunto}}</td>
                            <td><input class="form-control" id="punto{{$contPunto}}" value="{{$item->Descripcion}}"></td>
                          </tr>  
                          @endforeach
                      @endif
                    </tbody>
                    
                </table>
              </div>
              <input type="text" id="contPunto" value="{{$contPunto}}" hidden>
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

              <div class="col-md-12">
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
                <h6>Datos cotización</h6>
                <hr>
            </div>
            <div class="col-md-12">
                <label for="">Norma:</label>
                <input type="text" class="form-control" disabled id="textNorma">
            </div>
            <div class="col-md-12">
                <label for="">Muestreo:</label>
                <input type="text" class="form-control" disabled id="textMuestreo">
                <label for="">Numero de Tomas:</label>
                <input type="text" class="form-control" disabled id="textTomas" >
                <label for="">Fecha de Muestreo:</label>
                <input type="date" class="form-control" disabled id="fechaMuestreo">
            </div>
           
            <!-- Tomas de Muestra-->
            <div class="col-md-12" id="divMuestreo">
              <div class="row">
                <div class="col-md-12">
                  <h6>Datos muestreo</h6>
                  <hr>
              </div>
                <div class="col-md-3">
                  <label for=""># tomas Muestreo:</label>
                  <input type="number" class="form-control" id="tomasMuestreo" disabled>
              </div>
              <!-- Viaticos -->
              <div class="col-md-3">
                  <label for="">Gasto muestreo:</label>
                  <input type="number" class="form-control" name="viaticos" id="viaticos">
              </div>
              <!-- Gastos de Paqueteria -->
              <div class="col-md-3">
                  <label for="">Gastos Paqueteria:</label>
                  <input type="number" class="form-control" name="paqueteria" id="paqueteria">
              </div>
              <!-- Gasto Adicional -->
              <div class="col-md-3">
                  <label for="">Gasto Adicional:</label>
                  <input type="number" class="form-control" name="gastosExtras" id="gastosExtras">
              </div>
              <!-- Numero de Servicio-->
              <div class="col-md-3">
                  <label for="">N Servicio:</label>
                  <input type="number" class="form-control" name="numeroServicio" id="numeroServicio">
              </div>
              <!-- Km Extra-->
              <div class="col-md-3"> 
                <label for="">Km:</label>
                <input type="number" class="form-control" name="Km" id="Km" onkeydown="cantidadGasolina()">
              </div>
              <div class="col-md-3">
                  <label for="">Km Extra:</label>
                  <input type="number" class="form-control" name="kmExtra" id="kmExtra" onkeydown="cantidadGasolina()">
              </div>
              <div class="col-md-3">
                <label for="">Cantidad Gasolina:</label>
                <input type="number" class="form-control" name="cantdidadGasolina" id="cantdidadGasolina">
            </div>
              <div class="col-md-3">
                <label for="">Hospedaje:</label>
                <input type="number" class="form-control" name="hospedaje" id="hospedaje">
            </div>
            <div class="col-md-3">
              <label for="">Casetas:</label>
              <input type="number" class="form-control" name="casetas" id="casetas">
          </div>
          <div class="col-md-3">
            <label for="">Dias muestreo:</label>
            <input type="number" class="form-control" name="diasMuestreo" id="diasMuestreo">
        </div>
        <div class="col-md-3">
          <label for="">Numero Muestreador:</label>
          <input type="number" class="form-control" name="numMuestreador" id="numMuestreador">
      </div>
      <div class="col-md-3">
        <label for="">Gasolina solicitada:</label>
        <input type="number" class="form-control" name="gasolinaSolicitada" id="gasolinaSolicitada">
    </div>
    <div class="col-md-3">
      <button type="button" onclick="precioMuestreo()" class="btn btn-success"><i class="fa fa-calc"></i> Calcular</button>
  </div>
  <div class="col-md-3">
    <label for="">Sub total:</label>
    <input type="number" class="form-control" name="subTotal" id="subTotal">
</div>
              <!-- Precio Km-->
              <div class="col-md-3">
                  <label for="">Estado:</label>
                  <select  class="form-control" placeholder="Estado" id="estado" name="estado">
                    @foreach ($estados as $item)
                      <option value="{{$item->Clave}}" selected>{{$item->Nombre}}</option>
                    @endforeach
                  </select>
              </div>
              <!-- Tomas de Muestra-->
              <div class="col-md-3">
                <label for="">Localidad:</label>
                <select  class="form-control select2" placeholder="Localidad" id="localidad" name="localidad">
                  
                </select>
              </div>
              <div class="col-md-12">
                <label for="">$ Total muestreo:</label>
                <input type="number" class="form-control" name="totalMuestreo" id="totalMuestreo" disabled>
              </div>
              </div>
            </div>

            <!-- Observación Interna -->
            <div class="col-md-12 mt-1">
                <label for="">Observación interna:</label>
                <textarea cols="30" rows="2" class="form-control" name="observacionInterna" id="observacionInterna">
            </textarea>
            </div> 
            <!-- Observación cotización  -->
            <div class="col-md-12 mt-1">
                <label for="">Observación cotización:</label>
                <textarea cols="30" rows="2" class="form-control" name="observacionCotizacion" id="observacionCotizacion">
            </textarea>
            </div>
            <!-- Forma de pago-->
            <div class="col-md-6">
                <label for="">Forma de Pago</label>
                <select name="metodoPago" class="form-control" id="metodoPago">
                    @foreach ($metodoPago as $item)
                        <option value="{{$item->Id_metodo}}">{{$item->Metodo}}</option>
                    @endforeach
                </select>
            </div>
            <!-- Tiempo de Entrega -->
            <div class="col-md-6">
                <label for="">Tiempo de Entrega</label>
                <input type="text" class="form-control" name="tiempoEntrega" id="tiempoEntrega">
            </div>
            <div class="col-md-12">
                <hr>
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
              <div class="form-group">
                <label for="precio">Costo cotizacion</label>
                <input type="text" class="form-control" id="precio" name="precio" disabled>
              </div>
          </div>
          <div class="col-md-12" hidden>
            <input type="text" class="form-control" hidden id="parametrosCotizacion" name="parametrosCotizacion" >
            <input type="text" class="form-control" hidden id="puntosCotizacion" name="puntosCotizacion" >
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
