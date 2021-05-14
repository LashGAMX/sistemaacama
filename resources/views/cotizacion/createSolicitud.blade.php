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
                  <option value="{{$item->Id_cliente}}">{{$item->Nombres}} {{$item->A_paterno}}</option>
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
                <select name="clientes" id="clientes" class="form-control select2" onchange="getSucursal()">
                 <option value="0">Sin seleccionar</option>
                 @foreach ($cliente as $item)
                 <option value="{{$item->Id_cliente}}">{{$item->Empresa}}</option>
                 @endforeach
               </select>
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
                <select name="direccionReporte" id="direccionReporte" class="form-control">
                </select>
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
                    
                    <button onclick="setContacto()" style="border:none;background:none;" type="button"><i class="fa fa-user-edit text-warning"> Editar</i></button>
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
                <select  class="form-control" placeholder="Frecuencia" id="frecuencia" name="frecuencia">
                  @foreach ($frecuencia as $item)
                  <option value="{{$item->Id_frecuencia}}">{{$item->Descripcion}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4">
                <label for="numTomas">Numero de tomas</label>
                <input type="text" id="numTomas" name="numTomas" class="form-control">
              </div>

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
        <input type="text" class="form-control" hidden id="parametrosSolicitud" name="parametrosSolicitud" >
        <input type="text" class="form-control" hidden id="puntosSolicitud" name="puntosSolicitud" >
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
<script src="{{asset('js/cotizacion/createSolicitud.js')}}"></script>
<script src="{{asset('js/libs/componentes.js')}}"></script>
<script src="{{asset('js/libs/tablas.js')}}"></script>
@stop
