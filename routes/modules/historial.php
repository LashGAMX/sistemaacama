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
    Route::get('precios',[PreciosController::class, 'index']);
});