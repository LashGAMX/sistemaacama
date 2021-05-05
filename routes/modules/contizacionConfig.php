<?php

use App\Http\Controllers\Cotizacion\CotizacionConfigController;
use Illuminate\Support\Facades\Route;

Route::get('cotizacioncatalogos', [CotizacionConfigController::class,'index']);

//Route::get('cotizacion', [CotizacionController::class,'index']);

//Route::get('cotizacion/cotizacion_detalle/{id}', [CotizacionController::class,'show']);

//Route::get('cotizacion/cotizacion_detalle/{id}/{idCotizacion}', [CotizacionController::class,'details']);
