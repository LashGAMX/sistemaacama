@extends('voyager::master')

@section('content')
  @section('page_header')
<div class="container-fluid">
  <p>Cotización</p>
    <div class="row">
        <div class="col-md-12">
            {{-- {{Auth::user()->id}}
            {{Auth::user()->name}} --}}

            <div class="row">
                {{-- <input type="text" value="{{$idUser}}"> --}}
                <!-- Parte de Encabezado-->
              <div class="col-md-2">

                <button class="btn btn-success btn-sm"  onclick="abrirModal(1)">
                    <i class="voyager-plus"></i> Crear</button>
              </div>
                {{-- {{$idUser}} --}}
               <div class="col-md-4 mt-2">
                   <input type="date"  placeholder="Fecha inicio" class="form-control" value="">
                </div>
               <div class="col-md-4 mt-2">
                   <input type="date"  placeholder="Fecha inicio" class="form-control" value="">
               </div>

              <div class="col-md-2 mt-2">
                <input type="search" class="form-control" placeholder="Buscar">
              </div>
               <!-- Fin Parte de Encabezado-->

                <!--Tabla -->
                <table class="table table-sm">
                    <thead class="">
                        <tr>
                            <th>Cliente</th>
                            <th>Folio Servicio</th>
                            <th>Cotización Folio</th>
                            <th>Empresa</th>
                            <th>Servicio</th>
                            <th>Fecha Cotización</th>
                            <th>Supervición</th>
                            <th>Acciónes</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($model as $item)
                      <td>{{$item->Cliente}}</td>
                      <td>{{$item->Folio_servicio}}</td>
                      <td>{{$item->Cotizacion_folio}}</td>
                      <td>{{$item->Empresa}}</td>
                      <td>{{$item->Servicio}}</td>
                      <td>{{$item->Fecha_cotizacion}}</td>
                      <td>{{$item->Supervicion}}</td>
                      <td>
                        <button type="button" class="btn btn-sm btn-warning" onclick=abrirModal(2)>
                        <i class="voyager-edit"></i> <span hidden-sm hidden-xs>Editar</span> </button>
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalCotizacionHistorico" wire:click="details('{{$item->Id_cotizacion}}')">
                        <i class="voyager-list" aria-hidden="true"></i>
                        <span hidden-sm hidden-xs>Historico</span> </button>
                            <button type="button" class="btn btn-sm btn-dark" data-toggle="modal" data-target="#modalCotizacionHistorico" wire:click="details('{{$item->Id_cotizacion}}')">
                                <i class="voyager-documentation" aria-hidden="true"></i>
                                <span hidden-sm hidden-xs>Duplicar</span> </button>
                      </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
        </div>
      </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalCotizacionPrincipal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width:100%">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="tituloModal"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true" onclick="formulario(1)"></a>
                </li>
                {{-- <li class="nav-item">
                  <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false" onclick="formulario(2)">Parametros</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false" onclick="formulario(3)">Información de Cotización</a>
                </li> --}}
              </ul>
              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="row" id="formularioUno">
                        <form id="formularioCotizacion">
                            <input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">
                        <div class="col-md-12">
                            <h6> <b> Datos Intermediario</b></h6>
                        </div>

                        <div class="col-md-12">
                        <select id="intermediarios" class="form-control select2" id="intermediario">
                           @foreach($intermediarios as $intermediario)
                            <option value="{{$intermediario->Id_intermediario}}">{{$intermediario->Nombres}}</option>
                            @endforeach
                        </select>
                        </div>

                        <div class="col-md-12">
                            <h6 class="mt-0"><b>&nbsp;&nbsp;Clientes Registrados</b></h6>
                        </div>

                        <div class="col-md-9">
                            <select id="clientes" class="form-control select2" id="clienteObtenidoSelect" >
                                @foreach ($cliente as $client)
                                <option value="{{$client->Id_cliente}}">{{$client->Nombres}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <button class="btn btn-sm btn-success" id="clienteAgregadoPorSeleccion">Agregar </button>
                        </div>

                        <!-- Nombre del Cliente-->
                    <div class="col-md-6">
                        <label for="">Nombre del Cliente:</label>
                        <input type="text" class="form-control" id="clienteManual">
                    </div>

                    <!-- Dirección -->
                    <div class="col-md-6">
                        <label for="">Dirección</label>
                        <input type="text" class="form-control" id="direccion">
                    </div>

                    <div class="col-md-12">
                        <h6 class="mt-1"><b>&nbsp;&nbsp;A quien va Dirijida la Cotización</b></h6>
                    </div>

                    <!-- Con Atención a -->
                    <div class="col-md-6">
                        <label for="">Con Atención a:</label>
                        <input type="text" class="form-control" id="atencionA">
                    </div>

                    <!-- Telefono -->
                    <div class="col-md-6">
                        <label for="">Telefono:</label>
                        <input type="number" class="form-control" id="telefono">
                    </div>

                    <!-- Correo Electronico-->
                    <div class="col-md-6">
                        <label for="">Email:</label>
                        <input type="text" class="form-control" id="correo">
                    </div>

                      <!-- Estado de la Cotización  -->
                    <div class="col-md-6">
                        <label for="">Estado de la Cotización:</label>
                        <select name="" id="" class="form-control" id="estadoCotizacion">
                            <option value="Cancelado">Cancelado</option>
                            <option value="Cotización">Cotización</option>
                            <option value="Cotización autorizada">Cotización autorizada</option>
                        </select>
                    </div>

                    <!-- Tipo de Servicio -->
                    <div class="col-md-6">
                        <label for="">Tipo de Servicio:</label>
                        <select name="" id="" class="form-control" id="tipoServicio">
                            <option value="ANÁLISIS Y MUESTREO">ANÁLISIS Y MUESTREO</option>
                            <option value="MUESTREO">MUESTREO</option>
                            <option value="ANALISIS">ANALISIS</option>
                        </select>
                    </div>

                    <!-- Tipo de Descarga -->
                    <div class="col-md-6">
                        <label for="">Tipo de Descarga:</label>
                        <select name="" id="" class="form-control" id="tipoDescarga">
                            <option value="AGUAS SALINAS">AGUAS SALINAS</option>
                            <option value="ALBERCA">ALBERCA</option>
                            <option value="CONDICIONES PARTICULARES DE DESCARGA">CONDICIONES PARTICULARES DE DESCARGA</option>
                            <option value="POTABLE">POTABLE</option>
                            <option value="PURIFICADA">PURIFICADA</option>
                            <option value="RESIDUAL">RESIDUAL</option>
                            <option value="SOLIDOS DISUELTOS TOTALES">SOLIDOS DISUELTOS TOTALES</option>
                            <option value="TODOS LOS PARAMETROS">TODOS LOS PARAMETROS</option>
                        </select>
                    </div>

                    <!-- Clasificación de la Norma -->
                    <div class="col-md-6">
                        <label for="">Clasifiación de la Norma:</label>
                        <select name="" id="" class="form-control" id="clasifacionNorma">
                        <option value="BALANCE">BALANCE</option>
                        <option value="BLANCO">BLANCO</option>
                        <option value="CONFICIONES PARTICULARES DE DESCARGA">CONFICIONES PARTICULARES DE DESCARGA</option>
                        <option value="ING.JAIME RANGEL">ING.JAIME RANGEL</option>
                        <option value="NMX-C-122">NMX-C-122</option>
                        <option value="NOM-117">NOM-117</option>
                        <option value="NOM-127">NOM-127</option>
                        </select>
                    </div>

                    <!-- Norma -->
                    <div class="col-md-6">
                        <label for="">Norma:</label>
                            <select name="" id="" class="form-control" id="normaFormularioUno">
                                @foreach ($norma as $norm)
                                <option value="{{$norm->Id_norma}}">{{$norm->Norma}}</option>
                                @endforeach
                            </select>
                    </div>

                    <!-- Fecha -->
                    <div class="col-md-6">
                        <label for="">Fecha:</label>
                        <input type="date" id="fechaCotizacion" placeholder="Fecha inicio" class="form-control" value="">
                    </div>

                    <!-- Frecuencia -->
                    <div class="col-md-6">
                        <label for="">Frecuencia:</label>
                        <input type="number" class="form-control" id="frecuencia">
                    </div>

                    <!-- Tipo de Muestra -->
                    <div class="col-md-3">
                        <label for="">Tipo de Muestra:</label>
                        <select name="" id="" class="form-control" id="tipoMuestra">
                            <option value="INSTANTANEA">INSTANTANEA</option>
                            <option value="COMPUESTA">COMPUESTA</option>
                        </select>
                    </div>

                    <!-- Promedio -->
                    <div class="col-md-3">
                        <label for="">Promedio:</label>
                        <select name="" id="" class="form-control" id="promedio">
                            <option value="MUESTREO INSTANTANEO">MUESTREO INSTANTANEO</option>
                            <option value="MENSUAL">MENSUAL</option>
                            <option value="DIARIO">DIARIO</option>
                        </select>
                    </div>

                    <!-- Puntos de Muestreo -->
                    <div class="col-md-3">
                        <label for="">Numero de Puntos de Muestreo:</label>
                        <input type="number" class="form-control" id="puntosMuestreo" >
                        <p>Puntos de Muestreo</p>
                    </div>

                    <!-- Tipo de reporte  -->
                    <div class="col-md-3">
                        <label for="">Tipo de Reporte:</label>
                        <select name="" id="" class="form-control" id="reporte">
                            <option value="">Opcion 1</option>
                            <option value="">Opcion 2</option>
                            <option value="">Opcion 3</option>
                        </select>
                    </div>

                    <!-- Condicciónes de Venta -->
                    <div class="col-md-12 mt-1">-
                        <label for="">Condicciónes de Venta:</label>
                        <textarea name="" id="" cols="30" rows="2" class="form-control" id="codiccionesVenta">
                    </textarea>
                    </div>


                </div>

                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="row" id="formularioDos">

                        <div class="col-md-6">
                            <div class="col-md-6">
                                <label for="">Norma</label>
                                <select name="" id="" class="form-control" id="normaFormularioUno">
                                    @foreach ($norma as $norm)
                                    <option value="{{$norm->Id_norma}}">{{$norm->Norma}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <h6><p>Parametros Extras</p></h6>
                                <hr>
                            </div>
                            <div class="col-md-12">
                                <select name="" id="" class="form-control" id="parametrosExtras">
                                    @foreach ($norma as $norm)
                                    <option value="{{$norm->Id_norma}}">{{$norm->Norma}}</option>
                                    @endforeach
                                </select>
                            </div>

                    </div>

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <label for="">Filtro</label>
                            <select name="" id="" class="form-control">
                                <option value="">Clasificación</option>
                            </select>
                         </div>
                             <div class="col-12">
                                <div class="form-group">
                                    <select multiple="multiple" size="10" name="duallistbox_demo2" class="demo2" id="parametrosPorClasifiacion">
                                        @foreach ($norma as $norm)
                                        <option value="{{$norm->Id_norma}}">{{$norm->Norma}}</option>
                                        @endforeach
                                      </select>
                                </div>
                            </div>
                </div>

                    </div>
                    <!-- -->
                </div>
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                    <div class="row" id="formularioTres">

                        <div class="col-md-12">
                            <h6> <b> Datos Intermediario</b></h6>
                        </div>
                        <div class="col-md-3">
                            <h6> <b>Intermediario:</b> <p id="obtenerIntermediario"></p> </h6>
                        </div>
                        <div class="col-md-3">
                            <h6> <b>Estado de Cotización:</b><p id="obtenerCotizacion"></p> </h6>
                        </div>
                        <div class="col-md-3">
                            <h6> <b>Ser nombre:</b><p id="obtenerNombreServicio"></p> </h6>
                        </div>
                        <div class="col-md-3">
                            <h6> <b>Tipo Descarga:</b><p id="obtenerTipoDescarga"></p> </h6>
                        </div>
                        <div class="col-md-12">
                            <hr>
                            <h6> <b> Cliente:</b></h6>
                        </div>
                        <div class="col-md-6">
                            <label for="">Nombre del Cliente</label>
                            <input type="text" class="form-control" disabled id="obtenerNombreClienet">
                        </div>
                        <div class="col-md-6">
                            <label for="">Con Atención a:</label>
                            <input type="text" class="form-control" disabled id="obtenerAtencion">
                        </div>
                        <div class="col-md-6">
                            <label for="">Telefono:</label>
                            <input type="number" class="form-control" disabled>
                        </div>
                        <div class="col-md-6">
                            <label for="">Email:</label>
                            <input type="email" class="form-control" disabled>
                        </div>
                        <div class="col-md-12 mt-1">
                            <label for="">Dirección de Cotización:</label>
                            <textarea name="" id="" cols="30" rows="1" class="form-control" disabled>
                        </textarea>
                        </div>
                        <div class="col-md-12">
                            <hr>
                            <h6> <b> Datos de Cotización:</b></h6>
                        </div>
                        <div class="col-md-5">
                            <h6>Norma:<b> <p id="obtenerNorma"></p></b></h6>
                        </div>
                        <div class="col-md-7">
                            <p><b>Muestreo</b> 18 HRS - <b>Numero de Tomas</b> 6 <b>Fecha de Muestreo:</b> 13/03/2021</p>
                        </div>
                        <div class="col-md-3">
                            <label for=""># tomas Muestreo:</label>
                            <input type="text" class="form-control" id="tomasMuestreo">
                        </div>
                        <div class="col-md-3">
                            <label for="">Viaticos:</label>
                            <input type="text" class="form-control" id="viaticos">
                        </div>
                        <div class="col-md-3">
                            <label for="">Gastos Paqueteria:</label>
                            <input type="text" class="form-control" id="paqueteria">
                        </div>
                        <div class="col-md-3">
                            <label for="">Gasto Adicional:</label>
                            <input type="text" class="form-control" id="gastosExtras">
                        </div>
                        <div class="col-md-3">
                            <label for="">N Servicio:</label>
                            <input type="text" class="form-control" id="servicio">
                        </div>
                        <div class="col-md-3">
                            <label for="">Km Extra:</label>
                            <input type="text" class="form-control" id="kmExtra">
                        </div>
                        <div class="col-md-3">
                            <label for="">Precio Km:</label>
                            <input type="text" class="form-control" id="precioKm">
                        </div>
                        <div class="col-md-3">
                            <label for="">Precio Km Extra:</label>
                            <input type="text" class="form-control" id="precioKmExtra">
                        </div>
                        <div class="col-md-12 mt-1">
                            <label for="">Observación interna:</label>
                            <textarea name="" id="" cols="30" rows="2" class="form-control" id="observacionInterna">
                        </textarea>
                        </div>
                        <div class="col-md-12 mt-1">
                            <label for="">Observación cotización:</label>
                            <textarea name="" id="" cols="30" rows="2" class="form-control" id="observaciónCotizacion">
                        </textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="">Forma de Pago</label>
                            <select name="" id="" class="form-control" id="tarjeta">
                                <option value="">Tarjeta de Credito </option>
                                <option value="">Deposito</option>
                                <option value="">Efectivo</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="">Tiempo de Entrega</label>
                            <input type="text" class="form-control" id="tiempoEntrega">
                        </div>
                        <div class="col-md-12">
                            <h6>Puntos de Muestreo</h6>
                        </div>
                        <div class="col-md-6">
                            <label for="">Punto de Muestreo</label>
                            <input type="text" id="new-todo-item" class="new-todo-item form-control" name="todo" />
                        </div>
                        <div class="col-md-6 mt-2">
                            <hr>
                            <input type="submit" id="add-todo-item" class="add-todo-item" value="Añadir" />
                        </div>
                        <div class="col-md-12">
                            <ul id="todo-list" class="todo-list">
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <label for="">Punto de Muestreo</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <table id="tablaCotizacion" class="table mt-1 ">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Parametros</th>
                                        <th scope="col">Acciónes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td scope="col">1</td>
                                        <td scope="col">Metales</td>
                                        <td scope="col">
                                            <button class="btn btn-danger btn-sm mt-1" disabled>x</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
              </div>
        </div>
        <!-- -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
{{--
           <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true" onclick="formulario(1)">
                    <button type="button" class="btn btn-primary" id="btn_primero">Anterior</button></a> --}}

             <a class="nav-link" id="nuevo" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false" onclick="formulario(2)">
                    <button type="button" class="btn btn-primary" id="bnt_dos" >Siguiente</button>
            </a>

                {{-- <a class="nav-link" id="pro" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false" onclick="formulario(2)">
                    <button type="button" class="btn btn-primary" id="bnt_dos_atras">Anterior</button>
                  </a> --}}

                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false" onclick="formulario(3)">
                    <button type="button" class="btn btn-primary" id="bnt_tres">Siguiente</button>
                  </a>


                  <a href="">
                    <button type="submit" class="btn btn-primary"  id="guardarFormulario">Guardar</button>
                  </a>
                </form>
        </div>
      </div>
    </div>
  </div>
  @stop

@endsection
@section('javascript')
<script src="{{asset('js/cotizacion/cotizacion.js')}}"></script>
<script src="{{asset('js/libs/componentes.js')}}"></script>
<script src="{{asset('js/libs/tablas.js')}}"></script>
@stop
