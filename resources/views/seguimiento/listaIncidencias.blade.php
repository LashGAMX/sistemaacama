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
                </select>
            </div>
        </div>
        <div class = "col-md-2">
            <div class = "form-group">
                <label for="exampleFormControlSelect1">Submódulo</label>
                <select class="form-control" name="submodulo" id="submodulo">
                    <option value="0">Sin seleccionar</option>
                </select>
            </div>
        </div>
        <div class = "col-md-2">
            <div class = "form-group">
                <label for="exampleFormControlSelect1">Prioridad</label>
                <select class="form-control" name="prioridad" id="prioridad">
                    <option value="0">Sin seleccionar</option>
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
                <button type="button" class="btn btn-success" id="buscar">Nueva incidencia</button>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class = "row">
        <div class = "col-md-12">
           <table class="table" id="lista">
            <thead>
                    <tr>
                        <th>ID</th>
                        <th>Módulo</th>
                        <th>Submódulo</th>
                        <th>Prioridad</th>
                    </tr>
            </thead>

                    <tbody>
                   
                    </tbody>
           </table>
        </div>
    </div>
</div>

@section('javascript')
<script src="{{asset('/public/js/seguimiento/lista.js')}}?v=0.0.1"></script>
@stop

@endsection
 