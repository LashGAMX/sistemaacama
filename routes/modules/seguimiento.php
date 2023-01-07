<?php

use App\Http\Controllers\seguimiento\SeguimientoController;
use App\Http\Controllers\seguimiento\IncidenciasController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/seguimiento'], function () {
    Route::get('/muestra',[SeguimientoController::class,'muestra']);
    Route::get('/incidencias/lista',[IncidenciasController::class,'lista']);
    Route::get('/incidencias',[IncidenciasController::class,'incidencias']);
    Route::post('/incidencias/getsubmodulos',[IncidenciasController::class,'getsubmodulos']);
    Route::post('/incidencias/create',[IncidenciasController::class,'create']);
});   