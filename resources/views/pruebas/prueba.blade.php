@extends('voyager::master')

@section('content')

@section('page_header')
@stop
<div class="row">
    <div class="col-md-12">
        Contenido para mi componente
        @livewire('pruebas.prueba')
    </div>
</div>

@endsection
@section('javascript')
<script src="{{ asset('js/grupos/grupos.js') }}"></script>
@stop
