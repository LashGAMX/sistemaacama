<?php

use App\Http\Controllers\Ingresar\IngresarController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'ingresar'], function () {
    Route::get('', [IngresarController::class, 'index']);    
    Route::get('generar', [IngresarController::class, 'genera2']);

    //RUTA DE PRUEBA PARA EL BUSCADOR
    Route::get('buscador', [IngresarController::class, 'buscador']);

    Route::post('ingresar', [IngresarController::class, 'setIngresar']);
    //Route::post('listar', [IngresarController::class, 'generar']);
});