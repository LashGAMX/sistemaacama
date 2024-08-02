@extends('voyager::master')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <label for="codigo" class="form-label">Codigo</label>
            <input type="text" class="form-control" id="codigo" placeholder="Ingrese el codigo de la firma">
            <br>
            <button class="btn btn-success" id="btnValidar">Validar</button>
        </div>
    </div>
    <div class="col-12">
        <span id="contenido"></span>
    </div>
</div>
@endsection
@section('javascript')
<script src="{{ asset('public/js/supervicion/informe/valInforme.js') }}?v=0.0.1"></script>
@stop