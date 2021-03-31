<?php

use App\Http\Controllers\Cotizacion\CotizacionController;
use App\Http\Controllers\Cotizacion\CotizacionConfigController;
use Illuminate\Support\Facades\Route;

Route::get('cotizacion', [CotizacionController::class,'index']);


//Route::post('cotizacion', [CotizacionController::class,'registrar'])->name('cotizacion.registrar');
Route::post('cotizacion/save', [CotizacionController::class,'registrar'])->name('cotizacion.registrar');

//Route::get('cotizacion', [CotizacionController::class,'index']);

//Route::get('cotizacion/cotizacion_detalle/{id}', [CotizacionController::class,'show']);

//Route::get('cotizacion/cotizacion_detalle/{id}/{idCotizacion}', [CotizacionController::class,'details']);
