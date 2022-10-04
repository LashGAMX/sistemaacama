<?php

use App\Http\Controllers\Seguimiento\SeguimientoController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/seguimiento'], function () {
    Route::get('/muestra',[SeguimientoController::class,'muestra']);
});   