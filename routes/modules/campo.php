<?php

use App\Http\Controllers\Campo\CampoController;
use Illuminate\Support\Facades\Route;

Route::get('campo/asignar', [CampoController::class,'asignar']);
Route::get('campo/capturar', [CampoController::class,'listaMuestreo']);
Route::get('campo/captura/{id}', [CampoController::class,'captura']);
Route::post('campo/asignar/generar', [CampoController::class,'generar']);
// Route::post('campo/asignar/generarUpdate', [CampoController::class,'generarUpdate']);

Route::post('campo/asignar/getFolio', [CampoController::class,'getFolio']);
Route::post('campo/captura/getFactorCorreccion',[CampoController::class,'getFactorCorreccion']);
Route::post('campo/captura/getPhTrazable',[CampoController::class,'getPhTrazable']);
Route::post('campo/captura/getPhCalidad',[CampoController::class,'getPhCalidad']);
Route::post('campo/captura/getConTrazable',[CampoController::class,'getConTrazable']);
Route::post('campo/captura/getConCalidad',[CampoController::class,'getConCalidad']);