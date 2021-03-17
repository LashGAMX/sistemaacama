<?php

use App\Http\Controllers\Clientes\ClienteController;
use App\Http\Controllers\Clientes\IntermediarioController;
use Illuminate\Support\Facades\Route;

Route::get('clientes/intermediarios', [IntermediarioController::class,'index']);

Route::get('clientes/clientes', [ClienteController::class,'index']);
Route::get('clientes/cliente_detalle/{id}', [ClienteController::class,'show']);
 
Route::get('clientes/cliente_detalle/{id}/{idSuc}', [ClienteController::class,'details']);