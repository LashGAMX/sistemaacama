@extends('voyager::master')

@section('content')

@section('page_header')

<h6 class="page-title">
    <i class="fa fa-truck-pickup"></i>
    Cadena de custodia
</h6>
@stop
<input type="text" id="idSol" value="{{$model->Id_solicitud}}" hidden>
<input type="text" id="idNorma" value="{{$model->Id_norma}}" hidden>
<div class="container-fluid"> 
    <div class="row">
        <div class="col-md-12">
            <div class="card" style="width: 100%;">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <h6>Solicitud ID: <strong>{{$model->Id_solicitud}}</strong></h6>
                            <h6>Servicio: <strong>{{$model->Servicio}}</strong></h6>
                            <h6>Tipo descarga: <strong>{{$model->Descarga}}</strong></h6>
                        </div>
                        <div class="col-md-2">
                            <h6>Norma: <strong>{{$model->Clave}}</strong></h6>
                            <h6>Cliente: <strong>{{$model->Empresa_suc}}</strong></h6>
                            <h6>Intermediario: <strong>{{@$intermediario->Nombres}}</strong></h6>
                        </div>
                        <div class="col-md-2">
                            <h6>Folio: <strong>{{$model->Folio_servicio}}</strong></h6>
                            <h6>Estado: <strong>Reporte</strong></h6>
                            <h6>Dirección: <strong>{{$direccion->Direccion}}</strong></h6>
                        </div>
                        <div class="col-md-2">
                            <h6>Fecha muestro: <strong>{{$model->Fecha_muestreo}}</strong></h6>
                            <h6>Fecha recepcion: <strong>{{$model->Fecha_recepcion}}</strong></h6>
                            <h6>Fecha emisión: <input type="date" id="fechaEmision" value="{{$proceso->Emision_informe}}"> <span id="btnSetEmision" class="fas fa-edit bg-success"></span></h6>
                          
                            <span id="mensaje" class="badge">
                            
                            </span>


                        </div>
                     
                        <div class="col-md-2">
                            <div class="form-check">
                              <label class="form-check-label" for="defaultCheck1">Supervisado</label>
                              <input class="form-check-input" id="ckSupervisado" type="checkbox" value="" id="defaultCheck1" @if (@$proceso->Supervicion == 1) checked = "true" @endif>
                            </div>
                            <div class="form-check">
                              <label class="form-check-label" for="defaultCheck1">Liberado</label>
                              <input class="form-check-input" id="ckLiberado" type="checkbox" value="" id="defaultCheck1" @if (@$proceso->Liberado == 1) checked = "true" @endif>
                            </div>
                            <div class="form-check">
                              <label class="form-check-label" for="defaultCheck1">Historial</label>
                              <input class="form-check-input" id="ckHistorial" type="checkbox" value="" id="defaultCheck1" @if (@$proceso->Historial_resultado == 1) checked = "true" @endif>
                            </div>
                            
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-success" id="btnCadena">Imprimir</button>
                            <div id="fotos" style="display: flex;"></div>
                        </div>
                    </div>
                </div>
              </div>
        </div>
    </div>
    <div class="row">
        <div class=""></div>
        <div class="col-md-12">
            <div class="card" style="width: 100%;">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <table id="tablePuntos" class="display compact cell-border " style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Punto muestreo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($puntos as $item)
                                        <tr>
                                            <td>{{$item->Id_solicitud}}</td>
                                            <td>{{$item->Punto}}</td>
                                        </tr>
                                    @endforeach
                                </tbody> 
                            </table>
                        </div>
                        <div class="col-md-6">
                            
                           <div id="divTableParametros">
                            <table id="tableParametros" class="display compact cell-border" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Parametro</th>
                                        <th>Tipo formula</th>
                                        <th>Resultado</th>
                                        <th>Liberado</th>
                                        <th>Nombre</th>
                                    </tr>
                                </thead>    
                                <tbody>
                        
                                </tbody>
                            </table>
                           </div>
                        </div>
                        <div class="col-md-3">
                            <button id="btnLiberar" class="btn-success"><i class="fas fa-square-root-alt" data-toggle="tooltip" data-placement="top" title="Liberar"></i></button>
                            @switch(Auth::user()->role->id)
                            @case(1)
                                <button id="btnRegresar" class="btn-info" onclick="regresarMuestra()" data-toggle="tooltip" data-placement="top" title="Regresar Resultado"><i class="voyager-double-left"></i></button>
                                <button id="btnReasignar" class="btn-warning" onclick="reasignarMuestra()" data-toggle="tooltip" data-placement="top" title="Cuadro de asignación"><i class="voyager-check"></i></button>
                                <button id="btnDesactivar" class="btn-danger" onclick="desactivarMuestra()" data-toggle="tooltip" data-placement="top" title="Ocultar"><i class="voyager-x"></i></button>
                                @break
                            @default
                            @if (Auth::user()->id == 14 || Auth::user()->id == 4 ||  Auth::user()->id == 12)
                                <button id="btnRegresar" class="btn-info" onclick="regresarMuestra()" data-toggle="tooltip" data-placement="top" title="Regresar Resultado"><i class="voyager-double-left"></i></button>
                                <button id="btnReasignar" class="btn-warning" onclick="reasignarMuestra()" data-toggle="tooltip" data-placement="top" title="Cuadro de asignación"><i class="voyager-check"></i></button>
                                <button id="btnDesactivar" class="btn-danger" onclick="desactivarMuestra()" data-toggle="tooltip" data-placement="top" title="Ocultar"><i class="voyager-x"></i></button>
                            @endif                           
                            @endswitch
                            <div id="divTabDescripcion">
                                <table id="tableResultado" class="display compact cell-border" style="width:100%">
                                    <thead>
                                        <tr>                                     
                                         <th>Descripcion</th>
                                         <th>Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                            
                                    </tbody>
                                </table>
                            </div>
                            <table>
                                <tr>
                                    <td>Resultado</td>
                                    <td><input type="text" style="font-size: 20px;width: 100px;color:red;" id="resDes" value="0.0"></td>
                                </tr>
                            </table>
                        </div> 
                    </div>
                </div>
              </div>
        
        </div> 
    </div>
</div>

<!-- Modal historial-->
<div class="modal fade" id="modalHistorial" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-height: 200%;"> 
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Historial</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>
            <div class="modal-body"> 
                <div class="row">
                    <div class="col-md-12">
                        <span>Lista parametros </span>
                        <div id="divTablaHist">
                          <table id="tablaLoteModal" class="table table-sm">
                              <thead class="thead-dark">
                                  <tr>
                                      <th>Id lote</th>
                                      <th>Fecha lote</th>
                                      <th>Codigo</th>
                                      <th>Parametro</th>
                                      <th>Resultado</th>
                                      <th>His</th>
                                  </tr>
                              </thead>
                              <tbody>
                                      <td>Id lote</td>
                                      <td>Fecha lote</td>
                                      <td>Codigo</td>
                                      <td>Parametro</td>
                                      <td>Resultado</td>
                                      <td>Historial</td>
                              </tbody>
                        </table>
                        </div>
                    </div>
                </div>
     
            </div>
        </div>
    </div>
</div>


<!-- The Modal -->
<div class="modal fade" id="modalImgFoto" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" style="width: 80%">
                    <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="">Foto</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="divImagen">

                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>




@endsection
    @section('javascript')
    <script src="{{ asset('public/js/supervicion/cadena/detalleCadena.js') }}?v=1.2.0"></script>
@stop

