@extends('voyager::master')

@section('content')

@section('page_header')

    <div class="row">
        <div class="col-md-12">

            <div>
                <div class="row">
                    <div class="col-md-8">
                        <button class="btn btn-success btn-sm" id='crearGrupo'><i class="voyager-plus"></i>Usuarios</button>
                    </div>
                    <div class="col-md-4">
                        <input type="search" wire:model="search" wire:click='resetAlert' class="form-control"
                            placeholder="Buscar">
                    </div>
                </div>
                <table class="table table-hover table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Correo Electronico</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($usuarios->count())
                            @foreach ($usuarios as $usuario)
                                <td>{{ $usuario->name }}</td>
                                <td>{{ $usuario->email }}</td>
                                <td>Acciónes</td>
                                <td>
                                    <button type="button" class="btn btn-info"
                                        onclick="cambiarPassword('{{ $usuario->id }}')">Cambiar Contraseña</button>
                                    <button type="button" class="btn btn-warning"
                                        onclick="modifcarInformacionUsuario('{{ $usuario->id }}')">Modificar</button>
                                </td>
                                </tr>
                            @endforeach
                        @else
                            <h4>No hay resultados para la búsqueda</h4>
                        @endif
                    </tbody>
                </table>
                <!-- Cambio de Contraseña-->
                <!-- Cambio de Contraseña-->
                <!-- Cambio de Contraseña-->
                <!-- Cambio de Contraseña-->
                <!-- Cambio de Contraseña-->
                <!-- Cambio de Contraseña-->
                <div class="modal fade" id="cambioPassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Cambiar Contraseña</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <label>Agregue la Nueva Contraseña:</label>
                                <input type="hidden" name="_token" id="_token" value="{{ Session::token() }}">
                                <input type="text" class="form-control" id='id_usuario'>
                                <input type="text" class="form-control" id='passwordUsuarios'>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-primary" onclick="guardarPassword()">Guardar Cambios</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Cambio de Contraseña-->
                <!-- Cambio de Contraseña-->
                <!-- Cambio de Contraseña-->
                <!-- Cambio de Contraseña-->
                <!-- Cambio de Contraseña-->
            </div>
        </div>
    </div>
@stop

@endsection
@section('javascript')
<script src="{{ asset('/public/js/usuarios/usuarios.js') }}"></script>
@stop
