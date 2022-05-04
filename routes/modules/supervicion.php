<?php

use App\Http\Controllers\Supervicion\CadenaCustodiaController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'supervicion'], function () {
    Route::group(['prefix' => 'cadena'], function () {
        Route::get('cadenaCustodia', [CadenaCustodiaController::class, 'cadenaCustodia']);
        Route::get('detalleCadena/{id}', [CadenaCustodiaController::class, 'detalleCadena']);
        Route::post('getParametroCadena', [CadenaCustodiaController::class,'getParametroCadena']);
        Route::post('getDetalleAnalisis', [CadenaCustodiaController::class,'getDetalleAnalisis']);

        Route::post('liberarSolicitud', [CadenaCustodiaController::class,'liberarSolicitud']);
    });
});
     