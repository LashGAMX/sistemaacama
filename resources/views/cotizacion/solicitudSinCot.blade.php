@extends('voyager::master')
@section('content')
<div class="container-fluid">  
  <input type="text" value="{{ @$sw }}" id="sw" hidden>
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
      {{-- Contenido de nav --}}
      <form action="{{url('admin/cotizacion/solicitud/setSolicitudSinCot')}}" method="POST">
        @csrf
        <input type="text" class="" id="sw"  hidden value="{{@$sw}}">

       
        {{-- <input type="text" class="" id="idSol" hidden value="{{@$model->Id_solicitud}}"> --}}
       
       
        <div class="tab-content" id="myTabContent">
        {{-- Inicio Datos --}}
        <div class="tab-pane fade" id="datos" role="tabpanel" aria-labelledby="datos-tab">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="intermediario">Intermediario</label>
                <select name="intermediario" id="intermediario" class="form-control select2" onchange="getDatoIntermediario()">
                  <option value="0">Sin seleccionar</option>
                  @foreach ($intermediario as $item)
                    
                    <option value="{{$item->Id_cliente}}">{{$item->Nombres}} {{$item->A_paterno}} {{$item->A_materno}}</option>
                    
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="row">
                <div class="col-md-2">
                  <h6>Intermediario:</h6>
                </div>
                <div class="col-md-4">
                  <input type="text" class="form-control" id="nombreInter" disabled>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">
                  <h6>Celular:</h6>
                </div>
                <div class="col-md-4">
                  <input type="text" class="form-control" id="celularInter" disabled>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">
                  <h6>Telefono:</h6>
                </div>
                <div class="col-md-4">
                  <input type="text" class="form-control" id="telefonoInter" disabled>
                </div>
              </div>
            </div>

            <div class="col-md-12">
              <h6>Cliente</h6>
              <hr>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label for="clientes">Clientes registrados</label>

                {{-- @if (!is_null($model->Id_cliente))
                
                  <select name="clientes" id="clientes" class="form-control select2" onchange="getSucursal()">
                    <option value="{{$model->Id_cliente}}" selected>{{$cliente->Empresa}}</option>
                  </select>

                @else --}}
                  <select name="clientes" id="clientes" class="form-control {{-- select2 --}}" onclick="getSucursal()">
                    {{-- <option value="0">Sin seleccionar</option> --}}
                    @foreach ($cliente as $item)
                    
                      <option value="{{$item->Id_cliente}}">{{$item->Empresa}}</option>
                    
                    @endforeach
                  </select>
                {{-- @endif --}}

             </div>
             <div class="col-md-6">
              <div class="form-group">
                <label for="sucursal">Sucursal cliente</label>
                <select name="sucursal" id="sucursal" class="form-control" onclick="getDireccionReporteSir()">
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="direccionReporte">Dirección reporte</label>
                <select name="direccionReporte" id="direccionReporte" class="form-control">
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" name="siralab" id="siralab" onclick="getDireccionReporteSir()">
                <label class="form-check-label" for="siralab">Siralab</label>
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <h6>Contacto</h6>
            <hr>
          </div>
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="contacto">Contacto cliente</label>
                  
                  @if(isset($contactoCliente))
                    <select name="contacto" id="contacto" class="form-control" onchange="getDataContacto()">
                    
                      @foreach ($contactoCliente as $item)
                        {{-- <option value="{{$item->Id_contacto}}">{{$item->Nombres}} {{$item->A_paterno}} {{$item->A_materno}}</option> --}}
                        <option value="{{$item->Id_contacto}}">{{$item->Nombres}} {{$item->A_paterno}} {{$item->A_materno}}</option>
                      @endforeach                    

                    </select>                  
                  @else
                    <select name="contacto" id="contacto" class="form-control" onchange="getDataContacto()">
                    </select>
                  @endif

                  <small id="" class="form-text text-muted">
                    <button onclick="setContacto()" style="border:none;background:none;" type="button"><i class="fa fa-user-plus text-success hover" > Nuevo contacto</i></button>
                    
                    <button onclick="editContacto()" style="border:none;background:none;" type="button"><i class="fa fa-user-edit text-warning"> Editar</i></button>
                  </small>
                </div>
              </div>
              <div class="col-md-8"> 
                <table class="table">
                  <tr>
                    <td>Id: </td>
                    <td><input type="text" class="form-control" id="idCont" disabled></td>
                    <td>Nombre: </td>
                    <td><input type="text" class="form-control" id="nombreCont" disabled></td>
                    <td>Apellidos: </td>
                    <td><input type="text" class="form-control" id="apellidoCont" disabled></td>
                  </tr>
                  <tr>
                    <td>Email: </td>
                    <td><input type="text" class="form-control" name="emailContVal" id="emailCont" disabled></td>                    
                    <td>Celular: </td>
                    <td><input type="text" class="form-control" id="celCont" disabled></td>
                    <td>Telefono: </td>
                    <td><input type="text" class="form-control" name="telefonoCont" id="telCont" disabled></td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <h6>Otros</h6>
            <hr>
          </div>

          <div class="col-md-12">
            <label for="atencion">Con atención a reporte</label>
            <input type="text" class="form-control" id="atencion" name="atencion" placeholder="Nombre con atención a...">
          </div>
          <div class="col-md-12">
            <label for="observacion">Observación</label>
            {{-- <input type="text" class="form-control" id="observacion"> --}}
            <textarea class="form-control" id="observacion" name="observacion" placeholder="Escribir..."></textarea>
          </div>

          <div class="col-md-12">
            <h6>Datos generales</h6>
            <hr>
          </div>

          <div class="col-md-12">
            <div class="row">
              
              <div class="col-md-4">
                <label for="servicio">Servicio</label>                
                  <select name="tipoServicio" id="tipoServicio" class="form-control">
                    @foreach ($servicios as $item)
                    
                      <option value="{{$item->Id_tipo}}">{{$item->Servicio}}</option>
                    
                    @endforeach
                  </select>                
                
              </div>
              <div class="col-md-4">
                <label for="tipoDescarga">Tipo descarga</label>                                
                  <select name="tipoDescarga" id="tipoDescarga" class="form-control">
                    @foreach ($descargas as $item)                      
                        
                        <option value="{{$item->Id_tipo}}">{{$item->Descarga}}</option>
                      
                    @endforeach
                  </select>                
                                
              </div>
              <div class="col-md-4">
                <label for="norma">Norma</label>                
                  <select name="norma" id="norma" class="form-control">
                    @foreach ($normas as $item)                                              
                        
                        <option value="{{$item->Id_norma}}">{{$item->Clave_norma}}</option>

                    @endforeach
                  </select>                
                
              </div>
              <div class="col-md-4">
                <label for="subnorma">Clasificación</label>
                <select name="subnorma" id="subnorma" class="form-control">
                </select>
              </div>
              <div class="col-md-4">
                <label for="fechaMuestreo">Fecha muestreo</label> 
                <input type="date" id="fechaMuestreo" name="fechaMuestreo" class="form-control">
              </div>
              <div class="col-md-4">
                <label for="frecuencia">Muestreo</label>
                                
                  <select class="form-control" placeholder="Frecuencia" id="frecuencia" name="frecuencia">
                    @foreach ($frecuencia as $item)
                    <option value="{{$item->Id_frecuencia}}">{{$item->Descripcion}}</option>
                    @endforeach
                  </select>                
                
              </div>
              <div class="col-md-4">
                <label for="numTomas">Número de tomas</label>
                <input type="text" id="numTomas2" name="numTomas2" class="form-control" disabled>
              </div>

            </div>
          </div>
          
          <div class="col-md-12">
            <h6>Especificaciones</h6>
            <hr>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="tipoMuestra">Tipo de muestra</label>
                
                <select name="tipoMuestra" id="tipoMuestra" class="form-control">
                  <option>Sin seleccionar</option>
                  <option value="0">INSTANTANEA</option>
                  <option value="1">COMPUESTA</option>
                </select>              
                            
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="promedio">Promedio</label>
              
                <select name="promedio"  class="form-control" id="promedio">
                  <option value="SIN SELECCIONAR" selected>SIN SELECCIONAR</option>
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

          <div class="col-md-12">
            <label for="puntoMuestro">Punto de muestreo</label>
            <button id="addRow" type="button" class="btn btn-sm btn-success"><i
                    class="voyager-list-add"></i> Agregar</button>
            <button id="delRow" type="button" class="btn btn-sm btn-danger"><i
                    class="voyager-trash"></i> Eliminar</button>
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
                                <td><input class="form-control" id="punto{{ $contPunto - 1 }}"
                                        value="{{ $item->Descripcion }}"></td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>

            </table>
        </div>
        <input type="text" name="contPunto" id="contPunto" value="{{ $contPunto }}" hidden>

          {{-- <div class="col-md-12">
            <label for="puntoMuestro">Punto de muestreo</label>
            <button id="addRow" type="button" class="btn btn-sm btn-success"><i class="voyager-list-add"></i> Agregar</button>
            <button id="delRow" type="button" class="btn btn-sm btn-danger"><i class="voyager-trash"></i> Eliminar</button>
            <table id="puntoMuestro" class="display" style="width:100%">
              <thead>
                <tr>
                  <th>Id</th>
                  <th style="width: 90%">Punto muestreo</th>
                </tr>
              </thead>
              <tbody>
                @php
                    $contPunto = 0;
                @endphp

                @if (@$sw === true)
                  @foreach ($puntos as $item)
                    @php
                        $contPunto++;
                    @endphp

                    <tr>
                        <td>{{$item->Id_punto}}</td>
                        <td>{{$item->Punto_muestreo}}</td>
                    </tr>
                  @endforeach
                @endif
              </tbody>

            </table>
          </div>

          <input type="text" name="contPunto" id="contPunt" value="{{$contPunto}}" hidden>
 --}}
          </div>
        </div>
        {{-- Inicio parametros --}}
        <div class="tab-pane fade" id="parametro" role="tabpanel" aria-labelledby="parametro-tab">

          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label for="normaPa">Norma</label>
                <input type="text" class="form-control" placeholder="normaPa" id="normaPa" name="normaPa" disabled>
              </div>
            </div>
          
            {{--AQUÍ IBA BOTÓN--}}

            <div class="col-md-12">
              {{-- @livewire('analisis-q.norma-parametros', ['idUser' => Auth::user()->id,'idSub' => @$idSub]) --}}
              <div id="tabParametros">
              </div>
            </div>
          </div>
        </div>
        {{-- Fin parametros --}}


        {{--Inicio cotización--}}
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

                                    {{-- <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="textEstado">Estado Cotización</label>
                                            <input type="text" class="form-control" id="textEstado" disabled>
                                        </div>
                                    </div> --}}

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
                                <input type="text" class="form-control" disabled id="textTomas">
                                <label for="">Fecha de Muestreo:</label>
                                <input type="date" class="form-control" disabled id="fechaMuestreoCot">
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
                                        <label for=""># de muestreo:</label>
                                        <input type="number" class="form-control" name="numeroMuestreo"
                                            id="numeroMuestreo" value="{{ @$muestreo->Num_muestreo }}">
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
                                        <label for="">Gasto de paqueteria $ :</label>
                                        <input type="number" class="form-control" name="paqueteria" id="paqueteria"
                                            value="{{ @$muestreo->Paqueteria }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">Gasto Adicional $:</label>
                                        <input type="number" class="form-control" name="gastosExtras" id="gastosExtras"
                                            value="{{ @$muestreo->Adicional }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for=""># de Servicios:</label>
                                        <input type="number" class="form-control" name="numeroServicio"
                                            id="numeroServicio" value="{{ @$muestreo->Num_servicio }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">Numero Muestreador:</label>
                                        <input type="number" class="form-control" name="numMuestreador"
                                            id="numMuestreador" value="{{ @$muestreo->Num_muestreador }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">Estado:</label>
                                        <select class="form-control" placeholder="Estado" id="estado" name="estado">
                                            @foreach (@$estados as $item)
                                                @if (@$muestreo->Estado == $item->Nombre)
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
                                        <button class="btn btn-sm btn-success" id="btnCalcularMuestreo" type="button"
                                            onclick="precioCampo()">Calcular</button>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="">$ Total muestreo:</label>
                                        <input type="text" class="form-control" name="totalMuestreo" id="totalMuestreo"
                                            value="{{ @$muestreo->Total }}">
                                    </div>
                                </div>


                            </div>
                            <!-- Observación Interna -->
                            <div class="col-md-12 mt-1">
                                <label for="">Observación interna:</label>
                                <textarea cols="30" rows="2" class="form-control" name="observacionInterna"
                                    id="observacionInterna" value="{{ @$model->Observacion_interna }}">
                </textarea>
                            </div>
                            <!-- Observación cotización  -->
                            <div class="col-md-12 mt-1">
                                <label for="">Observación cotización:</label>
                                <textarea cols="30" rows="2" class="form-control" name="observacionCotizacion"
                                    id="observacionCotizacion" value="{{ @$model->Observacion_cotizacion }}">
                </textarea>
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
                                <input type="number" class="form-control" name="tiempoEntrega" id="tiempoEntrega"
                                    value="{{ @$model->Tiempo_entrega }}">
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
                                <div class="col-md-1">
                                    <button class="btn btn-success" onclick="btnDescuento()" type="button"><i
                                            class="voyager-tag"></i> Descuento</button>
                                </div>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="width: 40%">Servicio</th>
                                            <th scope="col">Sub total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Analisis</td>
                                            <td><input type="text" class="form-control" id="precioAnalisis"
                                                    name="precioAnalisis" placeholder="Precio análsis"
                                                    value="{{ @$model->Precio_analisis }}"></td>
                                        </tr>
                                        <tr>
                                            <td><code>Nota: El descuento solo aplica directamente al análisis</code></td>
                                            <td></td>
                                        </tr>
                                        <tr id="activarDescuento">
                                            <td>Descuento</td>
                                            <td>
                                                <input type="text" class="form-control" id="descuento" name="descuento"
                                                    placeholder="Descuento" value="{{ @$model->Descuento }}">
                                                <button type="button" class="btn btn-info"
                                                    onclick="aplicarTotal()">Aplicar</button>
                                            </td>
                                        </tr>
                                        <tr>
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
                            <div class="col-md-12" hidden>
                                <input type="text" class="form-control" id="parametrosSolicitud"
                                    name="parametrosSolicitud" hidden>
                                <input type="text" class="form-control" id="puntosSolicitud2"
                                    name="puntosSolicitud2" hidden>
                            </div>


                            <div class="col-md-2">
                              <div class="form-group">
                                <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Guardar solicitud</button>
                              </div>
                            </div>
                        </div>
                    </div>

        {{--Fin cotización--}}
      </div>
      <div class="col-md-12" hidden>
        <td><input type="hidden" class="form-control" name="emailContVal" id="emailCont2"></td>
        <td><input type="hidden" class="form-control" name="telefonoContVal" id="telCont2"></td>
        {{-- <input type="text" class="form-control" hidden id="idCotizacion" name="idCotizacion" value="{{$idCot}}"> --}}
        {{-- <input type="text" class="form-control" id="parametrosSolicitud" name="parametrosSolicitud" > --}}
        <input type="text" class="form-control" id="puntosSolicitud" name="puntosSolicitud">
        <input type="text" id="numTomas" name="numTomas" class="form-control">
      </div>      
        {{-- Fin parametros --}}
      </form>
      
    </div>
  </div>
</div>

<div id="divModal">
</div>

@endsection
@section('javascript')
<script src="{{asset('js/cotizacion/createSolicitudSinCot.js')}}"></script>
<script src="{{asset('js/libs/componentes.js')}}"></script>
<script src="{{asset('js/libs/tablas.js')}}"></script>
@stop