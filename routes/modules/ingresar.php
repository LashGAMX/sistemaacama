<?php

use App\Http\Controllers\Ingresar\IngresarController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'ingresar'], function () {
    Route::get('', [IngresarController::class, 'index']);    
    Route::get('recepcion', [IngresarController::class, 'recepcion']);    

    //RUTA DE PRUEBA PARA EL BUSCADOR
    Route::get('buscador', [IngresarController::class, 'buscador']);
    Route::post('buscarFolio', [IngresarController::class,'buscarFolio']);
    Route::post('setIngresar', [IngresarController::class,'setIngresar']);
    Route::post('getPuntoMuestreo', [IngresarController::class,'getPuntoMuestreo']);
    Route::post('getCodigoRecepcion', [IngresarController::class, 'getCodigoRecepcion']);
    Route::post('getDataPuntoMuestreo', [IngresarController::class, 'getDataPuntoMuestreo']);
    Route::post('setGenFolio', [IngresarController::class, 'setGenFolio']);
    Route::post('setActCC', [IngresarController::class, 'setActCC']);
}); 