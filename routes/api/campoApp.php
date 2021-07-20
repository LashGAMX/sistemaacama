<?php

use App\Http\Controllers\Api\CampoAppController; 
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function () {
    Route::post('login', [CampoAppController::class,'login']);
    Route::post('sycnDatos',[CampoAppController::class,'sycnDatos']);
    Route::post('enviarDatos', [CampoAppController::class,'enviarDatos']);
});
     