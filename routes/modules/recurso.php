<?php

use App\Http\Controllers\Recursos\RecursoController;
use App\Http\Controllers\Recursos\CampoController;
use App\Http\Controllers\Recursos\AppController;
use Illuminate\Support\Facades\Route;

Route::get('recursos', [RecursoController::class, 'index']);
Route::get('recursos/ingCampo', [CampoController::class, 'index']);
Route::get('recursos/ingCampo/app', [AppController::class, 'index']);