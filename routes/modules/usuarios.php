<?php

use App\Http\Controllers\Usuarios\GruposController;
use Illuminate\Support\Facades\Route;

Route::get('usuarios/grupos',[GruposController::class,'index']);

Route::post('usuarios/grupos/guardar',[GruposController::class,'guardar']);

Route::post('usuarios/grupos/obtenerInformacionGrupo',[GruposController::class,'editar']);
Route::post('usuarios/grupos/actualizarInformacionGrupo',[GruposController::class,'actualizar']);

