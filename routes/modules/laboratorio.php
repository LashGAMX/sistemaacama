<?php

use App\Http\Controllers\Laboratorio\LaboratorioController;
use App\Http\Controllers\Laboratorio\CurvaController;
use App\Http\Controllers\Laboratorio\FqController;
use App\Http\Controllers\Laboratorio\MbController;
use App\Http\Controllers\Laboratorio\MetalesController;
use App\Http\Controllers\laboratorio\MicroController;
use App\Models\Laboratorio;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'laboratorio'], function () {

    //! Grupo de metales
    Route::group(['prefix' => 'metales'], function () {
        //? Modulo Analisis -  Solo visualizar analisis pendientes
        Route::get('analisis', [MetalesController::class, 'analisis']);

        //? Modulo Observacion - Agregar observaciones a analisis
        Route::get('observacion', [MetalesController::class, 'observacion']);
        Route::post('getObservacionanalisis', [MetalesController::class, 'getObservacionanalisis']);
        Route::post('aplicarObservacion', [MetalesController::class, 'aplicarObservacion']);

        //? Modulo captura de datos analisis
        Route::get('tipoAnalisis', [MetalesController::class, 'tipoAnalisis']);
        Route::get('captura', [MetalesController::class, 'captura']);
        Route::post('getDataCaptura', [MetalesController::class, 'getDataCaptura']);
        Route::post('setControlCalidad', [MetalesController::class, 'setControlCalidad']);
        Route::post('liberarMuestraMetal', [MetalesController::class, 'liberarMuestraMetal']);

        //? Modulo Lote - Creación  de lotes
        Route::get('lote', [MetalesController::class, 'lote']);
        Route::post('createLote', [MetalesController::class, 'createLote']);
        Route::post('buscarLote', [MetalesController::class, 'buscarLote']);
        Route::post('getDatalote', [MetalesController::class, 'getDatalote']);
        Route::get('asgnarMuestraLote/{id}', [MetalesController::class, 'asgnarMuestraLote']);
        Route::post('muestraSinAsignar', [MetalesController::class, 'muestraSinAsignar']);
        Route::post('asignarMuestraLote', [MetalesController::class, 'asignarMuestraLote']);
        Route::post('getMuestraAsignada', [MetalesController::class, 'getMuestraAsignada']);
        Route::post('delMuestraLote', [MetalesController::class, 'getMuestraAsignada']);

        //Route::get('analisis/datos', [MetalesController::class, 'analisisDatos']);
        Route::post('operacion', [MetalesController::class, 'operacion']);
        Route::post('lote/equipo/guardarDatosGenerales', [MetalesController::class, 'guardarDatosGenerales']);
        Route::post('getDataLote/plantillaPredeterminada', [MetalesController::class, 'getPlantillaPred']);
        Route::post('lote/procedimiento/busquedaFiltros', [MetalesController::class, 'busquedaFiltros']);
        //Almacena el texto en la table reportes, campo Texto, el texto introducido en el editor de texto > Procedimiento/Validación
        Route::post('lote/procedimiento', [MetalesController::class, 'guardarTexto']);
    });

    // todo Modulo FisicoQuimicos
    Route::group(['prefix' => 'fq'], function () {
        //? Modulo Analisis -  Solo visualizar analisis pendientes
        Route::get('analisis', [FqController::class, 'analisis']);

        //? Modulo Observacion - Agregar observaciones a analisis
        Route::get('observacion', [FqController::class, 'observacion']);
        Route::post('getObservacionanalisis', [FqController::class, 'getObservacionanalisis']);
        Route::post('aplicarObservacion', [FqController::class, 'aplicarObservacion']);

        //? Modulo Lote - Creación  de lotes
        Route::get('lote', [FqController::class, 'lote']);
        Route::post('createLote', [FqController::class, 'createLote']);
        Route::post('buscarLote', [FqController::class, 'buscarLote']);
        Route::post('getDatalote', [FqController::class, 'getDatalote']);
        Route::get('asgnarMuestraLote/{id}', [FqController::class, 'asgnarMuestraLote']);
        Route::post('muestraSinAsignar', [FqController::class, 'muestraSinAsignar']);
        Route::post('asignarMuestraLote', [FqController::class, 'asignarMuestraLote']);
        Route::post('getMuestraAsignada', [FqController::class, 'getMuestraAsignada']);
        Route::post('delMuestraLote', [FqController::class, 'delMuestraLote']);

        //? Modulo captura de datos analisis
        Route::get('tipoAnalisis', [FqController::class, 'tipoAnalisis']);

        Route::get('capturaEspectro', [FqController::class, 'capturaEspectro']);
        Route::post('getDataCapturaEspectro', [FqController::class, 'getDataCapturaEspectro']);
        Route::post('getDetalleEspectro', [FqController::class, 'getDetalleEspectro']);
        Route::post('operacionEspectro', [FqController::class, 'operacionEspectro']);

        Route::get('capturaGA', [FqController::class, 'capturaGA']);
        Route::post('getDataCapturaGA', [FqController::class, 'getDataCapturaGA']);
        Route::post('getDetalleGA', [FqController::class, 'getDetalleGA']);
        Route::post('operacionGA', [FqController::class, 'operacionGA']);
        
        // Route::get('captura/exportPdfCapturaGA/{idLote}', [FqController::class, 'exportPdfCapturaGA']);
        // Route::post('getDataCapturaEspectro', [FqController::class, 'getDataCapturaEspectro']);
        
        Route::post('setControlCalidad', [FqController::class, 'setControlCalidad']);
        Route::post('liberarMuestraMetal', [FqController::class, 'liberarMuestraMetal']);
        Route::post('getDataLote/plantillaPredeterminada', [FqController::class, 'getPlantillaPred']);
        Route::post('lote/guardarDatos', [FqController::class, 'guardarDatos']);
        Route::post('lote/procedimiento', [FqController::class, 'guardarTexto']);

        //Ruta temporal 
        Route::get('captura/exportPdfCapturaGA', [FqController::class, 'exportPdfCapturaGA']);

        //? Export PDF
        Route::get('captura/exportPdfCapturaGA/{idLote}', [FqController::class, 'exportPdfCapturaGA']);
    });
        // todo Modulo MicroBiologia
        Route::group(['prefix' => 'micro'], function () {
            //? Modulo Analisis -  Solo visualizar analisis pendientes
            Route::get('analisis', [MbController::class, 'analisis']);
    
            //? Modulo Observacion - Agregar observaciones a analisis
            Route::get('observacion', [MbController::class, 'observacion']);
            Route::post('getObservacionanalisis', [MbController::class, 'getObservacionanalisis']);
            Route::post('aplicarObservacion', [MbController::class, 'aplicarObservacion']);
    
            //? Modulo Lote - Creación  de lotes
            Route::get('lote', [MbController::class, 'lote']); 
            Route::post('createLote', [MbController::class, 'createLote']);
            Route::post('buscarLote', [MbController::class, 'buscarLote']);
            Route::post('getDatalote', [MbController::class, 'getDatalote']);
            Route::get('asgnarMuestraLote/{id}', [MbController::class, 'asgnarMuestraLote']);
            Route::post('muestraSinAsignar', [MbController::class, 'muestraSinAsignar']);
            Route::post('asignarMuestraLote', [MbController::class, 'asignarMuestraLote']);
            Route::post('getMuestraAsignada', [MbController::class, 'getMuestraAsignada']);
            Route::post('delMuestraLote', [MbController::class, 'delMuestraLote']);
    
            //? Modulo captura de datos analisis
            Route::get('tipoAnalisis', [MbController::class, 'tipoAnalisis']);
    
            Route::get('capturaEspectro', [MbController::class, 'capturaEspectro']);
            Route::post('getDataCapturaEspectro', [MbController::class, 'getDataCapturaEspectro']);
            
            Route::post('setControlCalidad', [MbController::class, 'setControlCalidad']);
            Route::post('liberarMuestraMetal', [MbController::class, 'liberarMuestraMetal']);
            Route::post('getDataLote/plantillaPredeterminada', [MbController::class, 'getPlantillaPred']);
            Route::post('lote/guardarDatos', [MbController::class, 'guardarDatos']);
            Route::post('lote/procedimiento', [MbController::class, 'guardarTexto']);
 

            //RUTA PARA VISTA CAPTURA DE MICROBIOLOGÍA
            Route::get('capturaMicro', [MbController::class, 'capturaMicro']);
            
            //Ruta temporal de prueba
            Route::get('captura/exportPdfCaptura', [MbController::class, 'exportPdfCaptura']);
        });

    //? Módulo curva - Creación de curva
    Route::get('asignar', [MetalesController::class, 'asignar']);
    Route::get('curva', [CurvaController::class, 'index']);
    Route::post('promedio', [CurvaController::class, 'promedio']);
    Route::post('guardar', [CurvaController::class, 'guardar']);
    Route::post('formula', [CurvaController::class, 'formula']);
    Route::post('buscar', [CurvaController::class, 'buscar']);
    Route::post('createStd', [CurvaController::class, 'createStd']);
    Route::post('getParametro', [CurvaController::class, 'getParametro']);
    Route::post('setCalcular', [CurvaController::class, 'setCalcular']);
    Route::post('setConstantes', [CurvaController::class, 'setConstantes']);


    //? PDF
    Route::get('captura/exportPdfCaptura/{idLote}', [LaboratorioController::class, 'exportPdfCaptura']);
    /*Route::get('captura/exportPdfCaptura/{formulaTipo}/{numeroMuestra}/{idLote}', [LaboratorioController::class, 'exportPdfCaptura'])->where('numeroMuestra', '(.*)');*/
});
