@extends('voyager::master')

@section('content')


@section('page_header')

<h6 class="page-title">
    {{-- <i class="fa fa-truck-pickup"></i> --}}
    Permiso usuarios : {{ $user->name }}
</h6>
@stop
<input type="text" id="idUser" value="{{ $user->id }}" hidden>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3">
                    <button type="button" class="btn btn-info" id="btnAsignar"><i class="fas fa-edit"></i> Asignar
                        menu</button>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-success" id="btnGuardar"><i class="fas fa-save"></i>
                        Guardar</button>
                </div>
            </div>
        </div>
        <div class="col-md-12" id="divTable">
            <table class="table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Matriz</th>
                        <th>Parametro</th>
                    </tr>
                </thead>
                <tbody>
                    @php $temp = ""; @endphp
                    @foreach ($model as $item)
                    <tr>
                        <td>
                            @foreach ($parametros as $mod)
                                @if ($mod->Id_parametro == $item->Id_parametro)
                                  @php $temp = "checked"; @endphp
                                @endif
                            @endforeach
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" {{ $temp }} name="parametros"
                                    value="{{ $item->Id_parametro }}">
                                <label for="">{{ $item->Id_parametro }}</label>
                            </div>
                        </td>
                        <td>{{$item->Matriz}}</td>
                        <td>
                            {{$item->Parametro}}
                        </td>
                    </tr>
                    @php $temp = ""; @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('javascript')
<script src="{{asset('/public/js/usuarios/parametroUser.js')}}?v=0.0.1"></script>
@stop
@endsection