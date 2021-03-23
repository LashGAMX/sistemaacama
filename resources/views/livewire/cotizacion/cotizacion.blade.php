<div>
    <div class="row">
      <div class="col-md-2">
        <button class="btn btn-success btn-sm"  data-toggle="modal" data-target="#modalCotizacionPrincipal" ><i class="voyager-plus"></i> Crear</button>
      </div>
      <div class="col-md-2">
        <input type="text" class="form-control" placeholder="Búsqueda por día">
       </div>
       <div class="col-md-2">
           <input type="date" id="fInicio" name="filtroFecha" placeholder="Fecha inicio" class="form-control" value="">
       </div>
       <div class="col-md-2">
           <input type="date" id="fInicio" name="filtroFecha" placeholder="Fecha inicio" class="form-control" value="">
       </div>
      <div class="col-md-2">
        <button type="button" class="btn btn-success " name="" id=""><i class="fa fa-search"></i> Buscar</button>
      </div>
      <div class="col-md-3">
        <input type="search" class="form-control" placeholder="Buscar">
      </div>
    </div>
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
            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalCotizacionPrincipal">
            <i class="voyager-edit"></i> <span hidden-sm hidden-xs>Editar</span> </button>
            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalCotizacionHistorico">
                <i class="voyager-list" aria-hidden="true"></i>
                <span hidden-sm hidden-xs>Historico</span> </button>
          </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <!-- ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ -->
    <!-- Modal Principal -->
    <div wire:ignore.self class="modal fade" id="modalCotizacionPrincipal" tabindex="-1" aria-labelledby="modalCotizacionPrincipal" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="width:100%">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body"> <!-- Body-->

                <ul class="nav nav-tabs" id="myTab" role="tablist">  <!-- Content Tab-->
                    <li class="nav-item">
                      <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="informacionBasica" aria-selected="true">1. Información Basica|</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">2. Parametros|</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">3. Información Cotización|</a>
                    </li>
                  </ul>
                  <div class="tab-content" id="myTabContent">
                    <!-- ******************** -->
                    <!-- ******************** -->
                    <!-- ******************** -->
                    <!-- ******************** -->
                    <!-- Tab Formulario 1-->
                    <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">

                            <div class="col-md-12">
                                <h6> <b> Datos Intermediario</b></h6>
                            </div>

                            <div class="col-md-12">
                            <select id="intermediarios" class="form-control select2">
                               @foreach($intermediarios as $intermediario)
                                <option value="{{$intermediario->Id_intermediario}}">{{$intermediario->Nombres}}</option>
                                @endforeach
                            </select>
                            </div>

                            <div class="col-md-12">
                                <h6 class="mt-0"><b>&nbsp;&nbsp;Clientes Registrados</b></h6>
                            </div>
                            <div class="col-md-9">
                                <select id="clientes" class="form-control select2" wire:model="clientes">
                                    @foreach ($cliente as $client)
                                    <option value="{{$client->Id_cliente}}">{{$client->Nombres}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <button class="btn btn-sm btn-success" wire:click="clienteAgregadoPorSeleccion">Agregar </button>
                            </div>
                           @if($clienteAgregadoPorSeleccion == false)

                            <!-- Nombre del Cliente-->
                        <div class="col-md-6">
                            <label for="">Nombre del Cliente:</label>
                            <input type="text" class="form-control">
                        </div>
                        <!-- Dirección -->
                        <div class="col-md-6">
                            <label for="">Dirección</label>
                            <input type="text" class="form-control">
                        </div>
                        @endif
                        <div class="col-md-12">
                            <h6 class="mt-1"><b>&nbsp;&nbsp;A quien va Dirijida la Cotización</b></h6>
                        </div>

                        <!-- Con Atención a -->
                        <div class="col-md-6">
                            <label for="">Con Atención a:</label>
                            <input type="text" class="form-control">
                        </div>

                        <!-- Telefono -->
                        <div class="col-md-6">
                            <label for="">Telefono:</label>
                            <input type="number" class="form-control">
                        </div>

                        <!-- Correo Electronico-->
                        <div class="col-md-6">
                            <label for="">Email:</label>
                            <input type="text" class="form-control">
                        </div>

                          <!-- Estado de la Cotización  -->
                        <div class="col-md-6">
                            <label for="">Estado de la Cotización:</label>
                            <select name="" id="" class="form-control">
                                <option value="Cancelado">Cancelado</option>
                                <option value="Cotización">Cotización</option>
                                <option value="Cotización autorizada">Cotización autorizada</option>
                            </select>
                        </div>

                        <!-- Tipo de Servicio -->
                        <div class="col-md-6">
                            <label for="">Tipo de Servicio:</label>
                            <select name="" id="" class="form-control">
                                <option value="ANÁLISIS Y MUESTREO">ANÁLISIS Y MUESTREO</option>
                                <option value="MUESTREO">MUESTREO</option>
                                <option value="ANALISIS">ANALISIS</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <!-- Tipo de Descarga -->
                            <label for="">Tipo de Descarga:</label>
                            <select name="" id="" class="form-control">
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
                            <select name="" id="" class="form-control">
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
                                <select name="" id="" class="form-control" wire:model="normaFormularioUno">
                                    @foreach ($norma as $norm)
                                    <option value="{{$norm->Id_norma}}">{{$norm->Norma}}</option>
                                    @endforeach
                                </select>
                        </div>

                        <!-- Fecha -->
                        <div class="col-md-6">
                            <label for="">Fecha:</label>
                            <input type="date" id="fInicio" name="filtroFecha" placeholder="Fecha inicio" class="form-control" value="">
                        </div>

                        <!-- Frecuencia -->
                        <div class="col-md-6">
                            <label for="">Frecuencia:</label>
                            <input type="number" class="form-control">
                        </div>

                        <!-- Tipo de Muestra -->
                        <div class="col-md-3">
                            <label for="">Tipo de Muestra:</label>
                            <select name="" id="" class="form-control">
                                <option value="INSTANTANEA">INSTANTANEA</option>
                                <option value="COMPUESTA">COMPUESTA</option>
                            </select>
                        </div>

                        <!-- Promedio -->
                        <div class="col-md-3">
                            <label for="">Promedio:</label>
                            <select name="" id="" class="form-control" wire:model="promedio">
                                <option value="MUESTREO INSTANTANEO">MUESTREO INSTANTANEO</option>
                                <option value="MENSUAL">MENSUAL</option>
                                <option value="DIARIO">DIARIO</option>
                            </select>
                        </div>

                        <!-- Puntos de Muestreo -->
                        <div class="col-md-3">
                            <label for="">Numero de Puntos de Muestreo:</label>
                            <input type="number" class="form-control" wire:model="puntosMuestreo" >
                        </div>

                        <!-- Tipo de reporte  -->
                        <div class="col-md-3">
                            <label for="">Tipo de Reporte:</label>
                            <select name="" id="" class="form-control">
                                <option value="">Opcion 1</option>
                                <option value="">Opcion 2</option>
                                <option value="">Opcion 3</option>
                            </select>
                        </div>

                        <!-- Condicciónes de Venta -->
                        <div class="col-md-12 mt-1">
                            <label for="">Condicciónes de Venta:</label>
                            <textarea name="" id="" cols="30" rows="2" class="form-control" wire:model="codiccionesVenta">
                        </textarea>
                        </div>

                        </div>
                    </div>
                    <!-- Fin Tab Formulario 1-->
                    <!-- ******************** -->
                    <!-- ******************** -->
                    <!-- ******************** -->
                    <!-- ******************** -->

                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="row">
                            <p>Pro 2020</p>
                            <p>Pro 2020 sklacmlkscascsamklcsa</p>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                        <div class="row">
                            <p>Pro 2020</p>
                            <p>Pro cmaslcmaslmclsakml202csasca0</p>
                        </div>
                    </div>

                  </div><!-- Fin del Div Content-->
             </div><!-- Fin del Body-->
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
          </form>
          </div>
        </div>
      </div>
     <!-- Fin de Modal Principal -->

    <!-- ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ -->
    <!-- Modal Principal -->
    <div wire:ignore.self class="modal fade" id="modalCotizacionHistorico" tabindex="-1" aria-labelledby="modalCotizacionHistorico" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($model as $item)
                          <td>{{$item->Cliente}}</td>
                          <td>{{$item->Folio_servicio}}</td>
                          <td>{{$item->Cotizacion_folio}}</td>
                          <td>{{$item->Empresa }}</td>
                          <td>{{$item->Servicio}}</td>
                          <td>{{$item->Fecha_cotizacion}}</td>
                          <td>{{$item->Supervicion}}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Generar Nueva Cotización</button>
              <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
          </form>
          </div>
        </div>
      </div>
     <!-- Fin de Modal Principal -->
  </div>

<script>

</script>
