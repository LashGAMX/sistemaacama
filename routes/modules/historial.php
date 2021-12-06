<?php

use App\Http\Controllers\Historial\CampoController;
use App\Http\Controllers\Historial\AnalisisQController;
use App\Http\Controllers\Historial\ClientesController;
use App\Http\Controllers\Historial\ConfigController;
use App\Http\Controllers\Historial\HistorialController;
use App\Http\Controllers\Historial\PreciosController;
use Doctrine\DBAL\Schema\Index;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'historial'], function(){
    Route::get('/', [HistorialController::class,'index']);
    Route::get('config', [ConfigController::class, 'index']);
    Route::get('clientes',[ClientesController::class, 'index']);
    Route::get('analisisQ',[AnalisisQController::class, 'index']);
    Route::get('ingCampo', [CampoController::class, 'index']);
    Route::group(['prefix' => 'ingCampo'], function(){
        Route::get('capturar', [CampoController::class, 'capturar']);
        
        Route::group(['prefix' => 'capturar'], function(){
            Route::get('generales', [CampoController::class, 'datosGenerales']);
            Route::get('muestreo', [CampoController::class, 'datosMuestreo']);
            Route::get('compuestos', [CampoController::class, 'datosCompuestos']);
        });
    });
    
    Route::get('precios',[PreciosController::class, 'index']);
});