@extends('voyager::master')

@section('content')

@section('page_header')

    <div class="row">
        <div class="col-md-12">

            <div>
                <div class="row">
                    <div class="col-md-8">
                        <button class="btn btn-success btn-sm" id='crearGrupo'><i class="voyager-plus"></i> Crear</button>
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
                            <th>Nombre del Grupo</th>
                            <th>Observaciónes del Grupo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($grupos->count())
                            @foreach ($grupos as $grupo)
                                <td>{{ $grupo->Titulo }}</td>
                                <td>{{ $grupo->Comentario }}</td>
                                <td>Acciónes</td>
                                <td>
                                    <button type="button" class="btn btn-info"
                                        onclick="configuracionUsuarios('{{ $grupo->Id_grupos }}')">Usuarios</button>
                                    <button type="button" class="btn btn-warning"
                                        onclick="modifcarGrupo('{{ $grupo->Id_grupos }}')">Modificar</button>
                                </td>
                                </tr>
                            @endforeach
                        @else
                            <h4>No hay resultados para la búsqueda</h4>
                        @endif
                    </tbody>
                </table>
                {{ $grupos->links() }}
                <!-- Grupo -->
                <!-- Grupo -->
                <!-- Grupo -->
                <!-- Modal -->
                <!-- Modal -->
                <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Crear Grupo</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <input type="hidden" name="_token" id="_token" value="{{ Session::token() }}">
                                    <div class="col-md-12">
                                        <p>Bienvenido es Hora de Crear de un Grupo de Trabajo</p>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="">Titulo</label>
                                        <input type="text" id="id_edit_form" hidden="off">
                                        <input type="text" id="TituloGuardar" class="form-control">
                                    </div>
                                    <div class="col-md-12">
                                        <label for="">Descripción</label>
                                        <input id="comentarioGuardar" class="form-control">
                                    </div>
                                    <div class="col-md-12">
                                        <button type="text" class="btn btn-sm btn-success" onclick="crearGrupo()"
                                            id="guardarGrupo">Guardar
                                        </button>
                                        <button type="text" class="btn btn-sm btn-success" onclick="actualizarGrupo()"
                                            id="actualizarGrupo">Actualizar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- Fin Grupo -->
                <!-- Fin Grupo -->
                <!-- Fin Grupo -->

                <!-- Usuario -->
                <!-- Usuario -->
                <!-- Usuario -->
                <!-- Usuario -->
                <!-- Usuario -->
                <div class="modal fade" id="usuarios" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Configuración Usuarios</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <input type="hidden" name="_token" id="_token" value="{{ Session::token() }}">
                                    <div class="col-md-12">
                                        <p>Bienvenido es Hora de Configurar el Grupo de Trabajo</p>
                                    </div>
                                    <div class="col-md-8">
                                        <label>Seleccionar Usuario:</label>
                                        <input class="form-control" id="id_grupo" type="hidden">
                                        <select class="select2" id="lista_usuarios">
                                            @foreach ($usuarios as $usuario)
                                                <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                            @endforeach()
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <br>
                                        <button class="btn btn-sm btn-success mt-2"
                                            onclick="agregarUsuario()">Añadir</button>
                                    </div>
                                    <div class="col-md-12">
                                        <div id="tabla-grupos">
                                        </div>
                                    </div>
                                    <div class="col-md-12">

                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- Fin Usuario -->
                <!-- Fin Usuario -->
                <!-- Fin Usuario -->
            </div>
        </div>
    </div>
@stop

@endsection
@section('javascript')
<script src="{{ asset('js/usuarios/grupos.js') }}"></script>
@stop
