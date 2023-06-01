<?php

use App\Http\Controllers\Laboratorio\LabAnalisisController;
use Illuminate\Support\Facades\Route;
 

Route::group(['prefix' => 'laboratorio'], function () { 

 
    Route::group(['prefix' => 'analisis'], function () {
        Route::get('captura', [LabAnalisisController::class, 'captura']);
        Route::post('getPendientes',[LabAnalisisController::class,'getPendientes']);
        Route::post('getLote',[LabAnalisisController::class,'getLote']);
        Route::post('getDetalleLote',[LabAnalisisController::class,'getDetalleLote']);
        Route::post('setLote',[LabAnalisisController::class,'setLote']);
        Route::post('getMuestraSinAsignar', [LabAnalisisController::class,'getMuestraSinAsignar']);
        Route::post('setMuestraLote',[LabAnalisisController::class,'setMuestraLote']);
        Route::post('getCapturaLote',[LabAnalisisController::class,'getCapturaLote']);
        Route::post('getDetalleMuestra',[LabAnalisisController::class,'getDetalleMuestra']);
        Route::post('setDetalleMuestra',[LabAnalisisController::class,'setDetalleMuestra']);
        Route::post('setDetalleMuestra',[LabAnalisisController::class,'setDetalleMuestra']);

        Route::post('setControlCalidad', [LabAnalisisController::class, 'setControlCalidad']);
        Route::post('setLiberarTodo', [LabAnalisisController::class, 'setLiberarTodo']);
        Route::post('setLiberar', [LabAnalisisController::class, 'setLiberar']);
        Route::post('setObservacion', [LabAnalisisController::class, 'setObservacion']);
    });
   
});

 