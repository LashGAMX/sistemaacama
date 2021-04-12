<?php

use App\Http\Controllers\Cotizacion\Cotizacion2Controller;
use App\Http\Controllers\Cotizacion\CotizacionController;
use App\Http\Controllers\Cotizacion\CotizacionConfigController;
use Illuminate\Support\Facades\Route;

// Route::get('cotizacion', [CotizacionController::class, 'index']);
Route::post('cotizacion/save', [CotizacionController::class, 'registrar'])->name('cotizacion.registrar');
Route::post('cotizacion/obtenerParametros', [CotizacionController::class, 'obtenerParametros'])->name('cotizacion.obtenerParametros');
Route::post('cotizacion/obtenerClasificacion', [CotizacionController::class, 'obtenerClasificacion'])->name('cotizacion.obtenerClasificacion');
Route::get('cotizacion/edit/{id}', [CotizacionController::class, 'edit']);

Route::get('cotizacion', [Cotizacion2Controller::class, 'index']);
Route::get('cotizacion/create', [Cotizacion2Controller::class, 'create']);
Route::get('cotizacion/update/{id}', [Cotizacion2Controller::class, 'update']);
Route::post('cotizacion/getSubNorma', [Cotizacion2Controller::class, 'getSubNorma']);
Route::post('cotizacion/getSubNormaId', [Cotizacion2Controller::class, 'getSubNormaId']); 
Route::post('cotizacion/getNorma', [Cotizacion2Controller::class, 'getNorma']);
Route::post('cotizacion/getCliente', [Cotizacion2Controller::class, 'getCliente']);
Route::post('cotizacion/getDatos2', [Cotizacion2Controller::class, 'getDatos2']);
Route::post('cotizacion/setCotizacion', [Cotizacion2Controller::class, 'setCotizacion']);
Route::post('cotizacion/getTomas', [Cotizacion2Controller::class, 'getTomas']);
Route::post('cotizacion/getCotizacionId', [Cotizacion2Controller::class, 'getCotizacionId']);
Route::post('cotizacion/getParametroCot', [Cotizacion2Controller::class, 'getParametroCot']);
Route::get('cotizacion/fecha', [Cotizacion2Controller::class, 'fecha']);

Route::post('cotizacion/obtenerHistorico', [CotizacionController::class, 'obtenerHistorico'])->name('cotizacion.obtenerHistorico');
Route::post('cotizacion/duplicarCotizacion', [CotizacionController::class, 'duplicarCotizacion'])->name('cotizacion.duplicarCotizacion');
