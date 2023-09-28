<?php

use App\Http\Controllers\supervicion\CadenaController;
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

        Route::post('liberarSolicitud', [CadenaController::class,'liberarSolicitud']);
    });
});
       