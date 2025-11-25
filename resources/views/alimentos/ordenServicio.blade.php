@extends('voyager::master')

@section('content')
<style>
    #tablaSolicitud thead input {
        width: 100%;
        box-sizing: border-box;
        font-size: 12px;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row align-items-center mb-2">
                <!-- Botón Crear -->
                <div class="col-md-2">
                    <button class="btn btn-success w-50" id="btnCreate">
                        <i class="fas fa-plus"></i> Crear
                    </button>
                </div>
            
                <!-- Botón Crear Remitidas -->
                <div class="col-md-2">
                    <button class="btn btn-warning w-50" id="btnCreateIngreso" disabled>
                        <i class="fas fa-plus"></i> Crear remitidas con ingreso
                    </button>
                </div>
            
                <!-- Botón Imprimir -->
                <div class="col-md-2">
                    <button id="btnImprimir" class="btn btn-info w-50">
                        <i class="voyager-upload"></i> Imprimir
                    </button>
                </div>
            
                <!-- Botón Editar -->
                <div class="col-md-2">
                    <button class="btn btn-warning w-50" id="btnEdit">
                        <i class="voyager-pen"></i> Editar
                    </button>
                </div>
            
                <!-- Checkbox -->
                <div class="col-md-2">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="ELIMINADAS">
                        <label class="form-check-label" for="ELIMINADAS">
                            Solicitudes Canceladas
                        </label>
                    </div>
                </div>
            
                <!-- Botones solo para usuarios específicos -->
                @switch(Auth::user()->id)
                    @case(1)
                    @case(36)
                    @case(100)
                    @case(101)
                        <!-- Botón Cancelar -->
                        <div class="col-md-2">
                            <button class="btn btn-danger w-100" id="btnCancelar">
                                <i class="fas fa-ban"></i> Cancelar
                            </button>
                        </div>
            
                        <!-- Botón Duplicar -->
                        <div class="col-md-2">
                            <button class="btn btn-info w-100" id="btnDuplicar">
                                <i class="voyager-book"></i> Duplicar Solicitud
                            </button>
                        </div>
                    @break
                @endswitch
            </div>

            
                    <!-- Modal Cancelación - Bootstrap 4 -->
                        <div class="modal fade" id="modalCancelar" tabindex="-1" role="dialog" aria-labelledby="modalCancelarLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              
                              <div class="modal-header">
                                <h5 class="modal-title" id="modalCancelarLabel">Cancelar Solicitud</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              
                              <div class="modal-body">
                                <input type="text" id="inputIdSol" hidden>
                                <div class="form-group">
                                  <label for="motivo">Motivo de Cancelación</label>
                                  <textarea class="form-control" id="motivo" rows="4" placeholder="Describe el motivo..."></textarea>
                                </div>
                              </div>
                              
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-danger" id="confirmarCancelacion">Confirmar Cancelación</button>
                              </div>
                              
                            </div>
                          </div>
                        </div>
            <table id="tablaSolicitud" class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Folio</th>
                        <th>Norma</th>
                        <th>Cliente</th>
                        <th>Fecha Muestreo</th>
                        <th>Creado por</th>
                        <th>Obs</th>
                    </tr>
                    <tr> 
                        <th style="width: 50px;"><input type="text" class="form-control form-control-sm w-50" placeholder="ID" /></th>
                        <th style="width: 80px;"><input type="text" class="form-control form-control-sm w-50" placeholder="Buscar Folio" /></th>
                        <th><input type="text" placeholder="Buscar Norma" /></th>
                        <th><input type="text" placeholder="Buscar Cliente" /></th>
                        <th><input type="text" placeholder="Buscar Fecha" /></th>
                        <th><input type="text" placeholder="Buscar Creado" /></th>
                        <th><input type="text" placeholder="Buscar Actualizado" /></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>


        </div>
        <div class="col-md-12" id="divOrden"></div>
    </div>
</div>
@endsection
@section('javascript')
<script src="{{ asset('public/js/alimentos/ordenServicio.js')}}?v=0.0.5"></script>
@endsection