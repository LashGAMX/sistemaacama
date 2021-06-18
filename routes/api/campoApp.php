<?php

use App\Http\Controllers\Api\CampoAppController; 
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function () {
    Route::get('login/{user}/{pass}', [CampoAppController::class,'login']);
    Route::get('getUser', [CampoAppController::class,'getUser']);
});
    