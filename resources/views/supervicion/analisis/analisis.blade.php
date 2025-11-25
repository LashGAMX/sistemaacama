@extends('voyager::master')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" hidden value="{{ Auth::user()->id }}" id="idUser">

                        <label for="">Parametro</label>
                        <select id="parametro" class="form-control select2">
                            @foreach ($parametro as $item)
                                <option value="{{$item->Id_parametro}}">({{$item->Id_parametro}}) {{$item->Parametro}} ({{$item->Tipo_formula}})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="tipo">Tipo fórmula (Metales)</label>
                    <select class="form-control select2" id="tipo">
                        <option value="0">Sin seleccionar</option>
                        @foreach($tipo as $item)
                        <option value="{{$item->Id_tipo_formula}}">{{$item->Tipo_formula}}</option>
                        @endforeach
                    </select>
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
                    <label for="user">Usuarios</label>
                        <select class="form-control select2" id="user">
                            <option value="0" selected >Sin seleccionar</option>
                            <option value="1">ADMIN</option>
                            <option value="14">GUADALUPE GARCIA PÉREZ</option>
                            <option value="39">ERICK SÁNCHEZ TENORIO</option>
                            <option value="46">YURIDIA CORONA TLAPANCAL</option>
                            <option value="16">Francisco Javier Abundis Rodríguez</option>
                            <option value="31">ELSA RIVERA RIVERA</option>
                            <option value="30">MARIANA RAMÍREZ PICAZO</option>
                            <option value="37">DASAAET SANCHEZ BLANCO</option>
                            <option value="3">ALEJANDRO MORALES LÓPEZ</option>

                        </select>
                </div>
            </div>
        </div>
        <div class="col-md-12" id="divLote">
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Parametro</th>
                        <th>Id lote</th>
                        <th>Liberado / Sin liberar</th>
                        <th>Fecha</th>
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
<script src="{{ asset('public/js/supervicion/analisis/analisis.js') }}?v=0.0.1"></script>
@stop
