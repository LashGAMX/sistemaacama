@extends('voyager::master')

@section('content')

@section('page_header')


@stop
<div class="table table-sm" id="tabGenerales">
  <table id="tablaDatosGenerales">
  </table>
</div>

<h6 class="page-title">
  <i class="voyager-person"></i>
  Detalle Cliente
</h6>
<div class="row">
  <div class="col-md-5">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <h5 class="card-title"><strong>Status: </strong>@if ($clienteGen->deleted_at != 'null') Activo @else
              Inactivo @endif</h5>

          </div>
          <div class="col-md-6">
            <h5 class="card-title"><strong>Id clienteGen: </strong><span
                id="idCliente">{{$clienteGen->Id_cliente}}</span></h5>
          </div>
          <div class="col-md-6">
            <h5 class="card-title"><strong>Nombre: </strong>{{$clienteGen->Empresa}}</h5>
          </div>
          <div class="col-md-6">
            <h5 class="card-title"><strong>Intermediario: </strong>{{$clienteGen->Nombres}} {{$clienteGen->A_paterno}}
            </h5>
          </div>
        </div>
        <div class="row">
          <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#ModalSucursal" id="botonModal"><i
              class="voyager-plus"></i> Crear</button>

          <div class="col-md-12">

            <div id="SucurcalCliente">
              <table id="TableCliente"></table>
            </div>
          </div>



          <!-- Modal Sucursal  cliente-->
          <div class="modal fade" id="ModalSucursal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title" id="modalLabel"></h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form id="formEditSucursal">
                    <div class="form-group ">
                      <div class="row">
                        <div class="col-md-6">
                          <label for="deleted_at">Status</label>
                          <input type="checkbox" id="deleted_at" name="deleted_at">
                        </div>
                        <div class="col-md-6">
                          <label for="NombreM">Nombre Matrix</label>
                          <input type="checkbox" id="NombreMatrix" name="NombreMatrix">
                        </div>
                      </div>
                    </div>

                    <div class="form-group mb-3">
                      <div class="row">
                        <div class="col-md-8">
                          <label for="empresa">Empresa</label>
                          <input type="text" class="form-control" id="empresa" name="empresa" required>
                        </div>
                        <div class="col-md-6">
                          <label for="estado">Estado</label><br>
                          <select id="estado" name="estado" required>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <label for="id_siralab">Tipo</label>
                          <select class="form-control" id="tipo" name="tipo" required>
                            <option value="1">Siralab</option>
                            <option value="2">Siralab / Reporte</option>
                            <option value="0">No Seleccionado</option>
                          </select>
                        </div>
                      </div>
                    </div>


                  </form>

                </div>
                <div class="modal-footer">
                  <button class="btn btn-primary" id="guardar">Guardar Sucursal </button>
                  <button class="btn btn-primary" id="cambios">Guardar cambios </button>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
              </div>
            </div>
          </div>
          <!-- Modal -->
          <div class="modal fade" id="Modaleditar" tabindex="-1" role="dialog" aria-labelledby="ModaleditarLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="ModaleditarLabel">Editar</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="form-group">
                    <div class="form-check">
                      <input type="checkbox" class="form-check-input" id="estado">
                      <label class="form-check-label" for="estado">Estado</label>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputText">RFC</label>
                    <input type="text" class="form-control" id="inputText" placeholder="Ingrese RFC aquí">
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                  <button type="button" class="btn btn-primary" id="guardarCambios">Guardar cambios</button>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>

  </div>
  <div class="col-mb-7">
    <div class="card" id="generalidades" style="visibility: hidden;">
      <div class="card-body ">
        <!--eliminar succes cuando finalicen las pruebas-->
        <div class="row">
          <div class="container mt-4">
            <h2><i class="fas fa-search"></i>Información Adicional<i class="fas fa-building"></i> </h2>
          </div>
          <br>
          <div class="col-md-4">
            <strong>
              <h6 id="idSucursal">Id_cliente: </h6>
            </strong>
          </div>
          <div class="col-md-4">
            <strong>
              <h6 id="idempresa">Sucursal: </h6>
            </strong>
          </div>
          <div class="col-md-4">
            <strong>
              <h6 id="idestado">Estado: </h6>
            </strong>
          </div>
        </div>
        <div class="container">

          <!-- Encabezado del tab  -->
          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link " data-toggle="tab" href="#menu0">Reporte</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#menu1">Siralab</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#menu2">Datos Generales</a>
            </li>
          </ul>
          <!-- Modal General -->
          <div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="modalLabel"></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form id="editRFCForm">
                    <div class="form-group">
                      <input type="checkbox" id="modalStatus">
                      <label for="modalStatus">Status</label>
                    </div>
                    <div class="form-group">
                      <input type="text" name="rfc" id="modalText" class="form-control" placeholder="Escribe el RFC">
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                  <button type="button" class="btn btn-primary" id="saveButton">Guardar</button>
                </div>
              </div>
            </div>
          </div>
          <!-- Tab Paneles -->
          <div class="tab-content">
            <div id="menu0" class="container tab-pane "><br>
              <div id="accordion">
                <div class="card">
                  <div class="card-header">
                    <a class="h4 font-italic text-warning collapsed card-link" data-toggle="collapse"
                      href="#collapseOne">
                      RFC
                    </a>
                  </div>
                  <div id="collapseOne" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                      <button class="btn btn-success" data-toggle="collapse" data-target="#formCollapse">Crear</button>
                      <div id="formCollapse" class="collapse">
                        <form id="rfcForm">
                          <div class="container">
                            <div class="row">
                              <div class="col-md-6 d-flex align-items-center">
                                <input type="checkbox" value="true" id="idstatus1" checked>
                                <label for="idstatus1" class="ml-2">Status</label>
                                <input type="text" name="Rfc" id="RfC" class="form-control ml-2"
                                  placeholder="Escribe el Rfc">
                              </div>
                              <div class="col-md-6 d-flex align-items-center justify-content-end">
                                <button type="submit" id="Grfc" class="btn btn-success ml-2">Guardar</button>
                              </div>
                            </div>
                          </div>
                        </form>


                      </div>
                      <div class="container">
                        <table id="TableRFC" class="display"></table>
                      </div>
                    </div>
                  </div>

                </div>
                <div class="card">
                  <div class="card-header">
                    <a class=" h4 font-italic text-secondary collapsed card-link" data-toggle="collapse"
                      href="#collapseTwo">
                      Dirección Reporte
                    </a>
                  </div>
                  <div id="collapseTwo" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                      <button class="btn btn-success" data-toggle="collapse" data-target="#2">Crear</button>
                      <div id="2" class="collapse">
                        <form id="direcForm">
                          <div class="container">
                            <div class="row">
                              <div class="col-md-6 d-flex align-items-center">
                                <input type="checkbox" value="true" id="idstatus2" checked>
                                <label for="idstatus2" class="ml-2">Status</label>
                                <input type="text" name="Rfc" id="direccion" class="form-control ml-2"
                                  placeholder="Escribe La DIRECCION">
                              </div>
                              <div class="col-md-6 d-flex align-items-center justify-content-end">
                                <button type="submit" id="GDIR" class="btn btn-success ml-2">Guardar</button>
                              </div>
                            </div>
                          </div>
                        </form>
                      </div>
                      <div class="container">
                        <table id="TablaDirReport" class="display"></table>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card">
                  <div class="card-header">
                    <a class="h4 font-italic text-danger collapsed card-link" data-toggle="collapse"
                      href="#collapseThree">
                      Punto Muestreo
                    </a>
                  </div>
                  <div id="collapseThree" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                      <button class="btn btn-success" data-toggle="collapse" data-target="#3">Crear</button>
                      <div id="3" class="collapse">
                        <form id="PuntoMuestreo">
                          <div class="container">
                            <div class="row">
                              <div class="col-md-6 d-flex align-items-center">
                                <input type="checkbox" value="true" id="idstatus3" checked>
                                <label for="idstatus3" class="ml-2">Status</label>
                                <input type="text" name="punto" id="punto" class="form-control ml-2"
                                  placeholder="Escribe el Punto de Muestreo">
                              </div>
                              <div class="col-md-6 d-flex align-items-center justify-content-end">
                                <button type="submit" id="GPUN" class="btn btn-success ml-2">Guardar</button>
                              </div>
                            </div>
                          </div>
                        </form>
                      </div>
                      <div class="container">
                        <table id="TablaPM" class="display"></table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div id="menu1" class="container tab-pane fade"><br>
              <div id="accordion">
                <div class="card">
                  <div class="card-header">
                    <a class="h4 font-italic text-warning collapsed card-link" data-toggle="collapse" href="#collapse1">
                      Titulo de Concesión
                    </a>
                  </div>
                  <div id="collapse1" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                      <button class="btn btn-success" data-toggle="collapse" data-target="#4">Crear</button>
                      <div id="4" class="collapse">
                        <form id="concesion">
                          <div class="container">
                            <div class="row">
                              <div class="col-md-6 d-flex align-items-center">
                                <input type="checkbox" value="true" id="idstatus4" checked>
                                <label for="idstatus4" class="ml-2">Status</label>
                                <input type="text" name="punto" id="titulo" class="form-control ml-2"
                                  placeholder="Escribe el Titulo de Concesión">
                              </div>
                              <div class="col-md-6 d-flex align-items-center justify-content-end">
                                <button type="submit" id="GPUN" class="btn btn-success ml-2">Guardar</button>
                              </div>
                            </div>
                          </div>
                        </form>
                      </div>
                      <div class="container">
                        <table id="TablaConcesión" class="display"></table>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card">
                  <div class="card-header">
                    <a class="h4 font-italic text-secondary collapsed card-link" data-toggle="collapse"
                      href="#collapse2">
                      Dirección Reporte
                    </a>
                  </div>
                  <div id="collapse2" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                      <button type="button" class="btn btn-success" id="CrearDir" data-toggle="modal"
                        data-target="#ModalDirSir">
                        Crear
                      </button>
                      <!-- Modal -->
                      <div class="modal fade" id="ModalDirSir" tabindex="-1" role="dialog"
                        aria-labelledby="ModalDirSirLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="ModalDirSirLabel">Editar Dirección de Reporte Siralab</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <form id="formModalDirSir">
                                <!-- Estado Checkbox -->
                                <div class="mb-3 form-check">
                                  <input type="checkbox" class="form-check-input" id="Status">
                                  <label class="form-check-label" for="Status">Estado</label>
                                </div>

                                <!-- Fila 1: Título y Calle -->
                                <div class="form-row mb-3">
                                  <div class="col-md-6">
                                    <label for="NoTitulo" class="form-label">Título</label>
                                    <input type="text" class="form-control" id="NoTitulo">
                                  </div>
                                  <div class="col-md-6">
                                    <label for="calle" class="form-label">Calle</label>
                                    <input type="text" class="form-control" id="calle">
                                  </div>
                                </div>

                                <!-- Fila 2: Número Exterior, Interior y Estado -->
                                <div class="form-row mb-3">
                                  <div class="col-md-6">
                                    <label for="numExt" class="form-label">Número Exterior</label>
                                    <input type="text" class="form-control" id="numExt">
                                  </div>
                                  <div class="col-md-6">
                                    <label for="numInt" class="form-label">Número Interior</label>
                                    <input type="text" class="form-control" id="numInt">
                                  </div>
                                  <div class="col-md-6">
                                    <label for="estadodir" class="form-label">Estado</label>
                                    <input type="text" class="form-control" id="estadodir">
                                  </div>
                                </div>

                                <!-- Fila 3: Colonia, C.P. y Ciudad -->
                                <div class="form-row mb-3">
                                  <div class="col-md-6">
                                    <label for="colonia" class="form-label">Colonia</label>
                                    <input type="text" class="form-control" id="colonia">
                                  </div>
                                  <div class="col-md-6">
                                    <label for="cp" class="form-label">C.P.</label>
                                    <input type="text" class="form-control" id="cp">
                                  </div>
                                  <div class="col-md-6">
                                    <label for="ciudad" class="form-label">Ciudad</label>
                                    <input type="text" class="form-control" id="ciudad">
                                  </div>
                                </div>

                                <!-- Fila 4: Localidad -->
                                <div class="form-row mb-3">
                                  <div class="col-md-12">
                                    <label for="localidad" class="form-label">Localidad</label>
                                    <input type="text" class="form-control" id="localidad">
                                  </div>
                                </div>
                              </form>
                            </div>
                            <div class="modal-footer">
                              <!-- Botón de acción -->
                              <button type="button" class="btn btn-success" id="btnModalCrear">Crear</button>

                              <button type="button" class="btn btn-primary" id="btnModal">Guardar Cambios</button>
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            </div>
                          </div>
                        </div>
                      </div>


                      <div class="container">
                        <table id="TablaDireccionSiralab" class="display"></table>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card">
                  <div class="card-header">
                    <a class="h4 font-italic text-danger collapsed card-link" data-toggle="collapse" href="#collapse3">
                      Punto Muestreo
                    </a>
                  </div>
                  <div id="collapse3" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                      <button class="btn btn-success" data-toggle="collapse" data-target="#6">Crear</button>
                      <div id="6" class="collapse">
                        aqui va mi formulario
                      </div>
                      <div class="container">
                        <table id="TablePuntoSiralab" class="display"></table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div id="menu2" class="container tab-pane fade"><br>
              <table id="Tablageneral" class="table table-striped">
                <!-- La tabla se llenará con datos aquí -->
              </table>
              <!-- Modal -->
              <div class="modal fade" id="DatosModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="DatosModalLabel">Formulario de Datos</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <form id="FormDatoGen">
                        <div class="row">
                          <!-- Primera columna -->
                          <div class="col-md-6">
                          
                            <div class="form-group">
                              <label for="nombre">Nombre</label>
                              <input type="text" class="form-control" id="nombre" placeholder="Nombre">
                            </div>
                            <div class="form-group">
                              <label for="departamento">Departamento</label>
                              <input type="text" class="form-control" id="departamento" placeholder="Departamento">
                            </div>
                            <div class="form-group">
                              <label for="puesto">Puesto</label>
                              <input type="text" class="form-control" id="puesto" placeholder="Puesto">
                            </div>
                          </div>

                          <!-- Segunda columna -->
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="email">Email</label>
                              <input type="email" class="form-control" id="email" placeholder="Email">
                            </div>
                            <div class="form-group">
                              <label for="celular">Celular</label>
                              <input type="text" class="form-control" id="celular" placeholder="Celular">
                            </div>
                            <div class="form-group">
                              <label for="telefono">Teléfono</label>
                              <input type="text" class="form-control" id="telefono" placeholder="Teléfono">
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                      <button type="button" class="btn btn-primary" id="GDGen">Guardar cambios</button>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>

        </div>
      </div>

    </div>
  </div>

  @endsection

  @section('javascript')
  <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
  <script src="{{ asset('/public/js/cliente/cliente_detalle.js')}}?v=0.0.1"></script>
  @stop