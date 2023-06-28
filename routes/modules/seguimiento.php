<?php

use App\Http\Controllers\seguimiento\SeguimientoController;
use App\Http\Controllers\seguimiento\IncidenciasController; 
use App\Http\Controllers\seguimiento\IndicadoresController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/seguimiento'], function () {
    Route::get('/muestra',[SeguimientoController::class,'muestra']);
    Route::get('/incidencias/lista',[IncidenciasController::class,'lista']);
    Route::get('/incidencias',[IncidenciasController::class,'incidencias']);
    Route::get('/incidencias/admin',[IncidenciasController::class,'admin']);
    Route::post('/incidencias/getsubmodulos',[IncidenciasController::class,'getsubmodulos']);
    Route::post('/incidencias/create',[IncidenciasController::class,'create']);
    Route::post('/incidencias/buscar',[IncidenciasController::class,'buscar']);
    Route::post('/incidencias/getIncidencia',[IncidenciasController::class,'getIncidencia']);
    Route::post('/incidencias/update',[IncidenciasController::class,'update']);
    Route::post('/incidencias/index',[IncidenciasController::class,'index']);
    
});    
Route::group(['prefix' => '/indicadores'], function () {
    Route::get('/',[IndicadoresController::class,'index']);
    Route::get('/graficos',[IndicadoresController::class,'graficos']);
});    