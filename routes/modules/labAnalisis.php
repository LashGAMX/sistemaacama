<?php

use App\Http\Controllers\Laboratorio\LabAnalisisController;
use Illuminate\Support\Facades\Route;
 

Route::group(['prefix' => 'laboratorio'], function () { 

 
    Route::group(['prefix' => 'analisis'], function () { 
        Route::get('captura', [LabAnalisisController::class, 'captura']);
        Route::get('captura2', [LabAnalisisController::class, 'captura2']);
        Route::post('getPendientes',[LabAnalisisController::class,'getPendientes']);

        Route::get('getPendientesAdmin',[LabAnalisisController::class,'getPendientesAdmin']);
        Route::post('getLote',[LabAnalisisController::class,'getLote']);
        Route::post('getDetalleLote',[LabAnalisisController::class,'getDetalleLote']);
        Route::post('setLote',[LabAnalisisController::class,'setLote']);
        Route::post('getMuestraSinAsignar', [LabAnalisisController::class,'getMuestraSinAsignar']);

    
        Route::post('setMuestraLote',[LabAnalisisController::class,'setMuestraLote']);
        Route::post('getCapturaLote',[LabAnalisisController::class,'getCapturaLote']);
        Route::post('getDetalleMuestra',[LabAnalisisController::class,'getDetalleMuestra']);
        Route::post('setDetalleMuestra',[LabAnalisisController::class,'setDetalleMuestra']);
        Route::post('setBitacora',[LabAnalisisController::class,'setBitacora']);
        Route::post('setDetalleGrasas',[LabAnalisisController::class,'setDetalleGrasas']);
        Route::post('setNormalidadAlc',[LabAnalisisController::class,'setNormalidadAlc']);
        Route::post('getHistorial',[LabAnalisisController::class,'getHistorial']);
        Route::post('metodoCortoColiformes',[LabAnalisisController::class,'metodoCortoColiformes']);
        Route::get('pruebaValores',[LabAnalisisController::class,'pruebaValores']);

        Route::post('setTipoDqo',[LabAnalisisController::class,'setTipoDqo']); 
        Route::post('setControlCalidad', [LabAnalisisController::class, 'setControlCalidad']);
        // Route::post('setLiberarTodo', [LabAnalisisController::class, 'setLiberarTodo']);
        Route::post('setLiberar', [LabAnalisisController::class, 'setLiberar']);
        Route::post('setObservacion', [LabAnalisisController::class, 'setObservacion']);
        Route::get('bitacora/impresion/{id}', [LabAnalisisController::class, 'exportBitacora']);
        Route::get('updateBitacoraNI', [LabAnalisisController::class, 'updateBitacoraNI']);

        //Funciones adicionales 
        Route::get('liberarMatraz', [LabAnalisisController::class, 'liberarMatraz']);
        Route::get('updateTituloBitacora/{id}', [LabAnalisisController::class, 'updateTituloBitacora']);
        Route::get('updateVolumenMetales', [LabAnalisisController::class, 'updateVolumenMetales']);
        Route::get('updateVolMuestraMetales/{id}', [LabAnalisisController::class, 'updateVolMuestraMetales']);
        Route::get('updateVolFinalMetales', [LabAnalisisController::class, 'updateVolFinalMetales']);
        Route::get('updateContadorLotes', [LabAnalisisController::class, 'updateContadorLotes']);
        Route::get('replicarDatosEcoli', [LabAnalisisController::class, 'replicarDatosEcoli']);
        Route::get('updateDefaultLoteMetales', [LabAnalisisController::class, 'updateDefaultLoteMetales']);
        Route::get('updateBitacoraFolioMetales', [LabAnalisisController::class, 'updateBitacoraFolioMetales']);
        Route::get('updateMicroGramoMetales', [LabAnalisisController::class, 'updateMicroGramoMetales']);
        Route::post('getUltimoLote', [LabAnalisisController::class, 'getUltimoLote']);
        Route::get('matracesDuplicados', [LabAnalisisController::class, 'matracesDuplicados']);
        Route::get('crisolDuplicado/{id}', [LabAnalisisController::class, 'crisolDuplicado']);
        Route::get('pruebaRandom', [LabAnalisisController::class, 'pruebaRandom']);
        Route::get('updateMatrazDuplicado', [LabAnalisisController::class, 'updateMatrazDuplicado']);
        Route::get('updatePruebaConfirmativaCol', [LabAnalisisController::class, 'updatePruebaConfirmativaCol']);
        Route::get('updateDetalleDbo', [LabAnalisisController::class, 'updateDetalleDbo']);
        Route::get('updateCrisolDuplicado/{id}', [LabAnalisisController::class, 'updateCrisolDuplicado']);
        Route::get('updateCrisolDuplicado2', [LabAnalisisController::class, 'updateCrisolDuplicado2']);
        Route::get('regresarMuestrasDbo/{id}', [LabAnalisisController::class, 'regresarMuestrasDbo']);
        Route::post('getDetalleElegido', [LabAnalisisController::class, 'getDetalleElegido']);
        Route::post('getImagenMuestra', [LabAnalisisController::class, 'getImagenMuestra']);
        Route::post('eliminarMuestra', [LabAnalisisController::class, 'eliminarMuestra']);
        Route::get('updateLoteDetalle/{fi}/{ff}/{idp}/{ida}', [LabAnalisisController::class, 'updateLoteDetalle']);
        Route::post('setLiberarTodo', [LabAnalisisController::class, 'setLiberarTodo']); 
        Route::post('PorcentaejeE', [LabAnalisisController::class, 'PorcentaejeE']); 
        Route::post('eliminarLote', [LabAnalisisController::class, 'eliminarLote']); 




    });
   
});

  