@extends('voyager::master')

@section('content')
<style>
  /* Estilo para el botón flotante */
  .btn-flotante {
    border: none;
    position: fixed;
    right: 20px;
    bottom: 20px;
    background-color: #007bff;
    color: white;
    padding: 15px 20px;
    border-radius: 50px;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    font-size: 16px;
    text-decoration: none;
    transition: background-color 0.3s ease;
    z-index: 1000;
    /* Asegura que el botón esté por encima de otros elementos */
  }

  .btn-flotante:hover {
    background-color: #0056b3;
  }
</style>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label for="clientes">Clientes Registrado</label>
            <select name="clientes" id="clientes" class="form-control select2"
              onchange="getSucursalCliente(this.value)">
              <option value="0">Sin seleccionar</option>
              @foreach ($clientes as $item)
              @if (@$model->Id_cliente == $item->Id_cliente)
              <option value="{{$item->Id_cliente}}" selected>({{$item->Id_cliente}}) {{$item->Empresa}}</option>
              @else
              <option value="{{$item->Id_cliente}}">({{$item->Id_cliente}}) {{$item->Empresa}}</option>
              @endif
              @endforeach
            </select>

          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="sucursal">Sucursal Cliente</label>
              <select name="sucursal" id="sucursal" class="form-control select2"
                onchange="getDireccionReporte(this.value)">
                <option value="0">Sin seleccionar</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="direccionReporte">Dirección reporte</label>
              <select id="direccionReporte" class="form-control select2">
                <option value="0">Sin seleccionar</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="contacto">Contacto cliente</label>

              <select name="contacto" id="contacto" class="form-control select2" onchange="getDataContacto(this.value)">
                <option value="0">Sin seleccionar</option>
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
          <input type="text" class="" id="idCot"  value="{{@$model->Id_cotizacion}}">

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
        <label for="atencion">Con atención a reporte</label>
        <input type="text" class="form-control" id="atencion" name="atencion" placeholder="Nombre con atención a..."
          value="{{@$model->Atencion}}">
      </div>
      <div class="col-md-12">
        <label for="observacion">Observación</label>
        <textarea class="form-control" id="observacion" name="observacion"
          placeholder="Escribir...">{{@$model->Observacion}}</textarea>
      </div>

      <div class="col-md-12">
        <h6>Datos generales</h6>
        <hr>
      </div>
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-4">
            <label for="servicio">Servicio</label>
            <select name="servicio" id="servicio" class="form-control">
              <option value="0">Sin seleccionar</option>
              @foreach ($servicios as $item)
              @if (@$model->Id_servicio == $item->Id_tipo)
              <option value="{{$item->Id_tipo}}" selected>{{$item->Servicio}}</option>
              @else
              <option value="{{$item->Id_tipo}}">{{$item->Servicio}}</option>
              @endif
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label for="norma">Norma</label>
            <select name="norma" id="norma" class="form-control select2" onchange="getSubNormas(this.value)">
              <option value="0">Sin seleccionar</option>
              @foreach ($normas as $item)
              @if (@$model->Id_norma == $item->Id_norma)
              <option value="{{$item->Id_norma}}" selected>{{$item->Clave_norma}}</option>
              @else
              <option value="{{$item->Id_norma}}">{{$item->Clave_norma}}</option>
              @endif
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label for="subnorma">Paquete</label>
            <select name="subnorma" id="subnorma" class="form-control" onchange="getParametrosNorma()">
            </select>
          </div>
          <div class="col-md-4">
            <label for="fechaMuestreo">Fecha muestreo</label>
            <input type="date" id="fechaMuestreo" name="fechaMuestreo" @if (@$model->Folio_servicio != "") disabled
            @endif
            class="form-control" value="{{@$model->Fecha_muestreo}}">
          </div>
          <div class="col-md-4">
            <label for="numTomas">Número de muestras</label>
            <input type="number" id="numTomas" class="form-control" value="{{@$model->Num_tomas}}">
          </div>
          <div class="col-md-4">
            <label for="numTomas">Folio</label>
            <input type="text" id="Folio" class="form-control" value="{{@$model->Folio}}">
            <br>
            <button class="btn-success" id="btnFolio"><i class="fas fa-new"></i> Crear Folio</button>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <h6>Datos de la muestra a analizar</h6>
        <hr>
      </div>
      <div class="col-md-12">
        <button class="btn-success" id="btnAddCol"><i class="fas fa-plus"></i> </button>
        <div class="" id="divMuestras">
          <table class="table" id="tableParametros">
            <thead>
              <tr>
                <th>#</th>
                <td>Id</td>
                <th>Muestra</th>
                <th>Parametros a realizar</th>
                <th>Normas</th>
                <th></th>
              </tr>
            </thead>
            <tbody id="bodyTabMuestras">
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Botón flotante -->
  <button class="btn-flotante" id="btnGuardar"><i class="fas fa-save"></i> Guardar</button>
  @endsection

  @section('javascript')
  <script src="{{ asset('public/js/alimentos/createOrden.jsx')}}?v=0.0.1"></script>
  @endsection