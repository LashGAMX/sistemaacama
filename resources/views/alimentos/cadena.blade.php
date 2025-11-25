@extends('voyager::master')

@section('content')

@section('page_header')

<h6 class="page-title">
    <i class="fa fa-truck-pickup"></i>
    Cadena de custodia
</h6>
@stop

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">

          <table id="tableCadena" class="display compact cell-border" style="width:100%">
              <thead>
                  <tr>
                      <th>Id</th>
                      <th>Folio Servicio</th>
                      <th>Fecha Muestreo</th>
                      <th>Fecha Recepcion</th>
                      <th>Cliente</th>
                      <th>Norma</th>
                      <th>Recibio</th>
                      <th>Fecha creación</th>
                      <th>Fecha Actualizacion</th>
                  </tr>
              </thead>
              <tbody></tbody>
          </table>


        </div>
    </div>

    @endsection

    @section('javascript')
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <script src="{{ asset('public/js/alimentos/cadena.js') }}?v=2.0.0"></script>
    @stop