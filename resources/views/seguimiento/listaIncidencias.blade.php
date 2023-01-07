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
                       <option class="{{$item->Id_prioridad}}">{{$item->Prioridad}}</option>
                   @endforeach
                </select>
            </div>
        </div>
        <div class = "col-md-3">
            <div class = "form-group">
                <button type="button" class="btn btn-info" id="buscar">buscar</button>
            </div>
        </div>
        <div class = "col-md-3">
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
                                <th>Descripcion</th>
                            </tr>
                    </thead>
        
                            <tbody>
                                <tr>
                                    @foreach ($model as $item)
                                        <td>{{$item->Id_incidencia}}</td>
                                        <td>{{$item->Modulo}}</td>
                                        <td>{{$item->Submodulo}}</td>
                                        <td>{{$item->Prioridad}}</td>
                                        <td>{{$item->Estado}}</td>
                                        <td>{{$item->Descripcion}}</td>
                                    @endforeach
                                </tr>
                           
                            </tbody>
                   </table>
            </div>
           
        </div>
    </div>
</div>

@section('javascript')
<script src="{{asset('/public/js/seguimiento/lista.js')}}?v=0.0.1"></script>
@stop

@endsection
 