<?php

use App\Http\Controllers\supervicion\CadenaController;
use App\Http\Controllers\supervicion\CadenaController2;

use App\Http\Controllers\supervicion\SupervicionController;
use Illuminate\Support\Facades\Route; 

        Route::group(['prefix' => 'supervicion'], function () {
    Route::group(['prefix' => 'cadena'], function () {
        Route::get('cadenaCustodia', [CadenaController::class, 'cadenaCustodia']);
       // Route::get('cadenaCustodia', [CadenaController::class, 'cadenaCustodia2']);//ruta de prueba
        Route::get('detalleCadena/{id}', [CadenaController::class, 'detalleCadena']);
        Route::post('getParametroCadena', [CadenaController::class,'getParametroCadena']);

 
        //Rutas de PRUEBA CADENA CUSTODIA
        Route::get('cadenaCustodia2', [CadenaController2::class, 'cadenaCustodia2']);
        Route::get('detalleCadena2/{id}', [CadenaController2::class, 'detalleCadena2']);
        Route::post('getParametroCadena2', [CadenaController2::class,'getParametroCadena2']);

        Route::post('getDetalleAnalisis', [CadenaController::class,'getDetalleAnalisis']);
        Route::post('regresarRes', [CadenaController::class,'regresarRes']);
        Route::post('liberarMuestra', [CadenaController::class,'liberarMuestra']);
        Route::post('regresarMuestra', [CadenaController::class,'regresarMuestra']);
        Route::post('reasignarMuestra', [CadenaController::class,'reasignarMuestra']);
        Route::post('desactivarMuestra', [CadenaController::class,'desactivarMuestra']);

        Route::post('liberarSolicitud', [CadenaController::class,'liberarSolicitud']);
        Route::post('setSupervicion', [SupervicionController::class,'setSupervicion']);
        Route::post('setLiberar', [SupervicionController::class,'setLiberar']);
        Route::post('setHistorial', [CadenaController::class,'setHistorial']);
        Route::post('setEmision', [CadenaController::class,'setEmision']);
        Route::post('sugerido', [CadenaController::class,'sugerido']);
        Route::post('getHistorial', [CadenaController::class,'getHistorial']);
        Route::get('liberarTodoCampo', [SupervicionController::class,'liberarTodoCampo']);
    });
    Route::group(['prefix' => 'analisis'], function () {
        Route::get('/', [SupervicionController::class, 'analisis']);
        Route::post('getLotes', [SupervicionController::class,'getLotes']);
        Route::post('supervisarBitacora', [SupervicionController::class,'supervisarBitacora']);
        Route::post('setLiberarTodo', [SupervicionController::class,'setLiberarTodo']);
    });
    Route::group(['prefix' => 'campo'], function () {
        Route::get('/', [SupervicionController::class, 'campo']);
        Route::post('getMuestreos', [SupervicionController::class,'getMuestreos']);
        Route::post('supervisarBitacoraCampo', [SupervicionController::class,'supervisarBitacoraCampo']);
        Route::post('setLiberarTodoCampo', [SupervicionController::class,'setLiberarTodoCampo']);
        Route::get('liberarTodo', [SupervicionController::class,'liberarTodo']);
    });
});
       