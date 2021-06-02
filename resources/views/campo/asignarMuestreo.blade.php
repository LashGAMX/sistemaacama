@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="fa fa-check"></i>
    Asignar Muestreo
  </h6>
  @stop

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="col-md-2">
          <input type="Nombre" class="form-control" placeholder="nombre">
        </div>
        <div class="col-md-2">
          <input type="Folio" class="form-control" placeholder="folio">
        </div>
        <div class="col-md-2">
          <select type="Mes" class="form-control" placeholder="">
            <option>mes</option>
            <option>Enero</option>
            <option>Febrero</option>
            <option>Marzo</option>
            <option>Abril</option>
            <option>Mayo</option>
            <option>Junio</option>
            <option>Julio</option>
            <option>Agosto</option>
            <option>Septiembre</option>
            <option>Octubre</option>
            <option>Noviembre</option>
            <option>Diciembre</option>
        </select>
        </div>
        <div class="col-md-2">
          <input type="Año" class="form-control" placeholder="Año">
        </div>
        <div class="col-md-2">
          <button type="button" class="btn btn-success"><i class="fa fa-search"></i> Buscar</button>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
      <div class="col-md-1">
        <button type="button" class="btn btn-basic"><i class="fa fa-search"></i> Imprimir</button>
      </div>
      <div class="col-md-1">
        <button type="button" class="btn btn-success"><i class="fa fa-search"></i> Generar</button>
      </div>
      <div class="col-md-1">
        <button type="button" class="btn btn-primary"><i class="fa fa-search"></i> Plan de muestreo</button>
      </div>
      </div>
    </div>
  
    <div class="row">
        <div class="col-md-12">
          <table class="table">
            <thead>
              <tr>
                <th scope="col">id Solicitud</th>
                <th scope="col">Folio Servicio</th>
                <th scope="col">Nombre Cliente</th>
                <th scope="col">Ap. Paterno </th>
                <th scope="col">Ap. Materno </th>
                <th scope="col">Nombre </th>
                <th scope="col">Servicio</th>
                <th scope="col">Tipo Descarga</th>
                <th scope="col">Fecha muestreo</th>
                <th scope="col">Observaciones</th>
                <th scope="col">Creado por</th>
                <th scope="col">Fecha Creacción</th>
                <th scope="col">Actualizado por</th>
                <th scope="col">Fecha Actualización</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th>
               
              </tr>
            </tbody>
          </table>
        </div>
    </div>
  </div>

@endsection  


@section('javascript')

@stop

