<?php

use App\Http\Controllers\Usuarios\UsuariosController;
use Illuminate\Support\Facades\Route;

Route::get('usuarios/lista', [UsuariosController::class, 'index']);
