<?php

use App\Http\Controllers\Laboratorio\LabAnalisisController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'laboratorio'], function () { 

    //! Grupo de metales
    Route::group(['prefix' => 'analisis'], function () {
        Route::get('captura', [LabAnalisisController::class, 'captura']);
        Route::post('getPendientes',[LabAnalisisController::class,'getPendientes']);
        Route::post('getLote',[LabAnalisisController::class,'getLote']);
    });
 
});

