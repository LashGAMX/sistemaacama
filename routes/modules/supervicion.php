<?php

use App\Http\Controllers\supervicion\CadenaCustodiaController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'supervicion'], function () {
    Route::group(['prefix' => 'cadena'], function () {
        Route::get('cadenaCustodia', [CadenaCustodiaController::class, 'cadenaCustodia']);
        Route::get('detalleCadena/{id}', [CadenaCustodiaController::class, 'detalleCadena']);
        Route::post('getParametros', [CadenaCustodiaController::class, 'getParametros']);
    });
});
  