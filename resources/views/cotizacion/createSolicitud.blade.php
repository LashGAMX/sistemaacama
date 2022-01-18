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
      </ul>
      {{-- Contenido de nav --}}
      <form action="{{url('admin/cotizacion/solicitud/setSolicitud')}}" method="POST">
        @csrf
        <input type="text" class="" id="sw"  hidden value="{{@$sw}}">
        <input type="text" class="" id="idSol" hidden value="{{@$model->Id_solicitud}}">
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
                    @if (@$model->Id_intermedio == $item->Id_cliente)
                      <option value="{{$item->Id_cliente}}" selected>{{$item->Nombres}} {{$item->A_paterno}}</option>
                    @else
                      <option value="{{$item->Id_cliente}}">{{$item->Nombres}} {{$item->A_paterno}}</option>
                    @endif
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
                    @if (@$model->Id_cliente == $item->Id_cliente)
                      <option value="{{$item->Id_cliente}}" selected>{{$item->Empresa}}</option>
                    @else
                      <option value="{{$item->Id_cliente}}">{{$item->Empresa}}</option>
                    @endif
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
                    <td><input type="text" class="form-control" id="emailCont" disabled></td>
                    <td>Celular: </td>
                    <td><input type="text" class="form-control" id="celCont" disabled></td>
                    <td>Telefono: </td>
                    <td><input type="text" class="form-control" id="telCont" disabled></td>
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
            <input type="text" class="form-control" id="atencion" name="atencion" placeholder="Nombre con atención a..." value="{{@$model->Atencion}}">
          </div>
          <div class="col-md-12">
            <label for="observacion">Observación</label>
            {{-- <input type="text" class="form-control" id="observacion"> --}}
            <textarea class="form-control" id="observacion" name="observacion" placeholder="Escribir...">{{@$model->Observacion_cotizacion}}</textarea>
          </div>

          <div class="col-md-12">
            <h6>Datos generales</h6>
            <hr>
          </div>

          <div class="col-md-12">
            <div class="row">
              
              <div class="col-md-4">
                <label for="servicio">Servicio</label>

                @if (!is_null($model->Servicio))
                  <select name="tipoServicio" id="tipoServicio" class="form-control">
                    <option value="{{$model->Tipo_servicio}}" selected>{{$model->Servicio}}</option>
                  </select>
                @else
                  <select name="tipoServicio" id="tipoServicio" class="form-control">
                    @foreach ($servicios as $item)
                    @if (@$model->Tipo_servicio == $item->Id_tipo)
                      <option value="{{$item->Id_tipo}}" selected>{{$item->Servicio}}</option>
                    @else
                      <option value="{{$item->Id_tipo}}">{{$item->Servicio}}</option>
                    @endif
                    @endforeach
                  </select>
                @endif
                
              </div>
              <div class="col-md-4">
                <label for="tipoDescarga">Tipo descarga</label>
                
                @if (!is_null($model->Descarga))
                  <select name="tipoDescarga" id="tipoDescarga" class="form-control">
                    <option value="{{$model->Tipo_descarga}}" selected>{{$model->Descarga}}</option>
                  </select>                
                @else
                  <select name="tipoDescarga" id="tipoDescarga" class="form-control">
                    @foreach ($descargas as $item)
                      @if (@$model->Tipo_descarga == $item->Id_tipo)
                        <option value="{{$item->Id_tipo}}" selected>{{$item->Descarga}}</option>
                      @else
                        <option value="{{$item->Id_tipo}}">{{$item->Descarga}}</option>
                      @endif
                    @endforeach
                  </select>
                @endif
                                
              </div>
              <div class="col-md-4">
                <label for="norma">Norma</label>

                @if (!is_null($model->Clave_norma))
                  <select name="norma" id="norma" class="form-control">
                    <option value="{{$model->Id_norma}}" selected>{{$model->Clave_norma}}</option>
                  </select>
                @else
                  <select name="norma" id="norma" class="form-control">
                    @foreach ($normas as $item)
                      @if (@$model->Id_norma == $item->Id_norma)
                        <option value="{{$item->Id_norma}}" selected>{{$item->Clave_norma}}</option>
                      @else
                        <option value="{{$item->Id_norma}}">{{$item->Clave_norma}}</option>
                      @endif
                    @endforeach
                  </select>
                @endif
                
              </div>
              <div class="col-md-4">
                <label for="subnorma">Clasificación</label>
                <select name="subnorma" id="subnorma" class="form-control">
                </select>
              </div>
              <div class="col-md-4">
                <label for="fechaMuestreo">Fecha muestreo</label> 
                <input type="date" id="fechaMuestreo" name="fechaMuestreo" class="form-control" value="{{@$model->Fecha_muestreo}}">
              </div>
              <div class="col-md-4">
                <label for="frecuencia">Muestreo</label>
                
                @if (!is_null($frecuencia))
                  <select class="form-control" placeholder="Frecuencia" id="frecuencia" name="frecuencia">                    
                    <option value="{{$frecuencia->Id_frecuencia}}" selected>{{$frecuencia->Descripcion}}</option>                    
                  </select>
                @else
                  <select class="form-control" placeholder="Frecuencia" id="frecuencia" name="frecuencia">
                    @foreach ($frecuencia as $item)
                    <option value="{{$item->Id_frecuencia}}">{{$item->Descripcion}}</option>
                    @endforeach
                  </select>
                @endif
                
              </div>
              <div class="col-md-4">
                <label for="numTomas">Número de tomas</label>
                <input type="text" id="numTomas" class="form-control" value="{{$model->Tomas}}" disabled>                
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
              
              @if (!is_null($model->Tipo_muestra))
                <select name="tipoMuestra" id="tipoMuestra" class="form-control">
                  <option value="0" selected>{{$model->Tipo_muestra}}</option>                  
                </select>  
              @else
                <select name="tipoMuestra" id="tipoMuestra" class="form-control">
                  <option>Sin seleccionar</option>
                  <option value="0">INSTANTANEA</option>
                  <option value="1">COMPUESTA</option>
                </select>
              @endif
                            
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="promedio">Promedio</label>

              @if (!is_null($model->Promedio))
                <select name="promedio"  class="form-control" id="promedio">                  
                  <option value="0">{{$model->Promedio}}</option>
                </select>
              @else
                <select name="promedio"  class="form-control" id="promedio">
                  <option value="SIN SELECCIONAR" selected>SIN SELECCIONAR</option>
                  <option value="MUESTREO INSTANTANEO">MUESTREO INSTANTANEO</option>
                  <option value="MENSUAL">MENSUAL</option>
                  <option value="DIARIO">DIARIO</option>
                </select>
              @endif              
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
                @if (@$sw === true)
                  @foreach ($puntos as $item)
                    <tr>
                        <td>{{$item->Id_punto}}</td>
                        <td>{{$item->Punto_muestreo}}</td>
                    </tr>
                  @endforeach
                @endif
              </tbody>

            </table>
          </div>

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
            <div class="col-md-2">
              <div class="form-group">
                <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Guardar solicitud</button>
              </div>
            </div>

            <div class="col-md-12">
              {{-- @livewire('analisis-q.norma-parametros', ['idUser' => Auth::user()->id,'idSub' => @$idSub]) --}}
              <div id="tabParametros">

              </div>
            </div>
          </div>

        </div>
      </div>
      <div class="col-md-12" hidden>
        <input type="text" class="form-control" hidden id="idCotizacion" name="idCotizacion" value="{{$idCot}}">
        <input type="text" class="form-control" hidden id="parametrosSolicitud" name="parametrosSolicitud" >
        <input type="text" class="form-control" hidden id="puntosSolicitud" name="puntosSolicitud" >
        <input type="text" class="form-control" hidden id="numTomas2" name="numTomas" value="{{$model->Tomas}}">
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
<script src="{{asset('/js/cotizacion/createSolicitud.js')}}"></script>
<script src="{{asset('/js/libs/componentes.js')}}"></script>
<script src="{{asset('/js/libs/tablas.js')}}"></script>
@stop
