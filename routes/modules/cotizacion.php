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
    Route::post('getNormas',[CotizacionController::class, 'getNormas']); 
    Route::post('getSubNormas',[CotizacionController::class, 'getSubNormas']); 
    Route::post('setDatoGeneral',[CotizacionController::class, 'setDatoGeneral']); 
    Route::post('getParametrosNorma',[CotizacionController::class, 'getParametrosNorma']); 
    Route::post('getParametrosSelected',[CotizacionController::class, 'getParametrosSelected']); 
    Route::post('getFrecuenciaMuestreo',[CotizacionController::class, 'getFrecuenciaMuestreo']); 
    Route::post('setPrecioMuestreo',[CotizacionController::class, 'setPrecioMuestreo']); 
    Route::post('getDatosCotizacion',[CotizacionController::class, 'getDatosCotizacion']); 
    Route::post('updateParametroCot',[CotizacionController::class, 'updateParametroCot']); 


    Route::post('setCotizacion',[CotizacionController::class, 'setCotizacion']); 
    Route::post('setGenFolio',[CotizacionController::class, 'setGenFolio']); 
    Route::get('update/{id}', [CotizacionController::class, 'update']);
    Route::get('show/{id}', [CotizacionController::class, 'show']);
    Route::post('getDataUpdate',[CotizacionController::class, 'getDataUpdate']);
    Route::post('comprobarEdicion',[CotizacionController::class, 'comprobarEdicion']);
    Route::post('getLocalidad', [CotizacionController::class,'getLocalidad']);
    Route::post('cantidadGasolina', [CotizacionController::class,'cantidadGasolina']);
    Route::post('setPrecioCotizacion', [CotizacionController::class,'setPrecioCotizacion']);
    Route::get('duplicarCot/{idCot}', [CotizacionController::class, 'duplicar']);
    Route::get('exportPdfOrden/{idCot}',[CotizacionController::class,'exportPdfOrden']); 
    
});

Route::group(['prefix' => 'cotizacion/solicitud'], function () {
    
    Route::get('',[SolicitudController::class,'index']);
    Route::get('create/{id}',[SolicitudController::class,'create']);
    Route::get('create',[SolicitudController::class,'createOrden']);
    Route::get('updateOrden/{id}',[SolicitudController::class,'updateOrden']);
    Route::post('getDataUpdate',[SolicitudController::class,'getDataUpdate']);
    Route::post('buscar',[SolicitudController::class,'buscar']);  

    Route::post('getDatoIntermediario',[SolicitudController::class,'getDatoIntermediario']);
    Route::post('getDataCotizacion',[SolicitudController::class,'getDataCotizacion']);
    Route::post('getClienteRegistrado',[SolicitudController::class,'getClienteRegistrado']);
    Route::post('getSucursalCliente',[SolicitudController::class,'getSucursalCliente']);
    Route::post('getDireccionReporte',[SolicitudController::class,'getDireccionReporte']);
    Route::post('setContacto',[SolicitudController::class,'setContacto']);
    Route::post('getDataContacto',[SolicitudController::class,'getDataContacto']);
    Route::post('storeContacto',[SolicitudController::class,'storeContacto']);
    Route::post('getPuntoMuestro',[SolicitudController::class,'getPuntoMuestro']);
    Route::post('setSolicitud',[SolicitudController::class,'setSolicitud']);
    Route::get('createSinCot', [SolicitudController::class, 'createSinCot']);
    Route::post('setSolicitudSinCot',[SolicitudController::class,'setSolicitudSinCot']);
    Route::post('setOrdenServicio',[SolicitudController::class,'setOrdenServicio']);
    Route::post('setPuntoMuestreo',[SolicitudController::class,'setPuntoMuestreo']);
    Route::post('getPuntoMuestreoSol',[SolicitudController::class,'getPuntoMuestreoSol']);
    Route::post('editPuntoMuestreo',[SolicitudController::class,'editPuntoMuestreo']);
    Route::post('deletePuntoSol',[SolicitudController::class,'deletePuntoSol']);
    Route::post('getParametrosSelected',[SolicitudController::class,'getParametrosSelected']);
    Route::post('updateParametroSol',[SolicitudController::class,'updateParametroSol']);
    Route::post('setCreateOrden',[SolicitudController::class,'setCreateOrden']);
    Route::post('addParametro',[SolicitudController::class,'addParametro']);
 
    Route::post('setGenFolio',[SolicitudController::class,'setGenFolio']);
    Route::post('setGenFolioSol',[SolicitudController::class,'setGenFolioSol']);
    Route::get('duplicarSolicitud/{id}',[SolicitudController::class,'duplicarSolicitud']);

    Route::get('exportPdfOrden/{idOrden}',[SolicitudController::class,'exportPdfOrden']);
});
