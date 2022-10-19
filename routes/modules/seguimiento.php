<?php

use App\Http\Controllers\Seguimiento\SeguimientoController;
use App\Http\Controllers\Seguimiento\IncidenciasController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/seguimiento'], function () {
    Route::get('/muestra',[SeguimientoController::class,'muestra']);
    Route::get('/incidencias',[IncidenciasController::class,'index']);
});   