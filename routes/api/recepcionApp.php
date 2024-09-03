<?php

use App\Http\Controllers\Api\RecepcionAppController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'recepcionApp'], function(){
    Route::post('login', [RecepcionAppController::class, 'login']);
    Route::post('getUser', [RecepcionAppController::class, 'getUser']);
    Route::post('getInformacionFolioAgua', [RecepcionAppController::class, 'getInformacionFolioAgua']);
    Route::post('getParametros', [RecepcionAppController::class, 'getParametros']);
    Route::post('upHoraRecepcion', [RecepcionAppController::class, 'upHoraRecepcion']);
    Route::post('setImagenPunto', [RecepcionAppController::class, 'setImagenPunto']);
    Route::post('getImagenesPunto', [RecepcionAppController::class, 'getImagenesPunto']);
    Route::post('getDatosPunto', [RecepcionAppController::class, 'getDatosPunto']);
    Route::post('setGenFolio', [RecepcionAppController::class, 'setGenFolio']);
    Route::post('setIngresar', [RecepcionAppController::class, 'setIngresar']);
    Route::post('upFechaEmision', [RecepcionAppController::class, 'upFechaEmision']);
});