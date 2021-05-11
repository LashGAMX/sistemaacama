<?php

// use App\Http\Controllers\Cotizacion\Cotizacion2Controller;
use App\Http\Controllers\Cotizacion\CotizacionController;
use App\Http\Controllers\Cotizacion\SolicitudController;
use Illuminate\Support\Facades\Route;
 
// Route::get('cotizacion', [CotizacionController::class, 'index']);
// Route::post('cotizacion/save', [CotizacionController::class, 'registrar'])->name('cotizacion.registrar');
// Route::post('cotizacion/obtenerParametros', [CotizacionController::class, 'obtenerParametros'])->name('cotizacion.obtenerParametros');
// Route::post('cotizacion/obtenerClasificacion', [CotizacionController::class, 'obtenerClasificacion'])->name('cotizacion.obtenerClasificacion');
// Route::get('cotizacion/edit/{id}', [CotizacionController::class, 'edit']);

 
Route::group(['prefix' => 'cotizacion'], function () {
    Route::get('', [CotizacionController::class, 'index']);
    Route::get('create', [CotizacionController::class, 'create']);
    Route::get('update/{id}', [CotizacionController::class, 'update']);
    Route::post('getSubNorma', [CotizacionController::class, 'getSubNorma']); 
    Route::post('getSubNormaId', [CotizacionController::class, 'getSubNormaId']);
    Route::post('getNorma', [CotizacionController::class, 'getNorma']);
    Route::post('getCliente', [CotizacionController::class, 'getCliente']);
    Route::post('getDatos2', [CotizacionController::class, 'getDatos2']);
    Route::post('setCotizacion', [CotizacionController::class, 'setCotizacion']);
    Route::post('getTomas', [CotizacionController::class, 'getTomas']);
    Route::post('getCotizacionId', [CotizacionController::class, 'getCotizacionId']);
    Route::post('getParametroCot', [CotizacionController::class, 'getParametroCot']);
    Route::get('fecha', [CotizacionController::class, 'fecha']);
    Route::post('getLocalidad', [CotizacionController::class,'getLocalidad']);
    Route::post('precioMuestreo', [CotizacionController::class,'precioMuestreo']);
    Route::post('cantidadGasolina', [CotizacionController::class,'cantidadGasolina']);
    Route::post('updateCotizacion', [CotizacionController::class,'updateCotizacion']);
    Route::get('duplicar', [CotizacionController::class, 'duplicar']); 
});

Route::group(['prefix' => 'cotizacion/solicitud'], function () {
    
    Route::get('',[SolicitudController::class,'index']);
    Route::get('create',[SolicitudController::class,'create']);
    Route::post('getSucursal',[SolicitudController::class,'getSucursal']);
    Route::post('getDatoIntermediario',[SolicitudController::class,'getDatoIntermediario']);
    Route::post('getDireccionReporte',[SolicitudController::class,'getDireccionReporte']);
    Route::post('setContacto',[SolicitudController::class,'setContacto']);
});
// Route::post('cotizacion/obtenerHistorico', [CotizacionController::class, 'obtenerHistorico'])->name('cotizacion.obtenerHistorico');
// Route::post('cotizacion/duplicarCotizacion', [CotizacionController::class, 'duplicarCotizacion'])->name('cotizacion.duplicarCotizacion');
