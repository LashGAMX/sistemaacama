<?php

use App\Http\Controllers\Api\CampoAppController;
use App\Http\Controllers\PruebaController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function () {
    Route::post('login', [CampoAppController::class,'login']);
    Route::post('sycnDatos',[CampoAppController::class,'sycnDatos']);
    Route::post('enviarDatos', [CampoAppController::class,'enviarDatos']);
    Route::post('version', [CampoAppController::class,'version']);
    Route::get('prueba', [CampoAppController::class,'prueba']);

    Route::post('guardar', [PruebaController::class,'guardar']);

});
     