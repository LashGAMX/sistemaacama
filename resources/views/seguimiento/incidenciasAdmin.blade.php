@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title">
    <i class="voyager-window-list"></i>
   Lista de Incidencias
</h6>
  @stop
<div class = "container-fluid">
    <div class = "row">
        <div class = "col-md-2">
            <div class = "form-group">
                <label for="exampleFormControlSelect1">Módulo</label>
                <select class="form-control" name="modulo" id="modulo">
                    <option value="0">Sin seleccionar</option>
                    @foreach ($modulos as $item)
                        <option value="{{$item->id}}">{{$item->title}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class = "col-md-2">
            <div class = "form-group">
                <label for="exampleFormControlSelect1">Submódulo</label>
                <div id="divSubmodulo">
                    <select class="form-control" name="submodulo" id="submodulo">
                        <option value="0">Sin seleccionar</option>
                    </select>
                </div>
                
            </div>
        </div>
        <div class = "col-md-2">
            <div class = "form-group">
                <label for="exampleFormControlSelect1">Prioridad</label>
                <select class="form-control" name="prioridad" id="prioridad">
                    <option value="0">Sin seleccionar</option>
                   @foreach ($prioridad as $item)
                       <option value="{{$item->Id_prioridad}}">{{$item->Prioridad}}</option>
                   @endforeach
                </select>
            </div>
        </div>
        <div class = "col-md-2">
            <div class = "form-group">
                <label for="exampleFormControlSelect1">Estado</label>
                <select class="form-control" name="estado" id="estado">
                    <option value="0">Sin seleccionar</option>
                   @foreach ($estado as $item)
                       <option value="{{$item->Id_estado}}">{{$item->Estado}}</option>
                   @endforeach
                </select>
            </div>
        </div>
        <div class = "col-md-2">
            <div class = "form-group">
                <button type="button" class="btn btn-info" id="buscar" onclick="buscar()">buscar</button>
            </div>
        </div>
        <div class = "col-md-2">
            <div class = "form-group">
                <button type="button" class="btn btn-success" id="nueva">Nueva incidencia</button>
            </div>
        </div>
       
    </div>
</div>

<div class="container-fluid">
    <div class = "row">
        <div class = "col-md-12">
            <div id="tabla">
                <table class="table" id="lista">
                    <thead>
                            <tr>
                                <th>ID</th>
                                <th>Módulo</th>
                                <th>Submódulo</th>
                                <th>Prioridad</th>
                                <th>Estado</th>
                                <th>Usuario</th>
                                <th>Descripcion</th>
                                <th>Fecha</th>
                            </tr>
                    </thead>
        
                            <tbody>
                            @foreach ($model as $item)
                                <tr>
                                        <td>{{$item->Id_incidencia}}</td>
                                        <td>{{$item->Modulo}}</td>
                                        <td>{{$item->Submodulo}}</td>
                                        <td>{{$item->Prioridad}}</td>
                                        <td>{{$item->Estado}}</td>
                                        <td>{{$item->Usuario}}</td>
                                        <td>{{$item->Descripcion}}</td>
                                        <td>{{$item->created_at}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                   </table>
            </div>
           
        </div>
    </div>
</div>

<div class="modal fade" id="modalIncidencia" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="width: 70%">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detalle Incidencia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div id="divIdIncidencia"></div>
                  <div class="modal-body"> 
                      <div class="row">
                            <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Modulo</label>
                                        <div id="divModulo"></div>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Submodulo</label>
                                        <div id="divSubmoduloModal"></div>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Prioridad</label>
                                        <div id="divPrioridad"></div>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Fecha</label>
                                        <div id="divFecha"></div>
                                        </select>
                                    </div>
                            </div> 
                        </div>
                        <div class="row">
                        <div class="col-md-6">
                            <label for="">Descripcion</label>
                            <div id="divDescripcion"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="">Observacion</label>
                            <div id="divObservacion">
                               
                            </div>
                        </div>
                    </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Estado</label>
                                    <div id="divEstado"></div>
                                    <select id="estado">
                                            <option value="0">Seleciona uno</option>
                                        @foreach ($estado as $item)
                                            <option value="{{$item->Id_estado}}">{{$item->Estado}}</option>
                                        @endforeach
                                    </select>    
                                </div>
                                    <div class="col-md-6">
                                        <label for="">Imagen</label>
                                        <div id="divImagen"></div>    
                                    </div>
                            </div>
                  </div>
                  <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" onclick="update()" id="update" class="btn btn-primary">Guardar</button>
                  </div>
                </div>
              </div>
              
            </div>     
        </div>



                <style>
                    .zoom {
                    transition: transform .2s; 
                    }
                        .zoom:hover {
                        transform: scale(10); 
                    }
                </style>

@section('javascript')
<script src="{{asset('/public/js/seguimiento/admin.js')}}?v=0.0.1"></script>
@stop

@endsection
 