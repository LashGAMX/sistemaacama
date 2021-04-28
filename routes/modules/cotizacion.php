<?php

// use App\Http\Controllers\Cotizacion\Cotizacion2Controller;
use App\Http\Controllers\Cotizacion\CotizacionController;
use App\Http\Controllers\Cotizacion\CotizacionConfigController;
use Illuminate\Support\Facades\Route;

// Route::get('cotizacion', [CotizacionController::class, 'index']);
// Route::post('cotizacion/save', [CotizacionController::class, 'registrar'])->name('cotizacion.registrar');
// Route::post('cotizacion/obtenerParametros', [CotizacionController::class, 'obtenerParametros'])->name('cotizacion.obtenerParametros');
// Route::post('cotizacion/obtenerClasificacion', [CotizacionController::class, 'obtenerClasificacion'])->name('cotizacion.obtenerClasificacion');
// Route::get('cotizacion/edit/{id}', [CotizacionController::class, 'edit']);

Route::get('cotizacion', [CotizacionController::class, 'index']);
Route::get('cotizacion/create', [CotizacionController::class, 'create']);
Route::get('cotizacion/update/{id}', [CotizacionController::class, 'update']);
Route::post('cotizacion/getSubNorma', [CotizacionController::class, 'getSubNorma']);
Route::post('cotizacion/getSubNormaId', [CotizacionController::class, 'getSubNormaId']);
Route::post('cotizacion/getNorma', [CotizacionController::class, 'getNorma']);
Route::post('cotizacion/getCliente', [CotizacionController::class, 'getCliente']);
Route::post('cotizacion/getDatos2', [CotizacionController::class, 'getDatos2']);
Route::post('cotizacion/setCotizacion', [CotizacionController::class, 'setCotizacion']);
Route::post('cotizacion/getTomas', [CotizacionController::class, 'getTomas']);
Route::post('cotizacion/getCotizacionId', [CotizacionController::class, 'getCotizacionId']);
Route::post('cotizacion/getParametroCot', [CotizacionController::class, 'getParametroCot']);
Route::get('cotizacion/fecha', [CotizacionController::class, 'fecha']);
Route::post('cotizacion/getLocalidad', [CotizacionController::class,'getLocalidad']);
Route::post('cotizacion/precioMuestreo', [CotizacionController::class,'precioMuestreo']);
Route::post('cotizacion/cantidadGasolina', [CotizacionController::class,'cantidadGasolina']);
Route::post('cotizacion/updateCotizacion', [CotizacionController::class,'updateCotizacion']);
Route::get('cotizacion/duplicar', [CotizacionController::class, 'duplicar']);
// Route::post('cotizacion/obtenerHistorico', [CotizacionController::class, 'obtenerHistorico'])->name('cotizacion.obtenerHistorico');
// Route::post('cotizacion/duplicarCotizacion', [CotizacionController::class, 'duplicarCotizacion'])->name('cotizacion.duplicarCotizacion');
