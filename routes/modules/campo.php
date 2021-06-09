<?php

use App\Http\Controllers\Campo\CampoController;
use Illuminate\Support\Facades\Route;

Route::get('campo/asignar', [CampoController::class,'asignar']);
Route::get('campo/capturar', [CampoController::class,'listaMuestreo']);
Route::get('campo/captura', [CampoController::class,'captura']);
Route::post('campo/asignar/generar', [CampoController::class,'generar']);