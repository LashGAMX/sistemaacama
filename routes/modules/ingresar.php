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

  

   
}); 