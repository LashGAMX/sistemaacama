<div>
    <div class="row">
      <div class="col-md-3">
        <button class="btn btn-success btn-sm"  data-toggle="modal" data-target="#modalCotizacionPrincipal" ><i class="voyager-plus"></i> Crear</button>
      </div>
      <div class="col-md-3">
      <input type="date" id="fInicio" name="filtroFecha" placeholder="Fecha inicio" class="form-control" value="">
      </div>
      <div class="col-md-2">
        <button type="button" class="btn btn-success " name="" id=""><i class="fa fa-search"></i> Buscar</button>
      </div>
      <div class="col-md-4">
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
                <th>Fecha Cotización</th>
                <th>Supervicion</th>
                <th>Acciónes</th>
            </tr>
        </thead>
        <tbody>
          <td>1</td>
          <td>2</td>
          <td>3</td>
          <td>4</td>
          <td>5</td>
          <td>6</td>
          <td>7</td>
          <td>8</td>
          <td>9</td>
          <td>
            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalCotizacionPrincipal">
            <i class="voyager-edit"></i> <span hidden-sm hidden-xs>Editar</span> </button>
            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalCotizacionHistorico">
                <i class="voyager-list" aria-hidden="true"></i>
                <span hidden-sm hidden-xs>Historico</span> </button>
          </td>
        </tr>
        </tbody>
    </table>
    <!-- ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ -->
    <!-- Modal Principal -->
    <div wire:ignore.self class="modal fade" id="modalCotizacionPrincipal" tabindex="-1" aria-labelledby="modalCotizacionPrincipal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <!-- Body-->
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true"> <b style="color:black;"> 1. Información Basica|<b> </a>
                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false"><b style="color: black;">2. Parametros|<b></a>
                        <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false"><b style="color: black;">3. Datos de Cotización|<b></a>
                    </div>
                </nav>

                <div class="tab-content" id="nav-tabContent">

                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <div class="col-md-12">
                            <h6 class="mt-2"><b>&nbsp;&nbsp;Intermediarios</b></h6>
                        </div>
                        <div class="col-md-12">
                            <select id="intermediarios" class="form-control mt-2">
                                <option value="">Luis Alberto</option>
                                <option value="">Katerin </option>
                            </select>
                        </div>
                        <!--  -->
                        <div class="col-md-12">
                            <h6 class="mt-1"><b>&nbsp;&nbsp;Clientes Registrados</b></h6>
                        </div>
                        <div class="col-md-12">
                            <select id="intermediarios" class="form-control mt-1">
                                <option value="">Agua Puebla S.A. de C.V.</option>
                                <option value="">Minerales el Sur S.A. de C.V.</option>
                            </select>
                        </div>
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
                            <label for="">Telfono:</label>
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
                                <option value="">Opcion 1</option>
                                <option value="">Opcion 2</option>
                                <option value="">Opcion 3</option>
                            </select>
                        </div>
                        <!-- Tipo de Servicio -->
                        <div class="col-md-6">
                            <label for="">Tipo de Servicio:</label>
                            <select name="" id="" class="form-control">
                                <option value="">Opcion 1</option>
                                <option value="">Opcion 2</option>
                                <option value="">Opcion 3</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <!-- Tipo de Descarga -->
                            <label for="">Tipo de Descarga:</label>
                            <select name="" id="" class="form-control">
                                <option value="">Opcion 1</option>
                                <option value="">Opcion 2</option>
                                <option value="">Opcion 3</option>
                            </select>
                        </div>
                        <!-- Clasificación de la Norma -->
                        <div class="col-md-6">
                            <label for="">Clasifiación de la Norma:</label>
                            <select name="" id="" class="form-control">
                                <option value="">Opcion 1</option>
                                <option value="">Opcion 2</option>
                                <option value="">Opcion 3</option>
                            </select>
                        </div>
                        <!-- Norma -->
                        <div class="col-md-6">
                            <label for="">Norma:</label>
                            <select name="" id="" class="form-control">
                                <option value="">Opcion 1</option>
                                <option value="">Opcion 2</option>
                                <option value="">Opcion 3</option>
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
                                <option value="">Opcion 1</option>
                                <option value="">Opcion 2</option>
                                <option value="">Opcion 3</option>
                            </select>
                        </div>
                        <!-- Promedio -->
                        <div class="col-md-3">
                            <label for="">Promedio:</label>
                            <select name="" id="" class="form-control">
                                <option value="">Opcion 1</option>
                                <option value="">Opcion 2</option>
                                <option value="">Opcion 3</option>
                            </select>
                        </div>
                        <!-- Puntos de Muestreo -->
                        <div class="col-md-3">
                            <label for="">Numero de Puntos de Muestreo:</label>
                            <input type="number" class="form-control">
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
                            <textarea name="" id="" cols="30" rows="2" class="form-control">
                        </textarea>
                        </div>
                        <!-- Boton Guardar -->
                        <div class="col-md-12 mt-1">
                            <button class="btn  btn-success">Guardar</button>
                        </div>
                    </div> <!-- Fin tab-content  01 -->
</div>
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                            <p>Testing One</p>
                            {{-- <div class="col-md-6">
                                <div class="row ">
                                    <div class="col-md-6">
                                        <label for="">Norma</label>
                                        <select name="" id="" class="form-control">
                                            <option value="">Norma 001</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <br>
                                        <button class="btn btn-primary mt-2"> Parametros</button>
                                    </div>
                                    <div class="col-md-12">
                                        <br>
                                        <input type="checkbox">Coliformes Fecales
                                        <br>
                                        <input type="checkbox">Huevos de Helminto
                                    </div>
                                </div>
                            </div> --}}
                            {{-- <div class="col-md-6">
                                <div class="col-md-12">
                                    <label for="">Filtro</label>
                                    <select name="" id="" class="form-control">
                                        <option value="">Clasificación</option>
                                    </select>
                                 </div> --}}
                                    {{-- <div class="col-12">
                                        <div class="form-group">
                                            <div class="bootstrap-duallistbox-container row moveonselect moveondoubleclick">
                                                <div class="box1 col-md-6"> <label for="bootstrap-duallistbox-nonselected-list_" style="display: none;"></label> <span class="info-container"> <span class="info">Showing all 6</span> <button type="button" class="btn btn-sm clear1" style="float:right!important;">show all</button> </span> <input class="form-control filter" type="text" placeholder="Filter">
                                                    <div class="btn-group buttons"> <button type="button" class="btn moveall btn-outline-secondary" title="Move all">&gt;&gt;</button> </div> <select multiple="multiple" id="bootstrap-duallistbox-nonselected-list_" name="_helper1" style="height: 102px;">
                                                        <option>Parametro A</option>
                                                        <option>Parametro B</option>
                                                        <option>Parametro C</option>
                                                    </select>
                                                </div>
                                                <div class="box2 col-md-6"> <label for="bootstrap-duallistbox-selected-list_" style="display: none;"></label> <span class="info-container"> <span class="info">Showing all 1</span> <button type="button" class="btn btn-sm clear2" style="float:right!important;">show all</button> </span> <input class="form-control filter" type="text" placeholder="Filter">
                                                    <div class="btn-group buttons"> <button type="button" class="btn removeall btn-outline-secondary" title="Remove all">&lt;&lt;</button> </div> <select multiple="multiple" id="bootstrap-duallistbox-selected-list_" name="_helper2" style="height: 102px;">
                                                        <option selected="">Parametro A</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <select class="duallistbox" multiple="multiple" style="display: none;">
                                                <option selected="">Parametro D</option>
                                            </select>
                                        </div>
                                    </div> --}}
                    </div><!-- Fin tab-content 02 -->

                    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                        <p>Testing Three</p>
                        {{-- <div class="row">
                            <div class="col-md-12">
                                <h6> <b> Datos Intermediario</b></h6>
                            </div>
                            <div class="col-md-3">
                                <h6> <b>Intermediario:</b>Alberto </h6>
                            </div>
                            <div class="col-md-3">
                                <h6> <b>Estado de Cotización:</b>Cotización </h6>
                            </div>
                            <div class="col-md-3">
                                <h6> <b>Ser nombre:</b>Análisis y Muestreo </h6>
                            </div>
                            <div class="col-md-3">
                                <h6> <b>Tipo Descarga:</b>Residual </h6>
                            </div>
                            <div class="col-md-12">
                                <hr>
                                <h6> <b> Cliente:</b></h6>
                            </div>
                            <div class="col-md-6">
                                <label for="">Nombre del Cliente</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="">Con Atención a:</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="">Telefono:</label>
                                <input type="number" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="">Email:</label>
                                <input type="number" class="form-control">
                            </div>
                            <div class="col-md-12 mt-1">
                                <label for="">Dirección de Cotización:</label>
                                <textarea name="" id="" cols="30" rows="1" class="form-control">
                            </textarea>
                            </div>
                            <div class="col-md-12">
                                <hr>
                                <h6> <b> Datos de Cotización:</b></h6>
                            </div>
                            <div class="col-md-5">
                                <h6>Norma:<b> NOM-001-SEMRNAT-1996</b></h6>
                            </div>
                            <div class="col-md-7">
                                <p><b>Muestreo</b> 18 HRS - <b>Numero de Tomas</b> 6 <b>Fecha de Muestreo:</b> 13/03/2021</p>
                            </div>
                            <div class="col-md-3">
                                <label for=""># tomas Muestreo:</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label for="">Viaticos:</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label for="">Gastos Paqueteria:</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label for="">Gasto Adicional:</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label for="">N Servicio:</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label for="">Km Extra:</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label for="">Precio Km:</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label for="">Precio Km Extra:</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-12 mt-1">
                                <label for="">Observación interna:</label>
                                <textarea name="" id="" cols="30" rows="2" class="form-control">
                            </textarea>
                            </div>
                            <div class="col-md-12 mt-1">
                                <label for="">Observación cotización:</label>
                                <textarea name="" id="" cols="30" rows="2" class="form-control">
                            </textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="">Forma de Pago</label>
                                <select name="" id="" class="form-control">
                                    <option value="">Tarjeta de Credito </option>
                                    <option value="">Deposito</option>
                                    <option value="">Efectivo</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="">Tiempo de Entrega</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-12">
                                <h6>Puntos de Muestreo</h6>
                            </div>
                            <div class="col-md-4">
                                <label for="">Numero</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="">Punto de Muestreo</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-4 mt-2">
                                <hr>
                                <button class="btn btn-primary btn-sm mt-1">Añadir</button>
                            </div>
                            <div class="col-md-12">
                                <table id="tablaCotizacion" class="table mt-1 ">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Punto de muestreo</th>
                                            <th scope="col">Acciónes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td scope="col">1</td>
                                            <td scope="col">San Juan del Rio Queretaro Puente de Agua Potable</td>
                                            <td scope="col">
                                                <button class="btn btn-danger btn-sm mt-1">x</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success">Guardar</button>
                            </div> --}}
                    </div> <!-- Fin tab-content 03 -->

                </div>  <!-- Fin tab-content -->
                <!-- Fin del Body-->
            </div>
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

                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
          </form>
          </div>
        </div>
      </div>
     <!-- Fin de Modal Principal -->
  </div>



