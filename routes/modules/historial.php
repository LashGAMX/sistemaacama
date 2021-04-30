<?php

use App\Http\Controllers\Historial\AnalisisQController;
use App\Http\Controllers\Historial\ClientesController;
use App\Http\Controllers\Historial\ConfigController;
use App\Http\Controllers\Historial\HistorialController;
use Doctrine\DBAL\Schema\Index;
use Illuminate\Support\Facades\Route;

Route::get('historial', [HistorialController::class,'index']);
Route::get('historial/config', [ConfigController::class, 'index']);
Route::get('historial/clientes',[ClientesController::class, 'index']);
Route::get('historial/analisisQ',[AnalisisQController::class, 'index']);