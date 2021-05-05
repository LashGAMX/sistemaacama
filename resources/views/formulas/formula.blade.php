@extends('voyager::master')

@section('content')

  @section('page_header')

  <div class="row">
    <div class="col-md-12">
        <div>

            <div class="row">
              <div class="col-md-8">
                <button class="btn btn-success btn-sm" ><i class="voyager-plus"></i> Crear</button>
              </div>
              <div class="col-md-4">
                <input type="search" class="form-control" placeholder="Buscar">
              </div>
            </div>
            <table class="table table-hover table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Id </th>
                        <th>Nombre formula</th>
                        <th>Nivel</th>
                        <th>Expresión</th>
                        <th>Variables</th>
                        <th>Valor</th>
                        <th>Decimal</th>
                        <th>Descripción</th>
                        <th>Modificación</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                  <td>a</td>
                  <td>a</td>
                  <td>a</td>
                  <td>a</td>
                  <td>A</td>
                  <td>a</td>
                  <td>a</td>
                  <td>a</td>
                  <td>
                    <button type="button" class="btn btn-warning">
                    <i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button>
                    <button type="button" class="btn btn-primary"><i class="voyager-external"></i> <span hidden-sm hidden-xs>ver</span> </button>
                  </td>
                </tr>
                </tbody>
            </table>
          </div>
    </div>
  </div>
  @stop

@endsection

