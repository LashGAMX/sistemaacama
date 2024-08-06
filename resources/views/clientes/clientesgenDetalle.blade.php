@extends('voyager::master')

@section('content')

@section('page_header')

@stop
<div class="table table-sm" id="tabGenerales">
    <table id="tablaDatosGenerales">
    </table>
</div>

<h6 class="page-title"> 
      <i class="voyager-person"></i>
      Detalle Cliente
  </h6>
 
  <div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="card-title"><strong>Status: </strong>@if ($clienteGen->deleted_at != 'null') Activo @else Inactivo @endif</h5>
                    </div>
                    <div class="col-md-6">
                        <h5 class="card-title"><strong>Id clienteGen: </strong>{{$clienteGen->Id_cliente}}</h5>
                    </div>
                    <div class="col-md-6">
                        <h5 class="card-title"><strong>Nombre: </strong>{{$clienteGen->Empresa}}</h5>
                    </div>
                    <div class="col-md-6">
                        <h5 class="card-title"><strong>Intermediario: </strong>{{$clienteGen->Nombres}} {{$clienteGen->A_paterno}}</h5>
                    </div>
                </div>
                <div class="row">
             
                    <div class="col-md-12">
                    <div id="SucurcalCliente">
                      <table id="TablaScursal">
                       
                      </table>

                    </div>
                    </div>
                </div>
            </div>
          </div>
    </div>
    
    <div class="col-md-4">
                  <h6>Id_cliente: {{@$sucursal->Id_sucursal}}</h6>
                </div>
                <div class="col-md-4">
                  <h6>Sucursal: {{@$sucursal->Empresa}}</h6>
                </div>
                <div class="col-md-4">
                  <h6>Estado: {{@$sucursal->Estado}}</h6>
                </div>
              </div>
          <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                      <a class="nav-link active" id="reporte-tab" data-toggle="tab" href="#reporte" role="tab" aria-controls="reporte" aria-selected="true">Reporte</a>
                    </li>
                    <li class="nav-item" role="presentation">
                      <a class="nav-link" id="siralab-tab" data-toggle="tab" href="#siralab" role="tab" aria-controls="siralab" aria-selected="false">Siralab</a>
                    </li>
                    <li class="nav-item" role="presentation">
                      <a class="nav-link" id="generales-tab" data-toggle="tab" href="#generales" role="tab" aria-controls="siralab" aria-selected="false">Datos Generales</a>
                    </li>
                  </ul>
  </div>

@endsection

@section('javascript')
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<script src="{{ asset('/public/js/cliente/cliente_detalle.js') }}"></script>
@stop