@extends('voyager::master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-2">
                    <button class="btn-success" id=btnCreate><i class="fas fa-plus"></i> Crear</button>
                </div>
                <div class="col-md-2">
                    <button class="btn-warning" id="btnEdit"><i class="fas fa-pen"></i> Editar</button>
                </div>
                @switch(Auth::user()->id)
                @case(1)
                @case(36)
                @case(101)
                @case(100)
                @case(31)
                @case(65)
                <div class="col-md-2">
                    <button id="btnDuplicar" class="btn-info"><i class="fas fa-copy"></i> Duplicar</button>
                </div>
                &nbsp;
                <button id="btnCancelar" class="btn btn-danger"><i class="fas fa-delete"></i>Cancelar</button>
                @break
                @default
                @endswitch
            </div>
            <table id="tablaCotizacion" class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Folio Servicio</th>
                        <th>Folio Cotización</th>
                        <th>Fecha Cotización</th>
                        <th>Nombre Cliente</th>
                        <th>Norma</th>
                        <th>Tipo Descarga</th>
                        <th>Estado Cotización</th>
                        <th>Creado por</th>
                        <th>Fecha Creación</th>
                        <th>Actualizado por</th>
                        <th>Fecha Actualización</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>


        </div>
        <div class="col-md-12" id="divOrden"></div>
    </div>
</div>
@endsection
@section('javascript')
<script src="{{ asset('public/js/alimentos/cotizacion.js')}}?v=0.0.1"></script>
@endsection