<?php

use App\Http\Controllers\Usuarios\GruposController;
use App\Http\Controllers\Usuarios\UsuariosController;
use Illuminate\Support\Facades\Route;

Route::get('usuarios/grupos', [GruposController::class, 'index']);
// Rutas Para Grupos
Route::post('usuarios/grupos/guardar', [GruposController::class, 'guardar']);
Route::post('usuarios/grupos/obtenerInformacionGrupo', [GruposController::class, 'editar']);
Route::post('usuarios/grupos/actualizarInformacionGrupo', [GruposController::class, 'actualizar']);
Route::post('usuarios/grupos/agregarUsuario', [GruposController::class, 'agregarUsuario']);
Route::post('usuarios/grupos/obtenerTablaGruposUsuarios', [GruposController::class, 'obtenerTablaGruposUsuarios']);
Route::post('usuarios/grupos/eliminarUsuarioGrupo', [GruposController::class, 'eliminarUsuarioGrupo']);

Route::get('usuarios/usuarios', [UsuariosController::class, 'index']);
Route::post('usuarios/cambiarPassword', [UsuariosController::class, 'cambiarPassword'])->name('usuarios.cambiarPassword');
