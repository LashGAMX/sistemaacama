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
                    <button class="btn-success" id=btnCreateIngreso><i class="fas fa-plus"></i> Crear C. I</button>
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
            <div class="col-md-2">
            <button id="btnImprimir" class="btn btn-info"><i
            class="voyager-documentation"></i> Imprimir</button>
            </div>
            <table id="tablaSolicitud" class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Folio</th>
                        <th>Cliente</th>
                        <th>Fecha Muestreo</th>
                        <th>Obs</th>
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
<script src="{{ asset('public/js/alimentos/ordenServicio.js')}}?v=0.0.1"></script>
@endsection