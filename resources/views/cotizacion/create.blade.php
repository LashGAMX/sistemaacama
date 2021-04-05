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
                    <option value="1">Análisis</option>
                    <option value="2">Muestreo</option>
                    <option value="3">Análisis y muestreo</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="tipoDescarga">Tipo descarga</label>
                  <select name="tipoDescarga" id="tipoDescarga" class="form-control">
                    <option value="0">Sin seleccionar</option>
                    <option value="1">Rios</option>
                    <option value="2">Embalse natural</option>
                    <option value="3">Agua costera</option>
                    <option value="4">Suelo</option>
                  </select>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label for="norma">Clasificaciòn de la norma</label>
                  <select name="norma" id="norma" class="form-control">
                    <option value="0">Sin seleccionar</option>
                    @foreach ($normas as $item)
                        <option value="{{$item->Id_norma}}">{{$item->Clave_norma}}</option>
                    @endforeach
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
                    <option value="0">Sin seleccionar</option>
                    <option value="1">INSTANTANEA</option>
                    <option value="2">COMPUESTA</option>
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
          <div class="tab-pane fade" id="cotizacion" role="tabpanel" aria-labelledby="cotizacion-tab">Cotizacion</div>
        </div>
    </div>
</div>

@endsection
@section('javascript')


<script src="{{asset('js/cotizacion/create.js')}}"></script>
<script src="{{asset('js/libs/componentes.js')}}"></script>
<script src="{{asset('js/libs/tablas.js')}}"></script>
@stop
