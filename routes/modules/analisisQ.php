<?php

use App\Http\Controllers\AnalisisQ\NormaController;
use App\Http\Controllers\AnalisisQ\ParametroController;
use Illuminate\Support\Facades\Route;

Route::get('analisisQ/parametros', [ParametroController::class,'index']);
Route::get('analisisQ/normas', [NormaController::class,'index']);
Route::get('analisisQ/detalle_normas/{id}', [NormaController::class,'show']);
  