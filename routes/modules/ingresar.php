<?php

use App\Http\Controllers\Ingresar\IngresarController;
use Illuminate\Support\Facades\Route;

Route::get('ingresar', [IngresarController::class, 'index']);
Route::post('ingresar/listar', [IngresarController::class, 'generar']);