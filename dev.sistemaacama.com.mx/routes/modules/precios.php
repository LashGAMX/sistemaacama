<?php

use App\Http\Controllers\Precios\CatalogoController;
use App\Http\Controllers\Precios\IntermediarioController;
use App\Http\Controllers\Precios\PaqueteController;
use Illuminate\Support\Facades\Route;
 
Route::get('precios/catalogo', [CatalogoController::class,'index'] );
Route::get('precios/catalogo/{idSucursal}/{idNorma}', [CatalogoController::class,'details'] );
Route::post('precios/catalogo/savePrecioCat', [CatalogoController::class,'savePrecioCat']);
Route::post('precios/catalogo/getParametros', [CatalogoController::class,'getParametros']);
Route::post('precios/catalogo/setPrecioAnual', [CatalogoController::class,'setPrecioAnual']);
 

Route::get('precios/intermediario', [IntermediarioController::class,'index']);
Route::get('precios/intermediario/details/{idCliente}', [IntermediarioController::class,'details']);

Route::get('precios/paquete', [PaqueteController::class,'index']);
Route::post('precios/paquete/getPaquetes', [PaqueteController::class,'getPaquetes']);
Route::post('precios/paquete/savePrecioPaq', [PaqueteController::class,'savePrecioPaq']);
Route::post('precios/paquete/setPrecioPaquete', [PaqueteController::class,'setPrecioPaquete']);
Route::post('precios/paquete/setPrecioAnual', [PaqueteController::class,'setPrecioAnual']);