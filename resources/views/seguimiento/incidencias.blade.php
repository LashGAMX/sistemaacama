@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title">
    <i class="voyager-window-list"></i>
    Incidencias
</h6>
  @stop
<div class = "container-fluid">
    <div class = "row">
        <div class="col-md-6">
            <div class = "col-md-3">
                <div class = "form-group">
                    <label for="exampleFormControlSelect1">Módulo</label>
                    <select class="form-control" name="modulo" id="modulo">
                        <option value="0">Sin seleccionar</option>
                    </select>
                </div>
            </div>
            <div class = "col-md-3">
                <div class = "form-group">
                    <label for="exampleFormControlSelect1">Submódulo</label>
                    <select class="form-control" name="submodulo" id="submodulo">
                        <option value="0">Sin seleccionar</option>
                    </select>
                </div>
            </div>
            <div class = "col-md-3">
                <div class = "form-group">
                    <label for="exampleFormControlSelect1">Prioridad</label>
                    <select class="form-control" name="prioridad" id="prioridad">
                        <option value="0">Sin seleccionar</option>
                        <option value="5">Urgente</option>
                        <option value="4">Alta</option>
                        <option value="3">Mediar</option>
                        <option value="2">Baja</option>
                        <option value="1">Puede esperar</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class = "row">
        <div class = "col-md-6">
            <div class = "form-group">
                <label for="exampleFormControlTextarea1">Descripcion</label>
                <textarea class="form-control" name="description" id="description">
                </textarea>
            </div>
        </div>
        <div class = "col-md-6">
            <div class = "form-group">
                <label for="exampleFormControlTextarea1">Imagen de apoyo</label>
                <input type="file" id="imagen">
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class = "row">
        <div class = "col-md-6">
            <div class = "form-group">
                <button class="btn btn-success" type="button" id="enviar">enviar</button>
            </div>
        </div>
    </div>
</div>


<!--Preguntas frecuentes-->
<div class="container-fluid">
    <div class = "row">
        <div class = "col-md-12">
            <div class = "form-group">
                <label for="exampleFormControlTextarea1">Preguntas frecuentes.</label>
    </div>
</div>
<div class="container-fluid">
    <div class = "row">
        <div class = "col-md-12">
            <div class = "form-group">
                <a>Describe el problema o situación que causó el error.</a>
    </div>
</div>
 
@section('javascript')
<script src="{{asset('/public/js/seguimiento/insidencias.js')}}?v=0.0.1"></script>
@stop

@endsection
 
