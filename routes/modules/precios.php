<?php

use App\Http\Controllers\Precios\CatalogoController;
use Illuminate\Support\Facades\Route;

Route::get('precios/catalogo', [CatalogoController::class,'index'] );
Route::get('precios/catalogo/{idSucursal}', [CatalogoController::class,'details'] );