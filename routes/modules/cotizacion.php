<?php
 
use App\Http\Controllers\Cotizacion\Cotizacion2Controller;
use App\Http\Controllers\Cotizacion\CotizacionController;
use App\Http\Controllers\Cotizacion\CotizacionConfigController;
use Illuminate\Support\Facades\Route;

Route::get('cotizacion', [CotizacionController::class,'index']);
Route::post('cotizacion/save', [CotizacionController::class,'registrar'])->name('cotizacion.registrar');
Route::post('cotizacion/obtenerParametros', [CotizacionController::class,'obtenerParametros'])->name('cotizacion.obtenerParametros');
Route::post('cotizacion/obtenerClasificacion', [CotizacionController::class,'obtenerClasificacion'])->name('cotizacion.obtenerClasificacion');

Route::get('cotizacion/create', [Cotizacion2Controller::class,'create']);
Route::post('cotizacion/getSubNorma', [Cotizacion2Controller::class,'getSubNorma']);
Route::post('cotizacion/getDatos2', [Cotizacion2Controller::class,'getDatos2']);
Route::post('cotizacion/setCotizacion', [Cotizacion2Controller::class,'setCotizacion']); 
Route::get('cotizacion/fecha', [Cotizacion2Controller::class,'fecha']);
 
Route::post('cotizacion/obtenerHistorico', [CotizacionController::class,'obtenerHistorico'])->name('cotizacion.obtenerHistorico');
Route::post('cotizacion/duplicarCotizacion', [CotizacionController::class,'duplicarCotizacion'])->name('cotizacion.duplicarCotizacion');
    