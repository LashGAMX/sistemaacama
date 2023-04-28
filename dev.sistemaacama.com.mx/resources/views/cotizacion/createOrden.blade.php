@extends('voyager::master')
@section('content')

<link rel="stylesheet" type="text/css" href="{{asset('/public/css/libs/duallist/jquery.transfer.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('/public/css/libs/duallist/icon_font/css/icon_font.css')}}" />

<div class="container-fluid"> 
  <div class="row">
    <div class="col-md-12">
      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link active" id="datos-tab" data-toggle="tab" href="#datos" role="tab" aria-controls="datos"
            aria-selected="true">1. Datos</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="parametro-tab" data-toggle="tab" href="#parametro" role="tab"
            aria-controls="parametro" aria-selected="false">2. Parametros</a>
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
                    <select id="intermediario" class="form-control select2" onchange="getDatoIntermediario()">
                      <option value="0">Sin seleccionar</option>
                      @foreach ($intermediario as $item)
                      <option value="{{$item->Id_intermediario}}">{{$item->Nombres}} {{@$item->A_paterno}}</option>
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
                    <select name="clientes" id="clientes" class="form-control select2" onchange="getSucursalCliente()">
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="sucursal">Sucursal cliente</label>
                    <select name="sucursal" id="sucursal" class="form-control select2" onchange="getDireccionReporte()">
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="direccionReporte">Dirección reporte</label>
                    <select id="direccionReporte" class="form-control select2">
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="siralab" onchange="getDireccionReporte()">
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
                      <code>Deshabilitado temporalmente (Creaer/editar)</code><br>
                      <button disabled onclick="setContacto()" style="border:none;background:none;" type="button"><i
                          class="fa fa-user-plus text-success hover"> Nuevo contacto</i></button>
                      <button disabled onclick="editContacto()" style="border:none;background:none;" type="button"><i
                          class="fa fa-user-edit text-warning"> Editar</i></button>
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
                    </tr>
                    <tr>
                      <td>Departamento: </td>
                      <td><input type="text" class="form-control" id="deptCont" disabled></td>
                      <td>Puesto/Cargo: </td>
                      <td><input type="text" class="form-control" id="puestoCont" disabled></td>
                    </tr>
                    <tr>
                      <td>Email: </td>
                      <td><input type="text" class="form-control" id="emailCont" disabled></td>
                      <td>Telefono: </td>
                      <td><input type="text" class="form-control" id="telCont" disabled></td>
                    </tr>
                    <tr>
                      <td>Celular: </td>
                      <td><input type="text" class="form-control" id="celCont" disabled></td>
                      <td></td>
                      <td></td>
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
              <input type="text" class="form-control" id="atencion" name="atencion"
                placeholder="Nombre con atención a..." value="{{@$model->Atencion}}">
            </div>
            <div class="col-md-12">
              <label for="observacion">Observación</label>
              <textarea class="form-control" id="observacion" name="observacion"
                placeholder="Escribir...">{{@$model->Observacion_cotizacion}}</textarea>
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
                    @foreach ($servicio as $item)
                    <option value="{{$item->Id_tipo}}">{{$item->Servicio}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-4">
                  <label for="tipoDescarga">Tipo descarga</label>
                  <select name="tipoDescarga" id="tipoDescarga" class="form-control" onchange="getNormas()">
                    @foreach ($descargas as $item)
                    <option value="{{$item->Id_tipo}}">{{$item->Descarga}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-4">
                  <label for="norma">Norma</label>

                  <select name="norma" id="norma" class="form-control" onchange="getSubNormas()">

                  </select>

                </div>
                <div class="col-md-4">
                  <label for="subnorma">Clasificación</label>
                  <select name="subnorma" id="subnorma" class="form-control" onchange="getParametrosNorma()">
                  </select>
                </div>
                <div class="col-md-4">
                  <label for="fechaMuestreo">Fecha muestreo</label>
                  <input type="date" id="fechaMuestreo" name="fechaMuestreo" class="form-control"
                    value="{{@$model->Fecha_muestreo}}">
                </div>
                <div class="col-md-4">
                  <label for="frecuencia">Muestreo</label>

                  <select class="form-control" placeholder="Frecuencia" id="frecuencia" name="frecuencia"
                    onchange="getFrecuenciaMuestreo()">
                    @foreach ($frecuencia as $item)
                    <option value="{{$item->Id_frecuencia}}">{{$item->Descripcion}}</option>
                    @endforeach
                  </select>

                </div>
                <div class="col-md-4">
                  <label for="numTomas">Número de tomas</label>
                  <input type="text" id="numTomas" class="form-control" value="" disabled>
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
                    <select name="tipoMuestra" id="tipoMuestra" class="form-control">
                      @foreach($tipoMuestraCot as $item)
                      <option value="{{$item->Id_muestraCot}}">{{$item->Tipo}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="promedio">Promedio</label>
                    <select name="promedio" class="form-control" id="promedio">
                      @foreach($promedioCot as $item)
                      <option value="{{$item->Id_promedioCot}}">{{$item->Promedio}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>




                <div class="col-md-12">
                  <div class="form-group">
                    <label for="tipoReporte">Tipo de reporte</label>
                    <select name="tipoReporte" id="tipoReporte" class="form-control">
                      @foreach (@$categorias001 as $item)
                      <option value="{{$item->Id_categoria}}">{{$item->Categoria}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="col-md-12">
                  <label for="puntoMuestro">Punto de muestreo</label>
                  <button id="addRow" type="button" class="btn btn-sm btn-success"><i class="voyager-list-add"></i>
                    Agregar</button>
                  <button id="delRow" type="button" class="btn btn-sm btn-danger"><i class="voyager-trash"></i>
                    Eliminar</button>
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
                  <input type="text" placeholder="Sin seleccionar norma" id="normaPa" style="width: 60%;border: none;"
                    disabled>
                </div>
                <div class="col-md-2">
                  <button type="button" id="btnSetParametro" onclick="getParametrosSelected()" class="btn btn-warning"
                    data-toggle="modal" data-target="#exampleModal"><i class="fas fa-save"></i> Agragar y/o
                    Eliminar</button>
                </div>
                <div class="col-md-2">
                  <button type="button" id="btnCrearSolicitud" onclick="setSolicitud()" class="btn btn-success"><i
                      class="fas fa-save"></i> Crear Orden</button>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="row">
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
          </div>

        </div>
      </div>
      {{-- Fin parametros --}}
      </form>

    </div>
    <div class="col-md.12">
      <div  style="position: fixed;left: 90%;top:80%"> <button style="border: none" class="btn btn-success" id="btnGuardarOrden"><i class="voyager-paper-plane"></i> Guardar</button></div><br>
    </div>
  </div>
</div>

<div id="divModal">
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
        <div id="divTrans">

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="btnEditarParametros" onclick="updateParametroCot()"
          class="btn btn-success">Guardar</button>
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

@endsection
@section('javascript')
<script src="{{asset('public/js/cotizacion/createOrden.js')}}?v=1.0.0"></script>
<script src="{{ asset('/public/js/libs/duallist/jquery.transfer.js') }}"></script>
@stop