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

    // //Ruta para consultar fecha fin de muestreo siralab
    // Route::get('siralabFecha', [IngresarController::class, 'fechaFinSiralab']);

    // //Route::post('ingresar', [IngresarController::class, 'setIngresar']);
    // //Route::post('listar', [IngresarController::class, 'generar']);

    // //RUTA PARA OBTENER LA FECHA DE CONFORMACIÓN
    // Route::get('fechaConformacion', [IngresarController::class, 'fechaConformacion']);

    // //RUTA PARA OBTENER LA PROCEDENCIA CON PREVIA COTIZACIÓN
    // Route::get('procedencia', [IngresarController::class, 'procedencia']);


    // //-------------------Módulo solicitudes---------------------
    // Route::get('generar', [IngresarController::class, 'genera2']);
    // Route::get('/generar/buscarSol', [IngresarController::class, 'buscadorGen']);
}); 