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
        <input type="text" class="" id="idCot" hidden value="{{@$model->Id_cotizacion}}">
       <div class="tab-content" id="myTabContent">
        {{-- Inicio Datos --}}
        <div class="tab-pane fade" id="datos" role="tabpanel" aria-labelledby="datos-tab">
            <div class="row">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-6"> 
                    <div class="form-group">
                      <label for="intermediario">Intermediario</label>
                      <select  id="intermediario" class="form-control select2" onchange="getDatoIntermediario()">
                        <option value="0">Sin seleccionar</option>
                        @foreach ($intermediario as $item)
                          @if ($model->Id_intermedio == $item->Id_intermediario)
                            <option value="{{$item->Id_intermediario}}" selected>{{$item->Nombres}} {{@$item->A_paterno}}</option>  
                          @else
                            <option value="{{$item->Id_intermediario}}">{{$item->Nombres}} {{@$item->A_paterno}}</option>
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
                </div>
              </div>
              <div class="col-md-12">
                <h6>Cliente</h6>
                <hr>
              </div>

              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="clientes">Clientes registrados</label>
                      <select name="clientes" id="clientes" class="form-control" onchange="getSucursalCliente()" >
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="sucursal">Sucursal cliente</label>
                      <select name="sucursal" id="sucursal" class="form-control" onclick="getDireccionReporte()">
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="direccionReporte">Dirección reporte</label>
                      <select id="direccionReporte" class="form-control">
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group form-check">
                      <input type="checkbox" class="form-check-input" id="siralab">
                      <label class="form-check-label" for="siralab">Siralab</label>
                    </div>
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
          
                        <select name="contacto" id="contacto" class="form-control" onchange="getDataContacto()">
                        </select>

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
                <select name="tipoServicio" id="tipoServicio" class="form-control" disabled>
                  @foreach ($servicio as $item)
                    @if (@$model->Tipo_servicio == $item->Id_tipo)
                      <option value="{{$item->Id_tipo}}" selected>{{$item->Servicio}}</option>
                    @else
                      <option value="{{$item->Id_tipo}}">{{$item->Servicio}}</option>
                    @endif
                  @endforeach
                </select>
              </div>
              <div class="col-md-4">
                <label for="tipoDescarga">Tipo descarga</label>
                <select name="tipoDescarga" id="tipoDescarga" class="form-control" disabled>
                @foreach ($descargas as $item)
                    @if (@$model->Tipo_descarga == $item->Id_tipo)
                      <option value="{{$item->Id_tipo}}" selected>{{$item->Descarga}}</option>
                    @else
                      <option value="{{$item->Id_tipo}}">{{$item->Descarga}}</option>
                    @endif
                @endforeach
                </select>
              </div>
              <div class="col-md-4">
                <label for="norma">Norma</label>

                  <select name="norma" id="norma" class="form-control" disabled>
                    <option value="{{$model->Id_norma}}">{{$model->Clave_norma}}</option>
                  </select>
        
              </div>
              <div class="col-md-4">
                <label for="subnorma">Clasificación</label>
                <select name="subnorma" id="subnorma" class="form-control" disabled>
                </select>
              </div>
              <div class="col-md-4">
                <label for="fechaMuestreo">Fecha muestreo</label> 
                <input type="date" id="fechaMuestreo" name="fechaMuestreo" class="form-control" value="{{@$model->Fecha_muestreo}}">
              </div>
              <div class="col-md-4">
                <label for="frecuencia">Muestreo</label>
  
                  <select class="form-control" placeholder="Frecuencia" id="frecuencia" name="frecuencia" disabled>
                    @foreach ($frecuencia as $item)
                    @if (@$model->Frecuencia_muestreo == $item->Id_frecuencia)
                      <option value="{{$item->Id_frecuencia}}" selected>{{$item->Descripcion}}</option>  
                    @else
                      <option value="{{$item->Id_frecuencia}}">{{$item->Descripcion}}</option>
                    @endif
                  @endforeach
                  </select>
             
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

          <div class="col-md-12">
            <div class="row">

              <div class="col-md-6">
                <div class="form-group">
                    <label for="tipoMuestra">Tipo de muestra</label>
                    <select name="tipoMuestra" id="tipoMuestra" class="form-control" disabled>                                      
                      @foreach($tipoMuestraCot as $item)
                      @if (@$model->Id_tipoMuestra == $item->Id_muestraCot)
                          <option value="{{$item->Id_muestraCot}}" selected>{{$item->Tipo}}</option>    
                      @else
                          <option value="{{$item->Id_muestraCot}}">{{$item->Tipo}}</option>    
                      @endif
                  @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="promedio">Promedio</label>
                    <select name="promedio" class="form-control" id="promedio" disabled>
                      @foreach($promedioCot as $item)
                        @if (@$model->Id_promedio == $item->Id_promedioCot)
                            <option value="{{$item->Id_promedioCot}}" selected>{{$item->Promedio}}</option>    
                        @else
                            <option value="{{$item->Id_promedioCot}}">{{$item->Promedio}}</option>    
                        @endif 
                    @endforeach
                    </select>
                </div>
            </div>
    
              
    
    
              <div class="col-md-12">
                <div class="form-group">
                    <label for="tipoReporte">Tipo de reporte</label>
                    <select name="tipoReporte" id="tipoReporte" class="form-control" disabled>
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
 
                  </tbody>
    
                </table>
              </div>
            </div>
          </div>

            </div>
        </div>
        {{-- Inicio parametros --}}
        <div class="tab-pane fade" id="parametro" role="tabpanel" aria-labelledby="parametro-tab">

          <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-8">
                        <label for="normaPa">Norma : </label>
                        <input type="text" placeholder="Sin seleccionar norma" id="normaPa"
                            style="width: 60%;border: none;" disabled>
                    </div>
                    <div class="col-md-4">
                        <button type="button" id="btnCrearSolicitud" onclick="setSolicitud()" class="btn btn-success"><i class="fas fa-save"></i> Crear Orden</button>
                    </div>
                </div> 
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-8">
                        <table class="table" id="tableParametros">
                            <thead>
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
<script src="{{asset('public/js/cotizacion/createSolicitud.js')}}?v=0.0.2"></script>
@stop
