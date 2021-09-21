<?php

use App\Http\Controllers\Laboratorio\LaboratorioController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'laboratorio'], function () {
    Route::get('analisis',[LaboratorioController::class,'analisis'] );
    Route::get('observacion',[LaboratorioController::class,'observacion']);
});
