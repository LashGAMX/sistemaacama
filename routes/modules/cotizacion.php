<?php

// use App\Http\Controllers\Cotizacion\Cotizacion2Controller;
use App\Http\Controllers\Cotizacion\CotizacionController;
use App\Http\Controllers\Cotizacion\SolicitudController;
use App\Models\Solicitud;
use Illuminate\Support\Facades\Route;
 

 
Route::group(['prefix' => 'cotizacion'], function () {
    Route::get('', [CotizacionController::class, 'index']);
    Route::get('create', [CotizacionController::class, 'create']);
    Route::post('getClientesIntermediarios',[CotizacionController::class, 'getClientesIntermediarios']); 
    Route::post('getDataCliente',[CotizacionController::class, 'getDataCliente']); 
    Route::post('getSucursal',[CotizacionController::class, 'getSucursal']); 
    Route::post('setCotizacion',[CotizacionController::class, 'setCotizacion']); 

    // Route::get('update/{id}', [CotizacionController::class, 'update']);
    // Route::post('getSubNorma', [CotizacionController::class, 'getSubNorma']); 
    // Route::post('getSubNormaId', [CotizacionController::class, 'getSubNormaId']);
    // Route::post('getNorma', [CotizacionController::class, 'getNorma']);
    // Route::post('getCliente', [CotizacionController::class, 'getCliente']);
    // Route::post('getDatos2', [CotizacionController::class, 'getDatos2']);
    // Route::post('setCotizacion', [CotizacionController::class, 'setCotizacion']); 
    // Route::post('getTomas', [CotizacionController::class, 'getTomas']);
    // Route::post('getCotizacionId', [CotizacionController::class, 'getCotizacionId']);
    // Route::post('getParametroCot', [CotizacionController::class, 'getParametroCot']);
    // Route::get('fecha', [CotizacionController::class, 'fecha']);
    // Route::post('getLocalidad', [CotizacionController::class,'getLocalidad']);
    // Route::post('precioMuestreo', [CotizacionController::class,'precioMuestreo']);
    // Route::post('cantidadGasolina', [CotizacionController::class,'cantidadGasolina']);
    // Route::post('updateCotizacion', [CotizacionController::class,'updateCotizacion']);
    // Route::get('duplicarCot/{idCot}', [CotizacionController::class, 'duplicar']);
    // Route::post('comprobarEdicion',[CotizacionController::class, 'comprobarEdicion']);
    
    // Route::post('getClienteInter', [CotizacionController::class, 'getClienteInter']); 
    // Route::post('clienteSucursal', [CotizacionController::class, 'clienteSucursal']); 
    // Route::post('DatosClienteSucursal', [CotizacionController::class, 'DatosClienteSucursal']);  


    // Route::get('buscarFecha/{inicio}/{fin}', [CotizacionController::class,'buscarFecha']);
    // Route::get('exportPdfOrden/{idCot}',[CotizacionController::class,'exportPdfOrden']); 
});

Route::group(['prefix' => 'cotizacion/solicitud'], function () {
    
    Route::get('',[SolicitudController::class,'index']);
    Route::get('create/{idCot}',[SolicitudController::class,'create']);

    Route::get('createSinCot', [SolicitudController::class, 'createSinCot']);
    Route::post('getDatos2', [SolicitudController::class, 'getDatos2']);

    Route::post('getSucursal',[SolicitudController::class,'getSucursal']);
    Route::post('getDatoIntermediario',[SolicitudController::class,'getDatoIntermediario']);
    Route::post('getDireccionReporte',[SolicitudController::class,'getDireccionReporte']);
    Route::post('setContacto',[SolicitudController::class,'setContacto']);
    Route::post('storeContacto',[SolicitudController::class,'storeContacto']);
    Route::post('getDataContacto',[SolicitudController::class,'getDataContacto']);
    Route::post('getPuntoMuestro',[SolicitudController::class,'getPuntoMuestro']);
    Route::post('getParametroSol',[SolicitudController::class,'getParametroSol']);

    Route::post('setSolicitud',[SolicitudController::class,'setSolicitud']);

    Route::get('buscarFecha/{inicio}/{fin}', [SolicitudController::class,'buscarFecha']); 

    Route::post('setSolicitudSinCot', [SolicitudController::class, 'setSolicitudSinCot']);
    Route::get('duplicarSol/{idCot}', [SolicitudController::class, 'duplicarSol']);


    Route::get('update/{idCot}',[SolicitudController::class,'update']);
    Route::post('getDataSolicitud',[SolicitudController::class,'getDataSolicitud']);
    Route::post('getReporteSir',[SolicitudController::class,'getReporteSir']);
    Route::post('setGenFolio',[SolicitudController::class,'setGenFolio']);

    Route::get('exportPdfOrden/{idOrden}',[SolicitudController::class,'exportPdfOrden']);
});
// Route::post('cotizacion/obtenerHistorico', [CotizacionController::class, 'obtenerHistorico'])->name('cotizacion.obtenerHistorico');
// Route::post('cotizacion/duplicarCotizacion', [CotizacionController::class, 'duplicarCotizacion'])->name('cotizacion.duplicarCotizacion');

