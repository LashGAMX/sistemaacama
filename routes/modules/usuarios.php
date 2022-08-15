<?php

use App\Http\Controllers\Usuarios\GruposController;
use App\Http\Controllers\Usuarios\UsuariosController;
use Illuminate\Support\Facades\Route;

Route::get('usuarios/menu', [GruposController::class, 'index']);
