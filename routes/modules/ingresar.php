<?php

use App\Http\Controllers\Ingresar\IngresarController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'ingresar'], function () {
    Route::get('', [IngresarController::class, 'index']);    

    //RUTA DE PRUEBA PARA EL BUSCADOR
    Route::get('buscador', [IngresarController::class, 'buscador']);

    //Ruta para consultar fecha fin de muestreo siralab
    Route::get('siralabFecha', [IngresarController::class, 'fechaFinSiralab']);

    Route::post('ingresar', [IngresarController::class, 'setIngresar']);
    //Route::post('listar', [IngresarController::class, 'generar']);


    //-------------------Módulo solicitudes---------------------
    Route::get('generar', [IngresarController::class, 'genera2']);
    Route::get('/generar/buscarSol', [IngresarController::class, 'buscadorGen']);
});