<?php

use App\Http\Controllers\supervicion\CadenaController;
use App\Http\Controllers\supervicion\SupervicionController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'supervicion'], function () {
    Route::group(['prefix' => 'cadena'], function () {
        Route::get('cadenaCustodia', [CadenaController::class, 'cadenaCustodia']);
        Route::get('detalleCadena/{id}', [CadenaController::class, 'detalleCadena']);
        Route::post('getParametroCadena', [CadenaController::class,'getParametroCadena']);
        Route::post('getDetalleAnalisis', [CadenaController::class,'getDetalleAnalisis']);
        Route::post('regresarRes', [CadenaController::class,'regresarRes']);
        Route::post('liberarMuestra', [CadenaController::class,'liberarMuestra']);
        Route::post('regresarMuestra', [CadenaController::class,'regresarMuestra']);
        Route::post('reasignarMuestra', [CadenaController::class,'reasignarMuestra']);
        Route::post('desactivarMuestra', [CadenaController::class,'desactivarMuestra']);

        Route::post('liberarSolicitud', [CadenaController::class,'liberarSolicitud']);
        Route::post('setHistorial', [CadenaController::class,'setHistorial']);
    });
    Route::group(['prefix' => 'analisis'], function () {
        Route::get('/', [SupervicionController::class, 'analisis']);
        Route::post('getLotes', [SupervicionController::class,'getLotes']);
        Route::post('supervisarBitacora', [SupervicionController::class,'supervisarBitacora']);
        Route::post('setLiberarTodo', [SupervicionController::class,'setLiberarTodo']);
    });
    Route::group(['prefix' => 'campo'], function () {
 
    });
});
       