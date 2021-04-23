<?php

use App\Http\Controllers\AnalisisQ\ImportarController;
use App\Http\Controllers\AnalisisQ\LimitesController;
use App\Http\Controllers\AnalisisQ\NormaController;
use App\Http\Controllers\AnalisisQ\ParametroController;
use Illuminate\Support\Facades\Route;

Route::get('analisisQ/parametros', [ParametroController::class,'index']); 
Route::get('analisisQ/normas', [NormaController::class,'index']);

Route::get('analisisQ/detalle_normas/{id}', [NormaController::class,'show']);
Route::get('analisisQ/detalle_normas/{id}/{idSub}', [NormaController::class,'details']);
//Peticiones 
Route::post('analisisQ/detalle_normas/createNormaParametro', [NormaController::class,'createNormaParametro']);
Route::post('analisisQ/detalle_normas/getParametro', [NormaController::class,'getParametro']);
Route::post('analisisQ/detalle_normas/getParametroNorma', [NormaController::class,'getParametroNorma']);
   
Route::get('analisisQ/limites', [LimitesController::class,'index']);
Route::get('analisisQ/limites/{idNorma}', [LimitesController::class,'show']); 
Route::get('analisisQ/limites/{idNorma}/{idParametro}', [LimitesController::class,'details']); 

Route::get('analisisQ/importar', [ImportarController::class,'index']);
Route::post('analisisQ/importar/create', [ImportarController::class,'create']); 