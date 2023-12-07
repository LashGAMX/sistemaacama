@extends('voyager::master')
@section('page_header')
@stop
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Indicador</label>
                        <select id="" class="form-control">
                            <option value="0">Sin seleccionar</option>
                            <option value="1">Solicitudes pendientes</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <br>
                    <button class="btn btn-success"><i class="fas fa-search"></i> Buscar</button>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div id="tabIndicador">

            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script src="{{ asset('public/js/kpi/kpi.js') }}?v=0.0.1"></script>
@stop