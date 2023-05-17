<?php

use App\Http\Controllers\Laboratorio\LabAnalisis;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'laboratorio'], function () { 

    //! Grupo de metales
    Route::group(['prefix' => 'analisis'], function () {
        Route::get('captura', [LabAnalisis::class, 'captura']);
    });

});

