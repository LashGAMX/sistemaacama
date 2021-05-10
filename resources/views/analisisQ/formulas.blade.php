@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="voyager-code"></i>
    Formulas
  </h6>
  @stop

 <div class="container-fluid">
  <div class="row">
    <div class="col-md-4">
      <a href="{{url('/admin/analisisQ/formulas/crear')}}" class="btn btn-success"><i class="voyager-plus"></i> Crear</a>
      <a href="{{url('/admin/analisisQ/formulas/nivel')}}" class="btn btn-primary"><i class="fa fa-level-up-alt"></i> Nivel formula</a>
    </div>
    <div class="col-md-4">

    </div>
    <div class="col-md-4">
      <input type="search" class="form-control" placeholder="Buscar">
    </div>

    <div class="col-md-12">
      <table class="table table-hover table-striped">
        <thead class="thead-dark">
                <tr>
                    <th>Id </th>
                    <th>Parametro</th>
                    <th>Tecnica</th>
                    <th>Area de análisis</th>
                    <th>Tipo de foprmula</th>
                    <th>Formula</th>
                                       </tr>
            </thead>
            <tbody>
              <td>1</td>
              <td>Arsenico</td>
              <td>Espectrofotométrico</td>
              <td>Absorcion atomica</td>
              <td>Básico</td>
              <td>y=mx+b</td>
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

@endsection  




