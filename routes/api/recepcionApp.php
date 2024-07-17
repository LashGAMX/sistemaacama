<?php

use App\Http\Controllers\Api\RecepcionAppController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'recepcionApp'], function(){
    Route::post('login', [RecepcionAppController::class, 'login']);
    Route::post('getUser', [RecepcionAppController::class, 'getUser']);
});