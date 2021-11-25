<?php

use App\Http\Controllers\Precios\CatalogoController;
use App\Http\Controllers\Precios\IntermediarioController;
use App\Http\Controllers\Precios\PaqueteController;
use Illuminate\Support\Facades\Route;

Route::get('precios/catalogo', [CatalogoController::class,'index'] );
Route::get('precios/catalogo/{idSucursal}/{idNorma}', [CatalogoController::class,'details'] );

Route::get('precios/intermediario', [IntermediarioController::class,'index']);
Route::get('precios/intermediario/details/{idCliente}', [IntermediarioController::class,'details']);

Route::get('precios/paquete', [PaqueteController::class,'index']);