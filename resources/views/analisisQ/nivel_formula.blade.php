@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="fa fa-level-up-alt"></i>
    Nivel formulas
  </h6>
  @stop
 
 <div class="container-fluid">
  <div class="row">
    <div class="col-md-4">
      <a href="{{url('/admin/analisisQ/formulas/crear_nivel')}}" class="btn btn-success"><i class="voyager-plus"></i> Crear</a>
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
                    <th>Nombre</th>
                    <th>Nivel</th>
                    <th>Resultado</th>
                </tr>
            </thead>
            <tbody>
              @foreach ($nivel as $item) 
              <td>{{$item->Id_formulaNivel}}</td>
              <td>{{$item->Nombre}}</td>
              <td>{{$item->Nivel}}</td>
              <td>{{$item->Resultado}}</td>
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




