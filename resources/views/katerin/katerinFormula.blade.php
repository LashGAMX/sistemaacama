@extends('voyager::master')

@section('content')

@section('page_header')
<div class="col-md-8">
   <a href="{{url('admin/katerin2')}}"><button class="btn btn-success btn-sm" wire:click="setBtn" ><i class="voyager-plus"></i> Crear</button> </a>
</div>
<div class="col-md-4">
                <input type="search" class="form-control" placeholder="Buscar">
              </div>
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
@stop

@endsection
