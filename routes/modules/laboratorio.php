<?php

use App\Http\Controllers\Laboratorio\LaboratorioController;
use App\Models\Laboratorio;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'laboratorio'], function () {
    Route::get('analisis',[LaboratorioController::class,'analisis'] );
    Route::get('observacion',[LaboratorioController::class,'observacion']);
    Route::get('tipoAnalisis',[LaboratorioController::class,'tipoAnalisis']);
    Route::get('captura',[LaboratorioController::class,'captura']);
    Route::get('lote',[LaboratorioController::class,'lote']);
    Route::get('asignar',[LaboratorioController::class,'asignar']);

    //---------------------------------Rutas Ajax----------------------------------
    Route::get('analisis/datos', [LaboratorioController::class, 'analisisDatos']);
});
