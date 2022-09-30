<?php

use App\Http\Controllers\Usuarios\UsuariosController;
use Illuminate\Support\Facades\Route;

Route::get('usuarios/lista', [UsuariosController::class, 'index']);
Route::get('usuarios/menuUser/{id}',[UsuariosController::class,'menuUser']);
Route::post('usuarios/menuUser/assignMenu',[UsuariosController::class,'assignMenu']);
Route::post('usuarios/menuUser/setMenuUser',[UsuariosController::class,'setMenuUser']);

Route::get('usuarios/menuPerfil',[UsuariosController::class,'menuPerfil']);
Route::post('usuarios/setMenuPerfil',[UsuariosController::class,'setMenuPerfil']);
Route::post('usuarios/getMenuPerfil',[UsuariosController::class,'getMenuPerfil']);
 