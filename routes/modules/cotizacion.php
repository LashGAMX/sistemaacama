<?php

// use App\Http\Controllers\Cotizacion\Cotizacion2Controller;
use App\Http\Controllers\Cotizacion\CotizacionController;
use App\Http\Controllers\Cotizacion\SolicitudController;
use App\Models\Solicitud;
use Illuminate\Support\Facades\Route;
 

 
Route::group(['prefix' => 'cotizacion'], function () { 
    Route::get('', [CotizacionController::class, 'index']);
    Route::get('data', [CotizacionController::class, 'getCotizacionesData']);
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
    Route::get('exportPdfCotizacion/{idCot}',[CotizacionController::class,'exportPdfCotizacion']); 


    
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
    Route::get('/GetSolicitudes',[SolicitudController::class,'GetSolicitudes']);


    Route::get('/Solicitudes',[SolicitudController::class,'Solicitudes']);
    Route::post('Buscarsolicitud',[SolicitudController::class,'Buscarsolicitud']);


    Route::get('/solicitudes/data', [CotizacionController::class, 'solicitudesData']);

    Route::get('verimagen',[SolicitudController::class,'verimagen']);
    Route::get('migrarDatos',[SolicitudController::class,'migrarDatos']);
    Route::get('extraerTablaCotizacion',[SolicitudController::class,'extraerTablaCotizacion']);
    Route::get('arreglolotes',[SolicitudController::class,'arreglolotes']);




    Route::post('getSolicitudes',[SolicitudController::class,'getSolicitudes']);

    
    Route::post('setOrdenServicio',[SolicitudController::class,'setOrdenServicio']);
    Route::post('setPuntoMuestreo',[SolicitudController::class,'setPuntoMuestreo']);
    Route::post('getPuntoMuestreoSol',[SolicitudController::class,'getPuntoMuestreoSol']);
    Route::post('editPuntoMuestreo',[SolicitudController::class,'editPuntoMuestreo']);
    Route::post('deletePuntoSol',[SolicitudController::class,'deletePuntoSol']);
    Route::post('getParametrosSelected',[SolicitudController::class,'getParametrosSelected']); 
    Route::post('updateParametroSol',[SolicitudController::class,'updateParametroSol']);
    Route::post('setCreateOrden',[SolicitudController::class,'setCreateOrden']);
    Route::post('addParametro',[SolicitudController::class,'addParametro']);
    Route::post('getClienteSol',[SolicitudController::class,'getClienteSol']);

    Route::post('setGenFolio',[SolicitudController::class,'setGenFolio']);
    Route::post('setGenFolioSol',[SolicitudController::class,'setGenFolioSol']);
    Route::get('duplicarSolicitud/{id}',[SolicitudController::class,'duplicarSolicitud']);

    Route::get('exportPdfOrden/{idOrden}',[SolicitudController::class,'exportPdfOrden']);

    Route::post('cancelarOrden',[SolicitudController::class,'cancelarOrden']);

    Route::post('Tablasolicitud',[SolicitudController::class,'Tablasolicitud']);
    // Route::get('cancelarOrdenMasiva',[SolicitudController::class,'cancelarOrdenMasiva']);
    Route::get('migrarFotos',[SolicitudController::class,'migrarFotos']);
    
    Route::get('Consultas',[SolicitudController::class,'Consultas']);
    Route::get('UpdateAnalista',[SolicitudController::class,'UpdateAnalista']);
    Route::get('DesliberarMasivo',[SolicitudController::class,'DesliberarMasivo']);
    Route::get('RegresarMasivoCuadrodeAsignacion',[SolicitudController::class,'RegresarMasivoCuadrodeAsignacion']);
    Route::get('RejecutarParametro',[SolicitudController::class,'RejecutarParametro']);

    


    

    

});

