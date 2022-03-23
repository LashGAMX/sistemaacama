<?php

use App\Http\Controllers\Supervicion\CadenaCustodiaController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'supervicion'], function () {
    Route::group(['prefix' => 'cadena'], function () {
        Route::get('cadenaCustodia', [CadenaCustodiaController::class, 'index']);
    });
});
