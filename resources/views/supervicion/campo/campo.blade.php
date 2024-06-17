@extends('voyager::master')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Muestreador</label>
                        <select id="muestreador" class="form-control select2">
                            <option value="0">Sin seleccionar</option>   
                            @foreach ($muestreador as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>   
                            @endforeach
                        </select>
                    </div>
                </div>
             
                <div class="col-md-3">
                    <label for="">Mes / Año</label>
                    <input type="month" id="mes" class="form-control">
                </div>
                <div class="col-md-1">
                    <br>
                    <button id="btnBuscar" class="btn btn-info">Buscar <i class="fas fa-search"></i></button>
                </div>
                <div class="col-md-2">
                    <label for="user">Usuario</label>
                        <select class="form-control select2" id="user">
                            <option value="0" selected >Sin seleccionar</option>
                            <option value="1">ADMIN</option>
                            <option value="15">ALBERTO MÉNDEZ RAMÍREZ</option>
                            <option value="97">	FRANCISCO JAVIER ABUNDIS HERRADA</option>
                        </select>
                </div>
            </div>
        </div>
        <div class="col-md-12" id="divMuestreo">
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Folio</th>
                        <th>Captura</th>
                        <th>Muestreador</th>
                        <th>Fecha muestreo</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script src="{{ asset('public/js/supervicion/campo/campo.js') }}?v=0.0.1"></script>
@stop
