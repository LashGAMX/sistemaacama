<?php

use App\Http\Controllers\Laboratorio\LabAnalisisController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'laboratorio'], function () { 

    //! Grupo de metales
    Route::group(['prefix' => 'analisis'], function () {
        Route::get('captura', [LabAnalisisController::class, 'captura']);
        Route::post('getPendientes',[LabAnalisisController::class,'getPendientes']);
        Route::post('getLote',[LabAnalisisController::class,'getLote']);
        Route::post('setLote',[LabAnalisisController::class,'setLote']);
        Route::post('getMuestraSinAsignar', [LabAnalisisController::class,'getMuestraSinAsignar']);
        Route::post('setMuestraLote',[LabAnalisisController::class,'setMuestraLote']);
        Route::post('getCapturaLote',[LabAnalisisController::class,'getCapturaLote']);
    });
 
});

