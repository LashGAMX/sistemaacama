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
                    <h5 class="card-title"><strong>Id clienteGen: </strong><span id="idCliente">{{$clienteGen->Id_cliente}}</span></h5>
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
                        <table id="TableCliente"></table>
                    </div>
                </div>

            </div>
          </div>
  </div>
    
   

@endsection

@section('javascript')
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<script src="{{ asset('/public/js/cliente/cliente_detalle.js')}}?v=0.0.1"></script>
@stop