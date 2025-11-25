 @extends('voyager::master')

@section('content')

@section('page_header')

<div class="container-fluid">
    <div class="row">
        <h3>Asignacion de matriz y limites para alimentos</h3>
        <div class="form-row align-items-end">

            <div class="form-group col-md-3">
                <label for="parametro">Parámetro</label>
                <select id="parametro" name="parametro" class="form-control select2">
                    <option value="">-- Selecciona un parámetro --</option>
                    @foreach ($parametros as $parametro)
                    <option value="{{ $parametro->Id_parametro }}">
                        ({{ $parametro->Id_parametro }}) - {{ $parametro->Parametro }} ({{ $parametro->Matriz }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-2">
                <label for="matriz">Matriz</label>
                <select id="matriz" name="matriz" class="form-control">
                    <option value="">-- Selecciona una matriz --</option>
                    @foreach ($matriz as $item)
                    <option value="{{ $item->Id_matriz_parametro }}">
                        {{ $item->Id_matriz_parametro }} - {{ $item->Matriz }}
                    </option>
                    @endforeach
                </select>
            </div>
                <div class="form-group col-md-2">
                <label for="unidad">Unidad</label>
                <select id="unidad" name="unidad" class="form-control">
                    <option value="">-- Selecciona una matriz --</option>
                    @foreach ($unidad as $item)
                    <option value="{{ $item->Id_unidad }}">
                        {{ $item->Id_unidad }} - {{ $item->Unidad }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-1">
                <label for="limite">Limite</label>
                <input type="text" name="limite" id="limite" class="form-control">
            </div>
             <div class="form-group col-md-1">
                <label for="Dias">Dias de Analisis</label>
                <input type="text" name="Dias" id="Dias" class="form-control">
            </div>

            <div class="form-group col-md-1">
                <button id="btnGuardar" class="btn btn-primary btn-block">Guardar</button>
                <button class="btn-warning btn" type="submit" onclick="updateParaAli()"><i class="fas fa-pen"></i></button>
            </div>
        </div>
        <div class="col-md-12">
           <div id="tabParametros">

           </div>
        </div>
    </div>
</div>
@stop
@section('javascript')
<script src="{{asset('/public/js/analisisQ/LimiteAlimentos.jsx')}}?v=0.0.1"></script>
@stop
@endsection