<?php

use App\Http\Controllers\Usuarios\UsuariosController;
use Illuminate\Support\Facades\Route;

Route::get('usuarios/lista', [UsuariosController::class, 'index']);

Route::get('usuarios/menuPerfil',[UsuariosController::class,'menuPerfil']);
Route::post('usuarios/setMenuPerfil',[UsuariosController::class,'setMenuPerfil']);
 