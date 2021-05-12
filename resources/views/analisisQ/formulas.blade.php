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
                  @foreach ($formulas as $item) 
                    <th>Parametro</th>
                    <th>Técnica</th>
                    <th>Area de análisis</th>
                    <th>Formula</th>
                    <th>Formula Sistema</th>
                                       </tr>
            </thead>
            <tbody>
              <td>{{$item->Id_parametro}}</td>
              <td>{{$item->Id_tecnica}}</td>
              <td>{{$item->Id_area}}</td>
              <td>{{$item->formula}}</td>
              <td>{{$item->formula_sistema}}</td>
              <td>
                <button type="button" class="btn btn-warning">
                <i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button>
                <button type="button" class="btn btn-primary"><i class="voyager-external"></i> <span hidden-sm hidden-xs>ver</span> </button>
              </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
  </div>
  </div> 

@endsection  




