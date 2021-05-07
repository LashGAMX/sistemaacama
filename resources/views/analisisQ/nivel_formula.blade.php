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
                    <th>Formula</th>
                    <th>Nivel</th>
                    <th>Expresión</th>
                    <th>Variables</th>
                    <th>Valor</th>
                    <th>Decimal</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
            
            </tbody>
        </table>
    </div>
  </div>
  </div> 

@endsection  




